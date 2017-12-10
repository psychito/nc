<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
oseFirewall::checkDBReady ();
$this->model->getNounce ();
?>
<div id="oseappcontainer">
	<div class="container wrapbody">
	<?php
	$this->model->showLogo ();
//	$this->model->showHeader ();
	?>

		<!-- Import Form Modal -->
		<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_IMPORT_IP_CSV'); ?></h4>
					</div>
					<div class="modal-body">
						<form id = 'import-ip-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">
							<div class="col-lg-9 col-md-9">
								<input id="csvfile" type="file" name="csvfile" >
							</div>
							<div class="col-lg-3 col-md-3">
								<button type="submit" class="btn btn-primary btn-sm" id='import-ip-button'><i class="glyphicon glyphicon-import"></i> <?php oLang::_('O_IMPORT_NOW');?></button>
							</div>
							<input type="hidden" name="option" value="com_ose_firewall">
							<input type="hidden" name="controller" value="manageips">
							<input type="hidden" name="action" value="importcsv">
							<input type="hidden" name="task" value="importcsv">
							<input type="hidden" name="centnounceForm" id="centnounceForm" value="">
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<!-- /.modal -->

		<!-- Export Form Modal -->
		<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_EXPORT_IP_CSV'); ?></h4>
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
								<div class="panel panel-primary plain toggle panelClose panelRefresh">
									<!-- Start .panel -->
									<div class="panel-controls"></div>
									<div class="col-md-12 panel-body wrap-container">
										<div class="row row-set">
											<div class="col-sm-3 p-l-r-0">
												<div id="c-tag" style="height:130px;">
													<div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title" style="height: 130px;"><?php oLang::_('MANAGE_IPS'); ?><span>
													</div>
													<p class="tag-content"><?php oLang::_('MANAGEIPS_DESC'); ?></p>
												</div>
											</div>
										</div>

										<div class="row">
											<div id="sticky-anchor"></div>
                                            <div id = "pageTitle" class="title-bar">Details about all the detected attacks</div>
                                            <div id="report-btngroup" class="col-md-12" style="margin-top: 0px;padding: 0 20px;">
												<div class="clean-buttons">
                                                    <?php
                                                    $isIPV6Enabled = $this->model->isIPv6Enabled ();
                                                    if ($isIPV6Enabled == false) {
                                                        ?>
                                                        <button id="enablev6" class="btn-new result-btn-set" type="button"
                                                                 onClick="enableIPV6()">
                                                            <i id="ic-change" class="text-danger glyphicon glyphicon-ok"></i>
                                                            <?php oLang::_('O_ENABLED_IPV6'); ?>
                                                        </button>



                                                        <?php
                                                    }
                                                    ?>
													<button data-target="#addIPModal" data-toggle="modal" class="btn-new result-btn-set"><i id="ic-change" class="text-primary glyphicon glyphicon-plus-sign"></i> <?php oLang::_('ADD_IPS'); ?>
													</button>
													<button id="delete-button" class="btn-new result-btn-set" type="button" onClick="changeBatchItemStatus('blacklistIP')">
														<i id="ic-change" class="text-danger glyphicon glyphicon-minus-sign"></i>
														<?php oLang::_('O_BLACKLIST_IP'); ?>
													</button>
													<button class="btn-new result-btn-set" type="button" onClick="changeBatchItemStatus('whitelistIP')">
														<i id="ic-change" class="text-success glyphicon glyphicon-ok-sign"></i>
														<?php oLang::_('O_WHITELIST_IP'); ?>
													</button>
													<button class="btn-new result-btn-set" type="button" onClick="changeBatchItemStatus('monitorIP')">
														<i id="ic-change" class="text-warning glyphicon glyphicon-eye-open"></i>
														<?php oLang::_('O_MONITORLIST_IP'); ?>
													</button>
													<button class="btn-new result-btn-set" type="button" onClick="removeItems()">
														<i id="ic-change" class="text-primary glyphicon glyphicon-remove-sign"></i>
														<?php oLang::_('O_DELETE_ITEMS'); ?>
													</button>
													<button class="btn-new result-btn-set" type="button" onClick="changeBatchItemStatus('updateHost')">
														<i id="ic-change" class="text-primary glyphicon glyphicon-refresh"></i>
														<?php oLang::_('O_UPDATE_HOST'); ?>
													</button>
													<button data-target="#importModal" data-toggle="modal" class="btn-new result-btn-set" type="button">
														<i id="ic-change" class="text-success glyphicon glyphicon-import"></i>
														<?php oLang::_('O_IMPORT_IP_CSV'); ?>
													</button>
													<button data-target="#exportModal" data-toggle="modal" class="btn-new result-btn-set" type="button">
														<i id="ic-change" class="text-success glyphicon glyphicon-export"></i>
														<?php oLang::_('O_EXPORT_IP_CSV'); ?>
													</button>
													<button class="btn-new result-btn-set" type="button" onClick="removeAllItems()">
														<i id="ic-change" class="text-success glyphicon glyphicon-erase"></i>
														<?php oLang::_('O_DELETE__ALLITEMS'); ?>
													</button>
												</div>
											</div>

										</div>
										<div class="row col-sm-12" style="padding:0 20px;">
											<table class="table display" id="manageIPsTable">
												<thead>
                                                <tr>
                                                    <th></th>
                                                    <th><?php oLang::_('O_ID'); ?></th>
                                                    <th><?php oLang::_('O_DATE'); ?></th>
                                                    <th><?php oLang::_('O_IP_RULE_TITLE'); ?></th>
                                                    <th><?php oLang::_('O_RISK_SCORE'); ?></th>
                                                    <th><?php oLang::_('O_START_IP'); ?></th>
                                                    <th><?php oLang::_('O_STATUS'); ?></th>
                                                    <th><?php oLang::_('O_VISITS'); ?></th>
                                                    <th><?php oLang::_('O_VIEWDETAIL'); ?></th>
                                                    <th><input type="checkbox" name="checkedAll" id="checkedAll"></th>
                                                </tr>
												</thead>
												<tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th><?php oLang::_('O_ID'); ?></th>
                                                    <th><?php oLang::_('O_DATE'); ?></th>
                                                    <th><?php oLang::_('O_IP_RULE_TITLE'); ?></th>
                                                    <th><?php oLang::_('O_RISK_SCORE'); ?></th>
                                                    <th><?php oLang::_('O_START_IP'); ?></th>
                                                    <th><?php oLang::_('O_STATUS'); ?></th>
                                                    <th><?php oLang::_('O_VISITS'); ?></th>
                                                    <th><?php oLang::_('O_VIEWDETAIL'); ?></th>
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
	</div>


	<!-- AddIP Form Modal -->
                <div class="modal fade" id="addIPModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_IPS'); ?></h4>
                            </div>
                            <div class="modal-body">
                              	<p class="mb15">
									<?php oLang::_('IPFORM_DESC'); ?>
								</p>
								<form id = 'add-ip-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="title" class="col-sm-3 control-label"><?php oLang::_('O_IP_RULE');?></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="title" name="title" placeholder="<?php oLang::_('O_IP_RULE');?>">
										</div>
									</div>
									<div class="form-group">
										<label for="ip_type" class="col-sm-3 control-label"><?php oLang::_('O_IP_TYPE');?></label>
										<div class="col-sm-9">
				                                <label class="radio-inline">
                                                    <input id="single_ip" type="radio" name="ip_type" value="ip"
                                                           onchange="changeView()"
                                                           checked="checked"><?php oLang::_('O_SINGLE_IP'); ?>
				                                </label>
				                                <label class="radio-inline">
                                                    <input id="range_ip" type="radio" name="ip_type"
                                                           onchange="changeView()"
                                                           value="ips"><?php oLang::_('O_RANGE'); ?>
				                                </label>
										</div>
									</div>
									<div class="form-group">
										<label for="ip_start" class="col-sm-3 control-label"><?php oLang::_('O_START_IP');?></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="ip_start" name="ip_start">
										</div>
									</div>
                                    <div id="hidden_ip_end" class="form-group" style="display: none">
                                        <label for="ip_end" class="col-sm-3 control-label"><?php oLang::_('O_END_IP'); ?></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="ip_end" name="ip_end">
										</div>
									</div>
									<div class="form-group">
										<label for="ip_status" class="col-sm-3 control-label"><?php oLang::_('O_IP_TYPE');?></label>
										<div class="col-sm-9">
				                                <label class="radio-inline">
				                                     <input type="radio" name="ip_status" value="1" checked="checked"><?php oLang::_('O_STATUS_BLACKLIST_DESC');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="ip_status" value="2" ><?php oLang::_('O_STATUS_MONITORED_DESC');?>
				                                </label>
				                                <label class="radio-inline">
				                                     <input type="radio" name="ip_status" value="3" ><?php oLang::_('O_STATUS_WHITELIST_DESC');?>
				                                </label>            
										</div>
									</div>
									 	<input type="hidden" name="option" value="com_ose_firewall"> 
									 	<input type="hidden" name="controller" value="manageips"> 
									    <input type="hidden" name="action" value="addips">
									    <input type="hidden" name="task" value="addips">
				    				<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn" id='save-button'><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE');?></button>
										</div>
									</div>
								</form>
                              </div>
                        </div>
                    </div>
                </div>
	<!-- /.modal -->

	</div>
</div>
