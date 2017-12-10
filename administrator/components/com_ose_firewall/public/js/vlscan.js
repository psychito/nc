var url = ajaxurl;
var controller = "vlscan";
var option = "com_ose_firewall";
var isRunning = false;

// the current vulnerabilities object
var vl_model_obj = '';

jQuery(document).ready(function($){

    $('#vlscan').on('click', function() {
        $('#scan-window').fadeIn();
        showLoading (O_LOADING_TEXT);
        scanVls(1, 'vlscan');
    });
    $('#vlstop').on('click', function() {
        if (isRunning) {
            isRunning = false;
            showLoading (O_TERMINATE_SCAN);
            location.reload();
        }
    });
});

function scanVls(step, action) {

    jQuery('#status_content').hide();
    jQuery('#scan-result-panel').hide();
    //reset vl model
    var init_vl_model = {
        "scanDate": null,
        "content": {
            "theme": [],
            "plugin": [],
            "CMS": {}
        }
    };

    vl_model_obj.fresh_vl_data(init_vl_model);
    scanVlsInd(step, action);

}

function scanVlsInd(step, action) {
    isRunning = true;
    jQuery(document).ready(function($){
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:action,
                task:action,
                step : step,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                hideLoading ();

                //update vulnerabilities
                if(( typeof data.type !== 'undefined' && data.type != null) && ( typeof data.content !== 'undefined' && data.content != null))
                {
                    var oldRecord = vl_model_obj.vl_data();
                    if(data.type == 'CMS')
                    {
                        // assign new CMS vls object
                        oldRecord.content.CMS = data.content;
                        vl_model_obj.fresh_vl_data(oldRecord);
                    }
                    else if (data.type == 'plugin')
                    {
                        // add mew plugin vls to the end of array
                        oldRecord.content.plugin.push(data.content);
                        vl_model_obj.fresh_vl_data(oldRecord);
                    }
                    else if (data.type == 'theme')
                    {
                        // add mew theme vls to the end of array
                        oldRecord.content.theme.push(data.content);
                        vl_model_obj.fresh_vl_data(oldRecord);
                    }
                    jQuery('#scan-result-panel').fadeIn();
                    oldRecord = ko.unwrap(vl_model_obj.vl_data);
                }

                if(typeof data.status !== 'undefined' && data.status !== null)
                {
                    //update scanning status
                    vl_model_obj.vls_scan_status(data.status);
                    jQuery('#status_content').show();
                }

                if(typeof data.scanDate !== 'undefined' && data.scanDate !== null)
                {
                    jQuery('#scan-date').text(moment(data.scanDate).startOf('second').from(data.serverNow)
                    ).attr("title", moment(ko.unwrap(data.scanDate)).format('llll'));
                    jQuery('#scan-date').prepend('Last Scan: ');
                }

                //add collapes animation for new vls
                addRecordCollapseAnimation($);

                if(typeof data.status.cont !== 'undefined' && data.status.cont == true){
                    scanVlsInd(data.status.step, action)
                }else{
                    isRunning = false;
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var holdingStatus = {
                    "progress": "N/A",
                    "current_type": "Holding",
                    "current_scan": O_WAITING_SERVER_RESPONSE,
                    "total_scan": "N/A",
                    "total_vls": "N/A"
                };
                vl_model_obj.vls_scan_status(holdingStatus);
                jQuery('#status_content').show();
                sleep(1000);
                scanVlsInd(step, action);
            }
        });
    });
}

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}
function getLastScanRecord($)
{
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {
            option: option,
            controller: controller,
            action: 'getLastScanRecord',
            task: 'getLastScanRecord',
            centnounce: $('#centnounce').val()
        },
        success: function (data) {
            if(data !== null && data != '' && !$.isEmptyObject(data))
            {
                vl_model_obj = new vlMode($, data);
                ko.applyBindings(vl_model_obj);
                addRecordCollapseAnimation($);
                jQuery('#scan-result-panel').fadeIn();
                if (data.scanDate != '') {
                    jQuery('#scan-date').text(moment(data.scanDate).startOf('second').from(data.serverNow)
                    ).attr("title", moment(ko.unwrap(data.scanDate)).format('llll'));
                    jQuery('#scan-date').prepend('Last Scan: ');
                }
            }
        }
    });
}

function addRecordCollapseAnimation($)
{
    $('.vl-short-desc').unbind('click').click(function(){
        //$("#vl-short-desc").on( "click", function () {
        if ($(this).hasClass('collapsed')) {
            $(this).next().slideDown({duration: 300});
            $(this).removeClass('collapsed').addClass('expanded');
            $(this).children("i").removeClass('fa fa-plus-circle').addClass('fa fa-minus-circle');
        }
        else if ($(this).hasClass('expanded')){
            $(this).next().slideUp({duration: 300});
            $(this).removeClass('expanded').addClass('collapsed');
            $(this).children("i").removeClass('fa fa-minus-circle').addClass('fa fa-plus-circle');
        }
        //});
    });
}

jQuery(document).ready(function($){
    //filter text
    vlViewFilters();
    getLastScanRecord($);

});

function vlViewFilters()
{
    ko.bindingHandlers.filterCVE = {
        init: function(element, valueAccessor) {
            jQuery(element).attr({href : "https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-"+valueAccessor(), target: "_blank"});
            jQuery(element).text(valueAccessor());
        }
    };
    ko.bindingHandlers.filterosvdb = {
        init: function(element, valueAccessor) {
            jQuery(element).attr({href : "http://osvdb.org/show/osvdb/"+valueAccessor(), target: "_blank"});
            jQuery(element).text(valueAccessor());
        }
    };
    ko.bindingHandlers.filterexploitdb = {
        init: function(element, valueAccessor) {
            jQuery(element).attr({href : "https://www.exploit-db.com/exploits/"+valueAccessor(), target: "_blank"});
            jQuery(element).text(valueAccessor());
        }
    };
    ko.bindingHandlers.filtermetasploit = {
        init: function(element, valueAccessor) {
            jQuery(element).text(valueAccessor());
            jQuery(element).attr({href : "https://www.rapid7.com/db/modules/"+valueAccessor(), target: "_blank"});
        }
    };
    ko.bindingHandlers.filtersecunia = {
        init: function(element, valueAccessor) {
            jQuery(element).text(valueAccessor());
            jQuery(element).attr({href : "https://secunia.com/advisories/"+valueAccessor(), target: "_blank"});
        }
    };
    ko.bindingHandlers.filterurl = {
        init: function(element, valueAccessor) {
            jQuery(element).text(valueAccessor());
            jQuery(element).attr({href : valueAccessor(), target: "_blank"});
        }
    };
    ko.bindingHandlers.filteDate = {
        init: function(element, valueAccessor) {
            jQuery(element).text(moment(ko.unwrap(valueAccessor())).format('llll'));
        }
    };
    ko.bindingHandlers.filteLength = {
        init: function(element, valueAccessor) {
            if(ko.unwrap(valueAccessor()) === 'undefined' || ko.unwrap(valueAccessor())==null || jQuery.isEmptyObject(valueAccessor())){
                jQuery(element).text(0);
            }
            else
            {
                jQuery(element).text(ko.unwrap(valueAccessor()).length);
            }
        }
    };
}

function vlMode($, data){

    var self = this;

    //init
    //self.vl_data = ko.mapping.fromJS(data);
    self.vl_data = ko.observable();
    self.vls_scan_status = ko.observable();

    self.fresh_vl_data = function(vl_new_data){
        //ko.mapping.fromJSON(vl_new_data, self.vl_data);
        self.vl_data(vl_new_data);
    };

    self.fresh_vl_data(data);

}

function alert(){
    window.alert(5 + 6);
}



jQuery(document).ready(function($){


});