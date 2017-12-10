<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 31/08/15
 * Time: 9:55 AM
 */
oseFirewall::checkDBReady();
$this->model->getNounce();
$msg = $this->model->checkMD5DBUpToDate();
?>
<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php $this->model->showLogo();
        //  $this->model->showHeader();
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary plain ">
                    <!-- Start .panel -->

                    <div class="panel-body wrap-container">
                        <div class="row row-set">
                            <div class="col-sm-3 p-l-r-0">
                                <div id="c-tag">
                                    <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title">MD5 Hash Scanner<span>
                                    </div>
                                    <p class="tag-content">MD5 Hash Scanner checks for all known viruses and malware. It is recommended that if nothing is detected you use the Dynamic Scanner</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="vs-line-1">
                                    <div class="vs-line-1-number" style="padding-top: 40px;">
                                        <span id = "hashstatus"  class="scan-file-number" style="font-size: 16px; font-weight: 300;"><?php echo $msg['info'] ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="vs-line-1" style="padding:0px 45px 0px 0px;">
                                    <div class="vs-line-1-title" id="updateinfo"> <i id="updateMD5Sig" class="fa fa-refresh md5-icon-refresh"></i>
                                        <br><span style="opacity:0.7;">Click to update your MD5 Virus signature</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row row-set" style="padding-right: 20px;">
                            <div id="scan-date"></div>
                        </div>

                        <div class="row">
                            <!--Scan Status-->
                            <div id="scan-window" class="col-md-12" style="padding:0px 20px;">
                                <div id='scan_progress' class="alert alert-info fade in">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id = "status_content" class="col-md-12" style="display: none;" >
                                                <div id='status' class='col-md-12'>
                                                    <strong>Status </strong>
                                                    <div class="progress progress-striped active" style="width: 100%; margin-left:0px;">
                                                        <div id="vs_progress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                            <span id="p4text" ></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id = "last_batch" class='col-md-12'>Last Batch:
                                                    <strong id='last_file' class="text-white" style="color:white; padding-left:0px !important;"></strong>
                                                </div>
                                                <div class='col-md-12'># Scanned:
                                                    <strong id='total_number' class="text-white"></strong>
                                                </div>
                                                <div class='col-md-12'># Virus Files:
                                                    <a href="#scanresult"><strong id='vs_num' class="text-white"></strong></a>
                                                </div>
                                                <div id="surfcalltoaction" class='alert alert-dismissable alert-danger col-md-12' style="display: none;">
                                                    <!--                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
                                                    <?php oLang::_('SURF_SCAN_CALL_TOACTION') ?>
                                                </div>
                                            </div>
                                            <div id = "scanpathtext" class='col-md-12' style="display: none;">Scan Path:
                                                <label class="text-white" id="selectedfile"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row col-sm-12" style="padding-right: 20px;">
                            <div id="scanbuttons">
                                <button id="sfsstart" class='btn-new result-btn-set'>
                                    <i id="ic-change" class="glyphicon glyphicon-play color-green"></i> <?php oLang::_('START_NEW_SCAN') ?>
                                </button>
                                <button id="sfsstop" class='btn-new result-btn-set' style="display: none;">
                                    <i id="ic-change" class="glyphicon glyphicon-stop color-red"></i> <?php oLang::_('STOP_VIRUSSCAN') ?>
                                </button>
                                <button data-target="#scanPathModal" data-toggle="modal" id="setscanpath" title ="<?php oLang::_('SETSCANPATH') ?>"
                                        class='pull-right btn-new result-btn-set'>
                                    <i id="ic-change" class="glyphicon glyphicon-folder-close text-primary"></i>
                                    Set Scan Path
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12" id ="scan-result" class="row" style="display: none;">
                            <strong class="alert-danger">Virus Files Detected!</strong>
                            <div id="scan-result-panel"></div>
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
                <!--Scan Path Modal-->
                <div class="modal fade" id="scanPathModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <div class="form-group">
                                        <label for="scanPath" class="col-sm-1 control-label"><?php oLang::_('PATH');?></label>
                                        <div class="col-sm-11">
                                            <input type="text" name="scanPath" id="selected_file" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div>
                                            <button type="button" class="btn-new result-btn-set" id='save-button'><i class="glyphicon glyphicon-save text-white"></i> <?php oLang::_('SET');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>