<?php
oseFirewall::checkDBReady();
//$status = oseFirewall::checkSubscriptionStatus(false);
$url = oseFirewall::getCronjobURL();
$viewResult = oseFirewall::getViewResultURL();
$confArray = $this->model->getConfiguration('vsscan');
$this->model->getNounce();
if (isset($confArray['data']['vsScanExt']) && !isset($confArray['data']['file_ext'])) {
    $confArray['data']['file_ext'] = $confArray['data']['vsScanExt'];
}
?>
    <div id="oseappcontainer">
        <div class="container wrapbody" id="vsscan-page">
            <?php
            $this->model->showLogo();
//            $this->model->showHeader();
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
                                <span class="tag-title">Virus Scanner<span>
                                </div>
                                <p class="tag-content">Dynamic Virus Scanner is a powerful tool, acting like an Anti-Virus, to directly scan,detect,clean and quarantine the malware on the website.</p>
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <div class="vs-line-1">
                                <div class="vs-line-1-title"> <i class="fa fa-file"></i></div>
                                <div class="vs-line-1-number">
                                File Scanned: &nbsp;<span class="scan-file-number" id="numScanned">0</span>
                                </div>
                                </div>
                                </div>
                                <div class="col-sm-3">
                                <div class="vs-line-1">
                                <div class="vs-line-1-title"> <i class="fa fa-bug"></i></div>
                                <div class="vs-line-1-number">
                                Virus Files: &nbsp;<span  class="scan-file-number" id="numinfected">0</span>
                                </div>
                                </div>
                                </div>
                                <div class="col-sm-3">
                                 <div id="vs-div-update" class="vs-line-1">
                                <div class="vs-line-1-title"> <i id="icon-refresh" class="fa fa-refresh"></i></div>
                                <div class="vs-line-1-content">
                                <span id="v-sig"></span></div>
                                </div>
                                <div id="vs-div-uptodate" class="vs-line-1">
                                <div class="vs-line-1-title"> Virus Signature Upto Date</div>
                                <div class="vs-line-1-content">
                                <p id="vs-uptodate"></p></div>
                                </div>
                                </div>
                                  </div>

                            <div class="row row-set" style="padding-right: 20px;">

                            <div id="scan-result-green" class="col-sm-12">
                            <i id="close-green" class="fa fa-close icon_close"></i>
                            <div class="scan-result-title">Website Status</div>
                            <div class="result-icon"><i class="fa fa-check-circle-o"></i></div>
                            <div class="scan-result-content">No virus files are detected, please ensure to keep the Virus Signature up to date
                            if you are not a premium user.</div>
                            </div>
                            <div id="scan-result-red" class="col-sm-12" style="background-color:rgba(255, 255, 255, 0.8);">
                            <i id="close-red" class="fa fa-close icon_close"></i>
                            <div class="scan-result-title">Website Status :</div>

                            <div class="result-icon" style="color:rgba(232,70,78,0.7);"><i class="fa  fa-exclamation-triangle"></i></div>
                            <div class="scan-result-content">Attention! &nbsp; files are detected.
                            Please click "View Results" button to check the results.</div>
                                <a href='<?php echo $viewResult ?>'> <div id="btn-scan-viewResult">
                                <i class="fa fa-eye"></i> View Results</div></a>

                            </div>
                            <div id="time-bar"></div>
                                <div class="col-sm-9" style="padding: 10px 0px 10px 0px; background-color:rgba(242,241,239,0.6);">
                                 <div>

                                 <div id="selectoptionbar">
                                 <div class="col-sm-4" style="padding-left: 0px; padding-right: 0px;">
                                 <div class="col-sm-5 select-st">
                                 <p><i class="fa fa-arrow-right"></i></p>
                                     <p style="margin-top: -10px;">Select Scan Types</p>
                                 </div>
                                     <div class="col-sm-7" style="padding-left: 0px; padding-right: 0px;">
                                     <div id="btn-quick-scan" class="col-sm-12">
                                        <p><i class="fa fa-bolt"></i></p>
                                        <p style="margin-top: -13px;">Quick Scan</p>
                                      </div>
                                      <div id="btn-full-scan" class="col-sm-6">
                                        <p><i class="fa fa-sitemap"></i></p>
                                        <p style="margin-top: -10px;">Full Scan</p>
                                    </div>
                                    <div id="btn-deep-scan" class="col-sm-6">
                                        <p><i class="fa  fa-sort-amount-asc"></i></p>
                                        <p style="margin-top: -10px;">Deep Scan</p>
                                    </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-8" style="padding-left: 0px; padding-right: 0px;">
                                 <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                                    <div id="shell-codes-box" class="col-sm-3 checkbox-line-1 bg-color-lightgrey">
                                    <label onclick="boxColorChange('shell-codes')"><input id="shell-codes" class="messageCheckbox" type="checkbox" name="type[]" value="1">Shell
                                            Codes</label>
                                    </div>
                                    <div id="javascript-injection-box" class="col-sm-3 checkbox-line-1 bg-color-lightgrey">
                                    <label onclick="boxColorChange('javascript-injection')"><input id="javascript-injection" class="messageCheckbox" type="checkbox" name="type[]" value="3">Javascript
                                            Injection</label>
                                    </div>
                                    <div id="frame-injection-box" class="col-sm-3 checkbox-line-1 bg-color-lightgrey">
                                    <label onclick="boxColorChange('frame-injection')"><input id="frame-injection" class="messageCheckbox" type="checkbox" name="type[]" value="5">Iframe
                                            Injection</label>
                                    </div>
                                    <div id="execute-box" class="col-sm-3 checkbox-line-1 bg-color-lightgrey">
                                    <label onclick="boxColorChange('execute')"><input id="execute" class="messageCheckbox" type="checkbox" name="type[]" value="7">Executable
                                            Malicious Codes</label>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                                    <div id="base-encode-box" class="col-sm-3 checkbox-line-2 bg-color-lightgrey">
                                    <label onclick="boxColorChange('base-encode')"><input id="base-encode" class="messageCheckbox" type="checkbox" name="type[]" value="2">Base64
                                            Encoded Codes</label>
                                    </div>
                                    <div id="php-injection-box" class="col-sm-3 checkbox-line-2 bg-color-lightgrey">
                                     <label onclick="boxColorChange('php-injection')"><input id="php-injection" class="messageCheckbox" type="checkbox" name="type[]" value="4">PHP
                                            Injection</label>
                                    </div>
                                    <div id="spamming-box" class="col-sm-3 checkbox-line-2 bg-color-lightgrey">
                                    <label onclick="boxColorChange('spamming')"><input id="spamming" class="messageCheckbox" type="checkbox" name="type[]" value="6">Spamming
                                            Mailer Injection</label>
                                    </div>
                                    <div id="other-mmc-box" class="col-sm-3 checkbox-line-2 bg-color-lightgrey">
                                    <label onclick="boxColorChange('other-mmc')"><input id="other-mmc" class="messageCheckbox" type="checkbox" name="type[]" value="8">Other
                                            Miscellaneous Malicious Codes</label>
                                      </div>
                                </div>
                                     </div>
                                </div>
                                <div id="progressingbar" class="col-sm-12">
                                <div class="row">
                                <div id="progress-status" class="col-sm-3">
                                Progress
                                </div>
                                  <div id="percentage-box">
                                </div>
                                <div id="progress-processing-box">
                                  <div class="progress progress-striped active">
                                                    <div id="vs_progress" class="progress-bar" role="progressbar"
                                                         aria-valuenow=0" aria-valuemin="0" aria-valuemax="100"
                                                         style="width: 0%">
                                                    </div>

                                                </div>
                                </div>

                                </div>
                                <div class="row" style="margin-top: 15px;">
<!--                                <div class="col-sm-3" style="padding-left: 0px;">-->
<!--                                <div id="time-used-title"><i class="fa fa-clock-o"></i> Time Used</div>-->
<!--                                <div id="timeUsed"> </div>-->
<!--                                </div>-->
                                <div class="col-sm-12" style="padding-left: 39px;">
                                <div class="col-sm-3">Time Used: <span id="timeUsed"></span></div>
                                <div class="col-sm-3">Memory: <span id="memory"></span></div>
                                <div class="col-sm-5">CPU: <span id="cpu"></span></div>
                                </div>
                                </div>
                                 <div class="row" style="margin-top: 18px;">
                                <div class="col-sm-12 last_file_title">Last Scanned File:</div>
                                <div class="col-sm-12" id="last_file"></div>
                                </div>
                                </div>
                                </div>
                                </div>

                                <div class="col-sm-3" style="background-color:rgba(242,241,239,0.6); padding-top: 10px; padding-bottom: 10px;">
                                <div id="btn-schedule-scan" style="float:left; width:47%" onClick="location.href='<?php echo $url . '#vscannercron'; ?>'">
                                <i class="fa fa-calendar"></i><p>Schedule Scan</p>
                                </div>
                                <div id="btn-config" style="float:left; width:47%" data-target="#configModal" data-toggle="modal">
                                <i class="fa fa-bar-chart-o"></i><p>Config Setup</p>
                                </div>
                                <div id="vsscan" class="vs-bg-dark col-sm-12">
                                START &nbsp;<i class="fa fa-play"></i>
                                </div>
                                <div id="vsscan-cont" class="vs-bg-dark col-sm-12">
                                CONTINUE &nbsp;<i class="fa fa-play"></i>
                                </div>
                                <div id="vsscan-stop" class="vs-bg-dark col-sm-12">
                                STOP &nbsp;<i class="fa fa-pause"></i>
                                </div>
                                <div id="vsscan-fresh" class="vs-bg-dark col-sm-12">
                                REFRESH &nbsp;<i class="fa fa-repeat"></i>
                                </div>
                                <div data-target="#scanModal" data-toggle="modal" id="customscan" class="vs-bg-dark col-sm-12" style="margin-top:5px; height: 50px;">
                                <i class="fa fa-folder" style="margint-right:3px;"></i>Scan Specific Folder
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

                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts col-md-4">-->
                            <!--                                    <div class="easy-pie-chart" data-percent="0" id='easy-pie-chart-1'><span id='pie-1'>0%</span>-->
                            <!--                                    </div>-->
                            <!--                                    <div class="label">-->
                            <!--                                        --><?php //oLang::_('O_SHELL_CODES'); ?>
                            <!--                                        <div id="shell-result"></div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_1" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_1' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                                </div>-->
                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts red-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-red" data-percent="0" id='easy-pie-chart-2'><span-->
                            <!--                                            id='pie-2'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_BASE64_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_2" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_2' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                                </div>-->
                            <!--                                <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts green-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-green" data-percent="0" id='easy-pie-chart-3'><span-->
                            <!--                                            id='pie-3'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_JS_INJECTION_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                    <div id="last_batch_3" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                        <strong id='last_file_3' class="text-success"></strong>-->
                            <!--                                    </div>-->
                            <!--                                    </div>-->
                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts blue-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-blue" data-percent="0" id='easy-pie-chart-4'><span-->
                            <!--                                            id='pie-4'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_PHP_INJECTION_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_4" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_4' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                                </div>-->
                            <!---->
                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts teal-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-teal" data-percent="0" id='easy-pie-chart-5'><span-->
                            <!--                                            id='pie-5'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_IFRAME_INJECTION_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_5" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_5' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                                </div>-->
                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts purple-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-purple" data-percent="0" id='easy-pie-chart-6'><span-->
                            <!--                                            id='pie-6'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_SPAMMING_MAILER_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_6" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_6' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                                </div>-->
                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts orange-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-orange" data-percent="0" id='easy-pie-chart-7'><span-->
                            <!--                                            id='pie-7'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_EXEC_MAILICIOUS_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_7" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_7' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                                </div>-->
                            <!--                            <div class="row vsscanner-piechart">-->
                            <!--                                <div class="pie-charts lime-pie col-md-4">-->
                            <!--                                    <div class="easy-pie-chart-lime" data-percent="0" id='easy-pie-chart-8'><span-->
                            <!--                                            id='pie-8'>0%</span></div>-->
                            <!--                                    <div class="label">-->
                            <?php //oLang::_('O_OTHER_MAILICIOUS_CODES'); ?><!--</div>-->
                            <!--                                </div>-->
                            <!--                                <div id="last_batch_8" class='col-md-8'> --><?php //oLang::_('LAST_SCANNED_FILE') ?>
                            <!--                                    <strong id='last_file_8' class="text-success"></strong>-->
                            <!--                                </div>-->
                            <!--                            </div>-->

                            <!--                            <div class="row">-->
                            <!--                                <div class="col-lg-6 col-md-12 sortable-layout">-->
                            <!--                                    <div class="panel panel-default plain ">-->
                            <!--                                        <div class="panel-heading">-->
                            <!--                                            <h4 class="panel-title">CPU Load</h4>-->
                            <!--                                        </div>-->
                            <!--                                        <div class="panel-body">-->
                            <!--                                            <div id="line-chart-cpu" style="width: 100%; height:250px;"></div>-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                                <div class="col-lg-6 col-md-12 sortable-layout">-->
                            <!--                                    <div class="panel panel-default plain ">-->
                            <!--                                        <div class="panel-heading">-->
                            <!--                                            <h4 class="panel-title">Memory Usage</h4>-->
                            <!--                                        </div>-->
                            <!--                                        <div class="panel-body">-->
                            <!--                                            <div id="line-chart-memory" style="width: 100%; height:250px;"></div>-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                            </div>-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
           <!-- Background scanning Form Modal -->
         <div class="modal fade" id="bgModel" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel2">Background Scanning</h4>
                    </div>
                    <div class="modal-body">
                        <form id='emailForm' class="form-horizontal group-border stripped" role="form"
                              method="POST">
                            <div class="form-group">
                                <label for="email-address"
                                       class="col-sm-2 control-label">Email:</label>

                                <div class="col-sm-10">
                                    <input type="text" name="bgscan_email"
                                           id="input-email"
                                           value=""
                                           class="form-control">
                                </div>
                                <div id="email-content" class="col-sm-10"></div>
                                <div class="col-sm-12" style="margin-left: 13px; opacity: 0.8;">Background Scanning will run the scans at the backend cosole and the result will be sent to your email address after completion</div>
                            </div>

                                <div class="col-sm-offset-10">
                                    <div  class="btn btn-default" id='start-bg-button'>Start</div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Form Modal -->
        <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('CONFIGURATION'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <form id='configuraton-form' class="form-horizontal group-border stripped" role="form"
                              method="POST">
                            <div class="form-group">
                                <label for="file_ext"
                                       class="col-sm-4 control-label"><?php oLang::_('O_SCANNED_FILE_EXTENSIONS'); ?></label>

                                <div class="col-sm-8">
                                    <input type="text" name="file_ext"
                                           value="<?php echo (isset($confArray['data']['file_ext']) && empty($confArray['data']['file_ext'])) ? 'htm,html,shtm,shtml,css,js,php,php3,php4,php5,inc,phtml,jpg,jpeg,gif,png,bmp,c,sh,pl,perl,cgi,txt' : $confArray['data']['file_ext'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="maxfilesize"
                                       class="col-sm-4 control-label"><?php oLang::_('O_MAX_FILE_SIZE'); ?></label>

                                <div class="col-sm-8">
                                    <input type="text" name="maxfilesize"
                                           value="<?php echo (isset($confArray['data']['maxfilesize']) && empty($confArray['data']['maxfilesize'])) ? '3' : $confArray['data']['maxfilesize'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="maxdbconn"
                                       class="col-sm-4 control-label"><?php oLang::_('MAX_DB_CONN'); ?></label>

                                <div class="col-sm-8">
                                    <input type="text" name="maxdbconn"
                                           value="<?php echo (isset($confArray['data']['maxdbconn']) && empty($confArray['data']['maxdbconn'])) ? '0' : $confArray['data']['maxdbconn'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="maxextime"
                                       class="col-sm-4 control-label"><?php oLang::_('MAX_EX_TIME'); ?></label>

                                <div class="col-sm-8">
                                    <input type="text" name="maxextime"
                                           value="<?php echo (isset($confArray['data']['maxextime']) && empty($confArray['data']['maxextime'])) ? '3' : $confArray['data']['maxextime'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="option" value="com_ose_firewall">
                            <input type="hidden" name="controller" value="avconfig">
                            <input type="hidden" name="action" value="saveConfAV">
                            <input type="hidden" name="task" value="saveConfAV">
                            <input type="hidden" name="type" value="vsscan">

                            <div class="form-group">
                                <div class="col-sm-offset-10">
                                    <button type="submit" class="btn btn-default" id='save-button'><i
                                            class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?></button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->
    </div>
    <?php
    include_once(dirname(__FILE__) . '/scanpath.php');
?>

    <?php
 $this->model->showFooterJs();
?>