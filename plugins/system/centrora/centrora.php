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
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
defined('_JEXEC') or die("Direct Access Not Allowed");
if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}
jimport('joomla.plugin.plugin');
class plgSystemCentrora extends JPlugin{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }
    protected function isAdmin ($mainframe) {
        $user = JFactory::getUser();
        if (JOOMLA15 == true) {
            return $mainframe->isAdmin();
        }
        else {
            if ($mainframe->isAdmin() && ($user->authorise('core.admin'))) {
                return true;
            }
            else {
                return false;
            }
        }
    }
    public function onAfterInitialise()
    {
        if (!defined('ODS')) {
            define('ODS', DIRECTORY_SEPARATOR);
        }
        if (!defined('OFRONTENDSCAN'))  {define('OFRONTENDSCAN', false);}
        if (!defined('OSEFWDIR')) {define('OSEFWDIR', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_ose_firewall' . DS);}

	if(!file_exists(OSEFWDIR  . 'assets' . ODS . 'config' . ODS . 'define.php'))
	{
	    return ;
	}
        require_once(OSEFWDIR  . 'assets' . ODS . 'config' . ODS . 'define.php');
        $mainframe = JFactory::getApplication('SITE');
        if ($this->isAdmin ($mainframe) == true) {
            return; // Dont run in admin
        } else {
            require_once(OSE_FWFRAMEWORK . ODS . 'oseFirewallJoomla.php');
            $ready = oseFirewall::preRequisitiesCheck();
            if ($ready == false) {
                if (oseFirewall::isBackendStatic()) {
                    oseFirewall::showNotReady();
                } else {
                    return;
                }
            }
            // No need to load Autoload here;
            if (JOOMLA15 == false ) {
                require_once(OSEFWDIR . 'vendor/autoload.php');
            }
            require_once(OSEFWDIR  . 'classes' . ODS . 'Library' . ODS . 'RemoteLogin' . ODS . 'RemoteLogin.php');
            // Load the OSE Framework ;
            $oseFirewall = new oseFirewall();
            oseFirewall::loadRequest();
            $oseFirewall->initSystem();
            $ready = oseFirewall::isDBReady();
            $firewallv6_settings = $oseFirewall::getConfiguration('scan');
            if ($firewallv6_settings['success'] == 1 && isset($firewallv6_settings['data']['devMode']) && $firewallv6_settings['data']['devMode']==0) {
                if(!empty($_POST['googleAuthCode']))
                {
                    oseFirewall::callLibClass('fwscanner', 'fwscannerbs');
                    $oseFirewallScanner = new oseFirewallScannerBasic();
                    $oseFirewallScanner->manageBannedAdminsFW6();
                }
                $this->checkIPstatus();
            }
            if ($ready == true) {
                $signatureUpdate = JRequest::getInt('signatureUpdate', 0);
                $verifyKey = JRequest::getInt('verifyKey', 0);
                $updateProfile = JRequest::getInt('updateProfile', 0);
                $vsScanning = JRequest::getInt('vsScanning', 0);
                $runBackup = JRequest :: getInt('runBackup', 0);
                $getAllStats = JRequest :: getInt('getAllStats', 0);
                $clearIPkey = JRequest::getVar('clearIPKey', null);
                $gitBackup = JRequest:: getInt('gitBackup', 0);
                $gitBackupV6 = JRequest::getInt('gitbackupv6', 0);
                $sendStatsEmail = JRequest::getInt('fw7stats', 0);
                $manageWebLogs = JRequest::getInt('manageweblog', 0);
                if ($verifyKey == true) {
                    $this->verifyKey();
                } else if ($signatureUpdate == true) {
                    $this->signatureUpdate();
                } else if ($updateProfile == true) {
                    $profileID = JRequest::getVar('profileID', null);
                    $profileStatus = JRequest::getVar('profileStatus', null);
                    $this->updateProfile($profileID, $profileStatus);
                } else if ($vsScanning == 1) {
                    $step = JRequest::getInt('step', 0);
                    $type = JRequest::getInt('type', 0);
                    $this->vsScanning($step, $type);
                }
                else if ($runBackup == 1) {
                    $cloudbackuptype = JRequest :: getInt('cloudbackuptype', 0);
                    $upload = JRequest:: getInt('upload', 0);
                    $fileNum = JRequest:: getInt('fileNum', 0);
                    $preparelist =  oRequest :: getInt('preparelist', 0);
                    $this->runBackup($cloudbackuptype, $upload , $fileNum,$preparelist);
                } else if ($gitBackup == 1) {
                    $this->scheduleGitBackup();
                }
                else if ($getAllStats == 1) {
                    $this->getStats();
                }
                else if (!empty($clearIPkey))
                {
                    $this->clearBlacklistIP($clearIPkey);
                }else if($gitBackupV6 == 1){
                    $remoteLogin = new RemoteLogin();
                    $action = oRequest :: getVar('action', null);
                    $remoteLogin->rungitBackupV6($action);
                }elseif($sendStatsEmail == 1)
                {
                    $remoteLogin = new RemoteLogin();
                    $remoteLogin->sendStatsEmail();
                }elseif($manageWebLogs == 1)
                {
                    $remoteLogin = new RemoteLogin();
                    $remoteLogin->manageWebLogs();
                }
                else if ($mainframe->isAdmin()) {
                    oseFirewall::callLibClass('firewallstat', 'firewallstatJoomla');
                    $oseFirewallStat = new oseFirewallStat();
                    $results = $oseFirewallStat->getConfiguration('scan');
                    if ($results['success'] == 1 && isset($results['data']['devMode']) && $results['data']['devMode'] ==0) {
                        if (!empty($results['data']['secureKey'])) {
                            $this->centroraSecureKeyAuthentication($results['data']['secureKey']);
                        }
                    }else
                        if(oseFirewallBase::isFirewallV7Active()){
                        $oseFirewall::callLibClass('fwscannerv7', 'fwscannerv7');
                        $fwscannerv7 = new oseFirewallScannerV7();
                        $secureKey = $fwscannerv7->getBackendSecureKey();
                            if (!empty($secureKey)) {
                            $this->centroraSecureKeyAuthentication($secureKey);
                        }
                    }
                } else {
                    ////FIREWALL SCANNER V7
                    $oseFirewall::callLibClass('fwscannerv7', 'fwscannerv7');
                    $fwscannerv7 = new oseFirewallScannerV7();
                    $settings = $fwscannerv7->getFirewallSettingsfromDb();
                    if ($settings['status'] == 1 && $settings['info'][1] == 1) {
                        if(!oseFirewallBase::isSuite())
                        {
                            if (isset($_POST['googleAuthCode'])) {
                                $fwscannerv7->manageBanAdmins();
                            }
                        }
                        //scan the traffic using the fwscanner v7
                        $fwscannerv7->scanTraffic($settings['info'], 'GET');
                        $fwscannerv7->scanTraffic($settings['info'], 'POST');

                    } else {
                        $firewallv6_settings = $oseFirewall::getConfiguration('scan');
                        if ($firewallv6_settings['success'] == 1 && isset($firewallv6_settings['data']['devMode'])) {
                            if( $firewallv6_settings['data']['devMode'] == 0)
                            {
                                $oseFirewall->enhanceSysSecurity();
                                $isAdvanceFirewallScanner = $oseFirewall->isAdvanceFirewallSettingEnable();
                                if ($isAdvanceFirewallScanner == true) {
                                    $this->callOSEFirewallAdv();
                                } else {
                                    $this->callOSEFirewallBasic();
                                }
                            }
                        }
                    }
                }
            } else {
                return;
            }
        }
    }

    private function centroraSecureKeyAuthentication($userKey)
    {
        $session = JFactory:: getSession();
        $secureKey = $session->get('centroraAuthentication');
        if (empty($secureKey)) {
            if ((preg_match("/administrator\/*index.?\.php$/", strtolower($_SERVER['SCRIPT_NAME'])))) {
                if ($userKey != $_SERVER['QUERY_STRING']) {
                    $this->redirect();
                } else {
                    $session->set('centroraAuthentication', 1);
                }
            }
        }
    }

    private function scheduleGitBackup()
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->gitBackup();
    }
    private function vsScanning($step, $type)
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->vsScanning($step, $type);
    }
    private function runBackup ($cloudbackuptype, $upload , $fileNum ,$preparelist) {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->runBackup($cloudbackuptype, $upload , $fileNum,$preparelist);
    }

    private function clearBlackListIP ($clearIPkey)
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->clearBlacklistIP($clearIPkey);
    }

    private function getStats ()
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->getStats();
    }

    private function verifyKey()
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->verifyKey();
    }

    private function updateProfile($profileID, $profileStatus)
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->updateProfile($profileID, $profileStatus);
    }

    private function signatureUpdate()
    {
        $remoteLogin = new RemoteLogin();
        $remoteLogin->updateSignature();
    }

    private function callOSEFirewallAdv()
    {
        oseFirewall::callLibClass('fwscanner', 'fwscannerad');
        $oseFirewallScanner = new oseFirewallScannerAdvance();
        $oseFirewallScanner->hackScan();
    }

    private function callOSEFirewallBasic()
    {
        oseFirewall::callLibClass('fwscanner', 'fwscannerbs');
        $oseFirewallScanner = new oseFirewallScannerBasic();
        $oseFirewallScanner->hackScan();
    }

    public function onAfterRender()
    {
        $mainframe = JFactory::getApplication('SITE');
        if ($mainframe->isAdmin()) {
            $option = JRequest::getVar('option', null);
            if ($option == 'com_ose_firewall') {
                return;
                $document = JFactory::getDocument();
                unset($document->_styleSheets['templates/isis/css/template.css']);
            }
        }
    }
    private function redirect()
    {
        $mainframe= JFactory :: getApplication('SITE');

        $redURL= JURI :: root().'index.php';

        $redirect= str_replace("&amp;", "&", JRoute :: _($redURL));

        $mainframe->redirect($redirect);
    }

    private function checkIPstatus()
    {
        /*
            * Check IP status to protect against brute force attack
            */
        $bfconfig = oseFirewall::getConfiguration('bf');
        $status = !empty($bfconfig['data']['bf_status']) ? $bfconfig['data']['bf_status'] : false;
        if (!empty($status)) {
            oseFirewall::callLibClass('fwscanner', 'fwscanner');
            $fwscanner = new oseFirewallScanner();
            if ($fwscanner->ipStatus == 4) {
                $fwscanner->showBanPage();
            }
        }
    }

    public function onUserLoginFailure($response)
    {
        // run only in the backend
        // & only if failed login
        // & just with the Joomla! authentication
        // & if the IP is NOT in the whitelist
        $firewallv6_settings = oseFirewall::getConfiguration('scan');
        if ($firewallv6_settings['success'] == 1 && isset($firewallv6_settings['data']['devMode'])) {
            if ($firewallv6_settings['data']['devMode'] == 0) {
                oseFirewall::callLibClass('fwscanner', 'fwscanner');
                $fwscanner = new oseFirewallScanner();
                $flag = $fwscanner->ipStatus;
                //get details related to the brute force setting done by the user
                $bfconfig = oseFirewall::getConfiguration('bf');
                $status = !empty($bfconfig['data']['bf_status']) ? $bfconfig['data']['bf_status'] : false;
                $maxfail = (!empty($bfconfig['data']['loginSec_maxFailures'])) ? $bfconfig['data']['loginSec_maxFailures'] : 20;
                // $timeFrame = $bfconfig['data']['loginSec_countFailMins'];
                if ($response['status'] != JAuthentication::STATUS_SUCCESS && $response['type'] == 'Joomla' && $flag != 3) {
                    //JFactory::getApplication()->isAdmin() &&
                    // run only if the config has been loaded
                    // & the Active Scanner is enabled
                    if (!empty($status)) {
                        // count attempts for captcha
                        $session = JFactory::getSession();
                        $attempts = (int)$session->get('centrora_login_attempts', 0);

                        if ($attempts < $maxfail) {
                            $session->set('centrora_login_attempts', $attempts + 1);
                        } else {
                            $fwscanner->addACLRule(4, 99);
                            $content = $attempts . ' login attempts from IP address ' . $fwscanner->ip . ' this ip address is blocked due to exceeding the maximum login failure limit';
                            $fwscanner->addDetAttempts(15, $content);
                        }
                    }
                }
            }
            if(!oseFirewallBase::isSuite())
            {
                oseFirewall::callLibClass('fwscannerv7', 'fwscannerv7');
                $fwscannerv7 = new oseFirewallScannerV7();
                $settings = $fwscannerv7->getFirewallSettingsfromDb();
                //perform the check for brute force
                if ($settings['status'] == 1 && $settings['info'][1] == 1) {
                    if ($settings['info'][15] == 1 && $settings['info'][1] == 1) {
                        $fwscannerv7->bruteForceProtection($response['username']);
                    }
                }
            }
        }
    }
}

?>
