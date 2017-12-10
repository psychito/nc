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

class CfscanModel extends BaseModel
{
    public function __construct()
    {

    }

    public function loadLocalScript()
    {
        $this->loadAllAssets();
        oseFirewall::loadJSFile('CentroraManageIPs', 'cfscan.js', false);

    }

    public function getCHeader()
    {
        //return oLang:: _get('CORE_SCAN_TITLE');
        if (oseFirewall::checkSubscriptionStatus(false) == false) {
            return '<div class="subscribe-header">' . oLang::_get('CORE_SCAN_TITLE') . '</div>';
        } else {
            return oLang::_get('CORE_SCAN_TITLE');
        }
    }

    public function getCDescription()
    {
        //return oLang:: _get('CORE_SCAN_DESC');
        if (oseFirewall::checkSubscriptionStatus(false) == false) {
            return '<div class="subscribe-subheader">' . oLang::_get('CORE_SCAN_DESC') . '</div>';
        } else {
            return oLang::_get('CORE_SCAN_DESC');
        }
    }

    public function getBriefDescription()
    {
        //return oLang::_get('CORE_SCAN_DESC_BRIEF');
        return '<div class="subscribe-desc">' . oLang::_get('CORE_SCAN_DESC_BRIEF') . '</div>';
    }

    public function showSubPic()
    {
        return OSE_FWPUBLICURL . "/images/premium/coredic.png";
    }

    public function showSubDesc()
    {
        return oLang::_get('CORE_SCAN_DESC_SLOGAN');
    }

    public function cfscan()
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        if (OSE_CMS == 'wordpress') {
            $return = $scanner->wpcfscan();
        } else {
            $return = $scanner->jcfscan();
        }
        return $return;
    }

    public function getMultiSite()
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $scanner->getMultiSite();
    }

    public function suitecfscan($path, $type, $version)
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->suitecfscan($path, $type, $version);
        $return['cms'] = $type;
        return $return;
    }

    public function catchVirusMD5()
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->catchVirusMD5();
        return $return;
    }

    public function addToAi($id)
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->addToAi($id);
        return $return;
    }

    public function suitePathDetect($path)
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->suitePathDetect($path);
        return $return;
    }
    public function checkCoreFilesExixts()
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->coreFileExists();
        return $return;
    }
    public function downloadCoreFiles($cms, $version)
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->download_CoreFiles($cms,$version);
        return $return;
    }
    public function checkCoreFilesExistsSuite($cms,$version)
    {
        oseFirewall::callLibClass('vsscanner', 'cfscanner');
        $scanner = new cfScanner ();
        $return = $scanner->coreFileExistsSuite($cms,$version);
        return $return;
    }
}