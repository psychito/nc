<?php
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
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
require_once(dirname(__FILE__).'/oseEmailBase.php');
class oseEmailWP extends oseEmailBase
{
    public function __construct($app)
    {
        $this->app = $app;
        $this->setCMS();
        $this->setDB();
        $this->loadRequest ();
    }
    private function setDB()
    {
         $this->db = oseWordpress::getDBO();
    }
    public function sendMail($email, $config_var)
    {
        $receiptients = $this->getReceiptients($email->id);
        if (empty($receiptients))
        {
            return false;
        }
        foreach ($receiptients as $receiptient)
        {
            $email->body = str_replace('[user]', $receiptient->name, $email->body);
            require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'emails'.ODS.'oseEmailHelper.php');
            require_once(OSE_ABSPATH.'/wp-includes/class-phpmailer.php');
            $mailer = new PHPMailer();
            $mailer->From = $config_var->mailfrom;
            $mailer->FromName = $config_var->fromname;
            $this->addRecipient($mailer, $receiptient->email);
            $mailer->Subject = OSEMailHelper::cleanLine($email->subject);
            $mailer->Body = OSEMailHelper::cleanText($email->body);
            $mailer->IsHTML(true);
            $mailer->Send();
        }
        return true;
    }
    public function sendMailTo($email, $config_var, $receiptients)
    {
        if (empty($receiptients))
        {
            return false;
        }
        foreach ($receiptients as $receiptient)
        {
            $email->body = str_replace('[user]', $receiptient->name, $email->body);
            require_once(OSE_FRAMEWORKDIR.ODS.'oseframework'.ODS.'emails'.ODS.'oseEmailHelper.php');
            require_once(OSE_ABSPATH.'/wp-includes/class-phpmailer.php');
            $mailer = new PHPMailer();
            $mailer->From = $config_var->mailfrom;
            $mailer->FromName = $config_var->fromname;
            $this->addRecipient($mailer, $receiptient->email);
            $mailer->Subject = OSEMailHelper::cleanLine($email->subject);
            $mailer->Body = OSEMailHelper::cleanText($email->body);
            $mailer->IsHTML(true);
            $mailer->Send();
        }
        return true;
    }
    protected function getReceiptients($emailid)
    {
        oseFirewall::callLibClass('convertviews', 'convertviews');
        $attrList = array("`users`.`display_name` AS `name`, `users`.`user_email` AS `email`");
        $sql = convertViews::convertAdminEmail($attrList);
        $query = $sql." where `adminemail`.`email_id`= ".(int) $emailid;
        $this->db->setQuery($query);
        $items = $this->db->loadObjectList();
        return $items;
    }
    public function getAdminEmailListDB($search, $start, $limit)
    {
        oseFirewall::callLibClass('convertviews', 'convertviews');
        $where = array();
        if (!empty($search))
        {
            $where[] = "`subject` LIKE ".$this->db->quoteValue('%'.$search.'%', true);
        }
        $where[] = "`email`.`app` = ".$this->db->quoteValue($this->app);
        $where = $this->db->implodeWhere($where);
        $attrList = array("`email`.subject AS subject", "`email`.id AS id", "`email`.id AS email_id", "`users`.ID AS user_id", "`users`.display_name AS name", );
        $sql = convertViews::convertAdminEmail($attrList);
        $query = $sql.$where;
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }
}