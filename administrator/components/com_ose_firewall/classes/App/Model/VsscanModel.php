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
class VsscanModel extends BaseModel {
	public function __construct() {	
		
	}
	public function loadLocalScript() {
		$this->loadAllAssets ();
//		$status = oseFirewall::checkSubscriptionStatus (false);
		//if ($status == true) {
            oseFirewall::loadJSFile('CentroraManageJQPlot', 'plugins/pie-chart/jquery.flot.custom.js', false);
			oseFirewall::loadJSFile ('CentroraManageJQPieChart', 'plugins/pie-chart/jquery.easy-pie-chart.js', false);
			oseFirewall::loadJSFile ('CentroraManageIPs', 'vsscan.js', false);
		//}
	}
	private function getAVScanScript () {
		return "var path = \"".addslashes(OSE_DEFAULT_SCANPATH)."\";";
	}
	public function getCHeader() {
		//return oLang :: _get('ANTIVIRUS_TITLE');
		if (oseFirewall::checkSubscriptionStatus(false) == false) {
			return '<div class="subscribe-header">' . oLang::_get('ANTIVIRUS_TITLE') . '</div>';
		} else {
			return oLang::_get('ANTIVIRUS_TITLE');
		}
	}
	public function getCDescription() {
		//return oLang :: _get('ANTIVIRUS_DESC');
		if (oseFirewall::checkSubscriptionStatus(false) == false) {
			return '<div class="subscribe-subheader">' . oLang::_get('ANTIVIRUS_DESC') . '</div>';
		} else {
			return oLang::_get('ANTIVIRUS_DESC');
		}
	}
	public function getBriefDescription()
	{
		//return oLang::_get('ANTIVIRUS_DESC_BRIEF');
		return '<div class="subscribe-desc">' . oLang::_get('ANTIVIRUS_DESC_BRIEF') . '</div>';
	}
	public function showSubPic()
	{
		return OSE_FWPUBLICURL . "/images/premium/dynamicscaner.png";
	}
	public function showSubDesc()
	{
		return oLang::_get('ANTIVIRUS_DESC_SLOGAN');
	}
	public function initDatabase($step, $path) {
		oseFirewall::callLibClass('vsscanner','vsscanner'); 
		$scanner = new virusScanner ();
		$scanner -> initDatabase($step, $path);
	}
	public function getTotalFiles()
	{
		oseFirewall::callLibClass('vsscanner','vsscanner');
		$scanner = new virusScanner ();	
		return oLang::_get('SCAN_READY');
	}
	private function assembleArray($result, $status, $msg, $continue, $id)
	{
		$return = array(
			'success' => (boolean) $result,
			'status' => $status,
			'result' => $msg,
			'cont' => (boolean) $continue,
			'id' => (int) $id
		);
		return $return;
	}

	public function vsScan($step, $type, $process, $size, $continueType, $user_type)
	{
		$result= array();
		$statusQuery= null;
		$resultQuery= null;
		$infectedNum= 0;
		if($user_type == true) {
			oseFirewall::callLibClass('vsscanner','vsscannerpremium');
			$scanner = new vsscannerpremium();  //call to the premium class
		}
		else
		{
			//free users
			oseFirewall::callLibClass('vsscanner','vsscanner');
			$scanner = new virusScanner (); // call to the free class
		}

		if ($step<0)
		{
			$results = $scanner->vsScan($step, $type);
		}
		else
		{
			if ($continueType == 'continue') {
				$flag = $scanner->getContinueProcess();
				if ($flag == false) {
					$results = $this->assembleArray(false, 'ERROR', 'Please perform start new scan', $continue = false, $id = null);
					oseAjax::returnJSON($results);
				} else {
					$process = $flag['process'];
					$size = $flag['size'];
				}
			}
			$results = $scanner->vsScanInd($process, $size);
		}
		if($results == false)
		{
			$results = $this->assembleArray (false, 'ERROR', FILE_VSSCAN_FAILED_INCORRECT_PERMISSIONS, $continue = false, $id = null);
			oseAjax::returnJSON($results);
		}
		return $results;  
	}
	public function getTotalInfected()
	{
		oseFirewall::callLibClass('vsscanner','vsscanner'); 
		$scanner = new virusScanner ();
		$totalNum = $scanner->getNumInfectedFiles();
		if ($totalNum)
		{
			return oLang::_get('OSE_THERE_ARE').' '.oLang::_get('OSE_INTOTAL').' '.$totalNum.' '.oLang::_get('OSE_FILES').' '.oLang::_get('OSE_INFECTED_FILES');
		} 
		else
		{
			return oLang::_get('YOUR_SYSTEM_IS_CLEAN');
		}
	}
	public function isDBReady(){
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return['ready'];
	}
	public function updatePatterns ($patternType) {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader('avs', null);
		$response = $downloader->updateVSPattern($patternType);
		return $response; 
	}
	public function getVersion () {
		$this->loadLibrary();
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->getAdvancePatternsVersion();
		if (!empty($result['version']))
		{
			$type = ($result['type'] =='bsav')?" (Basic Version) ":" (Advance Version) ";
			return $result['version'].$type;
		}
		else
		{
			return '1.0.0 - Oudated';
		}
	}
	public function checkScheduleScanning () {
		oseFirewall::callLibClass('downloader', 'oseDownloader');
		$downloader = new oseDownloader('ath', null);
		$response = $downloader->checkScheduleScanning();
		return $response; 
	}

    public function getLastScanResult()
    {
        if (OSE_CMS == 'wordpress') {
            echo '<a class="btn btn-success btn-sm mr5 mb10" href="admin.php?page=ose_fw_vsreport">Last Scan Result</a>';
        } else {
            echo '<a class="btn btn-success btn-sm mr5 mb10" href="index.php?option=com_ose_firewall&view=vsreport">Last Scan Result</a>';
        }
    }

    public function getLastScan()
    {
        oseFirewall::callLibClass('vsscanner','vsscanner');
        $scanner = new virusScanner ();
        return $scanner->getLastScan();
    }

	public function checkSession()
	{
		oseFirewall::callLibClass('vsscanner', 'vsscanner');
		$scanner = new virusScanner ();
		return $scanner->checkSession();
	}

	public function ifContinue()
	{
		oseFirewall::callLibClass('vsscanner', 'vsscanner');
		$scanner = new virusScanner ();
		return $scanner->ifContinue();
	}

	public function getVirusCount()
	{
		oseFirewall::callLibClass('vsscanner', 'vsscanner');
		$scanner = new virusScanner ();
		$result['viruscount'] = $scanner->getVirusCount();
		$result['usertype'] = oseFirewall::checkSubscriptionStatus();
		return $result;
	}

	public function checkVirusSignatureVersion()
	{
		oseFirewall::callLibClass('vsscanner', 'vsscanner');
		$scanner = new virusScanner ();
		$result['status']= $scanner->checkVirusSignatureVersion();
		return $result;
	}
	public function updateVirusSignatureVersionFile()
	{
		oseFirewall::callLibClass('vsscanner', 'vsscanner');
		$scanner = new virusScanner ();
		$result['status']= $scanner->updateVirusCheckFile();
		return $result;
	}

	public function storeUserEmail($email)
	{
		if(oseFirewall::checkSubscriptionStatus()) {
			oseFirewall::callLibClass('vsscanner','vsscannerpremium');
			$scanner = new vsscannerpremium();
			$result = $scanner->storeUserEmail($email);
			return $result;
		}
		else
		{
			$result['status'] = 0 ;
			$result['info'] = "Please subscribe to our services to use this feature ";
			return $result;
		}
	}

	public function getUserEmail()
	{
		oseFirewall::callLibClass('vsscanner','vsscannerpremium');
		$scanner = new vsscannerpremium();
		$result['email']= $scanner->getUserEmail();
		return $result;

	}
	public function getDatefromVirusCheckFile()
	{
		oseFirewall::callLibClass('vsscanner','vsscanner');
		$scanner = new virusScanner();
		$result = $scanner->getDatefromVirusCheckFile();
		return $result;
	}
}