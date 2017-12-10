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

class AiscanController extends \App\Base
{
    public function action_aiscan()
    {
        $this->model->loadRequest();
        $samples = $this->model->getVar('vssample', null);
        $results = $this->model->aiscan($samples);
        $this->model->returnJSON($results);
    }

    public function action_getPatterns()
    {
        $results = $this->model->getPatterns();
        $this->model->returnJSON($results);
    }

    public function action_deletePattern()
    {
        $this->model->loadRequest();
        $ids = $this->model->getVar('id', null);
        $results = $this->model->deletePattern($ids);
        $this->model->returnJSON($results);
    }

    public function action_addPattern()
    {
        $this->model->loadRequest();
        $pattern = $_REQUEST['pattern'];
        $type = $this->model->getVar('type', null);
        $results = $this->model->addPattern($pattern, $type);
        $this->model->returnJSON($results);
    }

    public function action_addToScanResult()
    {
        $this->model->loadRequest();
        $results = $this->model->addToScanResult();
        $this->model->returnJSON($results);
    }

    public function action_contentScan()
    {
        $this->model->loadRequest();
        $results = $this->model->contentScan();
        $this->model->returnJSON($results);
    }

    public function action_resetSamples()
    {
        $results = $this->model->resetSamples();
        $this->model->returnJSON($results);
    }

    public function action_aiscan_main()
    {
        $results = $this->model->aiscan_main();
        $this->model->returnJSON($results);
    }

    public function action_aiscan_finish()
    {
        $results = $this->model->aiscan_finish();
        $this->model->returnJSON($results);
    }
}

?>