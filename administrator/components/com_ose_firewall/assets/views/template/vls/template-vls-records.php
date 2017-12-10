<?php
/**
 * Created by PhpStorm.
 * User: zhuhua
 * Date: 11/08/15
 * Time: 9:45 AM
 */

?>

<div class="row">
    <div id="scan-result-panel" style="display: none;" data-bind="with:vl_data">
        <div  data-bind="foreach: {data: $data, as: 'vls'}">

            <!--                <ul class="nav nav-tabs">-->
            <!--                    <li role="presentation" class="vl-tabs active">-->
            <!--                        <a href="#sectionWP" data-toggle="tab">--><?php //echo OSE_CMS=='joomla'? 'Joomla' : 'Wordpress' ?>
            <!--                            <span class='bubble' data-bind="filteLength: vls.content.CMS.vulnerabilities"></span>-->
            <!--                        </a>-->
            <!--                    </li>-->
            <!--                    <li role="presentation" class="vl-tabs">-->
            <!--                        <a data-toggle="tab" href="#sectionPlu">Plugins-->
            <!--                            <span data-bind="with:vls.content.plugin">-->
            <!--                                <span class='bubble' data-bind="filteLength:vls.content.plugin"></span>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                    </li>-->
            <!--                    --><?php //if (OSE_CMS=='wordpress') {?>
            <!--                    <li role="presentation" class="vl-tabs">-->
            <!--                        <a data-toggle="tab" href="#sectionThm">Themes-->
            <!--                            <span data-bind="with:vls.content.theme">-->
            <!--                                <span class='bubble' data-bind="filteLength:vls.content.theme"></span>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                    </li>-->
            <!--                    --><?php //} ?>
            <!--                </ul>-->
            <div class="tab-content" >
                <!-- CMS section -->
                <div id="sectionWP" class="tab-pane fade" data-bind="with: vls.content.CMS.vulnerabilities">
                    <div style="margin-left: 20px;">Your&nbsp<strong><?php echo OSE_CMS=='joomla'? 'Joomla' : 'Wordpress' ?></strong>&nbsp
                        <strong data-bind="text:vls.content.CMS.version"></strong>&nbsp; &nbsp;has vulnerabilities:&nbsp
                    </div>
                    <div id="scan-result-content"  data-bind="foreach: $data">
                        <div id = "vl-short-desc" class="vl-short-desc collapsed">
                            <i class="fa fa-plus-circle"></i> &nbsp; &nbsp;<span data-bind="text:title" class=""></span>
                        </div>
                        <div class="vl-long-desc" style="display:none;">
                            <div class="vl-desc-item">
                                <div class="row" data-bind="if: $data.vuln_type">
                                    <div class="col-md-2">
                                        <span> Vulnerability type</span>
                                    </div>
                                    <div class="col-md-10">
                                        <span data-bind="text:$data.vuln_type"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- references -->
                            <?php include('template-vls-references.php'); ?>
                            <div class="vl-desc-item" data-bind="if:$data.fixed_in">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>Fixed In Version:</strong>
                                    </div>
                                    <div class="col-md-10" >
                                        <strong class = "text-success" data-bind="text:$data.fixed_in"></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="vl-desc-item" data-bind="if: $data.created_at">
                                <div class="row">
                                    <div class="col-md-2">
                                        Publicly Published:
                                    </div>
                                    <div class="col-md-10" data-bind="filteDate: created_at">
                                    </div>
                                </div>
                            </div>

                            <div class="vl-desc-item" data-bind="if:$data.updated_at">
                                <div class="row">
                                    <div class="col-md-2">
                                        Last Updated:
                                    </div>
                                    <div class="col-md-10" data-bind="filteDate: updated_at">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- plugin section -->
                <div id="sectionPlu" class="tab-pane fade">
                    <div id="scan-result-content" class="col-md-12" data-bind="foreach: {data : vls.content.plugin, as:'plu'}">
                        <div id = "vl-short-desc" class="vl-short-desc collapsed">
                            <i class="fa fa-plus-circle"></i>&nbsp
                            <strong data-bind="text:plu.name"></strong>&nbsp
                            <strong data-bind="text:plu.version"></strong>&nbsp; &nbsp;vulnerabilities
                                <span data-bind="with:plu.vulnerabilities">
                                    <span class='bubble' data-bind="filteLength:$data"></span>
                                </span>
                        </div>
                        <div class="vl-long-desc" style="display:none;">
                            <div class="plu-vls" data-bind="foreach: $data.vulnerabilities">
                                <span class="text-danger" data-bind="text: $data.title" style="margin-left:25px;"></span>

                                <div class="vl-desc-item">
                                    <div class="row" data-bind="if: $data.vuln_type">
                                        <div class="col-md-2">
                                            <span> Vulnerability type</span>
                                        </div>
                                        <div class="col-md-10">
                                            <span data-bind="text:$data.vuln_type"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- references -->
                                <?php include('template-vls-references.php'); ?>
                                <div class="vl-desc-item" data-bind="if:$data.fixed_in">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>Fixed In Version:</strong>
                                        </div>
                                        <div class="col-md-10" >
                                            <strong class = "text-success" data-bind="text:$data.fixed_in"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="vl-desc-item" data-bind="if: $data.created_at">
                                    <div class="row">
                                        <div class="col-md-2">
                                            Publicly Published:
                                        </div>
                                        <div class="col-md-10" data-bind="filteDate: created_at">
                                        </div>
                                    </div>
                                </div>

                                <div class="vl-desc-item" data-bind="if:$data.updated_at">
                                    <div class="row">
                                        <div class="col-md-2">
                                            Last Updated:
                                        </div>
                                        <div class="col-md-10" data-bind="filteDate: updated_at">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- themes section -->
                <div id="sectionThm" class="tab-pane fade">
                    <div id="scan-result-content" class="col-md-12"
                         data-bind="foreach: {data : vls.content.theme, as:'thm'}">
                        <div id = "vl-short-desc" class="vl-short-desc collapsed">
                            <i class="fa fa-plus-circle"></i>&nbsp<strong data-bind="text:thm.name"></strong>&nbsp
                            <strong class="text-warning" data-bind="text:thm.version"></strong>&nbspvulnerabilities
                                <span data-bind="with:thm.vulnerabilities">
                                    <span class='bubble' data-bind="filteLength:$data"></span>
                                </span>
                        </div>
                        <div class="vl-long-desc" style="display:none;">
                            <div class="thm-vls" data-bind="foreach: $data.vulnerabilities">
                                <strong class="text-danger" data-bind="text: $data.title"></strong>
                                <div class="vl-desc-item">
                                    <div class="row" data-bind="if: $data.vuln_type">
                                        <div class="col-md-2">
                                            <span> Vulnerability type</span>
                                        </div>
                                        <div class="col-md-10">
                                            <span data-bind="text:$data.vuln_type"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- references -->
                                <?php include('template-vls-references.php'); ?>
                                <div class="vl-desc-item" data-bind="if:$data.fixed_in">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>Fixed In Version:</strong>
                                        </div>
                                        <div class="col-md-10" >
                                            <strong class = "text-success" data-bind="text:$data.fixed_in"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="vl-desc-item" data-bind="if: $data.created_at">
                                    <div class="row">
                                        <div class="col-md-2">
                                            Publicly Published:
                                        </div>
                                        <div class="col-md-10" data-bind="filteDate: created_at">
                                        </div>
                                    </div>
                                </div>

                                <div class="vl-desc-item" data-bind="if:$data.updated_at">
                                    <div class="row">
                                        <div class="col-md-2">
                                            Last Updated:
                                        </div>
                                        <div class="col-md-10" data-bind="filteDate: updated_at">
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