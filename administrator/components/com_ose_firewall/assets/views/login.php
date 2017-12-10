<?php
oseFirewall::checkSubscription ();
$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs ();
?>
<div id="oseappcontainer">
	<div class="container wrapbody">
	<?php
	$this->model->showLogo ();
//    $this->model->showHeader();
	?>
		<div class="col-sm-12">
	<div class="wrap-container col-sm-12" style="padding-bottom: 40px;">
		<div class="row row-set">
			<div class="col-sm-3 p-l-r-0">
				<div id="c-tag">
					<div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title">Centrora Member Login<span>
					</div>
					<p class="tag-content">You can login here with your Centrora Account or OSE Account to activate your premium services</p>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="col-sm-4">
					<div class="vs-line-1">
						<a href="https://www.centrora.com/services/hosting-services-pricing">
						<div id="fw-overview" class="vs-line-1-title fw-hover"> <i class="fa fa-shopping-cart"></i></div>
						<div class="vs-line-1-number">
							Subscribe To A Plan Now
						</div>
							</a>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="vs-line-1">
						<a href="https://www.centrora.com/services/hosting-services-pricing">
						<div id="fw-overview" class="vs-line-1-title fw-hover"> <i class="fa fa-info-circle"></i></div>
						<div class="vs-line-1-number">
							More about premium service
						</div>
						</a>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="vs-line-1">
						<a href="https://docs.centrora.com/en/latest/activate-premium.html">
						<div id="fw-overview" class="vs-line-1-title fw-hover"> <i class="fa fa-certificate"></i></div>
						<div class="vs-line-1-number">
							How to activate my premium service?
						</div>
						</a>
					</div>
				</div>

			</div>
			</div>
		<div class="row col-sm-12" style="padding-left: 20px;">
			<div class="col-sm-4 mypremium-steps"style="padding-left: 25px;">
				<a href='#step1 ' data-toggle="tab">Step 1 - Create an Account</a>
			</div>
			<div class="col-sm-4 mypremium-steps">
				<a href='#step2' data-toggle="tab">Step 2 - Place an order</a>
			</div>
			<div class="col-sm-4 mypremium-steps">
				<a href='#step3' data-toggle="tab">Step 3 - Activate the subscription</a>
			</div>

		</div>


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
            <div class="col-md-12" style="padding-left: 20px;">
					<div class="bs-component">
						<div class="panel panel-primary plain">
<!--							<div class="panel-heading"></div>-->
<!--							<div class="panel-controls-buttons">-->
<!--								 <button onclick="redirectTut('http://www.centrora.com/store/subscription-packages/');" type="button" class="btn btn-sm mr5 mb10"><i class="text-primary glyphicon glyphicon-shopping-cart"></i> Subscribe To A Plan Now</button>-->
<!--								 <button onclick="redirectTut('http://www.centrora.com/premium-service/');" type="button" class="btn btn-sm mr5 mb10"><i class="text-yellow glyphicon glyphicon-info-sign"></i> More about premium service</button>-->
<!--	                             <button class="btn btn-sm mr5 mb10" type="button" onClick="redirectTut('https://www.centrora.com/store/activating-premium-service');"><i class="text-danger glyphicon glyphicon-book"></i> --><?php //oLang::_('HOW_TO_ACTIVATE'); ?><!--</button>-->
<!--	                        </div>-->
	                        <div class ="row bg-transparent-white">
	                        	<div class='col-md-12'>
<!--							  		<ul class='nav nav-wizard'>-->
<!--									  <li class='active'><a href='#step1' data-toggle="tab">Step 1 - Create an Account</a></li>-->
<!--									  <li><a href='#step2' data-toggle="tab">Step 2 - Place an order</a></li>-->
<!--									  <li><a href='#step3' data-toggle="tab">Step 3 - Activate the subscription</a></li>-->
<!--									</ul>-->
									
									<div id="myTabContent" class="tab-content">
										<div class="tab-pane fade active in" id="step1">
											<div class="col-md-2">
												<img src="//dfsm9194vna0o.cloudfront.net/617220-0-moneybacklogo.png" alt="100% Satisfaction Guarantee">
											</div>
											<div class="col-md-10">
												<p style="padding-top:10px;">Simply create an account by using the form on the right hand side below, or if you have an account in Centrora already, simply sign in by using the form on the left hand side below.
												<br/>We offer 60 days 100% Satisfaction Guarantee, if you are not satisfied, we issue full refund to you without asking a question.</p>
											</div>
										</div>
										<div class="tab-pane fade " id="step2">
											<div class="col-md-2">
												<img src="//dfsm9194vna0o.cloudfront.net/617220-0-moneybacklogo.png" alt="100% Satisfaction Guarantee">
											</div>
											<div class="col-md-10">
											<p style="padding-top:10px;"><img src="<?php echo OSE_FWURL.'/public/images/subscribe_img.png';?>" ><br/>
											Next, click the subscribe button to place an order to a subscription plan. Once the order is placed, pay your subscription through Paypal or Credit Card. Once payments are made, you will see a subscription is active in the subscriptions table.</p>
											</div>
										</div>
										<div class="tab-pane fade " id="step3">
											<div class="col-md-2">
												<img src="//dfsm9194vna0o.cloudfront.net/617220-0-moneybacklogo.png" alt="100% Satisfaction Guarantee">
											</div>
											<div class="col-md-10">
												<p style="padding-top:10px;"><img src="<?php echo OSE_FWURL.'/public/images/subscribe_img2.png';?>" ><br/>
												Final step: click the link subscription button to activate the subscription for this website.</p>
											</div>	
										</div>
									</div>
								</div>

							</div>
							<div class="row  bg-transparent-white" style="padding-bottom: 10px;">
							 <div class="col-md-6 login-form">
								<div class="panel panel-primary">
								<div class="panel-heading">
									<p>
										If you have an account already, please enter your <code>Centrora</code>	Account Information
									</p>
								</div>
								<div class="panel-body">
								<form class="form-horizontal group-border stripped" role="form" id='login-form'>
									<div class="form-group">
										<label for="website" class="col-sm-4 control-label">Website</label>
										<div class="col-sm-8">
											<select class="form-control" name='website' id='website' >
												<option value="centrora">Centrora</option>
<!--												<option value="ose">Open Source Excellence [OSE]</option>-->
											</select>
										</div>
									</div>
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
                                    <?php
                                    if(oseFirewallBase::isSuite()) {
                                        ?>
                                        <div class="form-group">
                                            <label for="password" class="col-sm-4 control-label">Website Domain
                                                <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover" data-content="Please insert the domain of the suite installation account"></i>
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="textfield" class="form-control" id="domain" name="domain" placeholder="">
                                            </div>
                                        </div>
                                        <?php
                                    }
                                        ?>
									<div class="form-group">
										<div class="col-sm-12">
											<div class="pull-right">
												<button type="submit" class="btn-new result-btn-set "><i id="ic-change" class="text-primary glyphicon glyphicon-log-in"></i> Sign in</button>
											</div>
										</div>
									</div>
									<input type="hidden" name="option" value="com_ose_firewall"> 
									<input type="hidden" name="controller" value="login"> 
								    <input type="hidden" name="action" value="validate">
								    <input type="hidden" name="task" value="validate">
								    <?php echo $this->model->getToken();?>
								</form>
								</div>
							</div>
							</div>
							
							
							<div class="col-md-6 login-form">
								<div class="panel panel-primary">
								<div class="panel-heading">
									<p>
										If you don't have an account yet, please use the following form to create an account.
									</p>
								</div>
								<div class="panel-body">
								
								<form id = 'new-account-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('FIRSTNAME');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="firstname" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('LASTNAME');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="lastname" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('EMAIL');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="email" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('PASSWORD');?></label>
										<div class="col-sm-8">
				                               <input type="password" name="password" id="password" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('PASSWORD_CONFIRM');?></label>
										<div class="col-sm-8">
				                               <input type="password" name="password2" id="password2" value="" class="form-control">
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall"> 
										<input type="hidden" name="controller" value="login"> 
										<input type="hidden" name="action" value="createaccount">
										<input type="hidden" name="task" value="createaccount">
										<?php echo $this->model->getToken();?>
									<div class="form-group">
										<div class="col-sm-12">
											<div class="pull-right">
												<button type="submit" class="btn-new result-btn-set" id='save-button'><i id="ic-change" class="text-primary glyphicon glyphicon-user"></i> <?php oLang::_('CREATE');?></button>
											</div>
										</div>
									</div>
								</form>
								</div>
							  </div>
							</div>

						  </div>
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
	</div>
</div>

<?php
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>