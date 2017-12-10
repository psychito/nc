var url = ajaxurl;
var controller = "surfscan";
var option = "com_ose_firewall";
var isRunning = false;

jQuery(document).ready(function ($) {
    surfscan(1, 'getLastScanRecord');
    $('#sfsstart').on('click', function () {
        $("#scan-result").hide();
        isRunning = true;
        $('#sfsstop').show();
        $('#surfcalltoaction').hide();
        showLoading(O_LOADING_TEXT);
        surfscan(1, 'runSurfScan');
    });
    $('#sfsstop').on('click', function () {
        if (isRunning) {
            isRunning = false;
            showLoading(O_TERMINATE_SCAN);
            location.reload();
        }
    });
    $('#updateMD5Sig').on('click', function () {
        runupdateMD5Sig('checkMD5DBUpToDate');
    });
    $('#selected_file').val(''); //reset on refresh
    $('#save-button').on('click', function () {
        $('#scanPathModal').modal('hide');
        $('#scanpathtext').show();
        $('#selectedfile').text($('#selected_file').val());
    });
//file tree stuff
    $('#setscanpath').on('click', function () {
        $('#FileTreeDisplay').html('<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>');
        getfilelist($('#FileTreeDisplay'), '');
        $('#FileTreeDisplay').on('click', 'LI', function () { /* monitor the click event on foldericon */
            var entry = $(this);
            var current = $(this);
            var id = 'id';
            getfiletreedisplay(entry, current, id);
            return false;
        });
        $('#FileTreeDisplay').on('click', 'LI A', function () { /* monitor the click event on links */
            var currentfolder;
            var current = $(this);
            currentfolder = current.attr('id');
            $("#selected_file").val(currentfolder);
            return false;
        });
    });
});

function surfscan(step, action) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: action,
                task: action,
                step: step,
                scanPath: $('#selected_file').val(),
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                //update virus list
                if (typeof data.content !== 'undefined' && data.content !== null) {
                    if (typeof data.content[0] !== 'undefined' && data.content[0] !== null) {
                        setscanResult(data.content);
                    }
                }

                //update status
                if (typeof data.status !== 'undefined' && data.status !== null) {
                    setscanstatus(data.status);

                    //check for next step
                    if (typeof data.status.cont !== 'undefined' && data.status.cont == true) {
                        surfscan(data.status.step, action)
                    } else {
                        isRunning = false;
                        $('#sfsstop').hide();
                    }
                }

                //update scanDate
                if (typeof data.scanDate !== 'undefined' && data.scanDate !== null) {
                    $('#scan-date').text(
                        moment(ko.unwrap(data.scanDate)).startOf('second').from(ko.unwrap(data.serverNow))
                    ).attr("title", moment(ko.unwrap(data.scanDate)).format('llll'));
                    $('#scan-date').prepend('Last Scan: ');
                }
            }
        });

        function setscanResult(array) {
            $("#scan-result").show();
            $("#scan-result-panel").html(array.map(function (value) {
                return ('<span class="col-md-12"><span class="strong">Path:</span> ' + value + '</span>');
            }).join(""));
        }

        function setscanstatus(status) {
            $('#last_file').text(status.current_scan);
            $('#total_number').text(status.total_scan);
            $('#vs_num').text(status.total_vs);
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
    });
}

function runupdateMD5Sig(action) {
    showLoading(O_LOADING_TEXT);
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: action,
                task: action,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                showLoading(data.result);
                if (action == 'checkMD5DBUpToDate')
                {
                    if (data.status ==0) {
                        //need to update the signatures
                        runupdateMD5Sig('updateMD5DB');
                    } else {
                        //signature is already up to date
                        hideLoading();
                        $("#hashstatus").html(data.info);
                        $("#updateinfo").hide();
                        showDialogue(data.info, "UPDATE", "close");
                    }
                }
                else if(action == 'updateMD5DB')
                {
                        hideLoading();
                        if(data.status == 1)
                        {
                            $("#hashstatus").html(data.info2);
                            $("#updateinfo").hide();
                            showDialogue(data.info,O_UPDATE_DIALOG_HEADING,O_CLOSE);
                        }else {
                            showDialogue(data.info,O_ERROR,O_CLOSE);
                        }
                }
            }
        });
    });
}

