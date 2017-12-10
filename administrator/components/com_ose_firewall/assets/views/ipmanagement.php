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
		?>
		<!-- Import Form Modal -->
		<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
						</button>
						<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_IMPORT_IP_CSV'); ?></h4>
					</div>
					<div class="modal-body">
						<form id = 'import-ip-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">
							<div class="col-lg-9 col-md-9">
								<input id="csvfile" type="file" name="csvfile" >
							</div>
							<div>
								<button type="submit" class="btn-new" id='import-ip-button'><i class="glyphicon glyphicon-import"></i> <?php oLang::_('O_IMPORT_NOW');?></button>
							</div>
							<input type="hidden" name="option" value="com_ose_firewall">
							<input type="hidden" name="controller" value="IpManagement">
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
							<span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
						</button>
						<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_EXPORT_IP_CSV'); ?></h4>
					</div>
					<div class="modal-body">
						<div class="col-sm-12">
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

		<!-- AddIP Form Modal -->
		<div class="modal fade" id="addIPModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
						</button>
						<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_IPS'); ?></h4>
					</div>
					<div class="modal-body">
						<p class="mb15">
							<?php oLang::_('IPFORM_FWS7_DESC'); ?>
						</p>
						<form id = 'add-ip-form' class="form-horizontal group-border stripped" role="form">

                            <div class="form-group">
								<label for="ip_start" class="col-sm-3 control-label"><?php oLang::_('O_IP');?></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="ip_start" name="ip_start">
								</div>
							</div>

							<div class="form-group">
								<label for="ip_status" class="col-sm-3 control-label"><?php oLang::_('O_IP_TYPE');?></label>
								<div class="col-sm-9">
									<label class="radio-inline">
										<input type="radio" name="ip_status" value="2" checked="checked"><?php oLang::_('O_STATUS_BLACKLIST_DESC');?>
									</label>
									<label class="radio-inline">
										<input type="radio" name="ip_status" value="0" ><?php oLang::_('O_STATUS_MONITORED_DESC');?>
									</label>
									<label class="radio-inline">
										<input type="radio" name="ip_status" value="1" ><?php oLang::_('O_STATUS_WHITELIST_DESC');?>
									</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div id ="ip_duration_details">
                                    <label for="ip_duration" class="col-sm-3 control-label"><?php oLang::_('O_FOR');?></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="duration" name ="duration">
                                                <option value="0" selected="selected">Always</option>
                                                <option value="1">1 Hour</option>
                                                <option value="2">2 Hour</option>
                                                <option value="3">3 Hour</option>
                                                <option value="6">6 Hour</option>
                                                <option value="12">12 Hour</option>
                                                <option value="24">1 Day</option>
                                            </select>
                                        </div>
                                    </div>
                            </div>
							<input type="hidden" name="option" value="com_ose_firewall">
							<input type="hidden" name="controller" value="IpManagement">
							<input type="hidden" name="action" value="addIp">
							<input type="hidden" name="task" value="addIp">
							<div class="form-group">
								<div>
									<button type="submit" class="btn-new" id='save-button'><i class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE');?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /.modal -->

		<div class="content-inner">
			<div class="row">
				<div class="col-sm-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary plain">
						<!-- Start .panel -->
						<div class="panel-body wrap-container">
							<div class="row row-set">
								<div class="col-sm-3 p-l-r-0">
									<div id="c-tag">
										<div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title"><?php oLang::_('MENU_IP_MANAGEMENT');?></span>
										</div>
										<p class="tag-content"><?php oLang::_('MENU_IP_MANAGEMENT_DESC');?></p>
									</div>
								</div>
								<div class="col-sm-9">
									<div class="col-sm-11">
										<div class="vs-line-1">
											<div id="fw-overview" class="vs-line-1-title fw-hover">
												<?php if(OSE_CMS =='wordpress'){ ?>
													<a href="admin.php?page=ose_fw_bsconfigv7" style="color:white;"><i class="fa fa-fire"></i></a>
												<?php }else { ?>
													<a href="?option=com_ose_firewall&view=bsconfigv7" style="color:white;"> <i class="fa fa-fire"></i> </a>
												<?php }?>

											</div>
											<div class="vs-line-1-number">
												<?php oLang::_('BACK_TO_ADVANCE_SETTING');?>
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="row row-set" style="padding-right: 20px;">
								<div class="title-bar"><?php oLang::_('MANAGE_IP');?></div>
								<div class="panel-controls"></div>
								<div class="panel-controls-buttons" style="float:left;">
									<button data-target="#addIPModal" data-toggle="modal" class="btn-new result-btn-set ipmanage-btn-set " type="button"><i class="text-primary glyphicon glyphicon-plus-sign"></i> <?php oLang::_('ADD_IPS'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set"  type="button" onClick="changeBatchItemStatus('blacklistIp')"><i class="text-block glyphicon glyphicon-minus-sign"></i> <?php oLang::_('O_BLACKLIST_IP'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set" type="button" onClick="changeBatchItemStatus('whitelistIp')"><i class="text-success glyphicon glyphicon-ok-sign"></i> <?php oLang::_('O_WHITELIST_IP'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set" type="button" onClick="changeBatchItemStatus('monitorIp')"><i class="glyphicon glyphicon-eye-open"></i> <?php oLang::_('O_MONITORLIST_IP'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set text-danger" type="button" onClick="removeItem('0')"><i class="glyphicon glyphicon-remove-sign"></i> <?php oLang::_('O_DELETE_ITEMS'); ?></button>
									<button data-target="#importModal" data-toggle="modal" class="btn-new result-btn-set ipmanage-btn-set"><i class="text-primary glyphicon glyphicon-import"></i> <?php oLang::_('O_IMPORT_IP_CSV'); ?></button>
									<button data-target="#exportModal" data-toggle="modal" class="btn-new result-btn-set ipmanage-btn-set"><i class="text-primary glyphicon glyphicon-export"></i> <?php oLang::_('O_EXPORT_IP_CSV'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set" type="button" onClick="removeItem('1')"><i class="glyphicon glyphicon glyphicon-erase"></i> <?php oLang::_('O_DELETE__ALLITEMS'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set" type="button" onClick="syncips_confirm()"><i class="glyphicon glyphicon glyphicon-refresh"></i> <?php oLang::_('O_SYNC_IPS_FROMV6'); ?></button>
									<button class="btn-new result-btn-set ipmanage-btn-set" type="button" onClick="getTempWhiteListedIps()"><i class="glyphicon glyphicon glyphicon-th-list"></i><?php oLang::_('O_GET_WHITELIST_IPS'); ?></button>
								</div>
							</div>
							<div class="row row-set" style="padding-right: 20px;">
									<table class="table display" id="manageIPsTable">
										<thead>
										<tr>
											<th><?php oLang::_('O_ID'); ?></th>
											<th><?php oLang::_('O_IP'); ?></th>
											<th><?php oLang::_('O_STATUS'); ?></th>
											<th><?php oLang::_('O_DATETIME'); ?></th>
											<!--									<th><input type="checkbox" name="checkedAll" id="checkedAll"></th>-->
										</tr>
										</thead>
										<tfoot>
										<tr>
											<th><?php oLang::_('O_ID'); ?></th>
											<th><?php oLang::_('O_IP'); ?></th>
											<th><?php oLang::_('O_STATUS'); ?></th>
											<th><?php oLang::_('O_DATETIME'); ?></th>
											<!--									<th><input type="checkbox" name="checkedAll" id="checkedAll"></th>-->
										</tr>
										</tfoot>
									</table>
							</div>
						</div>



					</div>
					<!-- End .panel -->
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
</div>
