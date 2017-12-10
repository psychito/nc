<?php
oseFirewall::checkDBReady();
$this->model->getNounce();
$this->model->migrate();
?>
<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php
        $this->model->showLogo();
//        $this->model->showHeader();
        ?>

        <div class="content-inner">
            <div class="row">
                <div class="col-lg-12 sortable-layout">
                    <!-- col-lg-12 start here -->
                    <div class="panel panel-primary plain toggle panelClose panelRefresh">
                        <!-- Start .panel -->
                        <!--                                <div class="panel-heading white-bg"></div>-->
                        <div class="panel-controls"></div>
                        <div class="col-md-12 panel-body wrap-container">
                            <div class="row row-set">
                                <div class="col-sm-4 p-l-r-0">
                                    <div id="c-tag" style="height:130px;">
                                        <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title" style="height: 130px;">File Uploading Logs<span>
                                        </div>
                                        <p class="tag-content">
                                            <?php oLang::_('FILEEXTENSION_DESC'); ?>
                                        </p>
                                    </div>
                                </div>
                                <div id="modified_btn" class="col-sm-4">
                                    <div  class="cfscan-line-1"
                                         style="padding-left: 45px; cursor: pointer;">
                                        <div class="title-icon"><i class="fa fa-list" href="#extview"></i></div>
                                        <div class="title-content" href="#extview">
                                            File Extension List
                                        </div>
                                    </div>
                                </div>
                                <div id="suspicious_btn" class="col-sm-4">
                                    <div class="cfscan-line-1"
                                         style="padding-left: 10px; cursor: pointer;">
                                        <div class="title-icon"><i class="fa fa-upload" id="extview"></i></div>
                                        <div class="title-content" id="extview">
                                            File Uploading Log (Premium)
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id = "pageTitle" class="title-bar"></div>
                            <div class="row">
                                <div id="sticky-anchor"></div>
                                <div id="report-btngroup" class="col-md-12" style="margin-top: 0px;padding: 0 20px;">
                                    <div class="clean-buttons">
                                        <button class="btn-new result-btn-set" type="button" onClick="addExt()">
                                            <i id="ic-change" class="text-danger glyphicon glyphicon-plus-sign"></i>
                                            <?php oLang::_('ADD_EXT'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id = "extensionList">
                                <div class="row col-sm-12" style="padding:0 20px;">
                                    <table class="table display" id="extensionListTable">
                                        <thead>
                                        <tr>
                                            <th><?php oLang::_('O_EXTENSION_ID'); ?></th>
                                            <th><?php oLang::_('O_EXTENSION_NAME'); ?></th>
                                            <th><?php oLang::_('O_EXTENSION_TYPE'); ?></th>
                                            <th><?php oLang::_('O_EXTENSION_STATUS'); ?></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th><?php oLang::_('O_EXTENSION_ID'); ?></th>
                                            <th><?php oLang::_('O_EXTENSION_NAME'); ?></th>
                                            <th><?php oLang::_('O_EXTENSION_TYPE'); ?></th>
                                            <th><?php oLang::_('O_EXTENSION_STATUS'); ?></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
<!--                             file extension log-->
                            <div id="uploadlog">
                                <?php
                                $status = oseFirewall::checkSubscriptionStatus(false);
                                if ($status == true) {
                                    ?>

                                    <div class="row col-sm-12" style="padding:0 20px;">
                                        <table class="table display" id="uploadLogTable">
                                            <thead>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th><?php oLang::_('O_START_IP'); ?></th>
                                                <th><?php oLang::_('O_FILENAME'); ?></th>
                                                <th><?php oLang::_('O_FILETYPE'); ?></th>
                                                <th><?php oLang::_('O_IP_STATUS'); ?></th>
                                                <th><?php oLang::_('O_DATE'); ?></th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th><?php oLang::_('O_START_IP'); ?></th>
                                                <th><?php oLang::_('O_FILENAME'); ?></th>
                                                <th><?php oLang::_('O_FILETYPE'); ?></th>
                                                <th><?php oLang::_('O_IP_STATUS'); ?></th>
                                                <th><?php oLang::_('O_DATE'); ?></th>
                                            </tr>
                                            </tfoot>
                                        </table>
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


                                    <?php
                                }else{ ?>
<!--                                    show subscription banner to the free users -->
                                        <div id="oseappcontainer">
                                            <div class="container">
                                                <div id="sub-header" class="row"
                                                     style="background:url('<?php echo'http://www.googledrive.com/host/0B4Hl9YHknTZ4X2sxNTEzNTBJUlE/sub_hd_bg.png' ?>') top center;  min-height:500px;">
                                                    <div class="col-md-6" id="unsub-left">
                                                        <?php $this->model->showSubHeader(); ?>
                                                        <?php echo $this->model->getBriefDescription(); ?>
                                                    </div>
                                                    <div class="col-md-6" id="unsub-right">
                                                        <a href="https://www.centrora.com/services" id="leavetous">leave the work to us now</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div id="unsub-lower">
                                                        <?php
                                                        include_once dirname(__FILE__) . '/calltoaction.php';
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                    <!-- End .panel -->
                </div>
            </div>
        </div>
        </div>
    </div>

                <!-- /.modal -->
                <div class="modal fade" id="addExtModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_EXT'); ?></h4>
                            </div>
                            <form id='addext-form' class="form-horizontal group-border stripped" role="form">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label
                                            class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_NAME'); ?></label>

                                        <div class="col-sm-8">
                                            <input type="text" name="ext-name" value="" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ext-type"
                                               class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_TYPE'); ?></label>

                                        <div class="col-sm-8">
                                            <select class="form-control" name='ext-type' id='ext-type'>
                                                <?php print_r($this->model->getExtType()) ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ext-status"
                                               class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_STATUS'); ?></label>

                                        <div class="col-sm-8">
                                            <select class="form-control" name='ext-status' id='ext-status'>
                                                <option value='1'>Allowed</option>
                                                <option value='2'>Forbidden</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="option" value="com_ose_firewall">
                                    <input type="hidden" name="controller" value="upload">
                                    <input type="hidden" name="action" value="saveExt">
                                    <input type="hidden" name="task" value="saveExt">
                                </div>
                                <div class="modal-footer">
                                    <label id="ext-warning-label" class="col-sm-6 control-label" style="display: none"><i
                                            id="ext-warning-message" class="fa fa-exclamation-triangle" style="display: none"></i></label>

                                    <div id="buttonDiv">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm"><i
                                                    class="text-primary glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>