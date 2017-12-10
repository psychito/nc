var url = ajaxurl; 
var controller = "vsscan";
var option = "com_ose_firewall";
var contScan = true;
var currentProcess = 0;
var currentSize = 1;
jQuery(document).ready(function($){
    //hide up to date virus signature
    $("#vs-div-uptodate").hide();

	//get object with colros from plugin and store it.
    checkUserType();
    colours = $('body').data('appStart').getColors();
    var sizes = $('body').data('appStart').getSizes();
    $("#scan_progress").hide();
    //hide progressing bar
    $("#progressingbar").hide();
    $('#vsscan-stop').hide();
    $('#vsscan-fresh').hide();
    $('#vsscan-cont').hide();
    //hide result div s
    $('#scan-result-green').hide();
    $('#scan-result-red').hide();
	$('#vsscan').on('click', function() {
        //disable the scan specific button
        $("#customscan").prop('disabled',true);
        $("#customscan").css('opacity','0.2');
        showScanProgress();

        showLoading ();
        document.getElementById("vsscan").disabled = true;
        scanAntivirus(-1, 'vsscan', colours);
        //hide the select scan option bar and show progressing bar
        $("#selectoptionbar").hide();
        $("#progressingbar").show();
	});

    //$('#vsscan').removeAttr("onclick");
    //$('#vsscan').prop('onclick',null).off('click');

    $('#vsscan-fresh').on('click', function() {
        showLoading ('Page refreshing');
        location.reload();
    });

	$('#vsscan-stop').on('click', function() {
		//showLoading ('Terminating scanning process');
        $('#vsscan-stop').hide();
        $('#vsscan-cont').show();
        contScan = false;
        hideLoading();
        document.getElementById("vsscan").disabled = true;
        //location.reload();
	});
    $('#vsscan-cont').on('click', function () {
        //showLoading('Continue Scanning');
        document.getElementById("vsscan").disabled = true;
        var checkedValue = [];
        var inputElements = document.getElementsByClassName('messageCheckbox');
        var j = 0;
        for (var i = 0; inputElements[i]; ++i) {
            if (inputElements[i].checked) {
                checkedValue[j] = inputElements[i].value;
                j++;
            }
        }
        contScan = true;
        $("#scan-result").hide();
        $('#vsscan-cont').hide();
        $('#vsscan-stop').show();
        $('#statusresult').hide();
        $("#scan_progress").show();
        scanVirusIndCon('vsscan', checkedValue, colours, 'continue');

    });
    $('#vscont').on('click', function () {
        showLoading('Continue Scanning');
        document.getElementById("vsscan").disabled = true;
        var checkedValue = [];
        var inputElements = document.getElementsByClassName('messageCheckbox');
        var j = 0;
        for (var i = 0; inputElements[i]; ++i) {
            if (inputElements[i].checked) {
                checkedValue[j] = inputElements[i].value;
                j++;
            }
        }
        contScan = true;
        $("#scan-result").hide();
        $('#vscont').hide();
        $('#vsstop').show();
        $('#statusresult').hide();
        $("#scan_progress").show();
        scanVirusIndCon('vsscan', checkedValue, colours, 'continue');
    });

    //$('#vs-div-update').on('click', function () {
    //    checkVirusSignatureVersion();
    //});
});

function showScanProgress(){
    jQuery(document).ready(function($) {
        $("#scan_progress").show();
        $("#scan-result").hide();
        $("#scanpathtext").hide();
        $('#statusresult').hide();
        $('#vsscan-stop').show();
        $('#vsscan-fresh').show();
        $('#vsscan').hide();
    });
}

jQuery(document).ready(function ($) {
    $("#scan-form").submit(function () {
        //disable the scan specific button
        jQuery("#customscan").prop('disabled',false);
        $("#customscan").css('opacity','0.8');

        //hide the select scan option bar and show progressing bar
        $("#selectoptionbar").hide();
        $("#progressingbar").show();
        showScanProgress();

    	$("#scan_progress").show();
    	$('#scanpathtext').show();
        $('#selectedfile').text($('#selected_file').val());
        $('#scanModal').modal('hide');
        $('#vsstop').show();
        $('#vscont').hide();
        var checkedValue = [];
        var inputElements = document.getElementsByClassName('messageCheckbox');
        var j = 0;
        for (var i = 0; inputElements[i]; ++i) {
            if (inputElements[i].checked) {
                checkedValue[j] = inputElements[i].value;
                j++;
            }
        }
        showLoading();
        var data = $("#scan-form").serialize();
        data += '&type=' + checkedValue;
        data += '&centnounce=' + $('#centnounce').val();
        xhr = $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                hideLoading();
                setProgressBar(data);
                $('#last_file').html(data.last_file);
                currentSize = data.size;
                runAllScanAntivirus('vsscan', colours, data.size, 0);
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
});
var initPlotChart = function ($, data, cpu, colours) {
	if (cpu =='')
	{
		cpu = false;
	}
	//define chart colours
    var chartColours = [colours.linechart1, colours.linechart2, colours.linechart3, colours.linechart4, colours.linechart5, colours.linechart6, colours.linechart7];
	var options = {
			grid: {
				show: true,
			    aboveData: true,
                color: colours.black,
			    labelMargin: 15,
                axisMargin: 0,
			    borderWidth: 0,
			    borderColor:null,
			    minBorderMargin: 0,
			    clickable: true, 
			    hoverable: true,
			    autoHighlight: true,
			    mouseActiveRadius: 20
			},
	        series: {
	        	grow: {active:false},
	            lines: {
            		show: true,
            		fill: true,
            		lineWidth: 2,
            		steps: false
	            	},
	            points: {
	            	show:true,
	            	radius: 4,
	            	symbol: "circle",
	            	fill: true,
                    borderColor: colours.white
	            }
	        },
        yaxis: {min: 0},
        xaxis: {min: 0},
	        legend: { position: "se" },
	        colors: chartColours,
	        shadowSize:1,
	        tooltip: true, //activate tooltip
			tooltipOpts: {
				content: "%s : %y.0",
				shifts: {
					x: -30,
					y: -50
				}
			}
	};  
	if (cpu == true)
	{
		var plot1 = $.plot($("#line-chart-cpu"),
			    [{
			    		label: "CPU Load",
			    		data: data,
                    lines: {fillColor: colours.cream},
                    points: {fillColor: colours.linechart1}
			    }], options);
	}
	else
	{
		var plot2 = $.plot($("#line-chart-memory"),
			    [{
			    		label: "Memory Usage",
			    		data: data,
                    lines: {fillColor: colours.cream},
                    points: {fillColor: colours.linechart1}
			    }], options);
	}

};

var initPieChartPage = function($, lineWidth, size, animateTime, colours) {
	$(".easy-pie-chart").easyPieChart({
        barColor: colours.dark,
        borderColor: colours.dark,
        trackColor: colours.piedark,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-red").easyPieChart({
        barColor: colours.red,
        borderColor: colours.red,
        trackColor: colours.piered,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-green").easyPieChart({
        barColor: colours.green,
        borderColor: colours.green,
        trackColor: colours.piegreen,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-blue").easyPieChart({
        barColor: colours.blue,
        borderColor: colours.blue,
        trackColor: colours.pieblue,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-teal").easyPieChart({
        barColor: colours.teal,
        borderColor: colours.teal,
        trackColor: colours.pieteal,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-purple").easyPieChart({
        barColor: colours.purple,
        borderColor: colours.purple,
        trackColor: colours.piepurple,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-orange").easyPieChart({
        barColor: colours.orange,
        borderColor: colours.orange,
        trackColor: colours.pieorange,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-lime").easyPieChart({
        barColor: colours.lime,
        borderColor: colours.lime,
        trackColor: colours.pielime,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
};

function scanAntivirus(step, action, colours) {
    var checkedValue = [];
    var inputElements = document.getElementsByClassName('messageCheckbox');
    var j = 0;
    for (var i = 0; inputElements[i]; ++i) {
        if (inputElements[i].checked) {
            checkedValue[j] = inputElements[i].value;
            j++;
        }
    }
    checkedValue = checkedValue.toString();
	jQuery(document).ready(function($){
        $('#vsstop').show();
        xhr = $.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option,
                controller: controller,
                action: action,
                task: action,
                type: checkedValue,
		    		step : step,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
                setProgressBar(data);
                $('#last_file').html(data.last_file);
                if ((step == -1 && data.cont == true))
	        	{
                    currentSize = data.size;
                    runAllScanAntivirus(action, colours, data.size, 0);
	        	}
	        }
	      });
	});
}

function runAllScanAntivirus(action, colours, size, process) {
    var s1 = scanVirusInd(action, 1, colours, size, process);
    //var s2 = scanVirusInd(action, cpuData, memData, 2, colours, size, process);
    //var s3 = scanVirusInd(action, cpuData, memData, 3, colours, size, process);
    //var s4 = scanVirusInd(action, cpuData, memData, 4, colours, size, process);
    //var s5 = scanVirusInd(action, cpuData, memData, 5, colours, size, process);
    //var s6 = scanVirusInd(action, cpuData, memData, 6, colours, size, process);
    //var s7 = scanVirusInd(action, cpuData, memData, 7, colours, size, process);
    //var s8 = scanVirusInd(action, cpuData, memData, 8, colours, size, process);
    ////jQuery(document).ready(function($){
    //	$.when(s1, s2, s3, s4, s5, s6, s7, s8).then(
    //		function ( v1, v2, v3, v4, v5, v6, v7, v8 ) {
    //	});
    //});
}

function scanVirusInd(action, type, colours, size, process) {
	jQuery(document).ready(function($){
        xhr = $.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
                option: option,
                controller: controller,
                action: action,
                task: action,
                process: process,
                size: size,
                step: 0,
                type: type,
                centnounce: $('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
                setProgressBar(data);
                $('#last_file').html(data.last_file);
                if (size <= process) {
                    return true;
                } else {
                    currentProcess = process + 1;
                    if (contScan) {
                        scanVirusInd(action, type, colours, size, currentProcess);
                    }
                }
            },
		    error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (size > process) {
                    scanVirusInd(action, type, colours, size, process + 1);
                }
		    }
	      });
	});
}

function scanVirusIndCon(action, type, colours, continueType) {
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
                cont: continueType,
                step: 0,
                type: type,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                setProgressBar(data);
                $('#last_file').html(data.last_file);
                if (data.size == data.process) {
                    return true;
                } else {
                    currentProcess = data.process;
                    if (contScan) {
                        scanVirusInd(action, type, colours, data.size, currentProcess);
                    }
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                scanVirusIndCon(action, type, colours, continueType);
            }
        });
    });
}

function setProgressBar(status) {

    jQuery("#scan-result").show();
    if (status.completed >= 100) {
        //change refresh button size
        jQuery('#vsscan-fresh').css("width", "100%");
        jQuery('#vsscan-stop').hide();
        jQuery("#customscan").prop('disabled',false);
        jQuery("#customscan").css('opacity','0.8');
        jQuery('#vs_progress').attr({
            "aria-valuenow" : 100, style:"width: 100%"
        }).removeClass().addClass("progress-bar progress-bar-success").text(O_SCAN_COMPLETE)
            .parent().removeClass("progress-striped active").show();
        jQuery('#percentage-box').text(status.completed + "%");
        if (status.totalvs > 0){
            jQuery("#statusresult").html("");
            if (cms == 'wordpress') {
                //jQuery("<a href='admin.php?page=ose_fw_scanreport'>View Result</a>").appendTo("#statusresult");
                jQuery('#scan-result-red').show();
                getVirusCount();
            } else {
                //jQuery("<a href='index.php?option=com_ose_firewall&view=vsreport'> View Result</a>").appendTo("#statusresult");
                jQuery('#scan-result-red').show();
            }
            jQuery('#statusresult').removeClass("alert-success").addClass("alert-danger").show();
        } else {
            //jQuery('#statusresult').text(O_SCAN_COMPLETE + ': ' + O_CLEAN)
            //    .removeClass("alert-danger").addClass("alert-success").show();
            //jQuery("<a href='admin.php?page=ose_fw_scanreport'>Complete with no errors</a>").appendTo("#statusresult");
            jQuery('#scan-result-green').fadeIn();
        }
        if (status.missingPHP != 'notExist') {
            $('#missingPHP').html('These php files can not be scanned due to permission issue or exceed maximum excution time, please manually check them </br>' + status.missingPHP);
            $('#missingPHP').show();
        }
        jQuery('#vsstop').hide();

    } else if (status.completed < 100) {
        jQuery('#vs_progress').attr({
            "aria-valuenow": status.completed, style: "width: " + status.completed + "%"
        }).show().removeClass("progress-bar-success")
            .parent().addClass("progress-striped active").show();
    jQuery('#percentage-box').text(status.completed + "%")
    }
    jQuery('#timeUsed').html(status.timeUsed);
    jQuery('#cpu').html(status.cpuload);
    jQuery('#memory').html(status.memory);
    jQuery('#numScanned').html(status.totalscan);
    jQuery('#numinfected').html(status.totalvs);
}

function downloadSQL(type, downloadKey, version) {
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'downloadSQL',
                task: 'downloadSQL',
                type: type,
                downloadKey: downloadKey,
                version: version,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                //showLoading(data.result);
                //hideLoading();
                if (data.status == "ERROR"){
                    showDialogue(O_VSPATTERN_UPDATE_FAIL, O_FAIL, O_OK);
                } else {
                    // -stop animation
                    updateVirusSignatureVersionFile();
                    $('#icon-refresh').removeClass('spinAnimation');
                    showDialogue(O_VSPATTERN_UPDATE, O_SUCCESS, O_OK);
                }
                }
        });
    });
}
function validateEmail(email){
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if(filter.test(email)){
        return true;
    }
    else {
        return false;
    }
}

jQuery(document).ready(function($) {

    $('#start-bg-button').on('click', function () {
        var email = document.getElementById("input-email").value;
        if(validateEmail(email)){
            storeUserEmail(email);
            getUserEmail(email);
        }
        else {

            $('#email-content').css({'color':'#de482f','font-size':'12px'});
            $('#email-content').text('Please enter a validated email address to ensure our scan report would not be homeless :)')
        }

    });
});

function storeUserEmail(email) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'storeUserEmail',
                task: 'storeUserEmail',
                email: email,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 1) {
                    alert("the email has been stored successfuly ")
                }
                else {
                    alert(data.info);
                }
            }
        });
    });
}

function getUserEmail() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getUserEmail',
                task: 'getUserEmail',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data) {
                    alert(data.email);
                }
                else {
                    alert("There was some problem in retieving email address");
                }
            }
        });
    });
}


jQuery(document).ready(function($){
    $('#customscan').on('click',function(){
        showScanProgress();

        var element = $('#FileTreeDisplay');
        element.html( '<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>');
        getfilelist( element  , '' );
        element.on('click', 'LI', function() { /* monitor the click event on foldericon */
            var entry = $(this);
            var current = $(this);
            var id = 'id';
            getfiletreedisplay (entry, current, id);
            return false;
        });
        element.on('click', 'LI A', function() { /* monitor the click event on links */
            var currentfolder;
            var current = $(this);
            currentfolder = current.attr('id');
            $("#selected_file").val(currentfolder) ;
            return false;
        });

    });

    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {
            option : option,
            controller:controller,
            action:'getLastScanRecord',
            task:'getLastScanRecord',
            centnounce:$('#centnounce').val()
        },
        success: function(data)
        {
            if(typeof data.scanDate !== 'undefined' && data.scanDate !== null) {
                $('#time-bar').text(
                    "Last Scan : " + moment(ko.unwrap(data.scanDate)).startOf('second').from(ko.unwrap(data.serverNow))
                ).attr("title", moment(ko.unwrap(data.scanDate)).format('llll'));
            }
        }
    });
});

function resetNessesary ()
{
    jQuery(document).ready(function($){

    });
}

function unSelectBox (id) {
    jQuery(document).ready(function($){
        $('#'+id).prop('checked', false);
        $('#'+id +'-box').css("background-color","rgba(92, 84, 77, 0.6)");
    });
}

function selectBox (id) {
    jQuery(document).ready(function($){
        $('#'+id).prop('checked', true);
        $('#'+id +'-box').css("background-color","rgba(92, 84, 77, 0.8)");
    });
}

//add functions for checkbox button
function uncheckAllBoxes(){
    jQuery(document).ready(function($){
        unSelectBox ('shell-codes');
        unSelectBox ('base-encode');
        unSelectBox ('javascript-injection');
        unSelectBox ('php-injection');
        unSelectBox ('frame-injection');
        unSelectBox ('spamming');
        unSelectBox ('execute');
        unSelectBox ('other-mmc');
    });
}

function enableallBoxes(){
    jQuery(document).ready(function($){
        selectBox ('shell-codes');
        selectBox ('base-encode');
        selectBox ('javascript-injection');
        selectBox ('php-injection');
        selectBox ('frame-injection');
        selectBox ('spamming');
        selectBox ('execute');
        selectBox ('other-mmc');
    });
}

jQuery(document).ready(function($){
    selectBox ('base-encode');
    selectBox ('php-injection');


    $("#btn-full-scan").click(function() {
        uncheckAllBoxes();
        enableallBoxes();
        unSelectBox ('other-mmc');
        //add focus effects
        $("#btn-full-scan").addClass("btn-hover-effects");
        $("#btn-deep-scan").removeClass("btn-hover-effects");
        $("#btn-quick-scan").removeClass("btn-hover-effects");
    });

    $("#btn-quick-scan").click(function() {
        uncheckAllBoxes();
        selectBox ('base-encode');
        selectBox ('php-injection');
        //add focus effects
        $("#btn-quick-scan").addClass("btn-hover-effects");
        $("#btn-full-scan").removeClass("btn-hover-effects");
        $("#btn-deep-scan").removeClass("btn-hover-effects");
    });

    $("#btn-deep-scan").click(function() {
        uncheckAllBoxes();
        enableallBoxes();
        //add focus effects
        $("#btn-deep-scan").addClass("btn-hover-effects");
        $("#btn-full-scan").removeClass("btn-hover-effects");
        $("#btn-quick-scan").removeClass("btn-hover-effects");
    });

});

function boxColorChange(id) {
    jQuery(document).ready(function ($) {

        $('#'+id).on('change',function(){
            if ($('#'+id).prop('checked')){
                var newId = id + '-box';
                $('#'+newId).css("background-color","rgba(92, 84, 77, 0.8)");
            }
            else{
                var newId = id + '-box';
                $('#'+newId).css("background-color","rgba(92, 84, 77, 0.6)");
            }
        });

    });
}


function getVirusCount()
{
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getVirusCount',
                task: 'getVirusCount',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.usertype == false) {
                    //alert(data.viruscount);
                    bootbox.dialog({
                        message: "The scanner has detected " + data.viruscount + " viruses",
                        title: "Scanning Result",
                        buttons: {
                            main: {
                                label: "Close",
                                className: "btn-primary",
                                callback: function () {
                                    window.close();
                                }
                            }
                        }
                    });
                }
            }
        });
    });
}

jQuery(document).ready(function ($) {
    $("#close-green").click(function() {
        $("#scan-result-green").fadeOut();
    });
    $("#close-red").click(function() {
        $("#scan-result-red").fadeOut();
    });
});

/*
Updated mechanism of updating the virus patterns
 */

function checkPatternVersion()
{
    jQuery(document).ready(function ($) {
        $('#icon-refresh').addClass('spinAnimation');
        $('#v-sig').text('Checking virus signature version ...');
        $('#icon-refresh').prop('onclick',null).off('click');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'checkPatternVersion',
                task: 'checkPatternVersion',
                type : 'avs',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 2) {
                    //show icon and allow user to uodatfe the firewall rules
                    enableManualUpdate($);
                    showDialogue(data.info,'ERROR','CLOSE');
                } else if(data.status == 0){
                    downloadRequest('avs');
                }else if(data.status==1)
                {
                    $('#icon-refresh').removeClass('spinAnimation');
                    $('#icon-refresh').removeAttr("onclick");
                    $("#vs-div-update").hide();
                    $("#vs-div-uptodate").show();
                    getVirusSignatureVersionFromFile();
                }
            }
        });
    });
}
function downloadRequest(type) {
    jQuery(document).ready(function ($) {
        $('#icon-refresh').prop('onclick',null).off('click');
        $('#v-sig').text('Downloading latest Virus Signature ...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'downloadRequest',
                task: 'downloadRequest',
                type: type,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading(2500);
                if (data.status == 0) {
                    enableManualUpdate($);
                    showDialogue(data.info);
                    //show button and allows user to update manually
                } else {
                    installPatterns(type);
                }
            }
        });
    });
}

function installPatterns(type)
{
    jQuery(document).ready(function ($) {
        $('#icon-refresh').prop('onclick',null).off('click');
        $('#v-sig').text('Installing latest Virus Signature ...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'installPatterns',
                task: 'installPatterns',
                type: type,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading(2500);
                if (data.status == 0) {
                    enableManualUpdate($);
                    showDialogue(data.info);
                } else {
                    $('#icon-refresh').removeClass('spinAnimation');
                    $("#vs-div-update").hide();
                    $("#vs-div-uptodate").show();
                    getVirusSignatureVersionFromFile();
                }
            }
        });
    });
}

function getVirusSignatureVersionFromFile(){
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'advancerulesets',
                action: 'getDatefromVirusCheckFile',
                task: 'getDatefromVirusCheckFile',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 1) {
                    $('#vs-uptodate').text(data.info);
                    //alert(data.year+ "/" +data.month+ "/"+data.date+ "  "+data.hour+ ":"+data.min+ ":"+data.sec);
                }
            }
        });
    });
}



function enableManualUpdate($) {
    $('#icon-refresh').removeClass('spinAnimation');
    $("#vs-div-update").show();
    $("#vs-div-uptodate").hide();
    $('#v-sig').text('Click To Update Virus Signature');
    $( "#icon-refresh" ).one( "click", function() {
        downloadRequest('avs');
        $('#icon-refresh').addClass('spinAnimation');

    });
}


function checkUserType()
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
                    checkPatternVersion();
                } else {
                    $( "#icon-refresh" ).one( "click", function() {
                        checkPatternVersion();
                    });
                    $('#v-sig').text('Click To Update Virus Signature');
                }
            }
        });
    });
}