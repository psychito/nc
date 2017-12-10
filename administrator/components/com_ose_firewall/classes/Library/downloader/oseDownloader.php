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
class oseDownloader
{
    private $type = null;
    private $key = null;
    private $url = null;
    private $live_url = null;

    public function __construct($type, $key = null, $version = null)
    {
        $this->type = $type;
        $this->key = $key;
        $this->version = $version;
        $this->live_url = "https://www.centrora.com/?";
        $this->url = $this->live_url . "download=1&downloadKey=" . $this->key;
        oseFirewall::loadFiles();
    }

    private function setPHPSetting()
    {
        if (function_exists('ini_set')) {
            ini_set("allow_url_fopen", 1);
        }
        if (function_exists('ini_get')) {
            if (ini_get('allow_url_fopen') == 0) {
                //oseAjax::aJaxReturn(false, 'ERROR', 'The PHP function \'allow_url_fopen\' is turned off, please turn it on to allow the task to continue.', FALSE);
            }
        }
    }


    public function downloadPatternsFiles($type, $url)
    {
        $this->setPHPSetting();
        if ($type == "ath") {
            $file = TEMP_FIREWALL_PATTERNS_FILE;
        } else {
            $file = TEMP_VIRUS_SIGNATURE_FILE;
        }
        $url_fopen = ini_get('allow_url_fopen');
        if ($url_fopen == true) {
            $target1 = $this->downloadThroughFopen($url, $file);
            if ($target1 == false || isset($target1['status']) && $target1['status'] == 0) {
                $target = $this->downloadThroughCURL($url, $file);
            } else {
                $target = $target1;
            }
        } else {
            $target = $this->downloadThroughCURL($url, $file);
        }
        $contents = file_get_contents($file);
        if (file_exists($file) && !empty($contents)) {
            return oseFirewallBase::prepareSuccessMessage("The file has been downloaded");
        } else {
            //file  does not exists
            if ($type == "avs") {
                $msg = "There was some problem in downloading the Virus Pattern file" . CENTRORA_SUPOORT;
            } else {
                $msg = "There was some problem in downloading the Firewall Signature File" . CENTRORA_SUPOORT;
            }
            if (isset($target['status']) && $target['status'] == 0 && isset($target['info'])) {
                //incorrect format from the server
                return $target;
            } else {
                return oseFirewallBase::prepareErrorMessage($msg);
            }
        }
    }


    private function downloadThroughFopen($url, $target = null)
    {
        ini_set('user_agent', 'Centrora Security Plugin Request Agent;');
        $inputHandle = fopen($url, "r");
        if (!$inputHandle) {
            return false;
        }
        $meta_data = stream_get_meta_data($inputHandle);
        if (!empty($meta_data) && isset($meta_data['wrapper_data']) && isset($meta_data['wrapper_data']['headers']) && empty($meta_data['wrapper_data']['headers'])) {
            return false;
        }
        // Initialise contents buffer
        $contents = null;
        while (!feof($inputHandle)) {
            $contents .= fread($inputHandle, 8192);
            if ($contents === false) {
                return false;
            }
        }
        // Write buffer to file
        if (!empty($contents) && $url == VIRUS_PATTERN_DOWNLOAD_URL || $url == FIREWALL_PATTERN_DOWNLOAD_URL || $url == FIREWALL_PATTERN_UPDATE_DOWNLOAD_URL) //TODO : MISSING CONDITION FOR $url == ""
        {
            $result = $this->writePatternsFile($contents, $target);
            fclose($inputHandle);
            return $result;
        } else {
            $handle = is_int(file_put_contents($target, $contents)) ? true : false;
            if ($handle) {
                // Close file pointer resource
                fclose($inputHandle);
            }
            return $target;
        }

    }

    private function writePatternsFile($content, $target)
    {
        //PREPARE CONTENTS
//        $decoded = json_decode($content);
        $base_decoded = base64_decode($content);
        $decoded = json_decode($base_decoded);
        if (property_exists($decoded, "version") && property_exists($decoded, "pattern")) {
            $content_formatted['version'] = $decoded->version;
            $contents1 = ($decoded->pattern);
            $content_formatted['pattern'] = $contents1;
            //WRiTE FILE
            $filecontent = "<?php\n" . '$data = ' . var_export($content_formatted, true) . ";";
            if (file_exists($target)) {
                unlink($target);
            }
            $result = file_put_contents($target, $filecontent);
            return ($result == false) ? false : true;
        } else {
            return oseFirewallBase::prepareErrorMessage("The downloaded file is not in correct format <br/> The downloaded file content is :<br/>" . $content);
        }
    }

    private function downloadThroughCURL($url, $target = false)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Centrora Security Downloader Agent');

        $contents = curl_exec($curl);
        curl_close($curl);
        if (!empty($contents) && $url == VIRUS_PATTERN_DOWNLOAD_URL || $url == FIREWALL_PATTERN_DOWNLOAD_URL || $url == FIREWALL_PATTERN_UPDATE_DOWNLOAD_URL) {
            $return = $this->writePatternsFile($contents, $target);;
        } else {
            $return = (file_put_contents($target, $contents) != false) ? $target : false;
        }
        return $return;
    }

    public function updateVersion($type, $version)
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT * FROM `#__osefirewall_versions` WHERE `type` = " . $db->QuoteValue(substr($type, 0, 4));
        $db->setQuery($query);
        $result = $db->loadObject();
        if (empty($result)) {
            $varValues = array(
                'version_id' => 'NULL',
                'number' => $version,
                'type' => $type
            );
            $id = $db->addData('insert', '#__osefirewall_versions', null, null, $varValues);
        } else {
            $varValues = array(
                'number' => $version,
                'type' => $type
            );
            $id = $db->addData('update', '#__osefirewall_versions', 'version_id', $result->version_id, $varValues);
        }
        $db->closeDBO();
        return $id;
    }

    private function mergeString($scanURL, $content)
    {
        $url = "";
        foreach ($content as $key => $value) {
            $tmp[] = @$key . '=' . urlencode(@$value);
        }
        $workstring = implode("&", $tmp);
        $url .= $scanURL . "&" . $workstring;
        return $url;
    }

    public function sendRequest($content)
    {
        $url = $this->mergeString($this->live_url, $content);
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Centrora Security Plugin Request Agent'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }

    public function getAPIkey()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'privateAPIKey'";
        $db->setQuery($query);
        $result = $db->loadResult();
        $db->closeDBO();
        return $result['value'];
    }

    public function getRemoteAPIKey()
    {
        $content = $this->getRemoteConnectionContent('checkSubstatus');
        $response = $this->sendRequest($content);
        return $response;
    }

    private function getRemoteConnectionContent($task)
    {
        oseFirewall::loadUsers();
        $users = new oseUsers('firewall');
        $content = array();
        $content['url'] = oseFirewall::getSiteURL();
        $content['remoteChecking'] = true;
        $content['task'] = $task;
        $content['admin_email'] = $users->getUserEmail();
        $content['option'] = $_POST['option'];
        if (class_exists('SConfig')) {
            $content['cms'] = 'st';
        } else if (class_exists('JConfig')) {
            $content['cms'] = 'jl';
        } else if (defined('WPLANG')) {
            $content['cms'] = 'wp';
        }
        return $content;
    }

    public function getEmailConfig()
    {
        $db = oseFirewall::getDBO();
        $query = "SELECT `value` FROM `#__ose_secConfig` WHERE `key` = 'receiveEmail'";
        $db->setQuery($query);
        $result = $db->loadResult();
        $db->closeDBO();
        if ($result['value'] == 0 && $result['value'] != NULL) {
            return 0;
        } else {
            return 1;
        }
    }

    public function checkScheduleScanning()
    {
        $content = $this->getRemoteConnectionContent('scheduleScanning');
        $response = $this->sendRequest($content);
        return $response;
    }

    protected function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    private function downloadContentsThroughFOpen($url)
    {
        ini_set("display_errors", "on");
        ini_set('user_agent', 'Centrora Security Plugin Request Agent;');
        $inputHandle = fopen($url, "r");
        if (!$inputHandle) {
            return false;
        }
        $meta_data = stream_get_meta_data($inputHandle);
//        echo "downloaded meta data is : ";
//        print_r($meta_data);exit;
        if (!empty($meta_data) && isset($meta_data['wrapper_data']) && isset($meta_data['wrapper_data']['headers']) && empty($meta_data['wrapper_data']['headers'])) {
            return false;
        }
        // Initialise contents buffer
        $contents = null;
        while (!feof($inputHandle)) {
            $contents .= fread($inputHandle, 8192);
            if ($contents === false) {
                return false;
            }
        }
        return $contents;
    }

    private function downloadContentsThroughCurl($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Centrora Security Downloader Agent');
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_BUFFERSIZE, 10485764);
        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }


    public function downloadCoreFiles($url,$cms,$version)
    {
        $destination = false;
        $target = $this->downloadContentsThroughCurl($url);
        if(empty($target))
        {
            return oseFirewallBase::prepareErrorMessage("There was some problem in downloading the core files ".CONTACT_SUPPORT);
        }
        if($cms == "wordpress")
        {
            $destination = OSE_FWDATA."/wpHashList/$version.zip";
        }else if($cms == "joomla")
        {
            $destination = OSE_FWDATA."/jHashList/$version.zip";
        }
        $file = fopen($destination, "w+");
        fputs($file, $target);
        fclose($file);
        if (file_exists($destination)) {
            return oseFirewallBase::prepareSuccessMessage($destination);
        } else {
            return oseFirewallBase::prepareErrorMessage("Core file cannot be download " . CONTACT_SUPPORT);
        }
}
}