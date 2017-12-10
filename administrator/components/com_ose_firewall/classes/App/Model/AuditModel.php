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
require_once('BaseModel.php');
class AuditModel extends BaseModel
{
	public function __construct()
	{
		$this->loadLibrary();
		oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
		oseFirewall::callLibClass('audit', 'audit');
	}

	public function showStatus()
	{
		$dbReady = $this->isDBReady();
		if ($dbReady['ready'] == false) {
			echo '<li class="list-group-item"><span class="label label-warning">Warning</span> ' . oLang:: _get('DBNOTREADY') . ' <button id ="install-button" name ="install-button" class="btn btn-danger btn-xs fx-button" data-target="#formModal" data-toggle="modal" >' . oLang:: _get('INSTALLDB') . '</button></li>';
		} else {
			echo '<li class="list-group-item"><span class="label label-success">OK</span> ' . oLang:: _get('READYTOGO') . ' </li>';
		}
		$this->isDevelopModelEnable();
		$this->isAdminExistsReady();
		$this->isGAuthenticatorReady();
		$this->isWPUpToDate();
		$this->isGoogleScan();
		$this->isAdFirewallReady();
		$this->isSignatureUpToDate();
		if (OSE_CMS == 'joomla') {
			$this->checkSysPlugin();
		}
	}

	public function loadLocalScript()
	{
		$this->loadAllAssets();
		oseFirewall::loadJSFile('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
		oseFirewall::loadJSFile('CentroraManageIPs', 'installer.js', false);
	}

	public function getCHeader()
	{
		return oLang:: _get('DASHBOARD_TITLE');
	}

	public function getCDescription()
	{
		return oLang:: _get('OSE_WORDPRESS_FIREWALL_UPDATE_DESC');
	}

	public function showHeader()
	{
		//$html = '<div class="oseseparator"> &nbsp; </div>';
		//$html .= '<p></p>';
		//echo $html; 
	}

	public function actionCreateTables()
	{
		oseFirewall:: loadInstaller();
		oseFirewall:: loadRequest();
		$step = oRequest:: getInt('step');
		$retMessage = $this->getRetMessage($step);
		switch ($step) {
			case 0 :
				$result = $this->createTables();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 1 :
				$result = $this->insertConfigData();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 2 :
				$result = $this->insertAttackType();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 3 :
				$result = $this->insertBasicRules();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 4 :
				$result = $this->insertVspatterns();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			case 5 :
				$result = $this->insertFileExtension();
				$step++;
				$this->throwAjaxRecursive($result, 'Success', $retMessage, true, $step);
				break;
			default :
				if(OSE_CMS == 'wordpress')
				{
					$this->throwAjaxReturn(true, 'Completed,wp', $retMessage, false);
				}else
				{
					$this->throwAjaxReturn(true, 'Completed,jm', $retMessage, false);
				}
				break;
		}
	}

	private function getRetMessage($step)
	{
		switch ($step) {
			case 0 :
				$return = $this->transMessage(true, oLang:: _get('CREATE_BASETABLE_COMPLETED'));
				break;
			case 1 :
				$return = $this->transMessage(true, oLang:: _get('INSERT_CONFIGCONTENT_COMPLETED'));
				break;
			case 2 :
				$return = $this->transMessage(true, oLang:: _get('INSERT_ATTACKTYPE_COMPLETED'));
				break;
			case 3 :
				$return = $this->transMessage(true, oLang:: _get('INSERT_BASICRULESET_COMPLETED'));
				break;
			case 4 :
				$return = $this->transMessage(true, oLang:: _get('INSERT_VSPATTERNS_COMPLETED'));
				break;
			case 5 :
				$return = $this->transMessage(true, oLang:: _get('INSERT_FILEEXTENSION_COMPLETED'));
				break;
			default :
				$return = $this->transMessage(true, oLang:: _get('INSTALLATION_COMPLETED'));
				break;
		}
		return $return;
	}

	private function createTables()
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'createTable.sql';
		$result = $installer->createTables($dbFile);
		$installer->closeDBO();
		return $result;
	}

	private function insertConfigData()
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataSecConfig.sql';
		$dbAppEmailFile = OSE_FWDATA . ODS . 'dataAppEmail.sql';
		$installer->insertConfigData($dbFile, 'threshold');
		$installer->insertEmailData($dbAppEmailFile, 'firewall');
		$result = $installer->InsertOEMID();
		$installer->closeDBO();
		return $result;
	}

	private function insertAttackType()
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataAttacktype.sql';
		$result = $installer->insertAttackType($dbFile);
		$installer->closeDBO();
		return $result;
	}

	private function insertFileExtension()
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataFileExtension.sql';
		$result = $installer->insertFileExtension($dbFile);
		$installer->closeDBO();
		return $result;
	}

	private function insertBasicRules()
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataRulesBasic.sql';
		$result = $installer->insertBasicRules($dbFile);
		$installer->closeDBO();
		return $result;
	}

	private function insertVspatterns()
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'dataVspatterns.sql';
		$result = $installer->insertVspatterns($dbFile);
		$installer->closeDBO();
		return $result;
	}

	private function InsertOEMID()
	{
		$installer = new oseFirewallInstaller();
		$result = $installer->InsertOEMID();
		$installer->closeDBO();
		return $result;
	}

	private function installGeoIPDB($step)
	{
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'osegeoip{num}.sql';
		$result = $installer->installGeoIPDB($step, $dbFile);
		$installer->closeDBO();
		return $result;
	}

	private function cleanGeoIPDB($step)
	{
		$installer = new oseFirewallInstaller();
		$result = $installer->cleanGeoIPDB($step);
		$installer->closeDBO();
		return $result;
	}

	public function isDBReady()
	{
		$return = array();
		$return['ready'] = oseFirewall:: isDBReady();
		$return['type'] = 'base';
		return $return;
	}

	public function isDevelopModelEnable()
	{
		$audit = new oseFirewallAudit ();
		$audit->isDevelopModelEnable(true);
	}

	public function isAdFirewallReady()
	{
		$audit = new oseFirewallAudit ();
		$audit->isAdFirewallReady(true);
	}

	public function isAdminExistsReady()
	{
		$audit = new oseFirewallAudit ();
		$audit->isAdminExistsReady(true);
	}

	public function isGAuthenticatorReady()
	{
		$audit = new oseFirewallAudit ();
		$audit->isGAuthenticatorReady(true);
	}

	public function isWPUpToDate()
	{
		$audit = new oseFirewallAudit ();
		$audit->isWPUpToDate(true);
	}

	public function isGoogleScan()
	{
		$audit = new oseFirewallAudit ();
		$audit->isGoogleScan(true);
	}

	public function checkSysPlugin()
	{
		$audit = new oseFirewallAudit ();
		$audit->checkSysPlugin(true);
	}

	public function changeusername($username)
	{
		$oseFirewallStat = new oseFirewallStatPro();
		$updated = $oseFirewallStat->changeusername($username);
		return $updated;
	}

	public function isSignatureUpToDate()
	{
		$audit = new oseFirewallAudit ();
		$audit->isSignatureUpToDate(true);
	}

	public function showAuditList()
	{

	}

	public function showActionList()
	{

	}

	public function showSytemStatus()
	{
		$audit = new oseFirewallAudit ();
		$audit->enhanceSysSecurity();
		$audit->checkRegisterGlobals(true);
		$audit->checkSafeMode(true);
		$audit->checkURLFopen(true);
		$audit->checkDisplayErrors(true);
		$audit->checkDisableFunctions(true);
	}

	public function actionUninstallTables()
	{
		oseFirewall::callLibClass('', 'oseFirewallInstaller');
		$installer = new oseFirewallInstaller();
		$dbFile = OSE_FWDATA . ODS . 'UninstallTable.sql';
		$result = $installer->createTables($dbFile);// just used to split the sql file and query
		return $result;
	}

	public function getPHPConfig()
	{
		oseFirewall::callLibClass('sysguard', 'sysguard');
		$sysguard = new oseSysguard();
		$setting = $sysguard->customizePHPsetting();
		$this->returnJSON($setting);
	}

	public function showSafeBrowsingBar()
	{
		$audit = new oseFirewallAudit ();
		$audit->showSafeBrowsingBar(true);
	}

	public function saveTrackingCode($trackingCode)
	{
		$this->loadFirewallStat();
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->saveConfiguration('panel', array('trackingCode' => $trackingCode));
		return $result;
	}

	public function isBadgeEnabled()
	{
		return oseFirewall::isBadgeEnabled();
	}

	public function updateSignature()
	{
		$db = oseFirewall::getDBO();
		$query = "TRUNCATE TABLE `#__osefirewall_basicrules`;";
		$db->setQuery($query);
		$db->query();
		oseFirewall:: loadInstaller();
		return $this->insertBasicRules();
	}

	public function googleRot()
	{
		$this->loadFirewallStat();
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->saveConfiguration('seo', array('scanGoogleBots' => 0));
		return $result;
	}

	public function actCentroraPlugin()
	{
		$audit = new oseFirewallAudit ();
		$return = $audit->actCentroraPlugin();
		return $return;
	}

	//checks the current permissions for the folder and changes them if they are unsafe
	public function checkfolderPermission($foldername)
	{
		$audit = new oseFirewallAudit();
		$data = $audit->checkfolderPermission($foldername);
		return ($data);
	}

	//chnages the permission of the folder
	public function changePermission($foldername, $permission)
	{
		$audit = new oseFirewallAudit();
		if ($permission == "lock") {
			$data = $audit->changePermission($foldername, "lock");
		} elseif ($permission == "unlock") {
			$data = $audit->changePermission($foldername, "unlock");
		}
		return $data;
	}
	//checks if the htaccess file for that folder exist
	public function checkHtaccessFile($foldername)
	{
		$audit = new oseFirewallAudit ();
		$wp_dir = $audit->checkHtaccessFile($foldername);
		return $wp_dir;
	}

	//changes the content or creates a .htaccess file
	public function createHtaccessFile($foldername)
	{
		$audit = new oseFirewallAudit ();
		$data = $audit->createHtaccessFile($foldername);
		return $data;
	}
}
