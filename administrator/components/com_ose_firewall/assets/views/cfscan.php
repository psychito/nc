<?php
oseFirewall::checkDBReady();
//$status = oseFirewall::checkSubscriptionStatus(false);
$confArray = $this->model->getConfiguration('vsscan');
$this->model->getNounce();
if (isset($confArray['data']['vsScanExt']) && !isset($confArray['data']['file_ext'])) {
    $confArray['data']['file_ext'] = $confArray['data']['vsScanExt'];
}
?>
<div id="oseappcontainer">
        <div class="container wrapbody">
            <?php
            $this->model->showLogo();
            ?>
            <div class="row">
                <div class="col-lg-12 sortable-layout">
                    <div class="panel panel-primary plain toggle panelClose panelRefresh">
                        <!-- Start .panel -->
                        <div class="panel-controls"></div>
                        <div class="col-md-12 panel-body wrap-container">
                            <div class="row row-set">
                                <div class="col-sm-3 p-l-r-0">
                                    <div id="c-tag" style="height:170px;">
                                        <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title" style="height: 130px;">Core Directories Scanner<span>
                                        </div>
                                        <p class="tag-content">Core directories Scanner is a neat and quick detector, it
                                            scans the core directories of your website and detects suspicious files.
                                            Please notice that this scanner only applies to joomla and wordpress.</p>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div id="modified_btn" class="cfscan-line-1"
                                         style="padding-left: 45px; cursor: pointer;">
                                        <div class="title-icon"><i class="fa fa-exclamation-triangle"></i></div>
                                        <div class="title-content">
                                            Modified core<br> Files: &nbsp;<span class="scan-file-number" id="inf-file">0</span>
                                        </div>
                                    </div>
                                </div>
                                <?php if(oseFirewallBase::isSuite()) {?>
                                    <div class="col-sm-3">
                                        <div id="suspicious_btn" class="cfscan-line-1"
                                             style="padding-left: 10px; cursor: pointer;">
                                            <div class="title-icon"><i class="fa fa-ban"></i></div>
                                            <div class="title-content">
                                                Non-default system<br> Files: &nbsp;<span class="scan-file-number"
                                                                                          id="qua-file">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div id="missing_btn" class="cfscan-line-1" style="cursor: pointer;">
                                            <div class="title-icon"><i class="fa fa-times"></i></div>
                                            <div class="title-content">
                                                Missing<br> Files: &nbsp;<span class="scan-file-number"
                                                                               id="cle-file">0</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php }else  {?>
                                    <div class="col-sm-2">
                                        <div id="suspicious_btn" class="cfscan-line-1"
                                             style="padding-left: 10px; cursor: pointer;">
                                            <div class="title-icon"><i class="fa fa-ban"></i></div>
                                            <div class="title-content">
                                                Non-default system<br> Files: &nbsp;<span class="scan-file-number"
                                                                                          id="qua-file">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div id="missing_btn" class="cfscan-line-1" style="cursor: pointer;">
                                            <div class="title-icon"><i class="fa fa-times"></i></div>
                                            <div class="title-content">
                                                Missing<br> Files: &nbsp;<span class="scan-file-number"
                                                                               id="cle-file">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div id="cf-div-update" class="vs-line-1">
                                            <div class="vs-line-1-title"> <i id="icon-refresh" class="fa fa-refresh"></i></div>
                                            <div class="vs-line-1-content">
                                                <span id="cf-sig"></span></div>
                                        </div>
                                        <div id="cf-div-uptodate" class="vs-line-1">
                                            <div class="vs-line-1-title"> Core File are Up-to Date</div>
                                            <div class="vs-line-1-content">
                                                <p id="cf-uptodate"></p></div>
                                        </div>
                                    </div>
                                <?php }?>


                            </div>
                            <div class="row row-set" style="padding-right: 20px;">
                                <div id="scan-date"></div>
                                <div>
                                    <?php require_once('template/vls/template-vls-records.php') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div id="scanbuttons"></div>
                                <div id="scanbuttons-btngroup" class="col-md-12"
                                     style="margin-top: 0px;padding: 0 20px;">
                                    <div class="clean-buttons">
                                        <?php if (class_exists('SConfig')) { ?>
                                            <button data-target="#scanModal" data-toggle="modal" id="customscan"
                                                    class='btn-new result-btn-set'><i
                                                class="glyphicon glyphicon-search text-primary"></i> <?php oLang::_('START_NEW_SCAN') ?>
                                            </button>
                                        <?php } else { ?>
                                            <button id="cfscan" onclick="cfscan()" class='btn-new result-btn-set'><i
                                                    id="ic-change"
                                                    class="glyphicon glyphicon-search text-primary"></i> <?php oLang::_('START_NEW_SCAN') ?>
                                            </button>
                                        <?php }
                                        if ($_GET['centrora'] == 1) { ?>
                                            <button id="catchVirusMD5" onclick="catchVirusMD5()"
                                                    class='btn btn-sm mr5 mb10'><i
                                                    class="glyphicon glyphicon-search text-primary"></i> <?php oLang::_('CATCH_VIRUS_MD5') ?>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                            <div id="cfscan_result" class="row col-sm-12" style="padding:0 20px;">
                                <table class="table display" id="modifiedTable">
                                    <thead>
                                    <tr>
                                        <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <table id="suspiciousTable">
                                    <thead>
                                    <tr>
                                        <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <?php if((OSE_CMS != "wordpress"))
                                { ?>
                                <table class="table display" id="missingTable">
                                    <thead>
                                    <tr>
                                        <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <?php }?>
                            </div>

                            <?php
                            $oem = new CentroraOEM();
                            $oemCustomer = $oem->hasOEMCustomer();
                            if (!empty($oemCustomer['data']['customer_id'])) {
                                echo $oem->getCallToActionAndFooter();
                            } else { ?>
                                <?php echo $this->model->getCallToActionAndFooter();
                            } ?>
                        </div>
                    </div>
                    <!-- End .panel -->
                </div>

            </div>

        </div>
    </div>
    <!--    </div>-->
    <div id='fb-root'></div>
    <?php
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
    ?>

<!-- Form Modal -->
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCANPATH'); ?></h4>
            </div>
            <div class="modal-body" style="height:400px">
                <label style="vertical-align: top;"><?php oLang::_('FILETREENAVIGATOR'); ?></label>

                <div class="panel-body" id="FileTreeDisplay"></div>
            </div>
            <div class="modal-footer">
                <div class="panel-body">
                    <form id='scan-form' class="form-horizontal group-border stripped" role="form">
                        <div class="form-group">
                            <label for="scanPath" class="col-sm-1 control-label"><?php oLang::_('PATH'); ?></label>
                            <div class="col-sm-11">
                                <input type="text" name="scanPath" id="selected_file" class="form-control">
                            </div>
                        </div>
                        <input type="hidden" name="option" value="com_ose_firewall">
                        <input type="hidden" name="controller" value="cfscan">
                        <input type="hidden" name="action" value="suitecfscan">
                        <input type="hidden" name="task" value="suitecfscan">
                        <input id="cms" type="hidden" name="cms" value="">
                        <input id="version" type="hidden" name="version" value="">
                        <div class="form-group">
                            <div id="board"></div>
                            <div id="coreFilesDownload"></div>
                            <div>
                                <button type="submit" class="btn btn-sm" id='save-button' disabled><i
                                        class="glyphicon glyphicon-screenshot"></i> <?php oLang::_('SCAN_SPECIFIC_FOLDER'); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>