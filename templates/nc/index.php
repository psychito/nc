<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/** @var JDocumentHtml $this */
$app  = JFactory::getApplication();
$user = JFactory::getUser();
$lang = JFactory::getLanguage();
$sitename = $app->get('sitename');
// Output as HTML5
$this->setHtml5(true);
JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));
JHtml::_('stylesheet', 'nctema.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', '//fonts.googleapis.com/css?family=Lato:300,400,900|Roboto+Condensed:400,700');
$this->addStyleDeclaration("h1, h2, h3, h4, h5, h6, .site-title {	font-family: Lato, sans-serif;
}");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<jdoc:include type="head" />
</head>
<body  data-spy="scroll" data-target=".navbar" data-offset="50">

<div class="completo">
	<div class="anchototal container-fluid">
	<header>
		<div class="row-fluid topper padders">
			<div class="span1 logo">
				<a class="brand pull-left" href="<?php echo $this->baseurl; ?>/">
				   <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo.png" alt="NC"/>
			  </a>
			</div>
			<div class="span11 menu">
				<nav class="navigation" role="navigation">
					<div class="">
						<jdoc:include type="modules" name="nc-menu" style="none" />
					</div>
				</nav>
			</div>
		</div>
	</header>
		<?php $menu = $app->getMenu();
			if ($menu->getActive() == $menu->getDefault($lang->getTag())) {?>
				<section class="frontcont">
					<div class="row-fluid photoslider">
						<div class="span12 phslider">
							<jdoc:include type="modules" name="nc-photoslider" style="none" />
						</div>
					</div>
					<div class="row-fluid quoteslider">
						<div class="span12 quslider">
							<jdoc:include type="modules" name="nc-quoteslider" style="none" />
						</div>
					</div>
					<div class="row-fluid conten">
						<div class="span12 contenido">
							<jdoc:include type="component" />
						</div>
					</div>
				</section>
			<?php }else{ ?>
				<section class="secondcont padders">
					<div class="row-fluid conten">
						<div class="span12 contenido">
							<jdoc:include type="component" />
						</div>
					</div>
				</section>
			<?php } ?>
			<footer>
			<div class="row-fluid  footcont padders">
				<div class="span5 foot1">
					<jdoc:include type="modules" name="nc-foot1" style="none" />
				</div>
				<div class="span5 foot2">
					<jdoc:include type="modules" name="nc-foot2" style="none" />
				</div>
				<div class="span2 foot3">
					<jdoc:include type="modules" name="nc-foot3" style="none" />
				</div>
			</div>
			<p class="pull-right">
				<a href="#top" id="back-top">
					<?php echo JText::_('TPL_NC_BACKTOTOP'); ?>
				</a>
			</p>
			<p>
				&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
			</p>
		</footer>
		<jdoc:include type="modules" name="debug" style="none" />
	</div>
</div>


</body>
</html>
