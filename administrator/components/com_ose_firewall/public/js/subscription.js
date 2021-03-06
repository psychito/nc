var controller ='subscription';
		    
function activateCode () {
	jQuery(document).ready(function($){	
		$('#activationFormModal').modal();
	});
}

jQuery(document).ready(function($){	
	$("#activation-form").submit(function() {
		showLoading();
		var data = $("#activation-form").serialize();
		data += '&centnounce='+$('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: data, // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
            	   hideLoading();
            	   $('#activationFormModal').modal('hide');
                   showDialogue(data.message, data.status, O_OK);
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
});


function updateProfileID (profileID, profileStatus) {
 jQuery(document).ready(function($){
	$.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
	    data: {
	    		option : option, 
	    		controller:controller,
	    		action:'updateProfileID',
	    		task:'updateProfileID',
	    		profileID:profileID,
	    		profileStatus: profileStatus,
	    		centnounce:$('#centnounce').val()
	    },
        success: function(data)
        { }
	});  
   });
}

function centLogout () {
	jQuery(document).ready(function($){ 
		showLoading ();
		$.ajax({
		        type: "POST",
		        url: url,
		        dataType: 'json',
			    data: {
			    		option : option, 
			    		controller:controller,
			    		action:'logout',
			    		task:'logout',
			    		centnounce:$('#centnounce').val()
			    },
		        success: function(data)
		        {
		           hideLoading ();
                    showDialogue(data.message, data.status, O_OK);
		           location.reload(); 
		        }
		});
	});
}

function linkSub(profileID) {
	jQuery(document).ready(function($){ 
		showLoading ();
		$.ajax({
		        type: "POST",
		        url: url,
		        dataType: 'json',
			    data: {
			    		option : option, 
			    		controller:controller,
			    		action:'linkSubscription',
			    		task:'linkSubscription',
			    		profileID:profileID,
			    		centnounce:$('#centnounce').val()
			    },
		        success: function(data)
		        {
		           hideLoading ();
                    showDialogue(data.message, data.status, O_OK);
		           $('#subscriptionTable').dataTable().api().ajax.reload();
		           if (data.profileID!='undefined' && data.profileID!='')
		           {
		        	   updateProfileID (data.profileID, data.profileStatus);
		           }
		        }
		});
	});
}

jQuery(document).ready(function($){
	var subscriptionTable = $('#subscriptionTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = controller;
                d.action = 'getSubscription';
                d.task = 'getSubscription';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "recurringID"},
                { "data": "created" },
                { "data": "product" },
                { "data": "profileID" },
                { "data": "quantity" },
                //{ "data": "status" },
                { "data": "sub_status" },
                { "data": "created" },
                { "data": "expired_date" },
                { "data": "action" }
        ]
    });
});
