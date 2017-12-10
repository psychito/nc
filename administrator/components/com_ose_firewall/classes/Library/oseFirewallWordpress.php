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
require_once(dirname(__FILE__) . ODS . 'oseFirewallBase.php');

class oseFirewall extends oseFirewallBase
{
    protected static $option = 'ose_firewall';
    private $wp_login_php;
    public function __construct()
    {
        $this->setDebugMode(false);
    }

    protected function loadViews()
    {

    }

    public function initSystem()
    {

        add_action('init', array($this, 'startSession'), 1);
        oseFirewall::callLibClass('firewallstat', 'firewallstatWordpress');

        $oseFirewallStat = new oseFirewallStat();
        $results = $oseFirewallStat->getConfiguration('scan');
        if (!empty($results['data']['strongPassword']) && $results['data']['strongPassword'] == 1) {
            add_action('user_profile_update_errors', 'oseFirewall::updateValidatePassword', 0, 3);
        }
        if (!empty($results['data']['loginSlug'])) {
            add_action('plugins_loaded', array($this, 'plugins_loaded'), 2);
            // add_action( 'admin_notices', array( $this, 'admin_notices' ) );
            add_action('wp_loaded', array($this, 'wp_loaded'));

            add_filter('site_url', array($this, 'site_url'), 10, 4);
            add_filter('wp_redirect', array($this, 'wp_redirect'), 10, 2);
        }
    }

    protected static function addMenuActions()
    {
        add_action('admin_menu', 'oseFirewall::showmenus');
    }

    public static function getmenus()
    {
        $extension = 'ose_firewall';
        $view = $_GET['page'];
        $menu = '<div class="bs-component">';
        $menu .= '<div class="navbar navbar-default col-sm-12" style="padding-right: 0px;">';
        $menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
        $menu .= '<ul id ="nav" class="nav navbar-nav">';

        // Dashboard Menu
        $menu .= '<li id="dropdownMenu1"';
        $menu .= (in_array($view, array('ose_fw_manageips', 'ose_fw_variables', 'ose_fw_rulesets', 'ose_fw_countryblock', 'ose_fw_bsconfig','ose_fw_bsconfigv7', 'ose_fw_upload'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="admin.php?page=ose_firewall">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_d.png>';
        $menu .= 'Dashboard' . '</a>';
        $menu .= '</li>';


        // Search for Malware Menu;
        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_vsscan', 'ose_fw_scanreport', 'ose_fw_surfscan', 'ose_fw_cfscan','ose_fw_vlscan','ose_fw_fpscan'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_s.png>';
        $menu .= oLang::_get('SEARCHFORMALWARE') . '<b class="caret"></b></a>';

        // SubMenu Anti-Hacking Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_vsscan') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_vsscan">' . oLang::_get('DEEPSCAN') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_surfscan') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_surfscan">' . oLang::_get('SURF_SCAN') . '</a></li>';

//        $menu .= '<div style="font-family:arial;font-size:small;border-top-width:1px;border-top-style:solid;border-top-color:#d0d0d0;"></div>';
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_cfscan') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_cfscan">' . oLang::_get('CORE_SCAN') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_mfscan') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_mfscan">' . oLang::_get('MF_SCAN') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_scanreport') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_scanreport">' . oLang::_get('VSREPORT') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_vlscan') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_vlscan">' . oLang::_get('Vl_SCAN') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_fpscan') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_fpscan">' . oLang::_get('FILE_PERM_SCAN') . '</a></li>';
        $menu .= '</ul>';

        // SubMenu Anti-Hacking Ends;
        $menu .= '</li>';

        //Backup menu starts
        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_backup', 'ose_fw_advancedbackup', 'ose_fw_authentication', 'ose_fw_gitbackup'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_b.png>';
        $menu .= oLang::_get('O_BACKUP') . '<b class="caret"></b></a>';

        // SubMenu Anti-Virus Starts;
        $menu .= '<ul class="dropdown-menu">';

        //git backup
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_gitbackup') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_gitbackup">' . oLang::_get('GITBACKUP') . '</a></li>';

        $menu .= '</ul>';
        // SubMenu Anti-Hacking Ends;
        $menu .= '</li>';

        // Firewall Settings Menu
        $menu .= '<li id="dropdownMenu1"';
        $menu .= (in_array($view, array('ose_fw_manageips', 'ose_fw_variables', 'ose_fw_rulesets', 'ose_fw_countryblock', 'ose_fw_bsconfig', 'ose_fw_upload','ose_fw_audit'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_f.png>';
        $menu .= oLang::_get('FIREWALLSETINGS') . '<b class="caret"></b></a>';
        // SubMenu Starts;
        $menu .= '<ul class="dropdown-menu dropdown-menu-middle" aria-labelledby="dropdownMenu1">';
        //here is the view for version 7

        //NEW FIREWALL VIEW TAB FOR VERSION 7 DEFAULT IOF V6 AND V7 ARE TURNED OFF
        $menu .= '<div id="v7-tab-default">';
        $menu .= '<li onclick="enableV7()" ';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="admin.php?page=ose_fw_bsconfigv7">' . 'Enable Firewall Scanner V7.0 <sup>(Beta)</sup>' . '</a></li>';


        $menu .= '<li onclick="enableV6()"';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#" >' . 'Enable Firewall Scanner  V6.6 <sup>(Stable)</sup>'. '</a>';
        $menu .= '</div>';


        //NEW FIREWALL VIEW TAB FOR VERSION 7
        $menu .= '<div id="v7-tab">';
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="admin.php?page=ose_fw_bsconfigv7">' . 'Firewall Scanner V7.0 <sup>(Beta)</sup>' . '</a></li>';


        $menu .= '<li id="nav-to-v6"';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#" >' . '<b>Switch to Firewall Scanner Version 6.6 <sup>(Stable)</sup></b>'. '</a>';
        $menu .= '</div>';



        //OLD FIREWALL VIEW TAB FOR VERSION 6
        $menu .= '<div id="v6-tab">';
        $menu .= '<li id="nav-to-v7" ';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#">' . '<b>Enable Firewall Scanner V7.0 <sup>(Beta)</b></sup>' . '</a></li>';

        //OLD FIREWALL VIEW FOR VERSION 7
        $menu .= '<li id="nav-fsv6"';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . 'Firewall Scanner V6.6 <sup>(Stable)</sup>'. '</a>';
        $menu .= '<ul class="sub-dropdown-menu" aria-labelledby="dropdownMenu1">';
        // SubMenu LOG Starts;
        $menu .= '<li ';
        $menu .= ($view == 'manageips') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_manageips">' . oLang::_get('WEBATTACTS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_fileextension') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_upload">' . oLang::_get('FILEUPLOADINGLOGS') . '</a></li>';
        // SubMenu LOG Ends;

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_variables') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_variables">' . oLang::_get('VARIABLES_MANAGEMENT') . '</a></li>';


        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_countryblock') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_countryblock">' . oLang::_get('COUNTRYBLOCK') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_bsconfig') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_bsconfig">' . oLang::_get('FIREWALL_CONFIGURATION') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_rulesets') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_rulesets">' . oLang::_get('FIREWALL_RULES') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_audit') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_audit">' . oLang::_get('AUDIT_WEBSITE') . '</a></li>';
        $menu .= '</div>';


        $menu .= '</ul></li>';



        $menu .= '</ul>';
        // SubMenu Anti-Virus Ends;
        $menu .= '</li>';

        //First UL Ends
        $menu .='</ul></div>';

        //Second UL Starts
        $menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
        $menu .= '<ul id ="nav" class="nav navbar-nav" style="margin-top: 3px !important;">';


        //Update menu
        #Get update server version
        $serverversion = "";
        $plugins = get_plugin_updates();
        foreach ((array)$plugins as $plugin_file => $plugin_data) {
            if ($plugin_data->update->slug == "ose-firewall") {
                $serverversion = $plugin_data->update->new_version;
            }
        }
        $isOutdated = (self::getVersionCompare($serverversion) > 0) ? true : false;

        $oem = new CentroraOEM();
        $oemShowNews = $oem->showNews();
        $urls = $oemShowNews ? self::getDashboardURLs() : null;
        oseFirewall::loadJSFile('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
        self::getAjaxScript();

        if ($isOutdated) {
            #pass update url to js to run through ajax. Update handled by url function.
            $file = 'ose-firewall/ose_wordpress_firewall.php';
            $updateurl = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
            $activateurl = esc_url(wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $file), 'activate-plugin_' . $file));
            $menu .= '<li ';
            $menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
            $menu .= '><a href="#" onclick= "showAutoUpdateDialogue(\'' . $serverversion . '\', \'' . "https://www.centrora.com/blog/changelog" . '\',
														\'' . $updateurl . '\',
														\'' . $file . '\',
														\'' . $activateurl . '\');return false;" class="dropdown-toggle">';
            $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_u_yellow.png>';
            $menu .= 'Update to: '. $serverversion.'</a></li>';
        }else{
            $menu .= '<li ';
            $menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
            $menu .= '><a href="#" class="dropdown-toggle">';
            $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_u.png>';
            $menu .= oLang::_get('TOP_UPTODATE') . '</a></li>';
        }


        //Schedule Menu
        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_cronjobs'))) ? 'class="active dropdown"' : 'class="dropdown"';
        $menu .= '><a href="admin.php?page=ose_fw_cronjobs" class="dropdown-toggle">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_t.png>';
        $menu .= oLang::_get('SCHEDULETASKS') . '</a>';
        // SubMenu Anti-Hacking Starts;
        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_cronjobs') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_cronjobs">' . oLang::_get('CRONJOBS') . '</a></li>';

        $menu .= '</ul>';

        $menu .= '</li>';

        // Centrora Security Settings  Menu

        $menu .= '<li ';
        $menu .= (in_array($view, array('ose_fw_adminemails', 'ose_fw_cronjobs', 'configuration'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_m.png>';
        $menu .= oLang::_get('MANAGE') . '<b class="caret"></b></a>';
        // SubMenu Anti-Virus Starts;
        $menu .= '<ul class="dropdown-menu">';
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_adminemails') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_adminemails">' . oLang::_get('ADMINEMAILS') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'configuration') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_configuration">' . oLang::_get('INSTALLATION') . '</a></li>';

        $menu .= '</ul>';

        $menu .= '</li>';
        // Centrora Security Settings Ends

        // Premium Menu
        $menu .= '<li ';
        $menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
//        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
        $menu .= '><a href="admin.php?page=ose_fw_login" class="dropdown-toggle">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . 'images/topbar/icon_p.png>';
//        $menu .= oLang::_get('MY_ACCOUNT') . '<b class="caret"></b></a>';
        $menu .= oLang::_get('MY_ACCOUNT') . '</a>';
        // SubMenu Anti-Virus Starts;

        $menu .= '<ul class="dropdown-menu">';

        $menu .= '<li ';
        $menu .= ($view == 'login') ? 'class="active"' : '';
        $menu .= '><a href="admin.php?page=ose_fw_login">' . oLang::_get('LOGIN_OR_SUBSCIRPTION') . '</a></li>';

        $menu .= '</ul>';

        $menu .= '</li>';
        // About Ends

        // Main Feature Ends;
        $menu .= '</ul></div></div>';
        //subscription
        $menu.= '<div id="subscription-popup">
                                <span>This is a Premium Feature &nbsp;<i class="fa fa-certificate"></i></span>
                                <img id="close_popup" src=' . OSE_FWPUBLICURL . 'images/close_cross.png>
                                <p>Premium features includes</p>
                                <ul class="pop_ul">
                                    <li>Free Malware Removal Service for annual subscribers until the website is 100% clean.</li>
                                    <li>View infected files in detail by browsing source codes with suspicious codes highlighted..</li>
                                    <li>Clean or quarantine infected files within the scan report without accessing FTP.</li>
                                    <li>Monitor website malware with scheduled automatic virus scanning and email notifications.</li>
                                    <li>Automated backup to Bitbucket every 1 hour.</li>
                                    <li>Store your files securely in Bitbuckets.</li>
                                    <li>2GB free spaces for each website in Bitbucket.</li>
                                    <li>Roll back from copies in Bitbucket in case of server fault</li>
                                    <li>Save your hosting space and save you money</li>
                                </ul>
                                <a  target="_blank" href="http://www.centrora.com/services/">
                                    <button id="btn_gopremium">Go Premium</button></a>
                            </div>';
        $menu .='</div>';
        return $menu;
    }

    public static function showmenus()
    {
        oseFirewall::callLibClass('oem', 'oem');
        $oem = new CentroraOEM();
        $oemCustomer = $oem->hasOEMCustomer();
        $oemShowNews = $oem->showNews();

        $db = oseFirewall::getDBO();
        $query = "SELECT `value` FROM `#__ose_secConfig` WHERE `type` = 'secManager'";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        $db->closeDBO();
        $user_ID = get_current_user_id();
        if (!empty($results)) {
            foreach ($results as $single) {
                if ($user_ID == $single->value) {
                    $user = new WP_User($user_ID);
                    $user->add_cap('manage_centrora');
                    $permission = 'manage_centrora';
                    break;
                } else {
                    $permission = 'manage_options';
                }
            }
        } else {
            $permission = 'manage_options';
        }

        add_menu_page(OSE_WORDPRESS_FIREWALL_SETTING, OSE_WORDPRESS_FIREWALL, $permission, 'ose_firewall', 'oseFirewall::dashboard', $oem->getFavicon());
        add_submenu_page('ose_firewall', OSE_DASHBOARD_SETTING, OSE_DASHBOARD, $permission, 'ose_firewall', 'oseFirewall::dashboard');
        add_submenu_page('ose_firewall', SURF_SCAN, SURF_SCAN, $permission, 'ose_fw_surfscan', 'oseFirewall::surfscan');
        add_submenu_page('ose_firewall', DEEPSCAN, DEEPSCAN, $permission, 'ose_fw_vsscan', 'oseFirewall::vsscan');
        //add_submenu_page( 'ose_firewall', CLAMAV, CLAMAV, $permission, 'ose_fw_clamav', 'oseFirewall::clamav' );
        add_submenu_page('ose_fw_configuration', VSREPORT, VSREPORT, $permission, 'ose_fw_scanreport', 'oseFirewall::vsreport');
        add_submenu_page('ose_fw_configuration', Vl_SCAN, Vl_SCAN, $permission, 'ose_fw_vlscan', 'oseFirewall::vlscan');
        if (!empty($_GET['aiscan']) && $_GET['aiscan'] == 1) {
            add_submenu_page('ose_firewall', AI_SCANNER, AI_SCANNER, $permission, 'ose_fw_aiscan', 'oseFirewall::aiscan');
        }

        add_submenu_page('ose_firewall', MANAGE_IPS, MANAGE_IPS, $permission, 'ose_fw_manageips', 'oseFirewall::manageips');
        //add_submenu_page( 'ose_firewall', ADD_IPS, ADD_IPS, $permission, 'ose_fw_addips', 'oseFirewall::ipform' );
        add_submenu_page('ose_fw_configuration', AUDIT_WEBSITE, AUDIT_WEBSITE, $permission, 'ose_fw_audit', 'oseFirewall::audit');
        add_submenu_page('ose_fw_configuration', FIREWALL_RULES, FIREWALL_RULES, $permission, 'ose_fw_rulesets', 'oseFirewall::rulesets');
        add_submenu_page('ose_fw_configuration', FIREWALL_CONFIGURATION, FIREWALL_CONFIGURATION, $permission, 'ose_fw_bsconfig', 'oseFirewall::bsconfig');
        add_submenu_page('ose_fw_configuration', VARIABLES, VARIABLES, $permission, 'ose_fw_variables', 'oseFirewall::variables');
        add_submenu_page('ose_fw_configuration', INSTALLATION, INSTALLATION, $permission, 'ose_fw_configuration', 'oseFirewall::configuration');
        add_submenu_page('ose_firewall', BACKUP, BACKUP, $permission, 'ose_fw_backup', 'oseFirewall::backup');
        add_submenu_page('ose_firewall', GITBACKUP, GITBACKUP, $permission, 'ose_fw_gitbackup', 'oseFirewall::gitbackup');//suraj

        //firewall scanner v7
        add_submenu_page('ose_firewall', FIREWALL_V7, FIREWALLV7, $permission, 'ose_fw_bsconfigv7', 'oseFirewall::bsconfigv7');
        add_submenu_page('ose_fw_configuration', BANPAGE, BANPAGE, $permission, 'ose_fw_banpagemgmt', 'oseFirewall::banpagemgmt');
        add_submenu_page('ose_fw_configuration', AUDITV7, AUDITV7, $permission, 'ose_fw_auditv7', 'oseFirewall::auditv7');
        add_submenu_page('ose_fw_configuration', IP_MANAGEMENT,IP_MANAGEMENT, $permission, 'ose_fw_ipmanagement', 'oseFirewall::ipmanagement');
        add_submenu_page('ose_fw_configuration',WHITELISTMGMT,WHITELISTMGMT,$permission,'ose_fw_whitemgmt','oseFirewall::whitelistmgmt');
        add_submenu_page('ose_fw_configuration', COUNTRYBLOCKV7, COUNTRYBLOCKV7, $permission, 'ose_fw_countryblockingv7', 'oseFirewall::countryblockingv7');

        //firewall scanner v7 stats
        add_submenu_page('ose_fw_configuration', STATS, STATS, $permission, 'ose_fw_bsconfigv7stats', 'oseFirewall::bsconfigv7stats');
        add_submenu_page('ose_fw_configuration', EMAILNOTIFICATION, EMAILNOTIFICATION, $permission, 'ose_fw_emailnotificationv7', 'oseFirewall::emailnotificationv7');

        add_submenu_page('ose_fw_configuration', AUTHENTICATION, AUTHENTICATION, $permission, 'ose_fw_authentication', 'oseFirewall::authentication');
        add_submenu_page('ose_fw_configuration', ADVANCEDBACKUP, ADVANCEDBACKUP, $permission, 'ose_fw_advancedbackup', 'oseFirewall::advancedbackup');
        add_submenu_page('ose_firewall', PERMCONFIG, PERMCONFIG, $permission, 'ose_fw_permconfig', 'oseFirewall::permconfig');
        add_submenu_page('ose_fw_configuration', ADMINEMAILS, ADMINEMAILS, $permission, 'ose_fw_adminemails', 'oseFirewall::adminemails');

        add_submenu_page('ose_fw_configuration', COUNTRYBLOCK, COUNTRYBLOCK, $permission, 'ose_fw_countryblock', 'oseFirewall::countryblock');
        add_submenu_page('ose_firewall', CRONJOBS, CRONJOBS, $permission, 'ose_fw_cronjobs', 'oseFirewall::cronjobs');
        add_submenu_page('ose_firewall', LOGIN_OR_SUBSCIRPTION, LOGIN_OR_SUBSCIRPTION, $permission, 'ose_fw_login', 'oseFirewall::login');
        add_submenu_page('ose_fw_configuration', SUBSCRIPTION, SUBSCRIPTION, $permission, 'ose_fw_subscription', 'oseFirewall::subscription');
        //add_submenu_page( 'ose_firewall', VERSION_UPDATE, VERSION_UPDATE, $permission, 'ose_fw_versionupdate', 'oseFirewall::versionupdate' );
        add_submenu_page('ose_fw_configuration', FILEEXTENSION, FILEEXTENSION, $permission, 'ose_fw_fileextension', 'oseFirewall::fileextension');
        add_submenu_page('ose_fw_configuration', AUTHENTICATION, AUTHENTICATION, $permission, 'ose_fw_authentication', 'oseFirewall::authentication');
        add_submenu_page('ose_fw_configuration', SEO_CONFIGURATION, SEO_CONFIGURATION, $permission, 'ose_fw_seoconfig', 'oseFirewall::seoconfig');
        add_submenu_page('ose_fw_configuration', SCAN_CONFIGURATION, SCAN_CONFIGURATION, $permission, 'ose_fw_scanconfig', 'oseFirewall::scanconfig');
        add_submenu_page('ose_fw_configuration', ANTIVIRUS_CONFIGURATION, ANTIVIRUS_CONFIGURATION, $permission, 'ose_fw_avconfig', 'oseFirewall::avconfig');
        add_submenu_page('ose_fw_configuration', ANTISPAM_CONFIGURATION, ANTISPAM_CONFIGURATION, $permission, 'ose_fw_spamconfig', 'oseFirewall::spamconfig');
        add_submenu_page('ose_fw_configuration', EMAIL_CONFIGURATION, EMAIL_CONFIGURATION, $permission, 'ose_fw_emailconfig', 'oseFirewall::emailconfig');
        add_submenu_page('ose_fw_configuration', EMAIL_ADMIN, EMAIL_ADMIN, $permission, 'ose_fw_emailadmin', 'oseFirewall::emailadmin');
        add_submenu_page('ose_fw_configuration', CORE_SCAN, CORE_SCAN, $permission, 'ose_fw_cfscan', 'oseFirewall::cfscan');
        add_submenu_page('ose_fw_configuration', FILE_PERM_SCAN, FILE_PERM_SCAN, $permission, 'ose_fw_fpscan', 'oseFirewall::fpscan');
        add_submenu_page('ose_fw_configuration', API_CONFIGURATION, API_CONFIGURATION, $permission, 'ose_fw_apiconfig', 'oseFirewall::apiconfig');
        add_submenu_page('ose_fw_configuration', MF_SCAN, MF_SCAN, $permission, 'ose_fw_mfscan', 'oseFirewall::mfscan');
        add_submenu_page('ose_fw_configuration', MF_SCAN, MF_SCAN, $permission, 'ose_fw_aiscan', 'oseFirewall::aiscan');


        if ($oemShowNews) {
            add_submenu_page('ose_fw_configuration', NEWS_TITLE, NEWS_TITLE, $permission, 'ose_fw_news', 'oseFirewall::news');
        }
        add_submenu_page('ose_fw_configuration', FILE_UPLOAD_MANAGEMENT, FILE_UPLOAD_MANAGEMENT, $permission, 'ose_fw_upload', 'oseFirewall::upload');
        //add_submenu_page( 'ose_firewall', ANTI_VIRUS_DATABASE_UPDATE, ANTI_VIRUS_DATABASE_UPDATE, $permission, 'ose_fw_versionupdate', 'oseFirewall::updateChecking' );
        if ($oemCustomer) {
            add_submenu_page('ose_fw_configuration', OEM_PASSCODE, OEM_PASSCODE, $permission, 'ose_fw_passcode', 'oseFirewall::passcode');
        }
    }

    public static function getAjaxScript()
    {
        //add_action('admin_head', 'oseFirewall::showAjaxHeader');
    }

    public static function showAjaxHeader()
    {
        echo '<script type="text/javascript" >';
        echo "var url = \"" . admin_url('admin-ajax.php') . "\";" .
            "var option=\"" . self::$option . "\";";
        echo '</script>';
    }

    public static function showLogo()
    {
        $oem = new CentroraOEM();
        $head = '<nav class="" role="navigation">';
        $head .= '<div class ="everythingOnOneLine col-md-12">
					<div class ="col-sm-12 wrap-top">';
        $oem = new CentroraOEM();
        $oemCustomer = $oem->hasOEMCustomer();
        $oemShowNews = $oem->showNews();
        if ($oemCustomer) {
            $head .= $oem->addLogo();
        } else {
            $head .= '<div class="col-lg-2 logo"><img src="' . OSE_FWPUBLICURL . '/images/topbar/whitelogo.png" width="340px" alt ="Centrora Logo"/></div>' . $oem->showOEMName();
        }
        #Get update server version
        $serverversion = "";
        $plugins = get_plugin_updates();
        foreach ((array)$plugins as $plugin_file => $plugin_data) {
            if ($plugin_data->update->slug == "ose-firewall") {
                $serverversion = $plugin_data->update->new_version;
            }
        }
        $isOutdated = (self::getVersionCompare($serverversion) > 0) ? true : false;
//        $head .= '<div id="div_dashboard"><a id="btn_dashboard" href="admin.php?page=ose_firewall" align=center><i class="fa fa-dashboard -x"></i>Dashboard</a></div>';
        $head .= '<div id ="versions"> <div class ="' . (($isOutdated == true) ? 'version-outdated' : 'version-updated') . '"><i class="glyphicon glyphicon-' . (($isOutdated == true) ? 'remove' : 'ok') . '"></i>  ' . self::getVersion() . '</div>';
        $urls = $oemShowNews ? self::getDashboardURLs() : null;
        oseFirewall::loadJSFile('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
        self::getAjaxScript();

        #pass update url to js to run through ajax. Update handled by url function.
        $file = 'ose-firewall/ose_wordpress_firewall.php';
        $updateurl = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
        $activateurl = esc_url(wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $file), 'activate-plugin_' . $file));

        if ($isOutdated) {
            $head .= '<button class="version-update" type="button"
						onclick="showAutoUpdateDialogue(\'' . $serverversion . '\', \'' . "https://www.centrora.com/blog/changelog" . '\',
														\'' . $updateurl . '\',
														\'' . $file . '\',
														\'' . $activateurl . '\')"/>
						<i class="glyphicon glyphicon-refresh"></i> Update to : ' . $serverversion . '</button>';
        }
        $head .= '</div>';
        if ($oemShowNews) {
            $hasNews = self::checkNewsUpdated();
            $head .= '<div class="centrora-news"><i class="glyphicon glyphicon-bullhorn"></i> <a class="color-white" href="https://www.centrora.com/blog/changelog" target="_blank">What\'s New? </a><i class="glyphicon glyphicon-' . (($hasNews == true) ? 'asterisk' : '') . ' color-magenta"></i></div>';
        }

        if (oseFirewall::affiliateAccountExists() == false && CentroraOEM::hasOEMCustomer() == false) {
            $head .= '<div class="centrora-affiliates"><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#affiliateFormModal" href="#" ><i class="glyphicon glyphicon-magnet"></i> ' . oLang::_get('AFFILIATE_TRACKING') . '</button></div>';
        }
        $head .='<div class="served-websites">We are now serving  <span id="numofWebsite"></span> websites.</div>';
        $head .='<div class="at-banner">
          <div class="at-banner__content">
            <div class="at-banner-pic"></div>
            <div class="at-banner__text">

            <b>Suffering from website malware and server security issues ?</b><br>
            We have a complete hosting solution which includes :<br>
            advanced <b>Centrora Security Solutions</b>, <br>
            and <b>High Performance</b> hosting services at <b>Affordable Prices</b>.
            </div>
            <a class="at-banner__button" target="_blank" href="https://centrora.com/services#suite">VPS - only $28.6/m</a>
            <a class="at-banner__button" style="margin-left: 20px;" target="_blank" href="https://centrora.com/services/hosting-services-pricing">Dedicated Servers</a>
          </div>
        </div>';
        $head .= oseFirewall::getmenus();
        $head .= '</div>';
//this is the old horiziontal bar
//        $head .= '<div class="navbar-top col-sm-12" id="navbar-top-wp">
//					 <div class="col-lg-1 col-sm-6 col-xs-6 col-md-6">
//						<div class="pull-left">
//						</div>
//					 </div>
//					<div class="col-lg-11 col-sm-6 col-xs-6 col-md-6">
//					 <div class="pull-right">
//						<ul class="userMenu ">';
        $head .= '<div class="navbar-top col-sm-12" id="navbar-top-wp">

					<div>
					 <div class="pull-right col-sm-12" style="background-color:#272634;">
						<ul class="userMenu ">';


        $head .= $oem->getTopBarURL();

        $head .= '<li><a href="index.php" title="Home"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs hidden-sm hidden-md">Home</span> </a></li>';
        $head .= '</ul>
					 </div>
					</div>
				 </div>';
        $head .= '</div>';
        $head .= '</nav>';

        #take care of ajax js to run unpdate
        if (isset($_POST['updateaction']) && !empty($_POST['updateaction'])) {
            $action = $_POST['updateaction'];
            switch ($action) {
                case 'upgrade-plugin' :
                    self::runUpdate();
                    break;
            }
        }
        echo $head;
    }

    #Compare local version with the update server version
    private static function getVersionCompare($serverversion)
    {
        $pluginData = get_plugin_data(OSEFWDIR . 'ose_wordpress_firewall.php');
        $localversion = $pluginData['Version'];
        $compareversions = version_compare($serverversion, $localversion);
        return $compareversions;
    }

    private static function getVersion()
    {
        $pluginData = get_plugin_data(OSEFWDIR . 'ose_wordpress_firewall.php');
        return 'Version: ' . $pluginData['Version'];
    }

    public static function checkVersion()
    {
        $pluginData = get_plugin_data(OSEFWDIR . 'ose_wordpress_firewall.php');
        return $pluginData['Version'];
    }

    public static function loadNounce()
    {
        if (!session_id()) {
            session_set_cookie_params(7200);
            session_start();
        }
        $_SESSION['centnounce'] = oseFirewall::createSecret();
        return $_SESSION['centnounce'];
    }

    private static function createSecret()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // allowed characters in Base32
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }
        return $secret;
    }
    public static function getScanPath()
    {
        oseFirewall::loadRequest();
        $scan_path = oRequest::getVar('scan_path', null);
        if (!empty($scan_path)) {
            return $scan_path;
        } else {
            return addslashes(OSE_ABSPATH);
        }
    }
    public static function getCronjobURL () {
        $url = 'admin.php?page=ose_fw_cronjobs';
        return $url;
    }
    public static function getViewResultURL () {
        $url = 'admin.php?page=ose_fw_scanreport';
        return $url;
    }
    public static function getDashboardURLs()
    {
        $url = array();
        $url[] = 'admin.php?page=ose_fw_vsscan';
        $url[] = 'admin.php?page=ose_fw_manageips';
        $url[] = 'admin.php?page=ose_fw_backup';
        $url[] = 'admin.php?page=ose_fw_gitbackup';
        $url[] = 'admin.php?page=ose_fw_configuration';
        $url[] = 'admin.php?page=ose_fw_scanconfig';
        $url[] = 'admin.php?page=ose_fw_seoconfig';
        $url[] = 'admin.php?page=ose_fw_rulesets';
        $url[] = 'admin.php?page=ose_fw_bsconfig';
        $url[] = 'admin.php?page=ose_fw_news';
        $url[] = 'admin.php?page=ose_fw_cronjobs';
        return $url;
    }

    public static function getAdminEmail()
    {
        return get_option('admin_email');
    }

    public static function getSiteURL()
    {
        return OSE_WPURL;
    }

    public static function getConfigVars()
    {
        $bloginfo = new stdClass();
        $bloginfo->url = get_bloginfo('url');
        $bloginfo->fromname = get_bloginfo('name');
        $bloginfo->mailfrom = get_bloginfo('admin_email');
        return $bloginfo;
    }

    public static function loadJSFile($tag, $filename, $remote)
    {
        if ($remote == false) {
            $url = OSE_FWURL . '/public/js/' . $filename;
        } else {
            $url = $filename;
        }
        wp_enqueue_script($tag, $url, array(), '1.0.0', true);
    }

    public static function loadLanguageJSFile($tag, $filename, $remote)
    {
        if ($remote == false) {
            $url = OSE_FWURL . '/public/messages/' . $filename;
        } else {
            $url = $filename;
        }
        wp_enqueue_script($tag, $url, array(), '1.0.0', true);
    }

    public static function loadCSSFile($tag, $filename, $remote)
    {
        if ($remote == false) {
            $url = OSE_FWURL . '/public/css/' . $filename;
        } else {
            $url = $filename;
        }
        wp_enqueue_style($tag, $url);
    }

    public static function loadCSSURL($tag, $url)
    {
        wp_enqueue_style($tag, $url);
    }

    public static function redirectLogin()
    {
        echo '<script type="text/javascript">location.href="admin.php?page=ose_fw_login"</script>';
    }

    public static function redirectSubscription()
    {
        echo '<script type="text/javascript">location.href="admin.php?page=ose_fw_subscription"</script>';
    }

    public static function isBadgeEnabled()
    {
        $results = wp_get_sidebars_widgets();
        $return = false;
        if (!empty($results)) {
            foreach ($results as $result) {
                if (!empty($result)) {
                    foreach ($result as $widget) {
                        if (strstr($widget, 'ose_badge_widget') != false) {
                            $return = true;
                            break;
                        }
                    }
                }
            }
        }
        return $return;
    }

    public static function getConfigurationURL()
    {
        return 'admin.php?page=ose_fw_bsconfig';
    }

    public static function updateValidatePassword($errors, $update, $userData)
    {
        $user_id = isset($userData->ID) ? $userData->ID : false;
        if (!empty($user_id)) {
            $password = (isset($_POST['pass1']) && trim($_POST['pass1'])) ? $_POST['pass1'] : false;
            $username = isset($_POST["user_login"]) ? $_POST["user_login"] : $userData->user_login;
            $user_info = get_userdata($user_id);
            $userRoles = $user_info->roles;
        }
        else {
            $password = (isset($userData->user_pass) && trim($userData->user_pass)) ? $userData->user_pass: false;
            $username = isset($userData->user_login) ? $userData->user_login : '';
            $userRoles = $userData->role;
        }
        if ($password == false || $username =='') {
            return $errors;
        }
        if ($errors->get_error_data("pass")) {
            return $errors;
        }
        $enforce = implode(', ', $userRoles);
        if ($enforce == 'administrator') {
            if (!oseFirewall::isStrongPasswd($password, $username)) {
                $errors->add('pass', "Please choose a stronger password. Use a mix of letters, numbers, and symbols in your password.");
                return $errors;
            }
        }
        return $errors;
    }

    public static function isStrongPasswd($passwd, $username)
    {
        $strength = 0;
        if (strlen(trim($passwd)) < 5)
            return false;
        if (strtolower($passwd) == strtolower($username))
            return false;
        if (preg_match('/(?:password|passwd|mypass|wordpress)/i', $passwd)) {
            return false;
        }
        if ($num = preg_match_all("/\d/", $passwd, $matches)) {
            $strength += ((int)$num * 10);
        }
        if (preg_match("/[a-z]/", $passwd))
            $strength += 26;
        if (preg_match("/[A-Z]/", $passwd))
            $strength += 26;
        if ($num = preg_match_all("/[^a-zA-Z0-9]/", $passwd, $matches)) {
            $strength += (31 * (int)$num);

        }
        if ($strength > 60) {
            return true;
        }
    }

    public function plugins_loaded()
    {
        global $pagenow;

        if (!is_multisite()
            && (strpos($_SERVER['REQUEST_URI'], 'wp-signup') !== false
                || strpos($_SERVER['REQUEST_URI'], 'wp-activate')) !== false
        ) {

            wp_die(__('This feature is not enabled.', 'wps-hide-login'));

        }

        $request = parse_url($_SERVER['REQUEST_URI']);

        if ((strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false
                || untrailingslashit($request['path']) === site_url('wp-login', 'relative'))
            && !is_admin()
        ) {

            $this->wp_login_php = true;

            $_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/' . str_repeat('-/', 10));

            $pagenow = 'index.php';

        } elseif (untrailingslashit($request['path']) === home_url($this->new_login_slug(), 'relative')
            || (!get_option('permalink_structure')
                && isset($_GET[$this->new_login_slug()])
                && empty($_GET[$this->new_login_slug()]))
        ) {

            $pagenow = 'wp-login.php';

        }

    }

    public function wp_loaded()
    {

        global $pagenow;

        if (is_admin()
            && !is_user_logged_in()
            && !defined('DOING_AJAX')
        ) {

            status_header(404);
            nocache_headers();
            include(get_404_template());
            exit;
        }

        $request = parse_url($_SERVER['REQUEST_URI']);

        if ($pagenow === 'wp-login.php'
            && $request['path'] !== $this->user_trailingslashit($request['path'])
            && get_option('permalink_structure')
        ) {

            wp_safe_redirect($this->user_trailingslashit($this->new_login_url())
                . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));

            die;

        } elseif ($this->wp_login_php) {

            if (($referer = wp_get_referer())
                && strpos($referer, 'wp-activate.php') !== false
                && ($referer = parse_url($referer))
                && !empty($referer['query'])
            ) {

                parse_str($referer['query'], $referer);

                if (!empty($referer['key'])
                    && ($result = wpmu_activate_signup($referer['key']))
                    && is_wp_error($result)
                    && ($result->get_error_code() === 'already_active'
                        || $result->get_error_code() === 'blog_taken')
                ) {

                    wp_safe_redirect($this->new_login_url()
                        . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));

                    die;

                }

            }

            $this->wp_template_loader();

        } elseif ($pagenow === 'wp-login.php') {

            global $error, $interim_login, $action, $user_login;

            @require_once ABSPATH . 'wp-login.php';

            die;

        }

    }

    private function wp_template_loader()
    {

        global $pagenow;

        $pagenow = 'index.php';

        if (!defined('WP_USE_THEMES')) {

            define('WP_USE_THEMES', true);

        }

        wp();

        if ($_SERVER['REQUEST_URI'] === $this->user_trailingslashit(str_repeat('-/', 10))) {

            $_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/wp-login-php/');

        }

        require_once(ABSPATH . WPINC . '/template-loader.php');

        die;

    }

    public function site_url($url, $path, $scheme, $blog_id)
    {

        return $this->filter_wp_login_php($url, $scheme);

    }

    public function network_site_url($url, $path, $scheme)
    {

        return $this->filter_wp_login_php($url, $scheme);

    }

    public function wp_redirect($location, $status)
    {

        return $this->filter_wp_login_php($location);

    }

    public function filter_wp_login_php($url, $scheme = null)
    {

        if (strpos($url, 'wp-login.php') !== false) {

            if (is_ssl()) {

                $scheme = 'https';

            }

            $args = explode('?', $url);

            if (isset($args[1])) {

                parse_str($args[1], $args);

                $url = add_query_arg($args, $this->new_login_url($scheme));

            } else {

                $url = $this->new_login_url($scheme);

            }

        }

        return $url;

    }

    private function use_trailing_slashes()
    {

        return ('/' === substr(get_option('permalink_structure'), -1, 1));

    }

    private function new_login_slug()
    {
        $confArray = $this->getConfiguration('scan');
        if (!empty($confArray['data']['loginSlug'])) {
            return $confArray['data']['loginSlug'];
        }
        return;
    }

    private function user_trailingslashit($string)
    {

        return $this->use_trailing_slashes()
            ? trailingslashit($string)
            : untrailingslashit($string);

    }

    public function new_login_url($scheme = null)
    {

        if (get_option('permalink_structure')) {

            return $this->user_trailingslashit(home_url('/', $scheme) . $this->new_login_slug());

        } else {

            return home_url('/', $scheme) . '?' . $this->new_login_slug();

        }
    }

    public static function checkHtaccess()
    {
        if (file_exists(dirname(dirname(OSEAPPDIR)) . '/CentroraBackup') && !file_exists(dirname(dirname(OSEAPPDIR)) . '/CentroraBackup/.htaccess')) {
            if (function_exists('copy')) {
                $result = @copy(OSEAPPDIR . 'protected/.htaccess', dirname(dirname(OSEAPPDIR)) . '/CentroraBackup/.htaccess');
            }
        }
    }

    public function update_option()
    {
        $oseFirewall = new oseFirewall();
        $oseFirewall->enhanceSysSecurity();
        oseFirewall::callLibClass('fwscanner', 'fwscannerwp');
        $oseFirewallScanner = new fwscannerwp();
        $oseFirewallScanner->ScanAttack();
    }

}