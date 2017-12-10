<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 11/08/2016
 * Time: 11:49 AM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
oseFirewall::checkDBReady ();
$this->model->getNounce();
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
                                <span class="tag-title"><?php oLang::_('FIREWALL_STATISTICS');?><span>
                                    </div>
                                    <p class="tag-content"><?php oLang::_('CHECKING_ALL_FIREWALL');?></p>
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
                                            <?php oLang::_('BACK_TO_ADVANCE_SETTING');?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row row-set-sr" style="margin-top: 3px;">
                            <div class="title-bar"><?php oLang::_('O_FIREWALL_STATS_SMALL_DESC');?></div>
                            <div class="col-sm-12 bg-color-mediumpurple" style="padding-left: 5px; padding-right: 0px;">
                                <div class="col-sm-7 sr-title"><i class="fa fa-history"></i> <?php oLang::_('TIME_ANALYSIS');?> <span>(<?php oLang::_('SUMMARISING_NUMBERS_OF_ATTACKS_BY_TIME');?>)<span></div>
                                <div class="col-sm-5" style="padding-right: 0px;">
                                    <div id="go-current-month-chart" class="col-sm-9 pull-right time-analysis-selection"  style="margin-right: 0px;"><?php oLang::_('BACK_TO_STATISTIC_CURRENT_MONTH');?></div>
                                </div>
                            </div>
                            <div id="daily-chart-container"  class="col-sm-12 bg-color-lightpurple" style="margin-top: 1px; height: 440px; padding: 10px;">
                                <div class="col-sm-12 fs-nodata"><?php oLang::_('NO_ATTACKS');?></div>
                                <canvas id="time-chart" class="col-sm-12"></canvas>
                            </div>
                        </div>
                        <div class="row row-set-sr" style="margin-top: 3px;">
                            <div class="col-sm-12 bg-color-mediumpurple" style="padding-left: 5px; padding-right: 0px;">
                                <div class="col-sm-9 sr-title"><i class="fa fa-bullseye"></i> <?php oLang::_('ATTACK_TYPES');?> <span>( <?php oLang::_('SUMMARISING_NUMBERS_OF_ATTACKS_BY_TYPE');?>)<span></div>
                            </div>
                            <div class="col-sm-12 bg-color-lightpurple" style= "margin-top: 1px; height: 540px; padding: 20px 7px 0px;">
                                <div class="col-sm-12 fs-nodata"><?php oLang::_('NO_ATTACKS');?></div>
                                <canvas id="types-chart" class="col-sm-12"></canvas>
                            </div>
                        </div>
                        <div class="row row-set-sr" style="margin-top: 3px;">
                            <div class="col-sm-8" style="padding-left: 0px; padding-right: 0px;">
                                <div class="col-sm-12 sr-title bg-color-mediumpurple" style="padding-left: 15px;">
                                    <i class="fa fa-map-marker"></i><?php oLang::_('IP_INFO');?><span>(<?php oLang::_('SUMMARISING_NUMBERS_OF_ATTACKS_BY_IP');?>)<span></div>
                                <div class="col-sm-12 bg-color-lightpurple" style="margin-top: 1px; height: 400px;">
                                    <div class="col-sm-12 fs-nodata"><?php oLang::_('NO_ATTACKS');?></div>
                                    <canvas id="ip-chart" class="col-sm-12"></canvas>
                                </div>
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 0px;">
                                <div class="col-sm-12 sr-title bg-color-mediumpurple"> <i class="fa fa-delicious"></i> <?php oLang::_('MOST_COMMON_BROWSERS');?> </div>
                                <div class="col-sm-12 bg-color-lightpurple browser-chart-height" style="padding:15px; margin-top: 1px;">
                                    <div class="col-sm-12 fs-nodata"><?php oLang::_('NO_ATTACKS');?></div>
                                    <canvas id="browser-chart" class="col-sm-12"></canvas>
                                </div>
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


