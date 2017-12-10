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
if (version_compare(PHP_VERSION, '5.3.0') < 0)
{
	die("Centrora requires PHP 5.3.0, please contact your hosting company to update your PHP version. It will take them 5 seconds to do so.");
}
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
if (!defined('ODS')) {
	define('ODS', DIRECTORY_SEPARATOR);
}
if (!defined('OFRONTENDSCAN')) {define('OFRONTENDSCAN', false);}
if (!defined('OSEFWDIR')) {define('OSEFWDIR', dirname(__FILE__).ODS);}
require_once (OSEFWDIR.'assets'.ODS.'config'.ODS.'define.php');
require_once (OSE_FWFRAMEWORK.ODS.'oseFirewallJoomla.php');
// Do a pre-requisity check for PHP version;
ini_set("display_errors", "on");
$ready = oseFirewall::preRequisitiesCheck();
if ($ready == false)
{	
	if (oseFirewall::isBackendStatic())
	{	
		oseFirewall::showNotReady();
	}
	else 
	{
		return;	
	}
}
// If PHP 5.3.0 is satisfied, continue;
require_once (OSEFWDIR.'vendor/autoload.php');
// Load the OSE Framework ;
$oseFirewall = new oseFirewall();
$systemReady = $oseFirewall -> checkSystem () ;
$oseFirewall -> initSystem ();

if ($oseFirewall -> isBackend ())
{
	if ($systemReady[0] == false)
	{
		$oseFirewall -> loadBackendBasicFunctions();
		echo $systemReady[1];
		exit;
	}
	else
	{
		oseFirewall::checkHtaccess();
		$oseFirewall -> loadBackendFunctions ();
		//background scan for shawn
		$bgscan = oRequest::getInt('bgscan', 0);
		if ($bgscan == 1) {
			oseFirewall::callLibClass('vsscanner', 'vsscanner');
			$scanner = new virusScanner ();
			$scanner->bgscan();
		}
	}
}
