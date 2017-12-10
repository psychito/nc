<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 18/08/2016
 * Time: 3:11 PM
 */
namespace App\Controller;
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
class Emailnotificationv7Controller  extends \App\Base {
    public function action_saveSettings()
    {
        $this->model->loadRequest();
        $array = $this->model->getVar('data',null);
        $result = $this->model->saveSettings($array);
        $this->model->returnJSON($result);
    }
    public function action_getSettings()
    {
        $this->model->loadRequest();
        $result = $this->model->getSettings();
        $this->model->returnJSON($result);

    }


}