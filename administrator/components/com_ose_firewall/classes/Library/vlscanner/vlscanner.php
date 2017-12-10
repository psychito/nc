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

class vulnerabilityScanner
{
    private $db = null;
    private $vlstable = '#__osefirewall_vlscanner';
    private $scanhisttablebl = '#__osefirewall_scanhist';
    private $vlscanProgress;

    public function __construct()
    {
        oseFirewall::loadLanguage();
        $this->db = oseFirewall::getDBO();
        oseFirewall::loadFiles();
    }

    public function runVulnerabilityScan($step)
    {
        switch ($step) {
            case 1:
                $result = $this->generateVulScanList();
                break;
            case 2:
                $result = $this->runVulScan();
                break;
            case 3:
                $result = $this->completeVulScan();
                break;
        }
        return $result;
    }

    private function generateVulScanList()
    {
        if (OSE_CMS == 'joomla') {
            $vulList['scanlist'] = array_merge($this->getVulListJL(), $this->getVulListExtJL());
        } elseif (OSE_CMS == 'wordpress') {
            $vulList['scanlist'] = array_merge($this->getVulListWP(), $this->getVulListPlugin(), $this->getVulListTheme());
        }

        $vulList['totalscan'] = count($vulList['scanlist']);
        $vulList['totalvls'] = 0;

        $this->saveVulScanList($this->utf8ize($vulList));

        $result['status'] =
            array("progress" => 0,
                "current_type" => "GenList",
                "current_scan" => oLang::_get('VL_GET_LIST'),
                "total_scan" => 0,
                "total_vls" => 0,
                "cont" => true,
                "step" => 2);

        $this->setScanProgress('', $result['status'], array());
        $this->vlscanProgress['scanDate'] = oseFirewall::getTime();
        $this->vlscanProgress['serverNow'] = $this->vlscanProgress['scanDate'];
        $this->clearpreviousScanDB();
        return $this->vlscanProgress;
    }

    private function saveVulScanList($scanList)
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "vulScanList.json";
        $fileContent = oseJSON::encode($scanList);
        $result = oseFile::write($filePath, $fileContent);
    }

    private function readVulScanList()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "vulScanList.json";
        $fileContent = oseFile::read($filePath);
        $result = oseJSON::decode($fileContent, true);
        return $result;
    }

    private function utf8ize($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    private function deleteVulScanList()
    {
        $filePath = OSE_FWDATA . ODS . "vsscanPath" . ODS . "vulScanList.json";
        $result = oseFile::delete($filePath);
        return $result;
    }

    private function getVulListJL()
    {
        $returnArray[] = array(
            'slug' => 'joomla' . JVERSION,
            'version' => JVERSION,
            'name' => 'Joomla',
            'type' => 'joomla',
            'localtype' =>'joomla',
            'urltype' => 'joomla');
        return $returnArray;
    }

    private function getVulListExtJL()
    {
        $returnArray = array();
        $defaultExtV3 = array('com_admin', 'com_ajax', 'com_banners', 'com_cache', 'com_categories', 'com_checkin', 'com_config', 'com_contact', 'com_content', 'com_contenthistory', 'com_cpanel', 'com_finder', 'com_installer', 'com_joomlaupdate', 'com_languages', 'com_login', 'com_media', 'com_menus', 'com_messages', 'com_modules', 'com_newsfeeds', 'com_plugins', 'com_postinstall', 'com_redirect', 'com_search', 'com_tags', 'com_templates', 'com_users', 'com_mailto', 'com_wrapper');
        $defaultExtV2 = array('com_admin', 'com_banners', 'com_cache', 'com_categories', 'com_checkin', 'com_config', 'com_contact', 'com_content', 'com_cpanel', 'com_finder', 'com_installer', 'com_joomlaupdate', 'com_languages', 'com_login', 'com_media', 'com_menus', 'com_messages', 'com_modules', 'com_newsfeeds', 'com_plugins', 'com_redirect', 'com_search', 'com_templates', 'com_users', 'com_weblinks', 'com_mailto', 'com_wrapper');
        $Extns = $this->getExtList();
        // clean and return plugins list
        foreach ($Extns as $key => $value) {
            $manifest_cache = oseJSON::decode($value->manifest_cache, true);
            if (!empty($value->element) && !empty($manifest_cache['version'])){
                if ((version_compare(JVERSION, '3.0.0') == 1 && in_array($value->element, $defaultExtV3)) || (version_compare(JVERSION, '2.0.0') == 1 && in_array($value->element, $defaultExtV2))) {
                    $returnArray[] = array('slug' => $value->element,
                        'version' => JVERSION,
                        'name' => $value->name,
                        'type' => 'plugin',
                        'localtype' => $value->type,
                        'urltype' => 'plugins'
                    );
                } else {
                    $returnArray[] = array('slug' => $value->element,
                        'version' => $manifest_cache['version'],
                        'name' => $value->name,
                        'type' => 'plugin',
                        'localtype' => $value->type,
                        'urltype' => 'plugins'
                    );
                }
            }

        }
        return $returnArray;
    }

    private function getVulListWP()
    {
        global $wp_version;
        //$wp_version = '4.2.1';// used for testing
        $wpVerSlug = str_replace('.', '', $wp_version);
        $returnArray[] = array(
            'slug' => $wpVerSlug,
            'version' => $wp_version,
            'name' => 'Wordpress',
            'type' => 'wordpress',
            'urltype' => 'wordpresses');
        return $returnArray;
    }

    private function getVulListPlugin()
    {
        $returnArray = array();
        $excludedPlugins = array('hello.php');
        $the_plugs = get_plugins();

        // clean and return plugins list
        foreach ($the_plugs as $key => $value) {
            $string = explode('/', $key);

            if (!in_array($string[0], $excludedPlugins)) {
                $string = str_replace('.php', '', $string);
                $returnArray[] = array('slug' => $string[0],
                    'version' => $value['Version'],
                    'name' => $value['Title'],
                    'type' => 'plugin',
                    'urltype' => 'plugins');
            }
        }
        return $returnArray;
    }

    private function getVulListTheme()
    {
        $returnArray = array();
        $themes = wp_get_themes();

        // clean and return Themes list
        foreach ($themes as $key => $value) {
            $themedata = (object)wp_get_theme($key);

            $returnArray[] = array('slug' => $key,
                'version' => $themedata->version,
                'name' => $themedata->title,
                'type' => 'theme',
                'urltype' => 'themes');
        }
        return $returnArray;
    }

    public function runVulScan()
    {
        $vulScanList = $this->readVulScanList();

        $scanresult = $this->scanEachVul($vulScanList);

        $this->addVLSDBData(date('Y-m-d H:i:s', time()), 4);

        $this->db->closeDBO();
        $this->db->closeDBOFinal();
        return $this->vlscanProgress;
    }

    private function scanEachVul($vulScanList)
    {
        $VulData = array();
        foreach ($vulScanList['scanlist'] as $key => $value) {
            if (OSE_CMS == 'wordpress') {
                $url = 'https://wpvulndb.com/api/v1/' . $value['urltype'] . '/' . $value['slug'];
            } elseif (OSE_CMS == 'joomla') {
                $url = API_SERVER."Exploitdb/checkVulnJL?type=" . $value['type']
                    . "&slug=" . $value['slug'] . "&slug_type=" . $value['localtype'] . "&check=lkjunKKJB788GH";
            }
            $jsonData = $this->getJsonData($url);
            $results = oseJSON::decode($jsonData, true);
            $numScanned = $vulScanList['totalscan'] - count($vulScanList['scanlist']);
            if (isset($results[$value['type']]['vulnerabilities'])) {
                //get vul of current version & greater only
                $CurrentVuls = $this->getCurrentVuls($value, $results[$value['type']]['vulnerabilities']);
                if (!empty($CurrentVuls)) {
                    $VulData = array(
                        'slug' => $value['slug'],
                        'name' => $value['name'],
                        'version' => $value['version'],
                        'vulnerabilities' => $CurrentVuls
                    );
                }
            }
            $type = ($value['type'] == 'joomla' || $value['type'] == 'wordpress') ? 'CMS' : $value['type'];
            $result['content'] = $this->addVLSDBData($VulData, $type);
            $vulScanList['vsfilelist'] = $VulData;
            $vulScanList['totalvls'] = count($result['content']['vulnerabilities']) + $vulScanList['totalvls'];
            $result['status'] =
                array("progress" => round($numScanned / $vulScanList['totalscan'], 3) * 100,
                    "current_type" => $type,
                    "current_scan" => $value['name'],
                    "total_scan" => $numScanned,
                    "total_vls" => $vulScanList['totalvls'],
                    "cont" => true,
                    "step" => $numScanned == $vulScanList['totalscan'] ? 3 : 2
                );
            unset($vulScanList['scanlist'][$key]);
            $this->saveVulScanList($vulScanList);

            $this->setScanProgress($type, $result['status'], $result['content']);
            return $this->vlscanProgress;
        }
        return $this->completeVulScan();
    }

    private function getCurrentVuls($plugin, $vul)
    {
        $newvul["stuff"] = array();
        foreach ($vul as $key => $value) {
            $vulVersion = $value['version'];

            if (OSE_CMS == 'wordpress') {
                //set version from title
                preg_match("/((\\d+(\\.\\d+)+)(?:.(?!(\\d+(\\.\\d+)+)))+$)/", $value['title'], $matches);
                $vul[$key]['version'] = $matches[2];
                $vulVersion = $matches[2];
            }
            $compareVer = version_compare($plugin['version'], $vulVersion);
            //remove vul from list if fixed in current version
            if (($compareVer == 1)) {
                unset($vul[$key]);
            }
        }
        foreach ($vul as $key => $value) {
            $newvul["stuff"][] = $vul[$key];
        }
        return $newvul["stuff"];
    }

    private function setScanProgress($type, $status, $content)
    {
        $this->vlscanProgress =
            array(
                'type' => $type,
                'status' => $status,
                'content' => $content
            );
    }

    private function getJsonData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $json_data = curl_exec($ch);
        curl_close($ch);
        return $json_data;
    }

    private function addVLSDBData($content, $vlsType)
    {
        $return = $content;
        switch ($vlsType) {
            case "CMS":
                $vlsType = 1;
                break;
            case "plugin":
                $vlsType = 2;
                break;
            case "theme":
                $vlsType = 3;
                break;
        }

        if ((is_array($content) && (isset($content['vulnerabilities']) || isset($content[0]['vulnerabilities']))) || !is_array($content)) {
            $wpDBRecord = $this->getVlsFromDBByType($vlsType);

            if (empty($wpDBRecord)) {
                $varValues = array('vls_type' => $vlsType, 'content' => oseJSON::encode(array($content)));
                $this->db->addData('insert', $this->vlstable, '', '', $varValues);
            } else {
                if ($vlsType != 4) {
                    $data = oseJSON::decode($wpDBRecord['content'], true);
                    $data[] = $content;
                    $content = $data;
                }
                $varValues = array('vls_type' => $vlsType, 'content' => oseJSON::encode($content));
                $this->db->addData('update', $this->vlstable, 'vls_type', $vlsType, $varValues);
            }
            return $return;
        } else {
            return null;
        }
    }

    private function completeVulScan()
    {
        $vulScanList = $this->readVulScanList();

        $result['content'] = array();
        $result['status'] =
            array("progress" => "100",
                "current_type" => 'Complete',
                "current_scan" => oLang::_get('VL_COMPLETE'),
                "total_scan" => $vulScanList['totalscan'],
                "total_vls" => $vulScanList['totalvls'],
                "cont" => false,
                "step" => -1
            );
        $this->setScanProgress('', $result['status'], $result['content']);
        $Scanhistdata = array('totalscan' => $vulScanList['totalscan'],
            'totalvs' => $vulScanList['totalvls'],
            'vsfilelist' => $vulScanList['vsfilelist']);
        $this->saveDBLastScanResult( $Scanhistdata );
        $this->deleteVulScanList();
        return $this->vlscanProgress;
    }

    private function saveDBLastScanResult ($content)
    {
        $varValues = array('super_type' => 'vlscan',
            'sub_type' => 1,
            'content' => oseJSON::encode(array($content)),
            'inserted_on' => oseFirewall::getTime()
        );
        $this->db->addData('insert', $this->scanhisttablebl, '', '', $varValues);
    }

    private function getExtList()
    {
        $excluded = "''";
        $query = "SELECT * FROM " . $this->db->quoteTable('#__extensions')
            . " WHERE `element` NOT IN (" . $excluded . ") ";
        $this->db->setQuery($query);
        $result = $this->db->loadObjectList();
        return $result;
    }

    private function getLastScanRecordFromDB()
    {
        $query = "SELECT `vls_type`, `content` "
            . "FROM " . $this->db->quoteTable($this->vlstable);
        $this->db->setQuery($query);
        $result = $this->db->loadObjectList();
        return $result;
    }

    private function getVlsFromDBByType($vls_type)
    {
        $query = "SELECT `vls_type`, `content` "
            . "FROM " . $this->db->quoteTable($this->vlstable)
            . " WHERE vls_type = $vls_type";
        $this->db->setQuery($query);
        $result = $this->db->loadResult();
        return $result;
    }

    private function clearpreviousScanDB()
    {
        $result = $this->db->truncateTable($this->vlstable);
        return $result;
    }

    public function getLastScanRecord()
    {
        $last_db_records = $this->getLastScanRecordFromDB();
        $last_record = array();
        $last_record['content'] = array('theme' => array(), 'plugin' => array(), 'CMS' => '');
        $last_record['scanDate'] = '';
        $last_record['serverNow'] = '';
        foreach ($last_db_records as $db_record) {
            switch ($db_record->vls_type) {
                case 1:
                    $last_record['content']['CMS'] = oseJSON::decode($db_record->content, true);
                    break;
                case 2:
                    $last_record['content']['plugin'] = oseJSON::decode($db_record->content, true);
                    break;
                case 3:
                    $last_record['content']['theme'] = oseJSON::decode($db_record->content, true);
                    break;
                case 4:
                    $last_record['scanDate'] = oseJSON::decode($db_record->content, true);
                    $last_record['serverNow'] = oseFirewall::getTime();
                    break;
            }
        }
        return oseJSON::encode($last_record);
    }
}