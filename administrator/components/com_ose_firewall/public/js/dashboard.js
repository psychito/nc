var controller = 'dashboard';

jQuery(document).ready(function ($) {
    $('#ipmange-speech-bubble').tipsy({gravity: 'sw', fallback: O_SPEECH_BUBBLE});
    var colours = $('body').data('appStart').getColors();
    var seriesData = {};
    $('#world-map').vectorMap({
        map: 'world_mill_en',
        zoomButtons: false,
        scaleColors: [colours.yellow, colours.red],
        series: {
            regions: [{
                scale: [colours.yellow, colours.red],
                normalizeFunction: 'linear',
                attribute: 'fill',
                values: seriesData
            }]
        },
        normalizeFunction: 'polynomial',
        hoverOpacity: 0.8,
        hoverColor: false,
        focusOn: {
            x: 0.5,
            y: 0.5,
            scale: 1.0
        },
        enableZoom: 1,
        zoomMin: 1,
        zoomOnScroll: false,
        markerStyle: {
            initial: {
                fill: colours.red,
                stroke: colours.red
            }
        },
        backgroundColor: colours.white,
        regionStyle: {
            initial: {
                fill: '#999999',
                "fill-opacity": 0.7,
                stroke: '#999999',
                "stroke-width": 0,
                "stroke-opacity": 0
            },
            hover: {
                "fill-opacity": 1
            },
            selected: {
                fill: 'yellow'
            }
        }
    });
    var chartColours = [colours.red, colours.yellow, colours.linechart3, colours.linechart4, colours.linechart5, colours.linechart6, colours.linechart7];
    var totalPoints = 24;
    // Update interval
    var updateInterval = 200;
    // setup plot
    var options = {
        series: {
            grow: {active: false}, //disable auto grow
            shadowSize: 0, // drawing is faster without shadows
            lines: {
                show: true,
                fill: false,
                lineWidth: 2,
                steps: false
            },
            points: {
                show: true,
                fill: false
            }
        },
        grid: {
            show: true,
            aboveData: false,
            color: colours.black,
            labelMargin: 10,
            axisMargin: 0,
            borderWidth: 0,
            borderColor: null,
            minBorderMargin: 20,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20,
            margin: {
                top: 8,
                bottom: 20,
                left: 20
            }
        },

        colors: chartColours,
        tooltip: true, //activate tooltip
        tooltipOpts: {
            content: "Value is : %y.0",
            shifts: {
                x: -30,
                y: -50
            }
        },
        yaxis: {min: 0, max: 100},
        xaxis: {min: 0, max: 24, show: true, tickSize: 1}
    };


    retrieveCountryData();
    setInterval(function () {
        retrieveCountryData()
    }, 60000);
    retrieveTrafficData(options);
    setInterval(function () {
        retrieveTrafficData(options)
    }, 60000);
    retrieveHackingTraffic();
    setInterval(function () {
        $('#IPsTable').dataTable().api().ajax.reload();
    }, 60000);
    retrieveScanningResult();
    retrieveBackupResult();
    checkWebBrowsingStatus();

    if ($('#guideStatus').val() == 0) {
    	saveGuideStatus();
    }
    else {
    	$("#guide-img").hide();
    	$("#close-img").hide();
    }
    
    $("#btn_country").click(function ($) {
        jQuery("#overview_country").show();
        jQuery("#overview_traffic").hide();
        jQuery("#recent_scan").hide();
        jQuery("#ipmange-speech-bubble").hide();
        jQuery("#recent_backup").hide();
        //retrieveCountryData_after();
    });
    $("#btn_traffic").click(function ($) {
        jQuery("#overview_traffic").show();
        retrieveTrafficData_after(options);
        jQuery("#recent_scan").hide();
        jQuery("#ipmange-speech-bubble").hide();
        jQuery("#recent_backup").hide();
        jQuery("#overview_country").hide();
    });

    $("#btn_recentscan").click(function ($) {
        jQuery("#overview_country").hide();
        jQuery("#overview_traffic").hide();
        jQuery("#recent_scan").show();
        jQuery("#ipmange-speech-bubble").hide();
        jQuery("#recent_backup").hide();
    });

    $("#btn_recenthack").click(function ($) {
        jQuery("#overview_country").hide();
        jQuery("#overview_traffic").hide();
        jQuery("#recent_scan").hide();
        jQuery("#ipmange-speech-bubble").show();
        jQuery("#recent_backup").hide();
    });

    $("#btn_backup").click(function ($) {
        jQuery("#overview_country").hide();
        jQuery("#overview_traffic").hide();
        jQuery("#recent_scan").hide();
        jQuery("#ipmange-speech-bubble").hide();
        jQuery("#recent_backup").show();
    });
    
    $("#dashboardStyle").on('change', function () {
        showLoading('Loading');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                style: $("#style").val(),
                controller: controller,
                action: 'updateDashboardStyle',
                task: 'updateDashboardStyle',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                window.location.reload()
                hideLoading();
            }
        });
    });
});

function retrieveTrafficData_after(options) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getTrafficData',
                task: 'getTrafficData',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                var arr0 = [];
                $.each(data[0], function (i, item) {
                    arr0[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var arr1 = [];
                $.each(data[1], function (i, item) {
                    arr1[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var arr2 = [];
                $.each(data[2], function (i, item) {
                    arr2[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var plot = $.plot($("#traffic-overview"),
                    [{data: arr0, label: "blacklist", lines: {show: true}}
                        , {data: arr1, label: "monitor", lines: {show: true}}
                        , {data: arr2, label: "whitelist", lines: {show: true}}]
                    , options);

                var xaxisLabel = $("<div class='axisLabel xaxisLabel'></div>").text("Attack History (24-hour clock)").appendTo($('#traffic-overview'));

                var yaxisLabel = $("<div class='axisLabel yaxisLabel'></div>").text("Number of attacks (times)").appendTo($('#traffic-overview'));

                yaxisLabel.css("margin-top", yaxisLabel.width() / 2 - 20);
            }
        });
    });
}

function retrieveCountryData() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getCountryStat',
                task: 'getCountryStat',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                var map = $('#world-map').vectorMap('get', 'mapObject');
                map.series.regions[0].setValues(data);
            }
        });
    });
}

function retrieveTrafficData(options) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getTrafficData',
                task: 'getTrafficData',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                var arr0 = [];
                $.each(data[0], function (i, item) {
                    arr0[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var arr1 = [];
                $.each(data[1], function (i, item) {
                    arr1[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var arr2 = [];
                $.each(data[2], function (i, item) {
                    arr2[i] = [parseInt(item.hour), parseInt(item.count)];
                });
                var plot = $.plot($("#traffic-overview"),
                    [{data: arr0, label: "blacklist", lines: {show: true}}
                        , {data: arr1, label: "monitor", lines: {show: true}}
                        , {data: arr2, label: "whitelist", lines: {show: true}}]
                    , options);

                var xaxisLabel = $("<div class='axisLabel xaxisLabel'></div>").text("Attack History (24-hour clock)").appendTo($('#traffic-overview'));

                var yaxisLabel = $("<div class='axisLabel yaxisLabel'></div>").text("Number of attacks (times)").appendTo($('#traffic-overview'));

                yaxisLabel.css("margin-top", yaxisLabel.width() / 2 - 20);
            }
        });
    });
}

function retrieveHackingTraffic() {
    jQuery(document).ready(function ($) {
        var manageIPsDataTable = $('#IPsTable').dataTable({
            bFilter: false,
            bInfo: false,
            bPaginate: false,
            "bLengthChange": false,
            bProcessing: false,
            iDisplayLength: 5,
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: "POST",
                data: function (d) {
                    d.option = option;
                    d.controller = 'manageips';
                    d.action = 'getLatestTraffic';
                    d.task = 'getLatestTraffic';
                    d.centnounce = $('#centnounce').val();
                }
            },
            columns: [
                {"data": "datetime"},
                {"data": "ip32_start"},
                {"data": "score"},
                {"data": "status"}
            ]
        });
    });
}

function retrieveScanningResult() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getScanHist',
                task: 'getScanHist',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                jQuery('#lastScanned').html(data.inserted_on);
                jQuery('#numScanned').html(data.totalscan);
                jQuery('#numinfected').html(data.totalvs);
            }
        });
    });
}

function retrieveBackupResult() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'getBackupList',
                task: 'getBackupList',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if(data.cms == "st")
                {
                   getLastBackupTable()
                }else{
                    jQuery('#lastbackup').html(data.info);
                }

            }
        });
    });
}



function getLastBackupTable()
{
    jQuery(document).ready(function ($) {
        var backupTable = $('#dashBoardRecentBackup').dataTable({
            bFilter: false,
            bInfo: false,
            bPaginate: false,
            "bLengthChange": false,
            bProcessing: false,
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: "POST",
                data: function (d) {
                    d.option = option;
                    d.controller = 'dashboard';
                    d.action = 'getBackupAccountTable';
                    d.task = 'getBackupAccountTable';
                    d.centnounce = $('#centnounce').val();
                }
            },
            columns: [
                {"data": "id"},
                {"data": "name"},
                {"data": "latestbackup"},
            ]
        });
    });
}


function checkWebBrowsingStatus() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'checkWebBrowsingStatus',
                task: 'checkWebBrowsingStatus',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {

            }
        });
    });
}

function saveGuideStatus() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'scanconfig',
                action: 'saveConfigScan',
                task: 'saveConfigScan',
                type: 'style',
                guideStatus: 1,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {

            }
        });
    });
}

jQuery(document).ready(function ($) {
	if ($('#guideStatus').val() == 0) {
	    $("#guide-imgs").hide();
	    //$("#guide-imgs").fadeIn(1000);
	 }

    $("#close-img").click(function () {
        $("#guide-img").fadeOut();
        $("#close-img").fadeOut();
    });

    $("#guide-btn").click(function () {
        //$("#guide-img").fadeIn();
        $("#close-img").fadeIn();
    });
});