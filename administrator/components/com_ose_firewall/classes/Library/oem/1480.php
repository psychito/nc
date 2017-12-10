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

class CentroraOEM1480 {

    public function __construct ($customer_id) {
        $this->customer_id = $customer_id;
    }
    public function showOEMName () {
        return '<div class="vendorname"> Bayside Solutions Security </div>';
    }
    public function getTopBarURL () {
        $urls = '<li><a href="http://www.baywebsites.com.au/" title="My Account"><i class="glyphicon glyphicon-user"></i> <span class="hidden-xs hidden-sm hidden-md">My Account</span> </a></li>
				 <li><a href="http://www.baywebsites.com.au/" id="support-center" title="Support"><i class="glyphicon glyphicon-cd"></i> <span class="hidden-xs hidden-sm hidden-md">Support</span></a></li>
				 <li><a href="http://www.baywebsites.com.au/" title="Malware Removal"><i class="glyphicon glyphicon-screenshot"></i> <span class="hidden-xs hidden-sm hidden-md">Malware Removal</span></a></li>';
        return $urls;
    }
    public function addLogo () {
        if(OSE_CMS == "joomla")
        {
            return '<div class="logo"><img src="'.OSE_FWPUBLICURL.'/css/oem/'.$this->customer_id.'/imgs/logo-header.png" width="90px" alt ="Bayside Solutions Security"/></div>'.$this->showOEMName ();

        }else {
            return '<div class="logo"><img src="'.OSE_FWPUBLICURL.'css/oem/'.$this->customer_id.'/imgs/logo-header.png" width="90px" alt ="Bayside Solutions Security"/></div>'.$this->showOEMName ();

        }
    }
    public function defineVendorName () {
        define('OSE_WORDPRESS_FIREWALL', 'Bayside Solutions Security');
        define('OSE_WORDPRESS_FIREWALL_SHORT', 'Bayside Solutions Security');
        define('OSE_OEM_URL_MAIN', 'http://www.baywebsites.com.au/');
        define('OSE_OEM_URL_HELPDESK', 'http://www.baywebsites.com.au/');
        define('OSE_OEM_URL_MALWARE_REMOVAL', 'http://www.baywebsites.com.au/');
        define('OSE_OEM_URL_ADVFW_TUT', 'http://www.baywebsites.com.au/');
    }
    public function requiresPasscode () {
        return false;
    }
    public function showNews (){
        return true;
    }
    public function showFooter () {
        return '<div class="footer-bottom">
		    <div class="container">
		      <p class="pull-center">
		        Bayside Solutions Security is a portfolio of Bayside Solutions. &copy;  <?php echo date("Y"); ?> <a
					href="http://www.baywebsites.com.au" target="_blank">Bayside Solutions</a>. All Rights Reserved. <br /> Credits
				to: <a href="http://www.centrora.com" target="_blank">Centrora Security!&#0174;</a>
		      </p>
		    </div>
		  </div>';
    }
    public function getHomeLink() {
        return '<li><a href="http://www.baywebsites.com.au" title="Home">Quick links:&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-home"></i> <span class="hidden-xs hidden-sm hidden-md">'.OSE_WORDPRESS_FIREWALL_SHORT.'</span> </a></li>';
    }

    public function getCalltoAction()
    {
        if(OSE_CMS == "joomla")
        {
            $ose_fwpubliccurl = OSE_FWPUBLICURL.ODS;
        }else {
            $ose_fwpubliccurl = OSE_FWPUBLICURL;
        }
        return '<div class="row row-set" style="margin-top:14px;">
                                <div class="col-sm-12" style="padding-left: 0px; padding-right: 20px;">
                                 <a href="http://www.baywebsites.com.au" target="_blank"><div class="call-to-action">
                                    <div class="call-to-action-txt">
                                    <img width="35" height="35" alt="C_puma" src="'.$ose_fwpubliccurl.'css/oem/'.$this->customer_id.'/imgs/5s.png"> &nbsp;
                                    Schedule your scanning and update with Bayside Solutions Security <sup>Now</sup></div>
                                </div></a>
                                </div>
                                <div class="col-sm-3">
                                <div id="bg-scan" class="vs-bg-dark col-sm-12" data-target="#bgModel" data-toggle="modal" style="display: none">
                                Background Scanning
                                </div>
                                </div>
                            </div>';
    }

    public function getCallToActionAndFooter()
    {
        if(OSE_CMS == "joomla")
        {
            $ose_fwpubliccurl = OSE_FWPUBLICURL.ODS;
        }else {
            $ose_fwpubliccurl = OSE_FWPUBLICURL;
        }
        return '<div class="row row-set footer-box" style="margin-top:14px;">
                                <div class="col-sm-12 footer-innerbox" style="padding-left: 0px; padding-right: 20px;">
                                 <a href="http://www.baywebsites.com.au" target="_blank"><div class="call-to-action">
                                    <div class="call-to-action-txt">
                                    <img width="35" height="35" alt="C_puma" src="'.$ose_fwpubliccurl.'css/oem/'.$this->customer_id.'/imgs/5s.png"> &nbsp;
                                    Schedule your scanning and update with Bayside Solutions Security <sup>Now</sup></div>
                                </div></a>
                                </div>
                                <div class="col-sm-3">
                                <div id="bg-scan" class="vs-bg-dark col-sm-12" data-target="#bgModel" data-toggle="modal" style="display: none">
                                Background Scanning
                                </div>
                                </div>
                            </div>
		   <div class="row">
              <div id="footer" class="col-sm-12">
		      <p class="pull-center">
		        Bayside Solutions Security is a portfolio of Bayside Solutions. &copy;  <?php echo date("Y"); ?> <a
					href="http://www.baywebsites.com.au" target="_blank">Bayside Solutions</a>. All Rights Reserved. <br /> Credits
				to: <a href="http://www.centrora.com" target="_blank">Centrora Security!&#0174;</a>
		      </p>
		    </div>
		  </div>';
    }

    public function showLoginHeader()
    {
        $html = '<div class="bs-callout bs-callout-info fade in">';
        $html .= '<span class="icon-separator">

                </span>';
        $html .= '<div class="header-wrapper">
                    Bayside Solutions Member Login<small> You can login here with your Bayside Solutions Account to activate your premium services</small>
                </div>';
        $html .= '</div>';
        echo $html;
    }

}