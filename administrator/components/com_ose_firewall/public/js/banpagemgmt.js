/**
 * Created by suraj on 25/07/2016.
 */
var url = ajaxurl;
var controller = "banpagemgmt";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    tinymce.init({
        selector: "textarea.tinymce",
        menubar : false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code ",
            "insertdatetime table contextmenu paste"
        ],
        height: 200,
        toolbar: "undo redo | bold italic blockquote hr| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

    $('#save-button-fw').on( 'click', function () {
        tinyMCE.triggerSave();
    });
    if ($("centroraGASwitch").is(':checked')) {
        $("hidden-QRcode").attr("style", "display: block;");
    } else {
        $("hidden-QRcode").attr("style", "display: none;");
    }


    if($('#blockIP403').is(':checked')) {
        $("#customBanpageDiv").attr("style", "display: none;");
        $("#customBanURLDiv").attr("style", "display: none;");

    }else if($('#blockIPban').is(':checked')) {
        $("#customBanpageDiv").attr("style", "display: block;");
        $("#customBanURLDiv").attr("style", "display: block;");
    }
    if ($("bf_status").is(':checked')) {
        $("#bf-config").attr("style", "display: inline;");
    }

    $('#strongPassword').change(function () {
        if($(this).is(":checked") && cms == 'joomla') {
            checkPassword();
        }
    });

    $('#customUrl').on('change keyup', function () {
        this.value ? $("#googleAuth").fadeOut() : $("#googleAuth").fadeIn();
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
                if (data.status == 1)
                {
                    showLoading(data.info);
                    hideLoading();
                }
                else
                {
                    hideLoading();
                    showDialogue(data.info, 'ERROR', O_OK);
                    setTimeout(function () {
                        window.location.reload(1);
                    }, 7000);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
});

function showSecret() {
    jQuery(document).ready(function ($) {
        if (document.getElementById("centroraGASwitch").checked == true) {
            $("#hidden-QRcode").slideDown({ duration: 300 });
            // showGoogleSecret();
        } else {
            $("#hidden-QRcode").slideUp({ duration: 300 });
        }
    })
}

function toggleDisabled(enable){
    jQuery(document).ready(function ($) {
        if (enable == 1){
            $( "[id^=customBan]" ).slideDown({ duration: 300 });
            $("#customBanpage").attr("style", "display: none;");
        }else{
            $( "[id^=customBan]" ).slideUp({ duration: 300});
        }
    });
}

