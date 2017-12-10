<?php
/**
 * Created by PhpStorm.
 * User: Phil
 * Date: 15/10/7
 * Time: 3:23pm
 */
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
class cpcentrora
{
    private $db = null;
    private $scanhisttablebl = '#__osefirewall_scanhist';

    public function __construct()
    {
        $this->db = oseFirewall::getDBO();
        oseFirewall::loadFiles();
    }

    public function getAllStats ()
    {
        $return = array('firewall' => $this->getFirewallStats(),
            'scanners' => $this->getScannersStats(),
            'backup' => $this->getBackupStats(),
            'scheduledtasks' => $this->getScheduledTasks());
        return json_encode( $return );
    }

    /**
     * Get firewall Statistics Data
     * @return Array
     */
    private function getFirewallStats()
    {
        $result = $this->getDBFirewallData();
        return $result;
    }

    private function getDBFirewallData ()
    {
        $query = "SELECT
                      `at`.`id` AS `attacktypeid`,
                      `at`.`name` AS `name`,
                      `at`.`tag` AS `tag`,
                      SUM(IF(DATE(`inserted_on`) = DATE(NOW()), 1, 0)) AS `today_attack_count`,
                      SUM(IF((MONTH(`inserted_on`) = (MONTH(NOW())) AND YEAR(`inserted_on`) = YEAR(NOW())) OR
                             (MONTH(`inserted_on`) = 12 AND MONTH(NOW())=1 AND YEAR(`inserted_on`) = (YEAR(NOW()) - 1)),
                             1, 0)) AS `this_month_attack_count`,
                      SUM(IF((MONTH(`inserted_on`) = (MONTH(NOW()) - 1) AND YEAR(`inserted_on`) = YEAR(NOW())) OR
                              (MONTH(`inserted_on`) = 12 AND MONTH(NOW())=1 AND YEAR(`inserted_on`) = (YEAR(NOW()) - 1)),
                            1, 0)) AS `last_month_attack_count`,
                      COUNT(`dcd`.`detattacktype_id`) AS `total_attack_count`
                    FROM `#__osefirewall_attacktype` `at`
                      LEFT JOIN `#__osefirewall_detattacktype` `dat` ON `at`.`id` = `dat`.`attacktypeid`
                      LEFT JOIN `#__osefirewall_detcontdetail` `dcd` ON `dcd`.`detattacktype_id` = `dat`.`id`
                    GROUP BY `at`.`id`";
        $query_afterset = $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    /**
     * Get the latest Scanner Statistics Data from the scanhist table
     * @return Array
     */
    private function getScannersStats()
    {
        $result = $this->getDBScannerData();
        $json_arr = array( );
        foreach ($result as $key => $value){
            $content = json_decode($value->content, true);
            $json_arr[]=array(
                'super_type'=> $value->super_type,
                'date' => $value->inserted_on,
                'totalscan' => $content[0]['totalscan'],
                'totalvs' =>$content[0]['totalvs']
            );
        }
        return $json_arr;
    }
    /**
     * Get and return a list of all the latest scan results
     * @return Object List
     */
    private function getDBScannerData()
    {
        $query = 'SELECT * FROM ' . $this->db->quoteTable($this->scanhisttablebl)
            . ' WHERE `inserted_on` in ( SELECT  MAX(`inserted_on`) FROM '
            . $this->db->quoteTable($this->scanhisttablebl)
            . ' GROUP BY `super_type`)';
        $query_afterset = $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    /**
     * Get the latest Backup Statistics Data
     * @return Array
     */
    private function getBackupStats()
    {
        oseFirewall::callLibClass ( 'backup', 'oseBackup' );
        $backupManager = new oseBackupManager ();
        $result = $backupManager->getRecentBackup();

        if(sizeof($result)== 0)
        {
            $return['status'] = 'Empty';
            $return['result'] = '';
        }
        else
        {
            $return['status'] = 'Success';
            $return['result'] = $result[0];
        }
        return $return;
    }

    /**
     * Get the latest Scheduled Tasks Statistics Data
     * @return Array
     */
    private function getScheduledTasks()
    {
        oseFirewall::callLibClass('panel','panel');
        $panel = new panel ();
        $timezone = 'Australia/Melbourne';
        $result['scanner'] =  $panel->getNextSchedule($timezone, 1);
        $result['backup'] =  $panel->getNextSchedule($timezone, 2);

        $return = array();
        //no schedule
        if (empty($result['scanner']) && empty($result['backup']))
        {
            $return['status'] = 'empty';
            $return['scanner']['schedule_time'] = '0';
            $return['backup']['schedule_time'] = '0';
        }
        else
        {
            $return['status'] = 'success';
            $return['scanner']['schedule_time'] = (empty($result['scanner'])) ? '0' : $result['scanner'];
            $return['backup']['schedule_time'] = (empty($result['backup'])) ? '0' : $result['backup'];
        }
        return $return;
    }

}