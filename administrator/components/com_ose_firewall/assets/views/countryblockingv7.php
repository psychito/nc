<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 21/07/2016
 * Time: 1:58 PM
 */

oseFirewall::checkDBReady();
$status = oseFirewall::checkSubscriptionStatus(false);
$this->model->getNounce();
$confArray = $this->model->getConfiguration('country');
    ?>
    <div id="oseappcontainer">
        <div class="container wrapbody">
            <?php
            $this->model->showLogo();
            ?>
            <!-- Add Variable Form Modal -->
            <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE'); ?></span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('DOWNLOAD_COUNTRY'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <form id='download-geoip-form' class="form-horizontal group-border stripped" role="form"
                                  enctype="multipart/form-data" method="POST">
                                <div>
                                    <div class="col-sm-12">
                                        <div class="easy-pie-chart" data-percent="0" id='easy-pie-chart-1'><span
                                                id='pie-1'>0%</span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="download-message" id='message-box'><?php oLang::_('READY'); ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-8">
                                        <button type="submit" class="btn-new result-btn-set" id='save-button'><i
                                                class="glyphicon glyphicon-cloud-download"></i> <?php oLang::_('DOWNLOAD_NOW'); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->



            <div class="content-inner">
                <div class="row ">
                    <div class="col-sm-12">
                        <!-- col-lg-12 start here -->
                        <div class="panel panel-primary plain">
                            <!-- Start .panel -->
                            <div class="panel-body wrap-container">
                                <div class="row row-set">
                                    <div class="col-sm-3 p-l-r-0">
                                        <div id="c-tag">
                                            <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title"><?php oLang::_('COUNTRYBLOCK'); ?></span>
                                            </div>
                                            <p class="tag-content"><?php oLang::_('O_COUNTRY_BLOCKINGV7_DESC'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-5">
                                            <div class="vs-line-1">
                                                <div class="vs-line-1-title fw-hover">
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
                                        <div class="col-sm-4">
                                            <div class="vs-line-1">
                                                <div class="vs-line-1-title fw-hover">
                                                    <?php if(OSE_CMS =='wordpress'){ ?>
                                                        <a href="admin.php?page=ose_fw_bsconfigv7#advsettings" style="color:white;"><i class="fa  fa-gears"></i></a>
                                                    <?php }else { ?>
                                                        <a href="?option=com_ose_firewall&view=bsconfigv7#advsettings" style="color:white;"> <i class="fa  fa-gears"></i> </a>
                                                    <?php }?>
                                                </div>
                                                <div class="vs-line-1-number">
                                                    <?php oLang::_('BACK_TO_ADVANCE_SETTING_ADV');?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-set">
                                    <div class="title-bar"><?php oLang::_('O_COUNTRY_BLOCKINGV7_SMALL_DESC'); ?></div>
                                    <div style="padding-bottom: 170px; padding-top: 25px;" class="col-sm-3 bg-transparent-white">
                                        <button data-target="#formModal" data-toggle="modal" class="upload-btns wl-btns"><i
                                                class="text-primary glyphicon glyphicon-cloud-download"></i> <?php oLang::_('DOWNLOAD_COUNTRY'); ?>
                                        </button>
                                        <button class="upload-btns wl-btns" type="button"
                                                onClick="changeBatchItemStatus('blacklistCountry')"><i
                                                class="text-block glyphicon glyphicon-minus-sign"></i> <?php oLang::_('O_BLACKLIST_COUNTRY'); ?>
                                        </button>
                                        <button class="upload-btns wl-btns" type="button"
                                                onClick="changeBatchItemStatus('monitorCountry')"><i
                                                class="text-yellow glyphicon glyphicon-eye-open"></i> <?php oLang::_('O_MONITOR_COUNTRY'); ?>
                                        </button>
                                        <button class="upload-btns wl-btns" type="button"
                                                onClick="changeBatchItemStatus('whitelistCountry')"><i
                                                class="text-success glyphicon glyphicon-ok-sign"></i> <?php oLang::_('O_WHITELIST_COUNTRY'); ?>
                                        </button>
                                        <button class="upload-btns wl-btns" type="button"
                                                onClick="removeAllItems()"><i
                                                class="text-block glyphicon glyphicon-remove-sign"></i> <?php oLang::_('O_DELETE__ALLITEMS'); ?>
                                        </button>
                                    </div>
                                    <div style="padding: 0px;" class="col-sm-9">
                                        <table class="table display" id="countryTable">
                                            <thead>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th></th>
                                                <th><?php oLang::_('O_COUNTRY'); ?></th>
                                                <th><?php oLang::_('O_STATUS'); ?></th>
                                                <th><input type="checkbox" name="checkedAll" id="checkedAll"></th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th></th>
                                                <th><?php oLang::_('O_COUNTRY'); ?></th>
                                                <th><?php oLang::_('O_STATUS'); ?></th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- End .panel -->
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

    <!-- Form Modal -->
    <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE'); ?></span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCAN_CONFIGURATION_TITLE'); ?></h4>
                </div>
                <div class="modal-body">
                    <form id='configuraton-form' class="form-horizontal group-border stripped" role="form">
                        <div class="form-group">
                            <label for="blockCountry"
                                   class="col-sm-4 control-label"><?php oLang::_('O_COUNTRY_BLOCKING'); ?></label>

                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" name="blockCountry"
                                           value="1" <?php echo (isset($confArray['data']['blockCountry']) && $confArray['data']['blockCountry'] == true) ? 'checked="checked"' : '' ?>><?php oLang::_('ON'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="blockCountry"
                                           value="0" <?php echo (isset($confArray['data']['blockCountry']) && $confArray['data']['blockCountry'] == false) ? 'checked="checked"' : '' ?>><?php oLang::_('OFF'); ?>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="option" value="com_ose_firewall">
                        <input type="hidden" name="controller" value="scanconfig">
                        <input type="hidden" name="action" value="saveConfigScan">
                        <input type="hidden" name="task" value="saveConfigScan">
                        <input type="hidden" name="type" value="country">

                        <div class="form-group">
                            <div class="col-sm-offset-8 ">
                                <button type="submit" class="btn-new result-btn-set" id='save-button'><?php oLang::_('SAVE'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php //}
    $this->model->showFooterJs();
?>