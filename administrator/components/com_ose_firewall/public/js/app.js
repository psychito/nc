var url = ajaxurl;
var option = "com_ose_firewall";
// make console.log safe to use
window.console||(console={log:function(){}});
//Internet Explorer 10 in Windows 8 and Windows Phone 8 fix
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style');
    msViewportStyle.appendChild(
        document.createTextNode(
            '@-ms-viewport{width:auto!important}'
        )
    );
    document.querySelector('head').appendChild(msViewportStyle)
}

var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

jQuery.browser = browser;

//Android stock browser
var nua = navigator.userAgent;
var isAndroid = (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1);
if (isAndroid) {
    $('select.form-control').removeClass('form-control').css('width', '100%')
}

function showDialogue (message, title, buttonLabel, className) {
    bootbox.dialog({
        message: message,
        title: title,
        className: className,
        buttons: {
            success: {
                label: buttonLabel,
                className: "btn-primary btn-alt"
            }
        }
    });
}

function encodeAllIDs(selections)
{
    var i=0;
    ids = [];
    for (i = 0; i < selections.length; i++)
    {
        ids [i] = parseInt(selections[i].id);
    }
    ids = JSON.stringify(ids);
    return ids;
}

function encodeAllnames(selections)
{
    var i=0;
    ids = [];
    for (i = 0; i < selections.length; i++)
    {
        ids [i] = (selections[i].path);
    }
    ids = JSON.stringify(ids);
    return ids;
}
function showLoading (text, id) {
    if (text =='')
    {
        text = O_LOADING_TEXT;
    }
    jQuery(document).ready(function($){
        if (id =='' || id == undefined) {
            $('body').waitMe({
                text: text
            });
        }
        else {
            $(id).waitMe({
                text : text,
                bg : 'rgba(255,255,255,0.7)',
                color : '#048ec2'
            });
        }
    });
}

function hideLoading(timeout) {
    jQuery(document).ready(function($){
        timeout = typeof timeout !== 'undefined' ? timeout : 800;
        setTimeout(function()
        {
            $('body').waitMe("hide");
        }, timeout);
    });
}

function redirectTut (url) {
    window.open(url, '_blank');
    return false;
}

function AppChangeItemStatusRuleset(id, status, table, task, controller)
{
    jQuery(document).ready(function ($) {
        showLoading(O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: task,
                task: task,
                id: id,
                status: status,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status =='SUCCESS') {
                    showLoading(data.result);
                }
                else {
                    showDialogue(data.result, data.status, O_OK);
                }
                hideLoading();
                $(table).dataTable().api().ajax.reload();
            }
        });
    });
}

function AppChangeItemStatus(id, status, table, task) {
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:task,
                task:task,
                id:id,
                status:status,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                if (data.status =='SUCCESS') {
                    showLoading(data.result);
                }
                else {
                    showDialogue(data.result, data.status, O_OK);
                }
                hideLoading ();
                $(table).dataTable().api().ajax.reload();
            }
        });
    });
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
                if (data.status =='SUCCESS') {
                    showLoading(data.result);
                }
                else {
                    showDialogue(data.result, data.status, O_OK);
                }
                hideLoading ();
                $(table).dataTable().api().ajax.reload(null, false);
            }
        });
    });
}

function AppRemoveItems (action) {
    var message = O_DELETE_CONFIRM_DESC;
    if (action == 'deletevariable'){
        message = message + '</br> </br><b>NB: </b>' + O_DELETEVARIABLES_CONFRIM
    }
    bootbox.dialog({
        message: message,
        title: O_DELETE_CONFIRM,
        buttons: {
            success: {
                label: O_YES,
                className: "btn-success",
                callback: function(result) {
                    changeBatchItemStatus(action);
                }
            },
            main: {
                label: O_NO,
                className: "btn-danger",
                callback: function(result) {
                    this.close();
                }
            }
        }
    });
}

function AppRemoveAllItems (task, table) {
    var message = O_DELETEALL_CONFIRM_DESC;
    if (task == 'removeAllIPs'){
        message = message + '</br> </br><b>NB: </b>' + O_DELETEALLIP_CONFRIM
    } else if (task == 'deleteAllVariables'){
        message = message + '</br> </br><b>NB: </b>' + O_DELETEVARIABLES_CONFRIM
    }
    bootbox.dialog({
        message: message,
        title: O_DELETE_CONFIRM,
        buttons: {
            success: {
                label: O_YES,
                className: "btn-success",
                callback: function(result) {
                    showLoading (O_PLEASE_WAIT);
                    jQuery(document).ready(function($){
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: 'json',
                            data: {
                                option:option,
                                controller:controller,
                                action:task,
                                task:task,
                                centnounce:$('#centnounce').val()
                            },
                            success: function(data)
                            {
                                hideLoading ();
                                if (data.status =='SUCCESS') {
                                    showLoading(data.result);
                                }
                                else {
                                    showDialogue(data.result, data.status, O_OK);
                                }
                                $(table).dataTable().api().ajax.reload();
                            }
                        });
                    });
                }
            },
            main: {
                label: O_NO,
                className: "btn-danger",
                callback: function(result) {
                    this.close();
                }
            }
        }
    });
}

function AppRunAction (action, table) {
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:controller,
                action:action,
                task:action,
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                hideLoading ();
                if (data.status =='SUCCESS') {
                    showLoading(data.result);
                }
                else {
                    showDialogue(data.result, data.status, O_OK);
                }
                $(table).dataTable().api().ajax.reload();
            }
        });
    });
}

function checkphpConfig () {
    jQuery(document).ready(function($){
        showLoading(O_PLEASE_WAIT);
        var postdata = $("#php-configuraton-form").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: postdata, // serializes the form's elements.
            success: function(data)
            {
                hideLoading();
                data = jQuery.parseJSON(data);
                showDialogue(data.config, 'Recommended Setting', O_OK, 'phpconfig');
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
}

function updateSignature(table)
{
    jQuery(document).ready(function($){
        showLoading (O_PLEASE_WAIT);
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:'audit',
                action:'updateSignature',
                task:'updateSignature',
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                showLoading(data.result);
                hideLoading ();
                $(table).dataTable().api().ajax.reload();
                location.reload();
            }
        });
    });
}

function removejscssfile(filename, filetype){
    var targetelement = (filetype == "js") ? "script" : (filetype == "css") ? "link" : "none"; //determine element type to create nodelist from
    var targetattr = (filetype == "js") ? "src" : (filetype == "css") ? "href" : "none"; //determine corresponding attribute to test for
    var allsuspects = document.getElementsByTagName(targetelement);
    for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
        if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
            allsuspects[i].parentNode.removeChild(allsuspects[i]); //remove element by calling parentNode.removeChild()
    }
}

function fixGoogleScan() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'audit',
                action: 'googleRot',
                task: 'googleRot',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                location.reload();
            }
        });
    });
}

function fixGoogleScanV7() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'auditv7',
                action: 'googleRotv7',
                task: 'googleRotv7',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if(data.status==1)
                {
                    location.reload();
                }else{
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
    });
}
function joomla_check() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                option: option,
                controller: 'dashboard',
                action: 'check',
                task: 'check',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data != 1) {
                    alert(O_SESSION_EXPIRED);
                    location.reload();
                }
            }
        });
    });
}

//doc ready function
jQuery(document).ready(function($){
    if (cms == 'joomla') {
        setInterval(function () {
            joomla_check();
        }, 30000);
    }
    $('[data-toggle="popover"]').popover({
        placement : 'right',
        trigger : 'focus',
        container : 'body',
        title: function(){
            if (typeof $(this).attr("data-title") != 'undefined'){
                return $(this).attr("data-title");
            } else{
                return $(this).parent().text();
            }
        },
        //content: function(){return "CONTENT TEXT";},
        template : '<div class="popover" role="tooltip">' +
        '<div class="arrow"></div>' +
        '<h3 class="popover-title"></h3>' +
        '<div class="popover-content help-block" style="font-weight: normal;"></div>' +
        '</div>'
    });

    $("#passcodeForm").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var data = $("#passcodeForm").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                hideLoading();
                if (data.status == true) {
                    window.location = data.page;
                    window.location.reload;
                } else {
                    showDialogue(O_WORONG_PASSCODE, O_FAIL, O_FAIL);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });


    $("#configuraton-form").submit(function() {
        showLoading(O_PLEASE_WAIT);
        var data = $("#configuraton-form").serialize();
        data += '&centnounce='+$('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            dataType: 'json',
            success: function(data)
            {

                if (data.status == 'SUCCESS')
                {
                    showLoading(data.result);
                    hideLoading();
                }
                else
                {
                    hideLoading();
                    showDialogue(data.message, data.status, O_OK);
                    setTimeout(function () {
                        window.location.reload(1);
                    }, 7000);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });

    $("#seo-configuraton-form").submit(function() {
        showLoading(O_PLEASE_WAIT);
        $('#customBanpage').html(tinymce.get('customBanpage').getContent());
        var postdata = $("#seo-configuraton-form").serialize();
        postdata += '&centnounce='+$('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: postdata,
            success: function(data)
            {
                if (data.status == 'SUCCESS')
                {
                    showLoading(data.result);
                    hideLoading();
                }
                else
                {
                    showDialogue(data.result, data.status, O_OK);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });

    $("#adminconfiguraton-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var addata = $("#adminconfiguraton-form").serialize();
        addata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: addata, // serializes the form's elements.
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.status == 'SUCCESS') {
                    showLoading(data.result);
                    hideLoading();
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, O_OK);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    $("#adconfiguraton-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var addata = $("#adconfiguraton-form").serialize();
        addata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: addata, // serializes the form's elements.
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.status == 'SUCCESS') {
                    showLoading(data.result);
                    hideLoading();
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, O_OK);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });

    $("#bfconfiguraton-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var bfdata = $("#bfconfiguraton-form").serialize();
        bfdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: bfdata, // serializes the form's elements.
            success: function (data) {
                data = jQuery.parseJSON(data);
                if (data.status == 'SUCCESS') {
                    showLoading(data.result);
                    hideLoading();
                }
                else {
                    hideLoading();
                    showDialogue(data.result, data.status, O_OK);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    $("#admin-configuraton-form").submit(function() {
        showLoading(O_PLEASE_WAIT);
        var data = $("#admin-configuraton-form").serialize();
        data += '&centnounce='+$('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                if (data.status == 'SUCCESS')
                {
                    showLoading(data.result);
                    hideLoading();
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 5000);
                }
                else
                {
                    showDialogue(data.result, data.status, O_OK);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });

    $("#affiliate-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var data = $("#affiliate-form").serialize();
        data += '&centnounce='+$('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                if (data.status == 'SUCCESS')
                {
                    $("#affiliateFormModal").modal('hide');
                    showLoading(data.result);
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 6000);

                }
                else
                {
                    showDialogue(data.result, data.status, O_OK);
                    hideLoading();
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });

    $("#domains-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var data = $("#domains-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data, // serializes the form's elements.
            success: function (data) {
                hideLoading();
                if (data === parseInt(data, 10)) {
                    document.getElementById("domain-warning-label").style.display = 'none';
                    getDomain();
                    $('#addDomainModal').modal('hide');
                    $('#addAdminModal').modal();

                }
                else {
                    document.getElementById("domain-warning-label").style.display = 'inline';
                    document.getElementById("domain-warning-message").style.display = 'inline';
                    document.getElementById("domain-warning-message").innerHTML = data;
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    $("#adminemails-form").submit(function () {
        showLoading('Please wait...');
        var data = $("#adminemails-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data, // serializes the form's elements.
            success: function (data) {
                if (data === parseInt(data, 10)) {
                    showLoading(O_ADD_ADMIN_SUCCESS);
                    hideLoading();
                    document.getElementById("admin-warning-label").style.display = 'none';
                    $('#addAdminModal').modal('hide');
                    $('#adminTable').dataTable().api().ajax.reload();
                }
                else {
                    showLoading(O_ADD_ADMIN_FAIL);
                    hideLoading();
                    document.getElementById("admin-warning-label").style.display = 'inline';
                    document.getElementById("admin-warning-message").style.display = 'inline';
                    document.getElementById("admin-warning-message").innerHTML = data;
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    $("#addext-form").submit(function () {
        showLoading('Please wait...');
        var data = $("#addext-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data, // serializes the form's elements.
            success: function (data) {
                if (data === parseInt(data, 10)) {
                    showLoading(O_ADD_EXT_SUCCESS);
                    hideLoading();
                    document.getElementById("ext-warning-label").style.display = 'none';
                    $('#addExtModal').modal('hide');
                    $('#extensionListTable').dataTable().api().ajax.reload();
                }
                else {
                    showLoading(O_ADD_EXT_FAIL);
                    hideLoading();
                    document.getElementById("ext-warning-label").style.display = 'inline';
                    document.getElementById("ext-warning-message").style.display = 'inline';
                    document.getElementById("ext-warning-message").innerHTML = data;
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    $("#secManager-form").submit(function () {
        showLoading('Please wait...');
        var data = $("#secManager-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data, // serializes the form's elements.
            success: function (data) {
                if (data.status === 'SUCCESS') {
                    showLoading(O_ADD_SEC_SUCCESS);
                    hideLoading();
                    document.getElementById("sec-warning-label").style.display = 'none';
                    $('#addSecManagerModal').modal('hide');
                    $('#secManagerTable').dataTable().api().ajax.reload();
                }
                else {
                    showLoading(O_ADD_SEC_FAIL);
                    hideLoading();
                    document.getElementById("sec-warning-label").style.display = 'inline';
                    document.getElementById("sec-warning-message").style.display = 'inline';
                    document.getElementById("sec-warning-message").innerHTML = data.message;
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
    $("#strongPassword-form").submit(function () {
        showLoading(O_PLEASE_WAIT);
        var data = $("#strongPassword-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            success: function (data) {
                hideLoading();
                if (data !== null) {
                    $('#strongPasswordModal').modal('hide');
                    showDialogue(O_PASSWORD_SUCCESS, O_SUCCESS, O_OK);
                }
                else {
                    $('#strongPasswordModal').modal('hide');
                    showDialogue(O_PASSWORD_FAIL, O_FAIL, O_OK);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
});
function getfilelist( cont, root ) {
    jQuery( cont ).addClass( 'wait' );
    jQuery.ajax ({
        url: url,
        type: "POST",
        data: {
            option : option,
            controller:controller,
            action : 'getFileTree',
            task : 'getFileTree',
            centnounce : jQuery('#centnounce').val(),
            dir: root
        },
        success: function(data) {
            jQuery( cont ).find( '.start' ).html( '' );
            jQuery( cont ).removeClass( 'wait' ).append(data);
            if( '/' == root )
                jQuery( cont ).find('UL:hidden').show();
            else{
                jQuery( cont ).find('UL:hidden').slideDown({ duration: 500, easing: null });
            }
        }
    }).done(function() {
        var entry = jQuery("[name='filetreeroot']");
        var current = jQuery("[name='filetreeroot']");
        var id = 'id';
        if (root === ''){getfiletreedisplay (entry, current, id);}
        return false;
    });
}
function getfiletreedisplay (entry, current, rel_id){
    /*expand Root*/
    /* if (escape( current.attr(rel_id) ) === '/'){
     entry.find('UL').slideUp({ duration: 1, easing: null }); *//* collapse it *//*
     entry.removeClass('collapsed').addClass('expanded');
     }*/
    if( entry.hasClass('folder') ) { /* check if it has folder as class name */
        if( entry.hasClass('collapsed') ) { /* check if it is collapsed */

            entry.find('UL').remove(); /* if there is any UL remove it */
            getfilelist( entry, escape( current.attr(rel_id) )); /* initiate Ajax request */
            entry.removeClass('collapsed').addClass('expanded'); /* mark it as expanded */
        }
        else { /* if it is expanded already */
            entry.find('UL').slideUp({ duration: 500, easing: null }); /* collapse it */
            entry.removeClass('expanded').addClass('collapsed'); /* mark it as collapsed */
        }
        if (escape( current.attr(rel_id) ) !== '/'){
            return current.attr( rel_id );
        } else {return '';}
    }
}
function actCentroraPlugin() {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'audit',
                action: 'actCentroraPlugin',
                task: 'actCentroraPlugin',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 'Completed') {
                    $('#pluginNotice').html('<div class="bg-transparent-white" style="padding: 20px 80px 20px 60px;"> <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> <span class="label label-success" style="margin-right: 20px; padding-top: 10px; padding-bottom: 10px;">Success</span> Centrora Plugin is enabled now</div>');                }
                else {
                    showDialogue(O_ACTIVATE_FAIL, O_FAIL, O_OK);
                }
            }
        });
    });
}

function changePermission(foldername, permission) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'audit',
                action: 'changePermission',
                task: 'changePermission',
                foldername : foldername,
                permission : permission,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data == "lock" || data =="unlock") {
                    location.reload();
                } else {
                    showDialogue("missing folder");
                }
            }
        });
    });
}



function createHtaccessFile(foldername) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'audit',
                foldername: foldername,
                action: 'createHtaccessFile',
                task: 'createHtaccessFile',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data == true) {
                    window.location.reload();

                } else {
                    showDialogue(O_ERROR_ACCESS_JS);
                }
            }
        });
    });
}
jQuery(document).ready(function($){
    updateNumbOfWebsite();
});
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function updateNumbOfWebsite()
{
    //showLoading();
    jQuery(document).ready(function($){
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option : 'com_ose_firewall',
                controller: 'login',
                action:'getNumbOfWebsite',
                task:'getNumbOfWebsite',
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                //hideLoading();
                if (typeof data !== 'undefined' && data !== null)
                {
                    numberUpAnimation(numberWithCommas(data.total));
                }

                //$('#numofWebsite').text(numberWithCommas(data.total));
            }
        });
    });
}


//pop up set for free users
jQuery(document).ready(function ($) {
    $('#subscription-popup').hide();
    $('.btn-checkfilepath').click(function(){
        $('#subscription-popup').fadeIn();
    });
    $("#close_popup").click(function() {
        $("#subscription-popup").fadeOut();
    });
    $('.fw-freeuser').click(function(){
        $('#subscription-popup').fadeIn();
    });
});

function numberUpAnimation(num){

    jQuery(document).ready(function($){

        $('#numofWebsite').prop('Counter',100).animate({
                Counter:17940
            },
            {
                duration:3000,
                easing:'swing',
                step:function(now){
                    $('#numofWebsite').text(Math.ceil(now));
                },
                complete:function(){
                    $('#numofWebsite').text(num);
                }

            });
    });
}

// version toggle functions
function toggleFirewallScanners (value){
    showLoading(O_PLEASE_WAIT);
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'bsconfigv7',
                action: 'toggleFirewallScanners',
                task: 'toggleFirewallScanners',
                value:value,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if(data.status == 1) {
                    if(value == 1){
                        $('#v6-tab').hide();
                        $('#v7-tab').show();
                        $('#v7-tab-default').hide();
                    }else{
                        $('#v7-tab').hide();
                        $('#v6-tab').show();
                        $('#v7-tab-default').hide();
                    }
                    window.location.replace(data.url);
                    //showDialogue(data.info,"UPDATE","close");
                }else{
                    showDialogue(data.info,"ERROR","close");
                }
            }
        })
    });
}

function getFirewallVersion (){
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: 'bsconfigv7',
                action: 'getFirewallScannerVersion',
                task: 'getFirewallScannerVersion',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if(data.status == 1) {
                    //showDialogue('v6 '+data.v6 + ' v7 ' +data.v7,"UPDATE","close");
                    if(parseInt(data.v6) ==0){
                        $('#v6-tab').show();
                        $('#v7-tab').hide();
                        $('#v7-tab-default').hide();

                    }
                    if(parseInt(data.v7) ==1)
                    {
                        $('#v7-tab').show();
                        $('#v6-tab').hide();
                        $('#v7-tab-default').hide();

                    }
                    if(parseInt(data.v6) == 1 && parseInt(data.v7) == 0)
                    {
                        $('#v7-tab-default').show();
                        $('#v6-tab').hide();
                        $('#v7-tab').hide();
                    }
                }else{
                    showDialogue(O_ERROR_ACCESS_FIREALL_VERSION,"ERROR","close");
                }
            }
        })
    });
}

jQuery(document).ready(function($){
    getFirewallVersion();
    checkFolerPermissions();
    housekeepingV7();
    $('#nav-to-v6').click(function(){
        toggleFirewallScanners(0)
    });
    $('#nav-to-v7').click(function(){
        toggleFirewallScanners(1)
    });
});

function enableV7(){
    toggleFirewallScanners(1);
}
function enableV6(){
    toggleFirewallScanners(0);
}

function checkFolerPermissions () {
    jQuery(document).ready(function($){
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option : option,
                controller:'bsconfigv7',
                action:'getFolderPermissions',
                task:'getFolderPermissions',
                centnounce:$('#centnounce').val()
            },
            success: function(data)
            {
                if (data.status == 0) {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        });
    });
}
function housekeepingV7()
{
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "bsconfigv7stats",
                action: 'housekeepingV7',
                task: 'housekeepingV7',
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                if (data.status == 0) {
                    showDialogue(data.info,"ERROR","CLOSE");
                }
            }
        })
    });
}

//banner-close
jQuery(document).ready(function($){
    jQuery('.at-banner__close').on('click', function () {
        jQuery('.at-banner').remove();
        jQuery('.wrapbody .everythingOnOneLine').css("padding-top", "0");
    });
});

