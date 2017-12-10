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
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
class RemoteLogin
{
	public function verifyKey () {
		$db = oseFirewall::getDBO();
		$query = "SELECT `value` FROM `#__ose_secConfig`
				  WHERE `key` = 'webkey' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		$db->closeDBO ();
		$this->ajaxReturn(array("webkey"=>$result->value));
	}
	protected function loadFirewallStat () {
		if (OSE_CMS == 'joomla')
		{
			oseFirewall::callLibClass('firewallstat', 'firewallstatJoomla');
		}
		else
		{
			oseFirewall::callLibClass('firewallstat', 'firewallstatWordpress');
		}
	}
	public function updateProfile($profileID, $profileStatus) {
		$this->loadFirewallStat ();
		$oseFirewallStat = new oseFirewallStat();
		$db = oseFirewall::getDBO();
		$query = "SELECT `value` FROM `#__ose_secConfig`
				  WHERE `key` = 'profileID' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		$db->closeDBO ();
		if ($result->value == $profileID)
		{
			$oseFirewallStat->saveConfiguration('panel', array('profileID'=>$profileID, 'profileStatus'=>$profileStatus));
		}
		if ($profileStatus == 5) {
			$this->clearPatterns('avs');
		}
	}
	protected function clearPatterns ($type) {
		$db = oseFirewall::getDBO();
		if ($type=='ath')
		{
			$query = "TRUNCATE `#__osefirewall_advancerules` ";
		}
		else if ($type=='avs')
		{
			$query = "TRUNCATE `#__osefirewall_vspatterns` ";
		}
		$db->setQuery($query);
		$db->query();
	}
	private function ajaxReturn($array) {
		print_r(json_encode($array));
		exit;
	}
	public function login()
	{
		oseFirewall::loadLanguage();
		require_once(OSEFWDIR.'framework'.ODS.'oseframework'.ODS.'ajax'.ODS.'oseAjax.php');
		$this->checkEncryptFunction () ;
		$privateKey = null;
		$privateKey = $this->getPrivateKeyFromDB();
		$info = $this->decryptInfo($privateKey);
		if (!$info)
		{
			$return = $this->loginFailedInfo();
			oseAjax::returnJSON($return);
		}
		$state = $this->loginWithInfo($info);
		$remoteLogin = oRequest::getInt('remoteLogin', 0);
		if ($remoteLogin > 0)
		{
			if ($remoteLogin == 1)
			{
				$return = array();
				if ($state == false)
				{
					$return['success'] = false;
				}
				else
				{
					$return['success'] = true;
					$return['admin_url'] = OSE_WPURL.'/wp-admin/';
				}
				oseAjax::returnJSON($return);
			}
			if ($remoteLogin == 2)
			{
				if ($state == true)
				{
					$this->loadAction();
				}
				else
				{
					$return = $this->loginFailedInfo();
					oseAjax::returnJSON($return);
				}
			}
		}
	}
	private function checkEncryptFunction () {
		if (!function_exists('mcrypt_decrypt')) {
			$return = $this->mcryptNotExists();
			oseAjax::returnJSON($return);
		}
	}
	private function loginFailedInfo()
	{
		$return = array();
		$return['id'] = 1;
		$return['results']['id'] = 0;
		$return['results']['name'] = oLang::_get('LOGIN_FAILED');
		$return['results']['patterns'] = oLang::_get('LOGIN_FAILED');
		$return['results']['description'] = oLang::_get('LOGIN_FAILED');
		$return['results']['Server'] = oLang::_get('LOGIN_FAILED');
		$return['results']['rule'] = oLang::_get('LOGIN_FAILED');
		$return['results']['filename'] = oLang::_get('LOGIN_FAILED');
		$return['results']['keyname'] = oLang::_get('LOGIN_FAILED');
		$return['results']['info'] = oLang::_get('LOGIN_FAILED');
		$return['results']['login'] = oLang::_get('LOGIN_STATUS');
		$return['total'] = 0;
		return $return;
	}
	private function mcryptNotExists()
	{
		$return = array();
		$return['id'] = 1;
		$return['results']['id'] = 0;
		$return['results']['name'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['patterns'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['description'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['Server'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['rule'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['filename'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['keyname'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['info'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['results']['login'] = oLang::_get('MCRYPT_NOT_EXISTS');
		$return['total'] = 0;
		return $return;
	}
	private function decryptInfo($privateKey)
	{
		if ($privateKey != null)
		{
			$encryptedLogin = oRequest::getVar('encryptedLogin', null);
			oseFirewall::callLibClass('cipher', 'Cipher');
			$Cipher = new Cipher();
			$Cipher->setSecretKey($privateKey);
			$result = $Cipher->decrypt($encryptedLogin);
			if ($result == false)
			{
				return false;
			}
			$info = array($result);
			return $info;
		}
		else
		{
			return false;
		}
	}
	private function getPrivateKeyFromDB()
	{
		$db = oseFirewall::getDBO();
		$query = "SELECT `config`.`value`
				  FROM `#__ose_secConfig` AS `config`
				  WHERE `config`.`key` = 'privateAPIKey' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		$db->closeDBO ();
		$privateKey = $result->value;
		return $privateKey;
	}
	private function loginWithInfo($info)
	{
		require_once(ABSPATH."wp-includes/pluggable.php");
		require_once(ABSPATH."wp-includes/functions.php");
		// Perform the login function here;
		$user = get_user_by('login', $info[0]);
		if (empty($user) || $user->ID == null)
		{
			return false;
		}
		else
		{
			wp_set_auth_cookie($user->ID, true, false);
			return true;
		}
	}
	private static function callControllerClass($classname)
	{
		require_once(OSE_FWRECONTROLLERS.ODS.$classname.'.php');
	}
	private function getRemoteController()
	{
		// add encrypted login;
		$controller = oRequest::getVar('controller', null);
		if ($controller != null)
		{
			$controller = ucfirst($controller);
			$controller = $controller.'RemoteController';
		}
		return $controller;
	}
	private function getRemoteAction()
	{
		$action = oRequest::getVar('action', null);
		if ($action != null)
		{
			$action = ucfirst($action);
			$action = 'action'.$action;
		}
		return $action;
	}
	private function loadAction()
	{
		$controller = $this->getRemoteController();
		$action = $this->getRemoteAction();
		if ($action != null && $controller != null)
		{
			$this->callControllerClass($controller);
			$RemoteController = new $controller($action);
			$RemoteController->$action();
		}
		else
		{
			//header('Location: '.OSE_WPURL.'/wp-admin/');
			//echo "<script type='text/javascript'>window.location='".OSE_WPURL."/wp-admin/'</script>'";
		}
	}
	public function updateSignature () {
		$this->validateIP ();
		$action = "actionScheduledUpdatePatterns";
		$this->callControllerClass('DownloadRemoteController');
		oseFirewall::runApp();
		global $centrora;
		$RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
		$RemoteController->$action();
	}
    public function getStats() {
       $this->validateIP ();
        $this->callControllerClass('DownloadRemoteController');
        oseFirewall::runApp();
        global $centrora;
        $RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
        $RemoteController->actionGetStats();
    }

	public function bgscan()
	{
		$this->validateIP();
		$this->callControllerClass('DownloadRemoteController');
		oseFirewall::runApp();
		global $centrora;
		$RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
		$RemoteController->actionbgscan();
	}

	public function biggitbackup()
	{
		$this->validateIP();
		$this->callControllerClass('DownloadRemoteController');
		oseFirewall::runApp();
		global $centrora;
		$RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
		$RemoteController->actionbggitbackup();
	}
	public function vsScanning ($step, $type) {
		$this->validateIP ();
		$this->callControllerClass('DownloadRemoteController');
		oseFirewall::runApp();
		global $centrora;
		$RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
		$RemoteController->actionVsscan($step, $type);
	}
	public function gitBackup () {
		$this->validateIP ();
		$this->callControllerClass('DownloadRemoteController');
		oseFirewall::runApp();
		global $centrora;
		$RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
		$RemoteController->actionGitBackup();
	}
    public function runBackup ($cloudbackuptype, $upload , $fileNum,$preparelist = 0 ) {
//        $this->validateIP ();
        $this->callControllerClass('DownloadRemoteController');
        oseFirewall::runApp();
        global $centrora;
        $RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
        $RemoteController->actionScheduledBackup($cloudbackuptype, $upload , $fileNum,$preparelist );
    }

	public function clearBlacklistIP ($ClearIPKey)
	{
		oseFirewall::runApp();
		global $centrora;
		$RemoteController = new App\Controller\remoteControllers\DownloadRemoteController($centrora);
		$RemoteController->actionclearBlacklistIP($ClearIPKey);
	}

    public function validateIP () {
        $ip = $this->getRealIP();
        // Centrora server IP List;
        $iplist = array('49.255.209.82', '108.162.216.190', '158.69.56.254', '175.45.147.116', '149.56.117.155', '2607:5300:60:81ab::','10.42.153.241','139.99.131.27','10.42.117.76','10.42.160.218','10.42.180.129','167.114.1.205','192.99.100.56');
        $enabled_proxy = true;
        if  (isset($_SERVER['http_proxy']) && !empty($_SERVER['http_proxy']) && $enabled_proxy==true) {
            return;
        }
        if (in_array($ip, $iplist) == false)
        {
            die("Invalid Request from ".$ip);
        }
    }
	public function updateSafeBrowsing () {
		//$this->validateIP ();
		$status=oRequest :: getVar('status', null);
		if (empty($status)){
			return;
		}
		else
		{
			$status = base64_decode($status);
			oseFirewall::callLibClass('panel', 'panel');
			$panel = new panel();
			$response = $panel->updateSafebrowsingStatus($status);
			return $response;
		}
	}
	private function isCloudFlareIPs($ip) {
		// Cloudflare IP addresses;
		$array = array();
		$array[] = '103.21.244.0/22';
		$array[] = '103.22.200.0/22';
		$array[] = '103.31.4.0/22';
		$array[] = '104.16.0.0/12';
		$array[] = '108.162.192.0/18';
		$array[] = '141.101.64.0/18';
		$array[] = '162.158.0.0/15';
		$array[] = '172.64.0.0/13';
		$array[] = '173.245.48.0/20';
		$array[] = '188.114.96.0/20';
		$array[] = '190.93.240.0/20';
		$array[] = '197.234.240.0/22';
		$array[] = '198.41.128.0/17';
		$array[] = '199.27.128.0/21';

		foreach ($array as $cidr) {
            oseFirewall::callLibClass('ipmanager', 'ipmanager');
            $ipmanager = new oseFirewallIpManager();
			$result = $ipmanager->cidr_match($ip, $cidr);
			if ($result == true) {
				return true;
			}
		}
		return false;
	}
	private function getRealIP()
	{
		$ip = null;
		if (!empty($_SERVER['REMOTE_ADDR']))
		{
			if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				if ($this->isCloudFlareIPs($_SERVER['REMOTE_ADDR'])==true) {
					$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				}
			}
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
				if ($ip)
				{
					array_unshift($ips, $ip);
					$ip = null;
				}
				$this->tvar = phpversion();
				for ($i = 0, $total = count($ips); $i < $total; $i++)
				{
					if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i]))
					{
						if (version_compare($this->tvar, "5.0.0", ">="))
						{
							if (ip2long($ips[$i]) != false)
							{
								$ip = $ips[$i];
								break;
							}
						}
						else
						{
							if (ip2long($ips[$i]) != - 1)
							{
								$ip = $ips[$i];
								break;
							}
						}
					}
				}
			}
		}
		return $ip;
	}




    /*
   * Code to manage gitbackup cron job
   */
    /*
    * Code to manage gitbackup cron job
    */

    public function rungitBackupV6($action)
    {
        if (class_exists('SConfig')){
            $this->saveCronParams($action);
        }else{
            $this->rungitBackupV6_plugin($action);
        }
    }


    public function rungitBackupV6_plugin($action)
    {
//        $this->validateIP ();
        oseFirewall::callLibClass('gitBackup', 'GitSetup');
        $qatest = oRequest :: getInt('qatest', false);
        $gitbackup = new GitSetup($qatest);
        oseFirewall::callLibClass('panel', 'panel');
        $key = oRequest :: getVar('key', false);
        $panel = new panel();
        if($action == "preReqCheck")
        {
            $gitbackup->clearBackupLog();
            $gitbackup->clearErrorLogBackup();
            $checkResult = $gitbackup->preReqCheck();
            $panel->sendRecordResultRequest('preReqCheck',$checkResult['status'],$checkResult['info']);
            if($checkResult['status'] == 1)
            {
                $panel->gitBackupV6SendRequest('dbBackupGitv6');
            }
            $this->ajaxReturn($checkResult);
        }
        else if($action == "runDbBackup")
        {
            $dbBackup = $gitbackup->backupDbs();
            $panel->sendRecordResultRequest( "runDbBackup",$dbBackup['status'],$dbBackup['info']);
            if($dbBackup['status'] == 1)
            {
                //perform local backup
                $panel->gitBackupV6SendRequest('localBackupGitV6');
            }
            $this->ajaxReturn($dbBackup);
        }elseif($action == "localBackup")
        {
            oseFirewallBase::callLibClass('gitBackup','GitSetupL');
            $gitsetupl = new GitSetupL();
            $localBackup = $gitsetupl->localBackup();
            $panel->sendRecordResultRequest( "localBackup",$localBackup['status'],$localBackup['info']);
            if($localBackup['status']== 1 || $localBackup['status']==2)
            {
                //contlocalbackup
                $panel->gitBackupV6SendRequest('contLocalBackupGitv6');
            }
            $this->ajaxReturn($localBackup);

        }elseif($action == "contlocalBackup")
        {
            oseFirewallBase::callLibClass('gitBackup','GitSetupL');
            $gitsetupl = new GitSetupL();
            $contBackup = $gitsetupl->contLocalBackup();
            if($contBackup['status'] == 1)
            {
                if(isset($contBackup['folder']))
                {
                    $msg = "contlocalBackup-". $contBackup['folder'];
                }else{
                    $msg = "contlocalBackup";
                }
            }else{
                $msg = "contlocalBackup";
            }
            $panel->sendRecordResultRequest($msg,$contBackup['status'],$contBackup['info']);
            if($contBackup['status'] == 1)
            {
                //call cont local backup
                $panel->gitBackupV6SendRequest('contLocalBackupGitv6');
            }
            else if($contBackup['status'] == 2 || $contBackup['status'] == 4)
            {
                //check cron settings
                $push = oRequest :: getInt('push', false);
                if($push == 2)
                {
                    $panel->gitBackupV6SendRequest('finalGitPushv6');
                }else{
                    $panel->gitBackupV6SendRequest('gitBackupV6Completed');
                }
            }
            $this->ajaxReturn($contBackup);
        }elseif($action == "finalPush")
        {
            $remoteRepoSet = $gitbackup->isRemoteRepoSet();
            if($remoteRepoSet['status']==1)
            {
             //remote repoi set
                $push_result = $gitbackup->finalGitPush();
                $panel->sendRecordResultRequest( "finalPush",$push_result['status'],$push_result['info']);
                if($push_result['status'] == 1)
                {
                    $panel->gitBackupV6SendRequest('gitBackupV6Completed');
                }else {
                    $panel->gitBackupV6SendRequest('gitBackupV6Completed');
                }
            }
            else
            {
                //remote repo is not set
                $push_result['status'] = 0;
                $push_result['info'] = "Remote repo is not set";
                $panel->sendRecordResultRequest( "finalPush",$push_result['status'],$push_result['info']);
                $panel->gitBackupV6SendRequest('gitBackupV6Completed');
            }
            $this->ajaxReturn($push_result);
        }
        elseif($action  == "complGitBackupv6")
        {
            //send email on comletion
            $result = $gitbackup->complGitBackupv6();
            $this->ajaxReturn($result);
        }
    }

    public static function rungitbackupV6_suite($action,$accountname ,$accountpath,$key)
    {
        if(empty($action))
        {
           die();
        }
//        $this->validateIP();
        oseFirewall::callLibClass('gitBackup', 'GitSetupsuite');
        $qatest = false;
        if(!empty($accountname) && !empty($accountpath))
        {
            $gitbackupsuite = new GitSetupsuite($qatest,$accountname, $accountpath,true,true);
        }else if(!empty($accountname) && empty($accountpath))
        {
            $accountpath = oseFirewallBase::getAccountPath($accountname);
            $gitbackupsuite = new GitSetupsuite($qatest,$accountname, $accountpath,true,true);
        }else
        {
            $inital_setp = false;
            $gitbackupsuite = new GitSetupsuite($qatest,$accountname, $accountpath,$inital_setp,true);
        }
        oseFirewall::callLibClass('panel', 'panel');
        $panel = new panel();
        if($action == "backupAccountsQueue")
        {
            $gitbackupsuite->clearBackupLog_suite();
            $gitbackupsuite->clearErrorLogBackup_suite();
            $gitbackupsuite->deleteBackupQueueList();
            $backupQueue_result =   $gitbackupsuite->backupAccountsQueue(false,true);
            $panel->sendRecordResultRequest( "backupAccountsQueue",$backupQueue_result['status'],$backupQueue_result['info'],$key);
            if($backupQueue_result['status'] ==1)
            {
                $panel->gitBackupV6SendRequest('contBackupQueue',false,false,$key);
            }
        }
        elseif($action == "contBackupQueue")
        {
            $contBackupQueue_result = $gitbackupsuite->contBackupQueue();
            if(isset($contBackupQueue_result['name']))
            {
                $msg =  "contBackupQueue-".$contBackupQueue_result['name'];
            }else{
                $msg = "contBackupQueue";
            }
            $panel->sendRecordResultRequest($msg,$contBackupQueue_result['status'],$contBackupQueue_result['info'],$key);
            if($contBackupQueue_result['status'] == 1)
            {
                $panel->gitBackupV6SendRequest('backupDBs',$contBackupQueue_result['name'],$contBackupQueue_result['path'],$key);
            }elseif($contBackupQueue_result['status'] == 3)
            {
                $panel->gitBackupV6SendRequest("gitBackupV6Completed",false,false,$key);
            }elseif(($contBackupQueue_result['status'] ==0 && isset($contBackupQueue_result['impact']) && $contBackupQueue_result['impact'] == "low") || $contBackupQueue_result['status']==2)
            {
                $panel->gitBackupV6SendRequest("backupQueueCompleted",$contBackupQueue_result['name'],$contBackupQueue_result['path'],$key);
            }
        }elseif($action == "backupDBs")
        {
            $backupdb_result =   $gitbackupsuite->backupDbs_suite($accountname,$accountpath);
            $panel->sendRecordResultRequest( "backupDBs-$accountname",$backupdb_result['status'],$backupdb_result['info'],$key);
            if($backupdb_result['status'] ==1)
            {
                $panel->gitBackupV6SendRequest('localBackupqueue',$accountname,$accountpath,$key);
            }elseif($backupdb_result['status']== 0 && isset($contBackupQueue_result['impact']) && $contBackupQueue_result['impact'] == "low")
            {
                $panel->gitBackupV6SendRequest("localBackupqueue",$accountname,$accountpath,$key);
            }elseif($backupdb_result['status']== 0 && isset($contBackupQueue_result['impact']) && $contBackupQueue_result['impact'] == "medium")
            {
                $panel->gitBackupV6SendRequest("backupQueueCompleted",$accountname,$accountpath,$key);
            }
            //$this->ajaxReturn($backupdb_result);
        }elseif($action == "localBackupqueue")
        {
            $localBackup_result =   $gitbackupsuite->localBackup_suite("commit",$accountname,$accountpath);
            $panel->sendRecordResultRequest( "localBackupqueue-$accountname",$localBackup_result['status'],$localBackup_result['info'],$key);
            if($localBackup_result['status'] ==1 || $localBackup_result['status'] == 2)
            {
                $panel->gitBackupV6SendRequest('contlocalBackupqueue',$accountname,$accountpath,$key);
            }elseif($localBackup_result['status']== 0 && isset($localBackup_result['impact']) && $localBackup_result['impact'] == "medium")
            {
                $panel->gitBackupV6SendRequest("backupQueueCompleted",$accountname,$accountpath,$key);
            }
           //$this->ajaxReturn($localBackup_result);
        }elseif($action == "contlocalBackupqueue")
        {
            $contlocalBackup_result =   $gitbackupsuite->contLocalBackup_suite("commit",$accountname,$accountpath);
            if(isset($contlocalBackup_result['folder']))
            {
                $msg = "contlocalBackupqueue-$accountname-".$contlocalBackup_result['folder'];
            }else{
                $msg = "contlocalBackupqueue-$accountname";
            }
            $panel->sendRecordResultRequest( $msg,$contlocalBackup_result['status'],$contlocalBackup_result['info'],$key);
            if($contlocalBackup_result['status'] == 1)
            {
                $panel->gitBackupV6SendRequest('contlocalBackupqueue',$accountname,$accountpath,$key);
            }else if($contlocalBackup_result['status'] == 2 || $contlocalBackup_result['status'] == 4)
            {
                $panel->gitBackupV6SendRequest('finalPushGitV6',$accountname,$accountpath,$key);
            }elseif($contlocalBackup_result['status']== 0 && isset($contlocalBackup_result['impact']) && $contlocalBackup_result['impact'] == "medium")
            {
                $panel->gitBackupV6SendRequest("backupQueueCompleted",$accountname,$accountpath,$key);
            }
          //  $this->ajaxReturn($contlocalBackup_result);
        }elseif($action == "finalPushGitV6")
        {
            $isremoteRepo = $gitbackupsuite->isRemoteRepoSet_suite($accountpath);
            if($isremoteRepo['status'] == 1)
            {
                $push_result =  $gitbackupsuite->finalGitPush_suite($accountpath);
            }else{
                $push_result['status'] = 0;
                $push_result['info'] = "Remote Repos is not set for : $accountpath";
            }
            $panel->sendRecordResultRequest( "finalPushGitV6-$accountname",$push_result['status'],$push_result['info'],$key);
            if($push_result['status'] == 1)
            {
                //if the push was successfull
                $panel->gitBackupV6SendRequest('backupQueueCompleted',$accountname,$accountpath,$key);
            }else if($push_result['status'] == 0)
            {
                //if the push failed move to the next account
                $panel->gitBackupV6SendRequest('backupQueueCompleted',$accountname,$accountpath,$key);
            }
         //   $this->ajaxReturn($push_result);
        }elseif($action == "backupQueueCompleted")
        {
            $backupQueueCompleted_result =   $gitbackupsuite->isbackupQueueCompleted($accountname,$accountpath);
            $panel->sendRecordResultRequest( "backupQueueCompleted-$accountname",$backupQueueCompleted_result['status'],$backupQueueCompleted_result['info'],$key);
            if($backupQueueCompleted_result['status'] == 1)
            {
                //if the push was successfull
                $panel->gitBackupV6SendRequest('contBackupQueue',false,false,$key);
            }else if($backupQueueCompleted_result['status'] == 2)
            {
                //if the push failed move to the next account
                $panel->gitBackupV6SendRequest('gitBackupV6Completed',false,false,$key);
            }
           // $this->ajaxReturn($backupQueueCompleted_result);
        }elseif($action == "complGitBackupv6")
        {
            $result = $gitbackupsuite->complGitBackupv6();
            //$this->ajaxReturn($result);
        }else{
//            die("Incorrect action , action detected is : ".$action);
        }

    }

    public function runGitBackupV6Command($action)
    {
        $accountname = oRequest :: getVar('accountname', false);
        $accountpath = oRequest :: getVar('accountpath', false);
        oseFirewall::callLibClass('gitBackup', 'GitSetupsuite');
        $key = oRequest :: getVar('key', false);
        if(!empty($accountname) && !empty($accountpath))
        {
            $gitbackupsuite = new GitSetupsuite(false,$accountname, $accountpath,true,true);
        }else {
            $inital_setp = false;
            $gitbackupsuite = new GitSetupsuite(false,$accountname, $accountpath,$inital_setp,true);
        }
        $gitcmd = "php -r 'include \"/usr/local/lib/php/centrora/administrator/scan.php\"; include \"/usr/local/lib/php/centrora/administrator/components/com_ose_firewall/classes/Library/RemoteLogin/RemoteLogin.php\"; RemoteLogin::rungitbackupV6_suite(\"$action\",\"$accountname\",\"$accountpath\",\"$key\");'";
        if(!empty($gitcmd))
        {
           $result =  $gitbackupsuite->runShellCommandWithStandardOutput($gitcmd);
           $this->ajaxReturn($result);
        }else{
//            die("git command is empty ");
        }
    }



    public static function rungitbackupV6_suitecmd ()
    {
        $action = null;
        $accountname = null;
        $accountpath = null;
        $key = null;
        oseFirewall::callLibClass('gitBackup', 'GitSetupsuite');
        $gitbackupsuite = new GitSetupsuite(false,false, false,false,true);
        $cronParams = $gitbackupsuite->getCronSettingsLocal(7);
        if(!empty($cronParams) && property_exists($cronParams,"accountpath") && $cronParams->accountpath=="/")
        {
            $cronParams->accountpath = false;
        }
        if(!empty($cronParams) && property_exists($cronParams,'action'))
        {
           $gitbackupsuite->clearCronSettings(7);
           self::rungitbackupV6_suite($cronParams->action,$cronParams->accountname,$cronParams->accountpath,$cronParams->key);
        }else{
//            die("action is not set for gitbackup");
        }
    }

    public function saveCronParams($action)
    {
        //test code
        //end of test code
        $accountname = oRequest :: getVar('accountname', false);
        $accountpath = oRequest :: getVar('accountpath', false);
        $key = oRequest :: getVar('key', false);
        oseFirewall::callLibClass('gitBackup', 'GitSetupsuite');
        if(!empty($accountname) && !empty($accountpath))
        {
            $gitbackupsuite = new GitSetupsuite(false,$accountname, $accountpath,true,true);
        }else {
            $inital_setp = false;
            $gitbackupsuite = new GitSetupsuite(false,$accountname, $accountpath,$inital_setp,true);
        }
        $accountpath = $gitbackupsuite->formatAccountPath($accountpath);
        $gitbackupsuite->writeCronParams($action,$accountname,$accountpath,$key);
    }


    public function sendStatsEmail() {
        $this->validateIP ();
        oseFirewall::callLibClass('fwscannerv7','fwscannerv7');
        $fwscannerv7 = new oseFirewallScannerV7();
        $settings = $fwscannerv7->getFirewallSettingsfromDb();
        if($settings['status'] == 1 && $settings['info'][1] == 1)
        {
            oseFirewall::loadLibClass('fwscannerv7','emailNotificationMgmt');
            $fs= new emailNotificationMgmt();
            $result = $fs->sendEmail('stats',null,false);
            $this->ajaxReturn($result);
        }else{
            $return = oseFirewallBase::prepareErrorMessage("Firewall v7 is turned off");
            $this->ajaxReturn($return);
        }

    }

    public function manageWebLogs()
    {
        $this->validateIP ();
        oseFirewall::callLibClass('fwscannerv7','fwscannerv7');
        $fwscannerv7 = new oseFirewallScannerV7();
        $settings = $fwscannerv7->getFirewallSettingsfromDb();
        if($settings['status'] == 1 && $settings['info'][1] == 1)
        {
            oseFirewall::loadLibClass('fwscannerv7','fwstatsv7');
            $fs = new fwstatsv7();
            $fs->housekeepingV7();
            $return = oseFirewallBase::prepareSuccessMessage("Web Log has been cleared");
            $this->ajaxReturn($return);
        }else{
            $return = oseFirewallBase::prepareErrorMessage("Firewall v7 is turned off");
            $this->ajaxReturn($return);
        }
    }

}

?>