var url = ajaxurl;
var controller = "activation";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
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
            var currentfolder;
            var current = $(this);
            currentfolder = current.attr('id');
            $("#selected_file").val(currentfolder);
            return false;
        });
    });

    $("#scan-form").submit(function () {
        $('#scanModal').modal('hide');
        showLoading();
        var data = $("#scan-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        xhr = $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                hideLoading();
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
})
