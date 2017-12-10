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
require_once('BaseModel.php');
class CronjobsModel extends BaseModel
{
    public function __construct()
    {
        $this->loadLibrary ();
        $this->loadDatabase ();
    }
    protected function loadLibrary()
    {
        oseFirewall::callLibClass('panel','panel');
    }
    public function loadLocalScript()
    {
        $this->loadAllAssets ();
        oseFirewall::loadJSFile ('CentroraCronjobs', 'cronjobs.js', false);
    }
    public function getCHeader()
    {
        if (oseFirewall::checkSubscriptionStatus(false) == false) {
            return '<div class="subscribe-header">' . oLang::_get('CRONJOBS_TITLE') . '</div>';
        } else {
            return oLang::_get('CRONJOBS_TITLE');
        }
    }
    public function getCDescription()
    {
        //return oLang::_get('CRONJOBS_DESC');
        if (oseFirewall::checkSubscriptionStatus(false) == false) {
            return '<div class="subscribe-subheader">' . oLang::_get('CRONJOBS_DESC') . '</div>';
        } else {
            return oLang::_get('CRONJOBS_DESC');
        }
    }
    public function getBriefDescription()
    {
        //return oLang::_get('CRONJOBS_DESC_BRIEF');
        return '<div class="subscribe-desc">' . oLang::_get('CRONJOBS_DESC_BRIEF') . '</div>';
    }
    public function showSubPic()
    {
        return OSE_FWPUBLICURL . "/images/premium/schedule.png";
    }

    public function showSubDesc()
    {
        return oLang::_get('CRONJOBS_DESC_SLOGAN');
    }

    public function saveCronConfig($custhours, $custweekdays, $schedule_type, $cloudbackuptype, $enabled, $gitbackupfrequency,$recieveEmail = false) {
        $settings =array();
        $settings['custhours'] = $custhours;
        $settings['custweekdays'] = $custweekdays;
        $settings['cloudbackuptype'] = $cloudbackuptype;
        $settings['enabled'] = $enabled;
        $settings['gitbackupfrequency'] = $gitbackupfrequency;
        $settings['recieveEmail'] = $recieveEmail;
        $encoded_settings = json_encode($settings);
        oseFirewall::callLibClass('gitBackup', 'GitSetup');
        $gitbackup = new GitSetup();
        $gitbackup->saveCronSettings($encoded_settings,$schedule_type);
        $panel = new panel ();
        return $panel->saveCronConfig($custhours, $custweekdays, $schedule_type, $cloudbackuptype, $enabled, $gitbackupfrequency);
    }
    public function getCronSettings ($schedule_type) {
        $panel = new panel ();
        $settings = json_decode(json_decode($panel->getCronSettings($schedule_type)));
        $return = array ();
        foreach ($settings as $key => $val) {
            switch ($key) {
                case 'sun':
                    $return[0] = ($val==true)?true:false;
                    break;
                case 'mon':
                    $return[1] = ($val==true)?true:false;
                    break;
                case 'tue':
                    $return[2] = ($val==true)?true:false;
                    break;
                case 'wed':
                    $return[3] = ($val==true)?true:false;
                    break;
                case 'thu':
                    $return[4] = ($val==true)?true:false;
                    break;
                case 'fri':
                    $return[5] = ($val==true)?true:false;
                    break;
                case 'sat':
                    $return[6] = ($val==true)?true:false;
                    break;
            }
        }
        $return['hour'] = $settings->hour;
        $return['cloudbt'] = $settings->cloudbackuptype;
        $return['enabled'] = $settings->enabled;
        $return['frequency'] = $settings->frequency;
        return $return;
    }

    public function getClearBackup()
    {
        $cronSettings = $this->getConfiguration('clearbackup');
        $limit = !empty($cronSettings) ? $cronSettings['data']['clearbackuptime'] : '';
        if (!empty($limit)) {
            $clearBackupOptions = '<option value="1000" selected>' . oLang::_get("LAST_FOREVER") . '</option>';
            $clearBackupOptions .= '<option value="7">' . oLang::_get("LAST_ONE_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="14">' . oLang::_get("LAST_TWO_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="21">' . oLang::_get("LAST_THREE_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="28">' . oLang::_get("LAST_FOUR_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="60">' . oLang::_get("LAST_TWO_MONTH") . '</option>';
            $clearBackupOptions .= '<option value="90">' . oLang::_get("LAST_THREE_MONTH") . '</option>';
            $clearBackupOptions .= '<option value="180">' . oLang::_get("LAST_HALF_YEAR") . '</option>';
            $re = "/\"" . $limit . "\"/";
            $subst = "\"" . $limit . "\" selected";

            $result = preg_replace($re, $subst, $clearBackupOptions, 1);
            echo $result;

        } else {
            $clearBackupOptions = '<option value="1000" selected>' . oLang::_get("LAST_FOREVER") . '</option>';
            $clearBackupOptions .= '<option value="7">' . oLang::_get("LAST_ONE_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="14">' . oLang::_get("LAST_TWO_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="21">' . oLang::_get("LAST_THREE_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="28">' . oLang::_get("LAST_FOUR_WEEK") . '</option>';
            $clearBackupOptions .= '<option value="60">' . oLang::_get("LAST_TWO_MONTH") . '</option>';
            $clearBackupOptions .= '<option value="90">' . oLang::_get("LAST_THREE_MONTH") . '</option>';
            $clearBackupOptions .= '<option value="180">' . oLang::_get("LAST_HALF_YEAR") . '</option>';

            echo $clearBackupOptions;
        }
    }


    public function getReceiveEmailMenu($receiveEmail)
    {
        if($receiveEmail == 1)
        {
            $menu = '<option value="1" selected>' . oLang::_get("O_RECEIVE_EMAIL_YES") . '</option>';
            $menu .= '<option value="0">' . oLang::_get("O_RECEIVE_EMAIL_NO") . '</option>';
        }else{
            $menu = '<option value="1" >' . oLang::_get("O_RECEIVE_EMAIL_YES") . '</option>';
            $menu .= '<option value="0" selected>' . oLang::_get("O_RECEIVE_EMAIL_NO") . '</option>';
        }
        echo $menu;
    }

    public function canrunCronJob()
    {
        if (!class_exists('SConfig'))
        {
            oseFirewall::callLibClass('gitBackup', 'GitSetup');
            $this->gitbackup = new GitSetup();
            $result = $this->gitbackup->canrunCronJob();
            return $result;
        }else {
            oseFirewall::callLibClass('gitBackup', 'GitSetupsuite');
            $this->gitbackup = new GitSetupsuite(false,false,false,false);
            $result = $this->gitbackup->canrunCronJob();
            return $result;
        }
    }

    public function getBackupTypeMenu()
    {
        $gitbackupsettings = $this->getCronSettings(4);
        if(!empty($gitbackupsettings) && isset($gitbackupsettings['cloudbt']))
        {
            $cloudbt = $gitbackupsettings['cloudbt'];
        }else{
            $cloudbt = false;
        }
        $menu = false;
        if(!empty($cloudbt))
        {
            if($cloudbt == 1)
            {
                $menu = '<option value="1" selected>Local Backup</option>';
                if(class_exists("SConfig"))
                {
                    $isremotereposet = true;
                    $repoBare =false;
                }else{
                    oseFirewall::callLibClass('gitBackup', 'GitSetup');
                    $gitbackup = new GitSetup();
                    $isremotereposet = $gitbackup->gitCloudCheck();
                    if($isremotereposet['status'] ==1)
                    {
                        unset($isremotereposet);
                        $isremotereposet = true;
                    }else{
                        unset($isremotereposet);
                        $isremotereposet = false;
                    }
                    $repoBare = $gitbackup->checkisRepoBare();
                }
                if($isremotereposet==true && $repoBare == false)
                {
                    $menu .= '<option value="2">Cloud Backup </option>';

                }else{
                    $menu .= '<option value="2" disabled>Cloud Backup </option>';
                }
            }elseif($cloudbt == 2){
                $menu = '<option value="1">Local Backup</option>';
                if(class_exists("SConfig"))
                {
                    $isremotereposet = true;
                    $repoBare =false;
                }else{
                    oseFirewall::callLibClass('gitBackup', 'GitSetup');
                    $gitbackup = new GitSetup();
                    $isremotereposet = $gitbackup->gitCloudCheck();
                    if($isremotereposet['status'] ==1)
                    {
                        unset($isremotereposet);
                        $isremotereposet = true;
                    }else{
                        unset($isremotereposet);
                        $isremotereposet = false;
                    }
                    $repoBare = $gitbackup->checkisRepoBare();
                }
                if($isremotereposet && $repoBare == false)
                {
                    $menu .= '<option value="2" selected>Cloud Backup </option>';

                }else{
                    $menu .= '<option value="2" selected disabled>Cloud Backup </option>';
                }
            }
        }else{
            $menu = '<option value="1" selected>Local Backup</option>';
            if(class_exists("SConfig"))
            {
                $isremotereposet = true;
                $repoBare =false;
            }else{
                oseFirewall::callLibClass('gitBackup', 'GitSetup');
                $gitbackup = new GitSetup();
                $isremotereposet = $gitbackup->gitCloudCheck();
                if($isremotereposet['status'] ==1)
                {
                    unset($isremotereposet);
                    $isremotereposet = true;
                }else{
                    unset($isremotereposet);
                    $isremotereposet = false;
                }
                $repoBare = $gitbackup->checkisRepoBare();
            }
            if($isremotereposet && $repoBare == false)
            {
                $menu .= '<option value="2">Cloud Backup </option>';

            }else{
                $menu .= '<option value="2" disabled>Cloud Backup </option>';
            }
        }
        if(!empty($menu))
        {
            return oseFirewall::prepareSuccessMessage((string)$menu);
        }else{
            return oseFirewall::prepareErrorMessage("Menu is empty");
        }
    }

    public function getCronSettingsLocal($type)
    {
        oseFirewall::callLibClass('gitBackup', 'GitSetup');
        $gitbackup = new GitSetup();
        $result = $gitbackup->getCronSettingsLocal($type);
        return $result;
    }

}
