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
class sccaudit
{
	private $warning = array();
	private $urls = array();
	public function __construct()
	{
		oseFirewall::callLibClass('firewallstat', 'firewallstatPro');
		$this->urls = oseFirewall::getDashboardURLs();
	}
	public function test_cases()
	{
		$url[0] = "/../../wp-config.php";
		$url[1] = "/../../../wp-config.php";
		$url[2] = "/../../configuration.php";
		$url[3] = "/../../../configuration.php";
		$url[4] = "/../../etc/passwd";
		return $url;
	}

	public function curl_test($url,$plugin = "")
	{
	$ch = curl_init();
//	print_r(OSE_WPURL."/wp-content/plugins".$url);
	curl_setopt($ch, CURLOPT_URL, OSE_WPURL."/wp-content/plugins".$url);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	session_write_close();
	$result = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	echo "    the header status is ".$httpcode;
//	session_start();
	curl_close($ch);
		print_r($result);
//		return $result;
	}

	public function run_Testcases($url)
	{
		$i = 0;
		foreach($url as $value)
		{
			$result[$i] = $this->curl_test($value);
			$i++;
		}
//		return $result;
	}

	public function checkResponseCode($httpcode){
		if($httpcode == 404 ||httpcode == 200) {
			return false;
		}
		else {
			return true;
		}
	}
}


