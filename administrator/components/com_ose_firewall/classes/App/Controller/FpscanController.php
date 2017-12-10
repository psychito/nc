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

class FpscanController extends \App\Base
{

    public function action_Fpscan()
    {
        $results = array();
        $this->model->loadRequest();
        $step = $this->model->getInt('step', null);
        $path = $this->model->getVar('scanPath', null);
        $baseFilePerm = $this->model->getInt('baseFilePerm', null);
        $baseFolderPerm = $this->model->getInt('baseFolderPerm', null);
        if (empty($baseFilePerm)) {
            $baseFilePerm = 644;
        }
        if (empty($baseFolderPerm)) {
            $baseFolderPerm = 755;
        }
        $re = "/^[0-7]{3}$/";
        if (preg_match($re, $baseFilePerm, $matches) && preg_match($re, $baseFolderPerm, $matches)) {
            $results = $this->model->fpscan($path, $baseFilePerm, $baseFolderPerm, $step);
            $this->model->returnJSON($results);

        } else {
            $results['invalid'] = 'wrongInput';
            $results['summary'] = 'Please input correct permission format';
            $this->model->returnJSON($results);

        };
    }

    public function action_getLastScanRecord()
    {
        $results = $this->model->getLastScan();
        $this->model->returnJSON($results);
    }
    public function action_getFileTree()
    {

        $results = $this->model->getFileTree();
        exit;
    }
}

?>