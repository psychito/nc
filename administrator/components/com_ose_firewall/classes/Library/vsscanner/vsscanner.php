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
class virusScanner {
    private $db = null;
    protected $filestable = '#__osefirewall_files';
    protected $logstable = '#__osefirewall_logs';
    protected $malwaretable = '#__osefirewall_malware';
    private $scanhisttablebl = '#__osefirewall_scanhist';
    private $file_ext = '';
    private $config = '';
    private $maxfilesize = 0;
    private $patterns = '';
    private $size = 0;
    private $type = 0;
    protected $chunksize = 50;
    private $clamd = null;
    protected $vsInfo = array();
    private $last_scanned = '';
    protected  $virus_found = false;
    protected static $virus_count = 0;
    protected $memlimit = '1024M';
    private $original_maxdbconnection = 0;
    public function __construct()
    {
        oseFirewall::loadLanguage();
        $this->db= oseFirewall::getDBO();
        $this->setConfiguration();
        $this->setFileExts();
        $this->setMaxFileSize();
        $this->setDBConn();
        $this->setMaxExTime();
        $this->optimizePHP();
        $this->setClamd();
        oseFirewall::loadFiles();
        date_default_timezone_set('Australia/Melbourne');
        $this->setDefaultChunkSize();
    }

    private function setDefaultChunkSize()
    {
        if(file_exists(OSE_CHUNKSIZE_FILE))
        {
            $this->chunksize = $this->getChunkSize();
        }else{
            $this->chunksize = 50 ;
        }
    }
    private function setClamd () {
        if ($this->config->enable_clamav == 1)
        {
            oseFirewall::callLibClass('clamd', 'clamd');
            $this->clamd = new Clamd();
        }
    }
    private function setConfiguration() {
        $config = oseFirewall::getConfiguration('vsscan');
        $this->config = (object)$config['data'];
    }

    private function setMaxExTime()
    {
        if (empty($this->config->maxextime)) {
            $this->config->maxextime = 300;
        }
        else if ($this->config->maxextime <60) {
            $this->config->maxextime = 0;
        }
    }
    public function setFileExts()
    {
        if (!isset($this->config->file_ext))
        {
            $this->config->file_ext = "htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt";
        }
        $this->file_ext = explode(',', trim($this->config->file_ext));
    }
    private function setMaxFileSize () {
        if ($this->config->maxfilesize>0)
        {
            $this->config->maxfilesize = $this->config->maxfilesize * 1024 * 1024;
        }
    }
    private function setDBConn () {
        $this->original_maxdbconnection = $this->config->maxdbconn;
        if (empty($this->config->maxdbconn))
        {
            $this->config->maxdbconn = 200;
        }
    }
    protected function setMemLimit ($mem) {
        if (function_exists('ini_set')) {
            ini_set('memory_limit', $mem);
        }
    }
    protected function optimizePHP () {
        if (function_exists('ini_set'))
        {
            $this->setMaxExecutionTime ($this->config->maxextime);
            $this->setMemLimit('256M');
            ini_set("pcre.recursion_limit", "524");
            set_time_limit($this->config->maxextime);
        }

    }
    public function initDatabase($step, $directory) {
        if ($step<0)
        {
            $this->clearTable();
            $return = $this ->getReturn($directory);
        }
        else
        {
            $dirs = $this->getFolder(5);
            if (empty($dirs))
            {
                $return['cont']= false;
                $return['folders']= 0;
                $return['file']= 0;
            }
            else
            {
                $return=array();
                $return ['folder'] =0;
                $return ['file'] =0;
                foreach ($dirs as $dir)
                {
                    $tmp = $this ->getReturn($dir->filename);
                    $return ['folder'] += $tmp['folder'];
                    $return ['file'] += $tmp['file'];
                    $return ['cont'] = $tmp['cont'];
                    $return ['lastscanned'] = LAST_SCANNED. $dir->filename;
                    $this->deletepathDB($dir->filename);
                    unset($tmp);
                }
            }
        }
        if ($return ['cont']==true)
        {
            $return['summary'] = OSE_SCANNING.' '.$return ['folder'].' '.OSE_FOLDERS.' '.OSE_AND.' '.$return ['file'].' '.OSE_FILES.' ';
        }
        else
        {
            $total = $this->CountFiles();
            $return['summary'] = OSE_ADDED.' '.OSE_INTOTAL.' '.$total.' '.OSE_FILES.'.';
        }
        $this->db -> closeDBO();
        oseAjax::returnJSON($return);
    }
    private function clearTable () {
        $query = "TRUNCATE TABLE ".$this->db->quoteTable($this->filestable);
        $this->db->setQuery($query);
        $result = $this->db->query();
        return $result;
    }
    public function getReturn($path)
    {
        $return = $this->getFolderFiles($path);
        $return['cont']= $this->isFolderLeft();
        return $return;
    }
    private function getFolderFiles($folder) {
        // Initialize variables
        $arr = array();
        $arr['folder'] = 0;
        $arr['file'] = 0;
        $false = false;
        if (!is_dir($folder))
            return $false;
        $handle = @opendir($folder);
        // If directory is not accessible, just return FALSE
        if ($handle === FALSE) {
            return $false;
        }
        while ((($file = @readdir($handle)) !== false)) {
            if (($file != '.') && ($file != '..')) {
                $ds = ($folder == '') || ($folder == '/') || (@substr($folder, -1) == '/') || (@substr($folder, -1) == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR;
                $dir = $folder . $ds . $file;
                $isDir = is_dir($dir);
                if ($isDir) {
                    $arr['folder'] ++;
                    $this->insertData($dir, 'd');
                }
                else
                {
                    $fileext = $this->getExt($dir);
                    $filesize= filesize($dir);
                    if (in_array($fileext, $this->file_ext))
                    {
                        if (!empty($this->config->maxfilesize))
                        {
                            if(filesize($dir) < $this->config->maxfilesize)
                            {
                                $arr['file'] ++;
                                $this->insertData($dir, 'f', $fileext);
                            }
                        }
                        else
                        {
                            $arr['file'] ++;
                            $this->insertData($dir, 'f', $fileext);
                        }
                    }
                }
            }
        }
        @closedir($handle);
        return $arr;
    }
    protected function insertData($filename,$type, $fileext='')
    {
        $result = $this->getfromDB($filename, $type, $fileext);
        if (empty($result))
        {
            return $this->insertInDB($filename, $type, $fileext);
        }
        else
        {
            $this->updateFile($result -> id, 'checked', 0);
            return $result -> id;
        }
    }
    private function getfromDB($filename, $type, $fileext) {
        $query = "SELECT `id` "
            ."FROM ".$this->db->quoteTable($this->filestable)
            ." WHERE `filename` = ".$this->db->quoteValue($filename)
            ." AND `type` = ".$this->db->quoteValue($type)
            ." AND `ext` = ".$this->db->quoteValue($fileext);
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        return $result;
    }
    public function insertInDB($filename, $type, $fileext) {
        $varValues = array(
            'filename' => $filename,
            'type' => $type,
            'checked' => 0,
            'patterns' => '',
            'ext' => $fileext
        );
        $id = $this->db->addData ('insert', $this->filestable, '', '', $varValues);
        return $id;
    }
    private function isFolderLeft() {
        $query = "SELECT COUNT(`id`) as count FROM ".$this->db->quoteTable($this->filestable)
            ." WHERE `type` = 'd'";
        $this->db->setQuery($query);
        $result = (object)$this->db->loadObject();
        return $result->count;
    }
    private function getExt($file)
    {
        $dot = strrpos($file, '.') + 1;
        return substr($file, $dot);
    }
    private function getFolder($limit)
    {
        $query = "SELECT `filename` FROM `".$this->filestable."`"
            ." WHERE `type` = 'd' LIMIT ".(int)$limit;
        $this->db->setQuery($query);
        $result = $this->db->loadObjectList();
        return $result;
    }
    private function deletepathDB($path)
    {
        $query = "DELETE FROM `".$this->filestable."` WHERE `type` = 'd' AND `filename` = " .$this->db->quoteValue ($path);
        $this->db->setQuery($query);
        return $this->db->query();
    }
    public function countFiles() {
        $query = "SELECT COUNT(`id`) as count FROM `".$this->filestable."`"
            ." WHERE `type` = 'f'";
        $this->db->setQuery($query);
        $result = (object)$this->db->loadResult();
        return $result->count;
    }
    private function getFiles($limit, $status)
    {
        $query = "SELECT `id`, `filename` FROM `".$this->filestable."`"
            ." WHERE `type` = 'f' "
            ." AND `checked` = ".(int)	$status
            ." LIMIT ".(int)$limit;
        $this->db->setQuery($query);
        $result = $this->db->loadObjectList();
        return (!empty($result))?$result:false;
    }

    function stripslashes_centrora($value)
    {
        if (is_array($value)) {
            $value = array_map('stripslashes_centrora', $value);
        } elseif (is_object($value)) {
            $vars = get_object_vars($value);
            foreach ($vars as $key => $data) {
                $value->{$key} = stripslashes_centrora($data);
            }
        } elseif (is_string($value)) {
            $value = stripslashes($value);
        }

        return $value;
    }

    private function setPatterns($array,$fw7 =false)
    {
        $type = '(' . implode(',', $array) . ')';
        $query = "SELECT `id`,`patterns` FROM `#__osefirewall_vspatterns` WHERE `type_id` IN " . $type;
        $this->db->setQuery($query);
        $this->patterns = $this->db->loadArrayList();
        if($fw7)
        {
            file_put_contents(OSE_VIRUSPATTERN_FILE_FW7, '<?php $pattern = ' . var_export($this->patterns, true) . ';');

        }else{
            file_put_contents(OSE_VIRUSPATTERN_FILE, '<?php $pattern = ' . var_export($this->patterns, true) . ';');

        }
    }

    public function generatePatternsFiles($array,$fw7 = false)
    {
        $this->setPatterns($array,$fw7);
    }


    private function updateAllFileStatus($status = 0)
    {
        $query = "UPDATE `".$this->filestable."` SET `checked` = ". (int)$status;
        $this->db->setQuery($query);
        $result = $this->db->query();
        return $result;
    }

    public function vsScan($step, $type, $remote = false)
    {
        if ($step == -1)
        {
            $this->clearTable();
            $this->cleanMalwareData ();
            $this->clearPathFiles();
            $this->clearPattern();
            $_SESSION['completed'] = 0;
            $_SESSION['start_time'] = time();
            $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "viruscount.php";
            if(file_exists($filePath))
            {
                unlink($filePath);
            }
            if (empty($type)) {
                $type = array(1, 2, 3, 4, 5, 6, 7, 8);
            } elseif (!is_array($type)) {
                $type = explode(',', $type);
            }
            $result = $this->scanFiles();
            $this->setPatterns($type);
        }
        if ($remote == false) {
            $this->db->closeDBO();
            $this->db->closeDBOFinal();
        }
        return $result;
    }

    protected function clearPathFiles()
    {
        $files = scandir(OSE_FWDATA . ODS . "vsscanPath" . ODS);
        if (count($files) > 3) {
            foreach ($files as $file) {
                if (substr($file,0,4)=="path") {
                    unlink(OSE_FWDATA . ODS . "vsscanPath" . ODS.$file);
                }
            }
        }
    }

    protected function scanFiles () {
        if (!empty($_POST['scanPath']))
        {
            $scanPath = trim($_POST['scanPath']);
            $this->saveFilesFromPath($scanPath);
        } else
        {
            $scanPath = oseFirewall::getScanPath();
            $this->saveFilesFromPath($scanPath);
        }
        $result = $this->showScanningStatusMsg();
        return $result;
    }
    protected function returnAjaxResults ($scanPath) {
        $path = OSE_FWDATA . ODS . "vsscanPath" . ODS . "dirList.json";
        $content = oseFile::read($path);
        $dirArray = oseJSON::decode($content);
        $result = array ();
        if (COUNT($dirArray)==0)
        {
            $return['completed'] = 0;
            $result['success']=true;
            $result['status']='Completed';
            $result['contFileScan']=false;
            $result['content']='All files in the website have been added to the scanning queue.';
        }
        else
        {
            $return['completed'] = 0;
            $result['success']=true;
            $result['status']='Continue';
            $result['contFileScan']=true;
            $result['content']='Continue scanning files, the last folder being scanned is: '.$scanPath;
            $this->saveDirFile (array() , true);
        }
        $result['last_file'] = $scanPath;
        $result['cont'] = true;
        $result['cpuload'] = $this->getCPULoad();;
        $result['memory'] = $this->getMemoryUsed();
        return $result;
    }
    protected function getdirList () {
        $baseScanPath = $this->getBaseScanPath();
        $path = OSE_FWDATA.ODS."vsscanPath".ODS."dirList.json";
        $content = oseFile::read($path);
        $dirArray = oseJSON::decode($content);
        $this->saveDirFile (array(), true) ;
        $start_time = time();
        while (COUNT($dirArray)>0)
        {
            $since_start = $this->timeDifference($start_time, time());
            if ($since_start>10) {
                $this->saveDirFile ($dirArray, false);
                break;
            }
            $scanPath = array_pop($dirArray);
            while (empty($scanPath)) {
                $scanPath = array_pop($dirArray);
            }
            $this->saveFilesFromPath ($scanPath, $baseScanPath);
        }
        return $scanPath;
    }
    protected function saveBaseScanPath($scanPath) {
        $filePath = OSE_FWDATA.ODS."vsscanPath".ODS."basePath.json";
        $scanPath = str_replace(' ', '', $scanPath);
        $fileContent = oseJSON::encode(array($scanPath));
        $result = oseFile::write($filePath, $fileContent);
        return $result;
    }
    protected function getBaseScanPath () {
        oseFirewall::loadJSON();
        $filePath = OSE_FWDATA.ODS."vsscanPath".ODS."basePath.json";
        if (file_exists($filePath)) {
            $content = oseFile::read($filePath);
            return oseJSON::decode($content);
        }
        else
        {
            return array();
        }
    }

    protected function saveFilesFromPath($scanPath)
    {
        $files = array ();
        $cmdline_option  = oseFirewallBase::getVersionInfo('cmdl');
        if($cmdline_option == 1)
        {
            oseFirewall::callLibClass('gitBackup', 'GitSetup');
            $gitbackup = new GitSetup(false);
            $gitcmd = "cd $scanPath ; find $scanPath -type f";
            $result = $gitbackup->runShellCommandWithStandardOutput($gitcmd);
            $content  = explode("\n",$result);

            foreach($content as $path) {
                $temp1 = $this->handleNoExtensionFile($path);
                if ($temp1 == true) {
                    $files[] = addslashes($path);
                } else{
                    $needle = "com_ose_firewall/protected/data";
                    $needle2 = "modules/mod_pwebcontact/helpers/sobipro.php";
                    $needle3= "media/widgetkit";
                    if(strpos($path,$needle) == false && strpos($path,$needle2) == false && strpos($path,$needle3) == false) {
                        if (is_file($path) && $this->getFileInformation($path) && !strstr($path, '.git/') && !strstr($path, OSE_FWDATA . ODS . 'vsscanPath') && substr($path, -9) != '.svn-base') {
                            $extension = substr($path, -4);
                            if (!in_array($extension, array('.pbk', '.png', '.pdf', '.zip', '.jpa', '.ttf', '.svg', '.otf', '.eot')) && !$this->isZipFile($path) && ($this->ignoreAkeebaFiles($path)) && ($this->needtoScanFile($path))) {
                                if ($path != OSE_FWDATA . ODS . "vsscanPath" . ODS . "pattern.php") {
                                    $files[] = addslashes($path);
                                }
                            }
                        }
                    }
                }
            }
        }else
        {
            oseFirewall::loadJSON();
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath($scanPath), RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
            foreach ($objects as $path => $dir) {
                $temp1 = $this->handleNoExtensionFile($path);
                if ($temp1 == true) {
                    $files[] = addslashes($path);
                } else {
                    $needle = "com_ose_firewall/protected/data";
                    $needle2 = "modules/mod_pwebcontact/helpers/sobipro.php";
                    $needle3= "media/widgetkit";
                    if(strpos($path,$needle) == false && strpos($path,$needle2) == false && strpos($path,$needle3) == false) {
                        if (is_file($path) && $this->getFileInformation($path) && !strstr($path, '.git/') && !strstr($path, OSE_FWDATA . ODS . 'vsscanPath') && substr($path, -9) != '.svn-base') {
                            $extension = substr($path, -4);
                            if (!in_array($extension, array('.pbk', '.png', '.pdf', '.zip', '.jpa', '.ttf', '.svg', '.otf', '.eot')) && !$this->isZipFile($path) && ($this->ignoreAkeebaFiles($path)) && ($this->needtoScanFile($path))) {
                                if ($path != OSE_FWDATA . ODS . "vsscanPath" . ODS . "pattern.php") {
                                    $files[] = addslashes($path);
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($files))
        {
            $total = count($files);
            $this->setChunkSize($total);
            $this->writeChunkSizeValue($this->chunksize);
            // Get the toal number of batches
            $this->size = (($total - $total % $this->chunksize) / $this->chunksize + 1) - 1;
            $this->saveFilesFile($files, $total);
        }
    }

    protected function setChunkSize($nooffiles)
    {
        $temp_size =  (($nooffiles - $nooffiles % $this->chunksize) / $this->chunksize + 1) - 1;
        if($temp_size<=723 && $this->chunksize<=60)
        {
            return true;
        }else{
            $this->chunksize++;
            if($this->chunksize>60)
            {
                $this->chunksize = 50;
                return true;
            }
            $this->setChunkSize($nooffiles);
        }
    }

    protected function getFileExtensionsToBeScanned()
    {
        if(!empty($this->config->file_ext))
        {
            $allowed_file_ext = explode(',', trim($this->config->file_ext));
            return $allowed_file_ext;
        }else{
            $allowed_file_ext =  array("htm","html","shtm","shtml","css","js","php","php3","php4","php5","inc","phtml","jpg","jpeg","gif","png","bmp","c","sh","pl","perl","cgi","txt");
            return $allowed_file_ext;
        }
    }

    protected function needtoScanFile($path)
    {
        $path_parts = pathinfo($path);
        $allowed_extensions = $this->getFileExtensionsToBeScanned();
        if(empty($path_parts) || empty($allowed_extensions)) //fail safe
        {
            return true;
        }
        if(!empty($path_parts) && (!isset($path_parts['extension'])))
        {
            return true;
        }
        if(isset($path_parts['extension']) && in_array($path_parts['extension'],$allowed_extensions))
        {
            return true;
        }else{
            return false;
        }
    }

    protected function handleNoExtensionFile($path)
    {
        $path_parts = pathinfo($path);
        if(!empty($path_parts) && (!isset($path_parts['extension'])))
        {
            return true;
        }else{
            return false;
        }
    }


    protected function getFileInformation($file)
    {
        $path_parts = pathinfo($file);
        if(!empty($path_parts))
//        if(!empty($path_parts) && isset($path_parts['extension']) && !empty($path_parts['extension']))
        {
            return true;
        }else {
            return false;
        }
    }

    protected function isZipFile($file)
    {
        $path_parts = pathinfo($file);
        if(!empty($path_parts) && isset($path_parts['extension']) && !empty($path_parts['extension']))
        {
            if($path_parts['extension'] == "gz")
            {
                return true;
            }else{
                $zippattern = "/z[0-9]/im";
                if(preg_match($zippattern,$path_parts['extension']))
                {
                    return true;
                }else {
                    return false;
                }
            }
        }else {
            return false;
        }
    }

    protected function emptyDirFile () {
        $content = "";
        $filePath = OSE_FWDATA.ODS."vsscanPath".ODS."dirList.json";
        $result = oseFile::write($filePath, $content);
    }
    protected function saveDirFile ($dirs, $update=false) {
        $filePath = OSE_FWDATA.ODS."vsscanPath".ODS."dirList.json";
        if (file_exists($filePath))
        {
            if ($update == false)
            {
                $contentArray= oseJSON::decode(oseFile::read ($filePath));
                $fileContent = oseJSON::encode(array_merge ($dirs, $contentArray));
                $result = oseFile::write($filePath, $fileContent);
            }
            else
            {
                $fileContent = oseJSON::encode($dirs);
                $result = oseFile::write($filePath, $fileContent);
            }
        }
        else
        {
            $fileContent = oseJSON::encode($dirs);
            $result = oseFile::write($filePath, $fileContent);
        }
    }

    protected function saveFilesFile($files, $total, $batch = 0)
    {

        $loadfiles = array();
        // Get the start number of the batch files
        $start = ($this->size - $batch) * $this->chunksize;
        $next = ($this->size - $batch + 1) * $this->chunksize;
        $end = ($next<$total)?$next:$total;
        for ($i=$end-1; $i>=$start; $i--)
        {
            $loadfiles[] = $files[$i];
        }
        $this->createBatchFile($this->size - $batch, $loadfiles);
        $batch ++;
        if (($batch) <= $this->size) {
            $this->saveFilesFile($files, $total, ($batch));
        }
    }
    protected function createBatchFile ($batch, $loadfiles) {
        $array = 'array("' . implode('"'.",\n".'"', $loadfiles) . '");';
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "path" . $batch . ".php";
        file_put_contents($filePath, '<?php $path' . $batch . ' = ' . $array);
    }
    public function vsScanInd($process, $size, $remote = false)
    {
        oseFirewall::loadJSON();
        $conn = $this->getCurrentConnection();
        if ($remote == true) {
            if($this->original_maxdbconnection == 0 || empty($this->original_maxdbconnection))
            {
                $maxdbCheck = false;
            }else{
                $maxdbCheck = true;
            }
            $this->config->maxdbconn = 200;
        }else{
            $maxdbCheck = true;
        }
        if ($conn > $this->config->maxdbconn && $maxdbCheck)
        {
            $result = $this->getHoldingStatus($process, $conn);
        }
        else
        {
            oseFirewall::loadFiles();
            $result = $this->showScanningResultMsg($process, $size, $remote);
        }
        if ($remote == false) {
            $this->db->closeDBO();
            $this->db->closeDBOFinal();
        }
        return $result;
    }

    private function getHoldingStatus($process, $conn)
    {
        $this->vsInfo = $this->getVsFiles($process);
        $timeUsed = $this->timeDifference($_SESSION['start_time'], time());
        $completed = $this->vsInfo->completed;
        $left = count($this->vsInfo->fileset);
        $total = $this->vsInfo->completed + $left;
        $progress = ($completed/$total);
        $return['completed'] = 'Queue';
        $return['summary'] = (round($progress, 3)*100). '% ' .oLang::_get('COMPLETED');
        $return['progress'] = "<b>Progress: ".($left)." files remaining.</b>. Time Used: ".$timeUsed." seconds<br/><br/>";
        $return['last_file'] = oLang::_get('CURRENT_DATABASE_CONNECTIONS').': '.$conn.'. '.oLang::_get('YOUR_MAX_DATABASE_CONNECTIONS').': '.$this->config->maxdbconn.'. '.oLang::_get('WAITING_DATABASE_CONNECTIONS');
        $return['cont'] = ($left > 0 )?true:false;
        $return['cpuload'] = $this->getCPULoad();;
        $return['memory'] = $this->getMemoryUsed();
        return $return;
    }
    private function getCurrentConnection () {
        $result = $this->db->getCurrentConnection ();
        return $result['Value'];
    }

    private function showScanningResultMsg($process, $size, $remote)
    {
        $return=array();
        $return['summary'] = null;
        $return['found'] = 0;
        $start_time = time();
        $result = $this->scanFileLoop($start_time, $process, $size, $remote);
        if($result == false)
        {
            return false;
        } else {
            return $result;
        }
    }

    private function scanFileLoop($start_time, $process, $size, $remote = false)
    {
        $this->vsInfo = $this->getVsFiles($process);
        if ($process == $size) {
            $lastfileno = count($this->vsInfo);
        }
        $pattern = array();
        $patternFile = OSE_FWDATA . ODS . "vsscanPath" . ODS . "pattern.php";
        if (file_exists($patternFile)) {
            require_once(OSE_FWDATA . ODS . "vsscanPath" . ODS . "pattern.php");
        } else {
            return $this->returnCompleteMsgFinal($this->last_scanned, $process, $size, $lastfileno, $remote);
        }
        $i=0;
        while (!empty($this->vsInfo)) {
            $this->last_scanned = stripslashes(array_pop($this->vsInfo));
            if (oseFile::exists($this->last_scanned) == false) {
                continue;
            }
            if (filesize($this->last_scanned) > 2048000) {
                continue;
            } else {
                if ($this->getFileInformation($this->last_scanned)) {
                    $this->scanFile($this->last_scanned, $pattern);
                }
            }
            // In order to allow the scanner to continue without touching the same files again
            if ($remote == true && $i == 5) {
                $i = 0;
                $this->createBatchFile($process, $this->vsInfo);
            }
            $i++;
        }
        return $this->returnCompleteMsg($this->last_scanned, $process, $size, $lastfileno, $remote);
    }
    public function getVirusPatternsfromLocalFile($fw7 = false){
        if($fw7 == true)
        {
            $file = OSE_VIRUSPATTERN_FILE_FW7;
        }else{
            $file =OSE_VIRUSPATTERN_FILE;
        }
        $pattern = array();
        if(file_exists($file))
        {
            require($file);

        }
        return $pattern;

    }
    private function ignoredFiles ($file)
    {
        if (preg_match('/mootree\.gif/ims', $file) || ($this->checkIsMarkedAsClean($file))) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIsMarkedAsClean($file)
    {
        if (file_exists(CENTRORABACKUP_FOLDER . ODS . "markasclean.php")) {
            require(CENTRORABACKUP_FOLDER . ODS . "markasclean.php");
        }
        if (isset($whitelist)) {
            foreach ($whitelist as $single) {
                if ($file == $single['filename'] && md5_file($file) == $single['content']) {
                    return true;
                }
            }
        }
        return false;
    }
    private function clearFileFromArray ($index) {
        unset($_SESSION['oseFileArray'][$index]);
    }
    private function showScanningStatusMsg () {
        //$this->saveVsFilesLoop();
        $return['size'] = $this->size;
        $return['completed'] = 0;
        $return['summary'] = 'Finish creating all scanning objects, Scanner will start shortly';
        $return['progress'] = 'Finish creating all scanning objects, Scanner will start shortly';
        $return['last_file'] = 'Finish creating all scanning objects, Scanner will start shortly';
        $return['cont'] = true;
        $return['cpuload'] = $this->getCPULoad();;
        $return['memory'] = $this->getMemoryUsed();;
        $return['showCountFiles'] = false;
        $return['totalvs'] = 0;
        $return['totalscan'] = 0;
        $temp = $this->getRemainingFilesCount();
        $return['totalScanNum'] = $temp['totalScanNum'];
        $return['lastBatchNum'] = $temp['lastBatchNum'];
        $return['value'] = $temp['value'];
        return $return;
    }
    private function showCountFilesMsg () {
        $first  = new DateTime(date('Y-m-d h:i:s'));
        if (!empty($_POST['scanPath']))
        {
            $scanPath = $_POST['scanPath'];
        }
        else
        {
            $scanPath = oseFirewall::getScanPath();
        }
        $fileCount = $this->getNumberofFiles($scanPath);
        $timeUsed = $this->getTimeUsed ($first);
        $memUsed = $this->getMemoryUsed ();
        $return['completed'] = 0;
        $return['summary'] = 'There are in total of '.$fileCount.' files in your website (time used '.$timeUsed.'), the scanning will start shortly';
        $return['progress'] = 'Found '.$fileCount.' number of files';
        $return['last_file'] = '';
        $return['cont'] = true;
        $return['cpuload'] = $this->getCPULoad();;
        $return['memory'] = $memUsed;
        $return['showCountFiles'] = true;
        return $return;
    }
    protected function getMemoryUsed () {
        $newMemoryUsage = round(memory_get_usage(true)/(1028*1024), 2);
        return $newMemoryUsage;
    }
    private function getTimeUsed ($first) {
        $second = new DateTime(date('Y-m-d h:i:s'));
        $diff = $first->diff( $second );
        $timeUsed = $diff->format( '%H:%I:%S' );
        return $timeUsed;
    }
    private function getNumberofFiles ($path) {
        $x = 0;
        $oseFileArray = array ();
        if (!empty($path)) {
            $dir_iterator = new RecursiveDirectoryIterator($path);
            $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
            foreach ($iterator as $fullFileName => $fileSPLObject)
            {
                if ($fullFileName != $path."/.." && $fileSPLObject->isFile() && in_array($this->getFileExtension($fullFileName), $this->file_ext)) {
                    $x++;
                    $oseFileArray[] = $fullFileName;
                }
            }
        }
        $this->saveVsFiles($oseFileArray, $_SESSION['completed']);
        return $x;
    }
    private function getFileExtension ($path) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return $ext;
    }

    protected function isMissingPHP()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "missing.php";
        if (file_exists($filePath)) {
            $result = file_get_contents($filePath);
        } else {
            $result = false;
        }
        return $result;
    }

    protected function clearMissingPHP()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "missing.php";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    protected function returnCompleteMsgFinal($last_file = null, $process, $size, $lastfileno, $remote = false) {
        $timeUsed = $this->timeDifference($_SESSION['start_time'], time());
        $timeUsed = gmdate("H:i:s", $timeUsed);
        $return['completed'] = 100;
        $return['summary'] = ($return['completed']) . '% ' . oLang::_get('COMPLETED');
        $return['last_file'] = $last_file;
        $return['status'] = 'Completed';
        $return['cont'] = false;
        $return['cpuload'] = $this->getCPULoad();;
        $return['memory'] = $this->getMemoryUsed();
        $return['totalvs'] = $this->getVirusCount();
        $return['totalscan'] = ($process + 1) * $this->chunksize + $lastfileno;
        $return['timeUsed'] = $timeUsed . 's';
        $return['process'] = $process;
        $return['size'] = $size;
        $flag = $this->isMissingPHP();
        if ($flag) {
            $return['missingPHP'] = $flag;
        } else {
            $return['missingPHP'] = 'notExist';
        }
        $this->saveDBLastScanResult($return);
        $this->clearFileSingle($process);
        $this->clearPattern();
        $this->clearMissingPHP();
        if ($remote == true) {
            $this->sendEmail($return['totalvs']);
        }
        $this->deleteChunksizeFile();
        return $return;
    }
    protected function returnCompleteMsg($last_file = null, $process, $size, $lastfileno, $remote = false)
    {
        $timeUsed = $this->timeDifference($_SESSION['start_time'], time());
        $timeUsed = gmdate("H:i:s", $timeUsed);
        if ($process == $size && $size == 0) {
            $return['completed'] = 100;
            $return['summary'] = ($return['completed']) . '% ' . oLang::_get('COMPLETED');
            $return['last_file'] = $last_file;
            $return['status'] = 'Completed';
            $return['cont'] = false;
            $return['cpuload'] = $this->getCPULoad();;
            $return['memory'] = $this->getMemoryUsed();
            $return['totalvs'] = $this->getVirusCount();
            $return['totalscan'] = $process * $this->chunksize + $lastfileno;
            $return['timeUsed'] = $timeUsed . 's';
            $return['process'] = $process;
            $return['size'] = $size;
            $flag = $this->isMissingPHP();
            if ($flag) {
                $return['missingPHP'] = $flag;
            } else {
                $return['missingPHP'] = 'notExist';
            }
            $this->saveDBLastScanResult($return);
            $this->clearFile($size);
            $this->clearPattern();
            $this->clearMissingPHP();
            if ($remote == true) {
                $this->sendEmail($return['totalvs']);
            }
            $this->deleteChunksizeFile();
            return $return;
        } elseif ($process == $size) {
            $return['completed'] = 100;
            $return['summary'] = ($return['completed']) . '% ' . oLang::_get('COMPLETED');
            $return['last_file'] = $last_file;
            $return['status'] = 'Completed';
            $return['cont'] = false;
            $return['cpuload'] = $this->getCPULoad();;
            $return['memory'] = $this->getMemoryUsed();
            $return['totalvs'] = $this->getVirusCount();
            $return['totalscan'] = ($process + 1) * $this->chunksize + $lastfileno;
            $return['timeUsed'] = $timeUsed . 's';
            $return['process'] = $process;
            $return['size'] = $size;
            $flag = $this->isMissingPHP();
            if ($flag) {
                $return['missingPHP'] = $flag;
            } else {
                $return['missingPHP'] = 'notExist';
            }
            $this->saveDBLastScanResult($return);
            $this->clearFileSingle($process);
            $this->clearPattern();
            $this->clearMissingPHP();
            if ($remote == true) {
                $this->sendEmail($return['totalvs']);
            }
            $this->deleteChunksizeFile();
            return $return;
        }
        else
        {
            $return['completed'] = round($process / $size, 3) * 100;;
            $return['summary'] = ($return['completed']) . '% ' . oLang::_get('COMPLETED');
            $return['last_file'] = $last_file;
            $return['status'] = 'Continue';
            $return['cont'] = true;
            $return['cpuload'] = $this->getCPULoad();;
            $return['memory'] = $this->getMemoryUsed();
            $return['totalvs'] = $this->getVirusCount();
            $return['totalscan'] = ($process + 1) * $this->chunksize;
            $return['timeUsed'] = $timeUsed . 's';
            $return['process'] = $process + 1;
            $return['size'] = $size;
            $this->clearFileSingle($process);
            return $return;
        }
    }

    protected function getCurrentTime()
    {
        date_default_timezone_set('Australia/Melbourne');
        $time = date("Y-m-d H:i:s");
        return $time;
    }

    public function getLastScan ()
    {
        $result  = $this->getLastVsScanHist();
        $scanList = oseJSON::decode($result->content, true);
        $lastScan = array('scanDate' => $result->inserted_on
        ,'serverNow' => oseFirewall::getTime()
//            ,'content' => $scanList[0]['vsfilelist']
        );
        return $lastScan;
    }


    protected function saveDBLastScanResult ($content = '')
    {
        oseFirewall::loadJSON();
        $varValues = array('super_type' => 'vsscan',
            'sub_type' => 1,
            'content' => oseJSON::encode(array($content)),
            'inserted_on' => oseFirewall::getTime()
        );
        $this->db->addData('insert', $this->scanhisttablebl, '', '', $varValues);
    }

    private function getLastVsScanHist()
    {
        $query = "SELECT * FROM " . $this->db->quoteTable($this->scanhisttablebl)
            . " WHERE inserted_on = (SELECT max(inserted_on) FROM " . $this->db->quoteTable($this->scanhisttablebl)
            . " WHERE super_type = 'vsscan' AND sub_type = 1)";
        $this->db->setQuery($query);
        $result = $this->db->loadObject();
        return $result;
    }

    protected function getOverallLeft()
    {
        $lefttotal = 0 ;

        for($i=1; $i<=8; $i++)
        {
            $content = $this->getVsFiles($i);
            $lefttotal = $lefttotal + count($content->fileset);
        }

        return $lefttotal;
    }
    protected function getCPULoad () {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            $cpuload = 'N/A in Windows';
            return $cpuload;
        }
        else
        {
            $cpuload = sys_getloadavg();
            return $cpuload[0];
        }
    }
    private function getCompleted() {
        $query= "SELECT COUNT(`id`) as `count` FROM `#__osefirewall_files` WHERE `checked` = 0 ";
        $this->db->setQuery($query);
        $result = (object) ($this->db->loadResult());
        return $result->count;
    }
    private function getTotal() {
        $query= "SELECT COUNT(`id`) as `count` FROM  `#__osefirewall_files` ";
        $this->db->setQuery($query);
        $result = (object) ($this->db->loadResult());
        return $result->count;
    }
    private function updateFile($id, $field, $value){
        $query = " UPDATE `".$this->filestable."` SET `{$field}` = ".$this->db->quoteValue($value)
            ." WHERE id = " .(int)$id;
        $this->db->setQuery ($query);
        $result = $this->db->query();
        return $result;
    }
    protected function timeDifference($timeStart, $timeEnd){
        return $timeEnd- $timeStart;
    }

    protected function scanFile($scan_file, $array)
    {
        if (empty($scan_file))
        {
            return false;
        }
        oseFirewall::loadFiles();
        $virus_found = false;
        $content = oseFile::read ($scan_file);
        $start_time = time();
        foreach ($array as $pattern)
        {
            if ((time() - $start_time) > 400) {
                $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "missing.php";
                file_put_contents($filePath, $scan_file . "\n", FILE_APPEND);
                return false; // timeout, function took longer than 200 seconds
            }
            $flag = preg_split('/' . trim($pattern['patterns']) . '/im', $content, 2);
            if (count($flag) > 1) {
                if (preg_match('/mootree\.gif/ims', $scan_file) || $this->checkIsMarkedAsClean($scan_file)) {
                    break;
                } else {
                    $virus_found = true;
                    $file_id = $this->insertData($scan_file, 'f', '');
                    $this->logMalware($file_id, $pattern['id']);
                    $this->writeVirusCount($virus_found);
                    break;
                }
            }
        }
        usleep(100);
        return $virus_found;
    }
    public function fileScan($scan_file, $array)
    {
        $result = $this->scanFile($scan_file,$array);
        return $result;
    }

    protected function logMalware($file_id, $pattern_id)
    {
        $detectedMal = $this->getDectectedMal($file_id, $pattern_id);
        if (empty($detectedMal)) {
            $db = oseFirewall::getDBO();
            $varValues = array(
                'file_id' => (int)$file_id,
                'pattern_id' => (int)$pattern_id
            );
            $id = $db->addData('insert', $this->malwaretable, '', '', $varValues);
            return $id;
        } else {
            return /*$varObject->id*/
                ;
        }
    }
    protected function getDectectedMal($file_id, $pattern_id)
    {
        $db = oseFirewall::getDBO ();
        $query = "SELECT COUNT(`file_id`) as `count` FROM `".$this->malwaretable."`".
            " WHERE `file_id` = ".(int)$file_id;
        " AND `pattern_id` = ".(int)$pattern_id;
        $db->setQuery($query);
        $result = (object)($db->loadResult());
        $db -> closeDBO();
        return $result->count;
    }
    //write the count of the virusfiles into the file viruscount.php
    protected function writeVirusCount($virus_found)
    {
        $viruscount =0;
        $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "viruscount.php";
        if(file_exists($filePath))
        {
            require($filePath);
            $new_count = (int)$viruscount + 1;
            $content = "<?php\n".'$viruscount = '.$new_count.";";
            oseFirewall::callLibClass('gitBackup','GitSetup');
            $gitsetup = new GitSetup();
            $result = $gitsetup->writeFile($filePath, $content);
            return $result;
        }
        else
        {
            $content = '<?php $viruscount' . ' = '. var_export(1, true) .';';
            file_put_contents($filePath,$content);
        }

    }

    //return the value of virus count from the file viruscount.php
    public function getVirusCount()
    {
        $viruscount =0;
//		$user_type = oseFirewall::checkSubscriptionStatus();
//		if($user_type) {
        $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "viruscount.php";
        if (file_exists($filePath)) {
            require($filePath);
            return (int)$viruscount;
        } else {
            return 0;
        }
//		}
    }

    private function logScanning($status)
    {
        $result = $this->getScanninglog();
        if (!empty($result))
        {
            $this->updateScanninglog($result->id, $status);
        }
        else
        {
            $this->insertScanninglog($status);
        }
    }
    public function getScanninglog()
    {
        $db = oseFirewall::getDBO ();
        $query = "SELECT * FROM `".$this-> logstable."`"
            ." WHERE `comp` = 'avs'";

        $db->setQuery($query);
        $result = $db->loadobject();
        $db -> closeDBO();
        return $result;
    }
    private function insertScanninglog($status)
    {
        $this->db->insert($this->logtable,
            array(
                'id' => NULL,
                'date' => date('Y-m-d h:i:s'),
                'comp' => 'avs',
                'status' => $status
            ),
            array ('%d','%s', '%s', '%s'));
        return $this->db->insert_id;
    }
    private function updateScanninglog($id, $status)
    {
        $result = $this->db->query(
            $this->db->prepare(
                "UPDATE `".$this->logtable."` SET `status` = '%s', `date` = '%s'  WHERE id = %d",
                $status, date('Y-m-d h:i:s'), $id
            )
        );
        return $result;
    }
    private function cleanMalwareData () {
        $query = "TRUNCATE TABLE `". $this->malwaretable."`;";
        $this->db->setQuery ($query);
        $result = $this->db->query();
        return $result;
    }
    private function saveVsFiles($fileset, $completed, $type=null)
    {
        $array = "array('" . implode("',\n'", $fileset) . "');";

        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "path" . $type . ".php";

        $result = file_put_contents($filePath, '<?php $complete' . $type . ' = ' . $completed . '; $path' . $type . ' = ' . $array);

        return $result;
    }

    protected function clearFile($size)
    {
        for ($i = 0; $i <= $size; $i++) {
            $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "path" . $i . ".php";
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    protected function clearFileSingle($process)
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "path" . $process . ".php";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    protected function clearPattern()
    {

        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "pattern.php";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function getVsFiles($process)
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "path" . $process . ".php";
        if (file_exists($filePath)) {
            require_once($filePath);
            $variable = 'path' . $process;
            $return = $$variable;
        } else {
            $return = 0;
        }
        return $return;
    }
    protected function setMaxExecutionTime ($seconds) {
        if (function_exists('ini_set')) {
            ini_set('max_execution_time', (int)$seconds);
        }
        if (function_exists('set_time_limit')) {
            set_time_limit(0);
        }
    }
    protected function searchMissing () {
        $files = scandir (OSE_FWDATA . ODS . "vsscanPath" . ODS );
        if (count($files)>3) {
            foreach ($files as $file) {
                if (strpos($file, 'path') === 0) {
                    $process = preg_replace("/path/", "", $file);
                    $this->vsScanInd($process, -1, true);
                }
            }
            // TODO: Leave this to future development -->
            // Do a loop searching to see if any files are left;
            // $this->scanMissing();
        }
    }

    public function getNumInfectedFiles()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT COUNT(`file_id`) AS `count` FROM `" . $this->malwaretable . "`";
        $db->setQuery($query);
        $result = (object)($db->loadResult());
        $db->closeDBO();
        return $result->count;
    }

    protected function sendEmail($virusFound)
    {
        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            oseFirewall::callLibClass('emails', 'emails');
            $emailManager = new oseFirewallemails ();
            $content = $this->getEmailContent($virusFound);
            if ($virusFound == true) {
                $emailType = "virusFound";
            } else {
                $emailType = "virusClean";
            }
            $emailManager->sendemail($emailType, $content);
        }
    }
    public function scheduleScanning ($step, $type) {
        $this->setMemLimit('1024M');
        $this->setMaxExecutionTime (0);
        oseFirewall::loadRequest();
        $key = oRequest::getVar('key', NULL);
        $completed = oRequest::getVar('completed', NULL);
        $type = 0;
        if ($completed == true) {
            $this->searchMissing ();
            $this->returnCompleteMsg(null, 0, 0, null, true);
        }
        else {
            if (!empty($key))
            {
                $process = oRequest::getVar('process', null);
                $batch = oRequest::getInt('batch', 0);
                if ($step < 0)
                {
                    $result = $this->vsscan($step, $type, true);
                    $remainingFiles = $this->getRemainingFilesCount();
                    $result['process'] = 0;
                    $result ['confirm'] = 0;
                    $url = $this->getCrawbackURL($key, $result['cont'], $result['process'], $result ['size'], $result ['confirm'], $remainingFiles);
                    $this->db->closeDBO();
                    $this->db->closeDBOFinal();
                    $this->sendRequestVS($url);
                }
                else {
                    $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "path" . $process . ".php";
                    if (file_exists(($filePath))) {
                        $result = $this->vsScanInd($process, -1, true);
                        $result ['confirm'] = 0;
                    }
                    else {
                        $result['cont'] = 1;
                        $result ['confirm'] = 1;
                        $result ['size'] = 0;
                    }
                    $url = $this->getCrawbackURL($key, $result['cont'], $process, $result ['size'], $result ['confirm']);
                    $this->sendRequestVS($url);
                    $this->db->closeDBO();
                    $this->db->closeDBOFinal();
                }
                echo 'Completed'; exit;
            }
        }
        exit;
    }
    protected function getWebsiteStatus () {
        $infected = $this->getVirusCount();
        return ($infected>0)?1:0;
    }

    private function getCrawbackURL($key, $con, $process, $size, $confirm, $remainingFiles = array())
    {
        $webkey= $this->getWebKey ();
        if (!empty($remainingFiles)) {
            return VSSCAN_API_SERVER."vsscan/setupCronBatches?webkey=" . $webkey . "&key=" . $key . "&totalScanNum=" . $remainingFiles['totalScanNum'];
        }
        else {
            if ($con == false) {
                $status = $this->getWebsiteStatus();
                return VSSCAN_API_SERVER."vsscan/completeVSScanV6?webkey=" . $webkey . "&key=" . $key . "&con=" . $con . "&status=" . (int)$status . "&confirm=" . (int)$confirm;
            } else {
                return VSSCAN_API_SERVER."vsscan/scanresultsCallback?webkey=" . $webkey . "&key=" . $key . "&con=" . $con . "&process=" . $process;
            }
        }
    }
    protected function getScanFilePath ($type) {
        return OSE_FWDATA.ODS."vsscanPath".ODS."path_".$type.".json";;
    }
    protected function getWebKey () {
        $this->db = oseFirewall::getDBO();
        $query = "SELECT * FROM `#__ose_secConfig` WHERE `key` = 'webkey'";
        $this->db->setQuery($query);
        $webkey = $this->db->loadObject();
        if (!empty($webkey) && property_exists($webkey, 'value')) {
            return $webkey->value;
        }
        return $webkey;
    }
    private function sendRequestVS($url)
    {
        $User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
        $request_headers = array();
        $request_headers[] = 'User-Agent: '. $User_Agent;
        $request_headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => $request_headers,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Centrora Security Download Request Agent',
            CURLOPT_TIMEOUT => 60
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }

    public function  human_filesize($bytes, $decimals = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    private function strpos_array($haystack, $needles)
    {
        if (is_array($needles)) {
            foreach ($needles as $str) {
                if (is_array($str)) {
                    $pos = $this->strpos_array($haystack, $str);
                } else {
                    $pos = strpos($haystack, $str);
                }
                if ($pos !== FALSE) {
                    continue;
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return strpos($haystack, $needles);
        }
    }

    public function ifContinue()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath";
        if ($this->is_dir_empty($filePath)) {
            return false;
        } else {
            return true;
        }
    }

    private function  is_dir_empty($dir)
    {
        if (!is_readable($dir)) return NULL;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry == 'pattern.php') {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function getContinueProcess()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath";
        if ($this->is_dir_empty($filePath)) {
            return false;
        } else {
            $return = $this->getPathProcess();
            return $return;
        }
    }

    private function getPathProcess()
    {
        $return = array();
        $candidate = array();
        $filePath = OSE_FWDATA . ODS . "vsscanPath";
        $files = scandir($filePath);
        foreach ($files as $file) {
            if (strpos($file, 'path') === 0) {
                $candidate[] = preg_replace("/[^0-9]/", "", $file);
            }
        }
        $return['size'] = max($candidate);
        $return['process'] = min($candidate);
        return $return;
    }

    protected function getRemainingFilesCount()
    {
        $value = 1;
        $result = $this->getPathProcess();
        $totalremainingfile = $result['size'] + 1;
        $totalScanNum = floor($totalremainingfile /$value);
        $lastBatchNum = ($totalremainingfile % $value);
        $return['totalScanNum'] =$totalScanNum;
        $return['lastBatchNum'] =$lastBatchNum;
        $return['value'] = $value;
        return $return;
    }

    //check if the last version check file exists
    // if not create a file with the current date and time
    public function getLastVersionCheck()
    {
        $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "LastVersionCheck.php";
        if (file_exists($filePath)) {
            require_once($filePath);
            return $lastcheck;
        }
    }

    public function virusSignatureVersionFileExists()
    {
        $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "LastVersionCheck.php";
        if (file_exists($filePath)) {
            return true;
        }else {
            return false;
        }

    }

    public function getDatefromVirusCheckFile()
    {
        if($this->virusSignatureVersionFileExists())
        {
            $lastcheck ='';
            $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "LastVersionCheck.php";
            require_once($filePath);
            $result['year'] = substr($lastcheck,0,4);
            $result['month'] =substr($lastcheck,4,2);
            $result['date'] =substr($lastcheck,6,2);
            $result['hour'] =substr($lastcheck,8,2);
            $result['min'] =substr($lastcheck,10,2);
            $result['sec'] =substr($lastcheck,12,2);
            return $result;
        }
    }

    //find diffrence between current and last update signature and if the diff is greather than 59 mins
    //update the virus signature
    public function checkVirusSignatureVersion()
    {
        if($this->virusSignatureVersionFileExists() == false)
        {
            $this->updateVirusCheckFile();
            return true;
        }
        else {
            $currenttime = date("YmdHis");
            $current_time = new DateTime($currenttime);
            $lastcheck = $this->getLastVersionCheck();
            $lastcheck_time = new DateTime($lastcheck);
            $interval = $lastcheck_time->diff($current_time);
            //check if the time difference is more than 59 mins
            if ($interval->y > 1 || $interval->m > 1 || $interval->d > 1 || $interval->h > 1 || $interval->i >= 59) {
                return true;
            } else {
                return false;
            }
        }
    }

    //once the virus signature is updated, update lastversioncheck file with the latest date and time
    public function updateVirusCheckFile()
    {
        $lastcheck = '';
        $filePath = OSE_FWDATA . ODS . "tmp" . ODS . "LastVersionCheck.php";
        $currenttime = date("YmdHis");
        $content = "<?php\n".'$lastcheck = '.$currenttime.";";
        file_put_contents($filePath, $content);
        require_once($filePath);
        return $lastcheck;
    }

    public function bgscan()
    {
        $webkey = $this->getWebKey();
        if (!empty($webkey)) {
            $url = VSSCAN_API_SERVER."vsscan/bgscan?webkey=" . $webkey;
            $this->sendRequestVS($url);
            $result = array('result' => true, 'msg' => 'virus scan commenced at backend');
        } else {
            $result = array('result' => false, 'msg' => 'No webkey');
        }
        return $result;
    }

    public function ignoreAkeebaFiles($path)
    {
        $pathinfo = pathinfo($path);
        if(!isset($pathinfo['extension']) || !isset($pathinfo['dirname']))
        {
            return true;
        }
        $extension = $pathinfo['extension'];
        $dirname = $pathinfo['dirname'];
        if((strpos($dirname,"com_akeeba")!==false) ||(strpos($dirname,"akeeba_backup")!==false))
        {
            if($extension == "log" || (strpos($extension,"id") !== false) || (strpos($extension,"log") !== false))
            {
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    public function writeChunkSizeValue($value)
    {
        $this->deleteChunksizeFile();
        $contenttoput = "<?php\n" . '$chunksizevalue = ' . var_export($value, true) . ";";
        $result = file_put_contents(OSE_CHUNKSIZE_FILE, $contenttoput);
        return $result;
    }

    public function getChunkSize()
    {
        $chunksizevalue = 50;
        if(file_exists(OSE_CHUNKSIZE_FILE))
        {
            require (OSE_CHUNKSIZE_FILE);
        }
        return $chunksizevalue;
    }

    public function deleteChunksizeFile()
    {
        if(file_exists(OSE_CHUNKSIZE_FILE))
        {
            unlink(OSE_CHUNKSIZE_FILE);
        }

    }

}
?>