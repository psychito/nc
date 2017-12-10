<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 22/07/2016
 * Time: 4:17 PM
 */
namespace App\Controller;
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
class BanpagemgmtController extends \App\Base
{
    public function action_getBanPageSettings()
    {
        $this->model->loadRequest();
        $result = $this->model->getBanPageSettings();
        $this->model->returnJSON($result);
    }

    public function action_saveBanPageSettings()
    {
        $data['30'] = $this->model->getInt('blockIP', 0);  //1 =>show 403 ban page and 0 => show custom ban page
        $data['31'] = $_POST['customBanpage']; //custom ban page content
        $data['32'] = $this->model->getVar('customBanURL',null); //custom ban page url
        $data['28'] = $this->model->getVar('centroraGA', 0); //1 => ban page google authenticator activated
        $data['29'] = $this->model->getVar('GA_secret', null);  //secret code for the ban page google authenticator
        $data['34'] = $this->model->getVar('secureKey', null);
        $this->model->loadRequest();
        $result = $this->model->saveBanPageSettings($data);
        $this->model->returnJSON($result);
    }

}