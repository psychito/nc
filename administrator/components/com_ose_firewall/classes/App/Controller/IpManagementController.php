<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 6/07/2016
 * Time: 11:59 AM
 */
namespace App\Controller;
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
class IpManagementController extends \App\Base
{

    public function action_getIpInfo()
    {
        $this->model->loadRequest();
        $result = $this->model->getIpInfo();
        $this->model->returnJSON($result);
    }

    public function action_blacklistIp()
    {
        $this->model->loadRequest();
        $idlist = $this->model->getVar('ids', null);
        $idarray = $this->model->JSON_decode($idlist);
        $result = $this->model->blacklistIp($idarray);
        $this->model->returnJSON($result);
    }

    public function action_whitelistIp()
    {
        $this->model->loadRequest();
        $idlist = $this->model->getVar('ids', null);
        $idarray = $this->model->JSON_decode($idlist);
        $result = $this->model->whitelistIp($idarray);
        $this->model->returnJSON($result);
    }

    public function action_monitorIp()
    {
        $this->model->loadRequest();
        $idlist = $this->model->getVar('ids', null);
        $idarray = $this->model->JSON_decode($idlist);
        $result = $this->model->monitorIp($idarray);
        $this->model->returnJSON($result);
    }

    public function action_addIp()
    {
        $this->model->loadRequest();
        $ip = $this->model->getVar('ip_start', null);
        $status = $this->model->getVar('ip_status', null);
        $duration = $this->model->getVar('duration',null);
        $result = $this->model->addIp($ip, $status,$duration);
        $this->model->returnJSON($result);
    }

    public function action_clearAll()
    {
        $this->model->loadRequest();
        $result = $this->model->clearAll();
        $this->model->returnJSON($result);
    }

    public function action_deleteItem()
    {
        $this->model->loadRequest();
        $idlist = $this->model->getVar('ids', null);
        $idarray = $this->model->JSON_decode($idlist);
        $result = $this->model->deleteItem($idarray);
        $this->model->returnJSON($result);
    }

//    public function action_importCVS()
//    {
//        $this->model->loadRequest();
//        $result = $this->model->importCSV();
//        $this->model->returnJSON($result);
//    }

    public function action_downloadCSV()
    {
        $this->model->loadRequest();
        $filename = $this->model->getVar('filename', null);
        $this->model->downloadCSV($filename);
    }

    public function action_importcsv()
    {
        $this->model->loadRequest();
        $result = $this->model->importcsv($_FILES);
        $this->model->returnJSON($result);
    }
    public function action_viewAttackInfo()
    {
        $this->model->loadRequest();
        $ip = $this->model->getVar('ip',null);
        $result = $this->model->viewAttackInfo($ip);
        $this->model->returnJSON($result);
    }

    public function action_importips()
    {
        $this->model->loadRequest();
        $result = $this->model->importips();
        $this->model->returnJSON($result);
    }

    public function action_addEntityFromAttackLog()
    {
        $this->model->loadRequest();
        $entity = $this->model->getVar('entity', null);
        $result = $this->model->addEntityFromAttackLog($entity);
        $this->model->returnJSON($result);
    }

    public function action_getTempWhiteListedIps()
    {
        $this->model->loadRequest();
        $result = $this->model->getTempWhiteListedIps();
        $this->model->returnJSON($result);
    }
    public function action_deleteTempWhiteListIps()
    {
        $this->model->loadRequest();
        $ip = $this->model->getVar('ip', null);
        $result = $this->model->deleteTempWhiteListIps($ip);
        $this->model->returnJSON($result);
    }
}

