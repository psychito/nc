<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus ( false );

$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs ();

$vscansettings = $this->model->getCronSettings (3);
$gitbackupsettings = $this->model->getCronSettings (4);
//get local settings to enable or disable email
$localSettings = $this->model->getCronSettingsLocal(4);
if(empty($localSettings))
{
    $localSettings->recieveEmail = 0;
}

if (class_exists('SConfig')){
    $confArray = $this->model->getConfiguration('vsscan');
}
if ($status == true) {
	?>
<div id="oseappcontainer">
	<div class="container wrapbody">
	<?php
	$this->model->showLogo ();
//	$this->model->showHeader ();
	?>
	<div class="row">
			<div class="col-md-12">
				<div class="bs-component">
					<div class="panel-body panelRefresh wrap-container">
					 <div class="row row-set">
					   <div class="col-sm-3 p-l-r-0">
                             <div id="c-tag">
                                 <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title"><?php oLang::_('CRONJOBS_TITLE'); ?><span>
                                 </div>
                                 <p class="tag-content"><?php oLang::_('CRONJOBS_DESC'); ?></p>
                             </div>
                         </div>
                          <div class="col-sm-9">
                          <div class="col-sm-4">
                             <div class="vs-line-1">
                             <a data-toggle="tab" href="#vscannercron">
                                 <div id="fw-overview" class="vs-line-1-title fw-hover"> <i class="fa fa-calendar"></i></div>
                                 <div class="vs-line-1-number">
                                   <?php oLang::_('SCHEDULE_SCANNING'); ?>
                                 </div>
                              </a>
                             </div>
                         </div>

                          <div class="col-sm-4">
                             <div class="vs-line-1">
                             <a data-toggle="tab" href="#gitbackupcron" onclick="checkCanRunGitBackup();">
                                 <div id="fw-overview" class="vs-line-1-title fw-hover"> <i class="fa fa fa-save"></i></div>
                                 <div class="vs-line-1-number">
                                  <?php oLang::_('SCHEDULE_GITBACKUP'); ?>
                                 </div>
                              </a>
                             </div>
                         </div>

                          </div>
					 </div>
                    <div class="tab-content">
							<div class="tab-pane active" id="vscannercron">
								<div class="row row-set" style="padding-right: 15px;">
                                 <div class="title-bar" style="background:rgba(76, 53, 90, 0.6) !important;"><?php oLang::_('CRONJOBS_LONG'); ?></div>
                                </div>

								<form id='cronjobs-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<div class="col-md-6">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<h3 class="panel-title"><?php oLang::_('HOURS'); ?>
                                                        <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover" data-content="<?php oLang::_('HOURS_HELP');?>"></i>
													</h3>
												</div>
												<div class="panel-body bg-transparent-white">
													<div class="row">
														<div class="col-xs-8">
															<select class="form-control" id="vscancusthours" name="custhours" size="1"></select>
														</div>
														<label id="vscanusertime"></label> <input id="vscansvrusertime" style="display: none" value="<?php echo $vscansettings['hour'] ?>">
													</div>
												</div>
											</div>
											<div class="panel panel-primary">
												<div class="panel-heading">
													<h3 class="panel-title"><?php oLang::_('WEEKDAYS'); ?></h3>
												</div>
												<div id="panel-weekdays" class="panel-body bg-transparent-white">
													<select class="form-control" name="custweekdays[]" size="7" multiple="" id="vscanweekdays">
														<option value="0" <?php echo ($vscansettings[0] == true)?" selected ":""; ?>><?php oLang::_('SUN'); ?></option>
														<option value="1" <?php echo ($vscansettings[1] == true)?" selected ":""; ?>><?php oLang::_('MON'); ?></option>
														<option value="2" <?php echo ($vscansettings[2] == true)?" selected ":""; ?>><?php oLang::_('TUE'); ?></option>
														<option value="3" <?php echo ($vscansettings[3] == true)?" selected ":""; ?>><?php oLang::_('WED'); ?></option>
														<option value="4" <?php echo ($vscansettings[4] == true)?" selected ":""; ?>><?php oLang::_('THU'); ?></option>
														<option value="5" <?php echo ($vscansettings[5] == true)?" selected ":""; ?>><?php oLang::_('FRI'); ?></option>
														<option value="6" <?php echo ($vscansettings[6] == true)?" selected ":""; ?>><?php oLang::_('SAT'); ?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">

													<p class="unique-set-sct"><small><i class="glyphicon glyphicon-warning-sign"></i> <?php oLang::_('SAVE_SETTING_DESC'); ?></small></p>

											</div>

											<div class="row">
												<div class="form-group bg-transparent-white">
													<div class="col-xs-6"><?php oLang::_('SCHEDULE_SCANNING'); ?>:</div>
													<div class="col-xs-6">
														<div class="onoffswitch">
															<input type="checkbox" class="onoffswitch-checkbox" <?php echo ($vscansettings ['enabled'] == 1 && isset ( $vscansettings ['enabled'] )) ? " checked " : "";?> id="vscanonoffswitch">
															<label class="onoffswitch-label" for="vscanonoffswitch">
                                                                <span class="onoffswitch-inner"></span>
                                                                <span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</div>
											</div>

											<div class="col-xs-12 form-group row">
                                                <?php if (class_exists('SConfig')){?>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button data-target="#scanPathModal" data-toggle="modal" type="button" id="setscanpath" class='btn btn-sm mr5 mb10'><i class="glyphicon glyphicon-folder-close text-primary"></i> <?php oLang::_('SETSCANPATH'); ?></button>
                                                    </span>
                                                    <input type="text" id="selected_file2" class="form-control" disabled value="<?php echo $confArray['data']['scanPath']?>">
                                                </div>
                                                <?php }?>
												<div class="pull-right">
													<button class="btn-new result-btn-set" style="margin-right: -15px;" type="submit"><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE_SETTINGS'); ?></button>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="option" value="com_ose_firewall">
									<input type="hidden" name="controller" value="cronjobs">
									<input type="hidden" name="action" value="saveCronConfig">
									<input type="hidden" name="task" value="saveCronConfig">\
									<input type="hidden" name="schedule_type" value="3">
									<input type="hidden" name="cloudbackuptype" value="1">
									<input type="hidden" name="enabled" value="<?php echo ($vscansettings ['enabled'] == 1 && isset ( $vscansettings ['enabled'] )) ? 1 : 0;?>" id="vscanenabled">
									<!--also set in js for myonoffswitch-->
								</form>
								<!--Set Scan Path Modal-->
                                <div class="modal fade" id="scanPathModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE'); ?></span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCANPATH'); ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <label style="vertical-align: top;"><?php oLang::_('FILETREENAVIGATOR'); ?></label>
                                                <div class="panel-body" id="FileTreeDisplay"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="panel-body">
                                                    <form id = 'setscanpath-form' class="form-horizontal group-border stripped" role="form">
                                                        <div class="form-group">
                                                            <label for="scanPath" class="col-sm-1 control-label"><?php oLang::_('PATH');?></label>
                                                            <div class="col-sm-11">
                                                                <input type="text" name="scanPath" id="selected_file" class="form-control">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="option" value="com_ose_firewall">
                                                        <input type="hidden" name="controller" value="cronjobs">
                                                        <input type="hidden" name="action" value="saveScanPath">
                                                        <input type="hidden" name="task" value="saveScanPath">
                                                        <input type="hidden" name="type" value="vsscan">
                                                        <div class="form-group">
                                                            <div>
                                                                <button type="submit" class="btn btn-sm" id='save-button'><i class="glyphicon glyphicon-save text-success"></i> <?php oLang::_('SAVE');?></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>



<!--panel for the gitbackup cron setting starts -->
							<div class="tab-pane" id="gitbackupcron">
								<div class="row row-set" style="padding-right: 15px;">
                                 <div class="title-bar" style="background:rgba(76, 53, 90, 0.6) !important;"><?php oLang::_('O_GITBACKUP_CRON_INFO'); ?></div>
                                </div>
								<form id='gitbackup-cronjobs-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<div class="col-md-4">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<h3 class="panel-title"><?php oLang::_('HOURS'); ?>
                                                    <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover" data-content="<?php oLang::_('HOURS_HELP');?>"></i>
													</h3>
												</div>
												<div class="panel-body bg-transparent-white">
													<div class="row">
														<div class="col-xs-8">
															<select class="form-control" id="gitbackupcusthours" name="custhours" size="1"></select>
														</div>
														<label id="gitbackupusertime"></label> <input id="gitbackupsvrusertime" style="display: none" value="<?php echo $gitbackupsettings['hour'] ?>">
													</div>
												</div>
											</div>

											<div class="panel panel-primary">
												<div class="panel-heading">
													<h3 class="panel-title"><?php oLang::_('O_BACKUP_DAYS'); ?></h3>
												</div>
												<div id="panel-weekdays" class="panel-body bg-transparent-white">
													<select class="form-control" name="custweekdays[]" size="7" multiple="" id="gitbackupweekdays">
														<option value="0" <?php echo ($gitbackupsettings[0] == true)?" selected ":""; ?>>Sunday</option>
														<option value="1" <?php echo ($gitbackupsettings[1] == true)?" selected ":""; ?>>Monday</option>
														<option value="2" <?php echo ($gitbackupsettings[2] == true)?" selected ":""; ?>>Tuesday</option>
														<option value="3" <?php echo ($gitbackupsettings[3] == true)?" selected ":""; ?>>Wednesday</option>
														<option value="4" <?php echo ($gitbackupsettings[4] == true)?" selected ":""; ?>>Thursday</option>
														<option value="5" <?php echo ($gitbackupsettings[5] == true)?" selected ":""; ?>>Friday</option>
														<option value="6" <?php echo ($gitbackupsettings[6] == true)?" selected ":""; ?>>Saturday</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<h3 class="panel-title"><?php oLang::_('O_CRON_GITBACKUP_TYPE_DESC'); ?></h3>
												</div>
												<div class="panel-body bg-transparent-white">
													<div class="row">
														<div class="col-xs-8">
															<select class="form-control" id="gitcloudbackuptype" name="cloudbackuptype" size="1">
                                                            </select>
														</div>
														<label id="cloudbackupicon"></label>
														<div>
															<label style="color:#565656; font-size: 13px !important; font-weight: 500 !important;"><?php oLang::_('O_BACKUP_TYPE_HELP'); ?></label>
														</div>
													</div>
												</div>
											</div>
											<div class="row">

													<p class="unique-set-sct"><small><i class="glyphicon glyphicon-warning-sign"></i> <?php oLang::_('SAVE_SETTING_DESC'); ?></small></p>
											</div>
											<div class="row">
												<div class="form-group  bg-transparent-white">
													<div class="col-xs-6"><?php oLang::_('SCHEDULE_BACKUP'); ?>:</div>
													<div class="col-xs-6">
														<div class="onoffswitch">
															<input type="checkbox" class="onoffswitch-checkbox" <?php echo (isset ( $gitbackupsettings ['enabled'] ) && $gitbackupsettings ['enabled'] == 1 ) ? " checked " : "";	?>
																id="gitbackuponoffswitch"
															> <label class="onoffswitch-label" for="gitbackuponoffswitch"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-12 form-group">
													<div class="pull-right">
														<button class="btn-new result-btn-set " type="submit"><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE_SETTINGS'); ?></button>
													</div>
												</div>
											</div>
										</div>
                                        <div class="col-md-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><?php oLang::_('O_RECEIVE_EMAIL_INFO'); ?></h3>
                                                </div>
                                                <div class="panel-body  bg-transparent-white">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <select class="form-control" id="receiveemail"
                                                                        name="git_receiveemail" size="0.3">
                                                                    <?php
                                                                    $this->model->getReceiveEmailMenu($localSettings->recieveEmail);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
									<input type="hidden" name="option" value="com_ose_firewall"> <input type="hidden" name="controller" value="cronjobs"> <input type="hidden" name="action" value="saveCronConfig"> <input
										type="hidden" name="task" value="saveCronConfig"
									> <input type="hidden" name="schedule_type" value="4"> <input type="hidden" name="enabled"
										value="<?php

	echo ($gitbackupsettings ['enabled'] == 1 && isset ( $gitbackupsettings ['enabled'] )) ? 1 : 0;
	?>" id="gitbackupenabled"
									>
									<!--also set in js for myonoffswitch-->
								</form>
                            </div>
                            <div id='fb-root'></div>
<!--panel for the gitbackup cron ends -->

<?php
} else {
	?>
<div id="oseappcontainer">
        <div class="container">
            <?php
            $this->model->showLogo();
            ?>
             <div id="sub-header" class="row"
                 style="background:url('<?php echo'http://www.googledrive.com/host/0B4Hl9YHknTZ4X2sxNTEzNTBJUlE/sub_hd_bg.png' ?>') top center;  min-height:500px;">
                <div class="col-md-6" id="unsub-left">
                    <?php $this->model->showSubHeader(); ?>
                    <?php echo $this->model->getBriefDescription(); ?>
                </div>
                <div class="col-md-6" id="unsub-right">
                    <a href="https://www.centrora.com/services" id="leavetous">leave the work to us now</a>
                </div>
            </div>
            <div class="row">
                <div id="unsub-lower">
                    <?php
                    include_once dirname(__FILE__) . '/calltoaction.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
	$this->model->showFooterJs ();
}
?>