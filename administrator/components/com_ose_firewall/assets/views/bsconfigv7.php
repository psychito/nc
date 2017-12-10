<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 3/06/2016
 * Time: 3:18 PM
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
oseFirewall::checkDBReady ();
$this->model->getNounce();
$seoConfArray = $this->model->getSeoConfiguration();
$status = oseFirewall::checkSubscriptionStatus(false);
oseFirewallBase::callLibClass('fwscannerv7','fwscannerv7');
$fs = new oseFirewallScannerV7();
$settings = $fs->getFirewallScannerVersion();
$fs7on = false;
if($settings['status'] == 1)
{
    if(isset($settings['v7']) && $settings['v7'] == 1)
    {
        $fs7on = true;
        $fs7OffClass = '';
        $otherTags = '';
    }else {
        $fs7OffClass = 'disable-pointer turnBlur';
        $otherTags ='disable';
    }
}
?>
<div id="oseappcontainer">
 <div class="container wrapbody">
  <?php
  $this->model->showLogo ();
  ?>
     <div class="row">
         <div class="col-md-12">
             <div class="panel panel-primary plain ">
                 <!-- Start .panel -->
                 <div class="panel-body wrap-container">
                     <div class="row row-set">
                         <div class="col-sm-3 p-l-r-0" style = "width:20% !important;">
                             <div id="c-tag">
                                 <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title"><?php oLang::_('FIREWALL_OVERVIEW'); ?><span>
                                 </div>
                                 <p class="tag-content"><?php oLang::_('FIREWALL_OVERVIEW_DESC'); ?></p>
                             </div>
                         </div>
                         <div class="col-sm-9" style = "width:80% !important;">
                         <div class="col-sm-2" style = "width:14% !important;">
                             <div class="vs-line-1">
                                 <div id="fw-overview" class="vs-line-1-title fw-hover"> <i class="fa fa-fire"></i></div>
                                 <div class="vs-line-1-number">
                                     <?php oLang::_('FIREWALL_OVERVIEW'); ?>
                                 </div>
                             </div>
                         </div>
                             <div class="col-sm-2" style = "width:14% !important;">
                             <div class="vs-line-1">
                                 <div id="fw-uploadvali" class="vs-line-1-title fw-hover"> <i class="fa  fa-gears"></i></div>
                                 <div class="vs-line-1-number">
                                     <?php oLang::_('ADVANCE_SETTING'); ?>
                                 </div>
                             </div>
                         </div>
                             <div class="col-sm-2" style = "width:14% !important;">
                                 <div class="vs-line-1">
                                     <div id="fw-wizard" class="vs-line-1-title fw-hover"> <i class="fa  fa-magic"></i></div>
                                     <div class="vs-line-1-number">
                                         <?php oLang::_('WIZARD'); ?>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-sm-2" style = "width:14% !important;">
                                 <div class="vs-line-1">
                                     <div id="fw-login"class="vs-line-1-title fw-hover">
                                         <?php if(OSE_CMS =='wordpress'){ ?>
                                             <a href="admin.php?page=ose_fw_ipmanagement" style="color:white;"> <i class="fa fa-th"></i> </a>
                                         <?php }else { ?>
                                             <a href="?option=com_ose_firewall&view=ipmanagement" style="color:white;"> <i class="fa fa-th"></i> </a>
                                         <?php }?>
                                     </div>

                                     <div class="vs-line-1-number">
                                         <?php oLang::_('MENU_IP_MANAGEMENT'); ?>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-sm-2" style = "width:14% !important;">
                                 <div class="vs-line-1">
                                     <div id="" class="vs-line-1-title fw-hover">
                                         <?php if(OSE_CMS =='wordpress'){ ?>
                                         <a href="admin.php?page=ose_fw_bsconfigv7stats" style="color:white;" >
                                             <?php }else { ?>
                                             <a href="?option=com_ose_firewall&view=bsconfigv7stats" style="color:white;">
                                                 <?php }?>
                                             <i class="fa  fa-bar-chart"></i>
                                         </a>
                                     </div>
                                     <div class="vs-line-1-number">
                                         <?php oLang::_('FIREWALL_STATISTICS'); ?>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-sm-2" style = "width:14% !important;">
                                 <div class="vs-line-1">
                                     <div id="" class="vs-line-1-title fw-hover">
                                         <?php if(OSE_CMS =='wordpress'){ ?>
                                         <a href="admin.php?page=ose_fw_emailnotificationv7" style="color:white;">
                                             <?php }else { ?>
                                             <a href="?option=com_ose_firewall&view=emailnotificationv7" style="color:white;">
                                                 <?php }?>
                                             <i class="fa fa-bullhorn"></i>
                                         </a>
                                     </div>
                                     <div class="vs-line-1-number">
                                         <?php oLang::_('EMAIL_REPORTS'); ?>
                                     </div>
                                 </div>
                             </div>
<!--                             advanced rules update starts -->
                             <div class="col-sm-2" style = "width:14% !important;">
<!--                                 </div>-->
                                 <div id="vs-div-update" class="vs-line-1">
                                     <div class="vs-line-1-title"> <i id="icon-refresh" class="fa fa-refresh"></i></div>
                                     <div class="vs-line-1-content">
                                         <span id="v-sig"></span></div>
                                 </div>
                                 <div id="vs-div-uptodate" class="vs-line-1">
                                     <div class="vs-line-1-title"> Last Updated On : </div>
                                     <div class="vs-line-1-content">
                                         <p id="vs-uptodate"></p></div>
                                 </div>
                             </div>
<!--                             advance file rules update ends -->
                         </div>
                         <div class="col-sm-12" style="padding-right:20px; padding-left: 0px;">
                                 <div class="title-bar" style="background:rgba(76, 53, 90, 0.6) !important;"><?php oLang::_('V7_HELP_US'); ?> <b style="font-size: 14px;">support@centrora.com</b></div>
                         </div>
                     </div>
                     <div id="fo-row" class="row row-set" style="padding-right: 20px;">
                         <div class="title-bar"><?php oLang::_('FIREWALL_CONFIG_DESC'); ?></div>
                         <div id="fw-panel" class="col-sm-12 bg-transparent-white" style="padding-right: 0px; overflow: hidden; height: 400px;">
                             <div id="fw-off-mode-hint" class="col-sm-12" style="margin-bottom: -30px; text-align: center; margin-top: 5px;">
                                 <?php oLang::_('FIREWALL_STATUS_1'); ?> <b><?php oLang::_('FIREWALL_STATUS_2'); ?></b><?php oLang::_('FIREWALL_STATUS_3'); ?> &nbsp;<i class="fa fa-arrow-down"></i>
                             </div>
                             <div class="col-sm-4" style="padding:50px 0px;">
                             <div class="col-sm-12 remove-padding-l-r">
                                 <div id="fw-as" onclick="toggleSettings('#fw-as','11')" class="col-sm-12 firewall-checks">
                                     <div class="shield-square"><i class="fa fa-shield"></i></div>
                                     <div class="firewall-checks-content"><?php oLang::_('ANTI_SPAM'); ?></div>
                                 </div>
                                 <div id="fw-cua" onclick="toggleSettings('#fw-cua','16')" class="col-sm-12 firewall-checks">
                                     <div class="shield-square"><i class="fa fa-shield"></i></div>
                                     <div class="firewall-checks-content"><?php oLang::_('CHECK_USER_AGENT'); ?></div>
                                 </div>
                                 <?php if(!oseFirewallBase::isSuite()) {?>
                                 <div id="fw-bfp" class="col-sm-12 firewall-checks" style="margin-top: 20px;">
                                 <div class="shield-square"><i class="fa fa-shield"></i></div>
                                     <div class="firewall-checks-content p-t-b-10" ><?php oLang::_('BRUTEFORCE_PROTECTION'); ?></div>
                                 </div>
                                 <?php } ?>
                                 <div id="fw-fuc"  class="col-sm-12 firewall-checks">
                                     <div class="shield-square"><i class="fa fa-shield"></i></div>
                                     <div class="firewall-checks-content"><?php oLang::_('FILE_UPLOAD_CONTROL'); ?></div>
                                 </div>
                                 <div id="fw-srs"  class="col-sm-12 firewall-checks">
                                     <div class="shield-square"><i class="fa fa-shield"></i></div>
                                     <div class="firewall-checks-content"><?php oLang::_('SCAN_REQUEST_SETTING'); ?></div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-sm-4">
                             <div class="col-sm-12 txt-center">
                                 <div id="fw-securitytag">
                                     <?php oLang::_('SECURITY_LEVEL_1'); ?><br><?php oLang::_('SECURITY_LEVEL_2'); ?>: <span id="fw-levelcount"></span> %
                                 </div>

                                 <svg version="1.1" id="mark-shield" xmlns="http://www.w3.org/2000/svg"
                                      xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="286px"
                                      height="311px" viewBox="0 0 286 311" enable-background="new 0 0 286 311"
                                      xml:space="preserve">
                                   <defs>
                                       <mask id="mask">
                                           <polygon fill="white"
                                                    points="143.705,32.138 262.554,73.118 243.826,227.782 143.705,282.861 44.218,228.222 25.821,73.889 "/>
                                       </mask>
                                       <g id="curtain">
                                           <rect id="cr-left" x="9.875" y="28.999" fill="#41B3A1" width="134.312" height="263.573"/>
                                           <rect id="cr-right" x="144.188" y="28.999" fill="#269D8A" width="138.479" height="263.573"/>
                                       </g>
                                   </defs>
                                     <polygon fill="#D7D5D3" stroke="#A9A7A5" stroke-width="8" stroke-miterlimit="10"
                                              points="143.641,15.249 278.5,61.75 257.25,237.25 143.641,299.75 30.75,237.75 9.875,62.625 "/>
                                     <polygon fill="#c9c9c9"
                                              points="143.705,32.138 143.705,282.861 243.826,227.782 262.554,73.118 "/>
                                     <g mask="url(#mask)">
                                         <use id="fill-shield" xlink:href="#curtain"/>
                                     </g>
                                </svg>

                             </div>
                         </div>
                         <div class="col-sm-4" style="padding:30px 0px 22px 15px;">
                             <div id="fw-fs-section" class="col-sm-12" style="padding-right: 0px;">
                                 <div class="bg-color-midiumgrey fs-info-sections"><?php oLang::_('FIREWALL_STATUS'); ?></div>
                                 <div class="fs-info-sections" style="padding-left: 30px; margin-top: -5px; margin-bottom: -5px;">
                                         <div id="fc-fs-on"><?php oLang::_('ON'); ?></div>
                                         <div id="fc-fs-off"><?php oLang::_('OFF'); ?></div>
                                 </div>

                                 <div class="bg-color-lightgrey fs-info-sections"><?php oLang::_('WEB_ADMIN_EMAIL'); ?></div>
                                 <div class="form-group fs-info-sections" style="padding-left:27px;">
                                     <input type="text" class="form-control" id="wa-email">
                                 </div>

                                 <div class="bg-color-midiumgrey fs-info-sections"><?php oLang::_('FIREWALL_SENSITIVITY'); ?></div>
                                 <div class="fs-info-sections" style="padding-left:38px;">
                                     <input id="fwslider" type="text" data-slider-min="0" data-slider-max="4" data-slider-step="1"/>
                                 &nbsp; &nbsp;<span id="slide-value"><?php oLang::_('INSENSITIVE'); ?></span>
                                 </div>

                                 <div class="bg-color-lightgrey fs-info-sections"><?php oLang::_('MODEL'); ?></div>
                                 <?php if($status == false){?>
                                 <div class="fs-info-sections" style="padding-left: 30px; margin-top: -5px; margin-bottom: -5px;">
                                     <div id="mod-block-box">
                                         <div id="mod-block-box-done" style="cursor: pointer; position: absolute; color: white; right: 13px; font-size: 21px; top: 6px;"><i class="fa fa-close"></i></div>
                                         <div class="col-sm-12">
                                             <div style="color: white;"><?php oLang::_('SET_NUMBER_OF_ATTEMPTS'); ?></div>
                                             <a id="question-blocking" class="fuc-square" href="#" title=" blocking information " data-toggle="popover" data-placement="left" data-content="<?php oLang::_('SET_NUMBER_OF_ATTEMPTS_DESC'); ?>">
                                                 <i id="question-blocking" class="fw-question-icons fa fa-question"></i>
                                             </a>
                                             <input id="mod-block-attempts"  type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1"/>
                                             &nbsp; &nbsp;<span id="attempts-value"></span>
                                         </div>
                                     </div>
                                     <div id="" class="fw-freeuser"><?php oLang::_('LOGGING'); ?></div>
                                     </div>


                                 <?php }else { ?>
                                     <div class="fs-info-sections" style="padding-left: 30px; margin-top: -5px; margin-bottom: -5px;">
                                         <div id="mod-block-box">
                                             <div id="mod-block-box-done" style="cursor: pointer; position: absolute; color: white; right: 13px; font-size: 21px; top: 6px;"><i class="fa fa-close"></i></div>
                                             <div class="col-sm-12">
                                                 <div style="color: white;"><?php oLang::_('SET_NUMBER_OF_ATTEMPTS'); ?>
                                                     <a id="question-blocking" style = 'float:left;' class="fuc-square" href="#" title=" blocking information " data-toggle="popover" data-placement="left" data-content="Combination of firewall sensitivity and Attempt counts will be used as a criteria to determine whether to block an attacker or not">
                                                         <i id="question-blocking" class="fw-question-icons fa fa-question"></i>
                                                     </a>
                                                 </div>
                                                 <input id="mod-block-attempts" type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1"/>
                                                 &nbsp; &nbsp;<span id="attempts-value"></span>
                                             </div>
                                         </div>
                                         <div id="fc-mod-block"><?php oLang::_('BLOCKING'); ?></div>
                                         <div id="fc-mod-filter"><?php oLang::_('FILTERING'); ?></div>
                                     </div>
                                 <?php }?>
                             </div>

                             <div id="fw-bfp-section" class="col-sm-12" style="padding-right: 0px;">
                                 <div class="bg-color-midiumgrey bfp-info-sections"><?php oLang::_('BRUTE_FORCE_PROTECTION_STATUS'); ?></div>
                                 <div class="bfp-info-sections" style="padding-left: 30px; margin-top: -5px; margin-bottom: -5px;">
                                     <div id="bfp-on"><?php oLang::_('ON'); ?></div>
                                     <div id="bfp-off"><?php oLang::_('OFF'); ?></div>
                                 </div>
                                <div id="bfp-belongings">
                                 <div class="bg-color-lightgrey bfp-info-sections"><?php oLang::_('MAX_LOGIN_ATTEMPTS'); ?></div>
                                 <div class="form-group bfp-info-sections" style="padding-left:27px;">
                                     <select id="bfp-login-attempts">
                                         <option value="1">1</option>
                                         <option value="2">2</option>
                                         <option value="3">3</option>
                                         <option value="4">4</option>
                                         <option value="5">5</option>
                                         <option value="6">6</option>
                                         <option value="7">7</option>
                                         <option value="8">8</option>
                                         <option value="9">9</option>
                                         <option value="10">10</option>
                                         <option value="20">20</option>
                                         <option value="30">30</option>
                                         <option value="40">40</option>
                                         <option value="50">50</option>
                                         <option value="100">100</option>
                                         <option value="200">200</option>
                                         <option value="500">500</option>
                                     </select>
                                 </div>

                                 <div class="bg-color-lightgrey bfp-info-sections"><?php oLang::_('PERIOD_FOR_COUNTING_LOGIN_ATTEMPTS'); ?></div>
                                 <div class="bfp-info-sections" style="padding-left:27px;">
                                     <select id="bfp-login-period">
                                         <option value="5" selected >5 <?php oLang::_('MINS'); ?></option>
                                         <option value="10">10 <?php oLang::_('MINS'); ?></option>
                                         <option value="30">30 <?php oLang::_('MINS'); ?></option>
                                         <option value="60">1 <?php oLang::_('HR'); ?></option>
                                         <option value="120">2 <?php oLang::_('HRS'); ?></option>
                                         <option value="360">6 <?php oLang::_('HRS'); ?></option>
                                         <option value="720">12 <?php oLang::_('HRS'); ?></option>
                                         <option value="1440">1 <?php oLang::_('DAY'); ?></option>
                                     </select>
                                 </div>
                                 <?php if(!oseFirewallBase::isSuite()){ ?>
                                 <div class="bg-color-lightgrey bfp-info-sections">
                                     <div id="google-code-box">
                                         <span id="code-box-off"><?php oLang::_('GOOGLE_AUTH_DESC'); ?><b><?php oLang::_('GOOGLE_AUTH_DESC_1'); ?></b><?php oLang::_('GOOGLE_AUTH_DESC_2'); ?><b><?php oLang::_('GOOGLE_AUTH_DESC_3'); ?></b><?php oLang::_('GOOGLE_AUTH_DESC_4'); ?></span>
                                        <span id="code-box-on"></span>
                                     </div>
                                     <span id="google-code"><?php oLang::_('SHOW_QR'); ?></span><?php oLang::_('GOOGLE_VERIFICATION'); ?></div>
                                 <div class="bfp-info-sections" style="padding-left: 30px; margin-top: -5px; margin-bottom: -5px;">
                                     <div id="bfp-gv-on"><?php oLang::_('ON'); ?></div>
                                     <div id="bfp-gv-off"><?php oLang::_('OFF'); ?></div>
                                 </div>
                             <?php } ?>

                             </div>
                                 <div id="bfp-close" class="bfp-info-sections-btn btn-firewall-config col-sm-4 fw-hover" style="left: -10%">Close</div>
                             </div>

                             <div id="fw-srs-section" class="col-sm-12" style="padding: 0px;">
                                 <div id="fw-csrf" onclick="toggleSRSettings('#fw-csrf','3')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('XSITE_REQUEST_FORGERY'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="fw-css" onclick="toggleSRSettings('#fw-css','2')"  class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('XSITE_SCRIPTION'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div  id="fw-si" onclick="toggleSRSettings('#fw-si','4')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('SQL_INJECTION'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="fw-rfi" onclick="toggleSRSettings('#fw-rfi','5')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('REMOTE_FILE_INCLUSION'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="fw-lfi" onclick="toggleSRSettings('#fw-lfi','6')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('LOCAL_FILE_INCLUSION'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="fw-fsa" onclick="toggleSRSettings('#fw-fsa','12')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('FORMAT_SRTING_ATTACK'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="fw-lfma" onclick="toggleSRSettings('#fw-lfma','10')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('LOCAL_FILE_MODI_ATTEMPT'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="fw-dt" onclick="toggleSRSettings('#fw-dt','8')" class="srs-info-sections col-sm-12 p-l-r-0">
                                     <div class="srs-checks-content"><?php oLang::_('DIRECTORY_TRAVERSAL'); ?></div>
                                     <div class="srs-square"><i class="fa fa-shield"></i></div>
                                 </div>
                                 <div id="btn-srs-sa" class="col-sm-4 btn-firewall-config fw-hover srs-info-sections-btn" style="margin:5px 3px 0px -7px;"><?php oLang::_('SELECT_ALL'); ?></div>
                                 <div id="btn-srs-dsa" class="col-sm-4 btn-firewall-config fw-hover srs-info-sections-btn" style=" margin-top: 5px; margin-right: 3px;"><?php oLang::_('DESELECT_ALL'); ?></div>
                                 <div id="btn-srs-close" class="col-sm-4 btn-firewall-config fw-hover srs-info-sections-btn" style=" margin-top: 5px;"><?php oLang::_('CLOSE'); ?></div>
                             </div>
                             <div id="fw-fuc-section" class="col-sm-12" style="padding: 0px;">
                                 <div  class="fuc-info-sections col-sm-12 p-l-r-0">
                                     <div id="fw-vuf" onclick="toggleFUCSettings('#fw-vuf','13')" class="fuc-checks-content"><?php oLang::_('VALIDATE_UPLOAD_FILES'); ?></div>
                                     <a id="question-vuf" class="fuc-square" href="#" title=" Validate Upload Files" data-toggle="popover" data-placement="left" data-content="<?php oLang::_('FIREWALL_DESC1'); ?>">
                                         <i id="question-vuf" class="fw-question-icons fa fa-question"></i>
                                     </a>
                                     <!--                                     <div class="fuc-square"><i class="fa fa-question"></i></div>-->
                                 </div>
                                 <div  class="fuc-info-sections col-sm-12 p-l-r-0">
                                     <div id="fw-svf"  onclick="toggleFUCSettings('#fw-svf','14')" class="fuc-checks-content"><?php oLang::_('SCAN_VIRUS_FILES'); ?></div>
                                     <a id="question-svf" class="fuc-square" href="#" title="Scan Virus Files" data-toggle="popover"  data-content="<?php oLang::_('FIREWALL_DESC2'); ?>">
                                         <i class="fw-question-icons fa fa-question"></i>
                                     </a>
<!--                                     <div class="fuc-square"><i class="fa fa-question"></i></div>-->
                                 </div>
                                 <div id="btn-fuc-sa" class="col-sm-4 btn-firewall-config fw-hover fuc-info-sections-btn" style="margin:5px 3px 0px -7px;"><?php oLang::_('SELECT_ALL'); ?></div>
                                 <div id="btn-fuc-dsa" class="col-sm-4 btn-firewall-config fw-hover fuc-info-sections-btn" style=" margin-top: 5px; margin-right: 3px;"><?php oLang::_('DESELECT_ALL'); ?></div>
                                 <div id="btn-fuc-close" class="col-sm-4 btn-firewall-config fw-hover fuc-info-sections-btn" style=" margin-top: 5px;"><?php oLang::_('CLOSE'); ?></div>
                             </div>
                         </div>
                     </div>
                         <div class="col-sm-12">
                                 <div id="fw-submit" class="pull-right col-sm-4 btn-new result-btn-set " style="margin-right: -15px; margin-top: 15px; text-align: center;"><i id="ic-change" class="fa fa-save text-primary" style="margin-right: 5px;"></i><?php oLang::_('SAVE'); ?></div>
                                 <div id="save-hint" class="pull-right pumpAnimation" style="font-size: 14px; margin-right: 25px; font-weight: 400; display: none; color: white; margin-top: 19px;">
                                     <?php oLang::_('SAVE_BY_CLICK'); ?> &nbsp;&nbsp;<i class="fa  fa-arrow-right"></i>
                                 </div>
                         </div>
                     </div>

                     <!--   Here is the HTML for file upload validation-->
                     <div id="uv-row" class="row row-set" style="padding-right: 20px;">
                         <div class="title-bar"><?php oLang::_('VIEW_SETUP_ADVANCED_FEATURES'); ?></div>
                         <div class="col-sm-3 bg-transparent-white" style="padding-bottom: 170px;">

                             <div class="upload-btn-frame txt-center ;" style="margin-top:30px;">
                                 <div id="btn-se-config" class="upload-btns"  style="width:99%;"><?php oLang::_('TITLE_SEO_CONFIGURATION'); ?></div>
                             </div>
                             <div class="upload-btn-frame txt-center">
                                 <div class="upload-btns" id="upload-ful"><?php oLang::_('FILE_UPLOAD_LOGS'); ?></div>
                                 <a id="question-ful" class="fuc-square" href="#" title="Scan Virus Files" data-toggle="popover" data-content="<?php oLang::_('FILE_UPLOAD_LOGS_DESC'); ?>"
                                 style="width:15%; height:42px;">
                                 <i class="fw-question-icons fa fa-question"></i>
                                 </a>
                             </div>
                             <div class="upload-btn-frame txt-center">
                             <div id="btn-switch-table" class="col-sm-11 upload-btns" style="width:99%;">
                                 <?php oLang::_('FILE_EXTENSION_CONTROL_TABLE'); ?></div>
                             </div>
                             <div class="upload-btn-frame txt-center">
                                 <?php if(OSE_CMS =='wordpress'){ ?>
                                 <a href="admin.php?page=ose_fw_whitemgmt" style="color:white;">
                                     <?php }else { ?>
                                     <a href="?option=com_ose_firewall&view=whitelistmgmt" style="color:white;" >
                                         <?php }?>
                                         <div  class="col-sm-11 upload-btns" style="width:99%;">
                                             <?php oLang::_('WHITE_LIST_MANAGEMENT'); ?></div>
                                     </a>
                             </div>
                             <div class="upload-btn-frame txt-center">
                                 <?php if(OSE_CMS =='wordpress'){ ?>
                                 <a href="admin.php?page=ose_fw_countryblockingv7" style="color:white;" >
                                     <?php }else { ?>
                                     <a href="?option=com_ose_firewall&view=countryblockingv7" style="color:white;">
                                         <?php }?>
                                         <div  class="col-sm-11 upload-btns" style="width:99%;">
                                             <?php oLang::_('COUNTRY_BLOCKING'); ?></div>
                                     </a>
                             </div>
                             <div class="upload-btn-frame txt-center">
                                 <?php if(OSE_CMS =='wordpress'){ ?>
                                 <a href="admin.php?page=ose_fw_auditv7" style="color:white;">
                                     <?php }else { ?>
                                     <a href="?option=com_ose_firewall&view=auditv7" style="color:white;" >
                                         <?php }?>
                                         <div  class="col-sm-11 upload-btns" style="width:99%;">
                                             <?php oLang::_('AUDIT_WEBSITE'); ?></div>
                                 </a>
                             </div>
                             <div class="upload-btn-frame txt-center">
                                 <?php if(OSE_CMS =='wordpress'){ ?>
                                 <a href="admin.php?page=ose_fw_banpagemgmt" style="color:white;" >
                                     <?php }else { ?>
                                     <a href="?option=com_ose_firewall&view=banpagemgmt" style="color:white;">
                                         <?php }?>
                                         <div  class="col-sm-11 upload-btns" style="width:99%;">
                                             <?php oLang::_('BAN_PAGE_MANAGEMENT'); ?></div>
                                 </a>
                             </div>
                         </div>
                         <div class="col-sm-9" style="padding: 0px;">
                             <div id="adset-tablehints"><?php oLang::_('ENABLE_VALIDATE_UPLOAD_FILES_DESC1'); ?><b> <?php oLang::_('ENABLE_VALIDATE_UPLOAD_FILES_DESC2'); ?></b><?php oLang::_('ENABLE_VALIDATE_UPLOAD_FILES_DESC3'); ?><b>' <?php oLang::_('ENABLE_VALIDATE_UPLOAD_FILES_DESC4'); ?>'</b> <?php oLang::_('ENABLE_VALIDATE_UPLOAD_FILES_DESC5'); ?></div>
                             <div id="vuf-table" class="col-sm-12 disable-pointer turnBlur" style="padding:0px">
                                 <div class="fuv-table-btn-frame">
                                     <button class="btn-new fw-hover fuv-table-btns" onClick="addExt()" type="button">
                                         <i class="fa fa-plus"></i>
                                         <?php oLang::_('ADD_FILE_EXTENSION'); ?>
                                     </button>
                                 </div>
                                 <table class="table display" id="extensionListTable">
                                     <thead>
                                     <tr>
                                         <th><?php oLang::_('O_EXTENSION_ID'); ?></th>
                                         <th><?php oLang::_('O_EXTENSION_NAME'); ?></th>
                                         <th><?php oLang::_('O_EXTENSION_TYPE'); ?></th>
                                         <th><?php oLang::_('O_EXTENSION_STATUS'); ?></th>
                                     </tr>
                                     </thead>
                                     <tfoot>
                                     <tr>
                                         <th><?php oLang::_('O_EXTENSION_ID'); ?></th>
                                         <th><?php oLang::_('O_EXTENSION_NAME'); ?></th>
                                         <th><?php oLang::_('O_EXTENSION_TYPE'); ?></th>
                                         <th><?php oLang::_('O_EXTENSION_STATUS'); ?></th>
                                     </tr>
                                     </tfoot>
                                 </table>
                             </div>
                             <div id="ful-table" class="col-sm-12 disable-pointer turnBlur" style="padding:0px">
                                 <table class="table display" id="uploadLogTableV7">
                                     <thead>
                                     <tr>
                                         <th><?php oLang::_('O_ID'); ?></th>
                                         <th><?php oLang::_('O_IP'); ?></th>
                                         <th><?php oLang::_('O_FILENAME'); ?></th>
                                         <th><?php oLang::_('O_FILETYPE'); ?></th>
                                         <th><?php oLang::_('O_IP_STATUS'); ?></th>
                                         <th><?php oLang::_('O_DATE'); ?></th>
                                     </tr>
                                     </thead>
                                     <tfoot>
                                     <tr>
                                         <th><?php oLang::_('O_ID'); ?></th>
                                         <th><?php oLang::_('O_IP'); ?></th>
                                         <th><?php oLang::_('O_FILENAME'); ?></th>
                                         <th><?php oLang::_('O_FILETYPE'); ?></th>
                                         <th><?php oLang::_('O_IP_STATUS'); ?></th>
                                         <th><?php oLang::_('O_DATE'); ?></th>
                                     </tr>
                                     </tfoot>
                                 </table>
                             </div>
<!--                             SEO CONIG FORM BEGINS-->
                             <div id="seo-sets" class="col-sm-12" style="background:rgba(255, 255, 255, 0.7);">
                                 <form id = 'seo-configuraton-formv7' class="form-horizontal group-border stripped" role="form">
                                     <div class="form-group">
                                         <label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('O_SEO_PAGE_TITLE');?>
                                             <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                                                data-content="<?php oLang::_('O_SEO_PAGE_TITLE_HELP');?>"></i>
                                         </label>
                                         <div class="col-sm-8">
                                             <input type="text" name="pageTitle" value="<?php echo (empty($seoConfArray['info'][18]))?'Your Web Page Title':$seoConfArray['info'][18]?>" class="form-control">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label for="metaKeywords" class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_KEY');?>
                                             <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                                                data-content="<?php oLang::_('O_SEO_META_KEY_HELP');?>"></i>
                                         </label>
                                         <div class="col-sm-8">
                                             <input type="text" name="metaKeywords" value="<?php echo (empty($seoConfArray['info'][19]))?'SEO Meta Keywords':$seoConfArray['info'][19]?>" class="form-control">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_DESC');?>
                                             <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                                                data-content="<?php oLang::_('O_SEO_META_DESC_HELP');?>"></i>
                                         </label>
                                         <div class="col-sm-8">
                                             <input type="text" name="metaDescription" value="<?php echo (empty($seoConfArray['info'][20]))?'SEO Meta Description':$seoConfArray['info'][20]?>" class="form-control">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="col-sm-4 control-label"><?php oLang::_('O_SEO_META_GENERATOR');?>
                                             <i tabindex="0" class="fa fa-question-circle color-gray"  data-toggle="popover"
                                                data-content="<?php oLang::_('O_SEO_META_GENERATOR_HELP');?>"></i>
                                         </label>
                                         <div class="col-sm-8">
                                             <input type="text" name="metaGenerator" value="<?php echo (empty($seoConfArray['info'][21]))?'SEO Meta Generator':$seoConfArray['info'][21]?>" class="form-control">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="col-sm-4 control-label"><?php oLang::_('O_SCAN_GOOGLE_BOTS');?></label>
                                         <div class="col-sm-8">
                                             <div class="onoffswitch">
                                                 <input type="checkbox" value = 1 name="scanGoogleBots" class="onoffswitch-checkbox" id="scanGoogleBots"
                                                     <?php echo (!empty($seoConfArray['info'][22]) && $seoConfArray['info'][22]) ? 'checked="checked"' : '' ?>>
                                                 <label class="onoffswitch-label" for="scanGoogleBots">
                                                     <span class="onoffswitch-inner"></span>
                                                     <span class="onoffswitch-switch"></span>
                                                 </label>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="col-sm-4 control-label"><?php oLang::_('O_SCAN_YAHOO_BOTS');?></label>
                                         <div class="col-sm-8">
                                             <div class="onoffswitch">
                                                 <input type="checkbox" value = 1 name="scanYahooBots" class="onoffswitch-checkbox" id="scanYahooBots"
                                                     <?php echo (!empty($seoConfArray['info'][23]) && $seoConfArray['info'][23] == true) ? 'checked="checked"' : '' ?>>
                                                 <label class="onoffswitch-label" for="scanYahooBots">
                                                     <span class="onoffswitch-inner"></span>
                                                     <span class="onoffswitch-switch"></span>
                                                 </label>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="col-sm-4 control-label"><?php oLang::_('O_SCAN_MSN_BOTS');?></label>
                                         <div class="col-sm-8">
                                             <div class="onoffswitch">
                                                 <input type="checkbox" value = 1 name="scanMsnBots" class="onoffswitch-checkbox" id="scanMsnBots"
                                                     <?php echo (!empty($seoConfArray['info'][24]) &&$seoConfArray['info'][24] == true) ? 'checked="checked"' : '' ?>>
                                                 <label class="onoffswitch-label" for="scanMsnBots">
                                                     <span class="onoffswitch-inner"></span>
                                                     <span class="onoffswitch-switch"></span>
                                                 </label>
                                             </div>
                                         </div>
                                     </div>
                                     <input type="hidden" name="option" value="com_ose_firewall">
                                     <input type="hidden" name="controller" value="bsconfigv7">
                                     <input type="hidden" name="action" value="saveConfigSEO">
                                     <input type="hidden" name="task" value="saveConfigSEO">
                                     <input type="hidden" name="type" value="seo">

                                     <div class="form-group">
                                         <div class="col-sm-offset-10">
                                             <button type="submit" class="btn-new result-btn-set" id='save-button'><?php oLang::_('SAVE');?></button>
                                         </div>
                                     </div>
                                 </form>
                             </div>
                             <!--                         seo config form ends-->
                         </div>
                     </div>
                                            <!--          uv row end-->
                     <div id="wizard-row" class="row row-set" style="padding-right: 20px;">
                         <div class="title-bar"><?php oLang::_('FIREWALL_EASY_SETUP');?></div>
                         <div id="wiz-basic" class="col-sm-12 bg-transparent-white" style="padding-bottom: 30px; min-height: 345px;">
                            <div class="wizard-nav">
                                <ul>
                                    <?php if(!oseFirewallBase::isSuite()) {?>
                                    <li id="li-bfp" class="opacity-100"><?php oLang::_('BF');?></li>
                                    <li id="li-bfp-set"><?php oLang::_('BF_SETUP');?></li>
                                    <?php }?>
                                    <li id="li-as"><?php oLang::_('ANTI_SPAMMING');?></li>
                                    <li id="li-fup"><?php oLang::_('FILE_UPLOAD_PROTECTION');?></li>
                                    <li id="li-rs"><?php oLang::_('REQUEST_SCANNING');?></li>
                                    <li id="li-email"><?php oLang::_('EMAIL_FIREWALL_SENSITITY');?></li>
                                    <li id="li-block"><?php oLang::_('BLOCK_FILTER');?></li>
                                    <li id="li-complete"><?php oLang::_('COMPLETE');?></li>
                                </ul>
                            </div>
                             <div id="wiz-bfp">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('BRUTEFORCE_SETTINGS');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('BRUTEFORCE_SETTINGS_DESC');?>
                                 </div>
                             </div>
                             <div id="wiz-bfp-set">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('BRUTEFORCE_SETTINGS_SETUP');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <div class="col-sm-12">
                                         <?php oLang::_('BRUTEFORCE_SETTINGS_SETUP_DESC');?>
                                         <select id="bfp-wiz-attempts" class="col-sm-12">
                                             <option value="1">1</option>
                                             <option value="2">2</option>
                                             <option value="3">3</option>
                                             <option value="4">4</option>
                                             <option value="5">5</option>
                                             <option value="6">6</option>
                                             <option value="7">7</option>
                                             <option value="8">8</option>
                                             <option value="9">9</option>
                                             <option value="10">10</option>
                                             <option value="20">20</option>
                                             <option value="30">30</option>
                                             <option value="40">40</option>
                                             <option value="50">50</option>
                                             <option value="100">100</option>
                                             <option value="200">200</option>
                                             <option value="500">500</option>
                                         </select>
                                     </div>
                                     <div class="col-sm-12" style="margin-top: 10px;">
                                         <?php oLang::_('TIME_FRAME');?>
                                         <select id="bfp-wiz-period" class="col-sm-12">
                                             <option value="5" selected >5 <?php oLang::_('MINS');?></option>
                                             <option value="10">10 <?php oLang::_('MINS');?></option>
                                             <option value="30">30 <?php oLang::_('MINS');?></option>
                                             <option value="60">1 <?php oLang::_('HR');?></option>
                                             <option value="120">2 <?php oLang::_('HRS');?></option>
                                             <option value="360">6 <?php oLang::_('HRS');?></option>
                                             <option value="720">12 <?php oLang::_('HRS');?></option>
                                             <option value="1440">1 <?php oLang::_('DAY');?></option>
                                         </select>
                                     </div>
                                     <div class="col-sm-12" style="margin-top: 10px;">
                                        <b><?php oLang::_('GOOGLE_AUTH');?></b>
                                         <i id="gv-desc" class="fa  fa-question-circle"></i>
                                         <img id="wiz-gaimage-1" class="col-sm-12 gv-image-desc" alt="how it works" src="http://image.slidesharecdn.com/two-factorauthenticationallthingsapimeetup-150624094258-lva1-app6891/95/two-factor-authentication-with-laravel-and-google-authenticator-3-638.jpg?cb=1435139034">
                                         <img id="wiz-gaimage-2" class="col-sm-12 gv-image-desc" alt="how it works" src="https://d35q8ug2n7npkr.cloudfront.net/qr_firewallset.png">
                                         <img id="wiz-gaimage-3" class="col-sm-12 gv-image-desc" alt="how it works" src="https://d35q8ug2n7npkr.cloudfront.net/qr_userprofile.png">

                                         <?php oLang::_('GOOGLE_AUTH_DESC5');?>
                                       <div class="col-sm-12">
                                           </div>
                                     </div>
                                 </div>
                             </div>
                             <div id="wiz-as">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('ANTI_SPAMMING');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('ANTI_SPAMMING_DESC');?>
                                 </div>
                             </div>
                             <div id="wiz-fup">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('FILE_UPLOAD_PROTECTION');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('FILE_UPLOAD_PROTECTION_DESC');?>
                                 </div>
                             </div>
                             <div id="wiz-rs">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('REQUEST_SCANNING');?> [<?php oLang::_('HIGHLY_RECOMMENDED');?>]
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('REQUEST_SCANNING_DESC');?>
                                     <li><?php oLang::_('XSITE_SCRIPTING');?></li>
                                     <li><?php oLang::_('XSITE_REQUEST_FORGERY');?></li>
                                     <li><?php oLang::_('SQL_INJECTION');?></li>
                                     <li><?php oLang::_('REMOTE_FILE_INCLUSION');?></li>
                                     <li><?php oLang::_('LOCAL_FILE_INCLUSION');?></li>
                                     <li><?php oLang::_('DIRECTORY_TRAVERSAL');?></li>
                                     <li><?php oLang::_('LOCAL_FILE_MODI_ATTEMPT');?></li>
                                     <li><?php oLang::_('FORMAT_SRTING_ATTACK');?></li>
                                     <li><?php oLang::_('CHECK_USER_AGENT');?></li>
                                     </ul>
                                     <div style="clear:both;">
                                         <?php oLang::_('REQUEST_SCANNING_DESC1');?>
                                     </div>
                                 </div>
                             </div>
                             <div id="wiz-email">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('SET_EMAIL');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('SET_EMAIL_DESC');?>
                                 </div>
                                 <div style="text-align: center;">
                                     <input type="text" id="wiz-email-address" class="form-control">
                                 </div>
                                 <div class="col-sm-12 wizard-section-header" style="margin-top: 20px;">
                                     <?php oLang::_('FIREWALL_SENSITIVITY');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('FIREWALL_SENSITIVITY_DESC');?>
                                    <b> <?php oLang::_('FIREWALL_SENSITIVITY_DESC1');?></b> <?php oLang::_('FIREWALL_SENSITIVITY_DESC2');?>
                                 </div>
                                 <div class="col-sm-12" style="text-align: center; margin-top: 10px;">
                                     <div class="wiz-sensitivity-label col-sm-2 col-sm-offset-1">
                                         <?php oLang::_('FIREWALL_SENSITIVITY_OPTION1');?><br><input type="radio" name="sensitivity" value="90" checked>
                                     </div>
                                     <div class="wiz-sensitivity-label col-sm-2">
                                         <?php oLang::_('FIREWALL_SENSITIVITY_OPTION2');?><br><input type="radio" name="sensitivity" value="45">
                                     </div>
                                     <div class="wiz-sensitivity-label col-sm-2">
                                         <?php oLang::_('FIREWALL_SENSITIVITY_OPTION3');?><br><input type="radio" name="sensitivity" value="35">
                                     </div>
                                     <div class="wiz-sensitivity-label col-sm-2">
                                         <?php oLang::_('FIREWALL_SENSITIVITY_OPTION4');?><br><input type="radio" name="sensitivity" value="25">
                                     </div>
                                     <div class="wiz-sensitivity-label col-sm-2">
                                         <?php oLang::_('FIREWALL_SENSITIVITY_OPTION5');?><br><input type="radio" name="sensitivity" value="15">
                                     </div>
                                 </div>
                             </div>
                             <div id="wiz-block">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('FIREWALL_MODE');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php if($status == true){?>
                                         <?php oLang::_('FIREWALL_MODE_DESC');?>
                                     <table class="table display dataTable">
                                         <thead style="background:rgba(250,250,250,0.7);">
                                         <tr>
                                         <th><?php oLang::_('FIREWALL_MODE_DESC1');?> [<?php oLang::_('PREMIUM_USER');?>]</th>
                                         <th><?php oLang::_('FIREWALL_MODE_DESC2');?> [<?php oLang::_('PREMIUM_USER');?>]</th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         <tr>
                                             <td><?php oLang::_('FIREWALL_MODE_DESC3');?></td>
                                             <td><?php oLang::_('FIREWALL_MODE_DESC3');?></td>
                                         </tr>
                                         <tr>
                                             <td><?php oLang::_('FIREWALL_MODE_DESC4');?></td>
                                             <td><?php oLang::_('FIREWALL_MODE_DESC5');?></td>
                                         </tr>
                                         </tbody>
                                         </table>
                                     <br/>
                                         <?php oLang::_('FIREWALL_MODE_DESC6');?>

                                     <?php }else { ?>
                                         <?php oLang::_('FIREWALL_MODE_DESC7');?>
                                         <table class="table display dataTable">
                                             <thead style="background:rgba(250,250,250,0.7);">
                                             <tr>
                                                 <th><?php oLang::_('FIREWALL_MODE_DESC1');?> [<?php oLang::_('PREMIUM_USER');?>]</th>
                                                 <th><?php oLang::_('FIREWALL_MODE_DESC2');?> [<?php oLang::_('PREMIUM_USER');?>]</th>
                                                 <th><?php oLang::_('FIREWALL_MODE_DESC9');?> [<?php oLang::_('FREE');?>]</th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                             <tr>
                                                 <td style="opacity:0.6"><?php oLang::_('FIREWALL_MODE_DESC3');?></td>
                                                 <td style="opacity:0.6"><?php oLang::_('FIREWALL_MODE_DESC3');?></td>
                                                 <td><?php oLang::_('FIREWALL_MODE_DESC8');?></td>
                                             </tr>
                                             <tr>
                                                 <td><?php oLang::_('FIREWALL_MODE_DESC4');?></td>
                                                 <td><?php oLang::_('FIREWALL_MODE_DESC5');?></td>
                                                 <td><?php oLang::_('FIREWALL_MODE_DESC10');?></td>
                                             </tr>
                                             </tbody>

                                         </table>
                                         <br/>
                                         <?php oLang::_('ENABLE_LOGGING_MODE');?><b><?php oLang::_('ENABLE_LOGGING_MODE1');?></b><?php oLang::_('ENABLE_LOGGING_MODE2');?><b><?php oLang::_('ENABLE_LOGGING_MODE3');?></b>
<!--                                         subscription code -->
                                     <?php }?>
                                 </div>
                             </div>
                             <div id="wiz-com">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('COMPLETED_BASIC_SETTING');?>
                                 </div>
                                 <div class="wizard-section-content">
                                     <?php oLang::_('COMPLETED_BASIC_SETTING_DESC1');?><br><span style="opacity: 0.7;">(<?php oLang::_('COMPLETED_BASIC_SETTING_DESC2');?>)</span>
                                 </div>
                                 <div style="text-align: center; margin-top: 40px;">
                                 <div id="wiz-complete" class="btn-new"><?php oLang::_('SAVE_BASIC_SETTING');?></div>
                                 <div id="wiz-go-advance" class="btn-new"><?php oLang::_('GO_ADVANCE_SETTING');?></div>
                                 </div>
                             </div>
                             <div id="wiz-step" class="col-sm-10" style="text-align: right; margin-top: 25px;"><?php oLang::_('STEP');?> 1/9</div>
                         </div>

<!--                         here are the panel of wizard advance settings-->
                         <div id="wiz-advance" class="col-sm-12 bg-transparent-white" style="padding-bottom: 30px; min-height: 345px;">
                             <div class="wizard-nav">
                                 <ul>
                                     <li id="ad1-title" class="opacity-100"><?php oLang::_('WHITE_LIST');?></li>
                                     <li id="ad2-title"><?php oLang::_('SEARCH_ENGINE');?></li>
                                     <li id="ad3-title"><?php oLang::_('COMLETE_ADVANCE_SETTING');?></li>
                                 </ul>
                             </div>

                             <div id="wiz-ad-1" class="col-sm-12">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('ADVANCE_SETTING_WHITELIST_DESC');?>
                                 </div>
                                 <div class="wizard-section-content" style="text-align:center;">
                                     <?php oLang::_('ADVANCE_SETTING_WHITELIST_DESC1');?>
                                 </div>
                                 <div class="col-sm-offset-3 col-sm-6" style="text-align: center; margin-top: 40px;">
                                     <?php $v6variableexists = $this->model->v6variablesexists();?>
                                     <?php if($v6variableexists['status'] == 'SUCCESS' ) {?>
                                     <div id="wiz-ad1-imp" class="btn-new"><?php oLang::_('ADVANCE_SETTING_WHITELIST_DESC2');?></div>
                                     <?php }?>
                                     <div id="wiz-ad1-def" class="btn-new" style="margin-top: 5px;"><?php oLang::_('ADVANCE_SETTING_WHITELIST_DESC3');?></div>
                                     <div id="wiz-ad1-skip" class="btn-new" style="margin-top: 5px;"><?php oLang::_('SKIP');?></div>
                                 </div>
                             </div>
                             <div id="wiz-ad-2"  class="col-sm-12">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('ADVANCE_SETTING_BOTS_SECANNING');?>
                                 </div>
                                 <div class="wizard-section-content" style="text-align:center;">
                                     <?php oLang::_('ADVANCE_SETTING_BOTS_SECANNING_DESC');?>
                                 </div>
                                 <div class="col-sm-offset-3 col-sm-6" style="text-align: center; margin-top: 40px;">
                                     <div id="wiz-ad2-enable" class="btn-new"><?php oLang::_('ENABLE');?></div>
                                     <div id="wiz-ad2-skip" class="btn-new" style="margin-top: 5px;"><?php oLang::_('SKIP');?></div>
                                 </div>
                             </div>
                             <div id="wiz-ad-3"  class="col-sm-12">
                                 <div class="col-sm-12 wizard-section-header">
                                     <?php oLang::_('FIREWALL_WIZARD_COMPLETED');?>
                                 </div>
                                 <div class="wizard-section-content" style="text-align:center;">
                                     <?php oLang::_('FIREWALL_WIZARD_COMPLETED_DESC');?>
                                     <ol style="text-align:left;">
                                         <li><b><?php oLang::_('COUNTRY_BLOCKING');?></b><?php oLang::_('COUNTRY_BLOCKING_DESC');?></li>
                                         <li><b><?php oLang::_('FILE_UPLOAD_MANAGEMENT2');?></b><?php oLang::_('FILE_UPLOAD_MANAGEMENT2_DESC');?></li>
                                         <li><b><?php oLang::_('BAN_PAGE_CUSTOM');?></b><?php oLang::_('BAN_PAGE_CUSTOM_DESC');?></li>
                                     <ol>
                                 </div>
                                 <div class="col-sm-offset-3 col-sm-6" style="text-align: center; margin-top: 40px;">
                                     <div id="wiz-ad3-fin" class="btn-new"><?php oLang::_('FINISH');?></div>
                                 </div>
                             </div>
                         </div>
<!--                         here are the button for wizard basic settings-->
                         <div id="wiz-btns" class="col-sm-12" style="float:right; margin-top: 15px; padding-right: 20px;">
                             <div id="wiz-skip" class="btn-new result-btn-set p-l-r-20"><?php oLang::_('SKIP');?></div>
                             <div id="wiz-enable" class="btn-new result-btn-set p-l-r-20"><?php oLang::_('ENABLE');?></div>
                             <div id="wiz-disable" class="btn-new result-btn-set p-l-r-20"><?php oLang::_('DISABLE');?></div>
                             <div id="wiz-back" class="btn-new result-btn-set p-l-r-20"><?php oLang::_('BACK');?></div>
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
 </div>
</div>

<div class="modal fade" id="addExtModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><div><?php oLang::_('CLOSE');?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_EXT'); ?></h4>
            </div>
            <form id='addext-form' class="form-horizontal group-border stripped" role="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label
                            class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_NAME'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" name="ext-name" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ext-type"
                               class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_TYPE'); ?></label>

                        <div class="col-sm-8">
                            <select class="form-control" name='ext-type' id='ext-type'>
                                <?php print_r($this->model->getExtType()) ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ext-status"
                               class="col-sm-4 control-label"><?php oLang::_('O_EXTENSION_STATUS'); ?></label>

                        <div class="col-sm-8">
                            <select class="form-control" name='ext-status' id='ext-status'>
                                <option value='1'><?php oLang::_('ALLOWED');?></option>
                                <option value='2'><?php oLang::_('FORBIDDEN');?></option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="option" value="com_ose_firewall">
                    <input type="hidden" name="controller" value="upload">
                    <input type="hidden" name="action" value="saveExt">
                    <input type="hidden" name="task" value="saveExt">
                </div>
                <div class="modal-footer">
                    <label id="ext-warning-label" class="col-sm-6 control-label" style="display: none"><i
                            id="ext-warning-message" class="fa fa-exclamation-triangle" style="display: none"></i></label>

                    <div id="buttonDiv">
                        <div class="form-group">
                            <button type="submit" class="btn-new pull-right"><i
                                    class="glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


