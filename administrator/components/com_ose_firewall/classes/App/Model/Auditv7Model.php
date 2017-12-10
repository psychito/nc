<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 21/07/2016
 * Time: 2:44 PM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
require_once('AuditModel.php');
class Auditv7Model extends AuditModel
{
    public function __construct()
    {
        $this->loadLibrary();
        oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
        oseFirewall::callLibClass('fwscannerv7', 'auditv7');
    }

    public function showStatus()
    {
        $dbReady = $this->isDBReady();
        if ($dbReady['ready'] == false) {
            echo '<li class="list-group-item"><span class="label label-warning">Warning</span> ' . oLang:: _get('DBNOTREADY') . ' <button id ="install-button" name ="install-button" class="btn-new result-btn-set" data-target="#formModal" data-toggle="modal" >' . oLang:: _get('INSTALLDB') . '</button></li>';
        } else {
            echo '<li class="list-group-item"><span class="label label-success">OK</span> ' . oLang:: _get('READYTOGO') . ' </li>';
        }
        $this->isDevelopModelEnableV7();  //todo
        $this->isAdminExistsReady();
        $this->isGAuthenticatorReady();
        $this->isWPUpToDate();
        $this->isGoogleScanV7();  //todo
        $this->isAdFirewallReadyV7(); //todo
        $this->isSignatureUpToDateV7(); //todo
        if (OSE_CMS == 'joomla') {
            $this->checkSysPlugin();
        }
    }


    public function isDevelopModelEnableV7()
    {
        $audit = new oseFirewallAuditV7 ();
        $audit->isDevelopModelEnableV7(true);
    }

    public function isAdFirewallReadyV7()
    {
        $audit = new oseFirewallAuditV7 ();
        $audit->isAdFirewallReadyV7(true);
    }

    public function isGoogleScanV7()
    {
        $audit = new oseFirewallAuditV7();
        $audit->isGoogleScanV7(true);
    }

    public function isSignatureUpToDateV7()
    {
        $audit = new oseFirewallAuditV7 ();
        $audit->isSignatureUpToDateV7(true);
    }
    public function googleRotv7()
    {
        $audit = new oseFirewallAuditV7 ();
        return $audit->googleRotv7();
    }
}