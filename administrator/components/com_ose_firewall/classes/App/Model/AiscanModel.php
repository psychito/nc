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

class AiscanModel extends BaseModel
{
    public function __construct()
    {

    }

    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageIPs', 'aiscan.js', false);

    }

    public function getCHeader()
    {
        return oLang:: _get('AI_SCAN_TITLE');
    }

    public function getCDescription()
    {
        return oLang:: _get('AI_SCAN_DESC');
    }

    public function aiscan($samples)
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->aiscan($samples);
        return $return;
    }

    public function getPatterns()
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->getPatterns();
        return $return;
    }

    public function deletePattern($ids)
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->deletePattern($ids);
        return $return;
    }

    public function addPattern($pattern, $type)
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->addPattern($pattern, $type);
        return $return;
    }

    public function contentScan()
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->contentScan();
        return $return;
    }

    public function getSamples()
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->getSamples();
        return $return;
    }

    public function resetSamples()
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->resetSamples();
        return $return;
    }

    public function aiscan_main()
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->aiscan_main();
        return $return;
    }

    public function aiscan_finish()
    {
        oseFirewall::callLibClass('vsscanner', 'aiscanner');
        $scanner = new aiScanner ();
        $return = $scanner->aiscan_finish();
        return $return;
    }
}