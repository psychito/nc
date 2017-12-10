<?php
namespace App\Controller\remoteControllers;
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
require_once (OSE_FRAMEWORKDIR . ODS . 'oseframework' . ODS . 'ajax' . ODS . 'oseAjax.php');
class DownloadRemoteController extends BaseRemoteController{
	public function __construct($pixie)
	{
		parent::__construct($pixie);
		$this -> getModel () ;
		$this -> model -> isDBReady();
	}

	public function actionVsscan ($step, $type) {
		$result = $this->model->vsscan($step, $type);
		if ($result == true)
		{
			// Update vsscanning date; 
			//$this->model->updateVersion ($type, $version); 
			$receiveEmail = $this->model->getEmailConfig (); 
			$return = array(
				'success' => true,
				'status' => 'SUCCESS',
				'result' => oLang::_get("VSSCANNING_SUCCESS"),
				'cont' => false,
				'receiveEmail' => (int)$receiveEmail 
			);
			$tmp = $this->model->JSON_encode($return);
			header("Content-Type: text/plain");
			print_r($tmp); exit;
		}
		else
		{
			header("Content-Type: text/plain");
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VSSCANNING_FAILED"), false);
		}
	}

	public function actionGitBackup () {
		$result = $this->model->gitBackup();
		if ($result == true)
		{
			$receiveEmail = $this->model->getEmailConfig ();
			$return = array(
					'success' => true,
					'status' => 'SUCCESS',
					'result' => oLang::_get("GITBACKUP_SUCCESS"),
					'cont' => false,
					'receiveEmail' => (int)$receiveEmail
			);
			$tmp = $this->model->JSON_encode($return);
			header("Content-Type: text/plain");
			print_r($tmp); exit;
		}
		else
		{
			header("Content-Type: text/plain");
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("VSSCANNING_FAILED"), false);
		}
	}

    public function actionScheduledBackup ($cloudbackuptype, $upload , $fileNum,$preparelist =0)
	{
        $this->model->runScheduledBackup($cloudbackuptype, $upload , $fileNum,$preparelist);
//        if ($result == true)
//        {    $receiveEmail = $this->model->getEmailConfig ();
//            $return = array(
//                'success' => true,
//                'status' => 'SUCCESS',
//                'result' => oLang::_get("BACKUP_SUCCESS"),
//                'cont' => false,
//                'receiveEmail' => (int)$receiveEmail
//            );
//            $tmp = $this->model->JSON_encode($return);
//			header("Content-Type: text/plain");
//            print_r($tmp); exit;
//        }
//        else
//        {
//			header("Content-Type: text/plain");
//			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("BACKUP_FAILED"), false);
//        }
    }

	public function actionclearBlacklistIP ($ClearIPKey)
	{
		$result = $this->model->clearBlacklistIP ($ClearIPKey);
		if ($result !== false) {
			$receiveEmail = $this->model->getEmailConfig ();
			$return = array(
					'success' => true,
					'status' => 'SUCCESS',
					'result' => "Cleared $result Blacklisted IP(s)",
					'cont' => false,
					'receiveEmail' => (int)$receiveEmail
			);
			$tmp = $this->model->JSON_encode($return);
			header("Content-Type: text/plain");
			print_r($tmp); exit;
		}else {
			header("Content-Type: text/plain");
			$this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("CLEAR _BLACKLIST_FAILED"), false);
		}

	}

	public function actionGetStats()
	{
		$result = $this->model->getStats();
		header("Content-Type: text/plain");
		print_r($result); exit;
	}

	public function actionbgscan()
	{
		$result = $this->model->bgscan();
		header("Content-Type: text/plain");
		print_r($result);
		exit;
	}

	public function actionbggitbackup()
	{
		$result = $this->model->bggitbackup();
		header("Content-Type: text/plain");
		print_r($result);
		exit;
	}


    public function actionScheduledUpdatePatterns()
    {
        $this->model->loadFiles();
        $type= $this->model->getVar('type', null);
        $result = $this->model->scheduledUpdatePatterns($type);
        if ($result['status'] == 1)
        {
            $receiveEmail = $this->model->getEmailConfig ();
            $return = array(
                'success' => true,
                'status' => 'SUCCESS',
                'result' => $this->model->getLang("ADVRULESET_INSTALL_SUCCESS"),
                'cont' => false,
                'receiveEmail' => (int)$receiveEmail
            );
            $tmp = $this->model->JSON_encode($return);
            header("Content-Type: text/plain");
            print_r($tmp); exit;
        }
        else
        {
            header("Content-Type: text/plain");
            $this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("ADVRULESET_INSTALL_FAILED"), false);
        }
    }
}
?>