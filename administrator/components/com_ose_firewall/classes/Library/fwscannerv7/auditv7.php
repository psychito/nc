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
define('adminfolder','wp-admin');
define('contentfolder', 'wp-content');
define('includesfolder', 'wp-includes');
define ('uploadfolder', 'uploads');
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
oseFirewallBase::callLibClass('audit','audit');
class oseFirewallAuditV7 extends oseFirewallAudit
{
    private $warning = array();
    private $urls = array();
    public function __construct()
    {
        oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
        $this->urls = oseFirewall::getDashboardURLs();
    }
    public function isDevelopModelEnableV7($print = true)
    {
        $return = '';
        if(OSE_CMS == "joomla")
        {
            $url = "index.php?option=com_ose_firewall&view=bsconfigv7";
        }else{
            $url = 'admin.php?page=ose_fw_bsconfigv7';
        }
        $dbReady = oseFirewall::isDBReady();
        $action = ($print == true) ? '<a class="btn btn-danger btn-xs fx-button" href ="' . $url . '" ><i class="glyphicon glyphicon-wrench"></i> Fix It</a>' : '';
        if ($dbReady == true) {
            if(oseFirewallBase::isFirewallV7Active())
            {
                $flag = true;
            }else{
                $flag = false;
            }
            if ($flag ==false) {
                $this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> ' . oLang::_get('DISDEVELOPMODE') . " " . $action . "</li>";
            } else {
                $return = '<li class="list-group-item"><span class="label label-success">OK</span> ' . oLang::_get('DEVELOPMODE_DISABLED') . ' </li>';
            }
        }
        if ($print == true) {
            echo $return;
        } else {
            return $return;
        }
    }

    public function isAdFirewallReadyV7($print = true)
    {
        $return = '';
        if(OSE_CMS == "joomla")
        {
            $url = "index.php?option=com_ose_firewall&view=bsconfigv7";
        }else{
            $url = 'admin.php?page=ose_fw_bsconfigv7';
        }
        $oseFirewallStat = new oseFirewallStatPro();
        $isReady = $oseFirewallStat->isAdFirewallReadyV7();
        $action = ($print == true) ? '<a class="btn btn-danger btn-xs fx-button" href ="' .$url . '" target="_blank"><i class="glyphicon glyphicon-wrench"></i> Fix It</a>' : '';
        if (!$isReady) {
            $this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> ' . oLang::_get('ADVANCERULESNOTREADY') . " " . $action . " </li>";
        } else {
            $return = '<li class="list-group-item"><span class="label label-success">OK</span> ' . oLang::_get('ADVANCERULES_READY') . ' </li>';
        }
        if ($print == true) {
            echo $return;
        } else {
            return $return;
        }
    }


    public function isGoogleScanV7($print = true)
    {
        $return = '';
        $oseFirewallStat = new oseFirewallStatPro();
        $enabled = $oseFirewallStat->isGoogleScanV7();
        $action = ($print == true) ? '<a href="javascript:void(0)" onclick="fixGoogleScanV7()" class="btn btn-danger btn-xs fx-button"><i class="glyphicon glyphicon-wrench"></i> Fix It</a>' : '';
        if ($enabled == true) {
            $this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> ' . oLang::_get('GOOGLE_IS_SCANNED') . ". " . $action . "</li>";
        }
        if ($print == true) {
            echo $return;
        } else {
            return $return;
        }
    }

    public function isSignatureUpToDateV7($print = true)
    {
        $return = '';
        oseFirewall::callLibClass('update', 'updatePatterns');
        $update = new updatePattern();
        $version=  $update ->checkPatternVersion('ath');
        if(OSE_CMS == "joomla")
        {
            $url = "index.php?option=com_ose_firewall&view=bsconfigv7";
        }else{
            $url = 'admin.php?page=ose_fw_bsconfigv7';
        }
        $action = ($print == true) ? '<a href="' . $url . '" class="btn btn-danger btn-xs fx-button"><i class="glyphicon glyphicon-wrench"></i> Fix It</a>' : '';
        if ($version['status'] ==1) {
            $return = '<li class="list-group-item"><span class="label label-success">OK</span> ' . oLang::_get('SIGNATURE_UPTODATE') . "</li>";
        } else {
            $this->warning[] = $return = '<li class="list-group-item"><span class="label label-warning">Warning</span> ' . oLang::_get('SIGNATURE_OUTDATED') . ". " . $action . ' </li>';
        }
        if ($print == true) {
            echo $return;
        } else {
            return $return;
        }
    }


    private function getReportContent()
    {
        $this->isDevelopModelEnableV7(false);
        $this->isAdminExistsReady(false);
        $this->isGAuthenticatorReady(false);
        $this->isWPUpToDate(false);
        $this->isGoogleScanV7(false);
        $this->isAdFirewallReadyV7(false);
        $this->isSignatureUpToDateV7(false);
        $this->checkRegisterGlobals(false);
        $this->checkSafeMode(false);
        $this->checkURLFopen(false);
        $this->checkDisplayErrors(false);
        $this->checkDisableFunctions(false);
        $config_var = oseFirewall::getConfigVars();

        if (!empty($this->warning)) {
            $report = "<div style='font-weight: bold; color: red;'>Please note that your website is not 100% secure. Please review the following items in the <a href='" . $config_var->url . "/wp-admin/admin.php?page=ose_firewall'>dashboard</a> and fix them.</div>";
            $report .= '<ul class="list-group">';
            foreach ($this->warning as $warning) {
                $report .= '<li class="list-group-item">' . $warning . '</li>';
            }
            $report .= '</ul>';
        } else {
            $report = "<div style='font-weight: bold; color: #49FF40;'>Great! Everything looks right now.</div>";
        }
        $report .= "<br/>";
        $total = $this->getTotalBlockWebsites();
        $report .= "<div>Total website blocked since Centrora security is installed: " . $total . "</div>";
        return $report;
    }

    public function googleRotv7()
    {
        oseFirewall::callLibClass('fwscannerv7', 'fwscannerv7');
        $fwscannerv7 = new oseFirewallScannerV7();
        $settings = $fwscannerv7->getFirewallSettingsfromDb();
        if($settings['status']==1)
        {
            $fwscannerv7->updateSettings(22,0);
            return oseFirewallBase::prepareSuccessMessage('');
        }else{
            return oseFirewallBase::prepareErrorMessage("There was some problem in disabling Google Bot scanning ");
        }
    }

}


