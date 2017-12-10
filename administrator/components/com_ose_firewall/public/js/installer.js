var controller = "audit";

jQuery(document).ready(function($){
	$('#dbinstall-form').submit(function() {
		showLoading ('Please wait...', '#dbinstall-form');
        $('#message-box').html(O_DB_INSTALL_DESC);
		createTables(0);
	    return false; 
	});
	
	$('#dbuninstall-form').submit(function() {
		showLoading ('Please wait...', '#dbuninstall-form');
        $('#message-box').html(O_DB_INSTALL_DESC);
		uninstallDB();
	    return false; 
	});
	
});

function createTables (step) {
jQuery(document).ready(function($){
	$.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
	    data: {
	    		option : option, 
	    		controller:controller,
	    		action:'createTables',
	    		task:'createTables',
	    		step:step,
	    		centnounce:$('#centnounce').val()
	    },
        success: function(data)
        {
			var result = data.status.split(',');
        	if (result[0] == 'Completed')
            {
				hideLoading ();
				$('#installer-buttons').hide();
				$('.alert-danger').fadeOut();
				$('#message-box').html(data.result);
				if(result[1] == 'wp')
				{
					//open wordpress link for the dashboard
					 window.location.replace('admin.php?page=ose_firewall', '_blank');
				}else {
					//open the joomla link for the dashboard
					 window.location.replace('index.php?option=com_ose_firewall', '_blank');
				}
            }
        	else
        	{
        		if (data.cont == 1)
    			{
    				$('#message-box').html(data.result);
    				createTables (data.step);
    			}
        	}
        }
      });
	});
}

function uninstallDB () {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'uninstallTables',
		    		task:'uninstallTables',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	$('#message-box2').html(data.result);
	        	if (data.success==true)
				{
					$('#uninstaller-buttons').hide();
					$('.alert-danger').fadeIn();
					hideLoading ();
				}
	        }
	      });
		});
}
