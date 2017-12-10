<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 18/08/2016
 * Time: 3:12 PM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
require_once('BaseModel.php');
class Emailnotificationv7Model extends BaseModel{
    public function __construct() {
        $this->loadLibrary ();
        $this->loadDatabase ();
    }
    protected function loadLibrary () {
        $this->loadFirewallStat () ;
        oseFirewallBase::callLibClass('fwscannerv7','emailNotificationMgmt');
        $this->qatest = oRequest :: getInt('qatest', false);
        $this->emailMgmt = new emailNotificationMgmt();
    }
    protected function loadDatabase () {
    $this->db = oseFirewall::getDBO();
}
    public function loadLocalScript() {
        $this->loadAllAssets ();
        oseFirewall::loadJSFile ('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
        oseFirewall::loadJSFile ('Emailnotificationv7', 'emailnotificationv7.js', false);
    }
    public function getSettings()
    {
        $result = $this->emailMgmt->getFormattedSettings();
        return $result;
    }
    public function saveSettings($data)
    {
        $result = $this->emailMgmt->saveSettings($data);
        return $result;
    }
}