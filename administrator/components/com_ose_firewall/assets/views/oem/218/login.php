<?php
oseFirewall::checkSubscription ();
$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs ();
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
    $this->model->showHeader();
	?>
	<div class="row">
			<?php 
	  			if (OSE_CMS =='joomla')
	  			{
	  				oseFirewall::callLibClass('audit', 'audit');
	  				$audit = new oseFirewallAudit ();
	  				$plugin = $audit->isPluginEnabled ('plugin', 'centrora', 'system');
	  				if (empty($plugin) || $plugin->enabled == false)
	  				{
	  					$action = (!empty($plugin))?'<button class="btn btn-danger btn-xs fx-button" onClick ="location.href=\'index.php?option=com_plugins&task=plugin.edit&extension_id='.$plugin->extension_id.'\'" >Fix It</button>':'';
	  				}
	  				if (!empty($action))
	  				{	
	  		?>
	  		<div class="col-md-12">
		  		<div class="alert alert-danger fade in">
	                 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	                 	<?php 
	                 		echo '<span class="label label-warning">Warning</span> '.oLang::_get('SYSTEM_PLUGIN_DISABLED').$action;
	                 	?>
	            </div>
            </div>
            <?php 
	  				}
	  			}
            ?>
            <div class="col-md-12">
					<div class="bs-component">
						<div class="panel panel-primary plain">
							<div class="panel-heading"></div>
							<div class="panel-controls-buttons">
								 <button onclick="redirectTut('http://webandwire.de/index.php/security/webandwire-pageprotect');" type="button" class="btn btn-sm mr5 mb10"><i class="text-primary glyphicon glyphicon-shopping-cart"></i> Subscribe To A Plan Now</button>
	                        </div>
							<div class="row mt20">
							 <div class="col-md-2"></div>
							 <div class="col-md-8 white-bg login-form">
								<div class="panel panel-primary">
								<div class="panel-heading">
									<p>
										<?php oLang::_('LOGIN');?>
									</p>
								</div>
								<div class="panel-body">
								<form class="form-horizontal group-border stripped" role="form" id='login-form'>
									<div class="form-group">
										<label for="username" class="col-sm-4 control-label">Email</label>
										<div class="col-sm-8">
											<input type="textfield" class="form-control" id="email"  name="email" placeholder="Email">
										</div>
									</div>
									<div class="form-group">
										<label for="password" class="col-sm-4 control-label">Password</label>
										<div class="col-sm-8">
											<input type="password" class="form-control" id="password" name="password" placeholder="Password">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12">
											<div class="pull-right">
												<button type="submit" class="btn"><i class="text-primary glyphicon glyphicon-log-in"></i> Sign in</button>
											</div>
										</div>
									</div>
									<input type="hidden" name="option" value="com_ose_firewall"> 
									<input type="hidden" name="controller" value="login"> 
								    <input type="hidden" name="action" value="validate">
								    <input type="hidden" name="task" value="validate">
								    <input type="hidden" id="website"  name="website" value="centrora">
								    <?php echo $this->model->getToken();?>
								</form>
								</div>
							</div>
							</div>
							
							<div class="col-md-1"></div>
						  </div>
						</div>
					</div>
			</div>
		</div>

	</div>
</div>
<div id='fb-root'></div>

<?php
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>