<?php
/**
 * @version     6.0 +
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
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
if (class_exists('Sconfig') || class_exists('Jconfig'))
{
    require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'joomla.php');
    class oseFirewallRoot extends oseJoomla
    {
        protected static $option = 'com_ose_firewall';
    }
    define('OSE_CMS', 'joomla');
}
else
{
    require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'wordpress.php');
    class oseFirewallRoot extends oseWordpress
    {
        protected static $option = 'ose_firewall';
    }
    define('OSE_CMS', 'wordpress');
}
class oseFirewallBase extends oseFirewallRoot
{
    public function __construct()
    {
        $debug = $this->getDebugMode();
        $this->setDebugMode($debug);
    }

    public function initSystem()
    {
    }

    public function startSession()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function initGAuthenticator()
    {
        //$enable = true;
        require_once(OSE_FWFRAMEWORK . ODS . 'googleAuthenticator' . ODS . 'class_gauthenticator.php');
        $enable = $this->isGAuthenticatorEnabled();
        if ($enable == true) {
            require_once(OSE_FWFRAMEWORK . ODS . 'googleAuthenticator' . ODS . 'class_gauthenticator.php');
            $gauthenticator = new CentroraGoogleAuthenticator();
            add_action('init', array($gauthenticator, 'init'));
        }
    }

    private function isGAuthenticatorEnabled()
    {
        $enabled = $this->checkOseConfig('googleVerification', 'bf');
        if ($this->checkOseConfig('googleVerification', 'bf') == true || $this->checkOseConfig('googleVerification', 'advscan') == true) {
            return true;
        } else {
            return false;
        }
    }

    public function loadBackendFunctions()
    {
        $this->addMenuActions();
        oseFirewall::callLibClass('oem', 'oem');
        $oem = new CentroraOEM();
        $oem->defineVendorName();
        self::loadLanguage();
        self::loadJSON();
        $this->loadAjax();
        $this->loadViews();
    }

    public static function loadBackendBasicFunctions()
    {
        oseFirewall::addMenuActions();
        oseFirewall::callLibClass('oem', 'oem');
        $oem = new CentroraOEM();
        $oem->defineVendorName();
        self::loadLanguage();
    }

    public static function loadInstaller()
    {
        require_once(OSE_FWFRAMEWORK . ODS . 'oseFirewallInstaller.php');
    }

    private function loadAjax()
    {
        require_once(dirname(__FILE__) . ODS . 'oseFirewallAjax.php');
        oseFirewallAjax::loadAppActions();
    }

    public function isAdvanceFirewallSettingEnable()
    {
        $configEnable = $this->isAdvanceSettingConfigEnable();
        if ($configEnable == false) {
            return false;
        }

        $dbReady = oseFirewallBase:: isAdvanceSettingConfigDBReady();
        if ($dbReady == false) {
            return false;
        } else {
            return true;
        }
    }

    public static function isAdvanceSettingConfigDBReady()
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_advancerules');
        if (empty($data)) {
            return false;
        }
        $query = "SELECT COUNT(*) as `count` FROM `#__osefirewall_advancerules` ";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        return ($result['count'] > 0) ? true : false;
    }

    public static function isAdvancePatternConfigDBReady()
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_advancepatterns');
        if (empty($data)) {
            return false;
        }
        $query = "SELECT COUNT(*) as `count` FROM `#__osefirewall_advancepatterns` ";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        return ($result['count'] > 0) ? true : false;
    }

    public static function isCountryBlockConfigDBReady()
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_country');
        if (empty($data)) {
            return false;
        }
        $query = "SELECT COUNT(*) as `count` FROM `#__osefirewall_country` ";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        return ($result['count'] > 0) ? true : false;
    }

    private function isAdvanceSettingConfigEnable()
    {
        return $this->checkOseConfig('adRules', 'advscan');
    }

    public static function getLocaleString()
    {
        $lang = oseFirewall::getLocale();
        if (empty($lang)) {
            $lang = 'en-GB';
        }
        $lang = str_replace("-", "_", $lang);
        if (strpos('da_DK', $lang) === false && strpos('de_DE', $lang) === false && strpos('fr_FR', $lang) === false) {
            $lang = 'en_US';
        }

        return $lang;
    }

    public static function loadLanguage()
    {
        if (defined('OSE_OEM_LANG_TAG') && OSE_OEM_LANG_TAG == '') {
            $lang = self::getLocaleString();
        } else if (defined('OSE_OEM_LANG_TAG') && OSE_OEM_LANG_TAG != '') {
            $lang = OSE_OEM_LANG_TAG;
        } else {
            $lang = 'en_US';
        }
        require_once(OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'language' . ODS . 'oseLanguage.php');
        require_once(OSE_FWLANGUAGE . ODS . $lang . '.php');
    }

    public static function isDBReady()
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_backupath');
        if (!empty($data)) {
            self::checkVSTypeTable();
            $data = $oseDB2->isTableExists('#__osefirewall_vspatterns');
            $oseDB2->closeDBO();
        }
        //@todo add db version checker here
        self::checkNewDBTables();
        $ready = (!empty($data)) ? true : false;
        return $ready;
    }

    public static function isSigUpdated()
    {
        $count = self::getCountSignatures();
        if ($count < 12) {
            echo '<span class="label label-warning"><i class="glyphicon glyphicon-remove"></i> Warning: Firewall Outdated</span>&nbsp;&nbsp;<button class="btn btn-danger btn-xs fx-button" id="fixSignature" name="fixSignature" onClick="updateSignature(\'#rulesetsTable\')">Fix It</button>';
            if (OSE_CMS != 'joomla') {
                echo '<script type="text/javascript">document.getElementById("fixSignature").click();</script>';
            }
        } else {
            echo '<span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Firewall Updated</span>';
        }
    }

    private static function getCountSignatures()
    {
        $oseDB2 = self::getDBO();
        $query = "SELECT COUNT(id) AS count FROM `#__osefirewall_basicrules`;";
        $oseDB2->setQuery($query);
        $count = $oseDB2->loadResult();
        return ($count['count']);
    }

    // Version 3.4.0 Table checking;
    private static function checkVSTypeTable()
    {
        $oseDB2 = self::getDBO();
        if ($oseDB2->isTableExists('#__osefirewall_vstypes')) {
            $query = "SELECT COUNT(id) AS count1, COUNT(*) AS count2 FROM `#__osefirewall_vstypes` WHERE `type` = 'O_CLAMAV' ";
            $oseDB2->setQuery($query);
            $result = $oseDB2->loadResult();
            if ($result['count1'] == 1) {
                //$oseDB2->closeDBO();
                return true;
            } else {
                if ($result['count2'] > 0 && $oseDB2->isTableExists('#__osefirewall_vspatterns')) {
                    $queries = array();
                    $queries[] = "SET FOREIGN_KEY_CHECKS = 0";
                    $queries[] = "DROP TABLE IF EXISTS `#__osefirewall_files` ";
                    $queries[] = "DROP TABLE IF EXISTS `#__osefirewall_vstypes` ";
                    $queries[] = "DROP TABLE IF EXISTS `#__osefirewall_vspatterns` ";
                    $queries[] = "DROP TABLE IF EXISTS `#__osefirewall_malware` ";
                    $queries[] = "DROP TABLE IF EXISTS `#__osefirewall_logs` ";
                    foreach ($queries as $query) {
                        $oseDB2->setQuery($query);
                        $oseDB2->query();
                    }
                }
                return false;
            }
        }
    }

    public static function getGeoIPState()
    {
        $oseDB2 = self::getDBO();
        $query = "SHOW TABLES LIKE '#__ose_app_geoip' ";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        if (!empty($result)) {
            $query = "SELECT COUNT(`id`) as `count` FROM `#__ose_app_geoip` ";
            $oseDB2->setQuery($query);
            $result = $oseDB2->loadResult();
            $oseDB2->closeDBO();
            return ($result['count'] > 0) ? true : false;
        } else {
            return false;
        }
    }

    public static function isGeoDBReady()
    {
        $geoipState = self::getGeoIPState();
        return $geoipState;
    }

    public static function runController($controller, $action)
    {
        //global $centrora;
        $centrora = self::runApp();
        $requst = $centrora->runController($controller, $action);
        $requst->execute();
    }

    public static function testController($controller, $action)
    {
        //global $centrora;
        $centrora = self::runApp();
        $requst = $centrora->runController($controller, $action);
        return $requst->qatest();
    }

    public static function dashboard2()
    {
        self::runController('DashboardController', 'index');
    }

    public static function dashboard()
    {
        self::runController('DashboardController', 'index');
    }

    public static function manageips()
    {
        self::runController('ManageipsController', 'index');
    }

    public static function ipmanagement()
    {
//		self::runController ('IpManagementController', 'index');
        self::runController('IpManagementController', 'index');
    }

    public static function whitelistmgmt()
    {
        self::runController('WhitelistmgmtController', 'index');
    }

    public static function ipform()
    {
        self::runController('ManageipsController', 'ipform');
    }

    public static function rulesets()
    {
        self::runController('RulesetsController', 'index');
    }

    public static function configuration()
    {
        self::runController('ConfigurationController', 'index');
    }

    public static function aiscan()
    {
        self::runController('aiscanController', 'index');
    }

    public static function upload()
    {
        self::runController('UploadController', 'index');
    }

    public static function gitbackup()
    {
        self::runController('GitbackupController', 'index');
    }

    public static function gitbackupsuite()
    {
        self::runController('GitbackupsuiteController', 'index');
    }

    public static function passcode()
    {
        self::runController('PasscodeController', 'index');
    }

    public static function seoconfig()
    {
        self::runController('SeoconfigController', 'index');
    }

    public static function scanconfig()
    {
        self::runController('ScanconfigController', 'index');
    }

    public static function audit()
    {
        self::runController('AuditController', 'index');
    }

    public static function auditv7()
    {
        self::runController ('Auditv7Controller', 'index');
    }
    public static function countryblock()
    {
        self::runController('CountryblockController', 'index');
    }

    public static function cfscan()
    {
        self::runController('cfscanController', 'index');
    }

    public static function fpscan()
    {
        self::runController('fpscanController', 'index');
    }

    public static function adminemails()
    {
        self::runController('AdminemailsController', 'index');
    }

    public static function spamconfig()
    {
        $app = self::runApp();
        $app->runController('spamconfig', 'index');
    }

    public static function avconfig()
    {
        $app = self::runApp();
        $app->runController('avconfig', 'index');
    }

    public static function emailconfig()
    {
        $app = self::runApp();
        $app->runController('emailconfig', 'index');
    }

    public static function emailadmin()
    {
        $app = self::runApp();
        $app->runController('emailadmin', 'index');
    }

    public static function vsscan()
    {
        self::runController('VsscanController', 'index');
    }

    public static function vsreport()
    {
        self::runController('ScanreportController', 'index');
    }

    public static function vlscan()
    {
        self::runController('VlscanController', 'index');
    }

    public static function mfscan()
    {
        self::runController('MfscanController', 'index');
    }

    public static function surfscan()
    {
        self::runController('SurfscanController', 'index');
    }

    public static function variables()
    {
        self::runController('VariablesController', 'index');
    }

    public static function bsconfig()
    {
        self::runController('BsconfigController', 'index');
    }

    public static function bsconfigv7()
    {
        self::runController ('Bsconfigv7Controller', 'index');
    }
    public static function countryblockingv7()
    {
        self::runController ('Countryblockingv7Controller', 'index');
    }
    public static function banpagemgmt()
    {
        self::runController ('BanpagemgmtController', 'index');
    }
    public static function bsconfigv7stats()
    {
        self::runController ('Bsconfigv7statsController', 'index');
    }

    public static function emailnotificationv7()
    {
        self::runController ('Emailnotificationv7Controller', 'index');
    }
    public static function versionupdate()
    {
        $app = self::runApp();
        $app->runController('versionupdate', 'index');
    }

    public static function advancerulesets()
    {
        self::runController('AdvancerulesetsController', 'index');
    }

    public static function backup()
    {
        self::runController('BackupController', 'index');
    }

    public static function authentication()
    {
        self::runController('AuthenticationController', 'index');
    }

    public static function advancedbackup()
    {
        self::runController('AdvancedbackupController', 'index');
    }

    public static function login()
    {
        self::runController('LoginController', 'index');
    }

    public static function subscription()
    {
        self::runController('SubscriptionController', 'index');
    }

    public static function cronjobs()
    {
        self::runController('CronjobsController', 'index');
    }

    public static function permconfig()
    {
        self::runController('PermconfigController', 'index');
    }

    public static function clamav()
    {
        $app = self::runApp();
        $app->runController('clamav', 'index');
    }

    public static function apiconfig()
    {
        $app = self::runApp();
        $app->runController('apiconfig', 'index');
    }

    public static function activation()
    {
        self::runController('ActivationController', 'index');
    }

    public static function news()
    {
        self::runController('NewsController', 'index');
    }

    public static function showLogo()
    {
    }

    public static function callLibClass($folder, $classname)
    {
        require_once(OSE_FWFRAMEWORK . ODS . $folder . ODS . $classname . '.php');
    }

    public static function loadLibClass($folder, $classname)
    {
//		print_r(OSEAPPDIR."classes".ODS."Library".ODS.$folder.ODS.$classname.'.php');exit;
        require(OSEAPPDIR . "classes" . ODS . "Library" . ODS . $folder . ODS . $classname . '.php');
    }

    public static function loadBackendBasic()
    {
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        oseFirewall::loadallJs($cs);
        oseFirewall::loadbackendCSS($cs, $baseUrl);
        $cs->registerScript('oseAjax', oseFirewall::getAjaxScript(), CClientScript::POS_BEGIN);
    }

    public static function loadFrontendBasic()
    {
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        oseFirewall::loadallJs($cs);
        oseFirewall::loadFrontendCSS($cs, $baseUrl);
        $cs->registerScript('oseAjax', oseFirewall::getAjaxScript(), CClientScript::POS_BEGIN);
    }

    public static function loadBackendAll()
    {
        oseFirewall::loadBackendBasic();
        oseFirewall::loadGridAssets();
        oseFirewall::loadFormAssets();
    }

    public static function loadFrontendAll()
    {
        oseFirewall::loadFrontendBasic();
        oseFirewall::loadGridAssets();
        oseFirewall::loadFormAssets();
    }

    public function getDebugMode()
    {
        return $this->checkOseConfig('debugMode', 'scan');
    }

    private function checkOseConfig($key, $type)
    {
        if (!class_exists('PDO')) {
            return false;
        }
        $dbConfig = self::getConfig();
        if (!strstr($dbConfig->host, 'sock')) {
            $hostVar = $this->splitHost($dbConfig->host);
            $dbo = new PDO("mysql:host=" . $hostVar['host'] . ";dbname=" . $dbConfig->db . $hostVar['port'], $dbConfig->user, $dbConfig->password);
        } else {
            $host = explode(":", $dbConfig->host);
            $dbo = new PDO("mysql:unix_socket=" . $host[1] . ";dbname=" . $dbConfig->db, $dbConfig->user, $dbConfig->password);
        }
        $stmt = $dbo->query("SHOW TABLES LIKE '" . $dbConfig->prefix . "ose_secConfig' ");
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $result = $stmt->fetch();
        if (empty($result)) {
            $dbo = null;
            return true;
        } else {
            $stmt = $dbo->query("SELECT `value` FROM `" . $dbConfig->prefix . "ose_secConfig` WHERE `key` = '" . $key . "' AND `type` = '" . $type . "'");
            if (!empty($stmt)) {
                $stmt->setFetchMode(PDO::FETCH_OBJ);
                $result = $stmt->fetch();
                $dbo = null;
                return (empty($result) || ($result->value == 0)) ? false : true;
            } else {
                return false;
            }
        }
    }

    private function splitHost($host)
    {
        $tmp = explode(":", $host);
        $return = array();
        $return["host"] = $tmp[0];
        $return["port"] = "";
        if (!empty($tmp[1])) {
            $return["port"] = ";port=" . $tmp[1];
        }
        return $return;
    }

    public function checkSystem()
    {
        $return = array();
        $return[0] = true;
        $return[1] = null;
        $phpVersion = $this->comparePHPVersion();
        if ($phpVersion == false) {
            $return[0] = false;
            $return[1] = 'Centrora Security 4.0.0+ requires PHP version 5.3.0 or above, please contact your hosting company to upgrade the PHP version.';
        }
        if (!class_exists('PDO')) {
            $return[0] = false;
            $return[1] = 'Class PDO not found in your hosting environment, please follow this <a href="http://docs.centrora.com/en/latest/pdo-class.html" target="_blank" >tutorial</a> to enable the PDO class before using the Firewall System.';
        }
        return $return;
    }

    public function comparePHPVersion()
    {
        return (version_compare(PHP_VERSION, '5.3.0') >= 0) ? true : false;
    }

    public function runReport()
    {
        oseFirewall::callLibClass('audit', 'audit');
        $audit = new oseFirewallAudit ();
        $audit->runReport();
    }

    public static function getTime()
    {
        self::loadDateClass();
        $oseDatetime = new oseDatetime();
        $oseDatetime->setFormat("Y-m-d H:i:s");
        $time = $oseDatetime->getDateTime();
        return $time;
    }

    public static function enhanceSysSecurity()
    {
        oseFirewall::callLibClass('audit', 'audit');
        $audit = new oseFirewallAudit ();
        $audit->enhanceSysSecurity();
    }

    public static function getConfiguration($type)
    {
        $db = self::getDBO();
        $return = array();
        $query = "SELECT `key`, `value` FROM `#__ose_secConfig` WHERE `type` = " . $db->quoteValue($type);
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $db->closeDBO();
        if (!empty($results)) {
            foreach ($results as $result) {
                if ($type == 'l2var') {
                    $return['data'][$result->key] = (int)$result->value;
                } else {
                    $return['data'][$result->key] = self::convertValue($result->key, $result->value);
                }
            }
        } else {
            $return['data'] = array();
        }
        $return['success'] = true;
        return $return;
    }

    private static function convertValue($key, $value)
    {
        if (is_numeric($value)) {
            $value = intval($value);
        }
        return $value;
    }

    public static function checkDBReady()
    {
        if (!oseFirewall::isDBReady()) {
            if (OSE_CMS == 'joomla') {
                $url = 'index.php?option=com_ose_firewall&view=configuration';
                header('Location: ' . $url);
            } else {
                $url = 'admin.php?page=ose_fw_configuration';
                echo '<script type="text/javascript">window.location = "' . $url . '";</script>';
            }
        }
    }

    //gitlog
    public static function getGitLog()
    {
        $db = oseFirewall::getDBO();
        if (!self::isSuite()) {
            $query = "SELECT * FROM '#__osefirewall_gitlog'";
            $db->setQuery($query);
            $result = $db->loadObject();
            return (!empty($result)) ? $result->value : null;
        } else {
            //SUITE VERSION
            //GET LOG BASED ON THE USER NAME
            $accountName = oRequest::getVar('account_name', null);
            $accountName = self::cleanupVar($accountName);
            $query = "SELECT * FROM '#__osefirewall_gitlog' WHERE `account_name` = " . $accountName;
            $db->setQuery($query);
            $result = $db->loadObject();
            return (!empty($result)) ? $result->value : null;
        }
    }

    public static function getWebKey()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'webkey' AND `type` = 'panel'";
        $db->setQuery($query);
        $results = $db->loadObject();
        if (!empty($results->value)) {
            return $results->value;
        } else {
            return false;
        }
    }


    public static function checkSubscriptionStatus($redirect = true)
    {
        if (TEST_ENV) {
            return true;
        }

        $db = oseFirewall::getDBO();
        $query = "SELECT * FROM `#__ose_secConfig` WHERE (`key` = 'profileID' OR `key` = 'profileStatus' OR `key` = 'webkey') AND `type` = 'panel'";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $return = array();
        if(empty($results))
        {
            return false;
        }
        foreach ($results as $result) {
            $return[$result->key] = $result->value;
        }
        if (!empty($return['profileID']) && !empty($return['webkey']) && $return['profileStatus'] == 2) {
            return true;
        } else {
            if ($redirect == true) {
                oseFirewall::redirectLogin();
            } else {
                // TODO: need to change, this is only for testing
                return false;
            }
        }
    }

    public static function checkWebkey()
    {
        $webKeys = oseFirewall::getWebkeys();
        if (empty($webKeys['webkey'])) {
            oseFirewall::redirectLogin();
        }
    }

    public static function checkSubscription()
    {
        $webKeys = oseFirewall::getWebkeys();
        if (!empty($webKeys['webkey']) && $webKeys['verified'] == true) {
            oseFirewall::redirectSubscription();
        }
    }

    protected static function getWebkeys()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT * FROM `#__ose_secConfig` WHERE (`key` = 'webkey' OR `key` = 'verified') AND `type` = 'panel'";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $return = array();
        foreach ($results as $result) {
            $return[$result->key] = $result->value;
        }
        return $return;
    }

    public static function preRequisitiesCheck()
    {
        return (version_compare(PHP_VERSION, '5.3.0') < 0) ? false : true;
    }

    public static function showNotReady()
    {
        die('Centrora Security requires PHP 5.3.0 to work properly, please upgrade your PHP version to 5.3.0 or above');
    }

    public static function getActiveReceivers()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `A_email`,`A_name` FROM `#__osefirewall_adminemails` WHERE (`A_status` = 'active')";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i]->name = $result->A_name;
            $return[$i]->email = $result->A_email;
            $i++;
        }
        return $return;
    }

    public static function getActiveReceiveEmails()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `A_email` FROM `#__osefirewall_adminemails` WHERE (`A_status` = 'active')";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $i = 0;
        $return = array();
        foreach ($results as $result) {
            $return[$i] = $result->A_email;
            $i++;
        }
        return $return;
    }

    //suraj
    public static function checkNewDBTables()
    {
        $oseDB2 = self::getDBO();
//		echo "inside cehck new tables"; exit;
        $datadomains = $oseDB2->isTableExists('#__osefirewall_domains');
        if (!$datadomains) {
            $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_domains` (
                          `D_id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `D_address` VARCHAR(200) NOT NULL,
                          PRIMARY KEY (`D_id`),
                          UNIQUE KEY `D_address` (`D_address`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $dataadminemails = $oseDB2->isTableExists('#__osefirewall_adminemails');
        if (!$dataadminemails) {
            $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_adminemails` (
                          `A_id`     INT(11)     NOT NULL AUTO_INCREMENT,
                          `A_name`   TEXT        NOT NULL,
                          `A_email`  TEXT        NOT NULL,
                          `A_status` VARCHAR(10) NOT NULL,
                          `D_id`     INT(11),
                          PRIMARY KEY (`A_id`),
                          INDEX `#__osefirewall_adminemails_idx1` (`D_id`),
                          FOREIGN KEY (`D_id`) REFERENCES `#__osefirewall_domains` (`D_id`)
                            ON UPDATE CASCADE
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }
        $dataupload = $oseDB2->isTableExists('#__osefirewall_fileuploadext');
        if (!$dataupload) {
            $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_fileuploadext` (
                     `ext_id` int(11) NOT NULL AUTO_INCREMENT,
                     `ext_name` varchar(200) NOT NULL,
                     `ext_type` varchar(200) NOT NULL,
                     `ext_status` tinyint(1) NOT NULL,
                     PRIMARY KEY (`ext_id`),
                     UNIQUE KEY `ext_name` (`ext_name`)
                     ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; ";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
            oseFirewallBase::loadInstaller();
            $installer = new oseFirewallInstaller();
            $dbFile = OSE_FWDATA . ODS . 'dataFileExtension.sql';
            $result = $installer->insertFileExtension($dbFile);
            $installer->closeDBO();
        }
        $datauploadLog = $oseDB2->isTableExists('#__osefirewall_fileuploadlog');
        if (!$datauploadLog) {
            $query = "CREATE TABLE `#__osefirewall_fileuploadlog` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `ip_id` int(11) NOT NULL,
                      `file_name` varchar(100) DEFAULT NULL,
                      `file_type_id` int(11) NOT NULL,
                      `validation_status` tinyint(1) NOT NULL,
                      `vs_scan_status` tinyint(1) NOT NULL,
                      `datetime` datetime NOT NULL,
                      PRIMARY KEY (`id`),
                      INDEX `osefirewall_fileuploadlog_idx1` (`id`),
                      FOREIGN KEY (`ip_id`) REFERENCES `#__osefirewall_acl` (`id`) ON UPDATE CASCADE ON DELETE CASCADE ,
                      FOREIGN KEY (`file_type_id`) REFERENCES `#__osefirewall_fileuploadext` (`ext_id`) ON UPDATE CASCADE ON DELETE CASCADE
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        //table to store all the commits in the database
        $gitLog = $oseDB2->isTableExists('#__osefirewall_gitlog');
        if (!$gitLog) {
            $query = "CREATE TABLE `#__osefirewall_gitlog` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                  `commit_id` varchar(50) NOT NULL,
                  `commit_time` varchar(100) NOT NULL,
                  `commit_message` varchar(200) NOT NULL,
                  `is_head` boolean NOT NULL,
                  PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }


        $vlscanner = $oseDB2->isTableExists('#__osefirewall_vlscanner');
        if (!$vlscanner) {
            $query = "CREATE TABLE `#__osefirewall_vlscanner` (
                      `vls_id` int(11) NOT NULL AUTO_INCREMENT,
                      `vls_type` tinyint(4) NOT NULL,
                      `content` longtext NOT NULL,
                      PRIMARY KEY (`vls_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $scanhist = $oseDB2->isTableExists('#__osefirewall_scanhist');
        if (!$scanhist) {
            $query = "CREATE TABLE `#__osefirewall_scanhist` (
                      `scanhist_id` int(11) NOT NULL AUTO_INCREMENT,
                      `super_type` varchar(10) NOT NULL,
                      `sub_type` int(11) NOT NULL,
                      `content` longtext NOT NULL,
                      `inserted_on` datetime NOT NULL,
                      PRIMARY KEY (`scanhist_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $vshash = $oseDB2->isTableExists('#__osefirewall_vshash');
        if (!$vshash) {
            $query = "CREATE TABLE `#__osefirewall_vshash` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `type` tinyint(4) NOT NULL DEFAULT '0',
                      `name` varchar(100) NOT NULL,
                      `hash` text NOT NULL,
                      `inserted_on` datetime DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `unique_id` (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=8883 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }

        $aiscan = $oseDB2->isTableExists('#__osefirewall_aiscan');
        if (!$aiscan) {
            $query = "CREATE TABLE `#__osefirewall_aiscan` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `filename` varchar(250) NOT NULL,
                      `content` tinyint(1) NOT NULL DEFAULT '0',
                      `name` tinyint(1) NOT NULL,
                      `md5` tinyint(1) NOT NULL,
                      `date` tinyint(1) NOT NULL,
                      `pattern` tinyint(1) NOT NULL,
                      `size` tinyint(1) NOT NULL,
                      `score` tinyint(100) NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `filename` (`filename`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=8883 DEFAULT CHARSET=utf8";
            $oseDB2->setQuery($query);
            $oseDB2->loadResult();
        }


        $versioninfo_exists = $oseDB2->isTableExists('#__osefirewall_versioninfo');
        if (!$versioninfo_exists) {
            self::createVersionInfoDbs($oseDB2);
        }


        $dbgitsuite_tableExists = $oseDB2->isTableExists('#__osefirewall_dbconfiggit');
        if (!$dbgitsuite_tableExists) {
            self::createGitsuiteDBConfig($oseDB2);

        }
        $cronsettings_tableExists = $oseDB2->isTableExists('#__osefirewall_cronsettings');
        if (!$cronsettings_tableExists) {
            self::createCronSettingsTable($oseDB2);
        }

        self::createGitBackupErrorLogTable();
        self::createGitBackupLogTable();

        //initialis the table to save the firewall scannner v7 configurations
        $fwv7config_exists = $oseDB2->isTableExists('#__osefirewall_fwscannerv7Config');
        if(!$fwv7config_exists)
        {
            self::initialiseFwscannerV7Settings($oseDB2);
        }

        //intialiase the ip management table
        $iptableexists = $oseDB2->isTableExists('#__osefirewall_ipmanagement');
        if (!$iptableexists) {
            //if the ip table does not exist
            self::createBlockIpTable($oseDB2);
        }

        //intitalise the white list table
        $whitelistmgmt_tableexists = $oseDB2->isTableExists('#__osefirewall_whitelistmgmt');
        if (!$whitelistmgmt_tableexists) {
            //if the whitelist table does not exist
            self::createWhitelistMgmtTable($oseDB2);
        }

        $fileuploadlogv7table_tableexists = $oseDB2->isTableExists('#__osefirewall_fileuploadlogv7');
        if (!$fileuploadlogv7table_tableexists) {
            //if the fileuploadv7 table does not exist
            self::createFileUploadTableV7($oseDB2);
        }

        $emailNotificationMgmt =  $oseDB2->isTableExists('#__osefirewall_emailnotificationmgmtv7');
        if(!$emailNotificationMgmt)
        {
            self::createEmailNotificationMgmt($oseDB2);
            self::initialiseEmailNotificationSettingsV7($oseDB2);
        }





        self::ammendDBTables($oseDB2);

    }

    private static function createVersionInfoDbs($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_versioninfo` (
				  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
				  `date` VARCHAR(200) NOT NULL ,
				  `type` VARCHAR(200) NOT NULL ,
				  PRIMARY KEY (`id`)
				)
				ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }


    private static function ammendDBTables($oseDB2)
    {
        #run check on table to ammend
        $addcolumn_detcontdetail = self::checkTabledetcontdetail($oseDB2);
        $addcolumn_files = self::checkTablefiles($oseDB2);
        $addcolumn_vspatterns = self::checkTablevspatterns($oseDB2);
        if ($addcolumn_detcontdetail) {
            #Create New DateTime Column
            self::addcolumndetcontdetail($oseDB2);
        }
        if ($addcolumn_files) {
            #Create new content column
            self::addcolumnfiles($oseDB2);
        }

    }

    private static function checkTabledetcontdetail($oseDB2, $return = true)
    {
        $query = 'DESCRIBE #__osefirewall_detcontdetail;';
        $oseDB2->setQuery($query);
        $result = ($oseDB2->loadObjectList());
        #Run Check for ammending
        foreach ($result as $key => $value) {
            if ($value->Field == 'inserted_on') {
                $return = false;
                break;
            }
        }
        return $return;
    }

    private static function addcolumndetcontdetail($oseDB2)
    {
        $query = 'ALTER TABLE `#__osefirewall_detcontdetail` ADD inserted_on DATETIME NOT NULL;';
        $oseDB2->setQuery($query);
        $oseDB2->query();
        #Add ACL Date for Existing Data for backward Compatibility
        $query = 'UPDATE `#__osefirewall_detcontdetail` as dcd
				INNER JOIN `#__osefirewall_detected` as dc ON dcd.detattacktype_id = dc.detattacktype_id
				INNER JOIN `#__osefirewall_acl` as acl ON dc.acl_id = acl.id
				SET dcd.inserted_on = acl.datetime';
        $oseDB2->setQuery($query);
        $oseDB2->query();
    }

    private static function checkTablefiles($oseDB2, $return = true)
    {
        $query = 'DESCRIBE #__osefirewall_files;';
        $oseDB2->setQuery($query);
        $result = ($oseDB2->loadObjectList());
        #Run Check for ammending
        foreach ($result as $key => $value) {
            if ($value->Field == 'content') {
                $return = false;
                break;
            }
        }
        return $return;
    }

    private static function addcolumnfiles($oseDB2)
    {
        $query = 'ALTER TABLE `#__osefirewall_files` ADD content text NULL;';
        $oseDB2->setQuery($query);
        $oseDB2->query();
    }

    public static function affiliateAccountExists()
    {
        $config = self::getConfiguration('panel');
        return (!empty($config['data']['trackingCode'])) ? $config['data']['trackingCode'] : null;
    }

    protected static function checkNewsUpdated()
    {
        oseFirewall::callLibClass('panel', 'panel');
        $panel = new panel ();
        return $panel->checkNewsUpdated();
    }

    private static function checkTablevspatterns($oseDB2, $return = true)
    {
        $query = 'DESCRIBE #__osefirewall_vspatterns;';
        $oseDB2->setQuery($query);
        $result = ($oseDB2->loadObjectList());
        #Run Check for ammending
        foreach ($result as $key => $value) {
            if ($value->Field == 'badstring') {
                $return = false;
                break;
            }
        }
        return $return;
    }

    private static function addcolumnvspatterns($oseDB2)
    {
        $query = 'ALTER TABLE `#__osefirewall_vspatterns` ADD badstring TEXT NOT NULL;';
        $oseDB2->setQuery($query);
        $oseDB2->query();
    }

    public static function prepareSuccessMessage($message)
    {
        $result['status'] = 1;
        $result['info'] = $message;
        return $result;
    }

    public static function prepareErrorMessage($message)
    {
        $result['status'] = 0;
        $result['info'] = $message;
        return $result;
    }

    public static function prepareCustomErrorMessage($message, $impact, $details = false)
    {
        $result['status'] = 0;
        $result['info'] = $message;
        $result['impact'] = $impact;
        if ($details) {
            $result['details'] = $details;
        }
        return $result;
    }

    public static function prepareCustomMessage($status, $message,$message2 = false)
    {
        $result['status'] = $status;
        $result['info'] = $message;
        $result['info2'] = $message2;
        return $result;
    }

    public static function prepareCustomDetailedMessage($status, $message, $details)
    {
        $result['status'] = $status;
        $result['info'] = $message;
        $result['details'] = $details;
        return $result;
    }

    public static function createBlockIpTable($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_ipmanagement` (
                          `id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `ip` VARCHAR(200) NOT NULL UNIQUE,
                          `status` INT(11) NOT NULL,
                          `ischecked` TINYINT(1) NOT NULL,
                          `isspam`  TINYINT(1) NOT NULL,
                          `lastchecked`  VARCHAR(200) NOT NULL,
                          `dateadded`  TIMESTAMP,
                          PRIMARY KEY (`id`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    public static function createWhitelistMgmtTable($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_whitelistmgmt` (
                          `id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `entity` VARCHAR(200) NOT NULL UNIQUE,
                          `entity_type` VARCHAR(200) NOT NULL ,
                          `request_type` VARCHAR(200) NOT NULL ,
                          `status` INT(11) NOT NULL,
                          PRIMARY KEY (`id`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    public static function createFileUploadTableV7($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_fileuploadlogv7` (
				  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
				  `ip` VARCHAR(200) NOT NULL ,
				  `filename` VARCHAR(200) NOT NULL ,
				  `filetype`  VARCHAR(200) NOT NULL ,
				  `validationstatus` tinyint(1) NOT NULL,
				  `virusscanstatus` tinyint(1) NOT NULL,
                  `datetime` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				)
				ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }


    public static function createEmailNotificationMgmt($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_emailnotificationmgmtv7` (
				  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
				  `key` VARCHAR(200) NOT NULL ,
				  `value` tinyint(1) NOT NULL,
				  PRIMARY KEY (`id`)
				)
				ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    public static function initialiseEmailNotificationSettingsV7($oseDB2)
    {
        $emailNotificationSettingsV7table = '#__osefirewall_emailnotificationmgmtv7';
        $vararray = array(
            '1'=>array(
                'key' =>'blocked_ip',
                'value'=>1,
            ),
            '2'=>array(
                'key' =>'googleauth',
                'value'=>1,
            ),
            '3'=>array(
                'key' =>'stats',
                'value'=>0,
            ),
            '4'=>array(
                'key' =>'timestats',
                'value'=>0,
            ),
            '5'=>array(
                'key' =>'attackstats',
                'value'=>0,
            ),
            '6'=>array(
                'key' =>'ipstats',
                'value'=>0,
            ),
            '7'=>array(
                'key' =>'browserstats',
                'value'=>0,
            ),
            '8'=>array(
                'key' =>'stats_daily',
                'value'=>0,
            ),
            '9'=>array(
                'key' =>'stats_weekly',
                'value'=>0,
            ),
            '10'=>array(
                'key' =>'stats_fortnigthly',
                'value'=>0,
            ),
            '11'=>array(
                'key' =>'stats_monthly',
                'value'=>0,
            ),
        );
        foreach($vararray as $key=>$value)
        {
            $id = $oseDB2->addData ('insert', $emailNotificationSettingsV7table, '', '', $value);
            $oseDB2->closeDBO ();
        }
        if($id == 0)
        {
            return self::prepareErrorMessage("Problem in initialising the configuration table ");
        }else {
            return self::prepareSuccessMessage("The table has been initialised successfully");
        }
    }


    public static function cleanupVar($var)
    {
        return str_replace(array('"', "'", '<', '>'), "", $var);
    }


    //returns the last updated date for a type service
    /*
    type :
    'advancedrules' => advance rules files
    */

    public static function getversionInfo($type)
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_versioninfo');
        if (empty($data)) {
            return false;
        }
        $query = "SELECT `date` FROM `#__osefirewall_versioninfo`WHERE `type` = '" . $type . "'";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        return (!empty($result)) ? $result['date'] : false;
    }

    public static function updateVersionInfo($type)
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_versioninfo');
        if (empty($data)) {
            return self::prepareErrorMessage('The version info table dows not exists ');
        }
        if (self::versionInfoExists('advancerules')) {
            //update the record
            $vararray = array(
                'date' => date('d-m-Y H:i:s'),
            );
            $result = $oseDB2->addData('update', '#__osefirewall_versioninfo', 'type', $type, $vararray);
        } else {
            //insert the record
            $vararray = array(
                'date' => date('d-m-Y H:i:s'),
                'type' => 'advancerules',
            );
            $result = $oseDB2->addData('insert', '#__osefirewall_versioninfo', '', '', $vararray);
        }
        if ($result !== 0) {
            return self::prepareSuccessMessage('The Advanced pattern version has been updated successfully');
        } else {
            return self::prepareErrorMessage('There was some problem in updating the advance pattern version ');
        }
    }

    public static function versionInfoExists($type)
    {
        $oseDB2 = self::getDBO();
        $query = "SELECT `id` FROM `#__osefirewall_versioninfo`WHERE `type` = '" . $type . "'";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    //if the db was updated last day
    //check the record int the local databse
    public static function checkedWithinLastDay($lastcheck)
    {
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($lastcheck);
        $interval = $datetime1->diff($datetime2);
        if ($interval->y >= 1 || $interval->m >= 1 || $interval->d >= 1 || $interval->h >= 24) {
            return true;
        } else {
            return false;
        }
    }

    public static function createDomainValidationTable($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_domain_validation` (
                          `id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `domain` VARCHAR(200) NOT NULL UNIQUE,
                          `email` VARCHAR(200) NOT NULL ,
                          `validation_date` datetime NOT NULL,
                          PRIMARY KEY (`id`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    public static function createSslCertTable($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_sslcert` (
                          `id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `domain_id`      INT(11)      NOT NULL,
                          `ssl_domain` VARCHAR(200) NOT NULL UNIQUE,
                          `ssl_order_id` VARCHAR(200) NOT NULL ,
                          `issued_date` datetime NOT NULL,
                          PRIMARY KEY (`id`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    public static function isSuite()
    {
        if (class_exists('Sconfig')) {
            return true;
        } else {
            return false;
        }
    }

    public static function createGitsuiteDBConfig($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_dbconfiggit` (
                          `id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `accountname` VARCHAR(200) NOT NULL ,
                          `accountpath` VARCHAR(200) NOT NULL ,
                          `dbconfig` VARCHAR(200) NOT NULL UNIQUE,
                          PRIMARY KEY (`id`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    private static function createVersionInfoDb($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_versions` (
             `version_id` int(11) NOT NULL AUTO_INCREMENT,
              `number` varchar(32) NOT NULL,
              `type` varchar(4) NOT NULL,
              PRIMARY KEY (`version_id`)
            )
        ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }


    public static function getVersionInfos($type)
    {
        $oseDB2 = self::getDBO();
        $query = "SELECT * FROM `#__osefirewall_versions` WHERE `type` = '" . $type . "'";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadObject();
        if (empty($result)) {
            return false;
        } else {
            return $result->number;
        }
    }

    public static function UpdateVersionInfos($type, $value)
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT * FROM `#__osefirewall_versions` WHERE `type` = " . $db->QuoteValue(substr($type, 0, 4));
        $db->setQuery($query);
        $result = $db->loadObject();
        if (empty($result)) {
            $varValues = array(
                'version_id' => 'NULL',
                'number' => $value,
                'type' => $type
            );
            $id = $db->addData('insert', '#__osefirewall_versions', null, null, $varValues);
        } else {
            $varValues = array(
                'number' => $value,
                'type' => $type
            );
            $id = $db->addData('update', '#__osefirewall_versions', 'version_id', $result->version_id, $varValues);
        }
        $db->closeDBO();
        return $id;
    }

    private static function createCronSettingsTable($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_cronsettings` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
              `type` VARCHAR(200) NOT NULL,
              `value` VARCHAR(200) NOT NULL,
              PRIMARY KEY (`id`)
            )
        ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }


    public static function createGitBackupLogTable()
    {
        $oseDB2 = self::getDBO();
        $gitLog = $oseDB2->isTableExists('#__osefirewall_backuplog');
        if (!$gitLog) {
            if (self::isSuite()) {
                $query = "CREATE TABLE `#__osefirewall_backuplog` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                  `message` varchar(65535) NOT NULL,
                  `account` varchar(65535) ,
                   `datetime` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            } else {
                $query = "CREATE TABLE `#__osefirewall_backuplog` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                  `message` varchar(65535) NOT NULL,
                   `datetime` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            }

            $oseDB2->setQuery($query);
            $result = $oseDB2->loadResult();
            return $result;
        }
    }

    public static function createGitBackupErrorLogTable()
    {
        $oseDB2 = self::getDBO();
        $gitLog = $oseDB2->isTableExists('#__osefirewall_errorlog');
        if (!$gitLog) {
            if (self::isSuite()) {
                $query = "CREATE TABLE `#__osefirewall_errorlog` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                  `message` varchar(65535) NOT NULL,
                  `account` varchar(65535) ,
                   `datetime` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            } else {
                $query = "CREATE TABLE `#__osefirewall_errorlog` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                  `message` varchar(65535) NOT NULL,
                   `datetime` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            }
            $oseDB2->setQuery($query);
            $result = $oseDB2->loadResult();
            return $result;
        }
    }


    public static function loadEmailTemplate()
    {
        $body = file_get_contents(OSE_FWFRAMEWORK . ODS . 'emails' . ODS . 'emailv7.tpl');
        return $body;
    }

    public static function formateTemplate($template, $subject, $body)
    {
        $template = str_replace('{name}', 'Administrator', $template);
        $template = str_replace('{header}', $subject, $template);
        $template = str_replace('{content}', $body, $template);
        return $template;
    }

    public static function getAccountPath($accountname)
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `accountpath` FROM `#__osefirewall_dbconfiggit` WHERE `accountname`=" . $db->quoteValue($accountname);
        $db->setQuery($query);
        $result = $db->loadResultList();
        if (isset($result[0]) && isset($result[0]['accountpath'])) {
            return $result[0]['accountpath'];
        } else {
            return false;
        }
    }

    public static function suiteDomainValidation($domain)
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            if (strcmp($_SERVER['HTTP_HOST'].'/',$domain) == 0) {
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }


    public static function createConfigTable($oseDB2)
    {
        $query = "CREATE TABLE IF NOT EXISTS `#__osefirewall_fwscannerv7Config` (
                          `id`      INT(11)      NOT NULL AUTO_INCREMENT,
                          `key` VARCHAR(200) NOT NULL UNIQUE,
                          `value` TEXT NOT NULL,
                          `type` VARCHAR(200) NOT NULL,
                          PRIMARY KEY (`id`)
                        )
                          ENGINE = InnoDB  DEFAULT CHARSET = utf8  AUTO_INCREMENT = 1; ";
        $oseDB2->setQuery($query);
        $results = $oseDB2->loadObject();
        return $results;
    }

    public static function initialiseFwscannerV7Settings($oseDB2)
    {
        self::createConfigTable($oseDB2);
        $id = 0;
        $configtable= '#__osefirewall_fwscannerv7Config';
        $vararray = array(
            '1'=>array(
                'key' =>'firewall_status',
                'value'=>0,
                'type' => 'status',
            ),
            '2'=>array(
                'key' =>'cross_site_scripting',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '3'=>array(
                'key' =>'cross_site_request_forgery',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '4'=>array(
                'key' =>'sql_injection',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '5'=>array(
                'key' =>'remote_file_inclusion',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '6'=>array(
                'key' =>'local_file_inclusion',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '7'=>array(
                'key' =>'web_admin_email',
                'value'=> 'Please enter your email address',
                'type' => 'email',
            ),
            '8'=>array(
                'key' =>'directory_traversal',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '9'=>array(
                'key' =>'firewall_sensitivity',
                'value'=>35,
                'type' => 'sensitivity',
            ),
            '10'=>array(
                'key' =>'local_file_modification_attempt',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '11'=>array(
                'key' =>'spamming',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '12'=>array(
                'key' =>'formate_string_attack',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '13'=>array(
                'key' =>'inconsistent_file_type',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '14'=>array(
                'key' =>'virus_file',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '15'=>array(
                'key' =>'brute_force_protection',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '16'=>array(
                'key' =>'check_user_agents',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '17'=>array(
                'key' =>'mode',
                'value'=>0,
                'type' => 'attack_type',
            ),
            '18'=>array(
                'key' =>'seo_page_title',
                'value'=>0,
                'type' => 'seo',
            ),
            '19'=>array(
                'key' =>'seo_meta_keywords',
                'value'=>0,
                'type' => 'seo',
            ),
            '20'=>array(
                'key' =>'seo_meta_description',
                'value'=>0,
                'type' => 'seo',
            ),
            '21'=>array(
                'key' =>'seo_meta_generator',
                'value'=>0,
                'type' => 'seo',
            ),
            '22'=>array(
                'key' =>'seo_google_bots',
                'value'=>0,
                'type' => 'seo',
            ),
            '23'=>array(
                'key' =>'seo_yahoo_bots',
                'value'=>0,
                'type' => 'seo',
            ),
            '24'=>array(
                'key' =>'seo_msn_bots',
                'value'=>0,
                'type' => 'seo',
            ),
            '25'=>array(
                'key' =>'bruteforce_attempts',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '26'=>array(
                'key' =>'bruteforce_timelimit',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '27'=>array(
                'key' =>'google_authentication_login',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '28'=>array(
                'key' =>'google_authentication_banpage',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '29'=>array(
                'key' =>'google_authentication_secret_key',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '30'=>array(
                'key' =>'ban_page_mode',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '31'=>array(
                'key' =>'ban_page_content',
                'value'=>'<p>Dear Sir / Madam</p>
                            <p>Your IP is temporarily blocked for security reasons. Please contact us at info@your-website.com if you believe this is a false alert.</p>
                            <p>Best regards.</p>
                            <p>Management Team</p>',
                'type' => 'bruteforce',
            ),
            '32'=>array(
                'key' =>'ban_page_url',
                'value'=>0,
                'type' => 'bruteforce',
            ),
            '33'=>array(
                'key' =>'attack_threshold',
                'value'=>10,
                'type' => 'blockmode',
            ),
            '34'=>array(
                'key' =>'secureKey',
                'value'=>'',
                'type' => 'bruteforce',
            ),
        );

        foreach($vararray as $key=>$value)
        {
            $id = $oseDB2->addData ('insert', $configtable, '', '', $value);
            $oseDB2->closeDBO ();
        }

        if($id == 0)
        {
            return self::prepareErrorMessage("Problem in initialising the configuration table ");
        }else {
            return self::prepareSuccessMessage("The table has been initialised successfully");
        }
    }

    public function sendRequestReturnRes($content,$url = false)
    {
        if($url !== false) {
            $this->live_url = $url;
        }
        $query = $this->mergeString ($content);
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->live_url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS =>$query,
            CURLOPT_USERAGENT => 'Centrora Security Plugin Request Agent',
            CURLOPT_SSL_VERIFYPEER => false
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }

    public function formatAdvRulesServerVersion($datetime)
    {
        $year = substr($datetime,0,4);
        $month = substr($datetime,4,2);
        $date = substr($datetime,6,2);
        $hour = substr($datetime,8,2);
        $min = substr($datetime,10,2);
        $sec = substr($datetime,12,2);
        $result = $year.'/'.$month.'/'.$date.' '.$hour.':'.$min.':'.$sec;
        return $result;
    }

    private function mergeString($content)
    {
        $url = "";
        $tmp = array();
        foreach ($content as $key => $value)
        {
            $tmp[] = @$key.'='.urlencode(@$value);
        }
        $workstring = implode("&", $tmp);
        return $workstring;
    }


    public static function isFirewallV7Active()
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_fwscannerv7Config');
        if (empty($data)) {
            return false;
        }
        $query = "SELECT `value` FROM `#__osefirewall_fwscannerv7Config` WHERE `key`= 'firewall_status'";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        if(!empty($result) && isset($result['value']) && $result['value'] ==1)
        {
            return true;
        }else{
            return false;
        }
    }

    public static function isFirewallV6Active()
    {
        $results =  self::getConfiguration('scan');
        if ($results['success'] == 1 && isset($results['data']['devMode']) && $results['data']['devMode'] ==0) {
            return true;
        }else{
            return false;
        }
    }

    public static function anyActiveRules()
    {
        $oseDB2 = self::getDBO();
        $data = $oseDB2->isTableExists('#__osefirewall_fwscannerv7Config');
        if (empty($data)) {
            return false;
        }
        $query = "SELECT `key` FROM `#__osefirewall_fwscannerv7Config` WHERE `type`= 'attack_type' AND `value`= 1 AND `key` NOT IN ('mode')";
        $oseDB2->setQuery($query);
        $result = $oseDB2->loadResult();
        if(!empty($result))
        {
            return true;
        }else{
            return false;
        }
    }
    public static function getRegisteredWebsiteDomain()
    {
        oseFirewall::callLibClass('panel','panel');
        $panel = new panel();
        $domain = $panel->getResiteredDomain();
        if(!empty($domain) && isset($domain['domain'])) {
            if ((substr($domain['domain'], -1)) == '/') {
                $result['domain'] =  substr($domain['domain'], 0, -1);
                $result['protocol'] = $domain['protocol'];
                return $result;
            } else {
                $result['domain'] =  $domain['domain'];
                $result['protocol'] = $domain['protocol'];
                return $result;
            }
        }else{
            return false;
        }
    }







}


