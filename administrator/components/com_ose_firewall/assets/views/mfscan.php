<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 31/08/15
 * Time: 9:55 AM
 */
oseFirewall::checkDBReady();
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

                    <div class="panel-body wrap-container">
                        <div class="row row-set">
                            <div class="col-sm-3 p-l-r-0">
                                <div id="c-tag">
                                    <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title">Modified Files Scanner<span>
                                    </div>
                                    <p class="tag-content">The Modified Files Scanner can detect modified files within a certain time period and files which are symbolic links.</p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="vs-line-1" style="padding-top: 28px;">
                                    <div class="vs-line-1-number">
                                        <span class="scan-file-number" style="font-weight: 300; font-size: 18px;">Set Start Date</span>
                                    </div>
                                    <div class="vs-line-1-title" style="padding-top: 0px;"><input id='datepicker1' type='text' name="startdate"/></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="vs-line-1" style="padding-top: 28px;">
                                    <div class="vs-line-1-number">
                                        <span  class="scan-file-number"  style="font-weight: 300; font-size: 18px;">Set End Date</span>
                                    </div>
                                    <div class="vs-line-1-title" style="padding-top: 0px;"><input id='datepicker2' type='text' name="enddate"/></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="vs-line-1">
                                    <div class="vs-line-1-title"> <i id="mod-scanner-ssl" class="cursor-pointer fa  fa-square-o"><input type='checkbox' id="symlink"/></i></div>
                                    <div class="vs-line-1-number">
                                        Scan Symbolic link
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
                                            <div id="status_content" class="col-md-12" style="display: none;">
                                                <div id='status' class='col-md-12'>
                                                    <strong>Status </strong>
                                                    <div class="progress progress-striped active" style="width: 100%; margin-left:0px;">
                                                        <div id="vs_progress" class="progress-bar" role="progressbar"
                                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                                             style="width: 0%">
                                                            <span id="p4text"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="last_batch" class='col-md-12'>Last Batch:
                                                    <strong id='last_file' class="text-white" style="color:white;"></strong>
                                                </div>
                                                <div class='col-md-12'># Scanned:
                                                    <strong id='total_number' class="text-white"></strong>
                                                </div>
                                                <div class='col-md-12'># Modified Files:
                                                    <a href="#scanresult"><strong id='vs_num' class="text-white"></strong></a>
                                                </div>
                                                <div id="surfcalltoaction"
                                                     class='alert alert-dismissable alert-warning col-md-12'
                                                     style="display: none;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    <?php oLang::_('FPSCAN_CALL_TOACTION') ?>
                                                </div>
                                            </div>
                                            <div id="scanpathtext" class='col-md-12' style="display: none;">Scan Path:
                                                <label class="text-white" id="selectedfile"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div id="scanbuttons" class="col-sm-12" style="padding:0px 20px; margin-top:5px;">
                                <button id="sfsstop" class='btn-new result-btn-set' style="display: none;">
                                    <i class="glyphicon glyphicon-stop color-red"></i> <?php oLang::_('STOP_VIRUSSCAN') ?>
                                </button>
                                <button id="sfsstart" class='btn-new result-btn-set'>
                                    <i id="ic-change" class="glyphicon glyphicon-play color-green"></i> <?php oLang::_('START_NEW_SCAN') ?>
                                </button>
                                <button data-target="#scanPathModal" data-toggle="modal" id="setscanpath"
                                        title="<?php oLang::_('SETSCANPATH') ?>"
                                        class='pull-right btn-new result-btn-set'>
                                    <i class="glyphicon glyphicon-folder-close text-primary"></i>  Set Scan Path
                                </button>
                            </div>
                        </div>
                        <div id="scan-result" class="row col-sm-12" style="padding:0 20px;">
                            <table class="table display" id="mfscanResults">
                                <thead>
                                <tr>
                                    <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                    <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    <th><?php oLang::_('O_LAST_MODIFIED'); ?></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th><?php oLang::_('O_FILE_PATH'); ?></th>
                                    <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                    <th><?php oLang::_('O_LAST_MODIFIED'); ?></th>
                                </tr>
                                </tfoot>
                            </table>
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
                                    <label for="scanPath"
                                           class="col-sm-1 control-label"><?php oLang::_('PATH'); ?></label>

                                    <div class="col-sm-11">
                                        <input type="text" name="scanPath" id="selected_file" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <button type="button" class="btn-new result-btn-set" id='save-button'><i class="glyphicon glyphicon-save text-white"></i> Set Path
                                        </button>
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


<!-- Export Form Modal -->
<div class="modal fade" id="exportModal_mfscan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_DOWNLOAD_CSV'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-10 col-md-10">
                    <?php
                    echo $this->model->exportcsv_mfscan();
                    ?>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!--<!-- /.modal -->-->
