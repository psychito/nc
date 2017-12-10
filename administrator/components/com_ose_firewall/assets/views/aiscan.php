<?php
oseFirewall::checkDBReady();
$confArray = $this->model->getConfiguration('vsscan');
$this->model->getNounce();

?>
<div id="oseappcontainer">
    <div class="container">
        <?php
        $this->model->showLogo();
        $this->model->showHeader();
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary plain ">
                    <!-- Start .panel -->
                    <div class="panel-heading">

                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li role="presentation" class="vl-tabs active">
                                <a data-toggle="tab" href="#aiscanner">AI scanner
                                </a>
                            </li>
                            <li role="presentation" class="vl-tabs ">
                                <a data-toggle="tab" href="#patternsdt">Patterns
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="aiscanner" class="tab-pane fade in active">
                                <div class="row">
                                    <div id="scan-window" class="col-md-12">
                                        <div id='scan_progress' class="alert alert-info fade in">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <div class="bg-primary alert-icon">
                                                        <i class="glyphicon glyphicon-info-sign s24"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div id="status_content" class="col-md-12" style="display: none;">
                                                        <div id='status' class='col-md-12'>
                                                            <strong>Status </strong>

                                                            <div class="progress progress-striped active">
                                                                <div id="vs_progress" class="progress-bar"
                                                                     role="progressbar"
                                                                     aria-valuenow="0" aria-valuemin="0"
                                                                     aria-valuemax="100"
                                                                     style="width: 0%">
                                                                    <span id="p4text"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="last_batch" class='col-md-12'>Last Batch:
                                                            <strong id='last_file' class="text-success"></strong>
                                                        </div>

                                                        <div id="scan_result"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-lg-12">
                                    <textarea id="vssample" name="vssample" form="new-aiscan-form" rows="10"
                                              cols="100"><?php $this->model->getSamples(); ?></textarea>
                                    <form id='new-aiscan-form' class="form-horizontal group-border stripped"
                                          role="form">
                                        <input type="hidden" name="option" value="com_ose_firewall"> <input
                                            type="hidden"
                                            name="controller"
                                            value="aiscan">
                                        <input type="hidden" name="action" value="aiscan">
                                        <input type="hidden" name="task" value="aiscan">
                                        <button type="button" onclick="resetSamples()">Reset Samples</button>
                                        <button type="submit" id="send-files">AI Analysis</button>
                                        <button type="button" onclick="contentScan()">Content Analysis</button>
                                    </form>
                                </div>
                                <div id='aiscanresult' class='col-md-12'>&nbsp;</div>
                            </div>

                            <div id="patternsdt" class="tab-pane fade ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-offset-6 col-md-6">
                                                <button class="pull-right btn btn-success btn-sm mr5 mb10" type="button"
                                                        onClick="addPattern()"><i
                                                        class="glyphicon glyphicon-plus-sign"></i>Add Pattern
                                                </button>
                                                <button class="pull-right btn btn-danger btn-sm mr5 mb10" type="button"
                                                        onClick="confirmDeletePattern()"><i
                                                        class="glyphicon glyphicon-erase"></i>Delete Pattern
                                                </button>
                                            </div>
                                            </div>
                                        </div>
                                    <div class="col-md-12">
                                        <table class="table display" id="patternTable">
                                            <thead>
                                            <tr>
                                                <th>Pattern ID</th>
                                                <th>Pattern Name</th>
                                                <th>Pattern Type</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Pattern ID</th>
                                                <th>Pattern Name</th>
                                                <th>Pattern Type</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    </div>
                            </div>
                                </div>
                        </div>
                    </div>
                </div>

        </div>
        </div>
    </div>
<div id='fb-root'></div>

<!-- Form Modal -->
<div class="modal fade" id="addPatternModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Add Pattern</h4>
            </div>
            <div class="modal-body">
                <form id='add-pattern-form' class="form-horizontal group-border stripped" role="form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Pattern</label>

                        <div class="col-sm-9">
                            <input type="text" name="pattern" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Type</label>

                        <div class="col-sm-9">
                            <input type="text" name="type" value="" class="form-control" required>
                        </div>
                    </div>
                    <input type="hidden" name="option" value="com_ose_firewall"> <input type="hidden" name="controller"
                                                                                        value="aiscan"> <input
                        type="hidden" name="action" value="addPattern"> <input
                        type="hidden" name="task" value="addPattern">

            </div>
            <div class="modal-footer">
                    <div class="form-group">
                        <div id="buttonDiv">
                            <button type="submit" class="btn btn-sm"><i
                                    class="text-primary glyphicon glyphicon-save"></i> <?php oLang::_('SAVE'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
<!-- /.modal -->


