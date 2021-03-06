<?php
/**
 * @version     6.0 +
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
//Start here;
define('O_LATEST_SIGNATURE', '20140901');
define('OSE_WORDPRESS_FIREWALL_SETTING', ''.OSE_WORDPRESS_FIREWALL.' Settings');
define('OSE_WORDPRESS_FIREWALL_UPDATE_DESC', 'OSE Firewallâ„¢ has been renamed as â€˜'.OSE_WORDPRESS_FIREWALL.'â€™, which will works perfectly with our new product <a href="'.OSE_OEM_URL_MAIN.'" target = "_blank">Centrora</a>, a security management central that gains you the ability to manage all your websites in one place.');
define('OSE_DASHBOARD', 'Dashboard');
define('OSE_DASHBOARD_SETTING', 'Dashboard Settings');
define('BLOCKBL_METHOD', 'Block blacklisted methods (Trace / Delete / Track)');
define('CHECK_MUA', 'Checks Malicious User Agent');
define('checkDOS', 'Checks Basic DoS / Web Application Flooding Attacks');
define('checkDFI', 'Checks Basic Direct File Inclusion');
define('checkRFI', 'Checks Basic Remote File Inclusion');
define('checkJSInjection', 'Checks Basic Javascript Injection');
define('checkSQLInjection', 'Checks Basic Database SQL Injection');
define('checkTrasversal', 'Detect Directory Traversal');
define('BLOCK_QUERY_LONGER_THAN_255CHAR', 'Block Queries longer than 255 characters');
define('SAVE_CHANGES', 'Save Changes');
// Langauges for version 1.5 + start from here;
define('OSE_SCANNING','Scanning');
define('OSE_FOLDERS','folders');
define('OSE_AND','and');
define('OSE_FILES','files');
define('OSE_INFECTED_FILES','infected files');
define('OSE_INTOTAL','in total of');
define('OSE_THERE_ARE','There are');
define('OSE_VIRUS_SCAN','Dynamic Virus Scanner');
define('OSE_VIRUS_SCAN_DESC','OSE WordPress Virus Scannerâ„¢ aims to scan and clean WordPress malicious codes and monitors your website on a 24/7 basis.');
define('COMPATIBILITY','Compatibility');
define('OSE_PLEASE_CONFIG_FIREWALL','Please configure the firewall setting here.');
define('OSE_FOLLOWUS','Follow us to keep updated.');
define('OSE_SCAN_ACTIVITY','Scan Detailed Activity');
// Langauges for version 1.6 + start from here;

// Languages for version 2.0 start from here:
define('DBNOTREADY','<b>WARNING</b>: The database is not ready, please click the install button to create the database tables');
define('DASHBOARD_TITLE','Dashboard');
define('SEARCHFORMALWARE','Search for Malware');
define('INSTALLNOW','Install Now');
define('UNINSTALLDB', 'Uninstall');
define('UNINSTALLDB_INTRO', 'Removing the database created by '.OSE_WORDPRESS_FIREWALL.' from your website.');
define('UPDATEVERSION', 'Update');
define('SUBSCRIBE', 'Subscribe');
define('READYTOGO','Everything is ready to go! If you would like to uninstall the database, please do so from System Menu > Install/Uninstall.');
define('CREATE_BASETABLE_COMPLETED',' > Create Base Table Completed, continue...');
define('INSERT_CONFIGCONTENT_COMPLETED',' > Inserting Configuration Data Completed, continue...');
define('INSERT_EMAILCONTENT_COMPLETED',' > Inserting Email Content Completed, continue...');
define('INSTALLATION_COMPLETED',' > Database Installation Completed.');
define('INSERT_ATTACKTYPE_COMPLETED',' > Attack Type Information Installation Completed, continue...');
define('INSERT_BASICRULESET_COMPLETED',' > Basic Ruleset Installation Completed, continue...');
define('INSERT_FILEEXTENSION_COMPLETED', ' > File extension Installation Completed, continue...');
define('CREATE_IPVIEW_COMPLETED',' > IP-ACL Mapping View Creation Completed, continue...');
define('CREATE_ADMINEMAILVIEW_COMPLETED',' > Admin-Email Mapping View Creation Completed, continue...');
define('CREATE_ATTACKMAPVIEW_COMPLETED',' > ACL-Attack Mapping View Creation Completed, continue...');
define('CREATE_ATTACKTYPESUMEVIEW_COMPLETED',' > Attack Type Mapping View Creation Completed, continue...');
define('INSERT_STAGE1_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 1 Installation Completed, continue...');
define('INSERT_STAGE2_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 2 Installation Completed, continue...');
define('INSERT_STAGE3_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 3 Installation Completed, continue...');
define('INSERT_STAGE4_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 4 Installation Completed, continue...');
define('INSERT_STAGE5_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 5 Installation Completed, continue...');
define('INSERT_STAGE6_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 6 Installation Completed, continue...');
define('INSERT_STAGE7_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 7 Installation Completed, continue...');
define('INSERT_STAGE8_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 8 Installation Completed, continue...');
define('INSERT_VSPATTERNS_COMPLETED',' > Virus Patterns Insertion Completed, continue...');
define('MANAGEIPS_TITLE','IP Control');
define('MANAGEIPS_DESC','Block, Manage and Control the access of IP addresses. '.OSE_WORDPRESS_FIREWALL.' automatically detect suspicious IP for you and set as monitored as default.');
define('IP_EMPTY','IP is empty');
define('IP_INVALID_PLEASE_CHECK','The IP is invalid, please check if your any of your octets is greater than 255');
define('IP_RULE_EXISTS','The Access Control Rules for this IP / IP Range already exists.');
define('IP_RULE_ADDED_SUCCESS','The Access Control Rules for this IP / IP Range was added successfully.');
define('IP_RULE_ADDED_FAILED','The Access Control Rules for this IP / IP Range was added unsuccessfully.');
define('IP_RULE_DELETE_SUCCESS','The Access Control Rules for this IP / IP Range was removed successfully.');
define('IP_RULE_DELETE_FAILED','The Access Control Rules for this IP / IP Range was removed unsuccessfully.');
define('IP_RULE_CHANGED_SUCCESS','The Access Control Rules for this IP / IP Range has been changed successfully.');
define('IP_RULE_CHANGED_FAILED','The Access Control Rules for this IP / IP Range has been changed unsuccessfully.');
define('MANAGE_IPS', 'Web Attack');
define('RULESETS','Variable Management');
define('MANAGERULESETS_TITLE','<b>Firewall</b> <span><b>Rules Fine-tuning</b></span>');
define('MANAGERULESETS_DESC','Activate or deactivate specific firewall rules. You can change the security features of '.OSE_WORDPRESS_FIREWALL.' by deactivating specific security function.');
define('ADRULESETS', 'Advanced Firewall Rules Fine-tuning');
define('MANAGE_AD_RULESETS_TITLE','<b>Advanced Firewall Settings</b>');
define('MANAGE_AD_RULESETS_DESC','The Panel to Manage your Advance Rules');
define('ITEM_STATUS_CHANGED_SUCCESS','The status of the item has been changed successfully');
define('ITEM_STATUS_CHANGED_FAILED','The status of the item was changed unsuccessfully');
define('CONFIGURATION','Configuration');
define('CONFIGURATION_TITLE','<b>Installation</b>');
define('CONFIGURATION_DESC','You can install or uninstall the database tables here');
define('SEO_CONFIGURATION_TITLE','<b>Search Engine</b> <span><b>Configuration</b></span>');
define('SEO_CONFIGURATION_DESC','Search Engine settings which protect your rankings even if google bots block your website. Design message to be displayed for blocked IP visitors');
define('CONFIG_SAVE_SUCCESS','The configuration was saved successfully.');
define('CONFIG_SAVE_FAILED','The configuration was saved unsuccessfully.');
define('SCAN_CONFIGURATION','Scanning Configuration');
define('SCAN_CONFIGURATION_TITLE', 'Firewall Scanning');
define('SCAN_CONFIGURATION_DESC','Connect to '.OSE_WORDPRESS_FIREWALL.' with an API key and configure Firewall Scanning Settings');
define('ANTISPAM_CONFIGURATION','Anti-Spam Configuration');
define('ANTISPAM_CONFIGURATION_TITLE','<b>Anti-Spam</b> <span><b>Configuration</b></span>');
define('ANTISPAM_CONFIGURATION_DESC','Enable/Disable stop forum spam to avoid persistent spammers on message boards and blogs');
define('EMAIL_CONFIGURATION','Email Configuration');
define('EMAIL_CONFIGURATION_TITLE','<b>Email</b> <span><b>Configuration</b></span>');
define('EMAIL_CONFIGURATION_DESC','Email template configuration for blacklisted, filtered, and 403 blocked entry for detected attacks');
define('EMAIL_TEMPLATE_UPDATED_SUCCESS','The email template has been changed successfully.');
define('EMAIL_TEMPLATE_UPDATED_FAILED','The email template was changed unsuccessfully.');
define('EMAIL_ADMIN','Admin-Email Mapping');
define('EMAIL_ADMIN_TITLE','<b>Administrator-Email</b> <span><b>Mapping</b></span>');
define('EMAIL_ADMIN_DESC','Decide which admin user can receive different email for blacklisted, filtered, and 403 blocked entry for detected attacks');
define('LINKAGE_ADDED_SUCCESS','The linkage has been added successfully.');
define('LINKAGE_ADDED_FAILED','The linkage was added unsuccessfully.');
define('LINKAGE_DELETED_SUCCESS','The linkage has been deleted successfully.');
define('LINKAGE_DELETED_FAILED','The linkage was deleted unsuccessfully.');
define('ANTIVIRUS_CONFIGURATION','Virus Scanner Configuration');
define('ANTIVIRUS_CONFIGURATION_TITLE','<b>Virus Scanner</b> <span><b>Configuration</b></span>');
define('ANTIVIRUS_CONFIGURATION_DESC','Configure the settings for Virus Scanner, control file extension to be scanned and limit the size of scanning files');
define('ANTIVIRUS', 'Scan');
define('ANTIVIRUS_TITLE','<b>Virus</b> <span><b>Scanner</b></span>');
define('ANTIVIRUS_DESC', 'Dynamic Virus Scanner is a powerful malware detector, it acts like an antivirus but is more powerful than an antivirus.');
define('LAST_SCANNED','Last scanned folder: ');
define('LAST_SCANNED_FILE','Last scanned file: ');
define('OSE_FOUND',OSE_WORDPRESS_FIREWALL.' found');
define('OSE_ADDED',OSE_WORDPRESS_FIREWALL.' added');
define('SCANNED_PATH_EMPTY','Please make sure the scanned path is not empty.');
define('O_SHELL_CODES', 'Shell Codes');
define('O_BASE64_CODES', 'Base64 Encoded Codes');
define('O_JS_INJECTION_CODES', 'Javascript Injection Codes');
define('O_PHP_INJECTION_CODES', 'PHP Injection Codes');
define('O_IFRAME_INJECTION_CODES', 'iFrame Injection Codes');
define('O_SPAMMING_MAILER_CODES', 'Spamming Mailer Codes');
define('O_EXEC_MAILICIOUS_CODES','Executable Malicious Codes');
define('O_OTHER_MAILICIOUS_CODES','Other Miscellaneous Malicious Codes');
define('COMPLETED','Completed');
define('YOUR_SYSTEM_IS_CLEAN','Your system is clean.');
define('VSREPORT', 'Scan Result <small>(Premium)</small>');
define('SCANREPORT_TITLE','<b>Scan</b> <span><b>Report</b></span>');
define('SCANREPORT_DESC','Display the infected files last scanned by the virus scanner');
define('SCANREPORT_CLEAN', 'No files were infected.');
define('VARIABLES','Variables');
define('VARIABLES_TITLE','Variables Management');
define('VARIABLES_DESC','Variable scanning. '.OSE_WORDPRESS_FIREWALL.' automatically scan the variables in the background to prevent attacks through variables');
define('VERSION_UPDATE', 'Anti-Virus Database Update');
define('ANTI_VIRUS_DATABASE_UPDATE', 'Anti-Virus Database Update');
define('VERSION_UPDATE_TITLE', '<b>OSE Version Update Panel</b>');
define('VERSION_UPDATE_DESC', 'The panel is to update your local anti-virus database');
define('CHECK_UPDATE_VERSION', 'Connecting with server and Checking update version...');
define('START_UPDATE_VERSION', 'Start downloading updates...');
define('UPDATE_COMPLETED', 'Update Completed!');
define('CHECK_UPDATE_RULE', 'Checking update rule...');
define('UPDATE_LOG', 'Updating Log...');
//Since 2.3.0
define('FILE_UPLOAD_VALIDATION', 'File Upload Validation');
define('GEONOTREADY', 'Please install GeoIP Country List to enable country blocking feature.');
define('COUNTRYBLOCK_TITLE', 'Country <span>Blocking</span>');
define('COUNTRYBLOCK_DESC','The Panel to Block IPs from countries');
define('COUNTRYBLOCK', 'Country Blocking <small>(Premium)</small>');
define('BACKUP', 'Backup List');
define('ADVANCEDBACKUP', 'Cloud Backup <small>(Premium)</small>');
define('ADMINEMAILS_TITLE', '<b>Administrator Management</b>');
define('ADMINEMAILS_DESC', 'You can centrally manage your administrator and domain addresses here');
define('ADMINEMAILS', 'Manage Administrators');
define('BACKUP_MANAGER', 'Classic Backup');
define('BACKUP_TITLE', '<b>Backup Management</b>');
define('BACKUP_DESC', 'Centrally managing database and files backup. ( This is a classic backup function, we recommend you to use Gitbackup instead as it saves more website space )');
define('DB_COUNTRYBLOCK_FAILED_INCORRECT_PERMISSIONS','Failed backing up database, please ensure the backup directory "'.OSE_FWDATA.'/backup/" is writable.');
define('FILE_VSSCAN_FAILED_INCORRECT_PERMISSIONS', 'Failed Scanning Virus, please ensure the scan file "'.OSE_FWDATA.'/vsscanPath/path.json" is writable.');
define('DB_BACKUP_SUCCESS', 'The database backup is successful');
define('DB_DELETE_SUCCESS', 'The Backup item was removed successfully.');
define('DB_DELETE_FAILED', 'The Backup item was removed unsuccessfully.');
define('ADVRULESET_INSTALL_SUCCESS', 'Advanced security rulesets have been installed successfully');
define('ADVRULESET_INSTALL_FAILED', 'Advanced security rulesets was installed unsuccessfully');
define('GAUTHENTICATOR','googleVerification');
define('IPMANAGEMENT_INTRO', 'Block, Manage and Control the access of IP addresses. '.OSE_WORDPRESS_FIREWALL.' automatically detect suspicious IP for you and set as monitored as default.');
define('FIREWALL_SETTING_INTRO', 'Activate or Deactivate the firewall function. You can limit the security features of '.OSE_WORDPRESS_FIREWALL.' by deactivating any security function. We highly recommend to activate all of the security functions to carry the best out of '.OSE_WORDPRESS_FIREWALL.'');
define('VARIABLES_INTRO', 'Variable scanning. '.OSE_WORDPRESS_FIREWALL.' automatically scan the variables in the background to prevent attacks through variables');
define('VIRUS_SCANNER_INTRO', 'Virus Scanner is a powerful malware detector, it acts like an antivirus but is more powerful than an antivirus. It scans through every single files on your server or any specific path of files for virus, malware, spam, malicious codes, SQL injection, security vulnerabilities etc');
define('SCAN_REPORT_INTRO', 'Display the infected files last scanned by the virus scanner');
define('CONFIGURATION_INTRO', 'Configure the default settings of '.OSE_WORDPRESS_FIREWALL.' to best suit your personal needs. It includes settings for scanning, virus scanner, SEO, anti-spam, email, and admin email mapping');
define('BACK_UP_INTRO', 'Backup database into your own server for free');
define('COUNTRY_BLOCK_INTRO', 'Block the IP range of the entire country that you insist to. '.OSE_WORDPRESS_FIREWALL.' will keep the visitors from blocked country out of your website');
define('SCANCONFIG_INTRO', 'Configure Firewall Scanning Settings');
define('VSCONFIG_INTRO', 'Configure the settings for Virus Scanner, control file extension to be scanned and limit the size of scanning files');
define('SEOCONFIG_INTRO', 'Search Engine settings which protect your rankings even if google bots block your website. Design message to be displayed for blocked IP visitors');
define('LOGIN_FAILED', 'Login failed. Username, Password or Private Key is incorrect!');
define('LOGIN_STATUS', 'Login Status');
define('LOGIN', 'Login');
define('SUBSCRIPTION', 'Subscription');
define('STOP_VIRUSSCAN', 'Stop Scanning');
define('CONFIG_SAVECOUNTRYBLOCK_FAILE', 'Save Country Blocking config failed, Country Blocking Database is not ready.  <a href="' . DOWANLOAD_COUNTRYBLOCK_DB . '">Download now</a>');
define('CONFIG_ADPATTERNS_FAILE', 'Save Advanced Virus Pattern config failed, Advanced Virus Pattern Database is not ready.');
define('UNINSTALL_SUCCESS', 'Uninstall database table success!');
define('UNINSTALL_FAILED', 'Uninstall database table failed!');
define('SCAN_READY','Ready to scan virus');
define('ADVANCERULESNOTREADY', '<b>[Better Protection] </b><b>IMPROVEMENT</b>: Please turn on the advance firewall to get enhanced protection. The advance firewall protection offers 45+ detection technique to protect your website from hacking attempts');
define('ABOUT', 'Features');
define('ADVANCERULES_READY','<b>[Better Protection] </b>Great! Your website is more secure now');
define('ADMINUSER_EXISTS','<b>[Admin Protection] </b><b>WARNING</b>: The administrator account \'admin\' still exists, please change the username for the administrator user ASAP.');
define('ADMINUSER_REMOVED','<b>[Admin Protection] </b>Great! The admin account \'admin\' has been removed.');
define('FIREWALL','Firewall');
define('OSE_AUDIT','Audit');
define('GAUTHENTICATOR_NOTUSED','<b>[Admin Protection] </b><b>WARNING</b>: Google 2 Step Authenticator is not used. This is an effective method to avoid brute force attack, we strongly suggest you enable this function. Please follow this tutorial to enable it.');
define('GAUTHENTICATOR_READY','<b>[Admin Protection] </b>Great! Google Authenticator is available in this website, please ensure all web adminsitrators have enabled the function for their accounts.');
define('WORDPRESS_OUTDATED','<b>[Wordpress Update] </b><b>WARNING</b>: Your Wordpress is out dated, please update it ASAP. Current version is ');
define('WORDPRESS_UPTODATE','<b>[Wordpress Update] </b>Great! Your website is up-to-date with the current version of ');
define('USERNAME_CANNOT_EMPTY','Username cannot be empty');
define('USERNAME_UPDATE_SUCCESS','Successfully changed the username. The browser will be refreshed soon, if you logged in as \'admin\', please login with your new username then.');
define('USERNAME_UPDATE_FAILED','Failed to change the username');
define('GOOGLE_IS_SCANNED', '<b>[SEO Protection] </b><b>WARNING</b>: Please note that Google bots are being scanned, if your website is not under heavy attack, please disable this function to avoid your SEO being afftected.');
define('CLAMAV', 'ClamAV Integration');
define('CLAMAV_TITLE', '<b>ClamAV Integration</b>');
define('CLAMAV_DESC', 'ClamAV is an open source anti-virus software for linux server. '.OSE_WORDPRESS_FIREWALL.' can integrate ClamAV into the virus scanning function to enhance the power for picking malicious files. For server installation instruction, please see <a href ="https://www.centrora.com/blog/285-how-to-install-free-antivirus-clamav-on-your-linux" target="_blank">this tutorial</a>. Once installed, please see <a href="http://www.centrora.com/blog/free-antivirus-for-wordpress/" target = "_blank">this tutorial</a> to enable ClamAV scanning in '.OSE_WORDPRESS_FIREWALL.'.');
define('CLAMAV_CONNECT_SUCCESS', 'Successfully connected to Clam Daemon');
define('CLAMAV_CANNOT_CONNECT','Cannot connect to the ClamAV Daemon');
define('SIGNATURE_UPTODATE','<b>[Better Protection] </b>Your firewall rules are up to date');
define('SIGNATURE_OUTDATED','<b>[Better Protection] </b><b>IMPROVEMENT</b>: Your firewall rules are out-dated, please update the rules to enhance protection. The updated advance firewall protection offers 45+ detection technique to protect your website from hacking attempts');
define('SAFE_BROWSING_CHECKUP',''.OSE_WORDPRESS_FIREWALL.' Safe Browsing Checkup (Blacklist Monitoring)');
define('SECURITY_CONFIG_AUDIT','Security Configuration Audit');
define('CHECK_SAFE_BROWSING','Check your website safe browsing status now.');
define('SAFE_BROWSING_CHECKUP_UPDATED','Your Safe Browsing Checkup is updated');
define('API_CONFIGURATION','API Configuration');
define('API_INTRO','Connect to '.OSE_WORDPRESS_FIREWALL.' with API key');
define('SYSTEM_SECURITY_AUDIT','System Security Audit');
define ('WORDPRESS_FOLDER_PERMISSIONS','Wordpres Folder Permissions');
define('REG_GLOBAL_OFF','The PHP Setting register_global is <b>OFF</b>.');
define('CHANGE_PHPINI', 'If this has been turned off in the configuration section, please change it in the php.ini');
define('REG_GLOBAL_ON','The PHP Setting register_global is <b>ON</b>, please turn if off. '.CHANGE_PHPINI);
define('SAFEMODE_OFF','The PHP Setting safe_mode is <b>OFF</b>');
define('SAFEMODE_ON','The PHP Setting safe_mode is <b>ON</b>, please turn if off. '.CHANGE_PHPINI);
define('URL_FOPEN_OFF','The PHP Setting allow_url_fopen is <b>OFF</b>');
define('URL_FOPEN_ON','The PHP Setting allow_url_fopen is <b>ON</b>, please turn if off. '.CHANGE_PHPINI);
define('DISPLAY_ERROR_OFF','The PHP Setting display_errors is <b>OFF</b>');
define('DISPLAY_ERROR_ON','The PHP Setting display_errors is <b>ON</b>, please turn if off. '.CHANGE_PHPINI);
define('DISABLE_FUNCTIONS_READY','The following PHP functions have been disabled: ');
define('DISABLE_FUNCTIONS_NOTREADY','The following PHP functions need to be disabled: ');
define('SCHEDULE_SCANNING', 'Schedule Virus Scanning');
define('SYSTEM_PLUGIN_DISABLED', 'The '.OSE_WORDPRESS_FIREWALL.' system plugin is disabled, please enable it and put it to the first position.');
define('SYSTEM_PLUGIN_READY', 'The '.OSE_WORDPRESS_FIREWALL.' system plugin is ready.');
define('SCAN_SPECIFIC_FOLDER', 'Scan Specific Folder');

define('O_FILE_ID', 'File ID');
define('O_FILE_NAME', 'File Name');
define('O_CONFIDENCE', 'Notes');
define('O_PATTERN_ID', 'Pattern ID');
define('O_CHECKSTATUS', 'Status');

define('O_BACKUPFILE_ID', 'ID');
define('O_BACKUPFILE_DATE', 'Time');
define('O_BACKUPFILE_NAME', 'File Name');
define('O_BACKUPFILE_TYPE', 'Backup Type');
define('O_BACKUP_DROPBOX', 'Dropbox');


define('O_IP_RULE_TITLE', 'IP Rule Title');
define('O_ID', 'ID');
define('O_IP', 'IP');
define('O_DATE', 'Date');
define('O_DATETIME', 'Date&Time');
define('O_RISK_SCORE', 'Score');
define('O_START_IP', 'Start IP');
define('O_END_IP', 'End IP');
define('O_VARIABLE', 'Variable');
define('O_IP_RULE', 'IP Rule Title');
define('O_IP_TYPE', 'IP Type');
define('O_RANGE', 'IP Range');
define('O_SINGLE_IP', 'Single IP');
define('O_STATUS', 'Status');
define('O_LIC_STATUS', 'License Status');
define('O_ORDER_STATUS','Order Status');
define('O_VISITS', 'Visits');
define('O_VIEWDETAIL', 'Action');
define('O_DELETE_ITEMS', 'Delete');
define('O_STATUS_MONITORED_DESC', 'Monitored');
define('O_STATUS_BLACKLIST_DESC', 'Blacklist');
define('O_STATUS_WHITELIST_DESC', 'Whitelist');
define('O_DOMAIN','Domain');
define('O_DOWNLOAD_CERT','Download Certificate');

define('O_DEFAULT_VARIABLES_WARNING', 'Please enable the default variables to avoid false alerts from the firewall');
define('O_DEFAULT_VARIABLE_BUTTON','Enable Whitelist default variables');

define('ADD_IPS', 'Add');
define('O_BLACKLIST_IP', 'Blacklist');
define('O_WHITELIST_IP', 'Whitelist');
define('O_MONITORLIST_IP', 'Monitor');
define('ADD_IP_FORM','Add IP Form');
define('IPFORM_DESC', 'This form allows you to add an IP or IP Range into the system');
define('IPFORM_FWS7_DESC',"This form allows you to add an Ip and choose whether to block it or allow access to it ");
define('O_DELETE__ALLITEMS', 'Clear All');
define('O_SYNC_IPS_FROMV6','Sync from firewall V6.6');
define('SAVE', 'Save');

define('PLEASE_SELECT_ITEMS', 'Please select at least one item.');
define('O_UPDATE_HOST', 'Update Host');
define('O_IMPORT_IP_CSV', 'Import from CSV');
define('O_EXPORT_IP_CSV', 'Export to CSV');
define('O_IMPORT_NOW', 'Import Now');
define('GENERATE_CSV_NOW', 'Generate CSV File Now');

define('O_ATTACKTYPE', 'Attack Type');
define('O_RULE', 'Rule');
define('O_IMPACT', 'Impact');

define('ADD_A_VARIABLE', 'Add a Variable');
define('O_VARIABLE_NAME', 'Variable Name');
define('O_VARIABLE_TYPE', 'Variable Type');
define('O_VARIABLES', 'Variables');
define('VARIABLE_NAME_REQUIRED', 'Variable Name Required');
define('LOAD_WORDPRESS_DATA', 'Load WordPress default variables');
define('O_STATUS_EXP', 'Status Explanation');
define('SCAN_VARIABLE', 'Scan the Variable');
define('FILTER_VARIABLE', 'Filter the Variable');
define('IGNORE_VARIABLE', 'Ignore the Variable');
define('VARIABLE_CHANGED_SUCCESS', 'The variable status has been changed successfully.');
define('VARIABLE_CHANGED_FAILED', 'The variable status was changed unsuccessfully.');
define('VARIABLE_ADDED_SUCCESS', 'The variable has been added successfully.');
define('VARIABLE_ADDED_FAILED', 'The variable was added unsuccessfully.');
define('VARIABLE_DELETED_SUCCESS', 'The variable has been deleted successfully.');
define('VARIABLE_DELETED_FAILED', 'The variable was deleted unsuccessfully.');

define('LOAD_JOOMLA_DATA', 'Load Joomla Variables');
define('LOAD_JSOCIAL_DATA', 'Load JomSocial Variables');

define('O_BLACKLIST_COUNTRY', 'Blacklist Country');
define('O_WHITELIST_COUNTRY', 'Whitelist Country');
define('DOWNLOAD_COUNTRY', 'Download Country Database');
define('DOWNLOAD_NOW', 'Download Now');
define('O_MONITOR_COUNTRY', 'Monitor Country');
define('O_COUNTRY', 'Country');
define('COUNTRY_STATUS_CHANGED_SUCCESS', 'The country status has been changed successfully.');
define('COUNTRY_STATUS_CHANGED_FAILED', 'The country status was changed unsuccessfully.');
define('COUNTRY_DATA_DELETE_SUCCESS', 'Country data has been deleted successfully.');
define('COUNTRY_DATA_DELETE_FAILED', 'Country data was changed unsuccessfully.');

define('O_SCANREPORT_CLEAN', 'Clean');
define('O_SCANREPORT_QUARANTINE', 'Quarantine');
define('O_SCANREPORT_RESTORE', 'Restore');
define('O_SCANREPORT_DELETE', 'Delete');

define('O_BACKUP_BACKUPDB', 'Back Up Database');
define('O_BACKUP_BACKUPFILE', 'Back Up Files');
define('O_BACKUP_DELETEBACKUPFILE', 'Delete');


define('VARIABLES_MANAGEMENT', 'Variables Fine-tuning');

define('O_FRONTEND_BLOCKING_MODE','Frontend Blocking Mode');
define('O_COUNTRY_BLOCKING','Country Blocking');
define('O_ADRULESETS','Advanced Firewall Setting (See <a href ="'.OSE_OEM_URL_ADVFW_TUT.'" target=\'_blank\'>Tutorial Here</a>)');
define('O_GOOGLE_2_VERIFICATION','Google 2-Step Verification');

define('O_SEO_PAGE_TITLE','SEO Page Title');
define('O_SEO_META_KEY','SEO Meta Keywords');
define('O_SEO_META_DESC','SEO Meta Description');
define('O_SEO_META_GENERATOR','SEO Meta Generator');
define('O_WEBMASTER_EMAIL','Webmaster Contact Email');
define('O_CUSTOM_BAN_PAGE','Custom Ban Page');
define('O_SCAN_YAHOO_BOTS','Scan Yahoo Bots');
define('O_SCAN_GOOGLE_BOTS','Scan Google Bots');
define('O_SCAN_MSN_BOTS','Scan MSN Bots');
define('O_SCANNED_FILE_EXTENSIONS','File extensions being scanned');
define('O_MAX_FILE_SIZE','Maximum file size to be scanned');
define('AUDIT_WEBSITE','Audit My Website');
define('OVERVIEW_COUNTRY_MAP','Overview of Hacking Activities By Countries');
define('RECENT_HACKING_INFO','Recent Hacking Traffic');
define('RECENT_SCANNING_RESULT', 'Recent scanning report');
define('RECENT_BACKUP', 'Recent backup');
define('PLEASE_ENTER_REQUIRED_INFO','Please enter the required information.');
define('INSTALLATION', 'Install/Uninstall');
define('INSTALLDB','Install Database Tables');
define('INSTALLDB_INTRO','Install the database created by '.OSE_WORDPRESS_FIREWALL.' from your website');
define('UNINSTALLNOW','Uninstall Now');
define('CHANGE_ADMINFORM','New Administrator Username');
define('CHANGE','Change');
define('PHP_CONFIGURATION','PHP Configuration');
define('O_RECURRING_ID', 'Order ID');
define('O_PROFILE_ID', 'Profile ID');
define('O_REMAINING', 'Remaining');
define('O_VIEW', 'View');
define('CREATE', 'Create');
define('CREATE_AN_ACCOUNT', 'Create An Account');
define('FIRSTNAME', 'First Name');
define('LASTNAME', 'Last Name');
define('EMAIL', 'Email');
define('PASSWORD', 'Password');
define('PASSWORD_CONFIRM', 'Password Confirm');
define('TUTORIAL', 'Tutorial');
define('COUNTRY_CHANGED_SUCCESS','The country status is changed successfully');
define('ACTIVATION_CODES', 'Firewall Activation Codes');
define('ACTIVATION_CODE_TITLE', 'Activation Codes');
define('ADD_TRACKING_CODE', 'Add Tracking Codes');
define('TRACKINGCODE_CANNOT_EMPTY', 'Tracking Codes cannot be empty');
define('TRACKINGCODE_UPDATE_SUCCESS', 'Great! Successfully updated tracking codes. In the future, when the owner of this website subscribes to our subscription plans, the transaction will be logged into your affiliate accounts.');
define('TRACKINGCODE_UPDATE_FAILED', 'Failed updating Tracking Codes');
define('WORDPRESS_ADMIN_AJAX_PROTECTION', 'WordPress Admin Ajax Protection');
define('ADD_DOMAIN', 'Add a domain');
define('ADD_ADMIN', 'Add An Administrator');
define('ADD_ADMIN_ID', 'ID');

define('ADD_ADMIN_NAME', 'Name');
define('ADD_ADMIN_EMAIL', 'Email');
define('ADD_ADMIN_STATUS', 'Status');
define('ADD_ADMIN_DOMAIN', 'Allocate Domain');
define('TABLE_DOMAIN', 'Domain');
define('SCAN', 'Scan');
define('FILE_CONTENT', 'File Content');
define('O_CUSTOM_BAN_PAGE_URL', 'Custom Ban Page URL');
define('SUCCESS', 'Successful');
define('SUCCESS_LOGOUT', 'Successful logged out');
define('FIREWALL_RULES', 'Firewall Rules Fine-tuning');
define('FIREWALL_CONFIGURATION','Firewall Configuration');
define('FIREWALL_CONFIGURATION_DESC','This is the page where you can change the settings of '.OSE_WORDPRESS_FIREWALL.' Firewall.');
define('CRONJOBS', 'Schedule Tasks <small>(Premium)</small>');
define('CRONJOBS_TITLE','Scheduled Tasks');
define('CRONJOBS_DESC','Set up a scheduled task to automatically run at a specified day(s) and time. The time is based on the time of your system.');
define('CRONJOBS_LONG','Select the time and day(s) for Virus Scanner to run.');
define('HOURS','Hours');
define('WEEKDAYS','Week Days (Use Ctrl to multi-select items)');
define('CRON_SETTING_EMPTY','Please ensure you have selected both the hours and week days on the form.');
define('LAST_DETECTED_FILE','Folder of the files being added into the scanned queue in the last scan');
define('ENTER_ACTIVATION_CODE', 'Enter Activation Code');
define('ACTIVATE', 'Activate');
define('ERROR', 'Error');
define('ACTIVATION_CODE_EMPTY', 'Activation code cannot be empty');
define('MAX_DB_CONN', 'Maximum Database Connection');

// Version 4.4.0
define('PERMCONFIG', 'File Permissions Editor');
define('PERMCONFIG_DESC', 'Manage your server\'s  files & folders permissions configuration');
define('PERMCONFIGFORM_DESC', 'Please select the new attributes for the selected.');
define('PERMCONFIGFORM_NB', '<h5><small><b>NB: </b>Generally used permissions: Files 0644 (drw-r--r--) and Folders 0755 (drwxr-xr-x) </small></h5>');
define('PERMCONFIG_EDITOR', 'Edit Permissions');
define('PERMCONFIG_CHANGE', 'Change Permissions');
define('PERMCONFIG_SHORT', 'Permissions Configuration');
define('PERMCONFIG_NAME', 'Name');
define('PERMCONFIG_TYPE', 'Type');
define('PERMCONFIG_OWNER', 'Owner/Group');
define('PERMCONFIG_PERM', 'Permissions');
define('O_DOWNLOAD', 'Download');

// Version 4.4.1
define('CHOOSE_A_PLAN', 'Please choose a subscription plan');
define('SUBSCRIPTION_PLAN', 'Subscription plans');
define('SUBSCRIPTION_PLAN_EMPTY', 'Subscription plans cannot be empty, please choose at least one subscription plan.');
define('PAYMENT_METHOD', 'Payment Method');
define('COUNTRY', 'Country');
define('FIRST_NAME', 'First Name');
define('LAST_NAME', 'Last Name');
define('O_NEXT', 'Place Order');
define('PERMCONFIG_ONECLICKPERMFIX', 'One Click Permisions Fix <small>(Premium)</small>');

// Version 4.6.0
define('SCANPATH', 'Select Path');
define('PATH', 'Path');
define('FILETREENAVIGATOR', 'Directory Navigator');

// Version 4.7.0
define('EMAIL_EDIT', 'Edit Email');
define('O_GOOGLE_2_SECRET', 'Google authenticator secret');
define('O_GOOGLE_2_QRCODE', 'Google authenticator QRcode');
define('CENTRORA', ''.OSE_WORDPRESS_FIREWALL.'');
define('OSE', 'Open Source Excellence [OSE]');
define('WEBSITE', 'Website');
define('O_AUTHENTICATION', 'Authentication');
define('O_BACKUP_TITLE', 'Back Up Management');
define('DBNOTREADY_AFTER', 'After that, you can proceed to the Configuration page to change settings.');
define('SAVE_SETTINGS', 'Save Setting');
define('PHP_CHECK_STATUS', 'Checking Status');
define('RECURSE_INTO', 'Recurse into subdirectories');
define('APPLY_TO_ALL', 'Apply to all Files and Folders');
define('APPLY_TO_FILES', 'Apply to Files only');
define('APPLY_TO_FOLDERS', 'Apply to Folders only');
define('CLICK_TO_ACTIVATE', 'to activate your subscription and use this feature.');
define('SECURITY_BADGE_DESC', '<b>[Security Badge] </b>: The security badge is disabled now. You can increase sales conversion of your website by enabling it. ');
define('CALL_TO_ACTION_TITLE', 'We are always here to help');
define('CALL_TO_ACTION_P', 'We are now serving <span id="numofWebsite"></span> websites.');
define('CALL_TO_ACTION_UL', '<p>We love the intellectual thrill of cleaning malware and viruses and making sure that our software is able to block any attempt to bypass our security. Our degree-qualified team is always looking for innovative ways to improve our products as new malware and hacks evolve.</p>' . '
                    <p>Our goal is to take care of your website security so you can focus on growing your business. With our outstanding customer service and timely response, you can enjoy the peace of mind that comes with knowing we have got your back. </p>' . '
					<p>We have been helping thousands of customers to protect and clean their websites since 2009, if you need any help protecting or cleaning malware on your website, please feel free to contact us.</p></li>
					');
define('CALL_TO_ACTION_TITLE2', 'Urgent Hacking and malware help is here!');
define('CALL_TO_ACTION_DESC2', 'Need help? Contact us here.<br>
Centrora © 2017, a portfolio of Luxur Group PTY LTD<br>
Level 9, Melbourne Central Tower,<br>
360 Elizabeth Street,<br>
Melbourne, VIC, Australia, 3000.');
define('CALL_TO_ACTION_TITLE3', 'Need more help?');
define('CALL_TO_ACTION_DECS3', 'If you would like to add more protection, you can read');
define('SUBSCRIBE_NOW', 'SUBSCRIBE NOW!');
define('PLEASE_ENTER_CORRECT_EMAIL', 'Please enter the correct email.');
define('PASSWORD_DONOT_MATCH', 'The password should be identical');
define('LOGOUT', 'Log out');
define('O_DONT_BRACE', 'Please do not modify text within the braces');

// Version 4.8.0
define('CURRENT_DATABASE_CONNECTIONS','Current Database Connections');
define('WAITING_DATABASE_CONNECTIONS','Waiting database connections to be released.');
define('YOUR_MAX_DATABASE_CONNECTIONS','The Maximum Connections you have configured.');
define('SCHEDULE_BACKUP', 'Schedule Backup');
define('CRONJOBSBACKUP_LONG', 'Please select the backup type as well as day(s) and time you would like the backup to run.');
define('O_AUTHENTICATION_ONEDRIVE', 'OneDrive Authentication');
define('O_ONEDRIVE_LOGOUT', 'OneDrive Logout');
define('O_DROPBOX_LOGOUT' , 'Dropbox Logout');
define('CLOUD_BACKUP_TYPE', 'Cloud Backup Type');
define('O_BACKUP_ONEDRIVE','OneDrive');
define('NONE','Locally Only');
define('SAVE_SETTING_DESC','Click save settings everytime you make changes in the schedule');
define('CLOUD_SETTING_REMINDER','Authenticate other services to see more options');
define('OEM_PASSCODE', 'Passcode');
define('PASSCODE', 'Passcode');
define('VERIFY', 'Verify');
define('AUTHENTICATION', 'Classic Cloud Backup Authentication');
define('AUTHENTICATION_TITLE', '<b>Third Party Authentication</b>');
define('AUTHENTICATION_DESC', 'To enable cloud backup please authorise '.OSE_WORDPRESS_FIREWALL.' to your prefered cloud service.');

// Version 4.9.0
define('O_UPDATE_SIGNATURE', 'Update Firewall Signature');
define('RESTORE_EMAIL', 'Switch to new default template');
define('CHANGE_PASSCODE', 'Change passcode');
define('OLD_PASSCODE', 'Input old passcode');
define('NEW_PASSCODE', 'Input new passcode');
define('CONFIRM_PASSCODE', 'Confirm new passcode');
define('MY_ACCOUNT', 'My Premium');
define('LOGIN_OR_SUBSCIRPTION', 'Login/Subscription');
define('ADVANCED_FIREWALL_SETTINGS', 'Advanced Firewall Configuration');
define('BASIC_FIREWALL_RULES', 'Basic Firewall Rules');
define('ADVANCED_FIREWALL_RULES', 'Advanced Firewall Rules <small>(Premium)</small>');
define('O_GOOGLEDRIVE_LOGOUT', 'Google Drive Logout');
define('O_AUTHENTICATION_GOOGLEDRIVE', 'GoogleDrive Authentication');
define('O_AUTHENTICATION_DROPBOX', 'Dropbox Authentication');
define('O_BACKUP', 'Backup');
define('ADMINISTRATION', 'System Menu');
define('FILE_PERMISSION', 'File System Audit');
define('O_BACKUP_GOOGLEDRIVE', 'GoogleDrive');
define('DOWNLOAD_SUCCESS', 'Signatures are updated successfully');

// Version 5.0.0
define('O_FRONTEND_BLOCKING_MODE_403','Show a 403 error page');//previously O_SHOW_A_403_ERROR_PAGE_AND_STOP_THE_ATTACK
define('O_FRONTEND_BLOCKING_MODE_403_HELP','Show a 403 error page and stop the attack');
define('O_FRONTEND_BLOCKING_MODE_BAN','Show ban page');// previously O_BAN_IP_AND_SHOW_BAN_PAGE_TO_STOP_AN_ATTACK
define('O_FRONTEND_BLOCKING_MODE_BAN_HELP','Ban IP and show ban page to stop an attack');
define('O_ALLOWED_FILE_TYPES','Allowed upload file extensions*');
define('O_ALLOWED_FILE_TYPES_HELP',''.OSE_WORDPRESS_FIREWALL.' Firewall protects against untrusted file uploads. Use this list to add exceptions (e.g. jpg, png, doc) *Please note: FILEINFO module needs to be installed and configured properly');

define('O_SILENTLY_FILTER_ATTACK','Silent Mode');
define('O_SILENTLY_FILTER_ATTACK_HELP', 'Silently filter hacking values.  To enable this mode, you must have the setting â€œFrontend Blocking Modeâ€ set as â€œShow a 403 error pageâ€. Under this mode, the user will be redirected having the URL with the suspicious string trimmed. The IP will not be blocked and will be added into the Monitored IP list. This can avoid false positive detections in some cases. *Recommended for new users');
define('ATTACK_BLOCKING_THRESHOLD','Attack Blocking RS Threshold');
define('ATTACK_BLOCKING_THRESHOLD_HELP', 'Attack blocking risk score threshold (default: 35)');
define('SILENT_MODE_BLOCK_MAX_ATTEMPTS','Silent Mode Allowed Threshold');
define('SILENT_MODE_BLOCK_MAX_ATTEMPTS_HELP', 'Maximum attack attempts allowed for an IP in silent mode (default: 10)');

define('O_WEBMASTER_EMAIL_HELP','This Email address will be used to send Alert Emails from this installation of '.OSE_WORDPRESS_FIREWALL.'');
define('O_RECEIVE_EMAIL','Receive Update Email');
define('O_RECEIVE_EMAIL_HELP','Receive '.OSE_WORDPRESS_FIREWALL.' Firewall or SafeBrowsing Update Email');
define('O_STRONG_PASSWORD', 'Force Strong Password');
define('O_STRONG_PASSWORD_HELP', 'Use this to enforce the use of strong passwords for all users. A strong password incorporates the use of alphanumeric characters & symbols');
define('FIREWALL_HELP','When on, the Firewall is active. Turn this off to deacitivate the Firewall (NOT RECOMMENDED)');
define('O_FRONTEND_BLOCKING_MODE_HELP','Select the blocking mode type used by the Firewall when it\'s turned on');
define('O_GOOGLE_2_VERIFICATION_HELP','Use this to further protect against malicious Admin Login attempts. Turn on and link your wordpress user account under wordpress users!.');
define('O_SEO_PAGE_TITLE_HELP','This is the text you\'ll see at the top of your browser. Search engines view this text as the title of your ban page.');
define('O_SEO_META_KEY_HELP','A series of keywords relevant to the ban page.');
define('O_SEO_META_DESC_HELP','A brief description of the ban page.');
define('O_SEO_META_GENERATOR_HELP','Ban page CMS Generator');
define('O_CUSTOM_BAN_PAGE_HELP','This is the message shown to a Banned User. Custom Ban Page URL below overides this Message.');
define('O_CUSTOM_BAN_PAGE_URL_HELP','When this function is enabled, the attacker will be redirected to the URL as defined which replaces the Custom Ban Page as defined above.');
define('HOURS_HELP', 'Select the hour in the day you want the schedule to run. The hour is based on your timezone as shown e.g. GMT: 10 is Australia/Melbourne Timezone');
define('O_GOOGLE_2_SECRET_HELP', 'Use this code with the Google Authenticator browser plugin');
define('O_GOOGLE_2_QRCODE_HELP', 'Scan this QRCode with a smartphone which has the Google Authenticator App installed');
define('SEO_CONFIGURATION_HELP', 'The SEO Configuration here gives you control over your ban page SEO. This ensures your main site is not affected in search engine rankings.');
define('SEO_CONFIGURATION', 'Ban Page SEO');
define('O_STRONG_PASSWORD_SETTING', 'Strong Password Settings');
define('MPL', 'Minimum Password Length');
define('PMI', 'Password Minimum Integers');
define('PMS', 'Password Minimum Symbols');
define('PUCM', 'Password Upper Case Minimum');
define('RECOMMOND_PASSWORD', 'Recommend Settings');
define('RECOMMOND_JOOMLA', 'Joomla Default Settings');
define('COUNTRYBLOCK_HELP', 'This function allows you to block the IPs for specific countries. Please note that you need to Download the Country Database under the menu â€œFirewallâ€ â€“> â€œCountry Blockingâ€ first if you want to use this function.');
define('O_ADRULESETS_HELP', '');
define('CONFIG_ADRULES_FAILE', 'Save failed, \'Advanced Firewall Database\' is not ready, please update Firewall Signatures in Advanced Firewall Rules. <a href="' . UPDATE_ADFIREWALL_RULE . '">Update now</a>');
define('DEVELOPMODE_DISABLED','<b>[Firewall Activated] </b>Great! Your website is now protected by '.OSE_WORDPRESS_FIREWALL.'');
define('DISDEVELOPMODE', '<b>WARNING</b>: Please turn on the Firewall in the Firewall Scanning Configuration to activate the firewall protection.');
define('O_DELETE_ADMIN_SUCCESS', 'Successfully deleted the administrator account');
define('O_DELETE_ADMIN_FAIL', 'There are errors when deleting the administrator account');
define('AFFILIATE_TRACKING', 'Affiliate Program');
define('LOGIN_TITLE', 'Centrora Member Login');
define('LOGIN_DESC', 'You can login here with your Centrora Account or OSE Account to activate your premium services');
define('Git_backup_tittle', 'Centrora Git Backup');
define('Git_backup_desc', 'Git Backup is a brand-new and great potential tool for website backup and restore in seconds.');
define('HOW_TO_ACTIVATE', 'How to activate my premium service?');
define('SUBSCRIPTION_TITLE','Subscription Activation');
define('SUBSCRIPTION_DESC','Please select the subscription plan that you would like to link with this website.');
define ('NEWS_TITLE', '<b>What\'s New</b>');
define ('NEWS_DESC', 'Find out the latest news from Centrora');
define ('PASSCODE_TITLE', 'Passcode');
define ('PASSCODE_DESC', 'Please enter your passcode to access the administrator panel');

// Version 5.0.1
define ('O_LOGIN_PAGE_SETTING', 'Login Url');
//define ('CALL_TO_ACTION_P2', 'Website being hacked? Clean the malware with a free 6 month subscription PLUS 3 months warranty. <br/> <button class="btn btn-primary btn-sm" onClick ="location.href=\''.OSE_OEM_URL_MALWARE_REMOVAL.'\'">Leave the hard work to us now.</button>');
define('CALL_TO_ACTION_P2', 'Website being hacked? <br> Clean the malware for free with an annual subscription plus one year warranty. <br>Leave the hard work to us now.');
define ('FILE_UPLOAD_MANAGEMENT', 'Upload Control');
define('FILEEXTENSION', 'Upload Control');
define('FILEEXTENSION_TITLE', '<b>File</b> <span><b>Upload Control</b></span>');
define('FILEEXTENSION_DESC', ''.OSE_WORDPRESS_FIREWALL.' Firewall protects against untrusted file uploads. From this panel you can: <b>1)</b> Set the allowed files <b>2)</b> Keep track of uploaded files <b>3)</b> Track malicious upload attempts');
define('FILE_EXTENSION_LIST', 'File Extension List');
define('FILE_EXTENSION_LOG', 'File Uploading Log <small>(Premium)</small>');
define('O_VSSCAN_STATUS', 'Virus Scan Status');
define('O_IP_STATUS', 'File Validation Status');
define('O_FILETYPE', 'File type');
define('O_FILENAME', 'File name');
define('O_EXTENSION_ID', 'ID');
define('O_EXTENSION_NAME', 'Extension');
define('O_EXTENSION_TYPE', 'Type');
define('O_EXTENSION_STATUS', 'Status');
define('ADD_EXT', 'Add Extension');
define ('O_BACKEND_SECURE_KEY', 'Backend Access Secure Key <sup><span>(beta)</span></sup>');
define ('O_BACKEND_SECURE_KEY_HELP', 'You can harden the access to your backend by adding A Backend Access Secure Key. This key adds a layer of protection against access to the website backend. To deactivate, simply delete the content then save');
define('UPLOAD_FILE_403WARN', 'The upload of this file type is not allowed on this website. <br /> <br />If you are the server administrator, please allow this file type under Firewall -> Upload Control panel.');
define('BLOCKED_UPLOAD_LOG', 'Blocked');
define('INCONSISTENT_FILE', 'Inconsistent File! - IP Blocked');
define('PASSCODE_ENTRY', 'Passcode Entry');
define('PASSCODE_ENTRY_HELP', 'Require passcode to access other views exclude dashboard');
define('UNBAN_PAGE_GOOGLE_AUTH_DESC', 'If you have Unban Google Authenticator enabled and setup, please input your code here');

// Version 5.0.5
define('ADMIN_MANAGER', 'Administrator Manager');
define('SECURITY_MANAGER', 'Security Manager <sup><span>(beta)</span></sup>');
define('O_OUR_TUTORIAL', 'our tutorial here');
define('O_SUBSCRIBE_PLAN', 'to subscribe a plan');
define('SECURITY_NAME', 'Name');
define('SECURITY_USERNAME', 'Username');
define('SECURITY_EMAIL', 'Email');
define('SECURITY_STATUS', 'Status');
define('SECURITY_CONTACT', 'Contact');
define('ADD_SECURITY_MANAGER', 'Add Security Manager');
define('SECURITY_PASSWORD', 'Password');
define('SECURITY_PASSWORD2', 'Confirm Password');

// Version 5.1.0
define('CORE_SCAN', 'Core Directories Scanner <small>(Premium)</small>');
define('CORE_SCAN_TITLE', '<b>Core Directories </b> <span><b>Scanner</b>');
define('CORE_SCAN_DESC', 'Core directories Scanner is a neat and quick detector, it scans the core directories of your website and detects suspicious files. Please notice that this scanner only applies to joomla and wordpress.');
define('Vl_SCAN', 'Vulnerabilities Scanner');
define('Vl_SCAN_TITLE', '<b>Vulnerabilities Scanner</b>');
define('Vl_SCAN_DESC', 'Vulnerabilities Scanner is a powerful vulnerability detector. It scans through your website and detects any real vulnerabilities. Credits to WPScan Vulnerability.');
define('Vl_SCAN_CRED_WPSCAN', 'Credits to WPScan Vulnerability');
define('START_NEW_SCAN', 'Start Scanning');

define('JOOMLA_TWOFACTORAUTH', 'Two Factor Authentication - Google Authenticator');
define('JOOMLA_TWOFACTORAUTH_HELP', 'Allows users on your site to use two factor authentication using Google Authenticator or other compatible time-based One Time Password generators. To use two factor authentication please edit the user profile and enable two factor authentication.');
define('NO_HASHES_FOR_ALPHA', "Current Joomla! version is a non-stable version, we recommand you upgrade to the latest stable version. Hashes for non-stable version is not available. <strong>Centrora will update hashes once a new joomla stable version releases.</strong>");
define('BRUTEFORCE_SETTINGS', 'Brute Force Protection');
define('BRUTEFORCE_MAX_ATT', 'Maximum login attempts');
define('BRUTEFORCE_MAX_ATT_HELP', 'This will blacklist the IP address if the user exceeds the maximum login attempts.');
define('BRUTEFORCE_TIME', 'Time Period of counting login attempts');
define('BRUTEFORCE_TIME_HELP', 'This period is the time frame of counting login attempts');
define('BRUTE_FORCE_STATUS', 'Brute Force Protection Status');
define('BRUTE_FORCE_STATUS_HELP', 'Brute Force Protection will set a login attempts limit and time frame to ensure that hackers who try to brute force into your sites will be blocked');

define('VL_GET_LIST','Generating scan list...');
define('VL_COMPLETE','Scanning Complete with the result:');
define('VL_CALL_TOACTION', 'We highly recommend you update the following to the latest version immediately, or if you are no longer using it, remove it from your site. If your site has been compromised due to this vulnerability, <a style="color:white;" href="'.OSE_OEM_URL_MALWARE_REMOVAL.'" target="_blank" > we can help </a>.');

define('ADMIN_SETTINGS', 'Administrator Settings');
define('CENTRORA_GOOGLE_AUTH', 'Centrora Google Authenticator');
define('CENTRORA_GOOGLE_AUTH_HELP', 'Enable centrora google authenticator and scan the QR code, you can pass through the ban page or 403 forbidden page by inputting the correct google authentication code');
define('UPLOAD_FILE_403WARN2', 'You are uploading a suspicous file(file content <strong>does not</strong> match file extension). <br /> <br />If you are the server administrator, please notice that this is a suspicious file.');
define('SETSCANPATH', 'Set Scan Path');
define('SURF_SCAN', 'MD5 Hash Scanner');
define('SURF_SCAN_TITLE', '<b>MD5 Hash Scanner</b>');
define('SURF_SCAN_DESC', 'MD5 Hash Scanner checks for all known viruses and malware. It is recommended that if nothing is detected you use the Dynamic Scanner');
define('DEEPSCAN', 'Dynamic Scanner <small>(Premium)</small>');
define('SURF_SCAN_SIG_UPDATED', 'Your MD5 Hash Scanner signatures have been updated!');
define('SURF_SCAN_SIG_UPTODATE', 'Great! Your MD5 Hash Scanner signatures are up-to-date.');
define('SURF_SCAN_SIG_NOTUPTODATE', 'Updating Your MD5 Hash Scanner signatures.');
define('SURF_SCAN_CALL_TOACTION', 'We highly recommend you review the files listed immediately! If your site has been compromised due to the malicious file(s), <a href="'.OSE_OEM_URL_MALWARE_REMOVAL.'" target="_blank" > we can help</a>.');

// Version 6.0.0
define('FILE_PERM_SCAN', 'File Permissions Scanner');
define('FILE_PERM_SCAN_TITLE', '<b>File Permissions Scanner</b>');
define('FILE_PERM_SCAN_DESC', 'File Permissions Scanner can detect files with insecure file permission. You can set base file/folder permissions, the scanner will scan for files and folders that have higher permissions than given base permissions. ');
define('CLEAR_BACKUP_TIME', 'Clean old backups');
define('LAST_ONE_WEEK', 'Keep last one week');
define('LAST_TWO_WEEK', 'Keep last two weeks');
define('LAST_THREE_WEEK', 'Keep last three weeks');
define('LAST_FOUR_WEEK', 'Keep last four weeks');
define('LAST_TWO_MONTH', 'Keep last two months');
define('LAST_THREE_MONTH', 'Keep last three months');
define('LAST_HALF_YEAR', 'Keep last half year');
define('LAST_FOREVER', 'Keep all backups');
define('O_BK_TAB_BACKUPS', 'Backups');
define('O_BK_TAB_NEW_BACKUP', 'Create New');

define('SYMLINK', 'Scan symbolic link');
define('MF_SCAN', 'Modified Files Scanner');
define('MF_SCAN_TITLE', '<b>Modified Files Scanner</b> <sup><span>(beta)</span></sup>');
define('MF_SCAN_DESC', 'The Modified Files Scanner can detect modified files within a certain time period and files which are symbolic links.');
define('O_RESTORE_TEST', 'Restore testing button');
define('CLEAR_BLACKLIST_URL', 'Clear Blacklist Cronjob Url (See <a href ="' . OSE_OEM_URL_ADVFW_TUT . '" target=\'_blank\'>Tutorial Here</a>)');
define('CATCH_VIRUS_MD5', 'Update Virus MD5');
define('O_BACKUP_ACTION', 'Action');
define('FPSCAN_CALL_TOACTION', 'Please review the modified files listed.');
define('FILE_UPLOAD_LOG', 'Passed');
define('BLOCKED_SUS_UPLOAD_LOG', 'Suspicious upload attempt! - IP Blocked');
define('O_SCANREPORT_MARKASCLEAN', 'WhiteList');
define('UPLOAD_FILE_403WARN3', 'You are uploading a suspicous file to a not existing url. <br /> <br />If you are the server administrator, please notice that this is a suspicious file.');
define('OVERVIEW_TRAFFICS', 'Traffic Overview in last 24 hours');
define('AI_SCANNER', 'AI Scanner');
define('OVERVIEW_COUNTRY_MAP_BTN', 'Hacking Overview');
define('OVERVIEW_TRAFFICS_BTN', 'Traffic Overview');
define('RECENT_SCANNING_RESULT_BTN', 'Recent Scanning');
define('RECENT_HACKING_INFO_BTN', 'Recent Hacking');
define('RECENT_BACKUP_BTN', 'Recent Backup');
define('AI_SCAN_TITLE', 'AI Scanner');
define('AI_SCAN_DESC', 'AI Scanner, internal use only');

//brief description for subscription
define('COUNTRYBLOCK_DESC_BRIEF', '<b>Blocking countries</b> with high spam scores will save you bandwidth and reduce the chance of being hacked.  Country Blocking Panel allows you to block specific countries in our feature rich web application firewall.');
define('ANTIVIRUS_DESC_BRIEF', 'Though viruses keep changing, the <b>Dynamic Virus Scanner</b> detects virus files based on our daily updated signatures, fast and accurately !');
define('CORE_SCAN_DESC_BRIEF', '<b>Core Directory Scanner </b>checks the core files against the standard package to dig out suspicious files quickly.');
define('SCANREPORT_DESC_BRIEF', '<b>Detail report</b> of virus detected with the ability of batch malware removal, backup and quarantine.');
define('CRONJOBS_DESC_BRIEF', '<b></b>Allows you to leave the machine do the routine maintenance tasks so you can focus on your core business.');
define('AUTHENTICATION_DESC_BRIEF', 'Make the best use of free resources from <br><b>Dropbox, Google and Microsoft Drive</b>. <br>Store the files in the cloud drives.');
define('FIREWALL_CONFIGURATION_DESC_BRIEF', 'With <b>'.OSE_WORDPRESS_FIREWALL.'</b>, the basic firewall we have already helped you block over 95% of threats. To further harden your web application firewall you might consider an additional layer of protection with the advanced firewall rules, feel free to subscribe.');
define('MANAGERULESETS_DESC_BRIEF', 'We highly recommend to activate all of the security functions to carry the best out of '.OSE_WORDPRESS_FIREWALL.'');

//slogan for subscription
define('COUNTRYBLOCK_DESC_SLOGAN', 'Simply subscribe to a plan and stop spammers traffic<br> from a specific country immediately.');
define('ANTIVIRUS_DESC_SLOGAN', 'Dig out the well-hidden virus / malicious codes in your website within minutes.<br>'. OSE_WORDPRESS_FIREWALL .' Dynamic Virus Scanner can help you');
define('CORE_SCAN_DESC_SLOGAN', 'Detect underlying malware and modified core files more efficiently<br> by checking current core files against the original ones.');
define('SCANREPORT_DESC_SLOGAN', 'Review, clean, quarantine and delete malware and malicious codes,<br> get your site back on track within minutes.');
define('CRONJOBS_DESC_SLOGAN', 'Set up schedule tasks for automatic Virus Scanning and Backup.');
define('AUTHENTICATION_DESC_SLOGAN', 'Save your backups remotely with Cloud Backup.');
define('FIREWALL_CONFIGURATION_DESC_SLOGAN', 'Enhance your website security by adding an Advanced Web Application Firewall <br>Get your websites <b>malware-free</b>.');
define('MANAGERULESETS_DESC_SLOGAN', 'Fine-tune the firewall rules to better suit your online business!');

define('O_EXPORT_INFECTED_CSV', 'Export infected files to CSV');
define('IMPROVE', 'Improve');
define('SCHEDULE', 'Schedule');
define('PROTECT', 'Protect');
define('UPDATE',"Update");
define('FIREWALLSETINGS',"Firewall Settings");
define('LOGS',"Logs");
define('WEBATTACTS',"Web Attacks");
define('BRUTEFORCE',"BruteForce");
define('FILEUPLOADINGLOGS',"File Uploading Logs");
define('VIRUSSCAN',"Virus Scan");
define('MANAGE', 'Management');
define('FILEPERM_EDITOR', 'File Permissions Editor');
define('SCHEDULETASKS',"Schedule Tasks");

define('BACK_TO_JOOMLA', 'Back to Joomla');

// 6.1.0
define('ACTIVATE_SPECIFIC_DOMAIN', 'Activate');
define('MAX_EX_TIME', 'Maximum Execution Time');
define('GIT_ID', 'Backup ID');
define('GIT_DATE', 'Backup Time');
define('SR_NO', "No");
define('HEAD', 'Current Copy');
define('GIT_MESSAGE', 'Backup Description');
define('GIT_ROLLBACK', 'Restore');

//6.2.0
define('GITBACKUP', 'Git Backup<sup><span>(New)</span></sup>');
define('CRONJOBS_GITBACKUP','Select how often git backup to run.');
define('CRONJOBS_FREQUENCY','Frequency');
define('CRONJOBS_FREQUENCY_HELP','Select the frequency you want the schedule git backup to run.');
define('SCHEDULE_GITBACKUP', 'Schedule Git Backup' );
define('BITBUCKET_ACC' , 'GitLab Account');
define('GITCREMOTE_USERNAME', 'Username');
define('GITCREMOTE_PASSWORD', 'Password');
define('TOP_UPTODATE', 'Up to Date');

define('CREATE_REPOSITORY', 'Create a private repository in Bitbucket');
define('CREATE_REPOSITORY_GITLAB', 'Create a private repository in GitLab');

define("O_ENABLED_IPV6", "Enable IPv6");
define("COMMIT_MESSAGE", "Backup Description");
define("SUBMIT_COMMIT_MSG", "Backup Now");
define("ZIP_DOWNLOAD","Download");
define("COMMIT","Backup");
define("RECOMMENDATION_COMMIT", "The system has detected some unsaved changes, It is highly recommended to do a backup first");

//firewall configuratiuon V7
define("FIREWALLV7", "Firewall Scanner V7 <sup><span>(New)</span></sup>");
define("FIREWALLV7_ OPTIONSMSG", "Please choose the type of attacks you want to enable in firewall scanning ");
define("SELECT_ALL", "SELECT ALL");
define("DESELECT_ALL", "CLEAR ALL SELECTIONS");

define("FILE_UPLOAD", "File Upload");

define("IP_MANAGEMENT","IP MANAGAMENT");
define("FIREWALL_V7", " Firewall Scanner V7");
define("WHITELISTMGMT","WHITE LIST MANAGEMENT");
define("LOAD_DEFAULT_WHITELIST", "Load default variables and Strings");
define("ADD_ENTITY","Add an Entity");
define("ENTITY_NAME","Entity Name");
define("ENTITY_TYPE","Entity Type");
define("REQUEST_TYPE","Request Type");
define('O_WHITELISTVARIABLES',"Whitelisted(Choose this option if you trust certain variables or strings)");
define('O_FILTERVARIABLES',"Filtered(This option will remove malicious content from the request and will provide non malicious request )");
define('O_SCANVARIABLES',"Actively Scanned(Choose this option if you want the system to scan a specific variable and string)");
define('O_IMPORT_VARIABLES','Import Variables from Firewall version 6.6');



//ipmanagement page form to show attack information
define("ATTACK_INFORMATION","ATTACK INFORMATION");
define('IP_ADDRESS','IP ADDRESS');
define("Date","DATE");
define("TYPE_OF_ATTACK","TYPE OF ATTACK");
define("STRING_DETECTED","MALICIOUS PATTERN");
define("SCANNED_VARIABLES","SCANNED REQUEST");
define("FILES_UPLOAD_REQUEST","FILE UPLOAD REQUEST");
define("SCORE","SCORE");
define("ATTEMPTS","ATTEMPTS");
define('COUNTRYBLOCKV7', 'Country Blocking Version 7<small>(Premium)</small>');
define('AUDITV7','AUDIT V7');
define('BANPAGE','BAN PAGE MANAGEMENT');

define('STATS', 'STATISTICS PAGE');
define('SYSTEMSTATS','System Statistics');

define('EMAILNOTIFICATION','Email Notification Management');
define('SSL','FREE SSL');
define('SSL_PAGE_TITLE','FREE SSL');
define('SSL_PAGE_MENU_TITLE','Free SSL Certificate');

//SSL
define('CONTACT_SUPPORT',"<br/>".'Please contact Centrora support team at support@centrora.com to address this issue');

define('O_SUBSCRIPTION','Subscription Plan');
define('O_SUB_STATUS','Subscription Status');
define('O_START_DATE','Start Date');
define('O_EXPIRE_DATE','Expired Date');

define('O_ACCOUNTNAME','Account Name');
define('O_LASTBACKUP_DATE','Last Backup Date');
define('O_BACKUP_GIT','Backup');
define('O_DOWNLOAD_BACKUP','Download Backup');
define('O_UPLOADTOCLOUD','Upload to Cloud');


//ADD Missing for 7.0.0
define('ACTIVATION_CODE','Activation Codes');
define('ACTIVATION_CODE_DESC', 'This Panel shows the activation codes of the firewall in the php.ini or .htaccess for the whole server.');
define('ADMIN_MANAGEMENT','Administrator Management');
define('ADMIN_MANAGEMENT_DESC', 'You can centrally manage your administrator and domain addresses here.');
define('CLOSE','Close');
define('ACTIVE','Active');
define('INACTIVE','Inactive');
define('AI_SCAN','AI scanner');
define('PATTERNS','Patterns');
define('STATUS','Status');
define('LAST_BATCH','Last Batch');
define('RESET_SAMPLES','Reset Samples');
define('AI_ANALYSIS','AI Analysis');
define('CONTENT_ANALYSIS','Content Analysis');
define('ADD_PATTERN','Add Pattern');
define('DELETE_PATTERN','Delete Pattern');
define('PATTERN_ID','Pattern ID');
define('PATTERN_NAME','Pattern Name');
define('PATTERN_TYPE','Pattern Type');
define('PATTERN','Pattern');
define('TYPE','type');
define('FIX_IT','Fix it');
define('NOTE','Note');
define('FIREWALL_CONDIGURATION','Firewall Configuration');
define('AUDIT_MY_WEBSITE','Audit My Website');
define('AUDIT_MY_WEBSITE_DESC','Auditing my website here, this features will be renovated and improved in next version.');
define('BACK_TO_ADVANCE_SETTING_ADV','Back to Advance Settings');
define('BACK_TO_ADVANCE_SETTING','Back to Firewall Control Panel');
define('THIRD_PARTY_AUTH','Third Party Authentication');
define('THIRD_PARTY_AUTH_DESC','To enable cloud backup please authorise Centrora Security™ to your prefered cloud service.');
define('THIRD_PARTY_AUTH_SELECT','Select one type below to run.');
define('CENTRORA_PREMIUM','Schedule your scanning and update with Centrora Premium');
define('CENTRORA_PREMIUM_NOW','Now');
define('All_RIGHTS_CENTRORA','Centrora 2016 a portfolio of Luxur Group PTY LTD,  All rights reserved.');
define('LEAVE_THE_WORK','leave the work to us now');
define('BACKUP_MANAGEMENT','Backup Management');
define('CENTRALLY_MANAGING_BACKUP','Centrally managing database and files backup. ( This is a classic backup function, we recommend you to use Gitbackup instead as it saves more website space )');
define('SCHEDULED_BACKUPS','Scheduled Backups');
define('BACKUP_LIST','Backup List');
define('SETUP','Setup');
define('CONFIG','Configuration');
define('BUILD','Build');
define('BACKUP_STEP1','Step 1: Setup Backup');
define('BACKUP_PREFIX','Backup Prefix');
define('ARCHIVE','Archive');
define('FILES','Files');
define('DATABASE','DataBase');
define('PLATFORMS','Platforms');
define('LOCAL','Local Only');
define('DROPBOX','DropBox');
define('ONEDRIVE','OneDrive');
define('GOOGLEDRIVE','GoogleDrive');
define('BUILD_BACKUP','Build Backup');
define('BUILDING_BACKUP','Building Backup');
define('SUCCESSFUL','Successful');
define('BACKUP_NAME','Backup Name');
define('BAN_PAGE','Ban Page');
define('BAN_PAGE_DESC','Customize your ban page here.');
define('MANAGE_IP','View the attack information here ');
define('FIREWALL_OVERVIEW','Firewall Control Panel');
define('FIREWALL_OVERVIEW_DESC','To setup basic firewall settings and manage logs / IP rules');
define('ADVANCE_SETTING','Advance Settings');
define('WIZARD','Wizard');
define('MENU_IP_MANAGEMENT','IP Management');
define('FIREWALL_STATISTICS','Firewall statistics');
define('EMAIL_REPORTS','Email Reports');
define('ADV_RULES_VERSION','Advanced Rules Version');
define('ADV_RULES_UPTODATE','Adv. Rules: Upto Date');
define('FIREWALL_CONFIG_DESC','Overview of the basic firewall settings');
define('FIREWALL_STATUS','Firewall status');
define('FIREWALL_STATUS_1','Firewall status in currently on');
define('FIREWALL_STATUS_2','OFF mode');
define('FIREWALL_STATUS_3',', to activate settings, please Click ON below');
define('V7_HELP_US','Firewall V7 is in beta version, Help us to improve the quality of the product by emailing bugs at ');
define('ANTI_SPAM','Anti Spam');
define('CHECK_USER_AGENT','Check User Agent');
define('BRUTEFORCE_PROTECTION','Bruteforce Protection');
define('FILE_UPLOAD_CONTROL','File Upload Control');
define('SCAN_REQUEST_SETTING','Scan Request Settings');
define('SECURITY_LEVEL_1','Security');
define('SECURITY_LEVEL_2','Level');
define('ON','ON');
define('OFF','OFF');
define('WEB_ADMIN_EMAIL','Web Admin Email');
define('INSENSITIVE','insensitive');
define('MODEL','Model');
define('SET_NUMBER_OF_ATTEMPTS','Set Number of attempts');
define('SET_NUMBER_OF_ATTEMPTS_DESC','Combination of firewall sensitivity and Attempt counts will be used as a criteria to determine whether to block an attacker or not');
define('LOGGING','LOGGING(Only logs attacks)');
define('BLOCKING','BLOCKING');
define('FILTERING','FILTERING');
define('BRUTE_FORCE_PROTECTION_STATUS','Brute force protection status');
define('MAX_LOGIN_ATTEMPTS','Maximum login attempts');
define('PERIOD_FOR_COUNTING_LOGIN_ATTEMPTS','Period for counting login attempts');
define('MINS','minutes');
define('HR','hour');
define('HRS','hours');
define('DAY','day');
define('GOOGLE_AUTH_DESC','Google Authentication is currently in ');
define('GOOGLE_AUTH_DESC_1','off mode');
define('GOOGLE_AUTH_DESC_2',', Please enable \'Google Authentication\' and ');
define('GOOGLE_AUTH_DESC_3','SAVE');
define('GOOGLE_AUTH_DESC_4','First');
define('SHOW_QR','Show QR code');
define('GOOGLE_VERIFICATION','Google Verification');
define('XSITE_REQUEST_FORGERY','Cross site request forgery');
define('XSITE_SCRIPTION','Cross site scription');
define('SQL_INJECTION','SQL Injection');
define('REMOTE_FILE_INCLUSION','Remote file inclusion');
define('LOCAL_FILE_INCLUSION','Local file inclusion');
define('FORMAT_SRTING_ATTACK','Format string attack');
define('LOCAL_FILE_MODI_ATTEMPT','Local file modification attempt');
define('DIRECTORY_TRAVERSAL','Directory traversal');
define('VALIDATE_UPLOAD_FILES','Validate Upload Files');
define('SCAN_VIRUS_FILES','Scan Virus Files');
define('FIREWALL_DESC1','Centrora Security™ Firewall protects against untrusted file uploads. Use this list to add exceptions (e.g. jpg, png, doc) *Please note: FILEINFO module needs to be installed and configured properly.');
define('FIREWALL_DESC2','Centrora Security Firewall utilize the built-in malware scanner to scan all uploaded files for malicious codes. Any uploaded malware will be blocked immedialy once detected.');
define('SAVE_BY_CLICK','Please click save button here to save your changed settings');
define('VIEW_SETUP_ADVANCED_FEATURES','Setup the advance features and services here');
define('TITLE_SEO_CONFIGURATION','SEO Configuration');
define('FILE_UPLOAD_LOGS','File Upload Logs');
define('FILE_UPLOAD_LOGS_DESC','This panel shows the the list of files with invalid file types or malware.');
define('FILE_EXTENSION_CONTROL_TABLE','File Extension Control Table');
define('WHITE_LIST_MANAGEMENT','Variable Management');
define('COUNTRY_BLOCKING','Country Blocking');
define('BAN_PAGE_MANAGEMENT','Ban Page Management');
define('ENABLE_VALIDATE_UPLOAD_FILES_DESC1','Plesase enable ');
define('ENABLE_VALIDATE_UPLOAD_FILES_DESC2','Validate Upload files');
define('ENABLE_VALIDATE_UPLOAD_FILES_DESC3',' setting in ');
define('ENABLE_VALIDATE_UPLOAD_FILES_DESC4','Firewall Overview');
define('ENABLE_VALIDATE_UPLOAD_FILES_DESC5',' page, then you can access this table. ');
define('ADD_FILE_EXTENSION','Add file extension');
define('FIREWALL_EASY_SETUP','Easy set up here for Firewall Configuration');
define('BF','Brute Force');
define('BF_SETUP','Brute Force setups');
define('ANTI_SPAMMING','Anti Spamming');
define('ANTI_SPAMMING_DESC','There are numberous infected bots out there which might try to access your website and slow down the website by bombarding the website with malicious requests, block this spammers by enabling this feature and provide faster services to your customers by improving the speed of your website.');
define('FILE_UPLOAD_PROTECTION','File Upload Protection');
define('FILE_UPLOAD_PROTECTION_DESC','According to our experts, the easiest way to hack a website is by uploading a malicious file to the website. Leave this tedious work to Centrora and we will make sure no malicious files are uploaded to your website.');
define('REQUEST_SCANNING','Request Scanning');
define('REQUEST_SCANNING_DESC','This feature basically acts as an Anti Virus on your website and detects all the malicious requests sent by the users. This feature will protect your websites against attacks like:');
define('REQUEST_SCANNING_DESC1','Enable this feature and make your website secure against malicious requests.');
define('EMAIL_FIREWALL_SENSITITY','Email and Firewall Sensitity');
define('BLOCK_FILTER','Block & Filter');
define('COMPLETE','Complete');
define('BRUTEFORCE_SETTINGS_DESC','Hackers can attack your websites by guessing or trying out various combinations of username and password with bots. Protect the login portal on your website by enabling this feature.');
define('BRUTEFORCE_SETTINGS_SETUP','Brute Force Protection Setups');
define('BRUTEFORCE_SETTINGS_SETUP_DESC','Please select the attempts threshold after which an  attacker will be blocked :');
define('TIME_FRAME','Please select the time frame for which the the tracks will be tracked, If the number of attacks performed by attacker in the selected time exceeds the threshold, he/she will be blocked:');
define('GOOGLE_AUTH','Google Authentication');
define('GOOGLE_AUTH_DESC5','will heavily guard your website against the brute force attacks.To use this feature please download the Google Authentication app from the app store and scan the QR Code from the user page of the website.Use the code from the mobile application to sign into the website.');
define('HIGHLY_RECOMMENDED','Highly Recommended');
define('XSITE_SCRIPTING','Cross Site Scripting');
define('SET_EMAIL','Set email address to receive statistics of your website.');
define('SET_EMAIL_DESC','Please enter your email address, Centrora will send detailed statistics related to the security of the website along with the graphs to the email address provided.');
define('FIREWALL_SENSITIVITY','Firewall Sensitivity');
define('FIREWALL_SENSITIVITY_DESC','The system calculates the score of the attack based on the request from the users, firewall sensitivity helps the system to set a threshold till which it will provide the services to the users.');
define('FIREWALL_SENSITIVITY_DESC1',' Warning: ');
define('FIREWALL_SENSITIVITY_DESC2',' Higher sensitivity will have a higher chance of blocking an user with low attack score');
define('FIREWALL_SENSITIVITY_OPTION1','Insensitive');
define('FIREWALL_SENSITIVITY_OPTION2','Moderate');
define('FIREWALL_SENSITIVITY_OPTION3','Sensitive');
define('FIREWALL_SENSITIVITY_OPTION4','Very sensitive');
define('FIREWALL_SENSITIVITY_OPTION5','Highly sensitive');
define('FIREWALL_MODE','Firewall Mode');
define('FIREWALL_MODE_DESC','We provide two modes for the firewall:');
define('FIREWALL_MODE_DESC1','Block Mode');
define('FIREWALL_MODE_DESC2','Filter Mode');
define('FIREWALL_MODE_DESC3','Sanitise the malicious request');
define('PREMIUM_USER','Premium user only');
define('FIREWALL_MODE_DESC4','Blocks an user if the attack score exceeds the threshold');
define('FIREWALL_MODE_DESC5','Does not blocks an user instead keeps on filtering the request');
define('FIREWALL_MODE_DESC6','Click on the Enable Button to enable the BLOCK MODE or the Disable Button to enable FILTER MODE');
define('FIREWALL_MODE_DESC7','We provide three modes for the firewall:');
define('FIREWALL_MODE_DESC8','Does not sanitise the malicious request');
define('FIREWALL_MODE_DESC9','Logging Mode');
define('FREE','Free');
define('FIREWALL_MODE_DESC10','Does not blocks nor filters the request');
define('ENABLE_LOGGING_MODE','Click on  ');
define('ENABLE_LOGGING_MODE1','Enable Button');
define('ENABLE_LOGGING_MODE2',' to enable  ');
define('ENABLE_LOGGING_MODE3','Loggin MODE');
define('COMPLETED_BASIC_SETTING',' Congratulations you have completed the basic settings for your website');
define('COMPLETED_BASIC_SETTING_DESC1','Not satisfied with just the basic settings, Do you want to make you website absolutely impenetrable to the hackers.Please click on the Advanced Settings button to continue the wizard.');
define('COMPLETED_BASIC_SETTING_DESC2','Warning : if you click on the save basic settings, the advanced options for the settings will be disabled');
define('SAVE_BASIC_SETTING','Save Basic Settings');
define('GO_ADVANCE_SETTING','Go to advance settings');
define('STEP','Step');
define('WHITE_LIST','White List');
define('SEARCH_ENGINE','Search engine');
define('COMLETE_ADVANCE_SETTING','Complete Advance settings');
define('ADVANCE_SETTING_WHITELIST_DESC','Advance Settings: Choose Whitelist String and Variables');
define('ADVANCE_SETTING_WHITELIST_DESC1','This feature will not scan the variables or strings that are marked as whitelisted and will reduce the false alerts');
define('ADVANCE_SETTING_WHITELIST_DESC2','Migrate Whitelisted Variables from Firewall Scanner Version 6');
define('ADVANCE_SETTING_WHITELIST_DESC3','Use default whitelisted string and variables');
define('SKIP','Skip');
define('ADVANCE_SETTING_BOTS_SECANNING','Advance Settings: Search Engine Bots Scanning');
define('ADVANCE_SETTING_BOTS_SECANNING_DESC','Hackers can pretend to be search engine bots and might try to access services from your websites.By default the system will not scan the request from bots for malicious request , you can increase the security level by enabling the search engine bots scanning.');
define('ENABLE','Enable');
define('DISABLE','Disable');
define('FIREWALL_WIZARD_COMPLETED','Congratulation the firewall setup wizard has been completed');
define('FIREWALL_WIZARD_COMPLETED_DESC','The firewall provides a high level of customisation to its user.');
define('FILE_UPLOAD_MANAGEMENT2','File Upload Management');
define('BAN_PAGE_CUSTOM','Ban page Customisation');
define('COUNTRY_BLOCKING_DESC',': This feature will allow you to block  users from certain countries.');
define('FILE_UPLOAD_MANAGEMENT2_DESC',': This feature allows you to restrict the file types that can be uploaded by the user.');
define('BAN_PAGE_CUSTOM_DESC',': This feature allows you to design a custom ban page which will be visible to the users who are banned.');
define('FINISH','Finish');
define('BACK','Back');
define('ALLOWED','Allowed');
define('FORBIDDEN','Forbidden');
define('INSERT_DETAILS','Please Insert the following details : ');
define('0_DB_NAME', 'Database Name');
define('0_DB_USER', 'User Name');
define('0_DB_PASWD', 'Database Password');
define('0_DB_HOST', 'Database Host');
define('0_TABLE_PREFIX', 'Table Prefix');
define('CHECKING_ALL_FIREWALL','This features provides a detailed graphical representation of the detected attacks');
define('TIME_ANALYSIS','Time Analysis');
define('SUMMARISING_NUMBERS_OF_ATTACKS_BY_TIME','summarising numbers of attacks to your website by time');
define('BACK_TO_STATISTIC_CURRENT_MONTH','Back to statistic for current Month');
define('NO_ATTACKS','Congratulations! No attacks were detected on the website');
define('ATTACK_TYPES','Attack Types');
define('SUMMARISING_NUMBERS_OF_ATTACKS_BY_TYPE','summarising numbers of attacks to your website by Attacking type');
define('IP_INFO','IP Information');
define('SUMMARISING_NUMBERS_OF_ATTACKS_BY_IP','summarising numbers of attacks by IP address');
define('MOST_COMMON_BROWSERS','Most common browsers for hackers');
define('CORE_DIRECTORIES_SCANNER','Core Directories Scanner');
define('CORE_DIRECTORIES_SCANNER_DESC','Core directories Scanner is a neat and quick detector, it scans the core directories of your website and detects suspicious files. Please notice that this scanner only applies to joomla and wordpress.');
define('CHECK_FILE_FULL_PATH','Check file full path');
define('INSTALLATION_TITLE','Installation');
define('DATABASE_TABLE_INSTALL_OR_UNINSTALL','Database tables install or uninstall here.');
define('INSTALL_AND_UNINSATALL','install & uninstall');
define('READY','Ready');
define('INCREASE_SECURITY_BY_BLOCK_UNWANTED_COUNTRIES','Increase the website security level by enabling the Country Blocking to block accesses from the unwanted countries. ');
define('PANEL_BLOCK_IP','The Panel to Block IPs from countries.');
define('SUN','Sunday');
define('MON','Monday');
define('TUE','Tuesday');
define('WED','Wednesday');
define('THU','Thursday');
define('FRI','Friday');
define('\'','Saturday');
define('DYNAMIC','dynamic');
define('CLASSIC','classic');
define('GUIDE','Guide');
define('SETUP_EMAIL_NOTIFICATION','With the feature the site admin can stay up to date with the firewall status and regular statistical reports');
define('ALL_EMAIL_SEND_TO','All the emails will be sent to');
define('CHANGE_EMAIL_ADDRESS_BACK','To change your email address , please go back to  ‘Firewall Overview’');
define('EMAIL_ALERT_DESC','Please choose the events for which you will like to receive email alerts');
define('IP_BLOCK','IP Block');
define('EMAIL_MODE_DESC','If you have enabled Filtered Mode an email will be sent when the first attack is detected or if you have Blocked Mode Enabled an email will be sent when an IP is blocked. The email will contain all the details of the attack(s).');
define('GOOGLE_AUTH_LOGIN_CODE','Google Authentication Login code');
define('GOOGLE_AUTH_LOGIN_CODE_DESC','Whenever you set up google authentication, the system will send you an email with the QRCode so that you don’t need to worry about losing this code.Scan the QR Code using the Google Authenticator app and use the code from the app to login');
define('STATISTICS','Statistics');
define('STATISTICS_DESC1','This feature will send you a detailed graphical analysis of all the attacks performed on the website');
define('STATISTICS_DESC2','Please download the Country Databse from the country blocking page in FirewallV7>Advanced Settings>Country Blocking');
define('STATISTICS_DESC3','For Premium Users Only : This feature will send you a detailed graphical analysis of all the attacks performed on the website');
define('SELECT_DATA_TO_RECEIVE','Please select data you want to receive.');
define('GEO_IP_ANALYSIS','Geo/IP Analysis');
define('BROWSER_ANALYSIS','Browser Analysis');
define('SELECT_REPORT_FREQUENCY_CYLCE','Please choose report frequency cylce.');
define('DAILY','Daily');
define('WEEKLY','Weekly');
define('FORNIGHTLY','Fornightly');
define('MONTHLY','Monthly');
define('SELECT_REPORT_FREQUENCY_CYLCE_DESC_DAILY','An email will be sent daily with the stat type that you have selected.');
define('SELECT_REPORT_FREQUENCY_CYLCE_DESC_WEEKLY','An email will be sent every 7 days with the stat type that you have selected.');
define('SELECT_REPORT_FREQUENCY_CYLCE_DESC_FORNIGHTLY','An email will be sent every 14 days with the stat type that you have selected.');
define('SELECT_REPORT_FREQUENCY_CYLCE_DESC_MONTHLY','An email will be sent every 30 days with the stat type that you have selected.');
define('BASE_FILE_PERMISSION','Base file permission');
define('BASE_FOLDER_PERMISSION','Base folder permission');
define('FILE_SCANNED','Files Scanned');
define('INSECURE_PERMISSION_FILE','Insecure permission Files');
define('SCAN_PATH','Scan Path');
define('INSECURE_PERMISSION_FILES_DETECTED','Insecure permission Files Detected!');
define('PERMISSION','Permission');
define('MODE','Mode');
define('OWNER','Owner');
define('GROUP','Group');
define('PUBLIC','Public');
define('READ','Read');
define('WRITE','Write');
define('EXECUTE','Execute');
define('CREATE_LOCAL_BACKUP','Create local backup');
define('PUSH_BACKUP_TO_CLOUD','Push backup to cloud');
define('CURRENT_COLUMN','Current Version Column');
define('CURRENT_COLUMN_DESC','This column shows the current Git head in your Git Repository,  which indicates in which backup version your website is currently at.');
define('RESTORE_COLUMN','Restore Column');
define('RESTORE_COLUMN_DESC','This column shows the current Git head in your Git Repository,  which indicates in which backup version your website is currently at.');
define('PREMIUM_FEATURE','This is a Premium Feature');
define('PREMIUM_FEATURE_INCLUDES','Premium features includes');
define('PREMIUM_FEATURE_DESC1','Free Malware Removal Service for annual subscribers until the website is 100% clean.');
define('PREMIUM_FEATURE_DESC2','View infected files in detail by browsing source codes with suspicious codes highlighted.');
define('PREMIUM_FEATURE_DESC3','Clean or quarantine infected files within the scan report without accessing FTP.');
define('PREMIUM_FEATURE_DESC4','Monitor website malware with scheduled automatic virus scanning and email notifications.');
define('PREMIUM_FEATURE_DESC5','Automated backup to GitLab every 1 hour.');
define('PREMIUM_FEATURE_DESC6','Store your files securely in GitLab.');
define('PREMIUM_FEATURE_DESC7','10GB free spaces for each website in GitLab.');
define('PREMIUM_FEATURE_DESC8','Roll back from copies in GitLab in case of server fault');
define('PREMIUM_FEATURE_DESC9','Save your hosting space and save you money');
define('GO_PREMIUM','Go Premium');
define('CREATE_ACCOUNT_FOR_FREE_BITBUCKET','Create an account for free in GitLab');
define('CREATE_ACCOUNT_FOR_FREE_GITLAB','Create an account for free in GitLab');
define('GIT_ADVANTAGES_DESC1','Compared to the traditional backup methods, the technology with Git has');
define('GIT_ADVANTAGES_DESC2','significant advantages.');
define('GIT_ADVANTAGES_DESC3','1. Efficient in resource consumption');
define('GIT_ADVANTAGES_DESC4','Git only tracks the changes. So unlike the traditional backup method, it will not keep the full website files and data upon each backup. Only changes will be committed to the last backup package and it takes much less space and saves a lot of time in a new backup. Assume a website takes 100MB space originally and the size increases by 5MB every day. Let’s see how Git Backup works compared to the traditional way. We can observe the dramatic difference just after 5 days.');
define('GIT_ADVANTAGES_DESC5','2. Super fast in rollback');
define('GIT_ADVANTAGES_DESC6','With the Git method, just one click can resolve all the issues and it could just take less than 30 seconds because it only rollback the changed contents based on the previous version. It totally avoids the boring process of removing the old files, uploading the package, and extracting it as with the traditional way.');
define('GIT_ADVANTAGES_DESC7','3. Easy to track the differences');
define('GIT_ADVANTAGES_DESC8','Whenever we need to compare the files between different versions for development or security purposes, it will be as easy as pie with Git functions. Git is designed to have a complete history and full version tracking abilities. With a few operations, we can get a full list of changed files and also the detailed changed codes of each file.');
define('GIT_ADVANTAGES_DESC9','4. Unlimited space when uploading the backup to Cloud');
define('GIT_ADVANTAGES_DESC10','Cloud backup is a popular solution as we can easily bring the backup with us at any place at any time as long as there is an internet connection. However, the consideration of space will more or less restricts the freedom of keeping the backup in a long term. This is not a problem at all for Git solution. With GitLab free service, we can create unlimited repositories, Git work directories, and each repository offers 10GB space.');
define('EXAMPLE_DIAGRAM','Example Diagram');
define('ADD_BACKUP_SIZE','New added backup size (MB)');
define('SITE_SIZE','Site Size (MB)');
define('TRADITIONAL_METHOD','Traditional method');
define('GIT_METHOD','Git method');
define('ACCUMULATION','Accumulation');
define('PREVIOUS','Previous');
define('NEXT','Next');
define('BACK_ACC_LIST','Back to Accounts List');
define('CHECK_SYS_REQUIREMENTS','Please check the system requirements');
define('DISABLED_PROC_OPEN','Your website has disabled proc open, Please enable it');
define('DISABLE_ENABLE_GITBACKUP','Please complete the Pre-requisite to use GitBackup');
define('WELCOME_TO_GITBACKUPO','Welcome to GitBackup');
define('MENU_IP_MANAGEMENT_DESC','The panel manages the firewall IP logs and detailed records where the site admin can also manually set blacklist and whitelist IPs');
define('SUBSCRIBE_PLAN','Subscribe To A Plan Now');
define('MORE_ABOUT_PREMIUM_SERVICE','More about premium service');
define('PLACE_AN_ORDER','Place an order');
define('ACTIVATE_THE_SUBSCRIPTION','Activate the subscription');
define('WARNING','Warning');
define('ACTIVATE_STEPS_DESC1','Simply create an account by using the form on the right hand side below, or if you have an account in Centrora already, simply sign in by using the form on the left hand side below.');
define('ACTIVATE_STEPS_DESC2','We offer 60 days 100% Satisfaction Guarantee, if you are not satisfied, we issue full refund to you without asking a question.');
define('ACTIVATE_STEPS_DESC3','Next, click the subscribe button to place an order to a subscrption plan. Once the order is placed, pay your subscription through Paypal or Credit Card. Once payments are made, you will see a subscription is active in the subscriptions table.');
define('ACTIVATE_STEPS_DESC4','Final step: click the link subscription button to activate the subscription for this website.');
define('ACCOUNT_ALREADY','If you have an account already, please enter your');
define('CENTRORA_TITLE','Centrora');
define('OR','Or');
define('OSE_TITLE','OSE');
define('ACC_INFO','Account Information');
define('WEB','Website');
define('SIGN_IN','Sign in');
define('NO_ACCOUNT','If you dont have an account yet, please use the following form to create an account.');
define('SET_START_DATE','Set Start Date');
define('SET_END_DATE','Set End Date');
define('SCANNED','Scanned');
define('MODI_FILES','Modified Files');
define('MODI_FILES_FOUND','Modified files found');
define('SIZE','Size');
define('MODIFIED','Modified');
define('SET_PATH','Set Path');
define('LATEST','Latest');
define('CHANGELOG','Changelog');
define('CLICK_HERE','Click here');
define('CURRENT_FOLDER_ROOT','Current Folder: ROOT');
define('SCANNER_REPORT','Scanner Report');
define('DISPLAY_INFECTED_FILES','Display the infected files last scanned by the virus scanner.');
define('INFECTED','Infected');
define('QUARANTINED','Quarantined');
define('CLEANED','Cleaned');
define('FREE_SSL_CERTI_DESC1','Centrally managing SSL Certificate. This provide secure, encrypted communications between a website and an internet browser. SSL stands for Secure Sockets Layer, the protocol which provides the encryption.');
define('FREE_SSL_CERTI_DESC2','When SSL Certificates installed on a web server, it activates the padlock and the https protocol and allows secure connections from a web server to a browser. Typically, SSL is used to secure credit card transactions, data transfer and logins, and more recently is becoming the norm when securing browsing of social media sites.');
define('SSL_CERTIFICATE_SETUP','SSL Certificate Setup');
define('SSL_CREATE_OR_DOWNLOAD','Create new certificate or download current one');
define('MANAGE_DOMAINS','MANAGE DOMAINS');
define('UPDATE_MD5','Click to update your MD5 Virus signature');
define('VIRUS_FILES','Virus Files');
define('VIRUS_FILES_DETECTED','Virus Files Detected!');
define('POST','POST');
define('GET','GET');
define('COOKIE','COOKIE');
define('FILTERED','Filtered');
define('WHITELISTED','Whitelisted');
define('DYNAMIC_SCANNER','Dynamic Scanner');
define('DYNAMIC_SCANNER_DESC','Dynamic Virus Scanner is a powerful tool, acting like an Anti-Virus, to directly scan,detect,clean and quarantine the malware on the website.');
define('VIRUS_SIGN','Virus Signature');
define('VIRUS_SIGN_UPDATE','Tuesday 5 April');
define('WEBSITE_STATUS','Website Status');
define('NO_VIRUS','No virus files are detected, please ensure to keep the Virus Signature up to date if you are not a premium user.');
define('ATTENTION','Attention!');
define('FILES_DETECTED','files are detected. Please click "View Results" button to check the results.');
define('VIEW_RESULTS','View Results');
define('SCAN_TYPE','Select Scan Types');
define('QUICK_SCAN','Quick Scan');
define('FULL_SCAN','Full Scan');
define('DEEP_SCAN','Deep Scan');
define('JS_INJECTION', 'Javascript Injection');
define('PHP_INJECTION', 'PHP Injection');
define('IFRAME_INJECTION', 'iFrame Injection');
define('SPAMMING_MAILER_INJECTION', 'Spamming Mailer Injection ');
define('SCHEDULE_SCAN','Schedule Scan');
define('CONFIG_SETUP','Config Setup');
define('START','START');
define('CONTINUE','CONTINUE');
define('STOP','STOP');
define('REFRESH','REFRESH');
define('BACKGROUND_SCAN','Background Scanning');
define('BACKGROUND_SCAN_DESC','Background Scanning will run the scans at the backend cosole and the result will be sent to your email address after completion');
define('STRING','String');
define('WHITE_LIST_DESC','Variable Management improves the knowledge of firewall and lets it learn from the experience to increase the accuracy');
define('CENTRORA_SECURITY_AUTO_SCAN1','Centrora Security');
define('CENTRORA_SECURITY_AUTO_SCAN2','Make you firewall rules smarter here');
define('PROBLEM_IN_GIT','There were some problem in initialising the git <br/>ERROR: <br/>');
define('UNINSTALL_GIT',"Uninstall Git");

define('O_BACKUP_SELECTED_ACCOUNTS', 'Backup Selective Accounts');
define('O_BACKUP_ALL_ACCOUNTS',"Backup All Accounts");
define('O_MANAGE_BACKUP_ACCOUNTS', 'Manage Backups for all the accounts');


define("O_GITLAB_DETAILS","Please Enter Your GitLab Account Details");
define("O_ACCESS_TOKEN","Access Token");
define("O_CREATE_REPO_GITLAB","Create Repository");
define("O_UNISNTALL_GIT","Uninstall Git");



define("O_GITBACKUP_CRON_INFO","Please select the settings for the Scheduled Git Backup");
define("O_CRON_LOCALBACKUP","Local Backup");
define("O_CRON_CLOUDBACKUP","Cloud Backup");
define("O_CRON_GITBACKUP_TYPE_DESC","Please select the type of backup");
define("O_CRON_GITBACKUP_RECEIVEEMAIL_DESC","Would You Like to receive email related to the Backup Status ");
define("O_RECEIVE_EMAILS","Receive Emails");
define("O_BACKUP_TYPE_HELP","Note : You need to setup the Gitlab Repo to use cloud backup");




define("O_RECEIVE_EMAIL_YES","Receive Email");
define("O_RECEIVE_EMAIL_NO"," No Thank You");
define("O_RECEIVE_EMAIL_INFO","Would You Like to receive emails on completion of backup");
define("O_GITBCKUP_FREQUENCY","Please select the Frequency of Backup");
define("O_BACKUP_DAYS","Please select days to backup the website ");



define('O_FILE_PATH', 'File Path');
define('O_FILE_SIZE', 'File Size');
define('O_CREATED', 'Created');
define('O_PRODUCT', 'Product');
define('O_CREATED_AT', 'Start Date');
define('O_EXPIRY_DATE', 'Expires On');
define('O_LAST_MODIFIED','Last Modified On');
define('O_TYPE','Type');
define('O_DOWNLOAD_CSV','Export Scan Results as CSV');
define('FILEEXTENSION_DESC_BRIEF','');


//firewall 7 table for attakc
define('O_URL','URL');
define('O_REFERER','Referer');
define("O_USERAGENT","User Agent");
define("O_BROWSER","Browser");
define("O_DETECTED_VAR","DETECTED VARIABLES");
define("O_FOR","DURATION");
define('O_GET_WHITELIST_IPS',"Get Temp WhiteListed Ips");
define("O_FIREWALL_STATS_SMALL_DESC","View graphical representation of attacks here");
define("O_EMAIL_STATS_SMALL_DESC","Setup your email preferences here");
define("O_COUNTRY_BLOCKINGV7_DESC","This feature allows users to restrict or give access to traffic from certain countries");
define("O_COUNTRY_BLOCKINGV7_SMALL_DESC","Configure what countries are able to access the website");
define("O_AUDITV7_TITLE","Audit My Website");
define("O_AUDITV7_DESC","Analyses current website settings and gives security advice");
define("O_AUDITV7_SMALL_DESC","Find out your website security report here ");
define("O_BANPAGE_TITLE","Ban Page Management");
define("O_BAN_PAGE_DESC","Prepare a customised Ban Page which will be shown to users blocked by the firewall");
define("O_BAN_PAGE_SMALL_DESC","Customize what you want to show to the blocked users");
define("O_ATTEMPT_TO_UPLOAD_VIRUSFILE","Attempt to Upload Virus File");

define('ATTACK_DETECTED','Detecting an attack');

?>
