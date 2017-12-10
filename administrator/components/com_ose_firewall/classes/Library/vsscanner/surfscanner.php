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
oseFirewall::callLibClass('vsscanner', 'vsscanner');

class surfScanner
{
    private $db = null;
    private $vshashtable = '#__osefirewall_vshash';
    private $scanhisttablebl = '#__osefirewall_scanhist';
    private $sufscanProgress;
    private $md5Hashes = array();

    public function __construct()
    {
        $this->db = oseFirewall::getDBO();
        oseFirewall::loadFiles();
        $this->optimizePHP();
    }
    private function optimizePHP () {
    	if (function_exists('ini_set'))
    	{
    		ini_set('max_execution_time', 60);
			ini_set('memory_limit', '256M');
			ini_set("pcre.recursion_limit", "524");
			set_time_limit(60);
    	}
    }
    public function surfscan($step, $dir)
    {
        switch ($step) {
            case 1:
                $result = $this->generateSufScanList($dir);
                break;
            case 2:
                $result = $this->runSurfScan();
                break;
            case 3:
                $result = $this->completeSurfScan();
                break;
        }
        return $result;
    }

    public function getLastScan ()
    {
        $result  = $this->getLastMD5ScanHist();
        $scanList = oseJSON::decode($result->content, true);
        $lastScan = array('scanDate' => $result->inserted_on
            ,'serverNow' => oseFirewall::getTime()
            ,'content' => $scanList[0]['vsfilelist']
        );
        return $lastScan;
    }

    public function checkMD5DBUpToDate()
    {
        $data = array();
        $url = API_SERVER.'Maldetect/checkLastMD5Update';
        $serverresult = oseJSON::decode( $this->getJsonData($url), true );
        $query = 'SELECT MAX(inserted_on) as inserted_on  FROM '. $this->db->quoteTable($this->vshashtable);
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        $localresult = $result->inserted_on;
        if (empty($localresult)) {
            return oseFirewallBase::prepareErrorMessage("Your MD5 Hash Database is not up to date");
        } else {
            if($serverresult == $localresult)
            {
                return oseFirewallBase::prepareSuccessMessage("MD5 Hash is up to date. Last Updated On : ".date('Y-m-d H:i:s'));
            }else{
                return oseFirewallBase::prepareErrorMessage("MD5 Hash is available to update. Last Updated On : ".$localresult);
            }
        }
    }

    public function updateMD5DB()
    {
        $url = API_SERVER.'Maldetect/getUpdateMD5Data';
        $jsonData = $this->getJsonData($url);
        if(empty($jsonData))
        {
            //server returned null
            return oseFirewallBase::prepareErrorMessage("Server returned empty hash table \n".CONTACT_SUPPORT);
        }
        $resultlist = oseJSON::decode($jsonData, true);
        $this->deleteMD5DBData();
        $insertResult = $this->insertMD5DBData($resultlist);
        if($insertResult)
        {
            $displayMessage = "MD5 Hash is up to date. Last Updated On : ".date('Y-m-d H:i:s');
            return oseFirewallBase::prepareCustomMessage(1,SURF_SCAN_SIG_UPDATED,$displayMessage);
        }else {
            return oseFirewallBase::prepareErrorMessage("There was some problem in updating the MD5 Hash database" . "\n".CONTACT_SUPPORT);
        }

    }


    private function generateSufScanList($dir)
    {
        $scanList['scanlist'] = $this->setScanList($dir);
        $scanList['totalscan'] = count($scanList['scanlist']);
        $scanList['totalvs'] = 0;
        $scanList['vsfilelist'] = array();

        //save scan list
        $this->saveScanList($scanList);

        //set scan progress
        $this->setScanProgress(0, oLang::_get('VL_GET_LIST'), 0, 0, true, 2, array());
        $this->sufscanProgress['scanDate'] = oseFirewall::getTime();
        $this->sufscanProgress['serverNow'] = $this->sufscanProgress['scanDate'];

        //$this->clearpreviousScanDB ();
        return $this->sufscanProgress;
    }

    private function runSurfScan()
    {
        $this->setVirusHashes();
        $scanList = $this->readScanList();
        $i = 0;
        $vsFileList = $scanList['vsfilelist'];
        $path = 'no more files';
        $starttime = time();

        foreach ( $scanList['scanlist'] as $key => $path) {
            //check then set virus file list
            if (filesize($path) > 2048000) {
                unset ($scanList['scanlist'][$key]);
                continue;
            }
            $viruscheck = in_array(md5_file($path), $this->md5Hashes);
            if ($viruscheck) {
                $vsFileList [] = $path;
            }
            //unset clean files from scan list
            unset ( $scanList['scanlist'][$key] );
            //break from loop to send progress every 3sec. Ajax handles recall of this function if scanning is not complete
            if ( time() - $starttime >= 2) {
                //$vsFileList [] = $path; //testing
                break;
            }
        }
        $scanList['vsfilelist'] = $vsFileList;
        $scanList['totalvs'] = count($scanList['vsfilelist']);
        $numScanned = $scanList['totalscan'] - count($scanList['scanlist']);
        $this->saveScanList($scanList);

        $this->setScanProgress(round($numScanned / $scanList['totalscan'], 3) * 100,
            $path, $numScanned, $scanList['totalvs'], true, 2, $scanList['vsfilelist']);

        return $this->sufscanProgress;
    }

    private function completeSurfScan()
    {
        $scanList = $this->readScanList();
        $this->setScanProgress(100, oLang::_get('VL_COMPLETE'), $scanList['totalscan'],
            $scanList['totalvs'], false, 3, $scanList['vsfilelist']);
        $this->saveDBLastScanResult($scanList);
        $this->deleteScanList();
        return $this->sufscanProgress;
    }

    private function setScanProgress($progress, $desc, $totalscan, $totalvs, $cont, $step, $content)
    {
        $this->sufscanProgress = array(
            'status' => array("progress" => $progress,
                "current_scan" => $desc,
                "total_scan" => $totalscan,
                "total_vs" => $totalvs,
                "cont" => $cont,
                "step" => $progress >= 100 ? 3 : $step), //overide step to 3 if complete
            'content' => $content);
    }

    private function setVirusHashes()
    {
        $dbresult = $this->getMD5VShash();
        //check if hashed table is empty
        if (empty($dbresult)){
            $this->updateMD5DB();
            $dbresult = $this->getMD5VShash();
        }
        foreach ($dbresult as $key => $item) {
            $this->md5Hashes[] = $item ['hash'];
        }
    }

    private function getLastMD5ScanHist()
    {
        $query = "SELECT * FROM " . $this->db->quoteTable($this->scanhisttablebl)
            . " WHERE inserted_on = (SELECT max(inserted_on) FROM " . $this->db->quoteTable($this->scanhisttablebl)
            . " WHERE super_type = 'md5scan' AND sub_type = 1)";
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        return $result;
    }
    private function getMD5VShash()
    {
        $query = "SELECT * FROM " . $this->db->quoteTable($this->vshashtable) . " WHERE type = 0";
        $this->db->setQuery($query);
        $result = $this->db->loadResultList();
        return $result;
    }

    private function saveDBLastScanResult ($content = '')
    {
        $varValues = array('super_type' => 'md5scan',
            'sub_type' => 1,
            'content' => oseJSON::encode(array($content)),
            'inserted_on' => oseFirewall::getTime()
        );
        $this->db->addData('insert', $this->scanhisttablebl, '', '', $varValues);
    }

    private function setScanList($dir)
    {
        $files = array();
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        foreach ($iter as $path => $single) {
            if (!$single->isDir()) {
                $files[] = $path;
            }
        }
        return $files;
    }

    private function saveScanList($scanList)
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "surfScanList.json";
        $fileContent = oseJSON::encode($scanList);
        $result = oseFile::write($filePath, $fileContent);
    }

    private function readScanList()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "surfScanList.json";
        $fileContent = oseFile::read($filePath);
        $result = oseJSON::decode($fileContent, true);
        return $result;
    }

    private function deleteScanList()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "surfScanList.json";
        $result = oseFile::delete($filePath);
        return $result;
    }

    private function deleteMD5DBData ()
    {
        $conditions = array('type' => 0);
        $this->db->deleteRecord($conditions, $this->vshashtable);
    }
    private function insertMD5DBData ($resultlist)
    {
        $values = '';

        foreach ( $resultlist as $key => $value ) {

            $values .= "(0,'".$value['name']. "','". $value['MD5'] ."','". $value['inserted_on'] ."'),";
        }

        $query =  'INSERT INTO '. $this->db->quoteTable($this->vshashtable) .
            '(`type`, `name`,`hash`,`inserted_on`) VALUES ' . rtrim($values, ',') .';';
        $this->db->setQuery($query);
        return $this->db->query();
    }

    private function getJsonData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $json_data = curl_exec($ch);
        curl_close($ch);
        return $json_data;
    }
}