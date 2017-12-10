var controller = "cronjobs";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    function setTimeDropDown() {
        var CurrentTimezoneOffset = -(new Date().getTimezoneOffset() / 60); // get user tzoffset
        //@todo automatically grab centrora's timezone using jstz or similar
        var UserSverOffset = 11 - CurrentTimezoneOffset; // get time difference(hrs) with centrora servers
        $('#vscanusertime').text('GMT: ' + CurrentTimezoneOffset);
        $('#gitbackupusertime').text('GMT: ' + CurrentTimezoneOffset);
        for (var hr = 0; hr < 24; hr++) {
            var olddate = new Date(2000, 6, 15, hr, 00, 0, 0); // create a temp calculate date of Jun 15/2000, hr:00:00am
            var subbed = new Date(olddate - UserSverOffset * 60 * 60 * 1000); // subtract difference from Centrora Servers hours
            var concat0H = (subbed.getHours() < 10) ? "0" : "";  var concat0M = (subbed.getMinutes() < 10) ? "0" : "";
            var usrhr = (concat0H + subbed.getHours() + ':' + concat0M + subbed.getMinutes());
            var option = $('<option></option>').attr("value", hr).text(usrhr);
            $("#vscancusthours").append(option.clone());
            $("#gitbackupcusthours").append(option.clone());
        }
        //sort
        var my_options = $("#vscancusthours option");
        my_options.sort(function(a,b) {
            if (a.text > b.text) return 1;
            else if (a.text < b.text) return -1;
            else return 0
        });

        var my_options2 = $("#gitbackupcusthours option");
        my_options2.sort(function(a,b) {
            if (a.text > b.text) return 1;
            else if (a.text < b.text) return -1;
            else return 0
        });

        $("#vscancusthours").empty().append( my_options.clone() );
        $("#gitbackupcusthours").empty().append( my_options2.clone());

        $("#vscancusthours").val(+$("#vscansvrusertime").val());
        $("#gitbackupcusthours").val(+$("#gitbackupsvrusertime").val()); // select hour on dropdown set in centrora server
    }

    function setvisualdisabled() {
        if (typeof vscanonoffswitch != 'undefined'){
            $('#vscanweekdays').attr('disabled', !vscanonoffswitch.checked);
            $('#vscancusthours').attr('disabled', !vscanonoffswitch.checked);
        }
    }

    setTimeDropDown();
    setvisualdisabled();
    alterGitCronJobSettings($);
    //enable/disable inputs based on toggle switch
    $('#vscanonoffswitch').on('change', function () {
        $('#vscanenabled').val(this.checked ? 1 : 0);
        setvisualdisabled();
    });
    //enable/disable inputs based on toggle switch

    //enable/disable inputs based on toggle switch
    if($('#gitbackuponoffswitch').length) {
        $('#gitbackuponoffswitch').on('change', function () {
            $('#gitbackupenabled').val(this.checked ? 1 : 0);
            alterGitCronJobSettings($);
        });
    }
    $('#cronjobs-form').submit(function () {
        //enable disabled inputs for submit
        $('input, select').attr('disabled', false);
        showLoading();
        var postdata = $("#cronjobs-form").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        // submit the form
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: postdata,
            success: function (data) {
                if (data.success == true) {
                    if (data.status == 'Error') {
                        hideLoading();
                        showDialogue(data.message, data.status, 'OK');
                    }
                    else {
                        showLoading(data.message);
                        hideLoading();
                    }
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, 'OK');
                }
                setvisualdisabled();
            }
        });
        return false;
    });

    $('#gitbackup-cronjobs-form').submit(function () {
        //enable disabled inputs for submit
        $('input, select').attr('disabled', false);
        showLoading();
        // submit the backup-cronjobs-form
        var postdata = $("#gitbackup-cronjobs-form").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: postdata,
            success: function (data) {
                if (data.success == true) {
                    if (data.status == 'Error') {
                        hideLoading();
                        showDialogue(data.message, data.status, 'OK');
                    }
                    else {
                        showLoading(data.message);
                        hideLoading();
                    }
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, 'OK');
                }
                setvisualdisabled();
            }
        });
        return false;
    });
    $("#setscanpath-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var data = $("#setscanpath-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            success: function (data) {
                hideLoading();
                $('#scanPathModal').modal('hide');
                $("#selected_file2").val($("#selected_file").val());
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });

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


    $('.btn-toggle').click(function() {
        $(this).find('.btn').toggleClass('active');

        if ($(this).find('.btn-primary').size()>0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        $(this).find('.btn').toggleClass('btn-default');
    });



    //new gitbackup cron jobs
});



//
function checkCanRunGitBackup()
{
    showLoading("Checking Git Status  " +
        " <br/>  Please Wait......");
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'canrunCronJob',
                task: 'canrunCronJob',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if(data.status == 4)
                {
                    //can use the gitbackup cron jobs
                    getBackupTypeMenu();
                }else
                {
                    hideLoading();
                    showDialogue(data.info,'UPDATE','CLOSE');
                    //disbale cron jobs
                    disableGitCronJobSettings($);
                }
            }
        });
    });
}

function disableGitCronJobSettings($)
{
    if ($('#gitbackuponoffswitch').length) {
        $('#gitbackuponoffswitch').attr('checked', false);
        $('.onoffswitch').css('pointer-events', 'none');
        $('#gitbackupcusthours').attr('disabled', true);
        $('#gitcloudbackuptype').attr('disabled', true);
        $('#gitbackupweekdays').attr('disabled', true);
        $('#receiveemail').attr('disabled', true);
        $('#gitbackupfrequency').attr('disabled', true);
    }
}




function alterGitCronJobSettings($) {
    if ($('#gitbackuponoffswitch').length) {
        $('#gitbackupcusthours').attr('disabled', !gitbackuponoffswitch.checked);
        $('#gitcloudbackuptype').attr('disabled', !gitbackuponoffswitch.checked);
        $('#gitbackupweekdays').attr('disabled', !gitbackuponoffswitch.checked);
        $('#receiveemail').attr('disabled', !gitbackuponoffswitch.checked);
        $('#gitbackupfrequency').attr('disabled', !gitbackuponoffswitch.checked);
    }
}

function getBackupTypeMenu()
{
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getBackupTypeMenu',
                task: 'getBackupTypeMenu',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if(data.status == 1)
                {
                    $("#gitcloudbackuptype").append(data.info);
                }
            }
        });
    });
}
