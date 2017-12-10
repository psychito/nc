<?php
oseFirewall::checkDBReady();
$this->model->getNounce();
?>
<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php
        $this->model->showLogo();
        //       $this->model->showHeader();
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary plain ">
                    <!-- Start .panel -->
                    <div class="panel-body wrap-container">
                        <div class="row row-set"  data-bind="with:vl_data">
                            <div data-bind="foreach: {data: $data, as: 'vls'}">
                                <div class="col-sm-3 p-l-r-0">
                                    <div id="c-tag">
                                        <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title"><?php oLang::_('Vl_SCAN');?><span>
                                        </div>
                                        <p class="tag-content">
                                            <?php oLang::_('Vl_SCAN_DESC');?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="vs-line-1">
                                        <a href="#sectionWP" data-toggle="tab">
                                            <div class="cursor-pointer vs-line-1-title"> <i class="fa fa-<?php echo OSE_CMS=='joomla'? 'joomla' : 'wordpress' ?>"></i></div>
                                            <div class="vs-line-1-number">
                                                <?php echo OSE_CMS=='joomla'? 'Joomla' : 'Wordpress' ?>: &nbsp;&nbsp; <span class="scan-file-number" data-bind="filteLength: vls.content.CMS.vulnerabilities"></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <a data-toggle="tab" href="#sectionPlu">
                                        <div class="vs-line-1">
                                            <div class="cursor-pointer vs-line-1-title"> <i class="fa fa-wrench"></i></div>
                                            <div class="vs-line-1-number">
                                                Plugins: &nbsp;&nbsp;<span data-bind="with:vls.content.plugin">
                                <span class='scan-file-number' data-bind="filteLength:vls.content.plugin"></span>
                            </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-3">
                                    <div class="vs-line-1">
                                        <a data-toggle="tab" href="#sectionThm">
                                            <div class="cursor-pointer vs-line-1-title"> <i class="fa fa-puzzle-piece"></i></div>
                                            <div class="vs-line-1-number">
                                                Themes: &nbsp;&nbsp;<span data-bind="with:vls.content.theme">
                                <span class='scan-file-number' data-bind="filteLength:vls.content.theme"></span>
                            </span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-set" style="padding-right: 20px;">
                            <div id="scan-date"></div>
                            <div>
                                <?php require_once('template/vls/template-vls-records.php') ?>
                            </div>
                        </div>





                        <div class="row">
                            <?php require_once('template/vls/template-vls-scanstatus.php') ?>
                        </div>

                        <div class="col-sm-12" style="padding-right: 20px;">
                            <div id="scanbuttons">
                                <button id="vlscan" class='btn-new result-btn-set'><i id="ic-change"
                                                                                      class="glyphicon glyphicon-play color-green"></i> <?php oLang::_('START_NEW_SCAN') ?>
                                </button>
                                <button id="vlstop" class='btn-new result-btn-set'><i id="ic-change"
                                                                                      class="glyphicon glyphicon-stop color-red"></i> <?php oLang::_('STOP_VIRUSSCAN') ?>
                                </button>
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

    </div>
</div>

