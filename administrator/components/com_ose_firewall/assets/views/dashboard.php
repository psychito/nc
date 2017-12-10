<?php
oseFirewall::checkDBReady();
$this->model->getNounce();
$urls = oseFirewall::getDashboardURLs();
$hasOEMCustomer = CentroraOEM::hasOEMCustomer();
$styleArray = $this->model->getConfiguration('style');
$guideStatus =(!empty($styleArray['data']) && !empty($styleArray['data']['guideStatus']))?$styleArray['data']['guideStatus']:0;
?>
<div>
	<input type="hidden" id="guideStatus" value = "<?php echo $guideStatus; ?>" />
</div>
    <div id="oseappcontainer">
        <div class="container wrapbody">
            <?php
            $this->model->showLogo();
            ?>
            <div class="row">
                <div class="<?php
                $numCol = ($hasOEMCustomer == false) ? 12 : 12;
                echo 'col-md-' . $numCol;
                ?>">
                    <div class="col-sm-12 wrap-container"  style="padding:40px 35px;">
                        <div class="col-sm-12" style="background:white; opacity: 0.7;">
                    <div class="col-md-3">
                        <div class="panel panel-success">
                            <div class="panel-body" id="dashmenu" style="min-height: 300px; margin-left:-45px; ">
                                <a href="javascript:void(0)" id="btn_country"
                                   class="col-md-12"><?php oLang::_('OVERVIEW_COUNTRY_MAP_BTN'); ?></a>
                                <a href="javascript:void(0)" id="btn_traffic"
                                   class="col-md-12"><?php oLang::_('OVERVIEW_TRAFFICS_BTN'); ?></a>
                                <a href="javascript:void(0)" id="btn_recentscan"
                                   class="col-md-12"><?php oLang::_('RECENT_SCANNING_RESULT_BTN'); ?></a>
                                <a href="javascript:void(0)" id="btn_recenthack"
                                   class="col-md-12"><?php oLang::_('RECENT_HACKING_INFO_BTN'); ?></a>
                                <a href="javascript:void(0)" id="btn_backup"
                                   class="col-md-12"><?php oLang::_('RECENT_BACKUP_BTN'); ?></a>
                            </div>
                            <div id="dashboardStyle">
                                Style:<br>
                                <select id="style" value="dynamic">
                                    <option value="dynamic" selected="<?php $dynamic_default; ?>">dynamic</option>
                                    <option value="classic">classic</option>
                                </select>
                                <button id="guide-btn">Guide</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9" id="overview_country" style="display: block;">
                        <div class="bs-component">
                            <div class="panel panel-teal">
                                <div class="panel-heading">
                                    <h3 class="panel-title_visible"
                                        id="map-title"><?php oLang::_('OVERVIEW_COUNTRY_MAP'); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div id="world-map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9" id="overview_traffic" style="display: none;">
                        <div class="bs-component">
                            <div class="panel panel-teal">
                                <div class="panel-heading">
                                    <h3 class="panel-title_visible"><?php oLang::_('OVERVIEW_TRAFFICS'); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div id="traffic-overview" style="width: 100%; height:290px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="col-md-9" id="recent_scan" style="display: none;">
                            <div class="bs-component">
                                <div class="panel panel-teal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title_visible"><a
                                                href="<?php $this->model->getPageUrl('scanResult'); ?>"><?php oLang::_('RECENT_SCANNING_RESULT'); ?></a>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='col-md-8'>Last Scanned:
                                            <strong id='lastScanned' class="text-warning"></strong>
                                        </div>
                                        <div class='col-md-8'>File Scanned:
                                            <strong id='numScanned' class="text-warning"></strong>
                                        </div>
                                        <div class='col-md-8'>Virus Found:
                                            <strong id='numinfected' class="text-danger"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="ipmange-speech-bubble" class="col-md-9" style="display: none;">
                            <div class="bs-component">
                                <div class="panel panel-teal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title_visible"><a
                                                href="<?php $this->model->getPageUrl('ipmanage'); ?>"><?php oLang::_('RECENT_HACKING_INFO'); ?></a>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table display" id="IPsTable">
                                            <thead>
                                            <tr>
                                                <th><?php oLang::_('O_DATE'); ?></th>
                                                <th><?php oLang::_('O_START_IP'); ?></th>
                                                <th><?php oLang::_('O_RISK_SCORE'); ?></th>
                                                <th><?php oLang::_('O_STATUS'); ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9" id="recent_backup" style="display: none;">
                            <div class="bs-component">
                                <div class="panel panel-teal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title_visible"><a
                                                href="<?php $this->model->getPageUrl('backup'); ?>"><?php oLang::_('RECENT_BACKUP_BTN'); ?></a>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        if(!oseFirewallBase::isSuite())
                                        {
                                            ?>
                                        <div class='col-md-8'>
                                            <strong id='lastbackup' class="text-warning"></strong>
                                        </div>
                                        <?php
                                        }else{
                                            ?>
                                        <table class="table display" id="dashBoardRecentBackup">
                                            <thead>
                                            <tr>
                                                <th><?php oLang::_('O_ID'); ?></th>
                                                <th><?php oLang::_('O_ACCOUNTNAME'); ?></th>
                                                <th><?php oLang::_('O_LASTBACKUP_DATE'); ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <?php } ?>
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
    </div>
</div>
</div>
<div id='fb-root'></div>


