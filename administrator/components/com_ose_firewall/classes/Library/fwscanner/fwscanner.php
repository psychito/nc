<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC')) {
    die('Direct Access Not Allowed');
}

class oseFirewallScanner
{
    public $ip = null;
    private $ip32 = null;
    public $ipStatus = null;
    private $url = null;
    private $domain = null;
    private $referer = null;
    private $tags = null;
    private $target = null;
    private $converters = array();
    private $allowExts = array();
    private $logtime = null;
    protected $db = null;
    private $json = null;
    protected $tolerance = 5;
    protected $threshold = 35;
    protected $blockIP = true;
    protected $aclid = null;
    protected $scanGoogleBots = true;
    protected $scanYahooBots = true;
    protected $scanMsnBots = true;
    public $devMode = false;
    protected $slient_max_att = 10;
    protected $banpagetype = false;
    protected $sfspam = false;
    protected $sfs_confidence = 30;
    protected $visits = 0;
    protected $blockMode = 1;
    protected $replaced = array();
    protected $silentMode = 1;
    protected $detected = '';
    protected $blockCountry = '';
    protected $spamEmail = false;
    protected $gaSecret = false;
    protected $blockNow = false;
    protected $receiveEmail = false;

    public function __construct()
    {
        $this->initSetting();
    }

    protected function initSetting()
    {
        oseFirewall::callLibClass('convertviews', 'convertviews');
        $this->setDBO();
        $this->setTargetURL();
        $this->setReferer();
        $this->setClientIP();
        $this->setConfig();
        $this->checkAttackTypesNum();
        oseFirewall::loadBackendBasicFunctions();
    }

    protected function setConfig()
    {
        $query = 'SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` IN ("seo", "scan", "addons", "advscan", "country", "admin")';
        $this->db->setQuery($query);
        $results = $this->db->loadArrayList();
        foreach ($results as $result) {
            $key = $result['key'];
            if (in_array($key, array('threshold', 'slient_max_att', 'sfs_confidence'))) {
                $this->$key = (int)$result['value'];
                if ($this->threshold == 0) {
                    $this->threshold = 35;
                }
            } else {
                $this->$key = $result['value'];
            }
        }
    }

    protected function setTargetURL()
    {
        $query = (!empty($_SERVER['QUERY_STRING'])) ? str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->url = ((!empty($_SERVER['HTTPS'])) ? "https://" : "http://") . $query;
    }

    protected function setReferer()
    {
        if (isset ($_SERVER['HTTP_REFERER'])) {
            $this->referer = $_SERVER['HTTP_REFERER'];
        } else {
            $this->referer = 'N/A';
        }
    }

    protected function setClientIP()
    {
        oseFirewall::callLibClass('ipmanager', 'ipmanager');
        $ipmanager = new oseFirewallIpManager($this->db);
        $this->ip = $ipmanager->getIP();
        $this->ip32 = $ipmanager->getIPLong(true);
        $this->ipStatus = $ipmanager->getIPStatus();
        $this->aclid = $ipmanager->getACLID();
    }

    protected function setDBO()
    {
        $this->db = oseFirewall::getDBO();
    }

    public function hackScan()
    {
        $continue = $this->checkContinue();
        if ($continue === false) {
            if (class_exists('oseDBO')) {
                $this->db->__destruct();
            }
            return;
        } else if ($this->ipStatus == 1) {
            $blockMode = $this->getblockIP();
            $notified = $this->getNotified();
            switch ($blockMode) {
                case 1:
                    $this->sendEmail('blacklisted', $notified);
                    break;
                case 0:
                    $this->sendEmail('403blocked', $notified);
                    break;
            }
            if (!empty($_POST['googleAuthCode'])) {
                $flag = $this->verifyGA();
                if ($flag == true) {
                    $this->updateStatus(3);
                    print_r(1);
                    exit;
                } else {
                    print_r(0);
                    exit;
                }
            } else {
                $this->showBanPage();
            }
        } else {
            $this->scanAttack();
        }
        $this->db->closeDBO();
    }

    public function manageBannedAdminsFW6()
    {
        $flag = $this->verifyGA();
        if ($flag == true) {
            $this->updateStatus(3);
            print_r(1);
            exit;
        } else {
            print_r(0);
            exit;
        }

    }

    public function verifyGA()
    {
        $googleAuth = oseFirewall::getConfiguration('admin');
        $secret = $googleAuth['data']['gaSecret'];
        if (!empty($secret)) {
            require_once(OSE_FWFRAMEWORK . ODS . 'googleAuthenticator' . ODS . 'class_gauthenticator.php');
            $gauthenticator = new CentroraGoogleAuthenticator();
            $otp = trim($_POST ['googleAuthCode']);
            require_once(OSE_FWFRAMEWORK . ODS . 'googleAuthenticator' . ODS . 'class_base32.php');
            $match = $gauthenticator->verify($secret, $otp);
            return $match;
        } else {
            return false;
        }

    }

    protected function filterAttack($type)
    {
        $attrList = array("`detattacktype`.`attacktypeid` AS `attacktypeid`", "`detcontdetail`.`rule_id` AS `rule_id`", "`vars`.`keyname` AS `keyname`", "`detcontent`.`content` AS `content`");
        $sql = convertViews::convertAttackmap($attrList);
        $query = $sql . 'WHERE `acl`.`id` = ' . (int)$this->aclid . ' GROUP BY `rule_id` ORDER BY LENGTH(`content`) DESC';
        $this->db->setQuery($query);
        $results = $this->db->loadObjectList();

        foreach ($results as $result) {
            if (!empty($result->keyname) && !empty($result->rule_id)) {
                if ($type === 'ad') {
                    $this->convertL2Attack($result->rule_id, $result->keyname);
                } else {
                    $this->convertL1Attack($result->keyname, $result->content);
                }
            }
        }
    }

    protected function convertL1Attack($keyname, $content)
    {
        $tmp = array(0=>'', 1=>'');
        if (isset($_GET[$keyname])) {
            $tmp[0] = 'GET';
            $tmp[1] = $keyname;
        } else if (isset($_POST[$keyname])) {
            $tmp[0] = 'POST';
            $tmp[1] = $keyname;
        }
        if (!empty($_GET) && isset($tmp[1]) && isset($_GET[$tmp[1]]) && !empty($_GET[$tmp[1]])) {
	        $this->replaced['original']['GET'][$tmp[1]] = $_GET[$tmp[1]];
	        $_GET[$tmp[1]] = NULL;
	        $this->replaced['filtered']['GET'][$tmp[1]] = $_GET[$tmp[1]];
        } else if(!empty($_POST)  && isset($tmp[1]) && isset($_POST[$tmp[1]]) && !empty($_POST[$tmp[1]])) {
	        $this->replaced['original']['POST'][$tmp[1]] = $_POST[$tmp[1]];
	        $_POST[$tmp[1]] = NULL;
	        $this->replaced['filtered']['POST'][$tmp[1]] = $_POST[$tmp[1]];
        }
    }
    
    protected function removeMalUAgent()
    {
    	$this->replaced['original']['USERAGENT'][] = substr($_SERVER['HTTP_USER_AGENT'], 0, 50).'...';
    	$this->replaced['filtered']['USERAGENT'][] = $_SERVER['HTTP_USER_AGENT'] = '';
    }

    protected function convertL2Attack($rule_id, $keyname)
    {
        $tmp = explode('.', $keyname);
        $tmp[0] = strtoupper($tmp[0]);
        if (!empty($tmp) && is_array($tmp)) {
            switch ($tmp[0]) {
                case 'GET':
                    if (!empty($_GET[$tmp[1]])) {
                        $this->replaced['original']['GET'][$tmp[1]] = $_GET[$tmp[1]];
                        $_GET[$tmp[1]] = $this->filterVariable($_GET[$tmp[1]], $rule_id);
                        $this->replaced['filtered']['GET'][$tmp[1]] = $_GET[$tmp[1]];
                    }
                    break;
                case 'POST':
                    if (!empty($_POST[$tmp[1]])) {
                        $this->replaced['original']['POST'][$tmp[1]] = $_POST[$tmp[1]];
                        $_POST[$tmp[1]] = $this->filterVariable($_POST[$tmp[1]], $rule_id);
                        $this->replaced['filtered']['POST'][$tmp[1]] = $_POST[$tmp[1]];
                    }
                    break;
            }
        }

    }

    protected function filterVariable($var, $rule_id)
    {
        $pattern = '/' . $this->getPattern($rule_id) . '/ims';
        $var1 = preg_replace($pattern, '', $var);
        return $var1;
    }

    protected function getPattern($rule_id)
    {
        $query = "SELECT `filter` FROM `#__osefirewall_advancerules` WHERE `id` = " . (int)$rule_id;
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        return $result->filter;
    }

    protected function cleanVariable($var)
    {
        return html_entity_decode(urldecode($var));
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
    }

    protected function updateStatus($status)
    {
        $varValues = array(
            'status' => (int)$status
        );
        $result = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
        return (boolean)$result;
    }

    protected function getScore()
    {
        $attrList = array(" `acl`.`score` AS `score`");
        $sql = convertViews::convertAclipmap($attrList);
        $query = $sql . " WHERE `acl`.`id` = " . (int)$this->aclid;
        $this->db->setQuery($query);
        $result = (object)($this->db->loadResult());
        return (isset($result->score)) ? (int)$result->score : 0;
    }

    public function getVisits()
    {
        $attrList = array(" `acl`.`visits` AS `visits`");
        $sql = convertViews::convertAclipmap($attrList);
        $query = $sql . " WHERE `acl`.`id` = " . (int)$this->aclid;
        $this->db->setQuery($query);
        $result = (object)($this->db->loadResult());
        return (isset($result->visits)) ? (int)$result->visits : 0;

    }

    protected function getNotified()
    {
        $attrList = array(" `acl`.`notified` AS `notified`");
        $sql = convertViews::convertAclipmap($attrList);
        $query = $sql . " WHERE `acl`.`id` = " . (int)$this->aclid;
        $this->db->setQuery($query);
        $result = (object)($this->db->loadResult());
        return (isset($result->notified)) ? (int)$result->notified : 0;
    }

    protected function updateVisits()
    {
        $query = "UPDATE `#__osefirewall_acl` SET `visits` = (`visits` +1) WHERE `id` = " . (int)$this->aclid;
        $this->db->setQuery($query);
        $result = $this->db->query();
        return (boolean)$result;
    }

    protected function getDateTime()
    {
        oseFirewall::loadDateClass();
        $time = new oseDatetime();
        return $time->getDateTime();
    }

    public function addACLRule($status, $score)
    {
        $page_id = $this->addPages();
        $referer_id = $this->addReferer();
        if (empty ($this->aclid)) {
            $varValues = array(
                'name' => $this->ip,
                'datetime' => $this->getDateTime(),
                'score' => (int)$score,
                'status' => (int)$status,
                'referers_id' => $referer_id,
                'pages_id' => $page_id,
                'visits' => 1
            );
            $ipmanager = new oseFirewallIpManager($this->db);
            $aclid = $ipmanager->getACLID();
            if (empty($aclid)) {
                $this->aclid = $this->db->addData('insert', '#__osefirewall_acl', null, null, $varValues);
                if (!empty ($this->aclid)) {
                    $ipmanager->addIP('ip', $this->aclid);
                }
            }
        } else {
            $varValues = array(
                'score' => (int)$score,
                'status' => (int)$status
            );
            $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
        }
    }

    protected function updateACLScore($score)
    {
        $varValues = array(
            'score' => $score
        );
        $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
    }

    protected function addL1DetContent($attacktypeID, $detcontent_content = null, $rule_id = null)
    {
        $exists = $this->isDetContentExists($attacktypeID, $rule_id);
        if (!empty ($exists)) {
            return;
        }
        $detattacktype_id = $this->insertDetAttacktype($attacktypeID);
        if (!empty ($detattacktype_id)) {
            $this->insertDetected($detattacktype_id);
            if (!empty ($detcontent_content) && !empty ($rule_id)) {
                $this->insertDetContentDetail($detattacktype_id, $detcontent_content, $rule_id, null);
            }
        }
        return $detattacktype_id;
    }

    protected function addL2DetContent($attacktypeIDArray, $detcontent_content, $rule_id, $varKey)
    {
        $attacktypeIDArray = oseJSON:: decode($attacktypeIDArray);
        foreach ($attacktypeIDArray as $attacktypeID) {
            //commented out to always record detail content for stats purposes
            $detattacktype_id = $this->insertDetAttacktype($attacktypeID);
            if (!empty ($detattacktype_id)) {
                $this->insertDetected($detattacktype_id);
                if (!empty ($detcontent_content) && !empty ($rule_id)) {
                    $var_id = $this->insertVarKey($varKey);
                    $this->insertDetContentDetail($detattacktype_id, $detcontent_content, $rule_id, $var_id);
                }
            }
//			}
        }
    }

    public function insertVarKey($varKey)
    {
        $query = 'SELECT `id` FROM `#__osefirewall_vars` WHERE `keyname` = ' . $this->db->quoteValue($varKey);
        $this->db->setQuery($query);
        $id = $this->db->loadResult();
        if (empty ($id)) {
            $varValues = array(
                'keyname' => $varKey,
                'status' => 1
            );
            $id = $this->db->addData('insert', '#__osefirewall_vars', null, null, $varValues);
            return $id;
        } else {
            $tmp = array_values($id);
            $id = $tmp[0];
            return $id;
        }
    }

    protected function insertDetAttacktype($attacktypeID)
    {
        $varValues = array(
            'attacktypeid' => (int)$attacktypeID
        );
        $detattacktype_id = $this->db->addData('insert', '#__osefirewall_detattacktype', null, null, $varValues);
        return $detattacktype_id;
    }

    protected function insertDetected($detcontent_id)
    {
        $varValues = array(
            'acl_id' => (int)$this->aclid,
            'detattacktype_id' => (int)$detcontent_id
        );
        $this->db->addData('insert', '#__osefirewall_detected', null, null, $varValues);
    }

    protected function isDetContentExists($attacktypeID, $rule_id = null)
    {
        $where = array();
        $where[] = '`acl`.`id` = ' . (int)$this->aclid;
        $where[] = '`detattacktype`.`attacktypeid` = ' . (int)$attacktypeID;
        if (!empty ($rule_id)) {
            $where[] = '`detcontdetail`.`rule_id` = ' . (int)$rule_id;
        }
        $where = $this->db->implodeWhere($where);
        $sql = convertViews::convertAttackmap(array('COUNT(`acl`.`id`) as `count`'));
        $query = $sql . $where;
        $this->db->setQuery($query);
        $result = (object)($this->db->loadResult());
        return $result->count;
    }

    protected function insertDetContentDetail($detattacktype_id, $detcontent, $rule_id, $var_id)
    {
        $detcontent_id = $this->insertDetContent($detcontent);
        $varValues = array(
            'detattacktype_id' => (int)$detattacktype_id,
            'detcontent_id' => $detcontent_id,
            'rule_id' => $rule_id,
            'inserted_on' => oseFirewall::getTime()
        );
        if (!empty ($var_id)) {
            $varValues['var_id'] = $var_id;
        }
        $this->db->addData('insert', '#__osefirewall_detcontdetail', null, null, $varValues);
        return;
    }

    protected function insertDetContent($detcontent)
    {
        $id = $this->getDetContentID($detcontent);
        if (empty ($id)) {
            $varValues = array(
                'content' => $detcontent
            );
            $id = $this->db->addData('insert', '#__osefirewall_detcontent', null, null, $varValues);
            return $id;
        } else {
            return $id;
        }
    }

    protected function getDetContentID($detcontent)
    {
        $query = 'SELECT `id` FROM `#__osefirewall_detcontent` WHERE `content` = ' . $this->db->quoteValue($detcontent);
        $this->db->setQuery($query);
        $result = (object)($this->db->loadResult());
        return (isset($result->id)) ? $result->id : null;
    }

    protected function addPages()
    {
        $query = 'SELECT `id`, `visits` FROM `#__osefirewall_pages` WHERE `page_url` = ' . $this->db->quoteValue($this->url, true);
        $this->db->setQuery($query);
        $results = $this->db->loadObject();
        if (empty ($results)) {
            $varValues = array(
                'page_url' => $this->url,
                'action' => 1,
                'visits' => 1
            );
            $id = $this->db->addData('insert', '#__osefirewall_pages', null, null, $varValues);
        } else {
            $varValues = array(
                'visits' => $results->visits + 1
            );
            $this->db->addData('update', '#__osefirewall_pages', 'id', $results->id, $varValues);
            $id = $results->id;
        }
        return $id;
    }

    protected function addReferer()
    {
        $query = 'SELECT `id` FROM `#__osefirewall_referers` WHERE `referer_url` = ' . $this->db->quoteValue($this->referer, true);
        $this->db->setQuery($query);
        $results = $this->db->loadObject();
        if (empty ($results)) {
            $varValues = array(
                'referer_url' => $this->referer
            );
            $id = $this->db->addData('insert', '#__osefirewall_referers', null, null, $varValues);
        } else {
            $id = $results->id;
        }
        return $id;
    }

    protected function getAllowBots()
    {
        $bots = array();
        if ($this->scanGoogleBots == false) {
            $bots[] = 'Google';
        }
        if ($this->scanMsnBots == false) {
            $bots[] = 'msnbot';
        }
        if ($this->scanYahooBots == false) {
            $bots[] = 'Yahoo';
        }
        return $bots;
    }

    protected function set($var, $value)
    {
        $this->$var = $value;
    }

    protected function get($var)
    {
        return $this->$var;
    }

    protected function checkContinue()
    {
        if ($this->ipStatus == 3) {
            return false;
        }
        if ($this->devMode == true) {
            return false;
        }
        $bots = $this->getAllowBots();
        $ipmanager = new oseFirewallIpManager($this->db);
        if (in_array('msnbot', $bots) && !empty($_SERVER['HTTP_USER_AGENT']) && $ipmanager->isSearchEngineBot('msnbot', $_SERVER['HTTP_USER_AGENT'])) {
            $this->set('blockIP', 2);
            $this->set('slient_max_att', 20);
            return false;
        }
        if (in_array('Yahoo', $bots) && !empty($_SERVER['HTTP_USER_AGENT']) && $ipmanager->isSearchEngineBot('Yahoo', $_SERVER['HTTP_USER_AGENT'])) {
            $this->set('blockIP', 2);
            $this->set('slient_max_att', 20);
            return false;
        }
        if (in_array('Google', $bots) && !empty($_SERVER['HTTP_USER_AGENT']) && $ipmanager->isSearchEngineBot('Google', $_SERVER['HTTP_USER_AGENT'])) {
            if ($ipmanager->isSearchEngineGoogleBot()) {
                $this->set('blockIP', 2);
                $this->set('slient_max_att', 20);
                return false;
            } else {
                $this->set('blockIP', 2);
                return true;
            }
        }
        if (isset($_GET['action']) && $_GET['action'] == 'register') {
            return true;
        }
        if (preg_match("/wp-login/", $_SERVER['SCRIPT_NAME'])) {
            return false;
        }
        if ((preg_match("/administrator\/*index.?\.php$/", $_SERVER['SCRIPT_NAME'])) || !empty($_SESSION['secretword'])) {
            if (isset($this->secretword) && $this->secretword == $_SERVER['QUERY_STRING']) {
                $_SESSION['secretword'] = $this->secretword;
                return false;
            }
        }
        if (isset ($_REQUEST['option'])) {
            if (COUNT($_REQUEST['option']) == 1 && (in_array($_REQUEST['option'], array(
                    'com_ose_fileman',
                    'com_ose_antihacker',
                    'com_ose_antivirus',
                    'com_ose_firewall',
                    'com_akeeba',
                    'com_civicrm',
            		'com_autotweet',
                    'com_vmaffiliationcrontrigger'
                )))
            ) {
                if ($_REQUEST['option'] != 'com_vmaffiliationcrontrigger' ){
                    return false;
                }
                else if ($_REQUEST['option'] == 'com_vmaffiliationcrontrigger' && $_REQUEST['task']=='vmCronTrigger') {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    protected function customRedirect()
    {
        if (!empty($this->customBanURL)) {
            header('Location: ' . $this->customBanURL);
        }
    }

    public function showBanPage()
    {
        $this->customRedirect();
        $data = oseFirewall::getConfiguration('admin');
        $webmaster = $data['data']['adminEmail'];
        $adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail : $webmaster;
        $customBanPage = (!empty ($this->customBanpage)) ? $this->customBanpage : 'Banned';
        $pageTitle = (!empty ($this->pageTitle)) ? $this->pageTitle : 'Centrora Security';
        $metaKeys = (!empty ($this->metaKeywords)) ? $this->metaKeywords : 'Centrora Security';
        $metaDescription = (!empty ($this->metaDescription)) ? $this->metaDescription : 'Centrora Security';
        $metaGenerator = (!empty ($this->metaGenerator)) ? $this->metaGenerator : 'Centrora Security';
        $banhtml = $this->getBanPage($adminEmail, $pageTitle, $metaKeys, $metaDescription, $metaGenerator, $customBanPage);
        echo $banhtml;
        $this->db->closeDBO();
        exit;
    }

    protected function getBanPage($adminEmail, $pageTitle, $metaKeys, $metaDescription, $metaGenerator, $customBanPage)
    {
        $banbody = $this->getBanPageBody($customBanPage, $adminEmail);
        header('HTTP/1.0 403 Forbidden');
        $banhtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
						<head>
							  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
							  <meta name="robots" content="index, follow" />
							  <meta name="keywords" content="' . $metaKeys . '" />
							  <meta name="description" content="' . $metaDescription . '" />
							  <meta name="generator" content="' . $metaGenerator . '" />
							  <title>' . $pageTitle . '</title>
          				<link rel="stylesheet" href="' . OSE_BANPAGE_ADMIN . '/public/css/bootstrap.min.css">
         				<link rel="stylesheet" href="' . OSE_BANPAGE_ADMIN . '/public/css/blockpage.css">
                        <link rel="stylesheet" href="' . OSE_BANPAGE_ADMIN . '/public/css/animate.css">
						<script src="' . OSE_BANPAGE_ADMIN . '/public/js/jquery-1.11.1.min.js"></script>
						<script src="' . OSE_BANPAGE_ADMIN . '/public/js/plugins/wow/wow.min.js"></script>
						<script>new WOW().init();</script>
						<script>
      jQuery(document).ready(function($){
           $("#googleAuth-form").submit(function() {
             var data = $("#googleAuth-form").serialize();
             $.ajax({
             url: "index.php?",
            type: "POST",
            data: data,
            success: function(data)
            {
                if (data == 1)
                {
                   location.reload(true);
                }
                else
                {
                   alert("wrong code, try again");
                }
            }
        });
       return false; // avoid to execute the actual submit of the form.
});
})
                      </script>
						</head>
						<body>
						' . $banbody . '
						</body>
					</html>';
        return $banhtml;
    }

    protected function getBanPageBody($customBanPage, $adminEmail)
    {
        $banhead = '<header>
        <div id="hero">
            <div class="container herocontent">
				 <h2 class="wow fadeInUp" data-wow-duration="2s" style="color:#fff; text-shadow: 1px 1px 2px #000000;" >Your IP Address Has Been Blocked.</h2>
                <h4 class="wow fadeInDown" data-wow-duration="3s" style="color:#fff; text-shadow: 1px 1px 2px #000000;">The firewall has flagged your IP address, but don\'t worry!</h4>
            </div>
        </div>
     </header>';
        $banfooter = '<footer>
        <div class="container">
            <div class="copyright"><!-- FOOTER COPYRIGHT START -->
                <p style="color:#fff;">' . $customBanPage . '</p>
                 <h3 style="color:#fff;">WHAT NOW?</h3>
                 <p style="color:#fff;">Your IP address is ' . $this->ip . '. If you believe this is an error, please contact the <a href="mailto:' . $adminEmail . '?Subject=Inquiry:%20Banned%20for%20suspicious%20hacking%20behaviour - IP: ' . $this->ip . ' - Violation"> Webmaster </a>';
        $googleAuth = oseFirewall::getConfiguration('admin');
        $centroraGA = (!empty($googleAuth['data']['centroraGA'])) ? $googleAuth['data']['centroraGA'] : false;
        $secret = (!empty($googleAuth['data']['gaSecret'])) ? $googleAuth['data']['gaSecret'] : false;
        if ($centroraGA == true && !empty($secret)) {
            $banfooter .= $this->getGoogleAuthForm();
        }
        $banfooter .= '</p>
	            </div><!-- FOOTER COPYRIGHT END -->
	         </div>
	     </footer>';
        $banbody = $banhead . $banfooter;
        $banbody = str_replace('info@opensource-excellence.com', $adminEmail, $banbody);
        $banbody = str_replace('info@your-website.com', $adminEmail, $banbody);
        $banbody = str_replace('OSE Team', 'Management Team', $banbody);
        return $banbody;
    }

    protected function getGoogleAuthForm()
    {
        return ' <form id = "googleAuth-form" class="form-horizontal group-border stripped" role="form" action="index.php?">
            <lable style="color:#fff" for="googleAuthCode" class="form-label form-label-left form-label-auto">' . oLang:: _get("UNBAN_PAGE_GOOGLE_AUTH_DESC") . '</lable>
            <input  type="text" id="googleAuthCode" class="form-textbox"  name="googleAuthCode">
            <button type="submit" class="btn btn-default" id="save-button">Submit</button>
            </form>';
    }

    protected function show403Page()
    {
        $data = oseFirewall::getConfiguration('admin');
        $webmaster = $data['data']['adminEmail'];
        $adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail : $webmaster;
        $customBanPage = (!empty ($this->customBanpage)) ? $this->customBanpage : 'Banned';
        $banbody = $this->getBanPageBody($customBanPage, $adminEmail);
        header('HTTP/1.0 403 Forbidden');
        header('Content-type: text/html; charset=UTF-8');
        $banbody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
							<title>403 Forbidden</title>
							<link rel="stylesheet" href="' . OSE_BANPAGE_ADMIN . '/public/css/bootstrap.min.css">
         				<link rel="stylesheet" href="' . OSE_BANPAGE_ADMIN . '/public/css/blockpage.css">
                        <link rel="stylesheet" href="' . OSE_BANPAGE_ADMIN . '/public/css/animate.css">
						<script src="' . OSE_BANPAGE_ADMIN . '/public/js/jquery-1.11.1.min.js"></script>
						<script src="' . OSE_BANPAGE_ADMIN . '/public/js/plugins/wow/wow.min.js"></script>
						<script>new WOW().init();</script>
						<script>
      jQuery(document).ready(function($){
           $("#googleAuth-form").submit(function() {
             var data = $("#googleAuth-form").serialize();
             $.ajax({
             url: "index.php?",
            type: "POST",
            data: data,
            success: function(data)
            {
                if (data == 1)
                {
                   location.reload(true);
                }
                else
                {
                   alert("wrong code, try again");
                }
            }
        });
       return false; // avoid to execute the actual submit of the form.
});
})
                      </script>
						</head>
						<body>
								' . $banbody . '
						</body>
					 </html>';
        echo $banbody;
        $this->db->closeDBO();
        exit;
    }

    protected function show403Msg($msg)
    {
        header('HTTP/1.1 403 Forbidden');
        $banbody = '<html>
						<head>
							<title>403 Forbidden</title>
						</head>
						<body>
						<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Warning!</strong> ' . $msg . '
                        </div>
						</body>
					 </html>';
        echo $banbody;
        //exit;
    }

    protected function sendEmail($type, $notified)
    {
        if ($this->receiveEmail == true && $notified == 0) {
            $config_var = $this->getConfigVars();
            oseFirewall::loadEmails();
            $oseEmail = new oseEmail('firewall');
            $email = $this->getEmailByType($type);
            $email = $this->convertEmail($email, $config_var);

            $receiptient = oseFirewall::getActiveReceiveEmails();
            $receiptient = $this->filterReceiptient($receiptient);

			if (empty($receiptient)) {
                $data = oseFirewall::getConfiguration('admin');
                $webmaster = $data['data']['adminEmail'];
                $adminEmail = (isset ($this->adminEmail)) ? $this->adminEmail : $webmaster;
				$receiptient = array();
				$receiptientObject = new stdClass();
				$receiptientObject->name = 'admin';
				$receiptientObject->email = $adminEmail;
				$receiptient[] = $receiptientObject;
			}
			$result = $oseEmail->sendMailTo($email, $config_var, $receiptient);
            if ($result == true) {
                $this->updateNotified(1);
            }
            $oseEmail->closeDBO();
        }
    }

    protected function filterReceiptient($receiptient)
    {
        $filter = "('" . implode("', '", $receiptient) . "')";
        $this->domain = $_SERVER['HTTP_HOST'];

        $db = oseFirewall::getDBO();

        $query = "SELECT `A_email`,`A_name`
					  FROM `#__osefirewall_adminemails` `admin` LEFT JOIN `#__osefirewall_domains` `dom`
					  ON `admin`.`D_id` = `dom`.`D_id`
					  WHERE `D_address` = '$this->domain' AND `admin`.`A_email` IN $filter";
        $db->setQuery($query);

        $results = $db->loadObjectList();
        $i = 0;
        $return = array();

        foreach ($results as $result) {
            $return[$i] = new StdClass;
            $return[$i]->name = $result->A_name;
            $return[$i]->email = $result->A_email;
            $i++;
        }
        return $return;
    }

    protected function getEmailByType($type)
    {
        $email = new stdClass();
        switch ($type) {
            case 'blacklisted':
                $email->subject = 'Centrora Security Alert For a Blacklisted Entry';
                break;
            case 'filtered':
                $email->subject = 'Centrora Security Alert For a Filtered Entry';
                break;
            case '403blocked':
                $email->subject = 'Centrora Security Alert For an Access Denied Entry';
                break;
        }
        $emailTmp = oseFirewall::getConfiguration('emailTemp');
        if (empty($emailTmp['data']['emailTemplate'])) {
            $email->body = file_get_contents(dirname(__DIR__) . ODS . 'emails' . ODS . 'email.tpl');
        } else {
            $email->body = str_replace("=C2=A0", "", stripslashes($emailTmp ['data'] ['emailTemplate']));
        }
        return $email;
    }

    protected function updateNotified($status)
    {
        $varValues = array(
            'notified' => (int)$status
        );
        $result = $this->db->addData('update', '#__osefirewall_acl', 'id', $this->aclid, $varValues);
        return (boolean)$result;
    }

    protected function convertEmail($email, $config_var)
    {
        $attackTypetmp = $this->getAttackTypes();
        $attackType = '';
        foreach ($attackTypetmp as $key => $value) {
            $attackType .= implode(',', $value) . ', ';
        }
        $ipURL = $this->getIPURL($config_var);
        $violation = $this->getViolation();
        $score = $this->getScore();
        $email->subject = $email->subject . " for [" . $_SERVER['HTTP_HOST'] . "]";
        $email->body = str_replace('{name}', 'Administrator', $email->body);
        $email->body = str_replace('{header}', $email->subject, $email->body);
        $content = "An attack attempt was logged on: " . $this->logtime . "<br/> IP Address: " . $this->ip . "<br/> IP ID: " . $this->aclid . "<br/>
        URL: " . $this->url . "<br/> Referer (if any): " . $this->referer . "<br/> Attack Type: " . $attackType . "<br/> Violation: " . substr($violation,0,300) . "...<br/>
        Total Risk Score: " . $score . "<br/> IP information: <a href='http://www.infosniper.net/index.php?ip_address=" . $this->ip . "'>" . $this->ip . "</a>";
        $email->body = str_replace('{content}', $content, $email->body);

        return $email;
    }

    protected function getViolation()
    {
        $return = '';
        if (!empty($this->replaced['original'])) {
            foreach ($this->replaced['original'] as $key => $replaced) {
                if ($key == 'USERAGENT') {
                	$return .= $key . ' from <font color="red">' . $this->replaced['original'][$key][0] . '</font> is blocked';
                	break;
                }
            	elseif ($key == 'URL') {
                    $return .= $key . ' <br />redirected from <font color="red">' . $this->replaced['original'][$key] . '</font> <br />to ' . $this->replaced['filtered'][$key] . '<br/>';
                } elseif (in_array($key, array('GET', 'POST'))) {
                    foreach ($replaced as $k => $v) {
                        $return .= $key . '.' . $k . ' changed from <font color="red">' . $this->replaced['original'][$key][$k] . '</font> to ' . $this->replaced['filtered'][$key][$k] . '<br/>';
                    }
                }
            }
        } else {
            $return = $this->detected;
        }
        return $return;
    }

    protected function getIPURL($config_var)
    {
        return null;
        //return "<a href='".$config_var->live_site."/administrator/index.php?option=com_ose_antihacker&view=manageips&id=".$this->aclid."'>".$this->aclid."</a>";
    }

    protected function getAttackTypes()
    {
        $attrList = array("DISTINCT `attacktype`.`name` AS `name`");
        $sql = convertViews::convertAttackmap($attrList);
        $query = $sql . 'WHERE `acl`.`id` = ' . (int)$this->aclid;
        $this->db->setQuery($query);
        $results = $this->db->loadResultArray();
        return $results;
    }

    protected function getConfigVars()
    {
        return oseFirewall::getConfigVars();
    }

    protected function CheckSpambotEmail()
    {
        // Initiate and declare spambot/errorDetected as false - as we're just getting started
        $isspamcheck = $this->isspamcheck();
        $scanReturn = array();
        $spambot = false;
        if ($spambot != true && $this->ip != "" && (int)$isspamcheck < 3) {
            $email = '';
            if (isset($_POST['jform']['email1']) && !empty($_POST['jform']['email1'])) {
                $email = $_POST['jform']['email1'];
            }
            if (isset($_POST['user_email']) && !empty($_POST['user_email'])) {
                $email = $_POST['user_email'];
            }
            if (!empty($email)) {
                $data = array();
                $data["email"] = $_POST['jform']['email1'];
                $data["f"] = 'json';
                $json_return = $this->posttoSFS($data);
                $result = oseJSON:: decode($json_return);
                $this->updatespamcheck(2);
                if (!isset($result->email->confidence)) {
                    return false;
                } elseif ($result->email->appears == 1 && $result->email->confidence >= (int)$this->sfs_confidence) // Was the result was registered
                {
                    $spambot = true; // Check failed. Result indicates dangerous.
                    $return = $this->composeResult(100, oseJSON::encode($data["email"]), 1, 11, 'server.HTTP_CLIENT_IP', 'bs');
                    $return['spamtype'] = 'email';
                    return $return;
                } else {
                    return false; // Check passed. Result returned safe.
                }
                unset ($data);
                unset ($json_return);
                unset ($result);
            }
        }
        return $spambot; // Return test results as either true/false or 1/0
    }

    protected function CheckIsSpambot()
    {
        // Initiate and declare spambot/errorDetected as false - as we're just getting started
        $isspamcheck = $this->isspamcheck();
        $scanReturn = array();
        $spambot = false;
        if ($spambot != true && $this->ip != "" && (int)$isspamcheck < 1) {
            $data = array();
            $data["ip"] = $this->ip;
            $data["f"] = 'json';
            $json_return = $this->posttoSFS($data);
            $result = oseJSON:: decode($json_return);
            $this->updatespamcheck(1);
            if (!isset($result->ip->confidence)) {
                return false;
            } elseif ($result->ip->appears == 1 && $result->ip->confidence >= (int)$this->sfs_confidence) // Was the result was registered
            {
                $spambot = true; // Check failed. Result indicates dangerous.
                $return = $this->composeResult(100, oseJSON::encode($result->ip), 1, 11, 'server.HTTP_CLIENT_IP' . 'bs');
                return $return;
            } else {
                return false; // Check passed. Result returned safe.
            }
            unset ($data);
            unset ($json_return);
            unset ($result);
        }
        return $spambot; // Return test results as either true/false or 1/0
    }

    protected function isspamcheck()
    {
        $this->ip32 = ip2long($this->ip);
        $query = "SELECT `ischecked` FROM `#__osefirewall_sfschecked` WHERE `ip32_start` = " . $this->db->quoteValue($this->ip32);
        $this->db->setQuery($query);
        $result = (object)($this->db->loadResult());
        return (!empty($result->ischecked) && $result->ischecked == 1) ? true : false;
    }

    protected function updatespamcheck($type)
    {
        $varValues = array(
            'ip32_start' => $this->ip32,
            'ischecked' => (int)$type
        );
        $this->db->addData('insert', '#__osefirewall_sfschecked', '', '', $varValues);
    }

    protected function posttoSFS($data)
    {
        $Url = "http://www.stopforumspam.com/api?" . http_build_query($data);
        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_TIMEOUT, 4);
        curl_setopt($Curl, CURLOPT_FAILONERROR, 1);
        $ResultString = curl_exec($Curl);
        curl_close($Curl);
        unset ($Url);
        unset ($Curl);
        return $ResultString;
    }

    protected function scanUploadFiles()
    {
        $this->getAllowExts();
        if (!empty ($this->allowExts)) {
            $scanResult = $this->checkFileTypes();
            return $scanResult;
        } else {
            return null;
        }
    }

    protected function getAllowExts()
    {
        $query = "SELECT `ext_name` FROM `#__osefirewall_fileuploadext` WHERE `ext_status` = 1";
        $this->db->setQuery($query);
        $results = $this->db->loadArrayList();
        $return = array();
        if (!empty($results)) {
            foreach ($results as $result) {
                $return[] = strtolower($result['ext_name']);
            }
        }
        $this->allowExts = (!empty($return)) ? $return : null;
    }

    protected function cleanFileVariable($fileVar)
    {
        if (is_array($fileVar)) {
            foreach ($fileVar as $filetmp) {
                $fileVar = $filetmp;
                break;
            }
        }
        return $fileVar;
    }

    protected function checkFileTypes()
    {
        $i = 0;
        if (!empty($_FILES)) {
            $file = $this->url;
            $file_headers = @get_headers($file);
            if (strpos($file_headers[0], '404')) {
                $exists = false;
            } else {
                $exists = true;
            }
            foreach ($_FILES as $file) {
                if (!empty ($file['tmp_name'])) {
                    if (is_array($file['tmp_name'])) {
                        $file['tmp_name'] = $file['tmp_name'][0];
                    }
                    if (!empty($file['tmp_name'])) {
                        $file['tmp_name'] = $this->cleanFileVariable($file['tmp_name']);
                        $file['type'] = $this->cleanFileVariable($file['type']);
                        $mimeType = $this->getMimeType($file);
                        $ext = explode('/', $file['type']);
                        if (is_array($file['name'])) {
                            $filename = $file['name'][$i];
                            $i++;
                        } else {
                            $filename =  $file['name'];
                        }
                        $info = new SplFileInfo($filename);
                        $extname = strtolower($info->getExtension());
                       	$allowExts = array_map('trim', $this->allowExts);
                        if ($ext[1] == 'vnd.openxmlformats-officedocument.wordprocessingml.document' && ($mimeType[1] != $ext[1])) {
                            $ext[1] = $mimeType[1] = 'msword';
                        }
                        if ($ext[1] == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet' && ($mimeType[1] != $ext[1])) {
                            $ext[1] = $mimeType[1] = 'msexcel';
                        }
                        if ($ext[1] == 'jpg' && ($mimeType[1] == 'jpeg')) {
                            $ext[1] = 'jpeg';
                        }
                        if ($ext[1] == 'jpeg' && ($mimeType[1] == 'jpg')) {
                            $ext[1] = 'jpg';
                        }
                        if (($ext[1] == 'csv' || $ext[1] == 'comma-separated-values') && $mimeType[1] == 'plain') {
                            $ext[1] = $mimeType[1] = 'csv';
                        }
                        if ($ext[1] == 'download' && $mimeType[1] == 'zip') {
                        	$ext[1] = $mimeType[1] = 'zip';
                        }
                        if ($ext[1] != $mimeType[1]) {
                            $this->show403Msg(oLang:: _get('UPLOAD_FILE_403WARN2') . '<br /> File Type: <b>' . $mimeType[1] . '</b>');
                            $score = $this->getScore();
                            $return = $this->composeResult($score + 10, $file['name'], 11, oseJSON::encode(array(13)), 'server.FILE_TYPE', 'bs');
                            //prepare File Upload Log
                            $return = array_merge($return, $this->composeUploadLogResult(2, $filename, $mimeType[1]));
                            $this->unlinkUPloadFiles();
                            return $return;
                        } elseif (!empty($extname) && in_array($extname, $allowExts) == false) {
                            $this->show403Msg(oLang:: _get('UPLOAD_FILE_403WARN') . '<br /> File Type: <b>' . $extname . '</b>');
                            $return = $this->composeResult(0, $file['name'], 11, oseJSON::encode(array(13)), 'server.FILE_TYPE', 'bs');
                            //prepare File Upload Log
                            $return = array_merge($return, $this->composeUploadLogResult(1, $filename, $extname));
                            $this->unlinkUPloadFiles();
                            return $return;
                        } elseif (!$exists) {
                            $this->show403Msg(oLang:: _get('UPLOAD_FILE_403WARN3'));
                            $score = $this->getScore();
                            $return = $this->composeResult($score + 10, $file['name'], 11, oseJSON::encode(array(13)), 'server.FILE_TYPE', 'bs');
                            //prepare File Upload Log
                            $return = array_merge($return, $this->composeUploadLogResult(3, $filename, $mimeType[1]));

                            $this->unlinkUPloadFiles();
                            return $return;
                        } else {
                            if($mimeType[1] == "vnd.openxmlformats-officedocument.wordprocessingml.document" && $extname == "msword")
                            {
                                $mimeType[1] = $extname;
                            }
                            if($mimeType[1] == "msword" && $ext[1] == "msword" && $extname == "doc")
                            {
                                $mimeType[1] = "doc";
                            }
                            $result = array_merge(array('cont' => true), $this->composeResult(0, $file['name'], 11, oseJSON::encode(array(13)), 'server.FILE_TYPE', 'bs'));
                            $logreturn = array_merge($result, $this->composeUploadLogResult(0, $filename, $mimeType[1]));
                            return $logreturn;
                        }
                    }
                }
            }
        }
    }

    protected function composeUploadLogResult($valStatus, $filename, $filetype)
    {
        $return['ip'] = $this->ip;
        $return['validation_status'] = $valStatus;
        $return['fileuploadlog'] = true;
        $return['file_name'] = $filename;
        $return['file_type'] = $filetype;
        $return['datetime'] = $this->getDateTime();
        return $return;
    }

    protected function getMimeType($file)
    {
        $mimeType = $this->getFileInfo($file['tmp_name']);
        if (empty ($mimeType)) {
            $mimeType = $this->checkisPHPfile($file['tmp_name']);
        }
        if (!empty ($mimeType)) {
            if (strstr($mimeType, '/') != false) {
                $mimeType = explode("/", $mimeType);
            } else {
                $tmp = explode(" ", $mimeType);
                $mimeType = array();
                $mimeType[0] = strtolower($tmp [1]);
                $mimeType[1] = strtolower($tmp [0]);
            }
        } else {
            $mimeType = explode("/", $file['type']);
        }
        return $mimeType;
    }

    protected function getFileInfo($filename)
    {
        if (!defined('FILEINFO_MIME_TYPE')) {
            define('FILEINFO_MIME_TYPE', 1);
        }
        $defined_functions = get_defined_functions();
        if ((in_array('finfo_open', $defined_functions['internal'])) || function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $content_type = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $content_type;
        } elseif (function_exists('mime_content_type')) {
            $content_type = mime_content_type($filename);
            return $content_type;
        } else {
            return false;
        }
    }

    protected function checkisPHPfile($file)
    {
        if (empty($file)) {
            return false;
        }
        if (filesize($file) > '2048000') {
            return false;
        }
        $data = file($file);
        $data = implode("\r\n", $data);
        $pattern = "/(\<\?)|(\<\?php)/";
        if (preg_match($pattern, $data)) {
            return 'application/x-httpd-php';
        } else {
            return false;
        }
    }

    protected function unlinkUPloadFiles()
    {
        if (isset($_FILES['tmp_name']) && is_array($_FILES['tmp_name'])) {
            foreach ($_FILES['tmp_name'] as $filetmp) {
                if (file_exists($filetmp)) {
                    unlink($filetmp);
                }
                break;
            }
        } else {
            if (isset($_FILES['tmp_name']) && file_exists($_FILES['tmp_name'])) {
                unlink($_FILES['tmp_name']);
            }
        }
        unset ($_FILES);
    }

    protected function checkCountryStatus()
    {
        if ($this->blockCountry == false) {
            return false;
        } else {
            $ready = oseFirewall::getGeoIPState();
            if ($ready == true) {
                $query = "SELECT country.`status` FROM `#__ose_app_geoip` as `ip` LEFT JOIN `#__osefirewall_country` AS `country` ON country.country_code = ip.country_code WHERE " . $this->db->QuoteValue($this->ip32) . " BETWEEN ip.ip32_start AND ip.ip32_end ";
                $this->db->setQuery($query);
                $result = $this->db->loadObject();
                if (!empty($result)) {
                    if ($result->status == 1) {
                        $this->showCountryBlockMsg();
                    } else if ($result->status == 2) {
                        return false;
                    } else if ($result->status == 3) {
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }


    public function countryCheckV7()
    {
        $this->setClientIP();
        $ready = oseFirewall::getGeoIPState();
        if ($ready == true) {
            $query = "SELECT country.`status` FROM `#__ose_app_geoip` as `ip` LEFT JOIN `#__osefirewall_country` AS `country` ON country.country_code = ip.country_code WHERE " . $this->db->QuoteValue($this->ip32) . " BETWEEN ip.ip32_start AND ip.ip32_end ";
            $this->db->setQuery($query);
            $result = $this->db->loadObject();
            if (!empty($result)) {
                if ($result->status == 1) {
                    if(oseFirewallBase::isFirewallV7Active()) {
                        oseFirewall::callLibClass('fwscannerv7', 'ipManagement');
                        $this->ipmanagement = new ipManagement();
                        if (($this->ipmanagement->isIPWhiteListedDB($this->ip)) || $this->ipmanagement->checkIpIsWhiteListedLocalFile($this->ip)) {
                            return true;
                        } else {
                            $this->showCountryBlockMsg();

                        }
                    }else{
                            $this->showCountryBlockMsg();
                        }
                } else if ($result->status == 2) {
                    return false;
                } else if ($result->status == 3) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function showCountryBlockMsg()
    {
        $style = "font-family: arial; background: none repeat scroll 0 0 #0C56B0; border-bottom: 5px solid #4D91E2;color: #FFFFFF; padding: 10px; font-size: 12px; text-align: center;";
        $html = "<div style='" . $style . "'>Your country is not allowed to access in this website</div>";
        die($html);
    }

    protected function clearWhitelistVars($request)
    {
        $varArray = $this->getWhitelistVars();
        foreach ($request as $method => $array) {
            foreach ($array as $key => $value) {
                if (in_array(strtolower($method . '.' . $key), $varArray)) {
                    unset($request[$method][$key]);
                }
            }
        }
        return $request;
    }

    protected function getWhitelistVars()
    {
        $query = "SELECT `keyname` FROM `#__osefirewall_vars` WHERE `status`  = 3 ";
        $this->db->setQuery($query);
        $results = $this->db->loadArrayList('keyname');
        $return = array();
        foreach ($results as $result) {
            $return[] = strtolower($result['keyname']);
        }
        return $return;
    }

    protected function composeResult($impact, $content, $rule_id, $attackTypeID, $keyname, $type = 'bs')
    {
        $return = array();
        $return ['impact'] = $impact;
        $return ['attackTypeID'] = $attackTypeID;
        $return ['detcontent_content'] = $content;
        $return ['keyname'] = $keyname;
        $return ['rule_id'] = $rule_id;
        $return ['type'] = $type;
        return $return;
    }

    protected function logDomain()
    {
        $serverName = $_SERVER['SERVER_NAME'];
        $query = "SELECT * FROM `#__osefirewall_logs` WHERE `comp` = 'dom' AND `status` = " . $this->db->quoteValue($serverName) . " LIMIT 1";
        $this->db->setQuery($query);
        $this->db->query();
        $result = $this->db->loadObject();
        if (!$result) {
            $time = $this->getDateTime();
            $query = "INSERT INTO `#__osefirewall_logs` (
					`id`,
					`date`,
					`comp`,
					`status`
					)
					VALUES(
					NULL," . $this->db->quoteValue($time) . ",'dom'," . $this->db->quoteValue($serverName) . ");";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }

    public static function inet_pton($ip)
    {
        // convert the 4 char IPv4 to IPv6 mapped version.
        $pton = str_pad(self::hasIPv6Support() ? inet_pton($ip) : self::_inet_pton($ip), 16,
            "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff\x00\x00\x00\x00", STR_PAD_LEFT);
        return $pton;
    }

    public static function _inet_pton($ip)
    {
        // IPv4
        if (preg_match('/^(?:\d{1,3}(?:\.|$)){4}/', $ip)) {
            $octets = explode('.', $ip);
            $bin = chr($octets[0]) . chr($octets[1]) . chr($octets[2]) . chr($octets[3]);
            return $bin;
        }

        // IPv6
        if (preg_match('/^((?:[\da-f]{1,4}(?::|)){0,8})(::)?((?:[\da-f]{1,4}(?::|)){0,8})$/i', $ip)) {
            if ($ip === '::') {
                return "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
            }
            $colon_count = substr_count($ip, ':');
            $dbl_colon_pos = strpos($ip, '::');
            if ($dbl_colon_pos !== false) {
                $ip = str_replace('::', str_repeat(':0000',
                        (($dbl_colon_pos === 0 || $dbl_colon_pos === strlen($ip) - 2) ? 9 : 8) - $colon_count) . ':', $ip);
                $ip = trim($ip, ':');
            }

            $ip_groups = explode(':', $ip);
            $ipv6_bin = '';
            foreach ($ip_groups as $ip_group) {
                $ipv6_bin .= pack('H*', str_pad($ip_group, 4, '0', STR_PAD_LEFT));
            }

            return strlen($ipv6_bin) === 16 ? $ipv6_bin : false;
        }

        // IPv4 mapped IPv6
        if (preg_match('/^((?:0{1,4}(?::|)){0,5})(::)?ffff:((?:\d{1,3}(?:\.|$)){4})$/i', $ip, $matches)) {
            $octets = explode('.', $matches[3]);
            return "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff" . chr($octets[0]) . chr($octets[1]) . chr($octets[2]) . chr($octets[3]);
        }

        return false;
    }

    public static function hasIPv6Support()
    {
        return defined('AF_INET6');
    }

    public function antiBruteForce($authUser, $username, $passwd)
    {

        $tKey = 'oselginfl_' . bin2hex(oseFirewallScanner::inet_pton($this->ip));

        $bfconfig = oseFirewall::getConfiguration('bf');

        $maxfail = $bfconfig['data']['loginSec_maxFailures'];
        $timeFrame = $bfconfig['data']['loginSec_countFailMins'];
        if (is_wp_error($authUser) && ($authUser->get_error_code() == 'invalid_username' || $authUser->get_error_code() == 'incorrect_password')) {

            $tries = get_transient($tKey);
            if ($tries) {
                $tries++;
            } else {
                $tries = 1;
            }
            if ($tries >= $maxfail) {
                $this->addACLRule(4, 99);
                $content = $tries . ' login attempts from IP address ' . $this->ip . ' this ip address is blocked due to exceeding the maximum login failure limit';
                $this->addDetAttempts(15, $content);
            }
            set_transient($tKey, $tries, $timeFrame * 60);
        } else if (get_class($authUser) == 'WP_User') {
            delete_transient($tKey); //reset counter on success
        }

        if (is_wp_error($authUser) && ($authUser->get_error_code() == 'invalid_username' || $authUser->get_error_code() == 'incorrect_password')) {
            return new WP_Error('incorrect_password', sprintf(__('<strong>ERROR</strong>: The username or password you entered is incorrect. <a href="%2$s" title="Password Lost and Found">Lost your password</a>?'), $_POST['log'], wp_lostpassword_url()));
        }

        return $authUser;
    }

    private function checkAttackTypesNum()
    {
        $sql = 'SELECT COUNT(`id`) AS count FROM `#__osefirewall_attacktype`';
        $this->db->setQuery($sql);
        $result = $this->db->loadObject();
        if ($result->count == 14) {
            $name = 'Brute Force Attack';
            $tag = 'bf';
            $this->addAttackType($name, $tag);
        }
    }

    private function addAttackType($name, $tag)
    {

        $varValues = array(
            'name' => $name,
            'tag' => $tag
        );
        $this->db->addData('insert', '#__osefirewall_attacktype', null, null, $varValues);

    }

    public function addDetAttempts($attackID, $tries)
    {
        /*$exists = $this->isDetContentExists($attackID);
        if (!empty ($exists)) {
            return;
        }*/
        $detattacktype_id = $this->insertDetAttacktype($attackID);
        if (!empty ($detattacktype_id)) {
            $this->insertDetected($detattacktype_id);
            if (!empty ($tries)) {
                $this->insertBFDetail($detattacktype_id, $tries);
            }
        }
        return $detattacktype_id;
    }

    protected function insertBFDetail($detattacktype_id, $tries)
    {
        $detcontent_id = $this->insertDetContent($tries);
        $varValues = array(
            'detattacktype_id' => (int)$detattacktype_id,
            'detcontent_id' => $detcontent_id,
            'inserted_on' => oseFirewall::getTime()
        );
        $this->db->addData('insert', '#__osefirewall_detcontdetail', null, null, $varValues);
        return;
    }

    public function toggleFirewallScanerV6($value)
    {
        if($value == 0)
        { //turn firewall scanner v6 on
            $result = $this->updateSettings('devMode',0);
            if($result == 0)
            {
                return $this->prepareErrorMessage('There was some problem in turning the Firewall Scanner V6 ON');
            }else {
                return  $this->prepareSuccessMessage('The Firewall Scanner V6 has been Turned ON');
            }
        }else {
            //turn firewall scanner off
            $result = $this->updateSettings('devMode',1);
            if($result == 0)
            {
                return  $this->prepareErrorMessage('There was some problem in turning the Firewall Scanner V6 OFF');
            }else {
                return $this->prepareSuccessMessage('The Firewall Scanner V6 has been Turned OFF');
            }
        }
    }

    public function updateSettings($key,$value)
    {
        $vararray = array(
            'value'=>$value,
        );
        $result = $this->db->addData('update', '#__ose_secConfig', 'key', $key, $vararray);
        $this->db->closeDBO ();
        return $result;
    }

    public function prepareSuccessMessage($message)
    {
        $result['status'] = 1;
        $result['info'] = $message;
        return $result;
    }

    public function prepareErrorMessage($message)
    {
        $result['status'] = 0;
        $result['info'] = $message;
        return $result;
    }
}
