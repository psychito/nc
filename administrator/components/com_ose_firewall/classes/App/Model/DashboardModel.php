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
require_once('BaseModel.php');

class DashboardModel extends BaseModel
{
    public function __construct()
    {
        $this->loadLibrary();
    }

    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageJQPlot', 'plugins/pie-chart/jquery.flot.custom.js', false);
        oseFirewall::loadJSFile('CentroraVectorMap', 'plugins/vectormaps/jquery-jvectormap-1.2.2.min.js', false);
        oseFirewall::loadJSFile('CentroraVectorWorldMap', 'plugins/vectormaps/maps/jquery-jvectormap-world-mill-en.js', false);
        oseFirewall::loadJSFile('CentroraDashboard', 'dashboard.js', false);
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

    public function getCountryStat()
    {
        $oseFirewallStat = new oseFirewallStat();
        return $oseFirewallStat->getCountryStat();
    }

    public function getTrafficData()
    {
        $oseFirewallStat = new oseFirewallStat();
        return $oseFirewallStat->getTrafficData();
    }

    public function getMalwareMap()
    {
        oseFirewall::callLibClass('vsscanstat', 'vsscanstat');
        $scanReport = new oseVsscanStat ();
        $response = $scanReport->getMalwareMap();
        return $response;
    }

    public function getBackupList()
    {
        if(oseFirewallBase::isSuite())
        {
            $result['cms'] = "st";
            return $result;
        }else{
            oseFirewall::callLibClass('gitBackup', 'GitSetup');
            $git = new GitSetup();
            $result = $git->getLastBackupTime();
            if(!empty($result))
            {
                $temp = "Last Backup : $result";
                $result = $temp;
            }
            if(empty($result))
            {
                $temp1['status'] = 0;
                $temp1['info'] = "No Backups Performed";
                $temp1['cms'] = OSE_CMS;
                return $temp1;
            }else{
                $temp1['status'] = 1;
                $temp1['info'] = $result;
                $temp1['cms'] = OSE_CMS;
                return $temp1;
            }
        }

    }

    public function checkWebBrowsingStatus()
    {
        oseFirewall::callLibClass('panel', 'panel');
        $panel = new panel ();
        $response = $panel->checkSafebrowsing();
        return $response;
    }

    public function updateDashboardStyle($style)
    {
        $this->loadDatabase();
//        $query = "SELECT `value` FROM `wp_ose_secConfig` WHERE `type` = 'style'";
//        $this->db->setQuery($query);
//        $result = $this->db->loadObject();
        $this->saveConfiguration('style', array('style' => $style));
        return true;
    }

    public function getScanHist()
    {
        oseFirewall::callLibClass('vsscanstat', 'vsscanstat');
        $scanReport = new oseVsscanStat ();
        $response = $scanReport->getScanHist();
        return $response;
    }

    public function activate($domain)
    {
        oseFirewall::callLibClass('sysguard', 'sysguard');
        $sysGuard = new oseSysguard();
        return $sysGuard->activate($domain);
    }

    public function getBackupAccountTable()
    {
        oseFirewall::callLibClass('gitBackup', 'GitSetupsuite');
        $gitbackupSuite = new GitSetupsuite(false,false,false,false,false);
        $result = $gitbackupSuite->getFormattedRecentBackupTable();
        return $result;
    }
}