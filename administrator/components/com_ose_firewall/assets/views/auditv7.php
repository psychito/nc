<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 21/07/2016
 * Time: 2:35 PM
 */
oseFirewall::checkDBReady ();
$this->model->getNounce();
$urls = oseFirewall::getDashboardURLs();
$confArray = $this->model->getConfiguration('scan');
$seoConfArray = $this->model->getConfiguration('seo');
?>
    <div id = "oseappcontainer" >
        <div class="container wrapbody">
            <?php
            $this ->model->showLogo ();
            ?>
            <!-- Row Start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel-body wrap-container">

                        <div class="row row-set">
                            <div class="col-sm-3 p-l-r-0">
                                <div id="c-tag">
                                    <div class="col-sm-12" style="padding-left: 0px;">
                                        <span class="tag-title"><?php oLang::_('O_AUDITV7_TITLE'); ?></span>
<!--                                        <span>(--><?php //oLang::_('AUDIT_MY_WEBSITE'); ?><!--)</span>-->
                                    </div>
                                    <p class="tag-content"><?php oLang::_('O_AUDITV7_DESC'); ?></p>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="col-sm-5">
                                    <div class="vs-line-1">
                                        <div class="vs-line-1-title fw-hover">
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
                                <div class="col-sm-4">
                                    <div class="vs-line-1">
                                        <div class="vs-line-1-title fw-hover">
                                            <?php if(OSE_CMS =='wordpress'){ ?>
                                                <a href="admin.php?page=ose_fw_bsconfigv7#advsettings" style="color:white;"><i class="fa  fa-gears"></i></a>
                                            <?php }else { ?>
                                                <a href="?option=com_ose_firewall&view=bsconfigv7#advsettings" style="color:white;"> <i class="fa  fa-gears"></i> </a>
                                            <?php }?>
                                        </div>
                                        <div class="vs-line-1-number">
                                            <?php oLang::_('BACK_TO_ADVANCE_SETTING_ADV');?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row-set">
                            <div class="title-bar"><?php oLang::_('O_AUDITV7_SMALL_DESC'); ?></div>
                            <div class="col-sm-12 bg-transparent-white">
                                <?php
                                if ($this->model->isBadgeEnabled()==false)
                                {
                                    ?>
                                    <!-- Panels Start -->
                                    <div class="panel panel-teal">
                                        <div class="panel-heading">
                                            <h3 class="panel-title color-dark"><?php echo 'Security Badge Disabled'; ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="col-sm-10">
                                                    <span class="label label-warning"><?php oLang::_('NOTE'); ?></span> <?php oLang::_('SECURITY_BADGE_DESC'); ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                    <a class="btn-new result-btn-set" href="<?php echo OSE_WPURL . '/wp-admin/widgets.php';?>" target="_blank"><?php oLang::_('FIX_IT'); ?></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Panels Ends -->
                                    <?php
                                }
                                ?>

                                <!-- Panels Start -->
                                <div class="panel panel-teal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title color-dark"><?php echo SAFE_BROWSING_CHECKUP; ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <?php
                                            $this ->model->showSafeBrowsingBar ();
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Panels Ends -->
                                <!-- Panels Start -->
                                <div class="panel panel-teal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title color-dark"><?php echo SECURITY_CONFIG_AUDIT; ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <?php
                                            $this ->model->showStatus ();
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Panels Ends -->
                                <!-- Panels Start -->
                                <div class="panel panel-teal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title color-dark"><?php echo SYSTEM_SECURITY_AUDIT; ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <?php
                                            $this ->model->showSytemStatus ();
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>
                </div>
            </div>
            <!-- Row Ends -->
            <?php
            $oem = new CentroraOEM();
            $oemCustomer = $oem->hasOEMCustomer();
            if(!empty($oemCustomer['data']['customer_id'])) {
                echo $oem->getCallToActionAndFooter();
            }else {?>
                <?php  echo $this->model->getCallToActionAndFooter(); }?>
        </div>
    </div>
    <div id='fb-root'></div>
<?php
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>

<?php
include_once(dirname(__FILE__).'/scanconfig.php');
include_once(dirname(__FILE__).'/adminform.php');
include_once(dirname(__FILE__).'/phpconfig.php');
?>