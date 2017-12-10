var controller = "IpManagement";
jQuery(document).ready(function ($) {
    var manageIPsDataTable = $('#manageIPsTable').dataTable({
        processing: true,
        serverSide: true,
        order: [[3, 'desc']],
        ajax: {
            url: url,
            type: "POST",
            data:  {
                option : option,
                controller : controller,
                action : 'getIpInfo',
                task : 'getIpInfo',
                centnounce : $('#centnounce').val(),
            }
        },
        columns: [
            {"data": "id","width": "10%"},
            {"data": "ip","width": "30%"},
            {"data": "status", sortable: false,"width": "10%" },
            {"data": "datetime","width": "50%"},
        ]
    });
    $('#manageIPsTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });

    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter">' +
        '<option value="4">'+O_ALL+'</option>' +
        '<option value="1">'+O_WHITELISTED+'</option>' +
        '<option value="2">'+O_BLACKLISTED+'</option>' +
        '<option value="3">'+O_MONITORED+'</option>' +
        '</select></label>');
    statusFilter.appendTo($("#manageIPsTable_filter")).on('change', function () {
        var val = $('#statusFilter');
        manageIPsDataTable.api().column(2)
            .search(val.val(), false, false)
            .draw();
    });
    //hide
    $('#ip_duration_details').hide();
    $('input[type=radio][name=ip_status]').change(function() {
        if(this.value ==1)
            {
                $('#ip_duration_details').show();
            }else{
            $('#ip_duration_details').hide();
        }
    });
    $("#add-ip-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var postdata = $("#add-ip-form").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: postdata, // serializes the form's elements.
            success: function (data) {
                data = jQuery.parseJSON(data);
                $('#addIPModal').modal('hide');
                if (data.status == 1) {
                    hideLoading();
                    showDialogue(data.info,"UPDATE","CLOSE");
                    $('#manageIPsTable').dataTable().api().ajax.reload();
                }
                else {
                    hideLoading();
                    showDialogue(data.info,"ERROR" ,"CLOSE");
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });


    $('#import-ip-form').submit(function () {
        showLoading(O_IMPORTING_CSV);
        $('#centnounceForm').val($('#centnounce').val());
        // submit the form
        $(this).ajaxSubmit({
            url: url,
            success: function (data) {
                data = jQuery.parseJSON(data);
               //alert('inside success function ');
                if (data.status == 1) {
                    hideLoading();
                    $('#importModal').modal('hide');
                    $('#manageIPsTable').dataTable().api().ajax.reload();
                    showDialogue(data.info, "UPDATE","CLOSE");
                }
                else {
                    hideLoading();
                    $('#importModal').modal('hide');
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
        // return false to prevent normal browser submit and page navigation
        return false;
    });
});


function changeBatchItemStatus (action) {
    AppChangeBatchItemStatus (action, '#manageIPsTable');
}
function AppChangeBatchItemStatus (action, table) {
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        ids= encodeAllIDs($(table).dataTable().api().rows('.selected').data());
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:action,
                task:action,
                ids:ids,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                if (data.status == 1) {
                    showLoading(data.info);
                }
                else {
                    showDialogue(data.info,O_OK);
                }
                hideLoading ();
                $(table).dataTable().api().ajax.reload(null, false);
            }
        });
    });
}

function removeItem (deleteallrecords) { // 1 for delete all the records
    deleteallrecords = (deleteallrecords === undefined) ? 0 : deleteallrecords;
    jQuery(document).ready(function ($) {
        ids= encodeAllIDs($('#manageIPsTable').dataTable().api().rows('.selected').data());
        if (ids.length > 0) {
            confirmRemoveItems (ids,deleteallrecords);
        } else {
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}

function confirmRemoveItems(ids,deleteallrecords)
{
    if(deleteallrecords == 0)
    {
       var message =  O_DELETE_IP+"<br/>";
    }else {
        var message = O_DELETE_ALL_IP_RECORDS+"<br/>";
    }
    bootbox.dialog({
        message: message,
        title: "Confirmation",
        buttons: {
            success: {
                label: "Yes",
                className: "btn-success",
                callback: function () {
                    if(deleteallrecords == 1)
                    {
                        clearAllRecords();
                    }else {
                        deleteItems(ids);
                    }
                }
            },
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

function changeItemStatus(id, status)
{
    AppChangeItemStatus(id, status, '#manageIPsTable', 'changeIPStatus');
}


function deleteItems(ids){
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:'deleteItem',
                task:'deleteItem',
                ids:ids,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                if (data.status == 1) {
                    showLoading(data.info,"UPDATE","CLOSE");
                }
                else {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
                hideLoading ();
                $('#manageIPsTable').dataTable().api().ajax.reload(null, false);
            }
        });
    });
}

function clearAllRecords(){
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:'clearAll',
                task:'clearAll',
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                if (data.status == 1) {
                    showLoading(data.info,"UPDATE","CLOSE");
                }
                else {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
                hideLoading ();
                $('#manageIPsTable').dataTable().api().ajax.reload(null, false);
            }
        });
    });
}

function viewAttackInfo(ip)
{
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:'viewAttackInfo',
                task:'viewAttackInfo',
                'ip' : ip,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                hideLoading();
                if (data.status == 1) {
                    bootbox.dialog({
                        message: data.info,
                        title: "UPDATE",
                        className: 'detailed-form',
                        buttons: {}
                    });
                }
                else {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
    });
}

function syncips_confirm()
{
    var message = "Are you sure you want to import all the ip's from firewall version 6.6";
    bootbox.dialog({
        message: message,
        title: "Confirmation",
        buttons: {
            success: {
                label: "Yes",
                className: "btn-success",
                callback: function () {
                    importips();
                }
            },
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

function importips()
{
    jQuery(document).ready(function($){
    showLoading (O_PLEASE_WAIT);
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {
            option : option,
            controller:controller,
            action:'importips',
            task:'importips',
            centnounce:$('#centnounce').val()
        },
        success: function(data)
        {
            if (data.status == 1) {
                showLoading(data.info,"UPDATE","CLOSE");
            }
            else {
                showDialogue(data.info,"ERROR","CLOSE");
            }
            hideLoading ();
            $('#manageIPsTable').dataTable().api().ajax.reload(null, false);
        }
    });
});
}

function whitelist_confirm(varname)
{
    var message = "Are you sure you want to whitelist: " + varname;
    bootbox.dialog({
        message: message,
        title: "Confirmation",
        buttons: {
            success: {
                label: "Yes",
                className: "btn-success",
                callback: function () {
                    addentity(varname);
                }
            },
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

function addentity(varname)
{
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:'addEntityFromAttackLog',
                task:'addEntityFromAttackLog',
                entity: varname,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                hideLoading ();
                if (data.status == 1) {
                    showDialogue(data.info,"UPDATE","CLOSE");
                }
                else {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
    });
}

function getTempWhiteListedIps()
{
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:'getTempWhiteListedIps',
                task:'getTempWhiteListedIps',
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                hideLoading ();
                if (data.status == 1) {
                    showDialogue(data.info,"UPDATE","CLOSE");
                }
                else {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
    });
}

function deleteTempWhiteListIps(ip)
{

    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:'deleteTempWhiteListIps',
                task:'deleteTempWhiteListIps',
                ip: ip,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                hideLoading ();
                if (data.status == 1) {
                    $(".modal .modal-content .modal-header .close").click();
                    getTempWhiteListedIps();
                }
                else {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
    });
}







