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

class fpScanner extends virusScanner
{
    private $scanhisttablebl = '#__osefirewall_scanhist';
    private $db = null;
    var $file = array();
    var $folder = array();
    private $fpscanProgress;

    public function __construct()
    {
        $this->db = oseFirewall::getDBO();
        oseFirewall::loadFiles();
    }

    public function fpscan($path, $baseFilePerm, $baseFolderPerm, $step)
    {
        if (empty($path)) {
            $path = OSE_ABSPATH;
        }
        switch ($step) {
            case 1:
                $result = $this->generateFPScanList($path);
                break;
            case 2:
                $result = $this->runFpScan($baseFilePerm, $baseFolderPerm);
                break;
            case 3:
                $result = $this->completeFpScan();
                break;
        }
        return $result;
    }

    private function generateFPScanList($path)
    {

        $scanList['scanlist'] = $this->setScanList($path);
        $scanList['totalscan'] = count($scanList['scanlist']);
        $scanList['totalvs'] = 0;
        $scanList['vsfilelist'] = array();

        //save scan list
        $this->saveScanList($scanList);
        //set scan progress
        $this->setScanProgress(0, oLang::_get('VL_GET_LIST'), 0, 0, true, 2, array());
        $this->fpscanProgress['scanDate'] = oseFirewall::getTime();
        $this->fpscanProgress['serverNow'] = $this->fpscanProgress['scanDate'];
        //$this->clearpreviousScanDB ();
        return $this->fpscanProgress;
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
            if(strpos($path,'.git')==false) {
                if ($single != '.' && $single != '..' && $single != '.svn' && $single != '.idea' && $single != '.DS_Store') {
                    $files[] = $path;
                }
            }
        }
        return $files;
    }

    private function saveScanList($scanList)
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "fpScanList.json";
        $fileContent = oseJSON::encode($scanList);
        $result = oseFile::write($filePath, $fileContent);
    }

    private function readScanList()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "fpScanList.json";
        $fileContent = oseFile::read($filePath);
        $result = oseJSON::decode($fileContent, true);
        return $result;
    }

    private function deleteScanList()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "fpScanList.json";
        $result = oseFile::delete($filePath);
        return $result;
    }

    private function setScanProgress($progress, $desc, $totalscan, $totalvs, $cont, $step, $content)
    {
        $this->fpscanProgress = array(
            'status' => array("progress" => $progress,
                "current_scan" => $desc,
                "total_scan" => $totalscan,
                "total_vs" => $totalvs,
                "cont" => $cont,
                "step" => $progress >= 100 ? 3 : $step), //overide step to 3 if complete
            'content' => $content,
        );
    }

    private function runFpScan($baseFilePerm, $baseFolderPerm)
    {
        $scanList = $this->readScanList();
        $i = 0;
        $vsFileList = $scanList['vsfilelist'];
        $result1 = array();
        $return = array();
        $path = 'no more files';
        $starttime = time();
        ksort($scanList['scanlist']);
        $i = 0;
        $filesscanned = 0 ;
        foreach ($scanList['scanlist'] as $key => $path) {
            if (is_dir($path)) {
                if (intval(substr(sprintf('%o', fileperms($path)), -4)) != $baseFolderPerm) {
//                    $vsFileList [] = "<br/><div class='col-md-8'><i class='im im-folder-open text-primary'> </i>$path</div>"
//                        ."<div class='col-md-1'>".substr(sprintf('%o', fileperms($path)), -4)."</div>";

                    $result1[$i]['type'] = "<i class='im im-folder-open text-primary'> </i>";
                    $result1[$i]['path'] = $path;
                    $result1[$i]['permission'] = substr(sprintf('%o', fileperms($path)), -4);
                    $i++;

                }
            } elseif (is_file($path)) {
                if (intval(substr(sprintf('%o', fileperms($path)), -4)) != $baseFilePerm) {
//                    $vsFileList [] = "<div class='col-md-8'><i class='im im-file9 text-warning'> </i>$path</div>"
//                        ."<div class='col-md-1'>".substr(sprintf('%o', fileperms($path)), -4)."</div>";

                    $result1[$i]['type'] = "<i class='im im-file9 text-warning'> </i>";
                    $result1[$i]['path'] = $path;
                    $result1[$i]['permission'] = substr(sprintf('%o', fileperms($path)), -4);
                    $i++;
                }
            }
            $filesscanned++;
            //unset clean files from scan list
            unset ($scanList['scanlist'][$key]);
            //break from loop to send progress every 3sec. Ajax handles recall of this function if scanning is not complete
            if (time() - $starttime >= 2) {
                //$vsFileList [] = $path; //testing
                break;
            }
        }
        $return['data'] = $result1;
        $return['recordsTotal'] = count($result1);
        $return['recordsFiltered'] = count($result1);

        if(!empty($scanList['totalvs']))
        {
            $scanList['totalvs'] +=  $return['recordsTotal'] ;
        }else{
            $scanList['totalvs'] =  $return['recordsTotal'] ;
        }

        if(!empty($scanList['vsfilelist']))
        {
            $scanList['vsfilelist'] = array_merge($scanList['vsfilelist'],$result1);
        }else{
            $scanList['vsfilelist'] = $result1;
        } //keep track of detected files
        $numScanned = $scanList['totalscan'] - $filesscanned;
        $this->saveScanList($scanList);
        if(!empty($scanList['scanlist']))
        {
            //if scanning is not completed for all the files, call step 2 again
            $step = 2 ;
        }else{
            //if files is empty go to step 3
            $step = 3;
        }
        $this->setScanProgress(round($numScanned / $scanList['totalscan'], 3) * 100, $path, $numScanned, $scanList['totalvs'], true, $step,$return['vsfilelist']);
        return $this->fpscanProgress;
    }

    private function completeFpScan()
    {
        $return = array();
        $scanList = $this->readScanList();
        $return['data'] = $scanList['vsfilelist'];
        $return['recordsTotal'] = count($scanList['vsfilelist']);
        $return['recordsFiltered'] = count($scanList['vsfilelist']);
        $this->setScanProgress(100, oLang::_get('VL_COMPLETE'), $scanList['totalscan'],
            $scanList['totalvs'], false, 3, $return);
        $this->saveLastScanResult($scanList);
        $this->deleteScanList();
        return $this->fpscanProgress;
    }

    protected function saveDBLastScanResult($content = '')
    {
        $varValues = array('super_type' => 'fpscan',
            'sub_type' => 1,
            'content' => oseJSON::encode(array($content)),
            'inserted_on' => oseFirewall::getTime()
        );
        $this->db->addData('insert', $this->scanhisttablebl, '', '', $varValues);
    }

    protected  function saveLastScanResult($content = array())
    {
        if(!empty($content))
        {
            $content['inserted_on'] = oseFirewall::getTime();
        }
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "fpScanLastScan.json";
        $fileContent = oseJSON::encode($content);
        $result = oseFile::write($filePath, $fileContent);
        return $result;
    }

    public function getLastScan()
    {
        $result = $this->getLastFpScanHist();
        $scanList = oseJSON::decode($result->content, true);
        $lastScan = array('scanDate' => $result->inserted_on
        , 'serverNow' => oseFirewall::getTime()
        , 'content' => $scanList[0]['vsfilelist']
        );
        return $lastScan;
    }

    public function getLastScanFormattedResult()
    {
        $result = $this->getLastScanResult();
        if(!empty($result)) {
            if(isset($result['inserted_on']) && isset($result['vsfilelist']))
            {
                $return['data'] = $result['vsfilelist'];
                $return['recordsTotal'] = count($return['data']);
                $return['recordsFiltered'] = count($return['data']);
                $lastScan = array(
                    'scanDate' => $result['inserted_on'],
                    'serverNow' => oseFirewall::getTime(),
                    'content' => $return,
                    'status' => array(
                        "cont" => false,
                        'type' => 'getLastScan'
                    ),
                );
                return $lastScan;
            }else{
                return $this->getEmptyTable();
            }
        }else{
            return $this->getEmptyTable();
        }
    }

    private function getEmptyTable()
    {
        $return  = array();
        $return['data']['type'] = 'N/A';
        $return['data']['path'] = 'N/A';
        $return['data']['permission'] = 'N/A';
        $return['recordsTotal'] = 0;
        $return['recordsFiltered']=0;
        return $return;
    }



    private function getLastFpScanHist()
    {
        $query = "SELECT * FROM " . $this->db->quoteTable($this->scanhisttablebl)
            . " WHERE inserted_on = (SELECT max(inserted_on) FROM " . $this->db->quoteTable($this->scanhisttablebl)
            . " WHERE super_type = 'fpscan' AND sub_type = 1)";
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        return $result;
    }
    private function getLastScanResult()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "fpScanLastScan.json";
        if(file_exists($filePath))
        {
            $fileContent = oseFile::read($filePath);
            $result = oseJSON::decode($fileContent, true);
            return $result;
        }else{
            return false;
        }
    }

}

?>
