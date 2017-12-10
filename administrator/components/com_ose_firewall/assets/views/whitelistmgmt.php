<?php
/**
 * Created by PhpStorm.
 * User: suraj
 * Date: 11/07/2016
 * Time: 8:26 AM
 */
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$result = $this->model->getExistingVariables();
?>
<div id="oseappcontainer">
    <div class="container wrapbody">
        <?php
        $this->model->showLogo ();
        ?>
        <!-- Add Variable Form Modal -->
        <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only"><?php oLang::_('CLOSE');?></span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ADD_ENTITY'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <form id = 'add-variable-form' class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('ENTITY_TYPE');?></label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="entitytype" name ="entitytype">
                                        <option value="VARIABLE"><?php oLang::_('VARIABLES');?></option>
                                        <option value="STRING"><?php oLang::_('STRING');?></option>
                                    </select>
                                </div>
                            </div>
                            <div id="whiteform-rt" class="form-group">
                                <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('REQUEST_TYPE');?></label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="requesttype" name ="requesttype">
                                        <option value="POST"><?php oLang::_('POST');?></option>
                                        <option value="GET"><?php oLang::_('GET');?></option>
                                        <option value="COOKIE"><?php oLang::_('COOKIE');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="whiteform-label3" class="col-sm-4 control-label" for="textfield"><?php oLang::_('ENTITY_NAME');?></label>
                                <div class="col-sm-8">
                                    <input type="text" placeholder="<?php oLang::_('ENTITY_NAME');?>" id="entityname" name="entityname" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="textfield"><?php oLang::_('O_STATUS');?></label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="statusfield" name ="statusfield">
                                        <option value="2"><?php oLang::_('O_SCANVARIABLES');?></option>
                                        <option value="0"><?php oLang::_('O_WHITELISTVARIABLES');?></option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="controller" value="Whitelistmgmt">
                            <input type="hidden" name="action" value="addEntity">
                            <input type="hidden" name="task" value="addEntity">
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
        <div class="content-inner">
            <div class="row ">
                <div class="col-sm-12">
                    <!-- col-lg-12 start here -->
                    <div class="panel panel-primary plain">
                        <!-- Start .panel -->
                        <div class="panel-body wrap-container">
                            <div class="row row-set">
                                <div class="col-sm-3 p-l-r-0">
                                    <div id="c-tag">
                                        <div class="col-sm-12" style="padding-left: 0px;">
                                <span class="tag-title"><?php oLang::_('RULESETS');?></span>
                                        </div>
                                        <p class="tag-content">
<!--                                            --><?php //oLang::_('SAVE');?>
                                            <?php oLang::_('WHITE_LIST_DESC');?></p>
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
                                <div class="title-bar">
                                    <?php oLang::_('CENTRORA_SECURITY_AUTO_SCAN2');?>
                                </div>
<!--                                code to allow the users to add default white list variables -->
                                <?php
                                if($this->model->checkDefaultWhiteListVariablesV7()) {
                                ?>
                                <div id= "addwhitelistvars" class="alert alert-danger">
                                    <div class="false-alert-variables"><?php oLang::_('O_DEFAULT_VARIABLES_WARNING'); ?>
                                        <button class="btn btn-sm mr5 mb10" type="button" onClick="defaultWhiteListVariablesv7()" style="float: right;"><i class="text-success glyphicon glyphicon-ok-sign"></i> <?php oLang::_('O_DEFAULT_VARIABLE_BUTTON'); ?></button>
                                    </div>
                                </div>
                                <?php
                                }
//                                end of code to add default white list variables
                            ?>
                                <div style="padding-bottom: 170px; padding-top: 25px;" class="col-sm-3 bg-transparent-white">
                                    <button data-target="#formModal" data-toggle="modal" class="upload-btns wl-btns"><i class="text-primary glyphicon glyphicon-plus-sign"></i> <?php oLang::_('ADD_A_VARIABLE'); ?></button>
                                    <button class="upload-btns wl-btns" type="button" onClick="changeBatchItemStatus('scan')"><i class="text-block glyphicon glyphicon-minus-sign"></i> <?php oLang::_('SCAN_VARIABLE'); ?></button>
                                    <button class="upload-btns wl-btns" type="button" onClick="changeBatchItemStatus('whitelist')"><i class="text-success glyphicon glyphicon-ok-sign"></i> <?php oLang::_('IGNORE_VARIABLE'); ?></button>
                                    <?php
                                    if (OSE_CMS=='joomla') { //TODO :for joomla
                                        ?>
                                        <button class="upload-btns wl-btns" type="button" onClick="loadDefaultVariables('JOOMLA')"><i class="text-primary glyphicon glyphicon-transfer"></i> <?php oLang::_('LOAD_JOOMLA_DATA'); ?></button>
                                        <button class="upload-btns wl-btns" type="button" onClick="loadDefaultVariables('JOOMLASOCIAL')"><i class="text-primary glyphicon glyphicon-transfer"></i> <?php oLang::_('LOAD_JSOCIAL_DATA'); ?></button>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <button class="upload-btns wl-btns" type="button" onClick="loadDefaultVariables('WORDPRESS')"><i class="text-primary glyphicon glyphicon-transfer"></i> <?php oLang::_('LOAD_DEFAULT_WHITELIST'); ?></button>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($result['status'] == 'SUCCESS')
                                    {
                                        ?>
                                        <button class="upload-btns wl-btns" type="button" onClick="confirmVariableImport()"><i class="glyphicon glyphicon-import"></i> <?php oLang::_('O_IMPORT_VARIABLES'); ?></button>
                                        <?php
                                    }
                                    ?>
                                    <button class="upload-btns wl-btns" type="button" onClick="removeItem('0')"><i class="glyphicon glyphicon-remove-sign"></i> <?php oLang::_('O_DELETE_ITEMS'); ?></button>
                                    <button class="upload-btns wl-btns" type="button" onClick="removeItem('1')"><i class="glyphicon glyphicon-erase"></i> <?php oLang::_('O_DELETE__ALLITEMS'); ?></button>
                                </div>
                                <div style="padding: 0px;" class="col-sm-9">
                                    <table class="table display" id="variablesTable">
                                        <thead>
                                        <tr>
                                            <th><?php oLang::_('O_ID'); ?></th>
                                            <th><?php oLang::_('ENTITY_NAME'); ?></th>
                                            <th><?php oLang::_('ENTITY_TYPE'); ?></th>
                                            <th><?php oLang::_('REQUEST_TYPE'); ?></th>
                                            <th><?php oLang::_('O_STATUS'); ?></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th><?php oLang::_('O_ID'); ?></th>
                                            <th><?php oLang::_('ENTITY_NAME'); ?></th>
                                            <th><?php oLang::_('ENTITY_TYPE'); ?></th>
                                            <th><?php oLang::_('REQUEST_TYPE'); ?></th>
                                            <th><?php oLang::_('O_STATUS'); ?></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

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