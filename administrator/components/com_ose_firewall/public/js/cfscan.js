var url = ajaxurl;
var controller = "cfscan";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    checkUserType_cfscan();
    $('#board').html('');
    $('#tabs').hide();
    $('#cfscan_result').hide();
    $('#customscan').on('click', function () {
        var element = $('#FileTreeDisplay');
        element.html('<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>');
        getfilelist(element, '');
        element.on('click', 'LI', function () { /* monitor the click event on foldericon */
            var entry = $(this);
            var current = $(this);
            var id = 'id';
            getfiletreedisplay(entry, current, id);
            return false;
        });
        element.on('click', 'LI A', function () { /* monitor the click event on links */
            document.getElementById("cms").value = "";
            document.getElementById("version").value = "";
            document.getElementById("save-button").disabled = true;
            $('#board').html('');
            $('#coreFilesDownload').empty();
            var currentfolder;
            var current = $(this);
            currentfolder = current.attr('id');
            $("#selected_file").val(currentfolder);
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'suitePathDetect',
                    task: 'suitePathDetect',
                    scanPath: $('#selected_file').val(),
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    if (data.cms == 'wp') {
                        $('#board').html(data.message);
                        checkCoreFilesExists_suite("wordpress",data.version);
                        document.getElementById("cms").value = "wp";
                        document.getElementById("version").value = data.version;
                    } else if (data.cms == 'jm') {
                        $('#board').html(data.message);
                        checkCoreFilesExists_suite("joomla",data.version);
                        document.getElementById("cms").value = "jm";
                        document.getElementById("version").value = data.version;
                    }
                }
            });
            return false;
        });
    });

    $("#selected_file").bind("change paste keyup", function () {
        document.getElementById("cms").value = "";
        document.getElementById("version").value = "";
        document.getElementById("save-button").disabled = true;
        $('#board').html('');
        $('#coreFilesDownload').empty();
        if (!$(this).val()) {                      //if it is blank.

        } else {
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'suitePathDetect',
                    task: 'suitePathDetect',
                    scanPath: $('#selected_file').val(),
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    if (data.cms == 'wp') {
                        $('#board').html(data.message);
                        checkCoreFilesExists_suite("wordpress",data.version);
                        document.getElementById("cms").value = "wp";
                        document.getElementById("version").value = data.version;
                    } else if (data.cms == 'jm') {
                        $('#board').html(data.message);
                        checkCoreFilesExists_suite("joomla",data.version);
                        document.getElementById("cms").value = "jm";
                        document.getElementById("version").value = data.version;
                    }
                }
            });
        }
    });

    $("#scan-form").submit(function () {
        $('#scanModal').modal('hide');
        showLoading();
        $('#cfscan_result').hide();
        var data = $("#scan-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                hideLoading();
                if(data.cms == "jm")
                {
                    renderSuiteJoomlaResult(data);

                }else{
                    renderSuiteWordpressResult(data);

                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
});

function renderSuiteJoomlaResult(data)
{
    jQuery(document).ready(function ($) {
    if (data.status == 'Completed') {
        $("#missing_btn").on('click', function (){
            $("#modifiedTable_wrapper").hide();
            $('#suspiciousTable_wrapper').hide();
            $('#missingTable_wrapper').show();
        });
        $("#modified_btn").on('click', function (){
            $("#modifiedTable_wrapper").show();
            $('#suspiciousTable_wrapper').hide();
            $('#missingTable_wrapper').hide();
        });
        $("#suspicious_btn").on('click', function (){
            $("#modifiedTable_wrapper").hide();
            $('#suspiciousTable_wrapper').show();
            $('#missingTable_wrapper').hide();
        });
        $('#inf-file').html( data.modified.recordsFiltered);
        $('#qua-file').html( data.suspicious.recordsFiltered);
        $('#cle-file').html( data.missing.recordsFiltered);
        loadMissingFilesTable(data.missing);
        loadSuspiciousFilesTable(data.suspicious);
        loadModifiedFilesTable(data.modified);
        $('#cfscan_result').show();
    } else {
        message = data.summary+"<br/>"+data.details;
        showDialogue(message,"ERROR","CLOSE");
    }
    });
    $('#modified_btn').click();
}
function renderSuiteWordpressResult(data)
{
    jQuery(document).ready(function ($) {
    if (data.status == 'Completed') {
        $('#missing_btn').prop('disabled', true);
        $('#cfscan_result').show();
        $('#missingTable').hide();
        $('#modified_btn').click();
        $('#inf-file').html( data.modified.recordsFiltered);
        $('#qua-file').html( data.suspicious.recordsFiltered);
        $("#modified_btn").on('click', function (){
            $("#modifiedTable_wrapper").show();
            $('#suspiciousTable_wrapper').hide();
        });
        $("#suspicious_btn").on('click', function (){
            $("#modifiedTable_wrapper").hide();
            $('#suspiciousTable_wrapper').show();
        });
        loadSuspiciousFilesTable(data.suspicious);
        loadModifiedFilesTable(data.modified);

    } else {
        message = data.summary+"<br/>"+data.details;
        showDialogue(message,"ERROR","CLOSE");
    }
    });
}

function cfscan() {
    jQuery(document).ready(function ($) {
        $('#cfscan_result').hide();
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'cfscan',
                task: 'cfscan',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (cms == 'wordpress') {
                    if (data.status == 'Completed') {
                        $('#missing_btn').prop('disabled', true);
                        $('#cfscan_result').show();
                        $('#inf-file').html( data.modified.recordsFiltered);
                        $('#qua-file').html( data.suspicious.recordsFiltered);
                        $("#modified_btn").on('click', function (){
                            $("#modifiedTable_wrapper").show();
                            $('#suspiciousTable_wrapper').hide();
                        });
                        $("#suspicious_btn").on('click', function (){
                            $("#modifiedTable_wrapper").hide();
                            $('#suspiciousTable_wrapper').show();
                        });
                        loadSuspiciousFilesTable(data.suspicious);
                        loadModifiedFilesTable(data.modified);
                        $('#modified_btn').click();
                    } else {
                        message = data.summary+"<br/>"+data.details;
                        showDialogue(message,"ERROR","CLOSE");
                    }
                } else {
                    if (data.status == 'Completed') {
                        $("#missing_btn").on('click', function (){
                            $("#modifiedTable_wrapper").hide();
                            $('#suspiciousTable_wrapper').hide();
                            $('#missingTable_wrapper').show();
                        });
                        $("#modified_btn").on('click', function (){
                            $("#modifiedTable_wrapper").show();
                            $('#suspiciousTable_wrapper').hide();
                            $('#missingTable_wrapper').hide();
                        });
                        $("#suspicious_btn").on('click', function (){
                            $("#modifiedTable_wrapper").hide();
                            $('#suspiciousTable_wrapper').show();
                            $('#missingTable_wrapper').hide();
                        });
                        $('#cfscan_result').show();
                        $('#inf-file').html( data.modified.recordsFiltered);
                        $('#qua-file').html( data.suspicious.recordsFiltered);
                        $('#cle-file').html( data.missing.recordsFiltered);
                        loadMissingFilesTable(data.missing);
                        loadSuspiciousFilesTable(data.suspicious);
                        loadModifiedFilesTable(data.modified);
                        $('#modified_btn').click();
                    } else {
                        message = data.summary+"<br/>"+data.details;
                        showDialogue(message,"ERROR","CLOSE");
                    }
                }
            }
        });
    });
}


function loadMissingFilesTable(dataset1)
{
    jQuery(document).ready(function ($) {
        $('#missingTable').dataTable({
            data : dataset1.data,
            columns: [
                { data: "file" ,"width": "80%"},
                { data: "size","width": "20%" }
            ]
        });
    });
}
function loadModifiedFilesTable(dataset1)
{
    jQuery(document).ready(function ($) {
        $('#modifiedTable').dataTable({
            data : dataset1.data,
            columns: [
                { data: "file" ,"width": "80%"},
                { data: "size","width": "20%" }
            ]
        });
    });
}
function loadSuspiciousFilesTable(dataset1)
{
    jQuery(document).ready(function ($) {
        $('#suspiciousTable').dataTable({
            data : dataset1.data,
            columns: [
                { data: "file" ,"width": "80%"},
                { data: "size","width": "20%" }
            ]
        });
    });
}



function toggleChangelist(sitename) {
    jQuery(document).ready(function ($) {
        var changelist = $('#changelist' + sitename);
        var showmenu = $('#btnshowmenu' + sitename);
        if (changelist.hasClass('collapsed')) {
            changelist.slideDown({duration: 300});
            changelist.removeClass('collapsed').addClass('expanded');
            showmenu.attr('title', 'Hide Changelog');
        } else if (changelist.hasClass('expanded')) {
            changelist.slideUp({duration: 300});
            changelist.removeClass('expanded').addClass('collapsed');
            showmenu.attr('title', 'Show Changelog');
        }
    });
}

function catchVirusMD5() {
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'catchVirusMD5',
                task: 'catchVirusMD5',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.status == 'update') {
                    showDialogue(O_VSPATTERN_UPDATE, O_SUCCESS, O_OK);
                } else {
                    showDialogue(O_VSPATTERN_UPDATE_FAIL, O_FAIL, O_OK);
                }
            }
        });
    });
}

function addToAi(id) {
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'addToAi',
                task: 'addToAi',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
            }
        });
    });
}

function checkUserType_cfscan()
{
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'checkUserType',
                task: 'checkUserType',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 1) {
                    checkCoreFilesExists();
                } else {
                    $( "#icon-refresh" ).one( "click", function() {
                        checkCoreFilesExists();
                    });
                    $('#cf-sig').text('Click To Update Core Files');
                    $('#cf-div-uptodate').hide();
                }
            }
        });
    });
}

function checkCoreFilesExists()
{
    jQuery(document).ready(function ($) {
        $('#icon-refresh').addClass('spinAnimation');
        $('#cf-sig').text('Checking Core Files version ...');
        $('#icon-refresh').prop('onclick',null).off('click');
        $('#cf-div-uptodate').hide();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'checkCoreFilesExists',
                task: 'checkCoreFilesExists',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if(data.status == 1)
                {
                    //core files are upto date
                    $('#icon-refresh').removeClass('spinAnimation');
                    $('#icon-refresh').removeAttr("onclick");
                    $("#cf-div-update").hide();
                    $("#cf-div-uptodate").show();
                }else if(data.status == 0){
                    //file does not exists
                    //download the core dir files
                    downloadCoreFiles(data.cms, data.version);
                }else if(data.status == 2){
                    //suite version
                    //do nothing
                }
            }
        });
    });
}

function downloadCoreFiles(cms,version)
{
    jQuery(document).ready(function ($) {
        $('#icon-refresh').prop('onclick',null).off('click');
        $('#cf-sig').text('Downloading latest Core Files ...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'downloadCoreFiles',
                task: 'downloadCoreFiles',
                cms: cms,
                version : version,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading(2500);
                if (data.status == 0) {
                    showDialogue(data.info,"ERROR","CLOSE");
                    //show button and allows user to update manually
                } else {
                    $('#icon-refresh').removeClass('spinAnimation');
                    $('#icon-refresh').removeAttr("onclick");
                    $("#cf-div-update").hide();
                    $("#cf-div-uptodate").show();
                }
            }
        });
    });
}

function checkCoreFilesExists_suite(cms,version)
{
    jQuery(document).ready(function ($) {
        $('#coreFilesDownload').empty();
        $('#coreFilesDownload').append('<span class="glyphicon glyphicon-refresh color-blue animate"></span> Checking Core Files version ...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                cms : cms,
                version : version,
                action: 'checkCoreFilesExistsSuite',
                task: 'checkCoreFilesExistsSuite',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if(data.status == 1)
                {
                    //core files are upto date
                    $('#coreFilesDownload').empty();
                    $('#coreFilesDownload').append('<span class="glyphicon glyphicon-ok color-green"></span> Core Files are upto date');
                    document.getElementById("save-button").disabled = false;
                }else if(data.status == 0){
                    //file does not exists
                    //download the core dir files
                    downloadCoreFiles_suite(data.cms, data.version);
                }
            }
        });
    });
}

function downloadCoreFiles_suite(cms,version)
{
    jQuery(document).ready(function ($) {
        $('#coreFilesDownload').empty();
        $('#coreFilesDownload').append('<span class="glyphicon glyphicon-download color-green"></span> Downloading latest Core Files ...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'downloadCoreFiles',
                task: 'downloadCoreFiles',
                cms: cms,
                version : version,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading(2500);
                if (data.status == 0) {
                    showDialogue(data.info,"ERROR","CLOSE");
                    //show button and allows user to update manually
                } else {
                    $('#coreFilesDownload').empty();
                    $('#coreFilesDownload').append('<span class="glyphicon glyphicon-ok color-green"></span> Core Files are upto date');
                    document.getElementById("save-button").disabled = false;
                }
            }
        });
    });
}