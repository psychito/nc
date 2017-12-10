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
                console.log(data);
                $('#numofWebsite').text(numberWithCommas(data.total));
	        }
	      });
	});
}
function numberWithCommas(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
jQuery(document).ready(function($){
	updateNumbOfWebsite();
});