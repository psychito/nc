var url = ajaxurl;
var controller = "aiscan";
var option = "com_ose_firewall";
jQuery(document).ready(function ($) {


    var patternDataTable = $('#patternTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getPatterns';
                d.task = 'getPatterns';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "id"},
            {"data": "patterns"},
            {"data": "type_id"}
        ]
    });

    $('#patternTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });

    $("#new-aiscan-form").submit(function () {
        showLoading('Please wait...');
        var data = $("#new-aiscan-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data, // serializes the form's elements.
            success: function (data) {
                hideLoading();
                if (typeof data.status !== 'undefined' && data.status !== null) {
                    setscanstatus(data.status);
                    //check for next step
                    if (typeof data.status.cont !== 'undefined' && data.status.cont == true) {
                        aiscan();
                    }
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    function setscanstatus(status) {
        $('#last_file').text(status.current_scan);
        $('#status_content').show();
        if (status.progress >= 100) {
            $('#vs_progress').attr({
                "aria-valuenow": 100, style: "width: 100%"
            }).removeClass().addClass("progress-bar progress-bar-success").text(O_SCAN_COMPLETE)
                .parent().removeClass("progress-striped active");
            if (status.total_vs > 0) {
                $('#surfcalltoaction').show();
            }
            $('#last_batch').hide();
        } else {
            $('#vs_progress').attr({
                "aria-valuenow": status.progress, style: "width: " + status.progress + "%"
            }).removeClass("progress-bar-success").text(status.progress + "%")
                .parent().addClass("progress-striped active");
        }
    }
    function aiscan() {
        jQuery(document).ready(function ($) {
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'aiscan_main',
                    task: 'aiscan_main',
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    if (typeof data.status !== 'undefined' && data.status !== null) {
                        setscanstatus(data.status);
                        //check for next step
                        if (typeof data.status.cont !== 'undefined' && data.status.cont == true) {
                            aiscan();
                        } else {
                            aiscan_finish();
                        }
                    }
                }
            });

        });
    }

    function aiscan_finish() {
        jQuery(document).ready(function ($) {
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'aiscan_finish',
                    task: 'aiscan_finish',
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    $('#scan_result').html('<strong>Detected suspicious files</strong>: <br>' + data);
                }
            });

        });
    }

    $("#add-pattern-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var data = $("#add-pattern-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data, // serializes the form's elements.
            success: function (data) {

                if (data === parseInt(data, 10)) {
                    showLoading("Add Success");
                    $('#addPatternModal').modal('hide');

                }
                else {
                    showLoading("Add Fail");
                }
                hideLoading();
                $('#patternTable').dataTable().api().ajax.reload();

            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    //var form = document.getElementById('aiscan-form');
    // var fileSelect = document.getElementById('vssample');
    // var uploadButton = document.getElementById('send-files');
    //
    // form.onsubmit = function(event) {
    //     event.preventDefault();
    //
    //     // Update button text.
    //     uploadButton.innerHTML = 'Uploading...';
    //
    //     // The rest of the code will go here...
    //
    //     // Get the selected files from the input.
    //     var files = fileSelect.files;
    //
    //     // Create a new FormData object.
    //     var formData = new FormData();
    //
    //     // Loop through each of the selected files.
    //     for (var i = 0; i < files.length; i++) {
    //         var file = files[i];
    //
    //         // Check the file type.
    //         //if (!file.type.match('image.*')) {
    //         //    continue;
    //         //}
    //
    //         // Add the file to the request.
    //         formData.append('samples[]', file, file.name);
    //     }
    //
    //     // Strings
    //     formData.append('action', 'aiscan');
    //     formData.append('option', option);
    //     formData.append('task', 'aiscan');
    //     formData.append('controller', controller);
    //     formData.append('centnounce', $('#centnounce').val());
    //     // Set up the request.
    //     var xhr = new XMLHttpRequest();
    //     // Open the connection.
    //     xhr.open('POST', url, true);
    //
    //     // Set up a handler for when the request finishes.
    //     xhr.onload = function () {
    //         if (xhr.status === 200) {
    //             // File(s) uploaded.
    //             uploadButton.innerHTML = 'Upload';
    //         } else {
    //             alert('An error occurred!');
    //         }
    //     };
    //
    //     // Send the Data.
    //     xhr.send(formData);
    // }
    //

});
function addPattern() {
    jQuery(document).ready(function ($) {
        $('#addPatternModal').modal();
    });
}
function confirmDeletePattern() {
    jQuery(document).ready(function ($) {
        ids = $('#patternTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox
                .dialog({
                    message: O_DELETE_CONFIRM_DESC,
                    title: O_CONFIRM,
                    buttons: {
                        success: {
                            label: O_YES,
                            callback: function () {
                                DeletePattern();
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
    })
}
function DeletePattern() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#patternTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        for (index = 0; index < ids.length; ++index) {
            multiids[index] = (ids[index]['id']);
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'deletePattern',
                task: 'deletePattern',
                id: multiids,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                showLoading(O_DELE_SUCCESS_DESC);
                hideLoading();
                $('#patternTable').dataTable().api().ajax.reload();
            }
        });
    })
}
function resetSamples() {
    document.getElementById("vssample").value = "";
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'resetSamples',
                task: 'resetSamples',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {

            }
        });

    });
}
function addToScanResult() {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'addToScanResult',
                task: 'addToScanResult',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
            }
        });

    });
}

function contentScan() {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'contentScan',
                task: 'contentScan',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                $('#aiscanresult').html('<strong>Detected files</strong>: <br>' + data);
            }
        });

    });
}


