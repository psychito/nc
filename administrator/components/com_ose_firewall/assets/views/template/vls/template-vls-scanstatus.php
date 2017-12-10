<?php
/**
 * Created by PhpStorm.
 * User: zhuhua
 * Date: 11/08/15
 * Time: 9:45 AM
 */

?>

<div id="scan-window" class="col-md-12" style="padding:0px 20px; margin:10px 0px;">
    <div id='scan_progress' class="alert alert-info fade in">
        <div class="row">
            <div id = "status_content" class="col-md-12" data-bind="with: vls_scan_status" style="display: none;" >
                <div data-bind="ifnot:progress=='100'">
                    <div id='status' class='col-md-12'>
                        <strong>Scanning Status </strong>
                        <div class="progress progress-striped active" style="margin-left: 0px; width: 100%">
                            <div id="vls_progress" class="progress-bar" role="progressbar" aria-valuenow="1"
                                 data-bind="style:{width: progress + '%'}" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                <span id="p4text" data-bind="text:progress + '%'"></span>
                            </div>
                        </div>
                    </div>

                    <div id='type' class='col-md-12'>Current Scanning Type:
                        <strong class="text-white" data-bind="text:current_type"></strong>
                    </div>
                    <div id='last_file' class='col-md-12' style="color:white; opacity: 0.6; height:35px; margin-left:-40px;">Current Scanning:
                        <strong class="text-white" data-bind="text:current_scan" style="color:white;"></strong>
                    </div>
                    <div id='total_number' class='col-md-12'>Total Scanned:
                        <strong class="text-white" data-bind="text:total_scan"></strong>
                    </div>
                    <div id='vl_num' class='col-md-12'>Number of Vulnerabilities:
                        <a href="#scanresult"><strong class="text-white" data-bind="text:total_vls"></strong></a></div>
                </div>
                <div data-bind="if:progress=='100'">
                    <div id='status' class='col-md-12'>
                        <strong>Status </strong>
                        <div class="progress" style="margin-left: 0px; width: 100%">
                            <div id="vls_progress" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style = "width: 100%">
                                <span id="p4text">Scanning Complete</span>
                            </div>
                        </div>
                    </div>
                    <div id='total_number' class='col-md-12'>Total Scanned: <strong
                            class="text-warning" data-bind="text:total_scan"></strong></div>
                    <div id='vl_num' class='col-md-12'>Number of Vulnerabilities:
                        <a href="#scanresult"><strong class="text-danger" data-bind="text:total_vls"></strong></a>
                    </div>
                    <div data-bind="if:total_vls > '0'">
                        <div class='alert col-md-11'>
                            <!--                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
                            <?php oLang::_('VL_CALL_TOACTION') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <strong id="scan-date" class="text-success"></strong>
        </div>
    </div>
</div>