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
oseFirewall::callLibClass('fwscanner', 'fwscanner');
oseFirewall::loadJSON();
require_once("fwscannerad.php");

class fwscannerwp extends oseFirewallScannerAdvance
{

    public function scanAttack()
    {
        $scanResult = $this->checkCountryStatus();
        if ($scanResult == true) {
            // Reset scanning result;
            $scanResult = null;
        } else {
            $scanResult = $this->ScanLayer2();
            if (!empty ($scanResult)) {
                $scannerType = $scanResult['0']['type'];
                $status = $this->getBlockIP();
                $this->addACLRule($status, $this->sumImpact($scanResult));
                foreach ($scanResult as $result) {
                    $this->detected .= implode(",", $result ['detcontent_content']);
                    $content = oseJSON::encode($result ['detcontent_content']);
                    //each 'get' or 'post' request may triggers more than one kind of attack type
                    //record each attack type individually
                    $attacktypes = oseJSON::decode($result ['attackTypeID']);
                    foreach ($attacktypes as $attacktype) {
                        $attacktypeID = $attacktype;
                        $this->addDetContent($attacktypeID, $content, $result ['rule_id'], $result['keyname']);
                    }
                }
                $this->controlAttack($scannerType);
            }
        }
        unset ($scanResult);
    }

    protected function controlAttack($scannerType)
    {
        $notified = $this->getNotified();
        $this->updateVisits();
        $scannerType = 'ad';
        $url = $this->filterAttack($scannerType);
        $this->sendEmail('filtered', $notified);
    }
}