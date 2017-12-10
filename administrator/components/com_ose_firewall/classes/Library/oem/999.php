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

class CentroraOEM999 {
	public function __construct ($customer_id) {
		$this->customer_id = $customer_id;
	}
	public function showOEMName () {
        return '<div class="vendorname"> ME Security </div>';
	}
	public function getTopBarURL () {
		$urls = '<li><a href="http://www.marketingentourage.com.au/" title="My Account"><i class="glyphicon glyphicon-user"></i> <span class="hidden-xs hidden-sm hidden-md">My Account</span> </a></li>
				 <li><a href="http://www.marketingentourage.com.au/" id="support-center" title="Support"><i class="glyphicon glyphicon-cd"></i> <span class="hidden-xs hidden-sm hidden-md">Support</span></a></li>
				 <li><a href="http://www.marketingentourage.com.au/" title="Malware Removal"><i class="glyphicon glyphicon-screenshot"></i> <span class="hidden-xs hidden-sm hidden-md">Malware Removal</span></a></li>';
		return $urls;
	}
	public function addLogo () {
		return '<div class="logo"><img src="'.OSE_FWPUBLICURL.'/css/oem/'.$this->customer_id.'/imgs/logo-header.png" width="90px" alt ="ME Security"/></div>'.$this->showOEMName ();
	}
	public function defineVendorName () {
		define('OSE_WORDPRESS_FIREWALL', 'ME Security');
        define('OSE_WORDPRESS_FIREWALL_SHORT', 'ME Security');
        define('OSE_OEM_URL_MAIN', 'http://www.marketingentourage.com.au/');
        define('OSE_OEM_URL_HELPDESK', 'http://www.marketingentourage.com.au/');
        define('OSE_OEM_URL_MALWARE_REMOVAL', 'http://www.marketingentourage.com.au/');
        define('OSE_OEM_URL_ADVFW_TUT', 'http://www.marketingentourage.com.au/');
	}
	public function requiresPasscode () {
		return false;
	}
    public function showNews (){
        return true;
    }

    public function getCallToActionAndFooter()
    {
        return '<div class="row row-set" style="margin-top:14px;">
                                <div class="col-sm-12" style="padding-left: 0px; padding-right: 20px;">
                                 <a href="http://www.marketingentourage.com.au/" target="_blank"><div class="call-to-action">
                                    <div class="call-to-action-txt">
                                    <img width="35" height="35" alt="C_puma" src="'.OSE_FWPUBLICURL.'css/oem/'.$this->customer_id.'/imgs/5s.png"> &nbsp;
                                    Schedule your scanning and update with ME Security <sup>Now</sup></div>
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
		        ME Security is a portfolio of ME Security. &copy;  <?php echo date("Y"); ?> <a
					href="http://www.marketingentourage.com.au/" target="_blank">ME Security</a>. All Rights Reserved. <br /> Credits
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
                    ME Security Member Login<small> You can login here with yourME Security Account to activate your premium services</small>
                </div>';
        $html .= '</div>';
        echo $html;
    }
}