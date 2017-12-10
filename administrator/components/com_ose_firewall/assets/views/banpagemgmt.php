<?php
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
    die('Direct Access Not Allowed');
}
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$settings = $this->model->getBanPageSettings();
?>

<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php
        $this->model->showLogo ();
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel-body wrap-container">
                    <div class="row row-set">
                        <div class="col-sm-3 p-l-r-0">
                            <div id="c-tag">
                                <div class="col-sm-12" style="padding-left: 0px;">
                                    <span class="tag-title"><?php oLang::_('O_BANPAGE_TITLE'); ?></span>
                                </div>
                                <p class="tag-content"><?php oLang::_('O_BAN_PAGE_DESC'); ?></p>
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
                    <div class="row row-set" style="padding-right: 20px;">
                        <div class="title-bar"><?php oLang::_('O_BAN_PAGE_SMALL_DESC');?></div>
                        <div class="tab-pane active" id="firewall">
                            <form id = 'configuraton-form' class="form-horizontal  bg-transparent-white" style="padding:20px 0px;" role="form">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php oLang::_('O_FRONTEND_BLOCKING_MODE');?>
                                        <i tabindex="0" class="fa fa-question-circle"  data-toggle="popover"
                                           data-content="<?php oLang::_('O_FRONTEND_BLOCKING_MODE_HELP');?>"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="radio-inline">
                                            <input type="radio" id= "blockIPban" onclick="toggleDisabled(1)" name="blockIP" value="1" <?php echo (!empty($settings['info'][30]) && $settings['info'][30]==true)?'checked="checked"':''?>>
                                            <?php oLang::_('O_FRONTEND_BLOCKING_MODE_BAN');?>
                                            <i tabindex="0" class="fa fa-question-circle"  data-toggle="popover" data-title="Ban Page"
                                               data-content="<?php oLang::_('O_FRONTEND_BLOCKING_MODE_BAN_HELP');?>"></i>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" id= "blockIP403" onclick="toggleDisabled(0)" name="blockIP" value="0" <?php echo (empty($settings['info'][30]))?'checked="checked"':''?>>
                                            <?php oLang::_('O_FRONTEND_BLOCKING_MODE_403');?>
                                            <i tabindex="0" class="fa fa-question-circle"  data-toggle="popover" data-title="403 Blocking"
                                               data-content="<?php oLang::_('O_FRONTEND_BLOCKING_MODE_403_HELP');?>"></i>
                                        </label>
                                    </div>
                                </div>

                                <div id = "customBanpageDiv" class="form-group">
                                    <label class="col-sm-4 control-label"><?php oLang::_('O_CUSTOM_BAN_PAGE');?>
                                        <i tabindex="0" class="fa fa-question-circle"  data-toggle="popover"
                                           data-content="<?php oLang::_('O_CUSTOM_BAN_PAGE_HELP');?>"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <textarea name="customBanpage" id="customBanpage" class="form-control tinymce">
                                            <?php echo (empty($settings['info'][31]))?'':$settings['info'][31]?>
                                        </textarea>
                                    </div>
                                </div>

                                <div id = "customBanURLDiv" class="form-group">
                                    <label class="col-sm-4 control-label"><?php oLang::_('O_CUSTOM_BAN_PAGE_URL');?>
                                        <i tabindex="0" class="fa fa-question-circle"  data-toggle="popover"
                                           data-content="<?php oLang::_('O_CUSTOM_BAN_PAGE_URL_HELP');?>"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" id="customUrl" name="customBanURL" value="<?php echo (empty($settings['info'][32]))?'':$settings['info'][32]?>" class="form-control">
                                    </div>
                                </div>
                                <?php if(!oseFirewallBase::isSuite()){ ?>
                                <div class="form-group" id = "googleAuth">
                                    <label class="col-sm-4 control-label"><?php oLang::_('CENTRORA_GOOGLE_AUTH'); ?>
                                        <i tabindex="0" class="fa fa-question-circle" data-toggle="popover"
                                           data-content="<?php oLang::_('CENTRORA_GOOGLE_AUTH_HELP'); ?>"></i>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="onoffswitch">
                                            <input type="checkbox" value=1 name="centroraGA" class="onoffswitch-checkbox" id="centroraGASwitch"
                                                   onchange="showSecret()"
                                                <?php echo (!empty($settings['info'][28]) && $settings['info'][28] == true) ?
                                                    'checked="checked"' : '' ?>>
                                            <label class="onoffswitch-label" for="centroraGASwitch">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <?php if(!empty($settings['info'][28]) && $settings['info'][28] == true) {?>

                                        <!--                show qr code and the secret code -->
                                        <div class="col-sm-12" id="hidden-QRcode">
                                            <label class="col-sm-4 pt20 control-label"> <?php oLang::_('O_GOOGLE_2_SECRET'); ?>
                                                <i tabindex="0" class="fa fa-question-circle" data-toggle="popover"
                                                   data-content="<?php oLang::_('O_GOOGLE_2_SECRET_HELP'); ?>"></i>
                                            </label>

                                            <div id="shhsecret" class="col-sm-8 pt20">
                                                <?php
                                                $googleAuth = $this->model->showGoogleSecrets();
                                                echo $googleAuth['secret']; ?>
                                            </div>
                                            <div class="col-sm-12"></div>
                                            <label class="col-sm-4 control-label pt25">    <?php oLang::_('O_GOOGLE_2_QRCODE'); ?>
                                                <i tabindex="0" class="fa fa-question-circle " data-toggle="popover"
                                                   data-content="<?php oLang::_('O_GOOGLE_2_QRCODE_HELP'); ?>"></i>
                                            </label>
                                            <div id='shhqrcode' class="col-sm-8 pt5"><?php echo $googleAuth['QRcode']; ?> </div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="col-sm-12" id="hidden-QRcode" style="display: none">
                                            <label class="col-sm-4 pt20 control-label"> <?php oLang::_('O_GOOGLE_2_SECRET'); ?>
                                                <i tabindex="0" class="fa fa-question-circle " data-toggle="popover"
                                                   data-content="<?php oLang::_('O_GOOGLE_2_SECRET_HELP'); ?>"></i>
                                            </label>
                                            <div id="shhsecret" class="col-sm-8 pt20">
                                                <?php $googleAuth = $this->model->showGoogleSecrets();
                                                echo $googleAuth['secret']; ?>
                                            </div>
                                            <div class="col-sm-12"></div>
                                            <label class="col-sm-4 control-label pt25">    <?php oLang::_('O_GOOGLE_2_QRCODE'); ?>
                                                <i tabindex="0" class="fa fa-question-circle " data-toggle="popover"
                                                   data-content="<?php oLang::_('O_GOOGLE_2_QRCODE_HELP'); ?>"></i>
                                            </label>
                                            <div id='shhqrcode' class="col-sm-8 pt5"><?php echo $googleAuth['QRcode']; ?> </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <?php }?>
                                <?php if (OSE_CMS == 'joomla') { ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label"><?php oLang::_('O_BACKEND_SECURE_KEY'); ?>
                                            <i tabindex="0" class="fa fa-question-circle color-gray" data-toggle="popover"
                                               data-content="<?php oLang::_('O_BACKEND_SECURE_KEY_HELP'); ?>"></i>
                                        </label>
                                        <div class="col-sm-8">
                                            <?php $this->model->getBackEndSecureKey(); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <input type="hidden" name="option" value="com_ose_firewall">
                                <input type="hidden" name="controller" value="banpagemgmt">
                                <input type="hidden" name="action" value="saveBanPageSettings">
                                <input type="hidden" name="task" value="saveBanPageSettings">
                                <!--                <input type="hidden" name="type" value="scan">-->
                                <div class="form-group">
                                    <div  style="padding-right: 25px;">
                                        <button type="submit" class="btn-new result-btn-set" id='save-button-fw'><?php oLang::_('SAVE');?></button>
                                    </div>
                                </div>
                            </form>
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