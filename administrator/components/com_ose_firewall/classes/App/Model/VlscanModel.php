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
require_once('BaseModel.php');

class VlscanModel extends BaseModel
{
    public function __construct()
    {

    }

    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageIPs', 'vlscan.js', false);
    }

    public function getCHeader()
    {
        return oLang:: _get('Vl_SCAN_TITLE');
    }

    public function getCDescription()
    {
        $desc = oLang:: _get('Vl_SCAN_DESC') . ' ';
        if (OSE_CMS == 'wordpress')
            $desc .= oLang:: _get('Vl_SCAN_CRED_WPSCAN');
        return $desc;
    }

    public function getLastScanResult()
    {
        oseFirewall::callLibClass('vlscanner', 'vlscanner');
        $vlsscanner = new vulnerabilityScanner();
        return $vlsscanner->getLastScanRecord();
    }

    public function vlscan($step)
    {
        oseFirewall::callLibClass('vlscanner', 'vlscanner');
        $vs = new vulnerabilityScanner();
        return $vs->runVulnerabilityScan($step);

    }
}