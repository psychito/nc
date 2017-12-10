<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 11/08/2016
 * Time: 11:51 AM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
require_once('BaseModel.php');
class Bsconfigv7statsModel extends BaseModel{
    public function __construct() {
        $this->loadLibrary ();
        $this->loadDatabase ();
    }
    protected function loadLibrary () {
        $this->loadFirewallStat () ;
        oseFirewallBase::callLibClass('fwscannerv7','fwstatsv7');
        $this->qatest = oRequest :: getInt('qatest', false);
        $this->fwscannerv7stats = new fwstatsv7();
    }
    protected function loadDatabase () {
        $this->db = oseFirewall::getDBO();
    }
    public function loadLocalScript() {
        $this->loadAllAssets ();
        oseFirewall::loadJSFile ('CentroraSEOTinyMCE', 'plugins/tinymce/tinymce.min.js', false);
        oseFirewall::loadJSFile ('FirewallStatsV7', 'fwstatsv7.js', false);
    }
    public function getBrowserStats()
    {
        $result =$this->fwscannerv7stats->getAttackStatistics();
        return $result;
    }
    public function getStats()
    {
        $result =$this->fwscannerv7stats->getAttackStatistics();
        return $result;
    }
    public function getDailyStats($month,$date)
    {
        $result =$this->fwscannerv7stats->getAnalysisofDay($month,$date);
        return $result;
    }
    public function housekeepingV7()
    {
        $result =$this->fwscannerv7stats->housekeepingV7();
        return $result;
    }
}