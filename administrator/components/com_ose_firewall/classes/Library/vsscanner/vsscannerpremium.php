<?php
/**
 * Created by PhpStorm.
 * User: Rohit
 * Date: 5/04/2016
 * Time: 4:11 PM
 */

if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC')) {
    die('Direct Access Not Allowed');
}
oseFirewall::callLibClass('vsscanner', 'vsscanner');

class vsscannerpremium extends virusScanner{

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
                    break;
                }
            }
        }
        usleep(100);
        return $virus_found;
    }
    protected function getCurrentJoomlaVersion()
    {
        static $current = null;

        if (is_null($current)) {
            $jversion = new JVersion();
            $current = $jversion->getShortVersion();
            if (strpos($current, ' ') !== false) {
                $current = reset(explode(' ', $current));
            }
        }
        return $current;
    }

    public function getNumInfectedFiles ()
    {
        $db = oseFirewall::getDBO ();
        $query = "SELECT COUNT(`file_id`) AS `count` FROM `".$this->malwaretable."`";
        $db->setQuery($query);
        $result = (object)($db->loadResult());
        $db -> closeDBO();
        if (is_null($result->count)) {
            $result->count = 0;
        }
        return $result->count;
    }

    private function logClamVirus ($msg)
    {
        $detectedMal = $this->getClamMessage($msg);
        if (empty($detectedMal))
        {
            $db = oseFirewall::getDBO ();
            $varValues = array(
                'patterns' => $msg,
                'type_id' => 9,
                'confidence' => 100,
            );
            $id = $db->addData ('insert', '#__osefirewall_vspatterns', '', '', $varValues);
            return $id;
        }
        else
        {
//            return $varObject->id;
        }
    }

    private function getClamMessage ($msg) {
        $db = oseFirewall::getDBO ();
        $query = "SELECT COUNT(`id`) as `count` FROM `#__osefirewall_vspatterns` ".
            " WHERE `patterns` = ".$db->QuoteValue($msg);
        $db->setQuery($query);
        $result = (object)($db->loadResult());
        $db -> closeDBO();
        return $result->count;
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
            $return['totalvs'] = $this->getNumInfectedFiles();
            $return['totalscan'] = $process * $this->chunksize + $lastfileno;
            $return['timeUsed'] = $timeUsed . 's';
            $return['vsfilelist'] = $this->getVsFileNames();
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
            $return['totalvs'] = $this->getNumInfectedFiles();
            $return['totalscan'] = ($process + 1) * $this->chunksize + $lastfileno;
            $return['timeUsed'] = $timeUsed . 's';
            $return['vsfilelist'] = $this->getVsFileNames();
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
        } else {
            $return['completed'] = round($process / $size, 3) * 100;;
            $return['summary'] = ($return['completed']) . '% ' . oLang::_get('COMPLETED');
            $return['last_file'] = $last_file;
            $return['status'] = 'Continue';
            $return['cont'] = true;
            $return['cpuload'] = $this->getCPULoad();;
            $return['memory'] = $this->getMemoryUsed();
            $return['totalvs'] = $this->getNumInfectedFiles();
            $return['totalscan'] = ($process + 1) * $this->chunksize;
            $return['timeUsed'] = $timeUsed . 's';
            $return['process'] = $process + 1;
            $return['size'] = $size;
            $this->clearFileSingle($process);
            return $return;
        }
    }

    protected function getVsFileNames ()
    {
        $db = oseFirewall::getDBO ();
        $query = "SELECT filename FROM `".$this->malwaretable."` JOIN `".$this->filestable."` ON file_id = id";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        $db -> closeDBO();
        foreach ($result as $key => $value) {
            $filenames[] = $value->filename;
        }
        return $filenames;
    }

    protected function returnAjaxMsg ($last_file=null, $type) {
        if (count($this->vsInfo->fileset) == 0)
        {
            $this->clearFile ($type);
            $return = $this->returnCompleteMsg($last_file, $type);
            if ( $return['overall'] >= 100 ) {
                $return['vsfilelist'] = $this->getVsFileNames();
                $this->saveDBLastScanResult($return);
            }
            return $return;
        }
        else
        {
            $return = array ();
            $timeUsed = $this->timeDifference($_SESSION['start_time'], time());
            $completed = $this->vsInfo->completed;
            $left = count($this->vsInfo->fileset);
            $total = $this->vsInfo->completed + $left;
            $truetotal = $total * 8;
            $lefttotal = $this->getOverallLeft();
            $progress = ($completed/$total);
            $return['completed'] = round($progress, 3)*100;
            $return['overall'] = round( ($truetotal - $lefttotal) / $truetotal, 3)*100;
            $return['summary'] = ($return['completed']). '% ' .oLang::_get('COMPLETED');
            $return['progress'] = "<b>Progress: ".($left)." files remaining.</b>. Time Used: ".$timeUsed." seconds<br/><br/>";
            $return['last_file'] = $last_file;
            $return['cont'] = ($left > 0 )?true:false;
            $return['cpuload'] = $this->getCPULoad();;
            $return['memory'] = $this->getMemoryUsed();
            $return['type'] = $type;
            $return['totalvs'] =  $this->getNumInfectedFiles();
            $return['totalscan'] = round(($truetotal - $lefttotal) / 8);
            return $return;
        }
    }

    protected function getWebsiteStatus () {
		$infected = $this->getNumInfectedFiles();
		return ($infected>0)?1:0;
    }

    protected function sendEmail($virusFound)
    {
        if ($_SERVER['HTTP_HOST'] !='localhost') {
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

    private function getEmailContent($virusFound)
    {
        $time = $this->getCurrentTime();
        $currentDomain = $_SERVER['HTTP_HOST'];
        $domain = preg_replace('/[:\/;*<>|?]/', '', $currentDomain);
        if ($virusFound == true) {
            $status = "[Warning] Please review files in the scanning report.";
        } else {
            $status = "[Secured] Your files are all clean.";
        }
        $message = "<b>Virus scanning</b> was completed with the following status: <br/><br/>";
        $message .= '<table border="1" cellpadding="10" cellspacing="1">
					<thead>	<tr><th>Domain</th><th>Status</th><th>Completion</th></tr></thead>
					<tbody><tr><td>' . $domain . '</td><td>' . $status . '</td><td>' . $time . ' (AEST)</td></tr></tbody></table>';
        $message .= "<br/><br/>";
        $message .= "Centrora Security protects all your websites from malware and other malicious code.<br/><br/>";
        $message .= "Kind regards<br/>";
        return $message;
    }


    //store the email address of the user in the secConfig file
    public function storeUserEmail($email)
    {
        $configtable = '#__ose_secConfig';
        //validate the email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $result['status'] = 0;
            $result['info'] = "Invalid Email Address";
        }
        else {
            $db = oseFirewall::getDBO ();
            $varValues = array(
                'key' => "contact",
                'value' => (string)$email,
                'type' => "vsscan"
            );
            $id = $db->addData ('insert', $configtable, '', '', $varValues);
            if($id !== null) {
                $result['status'] = 1;
                $result['info'] = "The email address has been saved successfully";
                return $result;
            }
            else {
                $result['status'] = 0;
                $result['info'] = "Problem is database query";
                return $result;
            }
        }
    }

    //retrieve the email address from the databases
    public function getUserEmail()
    {
        $type= "vsscan";
        $result = oseFirewall::getConfiguration($type);
        return ($result['data']['contact']);
    }

}