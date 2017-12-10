<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
if ($status == true)
{
?>
<div id="oseappcontainer">
	<div class="container wrapbody">
	<?php
	$this->model->showLogo ();
//	$this->model->showHeader ();
	?>
	<!-- Export Form Modal -->
                <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_EXPORT_INFECTED_CSV'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="col-lg-8 col-md-7">
                                    <?php 
                                    	echo $this->model->exportcsv(); 
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->
	
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
										<div class="col-sm-3 p-l-r-0">
											<div id="c-tag" style="height:130px;">
												<div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title" style="height: 130px;">Scanner Report<span>
												</div>
												<p class="tag-content">Display the infected files last scanned by the virus scanner.</p>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="screport-line-1" style="padding-left: 45px;">
												<div class="title-icon"><i class="fa fa-bug"></i></div>
												<div class="title-content">
													Infected<br> Files: &nbsp;<span class="scan-file-number" id="inf-file">0</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="screport-line-1" style="padding-left: 10px;">
												<div class="title-icon"><i class="fa fa-exclamation-triangle"></i></div>
												<div class="title-content">
													Quarantined<br> Files: &nbsp;<span class="scan-file-number" id="qua-file">0</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="screport-line-1">
												<div class="title-icon"><i class="fa  fa-trash-o"></i></div>
												<div class="title-content">
													Cleaned<br> Files: &nbsp;<span class="scan-file-number" id="cle-file">0</span>
												</div>
											</div>
										</div>
									</div>

								<div class="row">
									<div id="sticky-anchor"></div>
                                  <div id="report-btngroup" class="col-md-12" style="margin-top: 0px;padding: 0 20px;">
                                   <div class="clean-buttons">
										<button data-target="#exportModal" data-toggle="modal" class="btn-new result-btn-set"><i id="ic-change" class="text-primary glyphicon glyphicon-export"></i> <?php oLang::_('O_EXPORT_INFECTED_CSV'); ?>
										</button>
									   <button id="delete-button" class="btn-new result-btn-set" type="button"
											   style="display: none" onClick="confirmbatchdl()">
										   <i id="ic-change" class="text-danger glyphicon glyphicon-trash"></i>
										   <?php oLang::_('O_SCANREPORT_DELETE'); ?>
									   </button>
									   <button class="btn-new result-btn-set" type="button" onClick="batchrs()">
										   <i id="ic-change" class="text-success glyphicon glyphicon-retweet"></i>
										   <?php oLang::_('O_SCANREPORT_RESTORE'); ?>
									   </button>
									   <button class="btn-new result-btn-set" type="button" onClick="batchMarkAsClean()">
										   <i id="ic-change" class="text-warning glyphicon glyphicon-check"></i>
										   <?php oLang::_('O_SCANREPORT_MARKASCLEAN'); ?>
									   </button>
									   <button class="btn-new result-btn-set" type="button" onClick="batchquarantine()">
										   <i id="ic-change" class="text-primary glyphicon glyphicon-alert"></i>
										   <?php oLang::_('O_SCANREPORT_QUARANTINE'); ?>
									   </button>
									   <button class="btn-new result-btn-set" type="button" onClick="batchbkcl()">
										   <i id="ic-change" class="text-success glyphicon glyphicon-erase"></i>
										   <?php oLang::_('O_SCANREPORT_CLEAN'); ?>
									   </button>
								   </div>
                                 </div>
                                </div>
                                <div class="row col-sm-12" style="padding:0 20px;">
                                    <table class="table display" id="scanreportTable">
                                        <thead>
                                            <tr>
                                                <th><?php oLang::_('O_FILE_ID'); ?></th>
                                                <th><?php oLang::_('O_PATTERN_ID'); ?></th>
								                <th><?php oLang::_('O_FILE_NAME'); ?></th>
                                                <th><?php oLang::_('O_CHECKSTATUS'); ?></th>
								                <th><?php oLang::_('SIZE'); ?></th>
								                <th><?php oLang::_('VIEW'); ?></th>
                                                <th><input id='checkbox' type='checkbox'></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_FILE_ID'); ?></th>
                                                <th><?php oLang::_('O_PATTERN_ID'); ?></th>
								                <th><?php oLang::_('O_FILE_NAME'); ?></th>
                                                <th><?php oLang::_('O_CHECKSTATUS'); ?></th>
								                <th><?php oLang::_('SIZE'); ?></th>
								                <th><?php oLang::_('VIEW'); ?></th>
												<th></th>
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
                            <!-- End .panel -->
                        </div>
	   </div>
	   <?php 
//			CentroraOEM::showProducts();
	   ?>
	   </div>
	</div>
</div>

<?php 
include_once(dirname(__FILE__).'/filecontent.php');
}
else {
?>
	<div id="oseappcontainer">
		<div class="container wrapbody">
			<?php
			$this->model->showLogo();
			?>
			<div class="col-sm-12">
				<div class="col-sm-12 wrap-container">
				<div class="row row-set">
					<div class="col-sm-3 p-l-r-0">
						<div id="c-tag" style="height:130px;">
							<div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title" style="height: 130px;">Scanner Report<span>
							</div>
							<p class="tag-content">Display the infected files last scanned by the virus scanner.</p>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="screport-line-1" style="padding-left: 45px;">
							<div class="title-icon"><i class="fa fa-bug"></i></div>
							<div class="title-content">
								Infected<br> Files: &nbsp;<span class="scan-file-number" id="inf-file">0</span>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="screport-line-1" style="padding-left: 10px;">
							<div class="title-icon"><i class="fa fa-exclamation-triangle"></i></div>
							<div class="title-content">
								Quarantined<br> Files: &nbsp;<span class="scan-file-number" id="qua-file">0</span>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="screport-line-1">
							<div class="title-icon"><i class="fa  fa-trash-o"></i></div>
							<div class="title-content">
								Cleaned<br> Files: &nbsp;<span class="scan-file-number" id="cle-file">0</span>
							</div>
						</div>
					</div>
				</div>
<!--				<div class="row row-set" style="padding-right: 20px;">-->
<!---->
<!--						<div class="col-sm-12 goforpremium">-->
<!--							<div class="col-sm-5">-->
<!--					<a class="btn-new" target="_blank" href="http://www.centrora.com/developers/" class="btn-new">Go for Centrora Premium</a>-->
<!--								</div>-->
<!--							<div class="col-sm-7">-->
<!--								<ul>-->
<!--									<li class="pre-title">Complete premium functions including:</li>-->
<!--									<li>View the full scan result in details</li>-->
<!--									<li>Browse source codes and highlight the detected suspicious codes</li>-->
<!--									<li>Free Malware Removal Service for annual subscribers</li>-->
<!--									<li>Scheduled automatic virus scanning and regular email notifications</li>-->
<!--								</ul>-->
<!--							</div>-->
<!--							</div>-->
<!--				</div>-->
					<div class="row">
						<div id="pop_subscription" class="col-lg-6">
							<span>This is a Premium Feature &nbsp;<i class="fa fa-certificate"></i></span>
							<img id="pop_close" src="<?php echo $this->model->getImgUrl('close_cross.png'); ?>">
							<p>Premium features includes</p>
							<ul class="pop_ul">
								<li>Free Malware Removal Service for annual subscribers until the website is 100% clean.</li>
								<li>View infected files in detail by browsing source codes with suspicious codes highlighted..</li>
								<li>Clean or quarantine infected files within the scan report without accessing FTP.</li>
								<li>Monitor website malware with scheduled automatic virus scanning and email notifications.</li>
								<li>Automated backup to GitLab every 1 hour.</li>
								<li>Store your files securely in GitLab.</li>
								<li>10GB free spaces for each website in GitLab.</li>
								<li>Roll back from copies in GitLab in case of server fault</li>
								<li>Save your hosting space and save you money</li>
							</ul>
							<a  target="_blank" href="https://www.centrora.com/services/">
								<button id="btn_gopremium">Go Premium</button></a>

						</div>
						<div id="sticky-anchor"></div>
						<div id="report-btngroup" class="col-md-12" style="margin-top: 0px; padding: 0 20px;">
							<div id="btn-fr-user" class="clean-buttons">
								<button data-target="#exportModal" data-toggle="modal" class="btn-new result-btn-set"><i id="ic-change" class="text-primary glyphicon glyphicon-export"></i> <?php oLang::_('O_EXPORT_INFECTED_CSV'); ?>
								</button>
								<button id="delete-button" class="btn-new result-btn-set" type="button"
										style="display: none">
									<i id="ic-change" class="text-danger glyphicon glyphicon-trash"></i>
									<?php oLang::_('O_SCANREPORT_DELETE'); ?>
								</button>
								<button class="btn-new result-btn-set" type="button" >
									<i id="ic-change" class="text-success glyphicon glyphicon-retweet"></i>
									<?php oLang::_('O_SCANREPORT_RESTORE'); ?>
								</button>
								<button class="btn-new result-btn-set" type="button" >
									<i id="ic-change" class="text-warning glyphicon glyphicon-check"></i>
									<?php oLang::_('O_SCANREPORT_MARKASCLEAN'); ?>
								</button>
								<button class="btn-new result-btn-set" type="button" >
									<i id="ic-change" class="text-primary glyphicon glyphicon-alert"></i>
									<?php oLang::_('O_SCANREPORT_QUARANTINE'); ?>
								</button>
								<button class="btn-new result-btn-set" type="button" >
									<i id="ic-change" class="text-success glyphicon glyphicon-erase"></i>
									<?php oLang::_('O_SCANREPORT_CLEAN'); ?>
								</button>
							</div>
						</div>
					</div>
					<div id="da-fr-user" class="row col-sm-12" style="padding:0 20px;">
						<table class="table display" id="scanreportTable">
							<thead>
							<tr>
								<th><?php oLang::_('O_FILE_ID'); ?></th>
								<th><?php oLang::_('O_PATTERN_ID'); ?></th>
								<th><?php oLang::_('O_FILE_NAME'); ?></th>
								<th><?php oLang::_('O_CHECKSTATUS'); ?></th>
								<th><?php oLang::_('O_CONFIDENCE'); ?></th>
								<th><?php oLang::_('VIEW'); ?></th>
								<th><input id='checkbox' type='checkbox'></th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th><?php oLang::_('O_FILE_ID'); ?></th>
								<th><?php oLang::_('O_PATTERN_ID'); ?></th>
								<th><?php oLang::_('O_FILE_NAME'); ?></th>
								<th><?php oLang::_('O_CHECKSTATUS'); ?></th>
								<th><?php oLang::_('O_CONFIDENCE'); ?></th>
								<th><?php oLang::_('VIEW'); ?></th>
								<th></th>
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
	</div>
<?php 
	$this->model->showFooterJs();
}
?>