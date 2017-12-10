<?php
namespace App\Controller;
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

class CronjobsController extends \App\Base
{
    public function action_saveCronConfig()
    {

        $this->model->loadRequest();
        $schedule_type = $this->model->getVar('schedule_type', null);
        $custhours = $this->model->getVar('custhours', null);
        $custweekdays = $this->model->getVar('custweekdays', array());
        $cloudbackuptype = $this->model->getVar('cloudbackuptype', null);
        $gitbackupfrequency = $this->model->getVar('gitbackupfrequency', null);
        $recieveEmail = $this->model->getVar('git_receiveemail', null);
        if(empty($gitbackupfrequency))
        {
                $gitbackupfrequency = 1;
        }
        $enabled = $this->model->getVar('enabled', null);
        if ($enabled == 1 && empty($custweekdays)){
            $custweekdays[] = 0; //set zero for no days selected and schedule not enabled
        }
        if ((!isset($custhours) AND $custhours != '') || (empty($custweekdays) )) {
            $this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("CRON_SETTING_EMPTY"), false);
        } else {
            $custweekdays = base64_encode($this->model->JSON_encode($custweekdays));
            $result = $this->model->saveCronConfig($custhours, $custweekdays, $schedule_type, $cloudbackuptype, $enabled, $gitbackupfrequency,$recieveEmail);
        }
    }

    public function action_saveScanPath ()
    {
        $type = $this->model->getVar('type', null);
        $data['scanPath'] = $this->model->getVar('scanPath', null);

        $this->model->saveConfiguration($type, $data);
    }

    public function action_getFileTree()
    {

        $results = $this ->model->getFileTree();
        exit;
    }

    public function action_canrunCronJob()
    {
        $this->model->loadRequest();
        $result = $this->model->canrunCronJob();
        return $this->model->returnJSON($result);
    }

     public function action_getBackupTypeMenu()
    {
        $this->model->loadRequest();
        $result = $this->model->getBackupTypeMenu();
        return $this->model->returnJSON($result);
    }

}

?>