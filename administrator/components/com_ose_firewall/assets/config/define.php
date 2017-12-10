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
define('CENT_DEBUG_MODE', false);
define('CENT_AI', false);
define('TEST_ENV', false);
define('DEVELOPMENT',false);
define('CENTRORA_SUPOORT',"To address this issue please contact the support team at support@centrora.com");
if(DEVELOPMENT)
{
    define('API_SERVER','https://apidev.centrora.com/'); //DEVELOPMENT
    define("BACKUP_API_SERVER","https://backup-api-dev.centrora.com.au/");
    define("VSSCAN_API_SERVER","https://vsscan-api-dev.centrora.com.au/");
    define('VIRUS_PATTERN_DOWNLOAD_URL',"https://update-api-dev.centrora.com.au/download/updateVSPattern");
    define("FIREWALL_PATTERN_DOWNLOAD_URL","https://update-api-dev.centrora.com.au/download/updateFWPattern&update=0");
    define("FIREWALL_PATTERN_UPDATE_DOWNLOAD_URL","https://update-api-dev.centrora.com.au/download/updateFWPattern&update=1");
    define("GET_VIRUS_PATTERN_VERSION_URL","https://update-api-dev.centrora.com.au/version/getVSPatternVersion");
    define("GET_FIREWALL_PATTERN_VERSION_URL","https://update-api-dev.centrora.com.au/version/getFWPatternVersion");
    define("BACKUP_API","https://backup-api-dev.centrora.com.au/");
    define("ACCOUNT_API","https://account-api-dev.centrora.com.au/");
    define("UPDATE_API","https://update-api-dev.centrora.com/");
    define("DOWNLOAD_CORE_FILES","https://update-api-dev.centrora.com.au/download/getCoreFile");
}else {
    define('API_SERVER','https://api2.centrora.com/'); //LIVE
    define("BACKUP_API_SERVER","https://backup-api.centrora.com/");
    define("VSSCAN_API_SERVER","https://vsscan-api.centrora.com/");
    define('VIRUS_PATTERN_DOWNLOAD_URL',"https://update-api.centrora.com/download/updateVSPattern");
    define("FIREWALL_PATTERN_DOWNLOAD_URL","https://update-api.centrora.com/download/updateFWPattern&update=0");
    define("FIREWALL_PATTERN_UPDATE_DOWNLOAD_URL","https://update-api.centrora.com/download/updateFWPattern&update=1");
    define("GET_VIRUS_PATTERN_VERSION_URL","https://update-api.centrora.com/version/getVSPatternVersion");
    define("GET_FIREWALL_PATTERN_VERSION_URL","https://update-api.centrora.com/version/getFWPatternVersion");
    define("BACKUP_API","https://backup-api.centrora.com/");
    define("ACCOUNT_API","https://account-api.centrora.com/");
    define("UPDATE_API","https://update-api.centrora.com/");
    define("DOWNLOAD_CORE_FILES","https://update-api.centrora.com/download/getCoreFile");
}
define('FIREWALL_VERSION_CHECK_THRESHOLD',1); //1 hour
define('VIRUS_VERSION_CHECK_THRESHOLD',5); // 5 mins
require_once(dirname(__FILE__).ODS.'uri.php');
if (class_exists('JConfig') || class_exists('SConfig') || (defined('OSE_SUITE') && OSE_SUITE == true))
{
    require_once(dirname(__FILE__).ODS.'joomla.php');
}
else
{
    require_once(dirname(__FILE__).ODS.'wordpress.php');
}
?>