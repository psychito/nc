<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 18/08/2016
 * Time: 3:09 PM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
oseFirewall::checkDBReady ();
$this->model->getNounce();
oseFirewallBase::callLibClass('fwscannerv7','fwscannerv7');
$fs = new oseFirewallScannerV7();
$email = $fs->getEmailFromDb();
oseFirewallBase::callLibClass('fwscannerv7','fwstatsv7');
$fwstats = new fwstatsv7();
$geodbexists = $fwstats->geoDBExists();
?>

<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php $this->model->showLogo();
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary plain ">
                    <!-- Start .panel -->

                    <div class="panel-body wrap-container" style="padding-bottom: 50px;">
                        <div class="row row-set">
                            <div class="col-sm-3 p-l-r-0">
                                <div id="c-tag">
                                    <div class="col-sm-12" style="padding-left: 0px;padding-top:10px;">
                                <span class="tag-title"><?php oLang::_('EMAIL_REPORTS'); ?><span>
                                    </div>
                                    <p class="tag-content"><?php oLang::_('SETUP_EMAIL_NOTIFICATION'); ?></p>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="col-sm-11">
                                    <div class="vs-line-1">
                                        <div id="fw-overview" class="vs-line-1-title fw-hover">
                                            <?php if(OSE_CMS =='wordpress'){ ?>
                                                <a href="admin.php?page=ose_fw_bsconfigv7" style="color:white;"><i class="fa fa-fire"></i></a>
                                            <?php }else { ?>
                                                <a href="?option=com_ose_firewall&view=bsconfigv7" style="color:white;"> <i class="fa fa-fire"></i> </a>
                                            <?php }?>

                                        </div>
                                        <div class="vs-line-1-number">
                                            <?php oLang::_('BACK_TO_ADVANCE_SETTING'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row row-set-sr" style="margin-top: 3px;">
                            <div class="title-bar"><?php oLang::_('O_EMAIL_STATS_SMALL_DESC');?></div>
                            <div class="col-sm-12 bg-color-mediumpurple" style="padding-left: 5px; padding-right: 0px;">
                                <div class="col-sm-12 sr-title tl-center">
                                    <?php oLang::_('ALL_EMAIL_SEND_TO'); ?> : <b><?php echo $email['value']; ?></b>
                                   <br> <span>( <?php oLang::_('CHANGE_EMAIL_ADDRESS_BACK'); ?>)<span>
                                </div>
                            </div>
                            <div class="col-sm-12 bg-color-minpurple" style="margin-top: 1px; padding: 10px 10px 40px;">
                            <div class="col-sm-12" style="margin-bottom: 10px; padding-left: 35px; margin-top: 10px; color:white;"><?php oLang::_('EMAIL_ALERT_DESC'); ?> :   </div>
                                <div class="col-sm-4">
                                    <div id="blocked_ip" class="fuc-checks-content height-45" onclick="changeStatus('blocked_ip')"><?php oLang::_('ATTACK_DETECTED'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="IP Block"
                                       data-toggle="popover" data-placement="left"
                                       data-content="<?php oLang::_('EMAIL_MODE_DESC'); ?>">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>
                                </div>
                                <?php if(!oseFirewallBase::isSuite()) {?>
                                <div class="col-sm-4">
                                    <div id="googleauth" class="fuc-checks-content height-45" onclick="changeStatus('googleauth')"><?php oLang::_('GOOGLE_AUTH_LOGIN_CODE'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="Google Authentication QRcode"
                                       data-toggle="popover" data-placement="left"
                                       data-content="<?php oLang::_('GOOGLE_AUTH_LOGIN_CODE_DESC'); ?>">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>
                                </div>
                                <?php }?>

                                <div class="col-sm-4">
                                    <?php if(oseFirewall::checkSubscriptionStatus(false)) {
                                        if ($geodbexists) {
                                            ?>

                                            <div id="stats" class="fuc-checks-content height-45"><?php oLang::_('STATISTICS'); ?></div>
                                            <a class="em-qmark fuc-square height-45" href="#" title="Statistics"
                                               data-toggle="popover" data-placement="left"
                                               data-content="<?php oLang::_('STATISTICS_DESC1'); ?>">
                                                <i class="fw-question-icons fa fa-question"></i>
                                            </a>
                                        <?php
                                        } else { ?>
<!--                                            geo db does not exists -->
                                            <div id="stats" class="fuc-checks-content height-45; disable"><?php oLang::_('STATISTICS'); ?></div>
                                            <a class="em-qmark fuc-square height-45" href="#" title="Statistics"
                                               data-toggle="popover" data-placement="left"
                                               data-content="<?php oLang::_('STATISTICS_DESC2'); ?>">
                                                <i class="fw-question-icons fa fa-question"></i>
                                            </a>
                                       <?php } ?>
                                    <?php }else {?>
                                        <div id="stats" class="fuc-checks-content height-45; disable"><?php oLang::_('STATISTICS'); ?></div>
                                        <a class="em-qmark fuc-square height-45" href="#" title="Statistics"
                                           data-toggle="popover" data-placement="left"
                                           data-content="<?php oLang::_('STATISTICS_DESC3'); ?>">
                                            <i class="fw-question-icons fa fa-question"></i>
                                        </a>
                                    <?php } ?>
                                </div>
                                <div id="statistic-section" class="col-sm-12" style="padding-right: 0px;">
                                <div class="col-sm-12" style="margin-bottom: 10px; padding-left: 20px; margin-top: 30px; color: white;">
                                    <?php oLang::_('SELECT_DATA_TO_RECEIVE'); ?></div>
                                <div class="col-sm-3">
                                    <div id="timestats" class="fuc-checks-content height-45" onclick="changeStatus('timestats')"><?php oLang::_('TIME_ANALYSIS'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="Time Analysis"
                                       data-toggle="popover" data-placement="left"
                                       data-content="This statistics will help you to analyse the number of attacks performed according to the respective date">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>                                </div>
                                <div class="col-sm-3">
                                    <div id="attackstats" class="fuc-checks-content height-45" onclick="changeStatus('attackstats')"><?php oLang::_('ATTACK_TYPES'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="Attack Analysis"
                                       data-toggle="popover" data-placement="left"
                                       data-content="This statistics will help you to analyse the most frequent type of attack on your website">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>                                </div>
                                <div class="col-sm-3">
                                    <div id="ipstats" class="fuc-checks-content height-45" onclick="changeStatus('ipstats')"><?php oLang::_('GEO_IP_ANALYSIS'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="GEO/IP Analysis"
                                       data-toggle="popover" data-placement="left"
                                       data-content="This statistics will help you to analyse the geographical location of the attackers and their most common attack types ">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>                                </div>
                                <div class="col-sm-3">
                                    <div id="browserstats" class="fuc-checks-content height-45" onclick="changeStatus('browserstats')"><?php oLang::_('BROWSER_ANALYSIS'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="Browser Analysis"
                                       data-toggle="popover" data-placement="left"
                                       data-content="This statistics will help you to analyse the most common web browsers used by the hacker/attackers">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>                                </div>

                                <div class="col-sm-12" style="margin-bottom: 10px; padding-left: 20px; margin-top: 10px; color: white;">
                                    <?php oLang::_('SELECT_REPORT_FREQUENCY_CYLCE'); ?></div>
                                <div class="col-sm-3">
                                    <div id="stats_daily" class="fuc-checks-content height-45" onclick="changeReportCycleStatus('stats_daily')"><?php oLang::_('DAILY'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="Daily"
                                       data-toggle="popover" data-placement="left"
                                       data-content="<?php oLang::_('SELECT_REPORT_FREQUENCY_CYLCE_DESC_DAILY'); ?>">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>                                </div>
                                <div class="col-sm-3">
                                    <div id="stats_weekly" class="fuc-checks-content height-45" onclick="changeReportCycleStatus('stats_weekly')"><?php oLang::_('WEEKLY'); ?></div>
                                    <a class="em-qmark fuc-square height-45" href="#" title="Weekly"
                                       data-toggle="popover" data-placement="left"
                                       data-content="<?php oLang::_('SELECT_REPORT_FREQUENCY_CYLCE_DESC_WEEKLY'); ?>">
                                        <i class="fw-question-icons fa fa-question"></i>
                                    </a>                                </div>
                                </div>
                            </div>
                            <div class="col-sm-12" style="padding-right: 0px; margin-top: 15px;">
                                <div class="pull-right col-sm-3 btn-new result-btn-set tl-center" onclick="saveSettings()"><?php oLang::_('SAVE'); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $oem = new CentroraOEM();
                    $oemCustomer = $oem->hasOEMCustomer();
                    if(!empty($oemCustomer['data']['customer_id'])) {
                        echo $oem->getCallToActionAndFooter();
                    }else {?>
                        <?php  echo $this->model->getCallToActionAndFooter(); }?>


                </div>
            </div>

        </div>
    </div>
</div>
</div>

