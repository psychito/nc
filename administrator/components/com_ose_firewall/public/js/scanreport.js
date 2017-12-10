var controller = "scanreport";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    coreFileCheck();
});
function loadScanResultTable() {
    jQuery(document).ready(function ($) {
        var scanreportDataTable = $('#scanreportTable').dataTable({
            processing: true,
            serverSide: true,
            dom: 'lrtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            //bFilter: false,
            ajax: {
                url: url,
                type: "POST",
                data: function (d) {
                    d.option = option;
                    d.controller = controller;
                    d.action = 'getMalwareMap';
                    d.task = 'getMalwareMap';
                    d.centnounce = $('#centnounce').val();
                }
            },
            columns: [
                {"data": "file_id"},
                {"data": "pattern_id"},
                {"data": "filename"},
                {"data": "checked"},
                {"data": "size","sortable":false},
                {"data": "view","sortable":false},
                {
                    "data": null,
                    "defaultContent": " ",
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
        $('#checkbox').prop('checked', false);
        $('#scanreportTable tbody').on('click', 'tr', function () {
            $(this).toggleClass('selected');
        });
        $('#checkbox').click(function () {
            if ($('#checkbox').is(':checked')) {
                $('#scanreportTable tr').addClass('selected');
            } else {
                $('#scanreportTable tr').removeClass('selected');
            }
        });
        var statusFilter = $('<label id="table-status">Status: <select name="statusFilter" id="statusFilter"><option value=""></option><option value="0 ">No action</option><option value="1">Cleaned</option><option value="2">Quarantined</option></select></label>');
        statusFilter.appendTo($("#scanreportTable_length")).on('change', function () {
            var val = $('#statusFilter');
            scanreportDataTable.api().column(3)
                .search(val.val(), false, false)
                .draw();
            if (val.val() == "2") {
                document.getElementById("btn-new").style.display = 'inline';
            }
            else {
                document.getElementById("btn-new").style.display = 'none';
            }
        });
        $('#filecontentModal').on('hidden.bs.modal', function () {
            $('#scanreportTable').dataTable().api().ajax.reload(null, false);
        });
    })
}


//to stick the buttons on top when scrolling
jQuery(document).ready(function ($) {
    $(window).scroll(stickButtonsToTop);
    stickButtonsToTop();
});

function stickButtonsToTop() {
    jQuery(document).ready(function ($) {

        var window_top = $(window).scrollTop();

        if($('#sticky-anchor').length != 0) {
            var div_top = $('#sticky-anchor').offset().top;
        }

        if (window_top > div_top) {
            $('#report-btngroup').addClass('stick');
            $('#sticky-anchor').height($('#sticky').outerHeight());
        } else {
            $('#report-btngroup').removeClass('stick');
            $('#sticky-anchor').height(0);
        }
    });
}

function batchquarantine() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchqt',
                    task: 'batchqt',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 1) {
                        showLoading(O_QUARANTINE_SUCCESS_DESC);
                        $('#scanreportTable').dataTable().api().ajax.reload(null, false);
                    } else {
                        showLoading(O_QUARANTINE_FAIL_DESC);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                    getVirusStats();
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function batchbkcl() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchbkcl',
                    task: 'batchbkcl',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 'success') {
                        showLoading(O_CLEAN_SUCCESS);
                        $('#scanreportTable').dataTable().api().ajax.reload(null, false);
                    } else {
                        showLoading(O_CLEAN_FAIL);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                    getVirusStats();
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function batchrs() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchrs',
                    task: 'batchrs',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 'success') {
                        showLoading(O_RESTORE_SUCCESS);
                        $('#scanreportTable').dataTable().api().ajax.reload(null, false);
                    } else {
                        showLoading(O_RESTORE_FAIL);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                    getVirusStats();
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function confirmbatchdl() {
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox
                .dialog({
                    message: O_DELETE_CONFIRM_DESC,
                    title: O_CONFIRM,
                    buttons: {
                        success: {
                            label: O_YES,
                            callback: function () {
                                batchdl();
                            }
                        },
                        main: {
                            label: O_NO,
                            callback: function () {
                                this.close();
                            }
                        }
                    }
                });
        } else {
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
        getVirusStats();
    })
}
function batchdl() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        for (index = 0; index < ids.length; ++index) {
            multiids[index] = (ids[index]['file_id']);
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'batchdl',
                task: 'batchdl',
                id: multiids,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_DELE_SUCCESS_DESC);
                } else {
                    showLoading(O_DELE_FAIL_DESC);
                }
                hideLoading();
                $('#checkbox').prop('checked', false);
                $('#scanreportTable').dataTable().api().ajax.reload(null, false);
            }
        });
    })
}
function viewFiledetail(id, status) {
    jQuery(document).ready(function ($) {
        showLoading();
        $('#filecontentModal').modal();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'viewfile',
                task: 'viewfile',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                var newtext = data.data;
                var filename = data.filename;
                var newtext = "<pre>"  + newtext + "</pre>";
                var re = /&lt;span class=&#039;bg-warning&#039;&gt;/img;
                var subst = '<span class=\'bg-warning\'>';
                var result = newtext.replace(re, subst);

                var re1 = /&lt;\/span&gt;/img;
                var subst1 = '</span>';
                var result1 = result.replace(re1, subst1);
                if (status == 0) {
                    var buttons =
                        "<button type='button' class='btn btn-sm' onclick='bkcleanvs(" + id + ", 1" + ")'><i class='text-success glyphicon glyphicon-erase'></i>" + O_CLEAN + "</button>" +
                        "<button type='button' class='btn btn-sm' onclick='quarantinevs(" + id + ", 2" + ")'><i class='text-primary glyphicon glyphicon-alert'></i>" + O_QUARANTINE + "</button>" +
                        "<button type='button' class='btn btn-sm' onclick='markAsClean(" + id + ")'><i class='text-warning glyphicon glyphicon-check'></i>" + O_MARKASCLEAN + "</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>" + O_CLOSE + "</button>";
                } else if (status == 1) {
                    var buttons =
                        "<button type='button' class='btn btn-sm' onclick='restorevs(" + id + ", 0" + ")'><i class='text-success glyphicon glyphicon-retweet'></i>" + O_RESTORE + "</button>" +
                        "<button type='button' class='btn btn-sm' onclick='quarantinevs(" + id + ", 2" + ")'><i class='text-primary glyphicon glyphicon-alert'></i>" + O_QUARANTINE + "</button>" +
                        "<button type='button' class='btn btn-sm' onclick='markAsClean(" + id + ")'><i class='text-warning glyphicon glyphicon-check'></i>" + O_MARKASCLEAN + "</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>" + O_CLOSE + "</button>";
                }
                else {
                    var buttons =
                        "<button type='button' class='btn btn-sm' onclick='restorevs(" + id + ", 0" + ")'><i class='text-success glyphicon glyphicon-retweet'></i>" + O_RESTORE + "</button>" +
                        "<button type='button' class='btn btn-sm' onclick='confirmdeletevs(" + id + ")'><i class='text-danger glyphicon glyphicon-trash'></i>" + O_DELETE + "</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>" + O_CLOSE + "</button>";
                }
                $('#codeareaDiv').html(result1);
                $('#buttonDiv').html(buttons);
                $('#headerpathDiv').html("File Path: " + filename);
            }
        });

    });
}
function restorevs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'restorevs',
                task: 'restorevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 'success') {
                    showLoading(O_RESTORE_SUCCESS);
                    viewFiledetail(id, status);
                }
                else {
                    showLoading(O_RESTORE_FAIL);
                }
                hideLoading();
            }
        })
    })
}
function deletevs(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'deletevs',
                task: 'deletevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_DELE_SUCCESS_DESC);
                    $('#filecontentModal').modal('hide');
                }
                else {
                    showLoading(O_DELE_FAIL_DESC);
                }
                hideLoading();
            }
        })
    })
}
function quarantinevs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'quarantinevs',
                task: 'quarantinevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_QUARANTINE_SUCCESS_DESC);
                    viewFiledetail(id, status);
                } else {
                    showLoading(O_QUARANTINE_FAIL_DESC);
                }
                hideLoading();
            }
        })
    })
}
function bkcleanvs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'bkcleanvs',
                task: 'bkcleanvs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 'success') {
                    showLoading(O_CLEAN_SUCCESS);
                    viewFiledetail(id, status);
                } else {
                    showLoading(O_CLEAN_FAIL);
                }
                hideLoading();
            }
        })
    })
}
function confirmdeletevs(id, status) {
    bootbox
        .dialog({
            message: O_DELETE_CONFIRM_DESC,
            title: O_NOTICE,
            buttons: {
                success: {
                    label: O_YES,
                    callback: function () {
                        deletevs(id, status);
                    }
                },
                main: {
                    label: O_NO,
                    callback: function () {
                        this.close();
                    }
                }
            }
        });
}
function batchMarkAsClean() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'markasclean',
                    task: 'markasclean',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 1) {
                        showLoading(O_MARKASCLEAN_SUCCESS_DESC);
                        $('#scanreportTable').dataTable().api().ajax.reload(null, false);
                    } else {
                        showLoading(O_MARKASCLEAN_FAIL_DESC);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function markAsClean(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'markasclean',
                task: 'markasclean',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_MARKASCLEAN_SUCCESS_DESC);
                } else {
                    showLoading(O_MARKASCLEAN_FAIL_DESC);
                }
            }
        })
    })
}
jQuery(document).ready(function ($) {
    getVirusStats();
});
function getVirusStats() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getVirusStats',
                task: 'getVirusStats',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {

                $('#inf-file').text(data.total);
                $('#qua-file').text(data.quarantined);
                $('#cle-file').text(data.clean);
            }
        })
    })
}

function coreFileCheck() {
    showLoading('initialising database...');
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'coreFileCheck',
                task: 'coreFileCheck',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                loadScanResultTable();
            }
        })
    })
}

//the set up for free users
jQuery(document).ready(function ($) {
    $("#pop_subscription").hide();
    $(".btn-new").prop('disabled', false);
    $("#btn_gopremium").prop('disabled', false);
    $("#btn-fr-user .btn-new").css('opacity', "0.5");
    $("#btn-fr-user").css("opacity","0.9");
    $("#da-fr-user").css("opacity","0.9");

    $("#btn-fr-user .btn-new").click(function() {
        $("#pop_subscription").fadeIn();
    });
    $("#pop_close").click(function() {
        $("#pop_subscription").fadeOut();
    });
});

function  callSubPop(){
    jQuery(document).ready(function ($) {
        $("#pop_subscription").fadeIn();
    });
}