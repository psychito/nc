<?php
$this->model->getNounce ();
?>
<!-- Add Variable Form Modal -->
                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('INSTALLDB'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'dbinstall-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                   	<div class="form-group">
                                           <div class="col-sm-12">
                                                <div id = "progressbar" class="progress-circular-blue" data-dimension="100" data-text="0%" data-width="12" data-percent="0"></div>
                                                <div class="download-message" id='message-box' style="margin-top:10px;"></div>
                                           </div>
                                    </div>
                                    <div class="form-group" >
<!--                                           <label class="col-sm-6 control-label" for="textfield"></label>-->
                                           <div>
                                               <button type="submit" class="btn-new col-sm-12" id='installer-buttons'><i class="glyphicon glyphicon-cog"></i> <?php oLang::_('INSTALLNOW');?></button>
                                           </div>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->

<!-- Add Variable Form Modal -->
                <div class="modal fade" id="formModal2" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('UNINSTALLDB'); ?></h4>
                            </div>
                            <div class="modal-body">
                              <form id = 'dbuninstall-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">                            
                                   	<div class="form-group">
                                           <div class="col-sm-12">
                                                <div id = "progressbar" class="progress-circular-blue" data-dimension="100" data-text="0%" data-width="12" data-percent="0"></div>
                                                <div class="download-message" id='message-box2' style="margin-top: 10px;"></div>
                                           </div>
                                    </div>
                                    <div class="form-group" >
<!--                                           <label class="col-sm-6 control-label" for="textfield"></label>-->
                                           <div>
                                               <button type="submit" class="btn-new col-sm-12" id='uninstaller-buttons'><i class="glyphicon glyphicon-trash"></i> <?php oLang::_('UNINSTALLNOW');?></button>
                                           </div>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->                
<div id="oseappcontainer">
	<div class="container wrapbody">
	<?php
	$this->model->showLogo ();
//	$this->model->showHeader ();
	?>
        <?php
        if (OSE_CMS == 'joomla') {
            oseFirewall::callLibClass('audit', 'audit');
            $audit = new oseFirewallAudit ();
            $plugin = $audit->isPluginEnabled('plugin', 'centrora', 'system');
            if (empty($plugin) || $plugin->enabled == false) {
                $action = (!empty($plugin)) ? '<button class="btn btn-danger btn-xs fx-button" style="padding-top: 0px; padding-bottom: 0px;" onClick ="actCentroraPlugin()" >Fix It</button>' : '';
            }
            if (!empty($action)) {
                ?>
                <div id="pluginNotice" class="col-sm-12">
                    <div class="bg-transparent-white" style="padding: 20px 80px 20px 60px;">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php
                        echo '<span class="label label-warning" style="margin-right: 20px; padding-top: 10px; padding-bottom: 10px;">Warning</span> ' . oLang::_get('SYSTEM_PLUGIN_DISABLED') . $action;                        ?>
                    </div>
                </div>
            <?php
            }
        }
        ?>
	<div class="content-inner">
	<?php 
	  			if (!oseFirewall::isDBReady())
	  			{
	  		?>
                    <div class="col-sm-12">
                        <div class="col-sm-12 bg-transparent-white" style="padding-left: 40px; padding-right: 40px;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button" style="margin-top: -15px;">×</button>
                            <i class="glyphicon glyphicon-warning-sign"></i>
                            <strong><?php oLang::_('DBNOTREADY'); ?></strong>.  <?php oLang::_('DBNOTREADY_AFTER'); ?>
                        </div>
                    </div>

            <?php 
	  			}
            ?>
        <div class="row">
                        <div class="col-sm-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain">
                                <!-- Start .panel -->

                                    <div class="panel-body wrap-container">
                                    <div class="row row-set">
                                        <div class="col-sm-5 p-l-r-0">
                                            <div id="c-tag">
                                                <div class="col-sm-12" style="padding-left: 0px;">
                                                    <span class="tag-title">Installation</span>
                                                </div>
                                                <p class="tag-content">Database tables install or uninstall here.</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                        </div>
                                    </div>
                                        <div class="row row-set">
                                            <div class="title-bar">install & uninstall</div>
                                            <section class="ose-options">
                                                <?php
                                                $this->model->showConfigBtnList();
                                                ?>
                                            </section>
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
</div>