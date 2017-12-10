<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 22/07/2016
 * Time: 4:26 PM
 */
if (! defined ( 'OSE_FRAMEWORK' ) && ! defined ( 'OSEFWDIR' ) && ! defined ( '_JEXEC' )) {
    die ( 'Direct Access Not Allowed' );
}
require_once ('BaseModel.php');
class BanpagemgmtModel extends BaseModel {
    public function __construct() {
        $this->loadLibrary ();
        $this->loadDatabase ();
        oseFirewall::callLibClass('fwscannerv7','fwscannerv7');
        $this->fscanner = new oseFirewallScannerV7();
    }
    public function loadLocalScript() {
        $this->loadAllAssets ();
        oseFirewall::loadJSFile ('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
//        oseFirewall::loadJSFile ('CentroraManageIPs', 'rulesets.js', false);
        oseFirewall::loadJSFile ('banpagemgmt', 'banpagemgmt.js', false);
    }

    public function getBanPageSettings()
    {
        $result = $this->fscanner->getBanSettingsfromDb();
        return $result;
    }
    public function saveBanPageSettings($data)
    {
        $result = $this->fscanner->saveBanPageSettings($data);
        return $result;
    }
    public function showGoogleSecrets()
    {
        $result = $this->fscanner->showGoogleSecret();
        return $result;
    }

    public function getBackEndSecureKey()
    {
        echo '<code>' . JURI:: root() . 'administrator/index.php?</code> <input id="secureKey" type="text" name="secureKey" value="' . $this->getSecureKey() . '">';
    }

    private function getSecureKey()
    {
        $confArray = $this->getBanPageSettings();
        if(!empty($confArray) && $confArray['status']==1)
        {
            return $confArray['info'][34];
        }else{
            return;
        }
    }



}