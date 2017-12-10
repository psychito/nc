<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 11/08/2016
 * Time: 11:51 AM
 */
namespace App\Controller;
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
class Bsconfigv7statsController extends \App\Base {
    public function action_getBrowserStats()
    {
        $this->model->loadRequest();
        $result = $this->model->getBrowserStats();
        $this->model->returnJSON($result);
    }
    public function action_getStats()
    {
        $this->model->loadRequest();
        $result = $this->model->getBrowserStats();
        $this->model->returnJSON($result);
    }
    public function action_getDailyStats()
    {
        $this->model->loadRequest();
        $month = $this->model->getVar('month',null);
        $date = $this->model->getVar('date',null);
        $result = $this->model->getDailyStats($month,$date);
        $this->model->returnJSON($result);
    }

    public function action_housekeepingV7()
    {
        $this->model->loadRequest();
        $result = $this->model->housekeepingV7();
        $this->model->returnJSON($result);
    }


}