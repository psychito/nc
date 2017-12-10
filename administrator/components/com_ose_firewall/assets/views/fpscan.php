<?php
oseFirewall::checkDBReady();
$confArray = $this->model->getConfiguration('vsscan');
$this->model->getNounce();

?>
<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php
        $this->model->showLogo();
        //        $this->model->showHeader();
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary plain ">
                    <!-- Start .panel -->

                    <div class="panel-body wrap-container">
                        <div class="row row-set">
                            <div class="col-sm-3 p-l-r-0">
                                <div id="c-tag" style="height:170px;">
                                    <div class="col-sm-12" style="padding-left: 0px; line-height: 20px;">
                                <span class="tag-title"><?php oLang::_('FILE_PERM_SCAN');?><span>
                                    </div>
                                    <p class="tag-content"><?php oLang::_('FILE_PERM_SCAN_DESC');?></p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="vs-line-1" style="padding-top: 28px;">
                                    <div class="vs-line-1-number">
                                        <span class="scan-file-number" style="font-weight: 300; font-size: 18px;"><?php oLang::_('BASE_FILE_PERMISSION');?></span>
                                    </div>
                                    <div class="vs-line-1-title" style="padding-top: 0px;">
                                        <span class="scan-file-number" style="font-weight: 300; font-size: 18px;">0644</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="vs-line-1" style="padding-top: 28px;">
                                    <div class="vs-line-1-number">
                                        <span  class="scan-file-number"  style="font-weight: 300; font-size: 18px;"><?php oLang::_('BASE_FOLDER_PERMISSION');?></span>
                                    </div>
                                    <div class="vs-line-1-title" style="padding-top: 0px;">
                                        <span class="scan-file-number" style="font-weight: 300; font-size: 18px;">0755</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row-set" style="padding-right: 20px;">
                            <div id="scan-date"></div>
                        </div>

                        <div class="row">
                            <div id="scan-window" class="col-md-12" style=" padding:0px 20px;">
                                <div id='scan_progress' class="alert alert-info fade in">
                                    <div class="row">

                                        <div class="col-md-11">
                                            <div id="status_content" class="col-md-12" style="display: none;">
                                                <div id='status' class='col-md-12'>
                                                    <strong><?php oLang::_('O_CHECKSTATUS');?> </strong>

                                                    <div class="progress progress-striped active"
                                                         style="margin-left: 0px;">
                                                        <div id="vs_progress" class="progress-bar" role="progressbar"
                                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                                             style="width: 0%">
                                                            <span id="p4text"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="last_batch" class='col-md-12'><?php oLang::_('LAST_BATCH');?>:
                                                    <strong id='last_file' class="text-white" style="color:white;"></strong>
                                                </div>
                                                <div class='col-md-12'><?php oLang::_('FILE_SCANNED');?>:
                                                    <strong id='total_number' class="text-white"></strong>
                                                </div>
                                                <div class='col-md-12'><?php oLang::_('INSECURE_PERMISSION_FILE');?>:
                                                    <a href="#scanresult"><strong id='vs_num' class="text-white"></strong></a>
                                                </div>
                                                <div id="surfcalltoaction"
                                                     class='col-md-12'
                                                     style="display: none;">
                                                    <!--                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
                                                    <?php oLang::_('FPSCAN_CALL_TOACTION') ?>
                                                </div>
                                            </div>
                                            <div id="scanpathtext" class='col-md-12' style="display: none;"><?php oLang::_('SCAN_PATH');?>:
                                                <label class="text-primary" id="selectedfile"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12" style="padding-right: 20px;">
                            <div id="scanbuttons">
                                <button id="sfsstop" class='btn-new result-btn-set' style="display: none">
                                    <i id="ic-change"
                                       class="glyphicon glyphicon-stop color-red"></i> <?php oLang::_('STOP_VIRUSSCAN') ?>
                                </button>
                                <button id="sfsstart" class='btn-new result-btn-set'>
                                    <i id="ic-change"
                                       class="glyphicon glyphicon-play color-white"></i> <?php oLang::_('START_NEW_SCAN') ?>
                                </button>
                                <button data-target="#scanPathModal" data-toggle="modal" id="setscanpath"
                                        title="<?php oLang::_('SETSCANPATH') ?>"
                                        class='btn-new result-btn-set'>
                                    <i id="ic-change" class="glyphicon glyphicon-folder-close text-primary"></i>
                                    Set Scan Path
                                </button>
                                <button class="btn-new result-btn-set" type="button"
                                        onClick="location.href='<?php $this->model->getPageUrl('permconfig'); ?>'"><i
                                        id="ic-change"
                                        class="text-primary glyphicon glyphicon-cog"></i><?php oLang::_('FILEPERM_EDITOR') ?>
                                </button>
                            </div>
                        </div>
                            <!--                            TABLE TO SHOW THE DATA -->
                            <div id="scan-result" class="row col-sm-12" style="padding:0 20px;">
                                <table class="table display" id="fpscanResults">
                                    <thead>
                                    <tr>
                                        <th><?php oLang::_('O_TYPE'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                        <th><?php oLang::_('PERMISSION'); ?></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th><?php oLang::_('O_TYPE'); ?></th>
                                        <th><?php oLang::_('O_FILE_SIZE'); ?></th>
                                        <th><?php oLang::_('PERMISSION'); ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!--                            TABLE TO SHOW THE DATA ENDS HERE -->
                            <div id="scan-result-panel"></div>
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
</div>
<div id='fb-root'></div>

<!--Scan Path Modal-->
<div class="modal fade" id="scanPathModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCANPATH'); ?></h4>
            </div>
            <div class="modal-body">
                <label style="vertical-align: top;"><?php oLang::_('FILETREENAVIGATOR'); ?></label>
                <div class="panel-body" id="FileTreeDisplay"></div>
            </div>
            <div class="modal-footer">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="scanPath" class="col-sm-1 control-label"><?php oLang::_('PATH'); ?></label>

                        <div class="col-sm-11">
                            <input type="text" name="scanPath" id="selected_file" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="button" class="btn btn-sm" id='save-button'><i
                                    class="glyphicon glyphicon-save text-success"></i> <?php oLang::_('SET'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="popoverhiddenhtml"  class="col-sm-2" style="display: none">
    <form name="fmode">
        <table class="table display table-condensed" id="chmodtbl">
            <tbody>
            <tr>
                <td align="center"><b><?php oLang::_('MODE'); ?></b></td>
                <td align="center"><?php oLang::_('OWNER'); ?></td>
                <td align="center"><?php oLang::_('GROUP'); ?></td>
                <td align="center"><?php oLang::_('PUBLIC'); ?></td>
            </tr>
            <tr>
                <td align="right"><?php oLang::_('READ'); ?></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="4" id="ur"></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="4" id="gr"></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="4" id="wr"></td>
            </tr>
            <tr>
                <td align="right"><?php oLang::_('WRITE'); ?></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="2" id="uw"></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="2" id="gw"></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="2" id="ww"></td>
            </tr>
            <tr>
                <td align="right"><?php oLang::_('EXECUTE'); ?></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="1" id="ux"></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="1" id="gx"></td>
                <td align="center"><input type="checkbox" onclick="calcperm();" value="1" id="wx"></td>
            </tr>
            <tr>
                <td align="right"><?php oLang::_('PERMISSION'); ?></td>
                <td><input style="text-align: center;" type="text" readonly="readonly" id="u" class="form-control"></td>
                <td><input style="text-align: center;" type="text" readonly="readonly" id="g" class="form-control"></td>
                <td><input style="text-align: center;" type="text" readonly="readonly" id="w" class="form-control"></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>

