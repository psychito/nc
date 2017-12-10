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
require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'ajax' . ODS . 'oseAjax.php');
class oseFirewallAjax extends oseAjax{
    public static function loadAppActions () {
        if (!empty($_REQUEST['controller']))
        {
            $method = 'loadAction'.$_REQUEST['controller'];
        }
        else
        {
            $method = 'loadActionDashboard';
        }
        if (method_exists('oseFirewallAjax',$method))
        {
            self::$method();
        }
    }

    public static function loadActionPasscode()
    {
        $actions = array('verify', 'changePasscode', 'disablePasscode', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionManageips () {
        $actions = array('getACLIPMap', 'addips', 'removeips', 'removeAllIPs', 'blacklistIP', 'whitelistIP', 'monitorIP', 'updateHost', 'changeIPStatus', 'viewAttack', 'importcsv', 'exportcsv', 'downloadCSV', 'getLatestTraffic', 'getKeyName', 'check', 'enableIPV6');
        parent::loadActions($actions);
    }
    public static function loadActionDashboard () {
        $actions = array('getCountryStat', 'getTrafficData', 'checkWebBrowsingStatus', 'getMalwareMap', 'updateDashboardStyle', 'getBackupList', 'getScanHist', 'check','getBackupAccountTable');
        parent::loadActions($actions);
    }
    public static function loadActionRulesets () {
        $actions = array('getRulesets', 'changeRuleStatus', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAdvancerulesets () {
        $actions = array('getRulesets', 'changeRuleStatus', 'checkAPI', 'downloadRequest', 'downloadSQL', 'check','checkPatternVersion','checkUserType','installPatterns','getDatefromVirusCheckFile');
        parent::loadActions($actions);
    }
    public static function loadActionSeoconfig () {
        $actions = array('getConfiguration', 'saveConfigSEO', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionScanconfig () {
        $actions = array('getConfiguration', 'saveConfigScan', 'showGoogleSecret', 'check');
        parent::loadActions($actions);
    }

    public static function loadActionUpload()
    {
        $actions = array('getExtLists', 'changeStatus', 'saveExt', 'getLog', 'check','getLogv7');
        parent::loadActions($actions);
    }
    public static function loadActionSpamconfig () {
        $actions = array('getConfiguration', 'saveConfAddon', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionEmailconfig () {
        $actions = array('getEmails', 'getEmailParams', 'getEmail', 'saveemail', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionEmailadmin () {
        $actions = array('getAdminEmailmap', 'getAdminUsers', 'getEmailList', 'addadminemailmap', 'deleteadminemailmap', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAvconfig () {
        $actions = array('getConfiguration', 'saveConfAV', 'check');
        parent::loadActions($actions);
    }

    public static function loadActionCfscan()
    {
        $actions = array('cfscan', 'addToAi', 'catchVirusMD5', 'check','checkCoreFilesExists','downloadCoreFiles','checkCoreFilesExistsSuite');
        parent::loadActions($actions);
    }

    public static function loadActionAiscan()
    {
        $actions = array('aiscan', 'getPatterns', 'deletePattern', 'resetSamples', 'addPattern', 'contentScan', 'aiscan_main', 'aiscan_finish', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionMfscan()
    {
        $actions = array('mfscan', 'getFileTree', 'getLastScanRecord', 'check','exportcsv_mfscan','downloadScanResultsCSV');
        parent::loadActions($actions);
    }
    public static function loadActionFpscan()
    {
        $actions = array('fpscan', 'getFileTree', 'getLastScanRecord', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionVsscan () {
        $actions = array('initDatabase', 'vsscan', 'updatePatterns', 'checkScheduleScanning', 'getFileTree', 'check', 'getLastScanRecord', 'getVirusCount', 'checkVirusSignatureVersion', 'updateVirusSignatureVersionFile', 'storeUserEmail', 'getUserEmail','getDatefromVirusCheckFile');
        parent::loadActions($actions);
    }
    public static function loadActionScanreport () {
        $actions = array('getTypeList', 'getMalwareMap', 'viewfile', 'quarantinevs', 'bkcleanvs', 'deletevs', 'restorevs', 'batchqt', 'batchbkcl', 'batchrs', 'batchdl', 'markasclean', 'check', 'downloadCSV', 'getVirusStats', 'coreFileCheck');
        parent::loadActions($actions);
    }
    public static function loadActionVariables () {
        $actions = array('getVariables', 'addvariables', 'deletevariable', 'loadWordpressrules', 'loadJoomlarules', 'loadJSocialrules', 'changeVarStatus', 'clearvariables', 'scanvar', 'filtervar', 'ignorevar', 'deleteAllVariables', 'check','defaultWhiteListVariables');
        parent::loadActions($actions);
    }
    public static function loadActionVersionupdate () {
        $actions = array('createTables', 'saveUserInfo', 'changeUserInfo', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionCountryblock () {
        $actions = array('downLoadTables', 'createTables', 'getCountryList', 'changeCountryStatus', 'blacklistCountry', 'whitelistCountry', 'monitorCountry', 'changeAllCountry', 'deleteCountry', 'deleteAllCountry', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAdvancedbackup()
    {
        $actions = array('backup', 'restore', 'getBackupList', 'deleteBackup', 'dropboxUpload', 'sendemail', 'oneDriveUpload', 'googledrive_upload', 'getGoogleDriveUploads', 'getOneDriveUploads', 'getDropboxUploads', 'check', 'easybackup');
        parent::loadActions($actions);
    }

    public static function loadActionBackup () {
        $actions = array('googledrive_upload', 'getGoogleDriveUploads', 'oneDriveUpload', 'getOneDriveUploads', 'dropboxUpload', 'getDropboxUploads', 'easybackup', 'restore', 'getRecentBackup', 'getNextSchedule', 'backupAuthenticationCallBack', 'backup', 'getBackupList', 'deleteBackup', 'sendemail', 'oauth', 'onedrive_logout', 'dropbox_logout', 'dropbox_init', 'googledrive_logout', 'check', 'backUpTypesCheck','backupDbTables');
        parent::loadActions($actions);
    }

    public static function loadActionAdminemails()
    {
        $actions = array('saveDomain', 'saveAdmin', 'getAdminList', 'getDomain', 'changeStatus', 'deleteAdmin', 'saveEmailEditor', 'restoreDefault', 'getSecManagers','saveSecManager','changeBlock', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAuthentication()
    {
        $actions = array('oauth', 'onedrive_logout', 'dropbox_logout', 'dropbox_init', 'googledrive_logout', 'backupAuthenticationCallBack', 'check');
        parent::loadActions($actions);
    }
    public static function loadactionUninstall(){
        $actions = array('uninstallTables', 'check');
        parent::loadActions($actions);
    }
    public static function loadactionApiconfig(){
        $actions = array('saveConfigScan', 'getConfiguration', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionLogin () {
        $actions = array('validate', 'verifyKey', 'updateKey', 'createaccount', 'getNumbOfWebsite', 'addOEM', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAudit () {
        $actions = array('createTables', 'changeusername', 'checkSafebrowsing', 'updateSafebrowsingStatus', 'uninstallTables', 'getPHPConfig', 'saveTrackingCode', 'updateSignature', 'check', 'googleRot','changePermission','createHtaccessFile');
        parent::loadActions($actions);
    }
    public static function loadActionSubscription () {
        $actions = array('getSubscription', 'getToken', 'linkSubscription', 'updateProfileID', 'logout', 'check', 'activateCode');
        parent::loadActions($actions);
    }
    public static function loadActionVlscan () {
        $actions = array('initDatabase', 'vlscan', 'check', 'getLastScanRecord');
        parent::loadActions($actions);
    }
    public static function loadActionSurfscan () {
        $actions = array('runSurfScan', 'check', 'getLastScanRecord', 'updateMD5DB', 'checkMD5DBUpToDate', 'getFileTree');
        parent::loadActions($actions);
    }
    public static function loadActionCronjobs () {
        $actions = array('saveCronConfig', 'check','canrunCronJob','getBackupTypeMenu');
        parent::loadActions($actions);
    }
    public static function loadActionPermConfig () {
        $actions = array('getDirFileList', 'getFileTree', 'editperms', 'oneClickFixPerm', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionNews () {
        $actions = array('getFeed', 'check');
        parent::loadActions($actions);

    }
    public static function loadActionGitbackup () {
        $actions = array('enableGitBackup', 'createBackupAllFiles','contBackupDB', 'backupDB', 'getGitLog', 'gitRollback', 'gitCloudCheck', 'saveRemoteGit', 'gitCloudPush', 'confirmRollback', 'websiteZipBackup','deleteZipBakcupFile','findChanges','discardChanges', 'downloadzip' ,'getZipUrl','viewChangeHistory','userSubscription', 'setCommitMessage', 'getLastBackupTime','checksystemInfo','localBackup','contLocalBackup', 'finalGitPush','uninstallgit','initalisegit','getFileNotification','toggleBackupLog','zipDownloadCloudCheck');
        parent::loadActions($actions);
    }


    public static function loadActionGitbackupsuite()
    {
        $actions = array('getAccountTable','addDBConfig','checkDBConfigExists','getWebSiteInfo','checkDBConnection','addDBConfigFileContent','getGitLog','isinit','backupDB','contBackupDB','localBackup','contLocalBackup','viewChangeHistory','gitCloudCheck','gitCloudPush','saveRemoteGit','findChanges','finalGitPush','websiteZipBackup','getZipUrl','downloadzip','deleteZipBakcupFile','discardChanges','getLastBackupTime','gitRollback','backupAccountsQueue','contBackupQueue','backupQueueCompleted','getPrerequisites','showErrorLog','getFileNotification','toggleBackupLog','manageQueues','zipDownloadCloudCheck');
        parent::loadActions($actions);
    }

    //firewall v7
    public static function loadActionBsconfigv7()
    {
        $actions = array('saveSettings','getFirewallSettings','saveConfigSEO','getLoginQRCode','toggleLoginGoogleAuthentication','toggleFirewallScanners','getFirewallScannerVersion','getFolderPermissions','isV7Activated','isSuite');
        parent::loadActions($actions);
    }

    public static function loadActionIpManagement()
    {
        $actions = array('getIpInfo','blacklistIp','whitelistIp','monitorIp','addIp','clearAll','deleteItem','importcsv','downloadCSV','viewAttackInfo','importips','addEntityFromAttackLog','getTempWhiteListedIps','deleteTempWhiteListIps');
        parent::loadActions($actions);
    }
    public static function loadActionWhitelistmgmt()
    {
        $actions = array('getEntityList','scan','filter','whitelist','clearAll','deleteItem','addEntity','loadDefaultVariables','importVariables','getSEOConfiguration','defaultWhiteListVariablesV7');
        parent::loadActions($actions);
    }
    public static function loadActionCountryblockingv7 () {
        $actions = array('downLoadTables', 'createTables', 'getCountryList', 'changeCountryStatus', 'blacklistCountry', 'whitelistCountry', 'monitorCountry', 'changeAllCountry', 'deleteCountry', 'deleteAllCountry', 'check');
        parent::loadActions($actions);
    }
    public static function loadActionAuditv7 () {
        $actions = array('createTables', 'changeusername', 'checkSafebrowsing', 'updateSafebrowsingStatus', 'uninstallTables', 'getPHPConfig', 'saveTrackingCode', 'updateSignature', 'check', 'googleRot','changePermission','createHtaccessFile','googleRotv7');
        parent::loadActions($actions);
    }
    public static function loadActionBanpagemgmt()
    {
        $actions = array('getBanPageSettings','saveBanPageSettings');
        parent::loadActions($actions);
    }
    public static function loadActionBsconfigv7stats()
    {
        $actions = array('getBrowserStats','getStats','getDailyStats','housekeepingV7');
        parent::loadActions($actions);
    }
    public static function loadActionEmailnotificationv7()
    {
        $actions = array('getSettings','saveSettings');
        parent::loadActions($actions);
    }


}