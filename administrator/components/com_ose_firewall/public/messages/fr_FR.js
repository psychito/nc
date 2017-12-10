var O_DELETE_CONFIRM = "Delete Confirmation";
var O_DELETE_CONFIRM_DESC = "Please confirm that you would like to delete the selected items";
var O_APIKEY = "API key";
var O_ENABLE_SFSPAM = "Enable Stop Forum Spam";
var O_SFS_CONFIDENCE_LEVEL = "Stop Forum Spam Confidence Level (between 1 and 100)";
var O_EDIT_EMAIL_TEMP = "Edit email template";
var O_EDIT = "Edit";
var O_EMAIL_TYPE = "Email Type";
var O_EMAIL_SUBJECT = "Email Subject";
var O_EMAIL_BODY = "Email Body";
var O_USER = "User";
var O_USER_ID = "User ID";
var O_EMAIL = "Email";
var O_ADD_A_LINK = "Add a linkage";

//added from 4.4.1
var O_CALLTOSUBSCRIBE = "Activate Subscription";
var O_CALLTOSUBSCRIBE_DESC = "Please activate your subscription to use this feature.";
var O_CANCEL = "Cancel";
var O_CONFIRM = "Confirm";
var O_YES = "Yes";
var O_NO = "No";
var O_FAIL = "Fail";
var O_NOTICE = "Notice";
var O_SUBSCRIBE = "Subscribe";
var O_FIXPERMISSIONS = "<i class='text-success glyphicon glyphicon-wrench'></i> Fix Permissions";
var O_FIXPERMISSIONS_LONG = "One Click Permissions Fix";
var O_FIXPERMISSIONS_DESC = "This will set the recommended default file/folder permissions to the config files";
var O_CLOSE = "Close";

//added from 4.7.0
var O_OK = "OK";
var O_SUCCESS = "Success";
var O_EMAIL_TEMP_SAVE = "Email template was saved successfully";
var O_SELECT_FIRST = "Please select one item first";
var O_UPLOAD_DROPBOX = "The backup file has been uploaded to your dropbox";
var O_UPLOAD_ERROR = "An error occured while uploading:";
var O_CONFIRM_EMAIL_NOTICE = "A Confirmation Email will be sent to you";
var O_SEND_EMAIL_ERROR = "An error occured while sending a confrimation email: <br />" + "Please make sure you have added a valid email address in the 'Administrator Panel'";
var O_BACKUP_DELE_DESC = "The backup file has been deleted successfully";
var O_DELE_FAIL_DESC = "The delete operation failed! Please try again";
var O_BACKUP_SUCCESS = "Backup success";
var O_BACKUP_FAIL = "Backup failed, please try again";
var O_ERROR = "Error";
var O_BACKUP_ERROR = "Operational error during backup<br /> Error Code: ";
var O_LOADING_TEXT = "Please wait...";
var O_DB_INSTALL_DESC = "Database installer preparing in progress";
var O_CSV_FORMAT = '<br/>Please create the CSV file with the following headers: title, ip_start, ip_end, ip_type, ip_status. <br/><br/> Explanations:<br/><br/>' +
    '<ul>' +
    '<li>title: the title of the rule for this IP / IP Range<li>' +
    '<li>ip_start: the start IP in the IP Range<li>' +
    '<li>ip_end: the end IP in the IP Range<li>' +
    '<li>ip_type: the type of this record, \'0\' refers to one single IP, whereas \'1\' refers to IP ranges<li>' +
    '<li>ip_status: the status of the IP, \'1\' for blocked IP, \'3\' for whitelisted IP, \'2\' for monitored IP <li>' +
    '</ul>';
var O_FILE_PERMISSION_DESC = "Make sure to set appropriate file permissions 0000 would render your selected Files/Folders inaccessible";
var O_QUARANTINE_FAIL_DESC = "Quarantine failed, please try again";
var O_QUARANTINE_SUCCESS_DESC = "Quarantine success";
var O_CLEAN_SUCCESS = "Clean success";
var O_CLEAN_FAIL = "Clean failed, please try again";
var O_RESTORE_SUCCESS = "Restore success";
var O_RESTORE_FAIL = "Restore failed, please try again";
var O_DELE_SUCCESS_DESC = "Delete success";
var O_CLEAN = "Clean";
var O_DELETE = "Delete";
var O_QUARANTINE = "Quarantine";
var O_RESTORE = "Restore";
var O_ORDER_NOTICE = "Your order has been successfully placed.";
var O_UPDATE = "Updating...";
var O_ACTIVATE_PLUGIN = "Activating plugin...";

//added from 4.8.0
var O_UPLOAD_ONEDRIVE = "The backup has been uploaded to your OneDrive";
var O_DROPBOX_LOGOUT = "Dropbox Logout";
var O_SPEECH_BUBBLE = "Hacking traffic table will refresh every minute";
var O_SESSION_EXPIRED = "Session expired, please login again";
var O_DROPBOX_AUTHO = "Dropbox Authorisation";
var O_DROPBOX_AUTHO_DESC2 = "Step 2 - Click OK to get the 'Access Token' for Dropbox authorisation";
var O_DROPBOX_AUTHO_DESC3 = "Step 3 - Click OK to complete Dropbox authentication";

//added from 4.9.0
var O_AUTH_CLOUD = "Authenticate a cloud Service";
var O_EMAILTEMP_FAIL = "Email template cannot be restored as it is already default";
var O_EMAILTEMP_SUCESSS = "Email template is restoring to default";
var O_UPLOAD_GOOGLEDRIVE = "The backup file has been uploaded to your GoogleDrive";
var O_VSPATTERN_UPDATE = "Virus signature has been updated";
var O_PREP_FILES = "Preparing File(s) for Upload...";
var O_VSPATTERN_UPDATE_FAIL = "There are errors when updating virus signature ";

//added from 5.0.0
var O_PASSWORD_STRENGTH_STRONG = 'Your current password strength is <b>strong</b>';
var O_PASSWORD_STRENGTH_WEAK = 'Your current password strength is <b>weak</b>, we suggest you change to recommend settings and save';
var O_PASSWORD_SUCCESS = "Password setting saved successfully";
var O_PASSWORD_FAIL = "There are errors when saving password setting";
var O_UPDATE_NOW = "Update Now";
var O_UPDATE_CONF = "Update Confirmation";
var O_UPDATE_CONF_DESC = "Are you sure you want to update to: ";
var O_ADD_ADMIN_SUCCESS = "Successfully added the administrator account";
var O_ADD_ADMIN_FAIL = "There are errors when adding the administrator account";
var O_PLEASE_WAIT = 'Please wait...';
var O_GDIALOG_MSG = 'Be sure to link your account with Google Authenticator under WordPress users ';
var O_GDIALOG_TITLE = 'REMINDER: Link Google Autheniticator';

//added from 5.0.1
var O_ADD_EXT_SUCCESS = "Successfully added the extension";
var O_ADD_EXT_FAIL = "There are errors when adding the extension";
var O_ADD_SEC_SUCCESS = "Successfully added the Security Manager";
var O_ADD_SEC_FAIL = "There are errors when adding the Security Manager";

//added from 5.1.0
var O_ACTIVATE_FAIL = "Activate centrora plugin failed";

//added from 6.0.0
var O_TERMINATE_SCAN = 'Terminating scanning process';
var O_SCAN_COMPLETE = '';
var O_BACKUP_CREATENEW_STEP1 = 'Step 1: Setup Backup';
var O_BACKUP_CREATENEW_STEP2 = 'Step 2: Backup Configuration';
var O_BACKUP_CREATENEW_STEP3 = 'Step 3: Building Backup';
var O_ONEDRIVE_AUTHO = 'OneDrive Authentication';
var O_ONEDRIVE_AUTHO_DESC = "Click 'OK' to complete OneDrive Authentication";
var O_GOOGLEDRIVE_AUTHO = 'GoogleDrive Authentication';
var O_GOOGLEDRIVE_AUTHO_DESC = "Click 'OK' to complete Google Authentication";
var O_DELETEALL_CONFIRM_DESC = 'You are about to delete ALL records on this table! Are you sure?';
var O_DELETEALLIP_CONFRIM = 'This also removes all IP log related detail e.g. File upload log details';
var O_DELETEVARIABLES_CONFRIM = 'This may also remove detail content associated with IP log data seen in the View detail Action of IP Logs.';
var O_MARKASCLEAN_SUCCESS_DESC = 'File(s) marked as Clean.';
var O_MARKASCLEAN_FAIL_DESC = 'Unsuccessful mark as Clean! Please try again.';
var O_MARKASCLEAN = 'Mark As Clean';


//ADD Missing for 7.0.0
var O_DETECTED_SUSPICIOUS_FILES = 'Detected suspicious files';
var O_DETECTED_FILES = 'Detected files';
var O_PRIVATE_API_KEY = 'Enter your Centrora private API Key';
var O_WORONG_PASSCODE = 'wrong passcode, try again';
var O_ERROR_ACCESS_JS = 'ERROR in the createht access javascript';
var O_ERROR_ACCESS_FIREALL_VERSION = 'Error Accessing the Firewall Version';
var O_LOADING_GET_TOKEN = 'Getting Access Token...';
var O_LOADING_FINAL_AUTH = 'Final Authentication...';
var O_LOADING_PLEASE_WAIT= 'Please Wait...';
var O_LOADING_WAIT_BACKUP = 'This may take a few seconds, please keep this page open until this backup is successfully built';
var O_LOCAL_BACKUP = 'Local Backup';
var O_BACKUP_TIME = 'MMMM Do YYYY, h:mm:ss a';
var O_BACKUP_AUTHED = 'Authenticated';
var O_LOGOUT = 'Logout';
var O_BACKUP_AUTH = 'Authentication';
var O_BACKUP_NOAUTHED = 'Not Authenticated';
var O_BACKUP_NEED_SUBS = 'Need Subscription';
var O_SUBS_NOW = 'Subscribe Now';
var O_NO_BACKUP_RECORDS_FOUNDS = 'No Schedule Backup Records Found';
var O_NEED_SUBS = 'Need Subscription';
var O_DOWNLOAD = 'Download';
var O_BACKUP_TIMES = 'Backup Time';
var O_BACKUP_NAME= 'Backup Name';
var O_BACKUP_PLATFORMS = 'Backup Platforms';
var O_BACKUP_FILES = 'Backup Files';
var O_NO_BACKUP_RECORDS_FOUND = 'No Backup Records Found';
var O_WIZARD = 'Wizard';
var O_WIZARD_DESC = 'Follow the steps to configure the firewall with the basic settings.';
var O_ADVANCE_SETTING = 'Advance Settings';
var O_ADVANCE_SETTING_DESC = 'Enable the advance features to further enhance the protection of the website';
var O_FIREWALL_OVERVIEW = 'Firewall Control Panel';
var O_FIREWALL_OVERVIEW_DESC = 'To setup basic firewall settings and manage logs / IP rules';
var O_FIREWALL_CONFIGURATION= 'Firewall Configuration';
var O_FIREWALL_CONFIGURATION_DESC = 'File upload logs on the website filtered by the firewall';
var O_WAENING_NOT_ABLE_LOGIN_THIS_CODE = 'WARNING : PLEASE NOTE THAT YOU WOULD NOT BE ABLE TO LOGIN WITHOUT THIS CODE';
var O_WAENING_NEED_GOOGLE_AUTH = 'You will need to download Google Authenticator App on Your Mobile';
var O_CONTINUE= 'Continue';
var O_ENTER_EMAIL_IN_FIREWALL_OVERVIEW_SETTING = 'please enter a valid email address in firewall overview settings to ensure we can  safely deliver the report to you.';
var O_FILL_VALIDATE_EMAIL_ADDRESS = 'Please fill in a validate email address';
var O_INSENSITIVE = 'insensitive';
var O_MODERATE = 'moderate';
var O_SENSITIVE = 'sensitive';
var O_VERY_SENSITIVE = 'very sensitive';
var O_HIGHTLY_SENSITIVE = 'highly sensitive';
var O_SAVE_WIZARD_CONFIRMATION = 'Once saved basic settings, the advanced settngs for the system will be disabled by default. However, you can configure them from the Firewall Settings > Firewall ScannerV7 > Advanced Settings at any time';
var O_FINISH = 'Finish';
var O_SCAN_QR = 'please scan the QR code';
var O_GOOGLE_AUTH_ACTIVE_BOX = 'In the Google Authentication Settings, please click on the Active box and Scan the secret QR Code, do you want to do this now?';
var O_CONTINUE_TO_USER_PROFILE = 'Continue to User Profile';
var O_FAILED_TO_UPDATE = 'Failed to Update';
var O_SCAN_SUMMARY = 'Scan Summary';
var O_DETECTED_MODIFILED_CORE_FILES = 'Detected modified core files';
var O_DETECTED_MISSING_FILES = 'Detected missing files';
var O_COUNTRYBLOCK_DATABASE_COMPLETED = 'CountryBlock Database Completed';
var O_ATTACK_HISTORY_24H = 'Attack History (24-hour clock)';
var O_NUMBER_OF_ATTATCKS_TIMES = 'Number of attacks (times)';
var O_COUNTRY_DATABASE_NOT_INSTALLED = 'Country database is not installed';
var O_SYSTEM_INITIALISING_GITBACKUP = 'The system is now initialising gitbackup';
var O_ENTER_ALPHANUMERIC_CHARACTERS_ONLY = 'PLEASE ENTER ALPHANUMERIC CHARACTERS ONLY';
var O_ALL = 'All';
var O_WHITELISTED= 'WhiteListed';
var O_BLACKLISTED = 'BlackListed';
var O_MONITORED = 'Monitored';
var O_IMPORTING_CSV= 'The System is now Importing the CSV File......Please wait.';
var O_DELETE_IP = 'Are you sure you wan to delete the ip(s)';
var O_DELETE_ALL_IP_RECORDS = 'Are you sure you want to delete all the ip records ';
var O_EXPAND_ALL_SUBSMENUS = 'Expand all SubMenus';
var O_COLLAPSE_ALL_SUBSMENUS = 'Collapse all SubMenus';
var O_LOADING_DATABASE_STRUCTURE = 'Changing Database structure, please wait...';
var O_GENERATING_TREE= 'Generating Tree...';
var O_HIDE_CHANGELOG = 'Hide Changelog';
var O_SHOW_CHANGELOG = 'Show Changelog';
var O_CURRENT_FOLDER_ROOT = 'Current Folder: ROOT';
var O_FOLDER_S = 'Folder(s):';
var O_FILE_S = 'File(s)';
var O_ACTIVE = 'Active';
var O_INACTIVE = 'InActive';
var O_STATUS = 'Status';
var O_SIGN_UPDATE = 'Signature is being updated, please wait...';
var O_NO_ACTION = 'No action';
var O_CLEANED = 'Cleaned';
var O_QUARANTINED = 'Quarantined';
var O_GENERATING_NEW_ORDER= 'Please wait, generating a new order...';
var O_REDIRECTING_PAYPAL = 'Please wait, redirecting to Paypal...';
var O_PATH = 'Path';
var O_ALLOWED = 'Allowed';
var O_FORBIDDEN = 'Forbidden';
var O_TYPE = 'Type';
var O_FILES_TEXT = 'Text Files';
var O_FILES_DATA = 'Data Files';
var O_FILES_AUDIO = 'Audio Files';
var O_FILES_VIDEO = 'Video Files';
var O_FILES_3D_IMAGE = '3D Image Files';
var O_FILES_RASTER_IMAGE = 'Raster Image Files';
var O_FILES_VECTOR_IMAGE = 'Vector Image Files';
var O_FILES_PAGE_LAOUT = 'Page Layout Files';
var O_FILES_SPREADSHEET = 'Spreadsheet Files';
var O_FILES_DATABASE = 'Database Files';
var O_FILES_EXECUTABLE = 'Executable Files';
var O_FILES_GAME = 'Game Files';
var O_FILES_CAD = 'CAD Files';
var O_FILES_GIS = 'GIS Files';
var O_FILES_WEB = 'Web Files';
var O_FILES_PLUGIN = 'Plugin Files';
var O_FILES_FONT = 'Font Files';
var O_FILES_SYSTEM = 'System Files';
var O_FILES_SETTING = 'Settings Files';
var O_FILES_ENCODED = 'Encoded Files';
var O_FILES_COMPRESSED = 'Compressed Files';
var O_FILES_DISK_IMAGE = 'Disk Image Files';
var O_FILES_DEVELOPER = 'Developer Files';
var O_FILES_BACKUP = 'Backup Files';
var O_FILES_MISC = 'Misc Files';
var O_SCANNED = 'Scanned';
var O_FILTERED = 'Filtered';
var O_IGNORED = 'Ignored';
var O_PROBLEMS_ADDING_WHITELIST = 'There was some problem in adding the default whitelist variables';
var O_WAITING_SERVER_RESPONSE = 'Waiting server response...';
var O_PHP_FILES_SCANNED_PERMISSION_ISSUE = 'These php files can not be scanned due to permission issue or exceed maximum excution time, please manually check them.';
var O_ENTER_VALIDATED_EMAIL_ENSURE = 'Please enter a validated email address to ensure our scan report would not be homeless :';
var O_EMAIL_STORED_SUCCESSFULY = 'the email has been stored successfuly';
var O_PROBLEM_RETIEVING_EMAIL_ADDRESS= 'There was some problem in retieving email address';
var O_SCANNER_DETECTED = 'The scanner has detected';
var O_VIRUSES = 'viruses';
var O_SACNNING_RESULT = 'Scanning Result';
var O_CHECKING_VIRUSES_SIGN = 'Checking virus signature...';
var O_STRINGS = 'Strings';
var O_VARIABLES = 'Variables';
var O_IMPORT_VARIABLE_LIST = 'Are you sure you wan to import the Variable List from Firewall Version 6.6 (The existing records will not be overwritten)';
var O_STRING_VALUE = 'String Value';
var O_ENTITY_KEY_NAME = 'Entity Key Name';
var O_SYSTEM_GENERATING_BACKUP = 'The system is now generating a backup of the complete website and the database.';
var O_ASK_RESTORE_DATABASE = 'Do you want to restore the database of';
var O_RECOMMENED_NOT_RESTORE_OLD_DATABASE = 'It is highly recommended not restoring the database if there is no error in the database.';
var O_SYSTEM_ROLLING_BACK = 'The system is now rolling back';
var O_SYSTEM_ROLLING_BACK_TO = 'The system has been rolled back to ';
var O_SYSTEM_BACKUP_WEBSITE = 'The system is now backing up your website';
var O_BACKUP_SUCCESSFULLY = 'The Backup has been Created Successfully';
var O_CANNT_GIT_BACKUP = 'The git could not backup the website, please contact the support team.';
var O_GIT_INITIALISED_WEBSITE = 'The git has been initialised for the website';
var O_BACKUP_ALL_TO_CLOUD = 'The system is now uploading the backup to the cloud';
var O_COPY_BACKUP_ON_CLOUD = 'A copy of backup is stored on the cloud';
var O_SYSTEM_GENERATING_ZIP_OF_WEBSITE = 'The system is now generating the Zip file of the website';
var O_SYSTEM_PREPARING_ZIP_OF_WEBSITE = 'The system is now preparing to download the Zip file of the website';
var O_ZIP_BACKUP_NOT_EXISTS = 'The zip Backup file does not exists';
var O_KEEP_UNSAVED_CHANGES= 'There are some unsaved changes do you want to keep them ?';
var O_DATE = 'Date';
var O_FILE_CHANGES = 'Files changed';
var O_CHOOSE_DOMAIN = 'Choose Domain';
var O_SSL_DOMAIN = 'The domain your want to get SSL';
var O_CHOOSE_EMAIL = 'Choose Email';
var O_DOMAIN_EMAIL_GET_CODE = 'The domain email will receive verification code';
var O_SEND_CODE = 'Send Verification Code';
var O_PREVIOUS = 'Previous';
var O_REVALIDATION_EMAIL_WILL_SEND = 'The Re validation code will be sent to the chosen email address';
var O_VERIFY = 'Verify';
var O_VALIDATION = 'Validation';
var O_VERIFIY_YOUR_CODE = 'Verifiy your code';
var O_RE_VALIDATION = 'Re - Validation';
var O_VERIFIY_CODE_TO_REVALIDATE_DOMAIN = 'Verifiy your code to Re Validate the domain';
var O_REVALIDATE = 'Re validate';
var O_GENERATE_SSL_CERT= 'Generate SSL Certificate';
var O_COUNTRY_NAME = 'Country Name';
var O_VALIDATION2 = 'Validation 2';
var O_VERIFIY_CODE2 = 'Verifiy your code 2';
var O_COMPLETED = 'Completed';
var O_CONGRATULATIONS = 'Congratulations';
var O_DOWNLOAD_SSL_CERT = 'Now you can download your SSL Certificate';
var O_SSL_CERT_CURRENTLY_HAVE = 'Here is the SSL Certificate you currently have';
var O_CREATE_NEW = 'Create new';
var O_RETRIEVING_EMAIL_IDS = 'Retrieving the list of email ids, Please Wait';
var O_SENDING_CODE_EMAIL = 'Sending verification code to the email. Please Wait ...';
var O_SENDING_REVALIDATION_EMAIL = 'Sending Re validation code to the email. Please Wait ...';
var O_VALIDATION_DOMAIN_NOW = 'Please wait.......validation the domain now';
var O_REVALIDATION_DOMAIN_NOW = 'Please wait.......Re validation the domain now';
var O_GENERATING_SSL_CERT = 'Generating ssl certificate';
var O_PREPARING_DOWNLOAD_SSL_CERT = 'Preparing to download the SSL Certificate';
var O_PREPARING_DOWNLOAD = 'Preparing to Download, Please wait....';
var O_SELECT_DOMAIN = 'Select Domain';
var O_NEW_DOMAIN = 'New Domain';
var O_PERFORMING_DOMAIN_CHECKS = 'Performing Domain Checks';
var O_DELETE_ALL_VALIDATED_DOMAIN= 'Are you sure you want to delete all the validated domain(s)';
var O_UPDATE_DIALOG_HEADING = "UPDATE";
var O_SEO_CONFIGURATION = "SEO Configuration";
var O_SEO_CONFIGURATION_DESC = "Improve search engine optimization by customising the website keywords";
var O_SEO_CONFIGURATION_SMALL_DESC = "Setup SEO and bots settings here ";
var VIEW_SETUP_ADVANCED_FEATURES = 'Setup the advance features and services here';
var O_FILE_UPLOAD_LOGS_SMALL_DESC = "View the list of files uploaded" ;
var O_FILE_UPLOAD_LOGS_TITLE = "File Upload Logs";
var O_FILE_EXTENSION_CONTROL_TITLE = "File Extension Control";
var O_FILE_EXTENSION_CONTROL_DESC = "Manages extensions of files which are allowed or blocked to upload by the firewall";
var O_FILE_EXTENSION_CONTROL_SMALL_DESC = "Set up type of files";
var O_DELETE_CONFIRM_DESC_FW7_IPMGMT = "Are you sure you wan to delete the ip(s)";

