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

require_once (dirname(__FILE__).ODS.'oseFirewallBase.php');
class oseFirewall extends oseFirewallBase {
	protected static $option = 'com_ose_firewall';
	public function __construct () {
		$this->setDebugMode (false);
	}
	public function initSystem()
	{
		if (OFRONTENDSCAN==false)
		{
			$this->startSession ();
		}
		if (JOOMLA15==true) {
			include_once(JPATH_SITE.'/libraries/joomla/document/renderer.php');
			include_once(JPATH_SITE.'/libraries/joomla/utilities/arrayhelper.php');
			include_once(JPATH_SITE.'/libraries/joomla/environment/response.php');
		}
	}
	protected function loadViews () {
		$view = JRequest::getVar('view');
		$tmpl = JRequest :: getVar('tmpl');
		if (empty ($tmpl)) {
			JRequest :: setVar('tmpl', 'component');
		}
		if (empty ($view))
		{
			oseFirewall::dashboard();
		}
		else
		{
			oseFirewall::$view();
		}
	}
	protected static function addMenuActions () {
	}
	protected static function getOEMClass () {
		if (!class_exists('CentroraOEM')) {
			oseFirewall::callLibClass('oem', 'oem');
		}
	}
	public static function getmenus(){
		self::getOEMClass();
		$oem = new CentroraOEM() ;
		$favIconPath = $oem->getFavicon();
		if (JOOMLA15 == false) {
			$db = JFactory :: getDBO();
			$query = "SELECT * FROM `#__menu` WHERE `alias` =  ".$db->Quote(OSE_WORDPRESS_FIREWALL);
			$db->setQuery($query);
			$results = $db->loadResult();
			if (empty ($results)) {
				$query = "UPDATE `#__menu` SET `alias` =  ".$db->Quote(OSE_WORDPRESS_FIREWALL).", `path` =  ".$db->Quote(OSE_WORDPRESS_FIREWALL).", `published`=1, `img` = ".$db->Quote($favIconPath)."  WHERE `component_id` = ( SELECT extension_id FROM `#__extensions` WHERE `element` ='com_ose_firewall')  AND `client_id` = 1 ";
				$db->setQuery($query);
				$db->query();
			}
			$db->closeDBO();
		}
		$extension = 'com_ose_firewall';
		$view = JRequest :: getVar('view');

		$menu = '<div class="bs-component">';
		$menu .= '<div class="navbar navbar-default col-sm-12" style="padding-right: 0px;">';
		$menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
		$menu .= '<ul id ="nav" class="nav navbar-nav">';

        $menu .= '<li ';
        $menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
        $menu .= '><a href="index.php?option=com_ose_firewall" class="dropdown-toggle">';
        $menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_d.png>';
        $menu .= oLang::_get('OSE_DASHBOARD') . '</a></li>';


		// Search for Malware Menu;
		$menu .= '<li ';
		$menu .= (in_array($view, array('vsscan', 'vsreport', 'surfscan', 'cfscan','vlscan','fpscan'))) ? 'class="active dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
		$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_s.png>';
		$menu .= oLang::_get('SEARCHFORMALWARE') . '<b class="caret"></b></a>';

		// SubMenu Anti-Hacking Starts;
		$menu .= '<ul class="dropdown-menu">';

		$menu .= '<li ';
		$menu .= ($view == 'vsscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsscan">' . oLang::_get('DEEPSCAN') . '</a></li>';

		$menu .= '<li ';
		$menu .= ($view == 'surfscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=surfscan">' . oLang::_get('SURF_SCAN') . '</a></li>';

		$menu .= '<li ';
		$menu .= ($view == 'cfscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=cfscan">' . oLang::_get('CORE_SCAN') . '</a></li>';

		$menu .= '<li ';
		$menu .= ($view == 'mfscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=mfscan">' . oLang::_get('MF_SCAN') . '</a></li>';

		$menu .= '<li ';
		$menu .= ($view == 'vsreport') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=vsreport">' . oLang::_get('VSREPORT') . '</a></li>';
		if (JOOMLA15 == false) {
			$menu .= '<li ';
			$menu .= ($view == 'vlscan') ? 'class="active"' : '';
			$menu .= '><a href="index.php?option=' . $extension . '&view=vlscan">' . oLang::_get('Vl_SCAN') . '</a></li>';
		}
		$menu .= '<li ';
		$menu .= ($view == 'fpscan') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=fpscan">' . oLang::_get('FILE_PERM_SCAN') . '</a></li>';
		$menu .= '</ul>';

		// SubMenu Anti-Hacking Ends;
		$menu .= '</li>';

		//Backup menu starts
		$menu .= '<li ';
		$menu .= (in_array($view, array('backup', 'advancedbackup', 'authentication', 'gitbackup'))) ? 'class="dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
		$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_b.png>';
		$menu .= oLang::_get('O_BACKUP') . '<b class="caret"></b></a>';

		// SubMenu Anti-Virus Starts;
		$menu .= '<ul class="dropdown-menu">';
		//git backup
		$menu .= '<li ';
		$menu .= ($view == 'gitbackup') ? 'class="active"' : '';
        if (class_exists('SConfig'))
        {
            $menu .= '><a href="index.php?option=' . $extension . '&view=gitbackupsuite">' . oLang::_get('GITBACKUP') . '</a></li>';

        }else{
            $menu .= '><a href="index.php?option=' . $extension . '&view=gitbackup">' . oLang::_get('GITBACKUP') . '</a></li>';

        }
		$menu .= '</ul>';
		// SubMenu Anti-Hacking Ends;
		$menu .= '</li>';

        if(oseFirewallBase::isSuite()) {
            if (oseFirewallBase::isFirewallV7Active()) {
                $v7Style = "display:block";
                $v6style = "display:none";
                $v7default = "display:none";
            } else if (oseFirewallBase::isFirewallV6Active()) {
                $v7Style = "display:none";
                $v6style = "display:block";
                $v7default = "display:none";
            } else {
                $v7Style = "display:none";
                $v6style = "display:none";
                $v7default = "display:block";
            }
        }else{
            $v7Style = "";
            $v6style = "";
            $v7default = "";
        }

		// Firewall Settings Menu
		$menu .= '<li id="dropdownMenu1"';
		$menu .= (in_array($view, array('manageips', 'variables', 'rulesets', 'countryblock', 'bsconfig', 'upload','audit'))) ? 'class="dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
		$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_f.png>';
		$menu .= oLang::_get('FIREWALLSETINGS') . '<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts;
		$menu .= '<ul class="dropdown-menu dropdown-menu-middle" aria-labelledby="dropdownMenu1">';

        //NEW FIREWALL VIEW TAB FOR VERSION 7 DEFAULT IOF V6 AND V7 ARE TURNED OFF
        $menu .= '<div id="v7-tab-default" style="'.$v7default.'" >';
        $menu .= '<li onclick="enableV7()" ';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=bsconfigv7">' . 'Enable Firewall Scanner V7.0 <sup>(Beta)</sup>' . '</a></li>';


        $menu .= '<li onclick="enableV6()"';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=bsconfigv7">' . 'Enable Firewall Scanner  V6.6 <sup>(Stable)</sup>'. '</a>';
        $menu .= '</div>';


        //NEW FIREWALL VIEW TAB FOR VERSION 7
        $menu .= '<div id="v7-tab" style="'.$v7Style.'" >';
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=bsconfigv7">'  . 'Firewall Scanner V7.0 <sup>(Beta)</sup>' . '</a></li>';


        $menu .= '<li id="nav-to-v6"';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#" >' . '<b>Switch to Firewall Version 6.6 <sup>(Stable)</sup></b>'. '</a>';
        $menu .= '</div>';



        //OLD FIREWALL VIEW TAB FOR VERSION 6
        $menu .= '<div id="v6-tab" style="'.$v6style.'" >';
        $menu .= '<li id="nav-to-v7" ';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#">' . '<b>Enable Firewall V7.0 <sup>(Beta)</b></sup>' . '</a></li>';

        //OLD FIREWALL VIEW FOR VERSION 7
        $menu .= '<li id="nav-fsv6"';
        $menu .= ($view == 'ose_fw_bsconfigv7') ? 'class=""' : '';
        $menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . 'Firewall Scanner V6.6 <sup>(Stable)</sup>'. '</a>';
        $menu .= '<ul class="sub-dropdown-menu" aria-labelledby="dropdownMenu1">';
        // SubMenu LOG Starts;
        $menu .= '<li ';
        $menu .= ($view == 'manageips') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=manageips">' . oLang::_get('WEBATTACTS') . '</a></li>';
        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_fileextension') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=upload">' . oLang::_get('FILEUPLOADINGLOGS') . '</a></li>';		// SubMenu LOG Ends;

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_variables') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=variables">' . oLang::_get('VARIABLES_MANAGEMENT') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_countryblock') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=countryblock">' . oLang::_get('COUNTRYBLOCK') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_bsconfig') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=bsconfig">' . oLang::_get('FIREWALL_CONFIGURATION') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_rulesets') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=rulesets">' . oLang::_get('FIREWALL_RULES') . '</a></li>';

        $menu .= '<li ';
        $menu .= ($view == 'ose_fw_audit') ? 'class="active"' : '';
        $menu .= '><a href="index.php?option=' . $extension . '&view=audit">' . oLang::_get('AUDIT_WEBSITE') . '</a></li>';
        $menu .= '</div>';


        //First UL Ends
        $menu .='</ul></div>';


        //Second UL Starts
        $menu .= '<div class="navbar-collapse collapse navbar-responsive-collapse">';
        $menu .= '<ul id ="nav" class="nav navbar-nav" style="margin-top: 3px !important;">';


		//Update menu
		#server version: -1 Old, 0 Same, +1 New
		$serverversion = self::getServerVersion();
		$isOutdated = (self::getVersionCompare($serverversion) > 0)?true:false;

		$oem = new CentroraOEM();
		$oemShowNews = $oem->showNews();
		$urls = $oemShowNews ? self::getDashboardURLs() : null;
		oseFirewall::loadJSFile('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
		self::getAjaxScript();

		if ($isOutdated) {
			#pass update url to js to run through ajax. Update handled by url function.
			$menu .= '<li ';
			$menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
			$menu .= '><a href="#" onclick= "showAutoUpdateDialogue(\''.$serverversion.'\', \''."https://www.centrora.com/blog/changelog".'\');return false;" class="dropdown-toggle">';
			$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_u_yellow.png>';
			$menu .= 'Update to: '. $serverversion.'</a></li>';
		}else{
			$menu .= '<li ';
			$menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
			$menu .= '><a href="#" class="dropdown-toggle">';
			$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_u.png>';
			$menu .= oLang::_get('TOP_UPTODATE') . '</a></li>';
		}


		//Schedule Menu
		$menu .= '<li ';
		$menu .= (in_array($view, array('cronjobs'))) ? 'class="active dropdown"' : 'class="dropdown"';
		$menu .= '><a href="index.php?option=' . $extension . '&view=cronjobs" class="dropdown-toggle">';
		$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_t.png>';
		$menu .= oLang::_get('SCHEDULETASKS') . '</a>';
		// SubMenu Anti-Hacking Starts;
		$menu .= '<ul class="dropdown-menu">';
		$menu .= '<li ';
		$menu .= ($view == 'cronjobs') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=cronjobs">' . oLang::_get('CRONJOBS') . '</a></li>';
		$menu .= '</ul>';
		$menu .= '</li>';

		// Centrora Security Settings  Menu

		$menu .= '<li ';
		$menu .= (in_array($view, array('adminemails', 'cronjobs', 'configuration'))) ? 'class="dropdown"' : 'class="dropdown"';
		$menu .= '><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
		$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_m.png>';
		$menu .= oLang::_get('MANAGE') . '<b class="caret"></b></a>';
		// SubMenu Anti-Virus Starts;
		$menu .= '<ul class="dropdown-menu">';
		if (JOOMLA15 == false) {
			$menu .= '<li ';
			$menu .= ($view == 'adminemails') ? 'class="active"' : '';
			$menu .= '><a href="index.php?option=' . $extension . '&view=adminemails">' . oLang::_get('ADMINEMAILS') . '</a></li>';
		}
		$menu .= '<li ';
		$menu .= ($view == 'configuration') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=configuration">' . oLang::_get('INSTALLATION') . '</a></li>';

		if (class_exists('SConfig'))
		{
			// About Menu
			$menu .= '<li ';
			$menu .= (in_array($view, array('activation'))) ? 'class="active"' : '';
			$menu .= '><a href="index.php?option=' . $extension . '&view=activation">' . oLang::_get('ACTIVATION_CODES') . '</a></li>';
			// About Ends
		}
		$menu .= self::addSuiteMenu();
		$menu .= '</ul>';
		$menu .= '</li>';

		// Premium Menu
		$menu .= '<li ';
		$menu .= (in_array($view, array('login'))) ? 'class="dropdown"' : 'class="dropdown"';
		$menu .= '><a href="index.php?option=' . $extension . '&view=login" class="dropdown-toggle">';
		$menu .= '<img src=' . OSE_FWPUBLICURL . '/images/topbar/icon_p.png>';
		$menu .= oLang::_get('MY_ACCOUNT') . '</a>';
		// SubMenu Anti-Virus Starts;

		$menu .= '<ul class="dropdown-menu">';
		$menu .= '<li ';
		$menu .= ($view == 'login') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=' . $extension . '&view=login">' . oLang::_get('LOGIN_OR_SUBSCIRPTION') . '</a></li>';
		$menu .= '</ul>';
		$menu .= '</li>';
		// About Ends


		// Main Feature Ends;
		$menu .= '</ul></div></div></div>';
		return $menu;

	}
	protected static function addSuiteMenu () {
		$option = JRequest::getVar('option', null);
		$menu = '';

		$menu .= '<li ';
		$menu .= ($option == 'com_users') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_users&view=users">User Manager</a></li>';

		$menu .= '<li ';
		$menu .= ($option == 'com_installer') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_installer">Extension Manager</a></li>';

		$menu .= '<li ';
		$menu .= ($option == 'com_admin') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_admin&view=sysinfo">System Information</a></li>';

		$menu .= '<li ';
		$menu .= ($option == 'com_config') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_config">Global Configuration</a></li>';

		$menu .= '<li ';
		$menu .= ($option == 'com_plugins') ? 'class="active"' : '';
		$menu .= '><a href="index.php?option=com_plugins&view=plugins">Plugin Manager</a></li>';

		$menu .= '<li ';
		$menu .= ($option == 'com_login') ? 'class="active"' : '';

		$menu .= '><a href="index.php?option=com_login&task=logout&'.self::loadNounce().'=1">Logout</a></li>';

		return $menu;
	}
	public static function getAjaxScript() {
		return "var ajaxurl = \"".OURI::base()."index.php\";".
		"var option=\"".self::$option."\";";
	}
	public static function showLogo()
	{
		$head = '<nav class="" role="navigation" style="margin-bottom: -9px;">';
		$head .= '<div class ="everythingOnOneLine col-lg-12">
					<div class ="col-sm-12 wrap-top">';

		$oem = new CentroraOEM();
		$oemCustomer = $oem->hasOEMCustomer();
		$oemShowNews = $oem->showNews();
		if ($oemCustomer) {
			$head .= $oem->addLogo();
		}
		else
		{
			$head .= '<div class="col-lg-2 logo"><img src="' . OURI::base() . 'components/com_ose_firewall/public/images/topbar/whitelogo.png" width="340px" alt ="Centrora Logo"/></div>' . $oem->showOEMName();
		}

		#server version: -1 Old, 0 Same, +1 New
		$serverversion = self::getServerVersion();
		$isOutdated = (self::getVersionCompare($serverversion) > 0)?true:false;
		$head .='<div id ="versions"> <div class ="'.(($isOutdated==true)?'version-outdated':'version-updated').'"><i class="glyphicon glyphicon-'.(($isOutdated==true)?'remove':'ok').'"></i>  '.self::getVersion ().'</div>';
		$urls = $oemShowNews? self::getDashboardURLs() : null;
		oseFirewall::loadJSFile ('CentroraUpdateApp', 'VersionAutoUpdate.js', false);
		self::getAjaxScript();
        $isOutdated = true;
		if ($isOutdated) {
			$head .= '<button class="version-update" type="button" onclick="showAutoUpdateDialogue(\''.$serverversion.'\', \''."https://www.centrora.com/blog/changelog".'\')"/><i class="glyphicon glyphicon-refresh"></i> Update to : '.$serverversion.'</button>';
		}
		$head .= '</div>';
		if ($oemShowNews) {
			$hasNews = self::checkNewsUpdated();
            $head .= '<div class="centrora-news"><i class="glyphicon glyphicon-bullhorn"></i> <a class="color-white" href="https://www.centrora.com/blog/changelog" target="_blank">What\'s New? </a><i class="glyphicon glyphicon-' . (($hasNews == true) ? 'asterisk' : '') . ' color-magenta"></i></div>';
		}
		if (OSE_CMS == 'joomla' && !class_exists('Sconfig'))
		{
			$head .='<div class="back-to-jm"><a class="joomla-home" href="index.php" ><i class="fa fa-joomla"></i> '.oLang::_get('BACK_TO_JOOMLA').'</a></div>';
		}
		if (oseFirewall::affiliateAccountExists()==false && CentroraOEM::hasOEMCustomer()==false)
		{
			$head .='<div class="centrora-affiliates"><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#affiliateFormModal" href="#" ><i class="glyphicon glyphicon-magnet"></i> '.oLang::_get('AFFILIATE_TRACKING').'</button></div>';
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
		$head .= '<div class="navbar-top-joomla  col-lg-12">
					 <div class="col-lg-1 col-sm-6 col-xs-6 col-md-6">
						<div class="pull-left">
						</div>
					 </div>
					<div class="col-lg-11 col-sm-6 col-xs-6 col-md-6">
					 <div class="pull-right">
						<ul class="userMenu ">';

		$head .= $oem->getTopBarURL ();
		if (OSE_CMS == 'joomla')
		{
			$head .= $oem->getHomeLink();
		}
		$head .=	'</ul>
					 </div>
					</div>
				 </div>';
		$head .='</div>';

		if (class_exists('SConfig')) {
			$head .= '<div class="navbar-top">
					 <div class="col-lg-1 col-sm-6 col-xs-6 col-md-6">
						<div class="pull-left">
						</div>
					 </div>
					<div class="col-lg-11 col-sm-6 col-xs-6 col-md-6">
					 <div class="pull-right">
						<ul id="suite-userMenu" class="userMenu ">';
		} else {}
		$head .='</nav>';
		#take care of ajax js to run unpdate
		if(isset($_POST['updateaction']) && !empty($_POST['updateaction'])) {
			$action = $_POST['updateaction'];
			switch($action) {
				case 'upgrade-plugin' : self::runUpdate() ;break;
			}
		}

		echo $head;
	}
	# Run the automatic update procedures
	private static function runUpdate(){
		oseFirewall::callLibClass('panel','panel');
		$panel2 = new panel ();
		return $panel2->runAutomaticUpdate();
	}
	#Check for version updates
	private static function getServerVersion(){
		oseFirewall::callLibClass('panel','panel');
		$panel = new panel ();
		return $panel->getLatestVersion();
	}
	#Compare local version with the update server version
	private static function getVersionCompare($serverversion){
		$localversion = self::getVersionXML();
		$compareversions = version_compare($serverversion, $localversion) ;
		return $compareversions;
	}
	private static function getVersionXML () {
		if (JOOMLA15 == true) {
			$xml = simplexml_load_file(JPATH_ADMINISTRATOR .'/components/com_ose_firewall/ose_firewall.xml');
		}
		else {
			$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_ose_firewall/ose_firewall.xml');
		}
		return (string)$xml->version;
	}
	private static function getVersion () {
		$localversion = self::getVersionXML();
		return 'Version: '.$localversion;
	}
	public static function checkVersion()
	{
		$localversion = self::getVersionXML();
		return $localversion;
	}
	public static function loadNounce () {
		if (JOOMLA15 == true) {
			return JUtility::getToken();
		}
		else  {
			return JSession::getFormToken();
		}
	}
	public static function getScanPath () {
		oseFirewall::loadRequest ();
		$scan_path = oRequest::getVar('scan_path', null);
		$dbscapath = oseFirewall::getConfiguration('vsscan');
		if (!empty($scan_path))
		{
			return $scan_path;
		}
		elseif (class_exists("SConfig") && !empty($dbscapath['data']['scanPath']))
		{
			return $dbscapath['data']['scanPath'];
		}else
		{
			return addslashes(JPATH_SITE);
		}
	}
	public static function getCronjobURL () {
		$url = 'index.php?option=com_ose_firewall&view=cronjobs';
		return $url;
	}
	public static function getViewResultURL () {
		$url = 'index.php?option=com_ose_firewall&view=vsreport';
		return $url;
	}
	public static function getDashboardURLs () {
		$url = array ();
		$url[]= 'index.php?option=com_ose_firewall&view=vsscan';
		$url[]= 'index.php?option=com_ose_firewall&view=manageips';
		$url[]= 'index.php?option=com_ose_firewall&view=gitbackup';
		$url[]= 'index.php?option=com_ose_firewall&view=configuration';
		$url[]= 'index.php?option=com_ose_firewall&view=scanconfig';
		$url[]= 'index.php?option=com_ose_firewall&view=seoconfig';
		$url[]= 'index.php?option=com_ose_firewall&view=rulesets';
		$url[]= 'index.php?option=com_ose_firewall&view=bsconfig';
		$url[]= 'index.php?option=com_ose_firewall&view=news';
		$url[]= 'index.php?option=com_ose_firewall&view=cronjobs';
		return $url;
	}
	public static function getAdminEmail () {
		$config = oseJoomla::getConfig();
		return $config->mailfrom;
	}
	public static function getSiteURL () {
		return JURI::root();
	}
	public static function getConfigVars () {
		if (class_exists('SConfig'))
		{
			$config = new SConfig();
			return $config;
		}
		elseif (class_exists('JConfig'))
		{
			$config = new JConfig();
			return $config;
		}
	}
	public static function loadJSFile ($tag, $filename, $remote) {
		if ($remote == false)
		{
			$url = OSE_FWURL.'public/js/'.$filename;
		}
		else
		{
			$url = $filename;
		}

		$document = JFactory::getDocument();
		$document->addScript ($url, "text/javascript", false, false);
		//JHtml::script($url);
	}
	public static function loadLanguageJSFile ($tag, $filename, $remote) {
		if ($remote == false)
		{
			$url = OSE_FWURL.'public/messages/'.$filename;
		}
		else
		{
			$url = $filename;
		}
		if (JOOMLA15==true) {
			$document = JFactory::getDocument();
			unset($document->_scripts[JURI::root(true) . '/media/system/js/mootools.js']);
			$document->addScript ($url, "text/javascript", true, false);
		}
		else {
			JHtml::script($url);
		}
	}
	public static function loadCSSFile ($tag, $filename, $remote) {
		if ($remote == false)
		{
			$url = OSE_FWURL.'public/css/'.$filename;
		}
		else
		{
			$url = $filename;
		}
		if (JOOMLA15==true) {
			$document = JFactory::getDocument();
			$document->addStylesheet( $url, 'text/css', null, array() );
		}
		else {
			JHtml::stylesheet($url);
		}
	}
	public static function loadCSSURL ($tag, $url) {
		if (JOOMLA15==true) {
			$document = JFactory::getDocument();
			$document->addStylesheet( $url, 'text/css', null, array() );
		}
		else {
			JHtml::stylesheet($url);
		}
	}
	public static function redirectLogin () {
		echo '<script type="text/javascript">location.href="index.php?option=com_ose_firewall&view=login"</script>';
	}
	public static function redirectSubscription () {
		echo '<script type="text/javascript">location.href="index.php?option=com_ose_firewall&view=subscription"</script>';
	}
	public static function isBadgeEnabled () {
		return true;
	}
	public static function getConfigurationURL () {
		return 'index.php?option=com_ose_firewall&view=bsconfig';
	}
	public static function checkHtaccess () {
		if (file_exists(JPATH_SITE.'/media/CentroraBackup') && !file_exists(JPATH_SITE.'/media/CentroraBackup/.htaccess'))
		{
			if (function_exists('copy')) {
				$result = @copy(OSEAPPDIR.'protected/.htaccess', JPATH_SITE.'/media/CentroraBackup/.htaccess');
			}
		}
	}

}