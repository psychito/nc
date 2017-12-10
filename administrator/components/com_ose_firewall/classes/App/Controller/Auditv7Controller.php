<?php
namespace App\Controller;
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 21/07/2016
 * Time: 2:42 PM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
class Auditv7Controller extends \App\Base
{
    public function action_CreateTables() {
        $this->model->actionCreateTables ();
    }
    public function action_Changeusername() {
        $this->model->loadRequest ();
        $username = $this->model->getVar ( 'username', null );
        if (empty ( $username )) {
            $this->model->aJaxReturn ( true, 'ERROR', $this->model->getLang ( 'USERNAME_CANNOT_EMPTY' ), false );
        } else {
            $result = $this->model->changeusername ( $username );
            if ($result == true) {
                $this->model->aJaxReturn ( true, 'SUCCESS', $this->model->getLang ( 'USERNAME_UPDATE_SUCCESS' ), false );
            } else {
                $this->model->aJaxReturn ( true, 'ERROR', $this->model->getLang ( 'USERNAME_UPDATE_FAILED' ), false );
            }
        }
    }
    public function action_saveTrackingCode() {
        $this->model->loadRequest ();
        $trackingCode = $this->model->getVar ( 'trackingCode', null );
        if (empty ( $trackingCode )) {
            $this->model->aJaxReturn ( true, 'ERROR', $this->model->getLang ( 'TRACKINGCODE_CANNOT_EMPTY' ), false );
        } else {
            $result = $this->model->saveTrackingCode ( $trackingCode );
            if ($result == true) {
                $this->model->aJaxReturn ( true, 'SUCCESS', $this->model->getLang ( 'TRACKINGCODE_UPDATE_SUCCESS' ), false );
            } else {
                $this->model->aJaxReturn ( true, 'ERROR', $this->model->getLang ( 'TRACKINGCODE_UPDATE_FAILED' ), false );
            }
        }
    }
    public function action_UninstallTables() {
        $this->model->loadRequest();
        $result = $this ->model->actionUninstallTables();
        if($result)
        {
            $this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("UNINSTALL_SUCCESS"), false);
        }
        else
        {
            $this->model->aJaxReturn(true, 'ERROR', $this->model->getLang("UNINSTALL_FAILED"), false);
        }
    }
    public function action_getPHPConfig() {
        $result = $this->model->getPHPConfig();
        if(!empty($result))
        {
            $this->model->aJaxReturn(true, 'SUCCESS', $result, false);
        }
        else
        {
            $this->model->aJaxReturn(true, 'ERROR', 'Error: Nothing to update in php.ini', false);
        }
    }
    public function action_updateSignature(){
        $result = $this->model->updateSignature();
        if(!empty($result))
        {
            $this->model->aJaxReturn(true, 'SUCCESS', $result, false);
        }
        else
        {
            $this->model->aJaxReturn(true, 'ERROR', 'Error: Nothing to update in php.ini', false);
        }
    }

    public function action_googleRotv7()
    {

        $result = $this->model->googleRotv7();

        $this->model->returnJSON($result);
    }

    public function action_actCentroraPlugin()
    {
        $data = $this->model->actCentroraPlugin();
        $this->model->returnJSON($data);
    }



    public function action_changePermission()
    {
        $this->model->loadRequest();
        $foldername = $this->model->getVar('foldername');
        $permissions = $this->model->getVar('permission');
        if($permissions == "lock")
        {
            $data = $this->model->changePermission($foldername,"lock");
            $this->model->returnJSON($data);
        }
        elseif($permissions == "unlock")
        {
            $data = $this->model->changePermission($foldername,"unlock");
            $this->model->returnJSON($data);
        }
    }

    public function action_createHtaccessFile()
    {
        $this->model->loadRequest();
        $foldername = $this->model->getVar('foldername');
        $data = $this->model->createHtaccessFile($foldername);
        $this->model->returnJSON($data);
    }



}