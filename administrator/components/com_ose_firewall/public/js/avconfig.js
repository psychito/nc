var url = ajaxurl; 
var option = "com_ose_firewall";
jQuery(document).ready(function($){
	$("#configuraton-form").submit(function() {
		showLoading();
        var postdata = $("#configuraton-form").serialize();
        postdata += '&centnounce=' + $('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: postdata, // serializes the form's elements.
               success: function(data)
               {
            	   hideLoading();
            	   data = jQuery.parseJSON(data);
           		   showDialogue (data.result, data.status, 'OK');
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
	
});
