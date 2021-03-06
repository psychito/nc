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
class ScanreportModel extends BaseModel
{
	public function __construct()
	{
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	protected function loadLibrary()
	{
		oseFirewall::callLibClass('vsscanstat', 'vsscanstat');
	}
	public function loadLocalScript()
	{
		$this->loadAllAssets ();
// 		oseFirewall::loadCSSFile ('CentroraCodeMirrorCSS', 'codemirror.css', false);
		oseFirewall::loadJSFile ('CentroraManageIPs', 'scanreport.js', false);
	}
	public function getCHeader()
	{
		//return oLang::_get('SCANREPORT_TITLE');
		if (oseFirewall::checkSubscriptionStatus(false) == false) {
			return '<div class="subscribe-header">' . oLang::_get('SCANREPORT_TITLE') . '</div>';
		} else {
			return oLang::_get('SCANREPORT_TITLE');
		}
	}
	public function getCDescription()
	{
		//return oLang::_get('SCANREPORT_DESC');
		if (oseFirewall::checkSubscriptionStatus(false) == false) {
			return '<div class="subscribe-subheader">' . oLang::_get('SCANREPORT_DESC') . '</div>';
		} else {
			return oLang::_get('SCANREPORT_DESC');
		}
	}
	public function getBriefDescription()
	{
		//return oLang::_get('SCANREPORT_DESC_BRIEF');
		return '<div class="subscribe-desc">' . oLang::_get('SCANREPORT_DESC_BRIEF') . '</div>';
	}
	public function showSubPic()
	{
		return OSE_FWPUBLICURL . "/images/premium/scanreport.png";
	}

	public function showSubDesc()
	{
		return oLang::_get('SCANREPORT_DESC_SLOGAN');
	}

	public function getTypeList()
	{
		$return = array();
		$oseVsscanStat = new oseVsscanStat();
		$return['id'] = 1;
		if (oseFirewall::isDBReady())
		{
			$return['results'] = $oseVsscanStat->getTypeList();
			if (empty($return['results']))
			{
				$return['results']['id'] = 0;
				$return['results']['type'] = 'N/A';
			}
			$return['total'] = count($return['results']);
		}
		else
		{
			$return['results']['id'] = 0;
			$return['results']['type'] = 'N/A';
			$return['total'] = 0;
		}
		return $return;
	}
	public function getMalwareMap()
	{
		$return = array();
		$oseVsscanStat = new oseVsscanStat();
		if (oseFirewall::isDBReady())
		{
			$return = $oseVsscanStat->getMalwareMap();
		}
		else
		{
			$return = $this->getEmptyReturn ();
		}
		$return['draw']=$this->getInt('draw');
		return $return;
	}
	public function viewfile($id)
	{
		$return = array();
		$oseVsscanStat = new oseVsscanStat();
		$return['data'] = utf8_encode($oseVsscanStat->getFileContent($id));
		$return['filename'] = $oseVsscanStat->getFilePaths($id);
		return $return;
	}

    public function quarantinevs($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchqt($id));
        return $return;
    }
    public function bkcleanvs($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchbkcl($id));

        return $return;
    }

    public function deletevs($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchdl($id));
        return $return;
    }

    public function restorevs($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchrs($id));
        return $return;
    }

    public function batchqt($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchqt($id));
        return $return;
    }

    public function batchbkcl($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchbkcl($id));
        return $return;
    }

    public function batchrs($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchrs($id));
        return $return;
    }

    public function batchdl($id)
    {
        $return = array();
        $oseVsscanStat = new oseVsscanStat();
        $return['data'] = utf8_encode($oseVsscanStat->batchdl($id));
        return $return;
    }
	public function getStatistics()
	{
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getACLIPStatistic();
	}

	public function markAsClean ($id)
	{
		$return = array();
		$oseVsscanStat = new oseVsscanStat();
		$return['data'] = utf8_encode($oseVsscanStat->markAsClean($id));
		return $return;
	}
	
	public function exportcsv()
	{
		oseFirewall::loadFiles();
		$time = date("Y-m-d");
		$filename = "vs-export-" . $time . ".csv";
		$centnounce = isset($_SESSION['centnounce']) ? $_SESSION['centnounce'] : oseFirewall::loadNounce();
		$url = EXPORT_VSFILES_URL . urlencode($filename) . "&centnounce=" . urlencode($centnounce);
		$exportButton = '<a href="' . $url . '"  id="export-ip-button" target="_blank" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-export"></i> ' . oLang::_get("GENERATE_CSV_NOW") . '</a>';
		return $exportButton;
	}
	
	public function downloadCSV($filename)
	{
		$oseFirewallStat = new oseVsscanStat();
		$oseFirewallStat->downloadcsv($filename);
	}

	public function getVirusStats()
	{
		$oseFirewallStat = new oseVsscanStat();
		$return = $oseFirewallStat->getVirusStats();
		return $return;
	}

	public function coreFileCheck()
	{
		oseFirewall::callLibClass('vsscanner', 'cfscanner');
		$cfScanner = new cfScanner();
		$result = $cfScanner->coreFileCheck();
		return $result;
	}
}
