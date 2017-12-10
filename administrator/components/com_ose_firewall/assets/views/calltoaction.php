<div>
    <!--    <div class="panel panel-danger plain toggle" id="jst_4">-->
        <!-- Start .panel -->
        <div class="panel-body">
            <div class="row">
                <div class="subscribe-layer">
                    <h2 class="text-center">
                        <?php oLang::_('CALL_TO_ACTION_P'); ?><br>
                    </h2>

                    <h2 class="text-center">
                        <img style="min-width: 300px;"
                             src="  <?php echo OSE_FWURL ?>/public/images/premium/subscribe-icons.png">
                    </h2>
                    <p class="text-center">
                        <?php oLang::_('CALL_TO_ACTION_P2'); ?>
                    </p>

                    <h2 class="text-center">
                        <button id="subscribe-btn" type="button"
                                onClick="location.href='<?php oLang::_('OSE_OEM_URL_SUBSCRIBE'); ?>'">
                            <i class="im-cart6 mr5"></i> <?php oLang::_('SUBSCRIBE_NOW'); ?>
                        </button>
                    </h2>

                    <br>
                </div>
                <div id="img-layer">
                    <div style="margin-top:-18px; margin-left: 18px;">
                        <h2><?php echo $this->model->showSubTitle(); ?></h2>
                        <p><?php echo $this->model->showSubDesc(); ?></p>
                    </div>
                    <img style="margin-top: -1px; min-width: 900px;" src="<?php echo $this->model->showSubPic(); ?>"
                         alt="Centrora Logo"/>
                </div>
                <div id="content-layer">
                    <div>
                        <h2 class="text-danger"
                            style="text-align: center; font-weight:700; "><?php oLang::_('CALL_TO_ACTION_TITLE2'); ?></h2>
                        <?php oLang::_('CALL_TO_ACTION_UL'); ?>
                    </div>
                </div>
            </div>
            <!--            <div class="row">-->
            <!--                <p class="text-left">-->
            <!--                <h2 class="text-danger">--><?php //oLang::_('CALL_TO_ACTION_TITLE3'); ?><!--</h2>-->
            <!--                --><?php //oLang::_('CALL_TO_ACTION_DECS3'); ?><!--<a-->
            <!--                    href="--><?php //echo OSE_OEM_URL_PREMIUM_TUT; ?><!--"-->
            <!--                    target="_blank">--><?php //oLang::_('O_OUR_TUTORIAL'); ?><!--</a>-->
            <!--                --><?php //oLang::_('O_SUBSCRIBE_PLAN'); ?><!--.-->
            <!--                </p>-->
            <!--            </div>-->
            <div class="subcribe-footer">
                <div>
                    <img style="min-width: 150px;"
                         src="  <?php echo OSE_FWURL ?>/public/images/premium/logo_footer.png">

                    <div id="border-right"></div>
                    <p> <?php oLang::_('CALL_TO_ACTION_DESC2'); ?> </p>
                </div>
            </div>
        </div>
    <!--    </div>-->
</div>