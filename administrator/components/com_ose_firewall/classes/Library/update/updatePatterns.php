<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 27/2/17
 * Time: 8:57 AM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC')) {
    die('Direct Access Not Allowed');
}
define("TEMP_VIRUS_SIGNATURE_FILE",OSE_FWDATA.ODS.'tmp'.ODS."avs.data");
define("TEMP_FIREWALL_PATTERNS_FILE",OSE_FWDATA.ODS.'tmp'.ODS."ath.data");
class updatePattern
{
    private $db = null;
    private $version_check = array('ath','avs','md5_hash'); ///add new paramters that needs to be checked

    public function __construct(){
        $this->db = oseFirewall::getDBO();
        oseFirewall::loadFiles();
    }


    public function updateLocalFileTimeStamp($type , $timestamp)
    {
        if(in_array($type,$this->version_check)) {
            if (file_exists(OSE_LAST_VERSION_CHECK)) {
                //get file contents and update only specific one
                $old_content = $this->getLastVersionCheckComplete();
                $newContent = $old_content;
                unset($newContent[$type]);
                $newContent[$type] = $timestamp;
                $write_Result = $this->writeLocalFileTimeStamp($newContent);
                if ($write_Result) {
                    return oseFirewallBase::prepareSuccessMessage("Last version check file has been updated ");
                } else {
                    return oseFirewallBase::prepareErrorMessage("There was some problem in updating the last version check file ");
                }
            } else {
                //get all the keys that needs to be set as false
                $diff = array_diff($this->version_check, array($type));
                //set all the other elements  as false
                $filled = array_fill_keys($diff, false);
                $content = array_merge($filled, array($type => $timestamp));
                $write_Result = $this->writeLocalFileTimeStamp($content);
                if ($write_Result) {
                    return oseFirewallBase::prepareSuccessMessage("Last version check file has been updated ");
                } else {
                    return oseFirewallBase::prepareErrorMessage("There was some problem in updating the last version cehck file ");
                }
            }
        }else{
            return oseFirewallBase::prepareErrorMessage("Please enter an valid key, $type is an invalid key");
        }
    }

    public function writeLocalFileTimeStamp($content)
    {
        $filecontent = "<?php\n" . '$lastUpdate = ' . var_export($content, true) . ";";
        $result = file_put_contents(OSE_LAST_VERSION_CHECK, $filecontent);
        return ($result == false) ? false : true;
    }


    public function getLastVersionCheckTime($type)
    {
        $lastUpdate = array();
        if(file_exists(OSE_LAST_VERSION_CHECK))
        {
            require(OSE_LAST_VERSION_CHECK);
            return $lastUpdate[$type];
        }else {
            return false;
        }
    }

    public function getLastVersionCheckComplete()
    {
        $lastUpdate = array();
        if(file_exists(OSE_LAST_VERSION_CHECK))
        {
            require(OSE_LAST_VERSION_CHECK);
            return $lastUpdate;
        }else {
            return false;
        }
    }


    public function checkPatternVersionLastCheck($type)
    {
        $last_versionCheck = $this->getLastVersionCheckTime($type);
        $dbExist = $this->checkPatternsExists($type);
        if($last_versionCheck == false || $dbExist == false)
        {
            return oseFirewallBase::prepareErrorMessage("The $type is outdated");
        }else {
            $now =  new DateTime();
            $interval = $now->diff($last_versionCheck);
//            if(property_exists($interval,"y") && property_exists($interval,"m") && property_exists($interval,"d") && property_exists($interval,"h"))
//            {
                if($type == "ath")
                {
                    if($interval->y > 1 || $interval->m > 1 || $interval->d > 1 || $interval->h >FIREWALL_VERSION_CHECK_THRESHOLD )
                    {
                        return oseFirewallBase::prepareErrorMessage("The $type is outdated");
                    }else {
                        return oseFirewallBase::prepareSuccessMessage("The $type is Up-to Date");
                    }
                }else
                {
                    if($interval->y > 1 || $interval->m > 1 || $interval->d > 1 || $interval->h>1 || $interval->i >VIRUS_VERSION_CHECK_THRESHOLD)
                    {
                        return oseFirewallBase::prepareErrorMessage("The $type is outdated");
                    }else {
                        return oseFirewallBase::prepareSuccessMessage("The $type is Up-to Date");
                    }
                }
//            }else{
//                return oseFirewallBase::prepareErrorMessage("cannot calculatre the time diffrence ");
//            }
        }
    }



   public function checkPatternVersion($type)
   {
        $lastcheck =  $this->checkPatternVersionLastCheck($type);
        if(oseFirewallBase::isFirewallV7Active() && oseFirewallBase::anyActiveRules()) {
           if (file_exists(OSE_ADVANCEDRULES_RULESFILE)) {
               $rules = array();
               require(OSE_ADVANCEDRULES_RULESFILE);
               if (empty($rules)) {
                   return oseFirewallBase::prepareErrorMessage("There was some problem in Updating Firewall Advanced patterns" . CONTACT_SUPPORT);
               }
           } else {
               return oseFirewallBase::prepareErrorMessage("There was some problem in Updating Firewall Advanced patterns " . CONTACT_SUPPORT);
           }
        }
       if($lastcheck['status'] == 0)
       {
           return $lastcheck;
       }else{
          $server_ver_check =  $this->compareVersions($type);
          return $server_ver_check;
       }
   }



    public function checkPatternsExists($type)
    {
        if($type == "ath")
        {
            $table = "#__osefirewall_advancerules";
        }elseif($type == "avs")
        {
            $table = "#__osefirewall_vspatterns";
        }else{
            return false;
        }
        $count = $this->db->getTotalNumber('id',$table);
        if($count >0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function updatePatterns($type)
    {
       if($type == "ath")
       {
          $result =  $this->updateFirewallPatterns();
       }else{
        $result = $this->updateVirusPatterns();
        }
        return $result;
    }


    public function updateFirewallPatterns()
    {
        $type = "ath";
        $download_result =  $this->downloadFirewallAdvPatterns();
        if($download_result['status'] == 0)
        {
            //ERROR IN DOWNLOADING THE FILE
            return $download_result;
        }else {
            $this->installDatabaseTables($type);
            $count =  $this->getCountofRecords($type);
            if($count == 0)
            {
                return oseFirewallBase::prepareErrorMessage("There was some problem in installing the Firewall patterns");
            }else{
                oseFirewall::callLibClass('downloader', 'oseDownloader');
                $downloader = new oseDownloader('avs');
                require(TEMP_FIREWALL_PATTERNS_FILE);
                if(isset($data['version']))
                {
                    $downloader->updateVersion($type,$data['version']);
                }
                $datetime1 = new DateTime();
                $this->updateLocalFileTimeStamp($type,$datetime1);
                oseFile::delete(TEMP_FIREWALL_PATTERNS_FILE);
                return oseFirewallBase::prepareSuccessMessage("The Firewall Advanced patterns Has been Updated ");
            }
        }
    }



    public function updateVirusPatterns()
    {
        $type = "avs";
        $download_result =  $this->downloadVirusPattern();
        if($download_result['status'] == 0)
        {
            //ERROR IN DOWNLOADING THE FILE
            return $download_result;
        }else {
           $this->installDatabaseTables($type);
           $count =  $this->getCountofRecords($type);
           if($count == 0)
           {
               return oseFirewallBase::prepareErrorMessage("There was some problem in installing the patterns table");
           }else{
               oseFirewall::callLibClass('downloader', 'oseDownloader');
               $downloader = new oseDownloader('avs');
               require(TEMP_VIRUS_SIGNATURE_FILE);
               if(isset($data['version']))
               {
                   $downloader->updateVersion($type,$data['version']);
               }
               $datetime1 = new DateTime();
               $this->updateLocalFileTimeStamp('avs',$datetime1);
               oseFile::delete(TEMP_VIRUS_SIGNATURE_FILE);
               return oseFirewallBase::prepareSuccessMessage("The Virus Scanner Patterns Has been Updated ");
            }
        }
    }


//    public function truncateTables($type)
//    {
//        if($type == "avs")
//        {
//            $table = "#__osefirewall_vspatterns";
//        }else {
//            $table = "#__osefirewall_advancerules";
//        }
//        $this->db->truncateTable($table);
//    }

    public function downloadVirusPattern()
    {
        oseFirewall::callLibClass('downloader', 'oseDownloader');
        $downloader = new oseDownloader('avs');
        $type = "avs";
        if(file_exists(TEMP_VIRUS_SIGNATURE_FILE))
        {
            oseFile::delete(TEMP_VIRUS_SIGNATURE_FILE);
        }
        $result = $downloader->downloadPatternsFiles($type,VIRUS_PATTERN_DOWNLOAD_URL);
        return $result;
    }

    public function downloadFirewallAdvPatterns()
    {
        oseFirewall::callLibClass('downloader', 'oseDownloader');
        $downloader = new oseDownloader('ath');
        $type = "ath";
        if(file_exists(TEMP_FIREWALL_PATTERNS_FILE))
        {
            oseFile::delete(TEMP_FIREWALL_PATTERNS_FILE);
        }
        //check count //TODO
        $count  = $this->getCountOfAdvRulesDb();
        //if no db records exist the download the file with query = insert
        if($count == 0)
        {
            $url = FIREWALL_PATTERN_DOWNLOAD_URL;
        }else{
            //download sql commands with update commands
            $url = FIREWALL_PATTERN_UPDATE_DOWNLOAD_URL;
        }
        $result = $downloader->downloadPatternsFiles($type,$url);
        return $result;
    }



    public function getCountOfAdvRulesDb()
    {
        $query = "SELECT COUNT(id) as `count` FROM ". $this->db->QuoteTable("#__osefirewall_advancerules");
        $this->db->setQuery($query);
        $result = $this->db->loadResult();
        if (isset($result['count'])) {
            return $result['count'];
        }else{
            return 0;
        }
    }

    public function installDatabaseTables($type)
    {
        if($type == "avs")
        {
            $file = TEMP_VIRUS_SIGNATURE_FILE;
        }else{
            $file = TEMP_FIREWALL_PATTERNS_FILE;
        }
        if (!empty($file))
        {
            oseFirewall::loadInstaller();
            $installer = new oseFirewallInstaller ();
            $result = $installer -> insertAdvRuleset ($file, $type);
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function getPatternsFileContents($type)
    {
        $data = array();
        if($type == "avs")
        {
            require(TEMP_VIRUS_SIGNATURE_FILE);
            return $data['pattern'];
        }else{
            //require();
            return false;
        }
    }

    public function getCountofRecords($type)
    {
        if($type == "avs")
        {
            $table = "#__osefirewall_vspatterns";
           $result =  $this->db->getTotalNumber('id',$table);
        }else{
            $table = "#__osefirewall_advancerules";
            $result = $this->db->getTotalNumber('id',$table);
        }
        return $result;
    }


    public function getServerPatternVersion($type)
    {
        $url = null;
        if($type == "avs")
        {
            $url = GET_VIRUS_PATTERN_VERSION_URL;
        }else if($type == "ath")
        {
            $url = GET_FIREWALL_PATTERN_VERSION_URL;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_USERAGENT => 'Centrora Security Plugin Request Agent',
            CURLOPT_SSL_VERIFYPEER => false
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        $result = json_decode($resp);
        if(property_exists($result,"version"))
        {
            return $result->version;
        }else{
            return false;
        }

    }

    public function getLocalVersion($type)
    {
       if($type == "ath" || $type == "avs")
       {
           $query = "SELECT * FROM `#__osefirewall_versions` WHERE `type` = ". $this->db->QuoteValue(substr($type, 0, 4));
           $this->db->setQuery($query);
           $result = $this->db->loadObject();
           if(!empty($result) && property_exists($result,"number"))
           {
               return $result->number;
           }else{
               return false;
           }
       }else{
           return false;
       }
    }

    //conpare the loca lpattern version with the server version
    public function compareVersions($type)
    {
        $local_version =  $this->getLocalVersion($type);
        $dbExist = $this->checkPatternsExists($type);
       if($local_version == false || $dbExist ==false)
       {
           return oseFirewallBase::prepareErrorMessage("The Local -  Pattern version is outdated");
       }
        else {
            $server_version = $this->getServerPatternVersion($type);
            if($server_version == false)
            {
                return oseFirewallBase::prepareCustomMessage(2,"There was some problem in getting the latest version from the server ");
            }
            $local = new DateTime($local_version);
            $server = new DateTime($server_version);
            $interval = $local->diff($server);
//            if((property_exists($interval,"y") && property_exists($interval,"m") && property_exists($interval,"d") && property_exists($interval,"h")) ==false)
//            {
//                return oseFirewallBase::prepareErrorMessage("There was some problem in calculating the time diffrence for the server version ");
//            }
            if($type == "ath")
            {
                if ($interval->y > 1 || $interval->m > 1 || $interval->d > 1 || $interval->h > 1) {
                    return oseFirewallBase::prepareErrorMessage("The Firewall Pattern version is outdated");
                } else {
                    $datetime1 = new DateTime();
                    $this->updateLocalFileTimeStamp($type,$datetime1);
                    return oseFirewallBase::prepareSuccessMessage("No need to update the Firewall patterns");
                }
            }else if($type == "avs")
            {
                if ($interval->y > 1 || $interval->m > 1 || $interval->d > 1) {
                    return oseFirewallBase::prepareErrorMessage("The Virus Pattern version is outdated");
                } else {
                    $datetime1 = new DateTime();
                    $this->updateLocalFileTimeStamp($type,$datetime1);
                    return oseFirewallBase::prepareSuccessMessage("No need to update the Virus patterns");
                }
            }
       }
    }


    public function installVirusPatterns($type)
    {
        if(!file_exists(TEMP_VIRUS_SIGNATURE_FILE))
        {
            return oseFirewallBase::prepareErrorMessage("The Virus Pattern File does not exists ,The download was not complete, Please refresh the page and try again");
        }
        $this->installDatabaseTables($type);
        $count =  $this->getCountofRecords($type);
        if($count == 0)
        {
            return oseFirewallBase::prepareErrorMessage("There was some problem in installing the Virus patterns table");
        }else{
            oseFirewall::callLibClass('downloader', 'oseDownloader');
            $downloader = new oseDownloader('avs');
            require(TEMP_VIRUS_SIGNATURE_FILE);
            if(isset($data['version']))
            {
                $downloader->updateVersion($type,$data['version']);
            }
            $datetime1 = new DateTime();
            $this->updateLocalFileTimeStamp('avs',$datetime1);
            oseFile::delete(TEMP_VIRUS_SIGNATURE_FILE);
            return oseFirewallBase::prepareSuccessMessage("The Virus Scanner Patterns Has been Updated ");
        }
    }


    public function installFirewallpatterns($type)
    {
        $data = false;
        if(!file_exists(TEMP_FIREWALL_PATTERNS_FILE))
        {
            return oseFirewallBase::prepareErrorMessage("The Firewall Pattern File does not exists ,The download was not complete, Please refresh the page and try again");
        }
        $this->installDatabaseTables($type);
        $count =  $this->getCountofRecords($type);
        if($count == 0)
        {
            return oseFirewallBase::prepareErrorMessage("There was some problem in installing the Firewall patterns");
        }else{
            oseFirewall::callLibClass('downloader', 'oseDownloader');
            $downloader = new oseDownloader('avs');
            require(TEMP_FIREWALL_PATTERNS_FILE);
            if(isset($data['version']))
            {
                $downloader->updateVersion($type,$data['version']);
            }
            $datetime1 = new DateTime();
            $this->updateLocalFileTimeStamp($type,$datetime1);
            oseFile::delete(TEMP_FIREWALL_PATTERNS_FILE);
            if(oseFirewallBase::isFirewallV7Active())
            {
                oseFirewallBase::callLibClass('fwscannerv7','fwscannerv7');
                $fs = new oseFirewallScannerV7();
                $fs->updateLocalFile();
                if (file_exists(OSE_ADVANCEDRULES_RULESFILE)) {
                    $rules = array();
                    require(OSE_ADVANCEDRULES_RULESFILE);
                    if (empty($rules)){
                        return oseFirewallBase::prepareErrorMessage("There was some problem in Updating Firewall Advanced patterns ".CONTACT_SUPPORT);
                    }
                }else{
                    return oseFirewallBase::prepareErrorMessage("There was some problem in Updating Firewall Advanced patterns ".CONTACT_SUPPORT);
                }
            }
            return oseFirewallBase::prepareSuccessMessage("The Firewall Advanced patterns Has been Updated ");
        }
    }

    public function getDatefromVirusCheckFile($type = false)
    {
        $lastUpdate = array();
        if (file_exists(OSE_LAST_VERSION_CHECK)) {
            require(OSE_LAST_VERSION_CHECK);
            if($type == "ath")
            {
                $virus_check  = $lastUpdate['ath'];

            }else{
                $virus_check  = $lastUpdate['avs'];
            }
            if($virus_check == false || empty($virus_check))
            {
                return oseFirewallBase::prepareErrorMessage("The virus pattern check is empty in the local file ");
            }else {
               $date =  $virus_check->format('d-m-Y H:i:s');
               return oseFirewallBase::prepareSuccessMessage($date);
            }
        }else {
            return oseFirewallBase::prepareErrorMessage("There version check file does not exists ");
        }
    }

    public function scheduledUpdate_download($type)
    {
        if($type == "avs")
        {
            $download_result =  $this->downloadVirusPattern();
        }else {
            $download_result = $this->downloadFirewallAdvPatterns();
        }
        return $download_result;
    }

    public function scheduledUpdate_install($type)
    {
        if($type == "avs")
        {
            $update_result =  $this->installVirusPatterns($type);
        }else {
            $update_result = $this->installFirewallpatterns($type);
        }
        return $update_result;
    }


    public function scheduledUpdatePatterns($type)
    {
        $check_version_result = $this->checkPatternVersion($type);
        if($check_version_result['status'] == 0)
        {
            $download_results= $this->scheduledUpdate_download($type);
            if($download_results['status']==1)
            {
                $install_results = $this->scheduledUpdate_install($type);
                if($install_results == 1)
                {
                    //success
                    return oseFirewallBase::prepareSuccessMessage("$type pastterns has been updated");
                }else{
                    //error in installation
                    return $install_results;
                }
            }else{
                //error in downloading
                return $download_results;
            }

        }elseif($check_version_result['status'] == 1){
            //upto date
            return oseFirewallBase::prepareSuccessMessage("The Patterns are already updated");
        }else{
            //error in checking version
            return $check_version_result;
        }

    }









}