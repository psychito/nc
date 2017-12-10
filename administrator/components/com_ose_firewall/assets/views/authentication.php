<?php
oseFirewall::checkDBReady();
$status = oseFirewall::checkSubscriptionStatus(false);
$this->model->getNounce();
if ($status == true) {
?>
<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php
        $this->model->showLogo();
//        $this->model->showHeader();
        ?>
        <div class="content-inner">
            <div class="row ">
                <div class="col-lg-12 sortable-layout">
                    <!-- col-lg-12 start here -->
                    <div class="panel panel-primary plain">
                        <!-- Start .panel -->

                        <div class="panel-body wrap-container">
                            <div class="row row-set">
                         <div class="col-sm-3 p-l-r-0">
                             <div id="c-tag">
                                 <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title">Third Party Authentication<span>
                                 </div>
                                 <p class="tag-content">To enable cloud backup please authorise Centrora Security™ to your prefered cloud service.</p>
                             </div>
                         </div>
                         <div class="col-sm-9">
                         </div>
                         </div>
                             <div class="row row-set" style="padding: 0px 20px;">
                            <div class="title-bar" style="background:rgba(76, 53, 90, 0.6) !important;">Select one type below to run.</div>
                            </div>
                         <div class="row row-set" style="padding-left: 30px; margin-top: 20px;">

                             <div class="col-xs-2">
                                 <?php
                                 $dropboxflag = $this->model->dropBoxVerify();
                                 if ($dropboxflag) { ?>
                                     <button id="onedriveLogout" class="btn btn-default col-xs-12"
                                             onclick="dropbox_logout()"><i class="fa fa-dropbox"></i>&nbsp;<?php oLang::_('O_DROPBOX_LOGOUT'); ?></button>
                                 <?php } else { ?>
                                     <button id="dropbox_authorize" class="btn-primary btn col-xs-12"
                                             onclick="initial_dropboxauth()"><i class="fa fa-dropbox"></i>&nbsp;<?php oLang::_('O_AUTHENTICATION_DROPBOX'); ?></button>
                                 <?php } ?>

                             </div>
                             <div class="col-xs-2">
                                 <?php
                                 if ($flag = $this->model->oneDriveVerify()) {
                                     ?>
                                     <button id="onedriveLogout" class="btn btn-default col-xs-12"
                                             onclick="onedrive_logout()"><i class="fa fa-windows"></i>&nbsp;<?php oLang::_('O_ONEDRIVE_LOGOUT'); ?></button>

                                 <?php } elseif (!empty($_GET['code'])) {
                                     $this->model->oauthOneDrive();
                                     ?>
                                     <button id="onedriveLogout" class="btn btn-default col-xs-12"
                                             onclick="onedrive_logout()"><i class="fa fa-windows"></i>&nbsp;<?php oLang::_('O_ONEDRIVE_LOGOUT'); ?></button>
                                 <?php } else { ?>
                                     <a id="onedrive-authorize" href="<?php $this->model->oauthOneDrive(); ?>"
                                        target="_blank" class="btn-primary btn col-xs-12"><i
                                             class="fa fa-windows"></i>&nbsp;<?php oLang::_('O_AUTHENTICATION_ONEDRIVE'); ?>
                                     </a>
                                 <?php }
                                 ?>

                             </div>

                             <!--  google drive oauth button-->
                             <div class="col-xs-2">
                                 <?php
                                 if ($flag = $this->model->googleDriveVerify()) {
                                     ?>
                                     <button id="googledriveLogout" class="btn btn-default col-xs-12"
                                             onclick="googledrive_logout()"><i class="fa fa-google"></i>&nbsp;<?php oLang::_('O_GOOGLEDRIVE_LOGOUT'); ?></button>

                                 <?php } elseif (!empty($_GET['googlecode'])) {
                                     $this->model->oauthGoogleDrive();
                                     ?>
                                     <button id="onedriveLogout" class="btn btn-default col-xs-12"
                                             onclick="googledrive_logout()"><i class="fa fa-google"></i>&nbsp;<?php oLang::_('O_GOOGLEDRIVE_LOGOUT'); ?></button>
                                 <?php } else { ?>
                                     <a id="googledrive-authorize" href="<?php $this->model->oauthGoogleDrive(); ?>"
                                        target="_blank" class="btn-primary btn col-xs-12"><i
                                             class="fa fa-google"></i>&nbsp;<?php oLang::_('O_AUTHENTICATION_GOOGLEDRIVE'); ?>
                                     </a>
                                 <?php }
                                 ?>
                             </div>
                         </div>

                            <div class="row row-set" style="margin-top:24px;">
                                <div class="col-sm-12" style="padding-left: 0px; padding-right: 20px;">
                                    <a href="https://www.centrora.com/services" target="_blank"><div class="call-to-action">
                                            <div class="call-to-action-txt">
                                                <img width="35" height="35" alt="C_puma" src="http://googledrive.com/host/0BzcQR8G4BGjUX0ZzTzBvUVNEb00"> &nbsp;
                                                Schedule your scanning and update with Centrora Premium <sup>Now</sup></div>
                                        </div></a>
                                </div>

                            </div>
                            <div class="row">
                                <div id="footer" class="col-sm-12">
                                    <div>Centrora 2016 a portfolio of Luxur Group PTY LTD,  All rights reserved.</div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End .panel -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
    ?>
    <div id="oseappcontainer">
        <div class="container">
            <?php
            $this->model->showLogo();
            ?>
            <div id="sub-header" class="row"
                 style="background:url('<?php echo 'http://www.googledrive.com/host/0B4Hl9YHknTZ4X2sxNTEzNTBJUlE/sub_hd_bg.png' ?>') top center;  min-height:500px;">
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
    $this->model->showFooterJs();
}
?>