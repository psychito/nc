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

class CfscanController extends \App\Base
{

    public function action_Cfscan()
    {
        $this->model->loadRequest();

        $results = $this->model->cfscan();

        $this->model->returnJSON($results);
    }

    public function action_Suitecfscan()
    {
        $this->model->loadRequest();
        $path = $this->model->getVar('scanPath', null);
        $type = $this->model->getVar('cms', null);
        $version = $this->model->getVar('version', null);
        $results = $this->model->suitecfscan($path, $type, $version);
        $this->model->returnJSON($results);
    }

    public function action_Suitemfscan()
    {
        $this->model->loadRequest();
        $startdate = $this->model->getVar('startdate', null);
        $enddate = $this->model->getVar('enddate', null);
        $symlink = $this->model->getInt('symlink', 0);
        $path = $this->model->getVar('path', null);
        $results = $this->model->suitemfscan($startdate, $enddate, $symlink, $path);
        $this->model->returnJSON($results);
    }

    public function action_catchVirusMD5()
    {
        $results = $this->model->catchVirusMD5();
        $this->model->returnJSON($results);
    }

    public function action_addToAi()
    {
        $this->model->loadRequest();
        $id = $this->model->getInt('id', 0);
        $results = $this->model->addToAi($id);
        $this->model->returnJSON($results);
    }

    public function action_getFileTree()
    {
        $results = $this->model->getFileTree();
        exit;
    }

    public function action_suitePathDetect()
    {
        $this->model->loadRequest();
        $path = $this->model->getVar('scanPath', null);
        $results = $this->model->suitePathDetect($path);
        $this->model->returnJSON($results);
    }

    public function action_checkCoreFilesExists()
    {
        $this->model->loadRequest();
        $results = $this->model->checkCoreFilesExixts();
        $this->model->returnJSON($results);
    }
    public function action_downloadCoreFiles()
    {
        $this->model->loadRequest();
        $cms = $this->model->getVar('cms', null);
        $version = $this->model->getVar('version', null);
        $results = $this->model->downloadCoreFiles($cms, $version);
        $this->model->returnJSON($results);
    }
    public function action_checkCoreFilesExistsSuite()
    {
        $this->model->loadRequest();
        $cms = $this->model->getVar('cms', null);
        $version = $this->model->getVar('version', null);
        $results = $this->model->checkCoreFilesExistsSuite($cms,$version);
        $this->model->returnJSON($results);
    }
}

?>