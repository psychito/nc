<?php
$this->model->getNounce ();
$activation_code = $this->model->getActivationCode();
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="row">
	  <div class="col-md-12">
				<div class="bs-component">
					<div class="panel panel-teal">
						<div class="panel-body">
							<div class="alert alert-info fade in">
								<div class="bg-primary alert-icon">
								     <i class="glyphicon glyphicon-info-sign s24"></i>
								</div>
								<b>Activation Codes:</b>
								<p class="text-default">
									<?php echo $activation_code; ?>
								</p>
							</div>
						</div>
					</div>		
				</div>
		</div>
	  </div>	
	</div>
</div>
<div id='fb-root'></div>

<!-- Form Modal -->
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('SCANPATH'); ?></h4>
			</div>
			<div class="modal-body" style="height:400px">
				<label style="vertical-align: top;"><?php oLang::_('FILETREENAVIGATOR'); ?></label>

				<div class="panel-body" id="FileTreeDisplay"></div>
			</div>
			<div class="modal-footer">
				<div class="panel-body">
					<form id='scan-form' class="form-horizontal group-border stripped" role="form">
						<div class="form-group">
							<label for="scanPath" class="col-sm-1 control-label"><?php oLang::_('PATH'); ?></label>

							<div class="col-sm-11">
								<input type="text" name="scanPath" id="selected_file" class="form-control">
							</div>
						</div>
						<input type="hidden" name="option" value="com_ose_firewall">
						<input type="hidden" name="controller" value="activation">
						<input type="hidden" name="action" value="activate">
						<input type="hidden" name="task" value="activate">

						<div class="form-group">
							<div>
								<button type="submit" class="btn btn-sm" id='save-button'><i
											class="glyphicon glyphicon-screenshot"></i> <?php oLang::_('ACTIVATE_SPECIFIC_DOMAIN'); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- /.modal -->

<?php
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>

