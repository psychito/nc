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

define('OSEAPPDIR', OSEFWDIR);
define('OSE_FRAMEWORK', true);
define('OSE_FRAMEWORKDIR', OSEFWDIR . 'vendor');
define('OSE_ABSPATH', dirname(dirname(dirname(OSEFWDIR))));
define('OSE_BACKUPPATH', dirname(dirname(OSEFWDIR)));
define('OSE_SUITEPATH', dirname(OSE_ABSPATH));

if (class_exists('SConfig')) {
    define('OSE_FWURL',OURI::root().'components/com_ose_firewall/');
    if(!defined('JOOMLA15'))
    {
        define('JOOMLA15',false);
    }
    if(!defined('JOOMLA25'))
    {
        define('JOOMLA25',true);
    }
    if(!defined('JOOMLA3'))
    {
        define('JOOMLA3',false);
    }

    $conf = new SConfig ();
    if (!empty($conf->assets_url)) {
        define('OSE_BANPAGE_ADMIN', $conf->assets_url.'administrator/components/com_ose_firewall/');
    }
    else {
        define('OSE_BANPAGE_ADMIN', str_replace('administrator/', '', OURI::root() ). 'administrator/components/com_ose_firewall/');
    }
    define('CENTRORABACKUP_FOLDER', OSE_ABSPATH.ODS.'media'.ODS.'CentroraBackup');
    define('CENTRORABACKUP_ZIPFILE', OSE_ABSPATH.ODS.'media'.ODS.'CentroraBackup'.ODS.'Backup.zip');
}
else {
    if (class_exists ('JURI')) {
        define('OSE_FWURL',JURI::root().'administrator/components/com_ose_firewall/');
    }
    else {
        define('OSE_FWURL',OURI::root().'components/com_ose_firewall/');
    }
    if (class_exists('JVersion')) {
        $version = new JVersion();
        $version = substr($version->getShortVersion(),0,3);
        if($version >='2.0' && $version <'3.0')
        {
            define('JOOMLA25',true);
        }else if($version >= '3.0')
        {
            define('JOOMLA3',true);
        }
        if(!defined('JOOMLA15'))
        {
            $value = ($version >= '1.5' && $version < '1.6')?true:false;
            define('JOOMLA15',$value);
        }
    }
    else {
        define('JOOMLA15',false);
    }
    define('OSE_BANPAGE_ADMIN', str_replace('administrator/', '', OURI::root() ). 'administrator/components/com_ose_firewall');
    define('CENTRORABACKUP_FOLDER', OSE_ABSPATH.ODS.'media'.ODS.'CentroraBackup');
    define('CENTRORABACKUP_ZIPFILE', OSE_ABSPATH.ODS.'media'.ODS.'CentroraBackup'.ODS.'Backup.zip');
}
define('OSE_BANPAGE_URL', OSE_BANPAGE_ADMIN . ODS . 'public');
define('CENTRORABACKUP_FOLDER_GITIGNORE','media'.ODS.'CentroraBackup'.ODS.'*');
define('CENTRORABACKUP_ZIPBACKUP_GITIGNORE','media'.ODS.'CentroraBackup'.ODS.'Backup.zip');
define('PRIVATEKEY_PATH',CENTRORABACKUP_FOLDER.ODS.'centrorakey');
define('PUBLICKEY_PATH',CENTRORABACKUP_FOLDER.ODS.'centrorakey.pub');
define('OSE_FWDATABACKUP', OSEFWDIR . 'protected' . ODS . 'data' . ODS . 'backup');
define('FOLDER_LIST',CENTRORABACKUP_FOLDER.ODS.'folderlist.php');
//define('CONTENT_FOLDER_LIST',CENTRORABACKUP_FOLDER.ODS.'contentfolderlist.php');
define('FOLDER_LIST_GITIGNORE','media'.ODS.'CentroraBackup'.ODS.'folderlist.php');
//define('CONTENT_FOLDER_LIST_GITIGNORE','media'.ODS.'CentroraBackup'.ODS.'contentfolderlist.php');
define('OSE_FWRELURL',OURI::root().'components/com_ose_firewall/');
define('OSE_FWASSETS', OSEFWDIR  . 'assets');
define('OSE_WPURL',rtrim(OURI::base(), '/') );
define('OSE_ADMINURL', OSE_WPURL.'/index.php?option=com_ose_firewall');
define('OSE_FWRECONTROLLERS' , OSEFWDIR . 'classes' .ODS. 'App' . ODS . 'Controller' . ODS . 'remoteControllers');
define('OSE_FWCONTROLLERS', OSEFWDIR . 'protected' . ODS . 'controllers');
define('OSE_FWMODEL', OSEFWDIR . 'classes' . ODS.'App' . ODS . 'Model');
define('OSE_FWFRAMEWORK', OSEFWDIR  . 'classes' . ODS.'Library');
define('OSE_FWPUBLIC', OSEFWDIR . 'public');
define('OSE_FWPUBLICURL', OSE_FWURL . 'public');
define('OSE_FWLANGUAGE', OSE_FWPUBLIC . ODS.'messages');
define('OSE_TEMPFOLDER', OSEFWDIR  . 'protected' . ODS.'data'.ODS.'tmp');
define('OSE_FWDATA', OSEFWDIR . 'protected' . ODS.'data');
define('OSE_QATESTFILE',CENTRORABACKUP_FOLDER.ODS.'QATest.php');
define('OSE_DEFAULT_SCANPATH', dirname(dirname(dirname(OSEFWDIR))));
define('OSE_DBTABLESFILE', OSE_FWDATABACKUP .ODS . "dbtables.php"); //TODO  CHECK
define('BACKUP_DOWNLOAD_URL', '?option=com_ose_firewall&view=backup&task=downloadBackupFile&action=downloadBackupFile&controller=backup&id=');
define('ZIP_DOWNLOAD_URL', '?option=com_ose_firewall&view=gitbackup&task=downloadzip&action=downloadzip&controller=gitbackup');
//define('ZIP_DOWNLOAD_URL', '?page=ose_fw_gitbackup&option=ose_firewall&task=downloadzip&action=downloadzip&controller=downloadzip&id=Backup.zip');
define('BACKUP_ZIPFOLDER_GITIGNORE_PATH','media'.ODS.'CentroraBackup'.ODS.'BackupFiles');
define('BACKUP_ZIPFOLDER',CENTRORABACKUP_FOLDER.ODS.'BackupFiles');
define('EXPORT_DOWNLOAD_URL', '?option=com_ose_firewall&view=manageips&task=downloadCSV&action=downloadCSV&controller=manageips&filename=');
define('UPDATE_ADFIREWALL_RULE', '?option=com_ose_firewall&view=rulesets#adfirewall-rule');
define('DOWANLOAD_COUNTRYBLOCK_DB', '?option=com_ose_firewall&view=countryblock');
define('EXPORT_VSFILES_URL', '?option=com_ose_firewall&view=scanreport&task=downloadCSV&action=downloadCSV&controller=scanreport&filename=');
define('APP_FOLDER_NAME', basename(OSE_ABSPATH));
define('BACKUPFILES_EXCLUDEPATH',APP_FOLDER_NAME.ODS.BACKUP_ZIPFOLDER_GITIGNORE_PATH);
define('OSE_CONTENTFOLDER', OSE_ABSPATH.ODS.'media');
define('MOVE_PRIVATEKEY_PATH',CENTRORABACKUP_FOLDER.ODS.'keybackup'.ODS.'centrorakey');
define('MOVE_PUBLICKEY_PATH',CENTRORABACKUP_FOLDER.ODS.'keybackup'.ODS.'centrorakey.pub');
define('KEY_BACKUP',CENTRORABACKUP_FOLDER.ODS.'keybackup'.ODS.'*');
define('KEY_BACKUP_GITIGNORE','media'.ODS.'CentroraBackup'.ODS.'keybackup'.ODS.'*');
define("OSE_GITBACKUP_MEDIAFOLDER",CENTRORABACKUP_FOLDER.ODS."gitbackup");
define('OSE_GITBACKUP_QUEUELIST', CENTRORABACKUP_FOLDER.ODS."gitbackup".ODS."backupQueue.php");
define("OSE_GITBACKUP_ERROR_LOG",CENTRORABACKUP_FOLDER.ODS."gitbackup".ODS."backupErrorLog.php");
define("OSE_GITBACKUP_LOG",CENTRORABACKUP_FOLDER.ODS."gitbackup".ODS."backupLog.php");
define("OSE_GITBACKUP_ERRORLOG_GITIGNORE",'media'.ODS.'CentroraBackup'.ODS."gitbackup".ODS."backupErrorLog.php");
define("OSE_GITBACKUP_BACKUPLOG_GITIGNORE",'media'.ODS.'CentroraBackup'.ODS."gitbackup".ODS."backupLog.php");
define("O_GITBACKUP_CREATETABLEFILE", OSE_GITBACKUP_MEDIAFOLDER.ODS."createTables.sql");
define("O_GITBACKUP_ALTERTABLEFILE", OSE_GITBACKUP_MEDIAFOLDER.ODS."alterTables.sql");
define("O_GITBACKUP_TABLELISTFILE", OSE_GITBACKUP_MEDIAFOLDER.ODS."tablesList.sql");
define("O_GITBACKUP_INSERDATAFILE", OSE_GITBACKUP_MEDIAFOLDER.ODS."insertData.sql");
define("O_GITBACKUP_GITLOGTABLEFILE", OSE_GITBACKUP_MEDIAFOLDER.ODS."gitLog.sql");
define('GITFOLDER', OSE_ABSPATH.ODS.'.git');
define("O_IGNORE_BACKUPLOG",'media'.ODS.'CentroraBackup'.ODS."gitbackup".ODS.'backupLog.php');
define("O_IGNORE_ERRORLOG",'media'.ODS.'CentroraBackup'.ODS."gitbackup".ODS.'backupErrorLog.php');
define("O_ENABLE_GITBACKUP_LOG",OSE_GITBACKUP_MEDIAFOLDER.ODS.'enableLog.php');
define("OSE_LAST_VERSION_CHECK",OSE_TEMPFOLDER.ODS."LastVersionCheck.php");
define('OSE_VIRUSPATTERN_FILE',OSE_FWDATA . ODS . "vsscanPath" . ODS . "pattern.php");
define('MFSCAN_RESULT_EXPORT_DOWNLOAD_URL', '?option=com_ose_firewall&view=mfscan&task=downloadScanResultsCSV&action=downloadScanResultsCSV&controller=mfscan&filename=');
//FIREWALL V7 PARAMS
define('OSE_ADVANCEDRULES_TEMPFILE',CENTRORABACKUP_FOLDER.ODS."advancedrulestemp.php");
define('OSE_ADVANCEDRULES_RULESFILE',CENTRORABACKUP_FOLDER.ODS."2a9rKzWuOFWao.inc");
define('OSE_WEBLOGFOLDER',CENTRORABACKUP_FOLDER.ODS.'Weblog');
define('OSE_WEBLOG_BACKUPFOLDER',CENTRORABACKUP_FOLDER.ODS.'WeblogBackup');
define('OSE_WEBLOG_ZIP_PATH',OSE_WEBLOG_BACKUPFOLDER.ODS.'weblog.tar.gz');
define('OSE_WEBLOG_ZIP_DESTINATIONPATH',CENTRORABACKUP_FOLDER.ODS.'Weblog');
define('OSE_BRUTEFORCE_ATTEMPTS',CENTRORABACKUP_FOLDER.ODS.'bruteforceattempts.php');
define('OSE_WHITELIST_STRINGFILE',CENTRORABACKUP_FOLDER.ODS.'whiteliststring.php');
define('OSE_WHITELIST_VARIABLESFILE',CENTRORABACKUP_FOLDER.ODS.'whitelistvariables.php');
define('OSE_FWSCANNERV7_ERRORLOG',OSE_WEBLOGFOLDER.ODS.'errorlog.php');
define('OSE_CSV_EXPORTFILES', OSE_CONTENTFOLDER.ODS."csv");
define('EXPORT_DOWNLOAD_URL_FWS7', '?option=com_ose_firewall&view=ipmanagement&task=downloadCSV&action=downloadCSV&controller=IpManagement&filename=');
define('OSE_WEBLOG_TEMP_FOLDER',OSE_WEBLOGFOLDER.ODS.'logBackup');
define('OSE_CLASSICBACKUP_DBTABLESLIST',CENTRORABACKUP_FOLDER.ODS.'dbtableslist.php');
define('OSE_SERIALIZE_FILE',OSE_ABSPATH . ODS . 'media' . ODS . 'CentroraBackup' . ODS . "filesbackuplist.txt");
define('OSE_BACKUP_FOLDER_LIST',CENTRORABACKUP_FOLDER.ODS.'backupfolderlist.php');
define("OSE_TEMP_IP_STATUS",CENTRORABACKUP_FOLDER.ODS."ipstatus.php");
define("OSE_FILTER_EMAIL_NOTIFI",OSE_WEBLOGFOLDER.ODS."emailNotification.php");
define('OSE_VIRUSPATTERN_FILE_FW7',OSE_WEBLOG_BACKUPFOLDER . ODS . "pattern.php");
define('OSE_CHUNKSIZE_FILE',OSE_FWDATA.ODS."chunksize.php");
?>