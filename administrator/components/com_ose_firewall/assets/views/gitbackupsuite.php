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
$this->model->getNounce();
$proc_open = $this->model->checkProcOpen();
$status = oseFirewallBase::checkSubscriptionStatus(false);
?>
<?php
$flag = $this->model->checkSysteminfo();
$accountpath = null;
?>
<div id = 'accountgtstatus'>
    <div id = 'gitintitialized'>
        <?php
        if ($flag && $proc_open == true) {
            ?>
            <div id = 'committablepage'>
                <div id="oseappcontainer">
                    <div class="container wrapbody">
                        <?php
                        $this->model->showLogo();
                        ?>
                        <!-- Row Start -->
                        <div class="row" style="padding-left: 15px; padding-right: 15px;">
                            <div class="col-md-12 wrap-container">
                                <div class="row row-set">
                                    <div class="col-sm-3 p-l-r-0">
                                        <div id="c-tag" style="height:130px;">
                                            <div class="col-sm-12" style="padding-left: 0px;">
                                        <span class="tag-title" style="height: 130px;"><?php oLang::_('Git_backup_tittle');?><span>
                                            </div>
                                            <p class="tag-content"><?php oLang::_('Git_backup_desc');?></p>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="vs-line-1" style="height:130px;">
                                            <div class="vs-line-1-title" onclick="showAccountsTable()" style="padding-top: 25px;">
                                                <i class="glyphicon glyphicon-triangle-left"></i></div>
                                            <div class="vs-line-1-number" style="font-size: 14px; font-weight: 300;">
                                                <?php oLang::_('BACK_ACC_LIST');?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="screport-line-1" style="padding-left: 25px;">
                                            <div class="title-icon" style="font-size: 45px;"><i class="fa  fa-clock-o"></i></div>
                                            <div class="title-content">
                                                <?php oLang::_('O_LASTBACKUP_DATE');?><br>&nbsp;<span id="backup-time">NONE</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="vs-line-1" style="height:130px;">
                                            <div id = "localBackup" class="vs-line-1-title" onclick="createBackupAllFiles()" style="padding-top: 25px;">
                                                <i class="fa fa-save btn-backup"></i></div>
                                            <div class="vs-line-1-number" style="font-size: 18px; font-weight: 300;">
                                                <?php oLang::_('CREATE_LOCAL_BACKUP');?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2" style="padding-left:10px;">
                                        <div class="vs-line-1" style="height:130px;">
                                            <?php if ($status == true) { ?>
                                                <div id = "cloudPush" onclick="gitCloudBackup()" class="vs-line-1-title" style="padding-top: 25px;">
                                                    <i class="fa fa-cloud-upload btn-backup"></i></div>
                                            <?php } else { ?>
                                                <div id="subscribe-button" class="vs-line-1-title" style="padding-top: 25px;">
                                                   <div id = "cloudPush"> <i class="fa fa-cloud-upload btn-backup"></i></div></div>
                                            <?php } ?>
                                            <div class="vs-line-1-number" style="font-size: 18px; font-weight: 300;">
                                                <?php oLang::_('PUSH_BACKUP_TO_CLOUD');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--                            push and zip-->
                                <input type="hidden" id="push" value= "0"/>
                                <input type="hidden" id="zip"  value = "0"/>
                                <input type="hidden" id="rollback" value= "0"/>
                                <input type="hidden" id="recall"  value = "0"/>
                                <input type="hidden" id="commitid"  value = "0"/>
                                <input type="hidden" id="choice"  value = "0"/>
                                <div id = 'account-name-text' class="title-bar">  <?php oLang::_('O_ACCOUNTNAME');?>:</div>
                                <div id="pop_subscription" class="col-lg-6">
                                    <span><?php oLang::_('PREMIUM_FEATURE');?> &nbsp;<i class="fa fa-certificate"></i></span>
                                    <img id="pop_close" src="<?php echo $this->model->getImgUrl('close_cross.png'); ?>">
                                    <p><?php oLang::_('PREMIUM_FEATURE_INCLUDES');?></p>
                                    <ul class="pop_ul">
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC1');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC2');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC3');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC4');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC5');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC6');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC7');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC8');?></li>
                                        <li><?php oLang::_('PREMIUM_FEATURE_DESC9');?></li>
                                    </ul>
                                    <a  target="_blank" href="https://www.centrora.com/services">
                                        <button id="btn_gopremium"><?php oLang::_('GO_PREMIUM');?></button></a>

                                </div>
                                <!-- Panels Start -->
                                <div class="row row-set" style="padding-right: 20px;">
                                    <table class="table display" id="gitBackupTable">
                                        <thead>
                                        <tr>
                                            <th><?php oLang::_('SR_NO'); ?></th>
                                            <th><?php oLang::_('GIT_DATE'); ?></th>
                                            <th><?php oLang::_('GIT_ID'); ?></th>
                                            <th><?php oLang::_('GIT_MESSAGE'); ?></th>
                                            <th><?php oLang::_('GIT_ROLLBACK'); ?></th>
                                            <th><?php oLang::_('ZIP_DOWNLOAD'); ?></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th><?php oLang::_('SR_NO'); ?></th>
                                            <th><?php oLang::_('GIT_DATE'); ?></th>
                                            <th><?php oLang::_('GIT_ID'); ?></th>
                                            <th><?php oLang::_('GIT_MESSAGE'); ?></th>
                                            <th><?php oLang::_('GIT_ROLLBACK'); ?></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- Panels Ends -->
                                <!-- Panels Ends -->
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
                    <!-- Row Ends -->
                </div>
                <!--    </div>-->
                <div id='fb-root'></div>
                <input type="hidden" id="accountname_session" value= "0"/>
                <input type="hidden" id="accountpath_session" value= "0"/>
            </div>
            <!--                                              form for commit ends-->
        <?php }else { ?>
            <div id="oseappcontainer">
                <div class="container wrapbody">
                    <?php
                    $this->model->showLogo();
                    //                $this->model->showHeader();
                    ?>
                    <!-- Row Start -->
                    <div class="row" style="padding-left: 15px; padding-right: 15px;">
                        <div class="col-md-12 wrap-container">

                            <div class="row row-set" style="padding-right: 18px;">
                                <div class="col-sm-12 goforpremium">
                                    <div class="col-sm-12 st-git-line-1">
                                        <?php oLang::_('GIT_ADVANTAGES_DESC1'); ?><b>4</b>  <?php oLang::_('GIT_ADVANTAGES_DESC2'); ?>
                                    </div>

                                    <div id="carousel-generic" class="col-sm-12 carousel slide" data-ride="carousel">

                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner" role="listbox">
                                            <?php if($proc_open == false) {?>
                                            <div class="item active" style="padding-top: 90px;">
                                                <div class= "st-git-content ">
                                                    <div><b><span class="st-git-line-1"><?php oLang::_('CHECK_SYS_REQUIREMENTS'); ?></span></b></div>
                                                    <div><span class = "glyphicon glyphicon-warning-sign"><?php oLang::_('DISABLED_PROC_OPEN'); ?></span></div>
                                                </div>
                                                <?php if($flag == false){?>
                                                    <div class="git-systemcheck">
                                                        <div class="systeminfosection">
                                                            <div>
                                                                <?php
                                                                $activationpanel = $this->model->getActivationPanel();
                                                                foreach ($activationpanel->systemInfo() as $value) {
                                                                    if ($value['status'] == false) {
                                                                        echo "<ul>";
                                                                        echo "<span class=\"fa fa-times color-red\">";
                                                                        echo " " . $value['info'];
                                                                        echo "</span> </ul>";
                                                                    } else
                                                                        if ($value['status'] == true) {
                                                                            echo "<ul>";
                                                                            echo "<span class=\"fa fa-check color-green\">";
                                                                            echo " " . $value['info'];
                                                                            echo "</span> </ul>";
                                                                        }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                            <div class="item" style="padding-top: 90px;">
                                                <?php }else if($flag == false) { ?>
                                                <div class="item active" style="padding-top: 90px;">
                                                    <div class="git-systemcheck">
                                                        <div class="systeminfosection">
                                                            <div>
                                                                <?php
                                                                $activationpanel = $this->model->getActivationPanel();
                                                                foreach ($activationpanel->systemInfo() as $value) {
                                                                    if ($value['status'] == false) {
                                                                        echo "<ul>";
                                                                        echo "<span class=\"fa fa-times color-red\">";
                                                                        echo " " . $value['info'];
                                                                        echo "</span> </ul>";
                                                                    } else
                                                                        if ($value['status'] == true) {
                                                                            echo "<ul>";
                                                                            echo "<span class=\"fa fa-check color-green\">";
                                                                            echo " " . $value['info'];
                                                                            echo "</span> </ul>";
                                                                        }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item " style="padding-top: 90px;">
                                                    <?php } else {?>
                                                    <div class="item active" style="padding-top: 90px;">
                                                        <?php } ?>
                                                        <div class=" st-git-content">
                                                            <div> <b><?php oLang::_('GIT_ADVANTAGES_DESC3'); ?></b></div>
                                                            <div><?php oLang::_('GIT_ADVANTAGES_DESC4'); ?></div>
                                                        </div>
                                                    </div>

                                                    <div class="item">
                                                        <div class=" st-git-content">
                                                            <div> <b><?php oLang::_('EXAMPLE_DIAGRAM'); ?>:</b></div>
                                                            <div data-example-id="condensed-table" class="bs-example"> <table class="table table-condensed">
                                                                    <thead>
                                                                    <tr><th></th><th></th><th colspan="2"><?php oLang::_('ADD_BACKUP_SIZE'); ?></th></tr>
                                                                    <tr> <th><?php oLang::_('DAY'); ?></th> <th><?php oLang::_('SITE_SIZE'); ?></th> <th><?php oLang::_('TRADITIONAL_METHOD'); ?></th> <th><?php oLang::_('GIT_METHOD'); ?></th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <th scope="row">1</th> <td>100</td> <td>100</td> <td>100</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">2</th> <td>105</td> <td>+105</td> <td>+5</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">3</th> <td>110</td> <td>+110</td> <td>+5</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">4</th> <td>115</td> <td>+115</td> <td>+5</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">5</th> <td>120</td> <td>+120</td> <td>+5</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th scope="row"><?php oLang::_('ACCUMULATION'); ?></th> <td></td> <td>+550</td> <td>+120</td>
                                                                    </tr>

                                                                    </tbody>
                                                                </table> </div>
                                                        </div>
                                                    </div>
                                                    <div class="item" style="padding-top: 90px;">
                                                        <div class=" st-git-content">
                                                            <div> <b><?php oLang::_('GIT_ADVANTAGES_DESC5'); ?></b></div>
                                                            <div><?php oLang::_('GIT_ADVANTAGES_DESC6'); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="item" style="padding-top: 90px;">
                                                        <div class=" st-git-content">
                                                            <div><b><?php oLang::_('GIT_ADVANTAGES_DESC7'); ?></b></div>
                                                            <div><?php oLang::_('GIT_ADVANTAGES_DESC8'); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="item" style="padding-top: 90px;">
                                                        <div class=" st-git-content">
                                                            <div><b><?php oLang::_('GIT_ADVANTAGES_DESC9'); ?></b></div>
                                                            <div><?php oLang::_('GIT_ADVANTAGES_DESC10'); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Indicators -->
                                                <ol class="carousel-indicators">
                                                    <li data-target="#carousel-generic" data-slide-to="0" class="active"></li>
                                                    <li data-target="#carousel-generic" data-slide-to="1"></li>
                                                    <li data-target="#carousel-generic" data-slide-to="2"></li>
                                                    <li data-target="#carousel-generic" data-slide-to="3"></li>
                                                    <li data-target="#carousel-generic" data-slide-to="4"></li>
                                                </ol>
                                                <!-- Controls -->
                                                <a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                    <span class="sr-only"><?php oLang::_('PREVIOUS'); ?></span>
                                                </a>
                                                <a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    <span class="sr-only"><?php oLang::_('NEXT'); ?></span>
                                                </a>
                                            </div>

                                            <div class="col-sm-12"  style="margin:20px 0px 15px 0px;">
                                                <button disabled class = 'btn-new' > <?php oLang::_('DISABLE_ENABLE_GITBACKUP'); ?></button>
                                            </div>
                                            <div class="col-sm-12"  style="margin:20px 0px 15px 0px;">
                                                <button class = 'btn-new' onclick="showAccountsTable()" > <?php oLang::_('BACK_ACC_LIST'); ?></button>
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
                                    <input type="hidden" id="accountname_session" value= "0"/>-->
                                    <input type="hidden" id="accountpath_session" value= "0"/>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>

    <div id = "gitactivationpanel">
        <div id="oseappcontainer">
            <div class="container wrapbody">
                <?php
                $this->model->showLogo();
                //                $this->model->showHeader();
                ?>
                <!-- Row Start -->
                <div class="row" style="padding-left: 15px; padding-right: 15px;">
                    <div class="col-md-12 wrap-container">
                        <div class="row row-set" style="padding-right: 18px;">
                            <div class="col-sm-12 goforpremium">
                                <div class="col-sm-12 st-git-line-1">
                                    <?php oLang::_('GIT_ADVANTAGES_DESC1'); ?> <b>4</b> <?php oLang::_('GIT_ADVANTAGES_DESC2'); ?>
                                </div>
                                <div id="carousel-generic" class="col-sm-12 carousel slide" data-ride="carousel">
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        <?php if($proc_open == false) {?>
                                        <div class="item active" style="padding-top: 90px;">
                                            <div class= "st-git-content ">
                                                <div><b><span class="st-git-line-1"><?php oLang::_('CHECK_SYS_REQUIREMENTS'); ?></span></b></div>
                                                <div><span class = "glyphicon glyphicon-warning-sign"><?php oLang::_('DISABLED_PROC_OPEN'); ?></span></div>
                                            </div>
                                            <?php if($flag == false){?>
                                                <div class="git-systemcheck">
                                                    <div class="systeminfosection">
                                                        <div>
                                                            <?php
                                                            $activationpanel = $this->model->getActivationPanel();
                                                            foreach ($activationpanel->systemInfo() as $value) {
                                                                if ($value['status'] == false) {
                                                                    echo "<ul>";
                                                                    echo "<span class=\"fa fa-times color-red\">";
                                                                    echo " " . $value['info'];
                                                                    echo "</span> </ul>";
                                                                } else
                                                                    if ($value['status'] == true) {
                                                                        echo "<ul>";
                                                                        echo "<span class=\"fa fa-check color-green\">";
                                                                        echo " " . $value['info'];
                                                                        echo "</span> </ul>";
                                                                    }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                                        <div class="item" style="padding-top: 90px;">
                                            <?php }else if($flag == false) { ?>
                                            <div class="item active" style="padding-top: 90px;">
                                                <div class="git-systemcheck">
                                                    <div class="systeminfosection">
                                                        <div>
                                                            <?php
                                                            $activationpanel = $this->model->getActivationPanel();
                                                            foreach ($activationpanel->systemInfo() as $value) {
                                                                if ($value['status'] == false) {
                                                                    echo "<ul>";
                                                                    echo "<span class=\"fa fa-times color-red\">";
                                                                    echo " " . $value['info'];
                                                                    echo "</span> </ul>";
                                                                } else
                                                                    if ($value['status'] == true) {
                                                                        echo "<ul>";
                                                                        echo "<span class=\"fa fa-check color-green\">";
                                                                        echo " " . $value['info'];
                                                                        echo "</span> </ul>";
                                                                    }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item " style="padding-top: 90px;">
                                                <?php } else {?>
                                                <div class="item active" style="padding-top: 90px;">
                                                    <?php } ?>
                                                    <div class=" st-git-content">
                                                        <div> <b><?php oLang::_('GIT_ADVANTAGES_DESC3'); ?> </b></div>
                                                        <div><?php oLang::_('GIT_ADVANTAGES_DESC4'); ?></div>
                                                    </div>
                                                </div>

                                                <div class="item">
                                                    <div class=" st-git-content">
                                                        <div> <b><?php oLang::_('EXAMPLE_DIAGRAM'); ?>:</b></div>
                                                        <div data-example-id="condensed-table" class="bs-example"> <table class="table table-condensed">
                                                                <thead>
                                                                <tr><th></th><th></th><th colspan="2"><?php oLang::_('ADD_BACKUP_SIZE'); ?></th></tr>
                                                                <tr> <th><?php oLang::_('DAY'); ?></th> <th><?php oLang::_('SITE_SIZE'); ?></th> <th><?php oLang::_('TRADITIONAL_METHOD'); ?></th> <th><?php oLang::_('GIT_METHOD'); ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">1</th> <td>100</td> <td>100</td> <td>100</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">2</th> <td>105</td> <td>+105</td> <td>+5</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">3</th> <td>110</td> <td>+110</td> <td>+5</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">4</th> <td>115</td> <td>+115</td> <td>+5</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">5</th> <td>120</td> <td>+120</td> <td>+5</td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row"><?php oLang::_('ACCUMULATION'); ?></th> <td></td> <td>+550</td> <td>+120</td>
                                                                </tr>

                                                                </tbody>
                                                            </table> </div>
                                                    </div>
                                                </div>
                                                <div class="item" style="padding-top: 90px;">
                                                    <div class=" st-git-content">
                                                        <div> <b><?php oLang::_('GIT_ADVANTAGES_DESC5'); ?></b></div>
                                                        <div><?php oLang::_('GIT_ADVANTAGES_DESC6'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="item" style="padding-top: 90px;">
                                                    <div class=" st-git-content">
                                                        <div><b><?php oLang::_('GIT_ADVANTAGES_DESC7'); ?></b></div>
                                                        <div><?php oLang::_('GIT_ADVANTAGES_DESC8'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="item" style="padding-top: 90px;">
                                                    <div class=" st-git-content">
                                                        <div><b><?php oLang::_('GIT_ADVANTAGES_DESC9'); ?></b></div>
                                                        <div><?php oLang::_('GIT_ADVANTAGES_DESC10'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Indicators -->
                                            <ol class="carousel-indicators">
                                                <li data-target="#carousel-generic" data-slide-to="0" class="active"></li>
                                                <li data-target="#carousel-generic" data-slide-to="1"></li>
                                                <li data-target="#carousel-generic" data-slide-to="2"></li>
                                                <li data-target="#carousel-generic" data-slide-to="3"></li>
                                                <li data-target="#carousel-generic" data-slide-to="4"></li>
                                            </ol>
                                            <!-- Controls -->
                                            <a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                <span class="sr-only"><?php oLang::_('PREVIOUS'); ?></span>
                                            </a>
                                            <a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                <span class="sr-only"><?php oLang::_('NEXT'); ?></span>
                                            </a>
                                        </div>

                                        <div class="col-sm-12"  style="margin:20px 0px 15px 0px;">
                                            <?php
                                            if ($flag && $proc_open == true) {
                                                echo "<a class=\"btn-new\" onclick=\"enableGitBackup()\">Enable GitBackup 'Now'</a>";
                                            }else {
                                                ?>
                                                <button disabled class = 'btn-new'  > <?php oLang::_('DISABLE_ENABLE_GITBACKUP'); ?></button>
                                            <?php }?>
                                            <div class="col-sm-12"  style="margin:20px 0px 15px 0px;">
                                                <button class = 'btn-new' onclick="showAccountsTable()" > <?php oLang::_('BACK_ACC_LIST'); ?></button>
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
                                <input type="hidden" id="accountname_session" value= "0"/>
                                <input type="hidden" id="accountpath_session" value= "0"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--                NEW VIEW FOR THE SUITE -->
<!--                Table to show the information for the accounts -->
<div id = 'accountspage'>
    <div id="oseappcontainer">
        <div class="container wrapbody">
            <?php
            $this->model->showLogo();
            ?>
            <!-- Row Start -->
            <div class="row" style="padding-left: 15px; padding-right: 15px;">
                <div class="col-md-12 wrap-container">
                    <div class="row row-set">
                        <div class="col-sm-3 p-l-r-0">
                            <div id="c-tag" style="height:130px;">
                                <div class="col-sm-12" style="padding-left: 0px;">
                    <span class="tag-title" style="height: 130px;"><?php oLang::_('Git_backup_tittle');?><span>
                                </div>
                                <p class="tag-content"><?php oLang::_('Git_backup_desc');?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row row-set" style="padding-right: 20px;">
                        <div class="title-bar"><?php oLang::_('O_MANAGE_BACKUP_ACCOUNTS');?></div>
                        <div class="panel-controls-buttons" style="float:right;">
                            <?php
                            if($flag == false)
                            {?>
                                <button disabled id="indv_accounts" class="btn-new result-btn-set ipmanage-btn-set text-danger" type="button" onClick="display_PrerequisiteInfo()"><i class="glyphicon glyphicon-floppy-disk"></i> <?php oLang::_('O_BACKUP_SELECTED_ACCOUNTS'); ?></button>
                                <?php
                            }else {
                                ?>
                                <button id="indv_accounts" class="btn-new result-btn-set ipmanage-btn-set text-danger" type="button" onClick="prepareBackupAccountsQueue()"><i class="glyphicon glyphicon-floppy-disk"></i> <?php oLang::_('O_BACKUP_SELECTED_ACCOUNTS'); ?></button>
                                <?php
                            } ?>
                        </div>
                        <div class="panel-controls-buttons" style="float:left;">
                            <?php
                            if($flag == false)
                            {?>
                                <button id="all_Accounts" class="btn-new result-btn-set ipmanage-btn-set text-danger" type="button" onClick="display_PrerequisiteInfo()"><i class="glyphicon glyphicon-floppy-disk"></i> <?php oLang::_('O_BACKUP_ALL_ACCOUNTS'); ?></button>
                                <?php
                            }else {
                                ?>
                                <button id="all_Accounts" class="btn-new result-btn-set ipmanage-btn-set text-danger" type="button" onClick="prepareBackupAccountsQueue(1)"><i class="glyphicon glyphicon-floppy-disk"></i> <?php oLang::_('O_BACKUP_ALL_ACCOUNTS'); ?></button>
                                <?php
                            } ?>
                        </div>
                    </div>
                    <div id= 'account-table'>
                        <div class="row row-set" style="padding-right: 20px;">
                            <table class="table display" id="AccountGitBackupTable">
                                <thead>
                                <tr>
                                    <th><?php oLang::_('O_ID'); ?></th>
                                    <th><?php oLang::_('O_ACCOUNTNAME'); ?></th>
                                    <th><?php oLang::_('O_LASTBACKUP_DATE'); ?></th>
                                    <th><?php oLang::_('O_BACKUP_GIT'); ?></th>
                                    <th><?php oLang::_('O_DOWNLOAD_BACKUP'); ?></th>
                                    <th><?php oLang::_('O_UPLOADTOCLOUD'); ?></th>
                                    <th><?php oLang::_('UNINSTALL_GIT'); ?></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th><?php oLang::_('O_ID'); ?></th>
                                    <th><?php oLang::_('O_ACCOUNTNAME'); ?></th>
                                    <th><?php oLang::_('O_LASTBACKUP_DATE'); ?></th>
                                    <th><?php oLang::_('O_BACKUP_GIT'); ?></th>
                                    <th><?php oLang::_('O_DOWNLOAD_BACKUP'); ?></th>
                                    <th><?php oLang::_('O_UPLOADTOCLOUD'); ?></th>
                                    <th><?php oLang::_('UNINSTALL_GIT'); ?></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!--                End of the account table -->
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
        <!-- Row Ends -->
    </div>
    <!--    <div id='fb-root'></div>-->
</div>

<!--        form to enter commit message-->
<div class="modal fade" id="commitMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('COMMIT'); ?></h4>
                <span class = "text-danger"><h6 class="modal-title" id="myModalLabel2"><?php oLang::_('RECOMMENDATION_COMMIT'); ?></h6></span>
            </div>
            <div class="modal-body">
                <form id='commitMessage-form' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('COMMIT_MESSAGE'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" id="message" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="buttonDiv">
                            <div class="form-group">
                                <div id ="errormessage">
                                </div>
                                <button type="submit" class="btn btn-sm" >
                                    <i class="text-primary glyphicon glyphicon-save"></i> <?php oLang::_('SUBMIT_COMMIT_MSG'); ?>
                                </button>
                                <input type="hidden" name="option" value="com_ose_firewall"> <input type="hidden"
                                                                                                    name="controller"
                                                                                                    value="Gitbackupsuite">
                                <input type="hidden" name="action" value="setCommitMessage"> <input
                                    type="hidden" name="task" value="setCommitMessage">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--        FORM TO ACCEPT DETAILS FOR THE DB CONNECTION -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('INSERT_DETAILS'); ?></h4>
            </div>
            <div class="modal-body">
                <form id = 'add-dbconfig-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('0_DB_NAME');?></label>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <input type="text" placeholder="<?php oLang::_('0_DB_NAME');?>" id="dbname" name="dbname" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('0_DB_USER');?></label>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <input type="text" placeholder="<?php oLang::_('0_DB_USER');?>" id="dbuser" name="dbuser" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('0_DB_PASWD');?></label>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <input type="text" placeholder="<?php oLang::_('0_DB_PASWD');?>" id="dbpassword" name="dbpassword" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('0_DB_HOST');?></label>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <input type="text" placeholder="<?php oLang::_('0_DB_HOST');?>" id="host" name="host" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('0_TABLE_PREFIX');?></label>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <input type="text" placeholder="<?php oLang::_('0_TABLE_PREFIX');?>" id="table_prefix" name="table_prefix" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="accountname" value= "0"/>
                    <input type="hidden" id="accountpath" value= "0"/>
                    <input type="hidden" name="controller" value="Gitbackupsuite">
                    <input type="hidden" name="action" value="addDBConfig">
                    <input type="hidden" name="task" value="addDBConfig">
                    <input type="hidden" name="option" value="com_ose_firewall">
                    <div class="col-sm-offset-10">
                        <button type="submit" class="btn-new fw-hover fuv-table-btns" id='save-button'><?php oLang::_('SAVE');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->


<!--Get choices from thr users about the bitbucket account-->
<div class="modal fade" id="bitBucketChoice" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo 'Do You have a BitBucket Account'; ?></h4>
            </div>
            <div class="modal-body">
                <div id="create_account">
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm">
                            <a class="create-repos btn-xs" href="https://bitbucket.org/" target="_blank"> <i class="text-primary glyphicon glyphicon-save"></i> <?php echo "I will like to create an Account!"; ?></a>
                        </button>
                    </div>
                </div>
                <div id="have_account">
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm">
                            <i class="text-primary glyphicon glyphicon-save"></i> <?php echo " I already have an Account! " ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <h6>Note : Username and Password of the Bitbucket account are required to use the Cloud Backup Services</h6>
            </div>
        </div>
    </div>
</div>
<!--choise form for the bitbucket account ends -->



<!--New modal for the GitLab-->
<!--confirm if the user wants to create a new account on gitlab or has an existing account -->
<div class="modal fade" id="gitLabChoice" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo 'Do You Have a GitLab Account'; ?></h4>
            </div>
            <div class="modal-body">
                <div id="gitLabChoice_option_createaccount">
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm">I will like to create an Account!
                        </button>
                    </div>
                </div>
                <div id="gitLabChoice_option_haveaccount">
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm">
                            <i class="text-primary glyphicon glyphicon-user"></i> <?php echo " I already have an Account! " ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <h6>Note : Username and Access Token of the GitLab account are required to use the Cloud Backup Services</h6>
            </div>
        </div>
    </div>
</div>
<!--Gitlab Modal ends -->



<!--form to display the information for creating a nw gitlkab account -->
<div class="modal fade" id="gitLab_createaccount" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo 'Instructions for Creating a new GitLab Account'; ?></h4>
            </div>
            <div class="modal-body">
                <div id="create_account">
                    <div class="form-group">
                        <h5><b>Please Follow the instructions below to create a new GitLab Account</b></h5>
                        <ol>
                            <li> Head to <a href="https://gitlab.com/users/sign_in" target="_blank">GitLab Create New Account</a> and sign up for a new account</li>
                            <li>Access the private Token :
                                    Click on the top-right corner of the screen >Settings > Account and copy the Private Tokens
                                        <span class="alert-danger">Please save this token as it will be needed by centrora plugin</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm" id ="gitLab_option_haveaccount">
                    <i class="text-primary"></i>I have the Access Token
                </button>
            </div>
        </div>
    </div>
</div>




<!--modal to show if the user has a gitlab account -->
<!--allow user to enter the token -->
<div class="modal fade" id="gitLabModal_suite" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('O_GITLAB_DETAILS'); ?></h4>
            </div>
            <div class="modal-body">
                <form id='remoteGit-form_suite' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('GITCREMOTE_USERNAME'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" name="username" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php oLang::_('O_ACCESS_TOKEN'); ?></label>
                        <div class="col-sm-8">
                            <input type="accesstoken" name="accesstoken" value="" class="form-control" required>
                        </div>
                    </div>

                    <input type="hidden" name="option" value="com_ose_firewall"> <input type="hidden"
                                                                                        name="controller"
                                                                                        value="Gitbackupsuite">
                    <input type="hidden" name="action" value="saveRemoteGit"> <input
                        type="hidden" name="task" value="saveRemoteGit">

            </div>
            <div class="modal-footer">
                <div id="buttonDiv">
                    <div class="form-group">
                        <a class="create-repos btn-xs" href="https://gitlab.com/users/sign_in" target="_blank"><?php oLang::_('CREATE_ACCOUNT_FOR_FREE_GITLAB'); ?></a>
                        <button type="submit" class="btn btn-sm">
                            <i class="text-primary glyphicon glyphicon-save"></i> <?php oLang::_('CREATE_REPOSITORY_GITLAB'); ?>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


<!--modal top enter gitlab details ends -->
