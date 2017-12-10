var url = ajaxurl;
var controller = "mfscan";
var option = "com_ose_firewall";
var isRunning = false;

jQuery(document).ready(function ($) {
    //mfscan(1, 'getLastScanRecord');
    $('#sfsstop').hide();
    $('#scan-result').hide();
    $('#sfsstart').on('click', function () {
        $("#scan-result").hide();
   	 	$('#mfiles-results').fadeOut();
        $('#surfcalltoaction').hide();
        showLoading(O_LOADING_TEXT);
        mfscan(1, 'mfscan');
        isRunning = true ;
        $('#sfsstop').show();
    });
    $('#sfsstop').on('click', function () {
        if (isRunning) {
            isRunning = false;
            showLoading(O_TERMINATE_SCAN);
            location.reload();
        }
    });

    $('#export-results-button').click(function () {
        $('#exportModal_mfscan').modal('hide');
    });

    $('#mod-scanner-ssl').click(function(){
        if($('#mod-scanner-ssl').hasClass('fa-square-o')){
            $('#mod-scanner-ssl').removeClass('fa-square-o');
            $('#mod-scanner-ssl').addClass('fa-check-square-o');
            $('#mod-scanner-ssl').css('margin-right','-7px');
            $('#symlink').prop('checked', true);
        }
        else{
            $('#mod-scanner-ssl').removeClass('fa-check-square-o');
            $('#mod-scanner-ssl').addClass('fa-square-o');
            $('#mod-scanner-ssl').css('margin-right','0px');
            $('#symlink').prop('checked', false);
        }
    });

    $('#selected_file').val(''); //reset on refresh

    $('#save-button').on('click', function () {
        $('#scanPathModal').modal('hide');
        $('#scanpathtext').show();
        $('#selectedfile').text($('#selected_file').val());
    });
    $(function() {
        $( "#datepicker1" ).datepicker({
            defaultDate: "-1d",
            showAnim : "slideDown",
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#datepicker2" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#datepicker2" ).datepicker({
            defaultDate: "+1w",
            showAnim : "slideDown",
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#datepicker1" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
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

function mfscan(step, action) {
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
                startdate: $('#datepicker1').val(),
                enddate: $('#datepicker2').val(),
                symlink: document.getElementById("symlink").checked,
                scanPath: $('#selected_file').val(),
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                //hideLoading();
                ////update virus list
                //if (typeof data.invalid !== 'undefined' && data.invalid !== null) {
                //    showDialogue(data.summary, O_FAIL, O_OK)
                //    isRunning = false;
                //    $('#sfsstop').hide();
                //}
                //if (typeof data.content !== 'undefined' && data.content !== null) {
                //    if (typeof data.content[0] !== 'undefined' && data.content[0] !== null) {
                //        setscanResult(data.content);
                //    }
                //}
                //
                ////update status
                //if (typeof data.status !== 'undefined' && data.status !== null) {
                //    setscanstatus(data.status);
                //
                //    //check for next step
                //    if (typeof data.status.cont !== 'undefined' && data.status.cont == true) {
                //        mfscan(data.status.step, action)
                //    } else {
                //        isRunning = false;
                //        $('#sfsstop').hide();
                //        location.reload();
                //    }
                //}

                ////update scanDate
                //if (typeof data.scanDate !== 'undefined' && data.scanDate !== null) {
                //    $('#scan-date').text(
                //        moment(ko.unwrap(data.scanDate)).startOf('second').from(ko.unwrap(data.serverNow))
                //    ).attr("title", moment(ko.unwrap(data.scanDate)).format('llll'));
                //    $('#scan-date').prepend('Last Scan: ');
                //}



                hideLoading();
                //newly added code to check for conditions :
                if (typeof data !== 'undefined' && data !== null) {
                    if (typeof data.invalid !== 'undefined' && data.invalid !== null) {
                        showDialogue(data.summary, O_FAIL, O_OK)
                    }

                    if (typeof data.status !== 'undefined' && data.status !== null) {
                        if (typeof data.status.type == 'undefined') {
                            setscanstatus(data.status);
                        }
                        if (typeof data.status.cont !== 'undefined' && (data.status.cont == true || data.status.cont == 1)) {
                            mfscan(data.status.step, action)
                        } else {
                            //if(step ==3)
                            //{
                            //    location.reload();
                            //}else{
                                isRunning = false;
                                $('#sfsstop').hide();
                            if (typeof data.longresult !== 'undefined' && data.longresult !== null) {
                                //showDialogue(data.longresult, O_DOWNLOAD_CSV, O_OK)
                                $('#exportModal_mfscan').modal();
                            }else{
                                setscanResult(data.content);

                            }
                        }
                    }
                    if (typeof data.scanDate !== 'undefined' && data.scanDate !== null) {
                        $('#scan-date').text(
                            moment(ko.unwrap(data.scanDate)).startOf('second').from(ko.unwrap(data.serverNow))
                        ).attr("title", moment(ko.unwrap(data.scanDate)).format('llll'));
                        $('#scan-date').prepend('Last Scan: ');
                    }
                }else {
                    showDialogue("There was problem in scanning files, Please refresh the page and try again", 'ERROR', 'CLOSE');
                }


            }
        });
        function setscanResult(results) {
            //$("#scan-result").show();
            //$("#scan-result-panel").html(array.map(function (value) {
            //    return ('<span class="col-md-12"> ' + value + '</span>');
            //}).join(""));

            jQuery(document).ready(function ($) {
                $("#scan-result").show();
                var table =  $('#mfscanResults').dataTable({
                    data : results.data,
                    columns: [
                        { data: "path" ,"width": "60%"},
                        { data: "filesize","width": "20%" },
                        { data: "date","width": "20%" }
                    ]
                });
            });

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

