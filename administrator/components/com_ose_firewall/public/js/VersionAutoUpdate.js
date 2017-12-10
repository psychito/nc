var url = ajaxurl;
var option = 'com_ose_firewall';

jQuery(document).ready(function($) {
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');

	$('.nav-tabs a').click(function (e) {
		$(this).tab('show');
		var scrollmem = $('body').scrollTop() || $('html').scrollTop();
		window.location.hash = this.hash;
		$('html,body').scrollTop(scrollmem);
	});
});

function showAutoUpdateDialogue (serverversion, newsurl, Updateurl, upgradeplugin, activateurl) {
	bootbox.dialog({
		message: O_UPDATE_CONF_DESC + "<b>" + serverversion + "</b>",
		title: O_UPDATE_CONF,
		buttons: {
			main: {
				label: O_UPDATE_NOW,
			  	className: "btn-new result-btn-set",
				callback: function () {
                    showLoadingStatus(O_UPDATE);
					  runAutoUpdate(Updateurl, upgradeplugin, activateurl);
				}
			},
			success: {
				label: "Changelog",
				className: "btn-new result-btn-set",
				callback: function() {
					window.open(newsurl);
					return false; //keep dialog open
				}
			}
		}
	});

 return false; // avoid to execute the actual submit of the form.
}

function runAutoUpdate(url, plugin, activateurl) {
	jQuery(document).ready(function($){
		jQuery.ajax ({
	        url: url,
	        type: "POST",
	        data: { 
	        	option : option,
	        	controller:controller, 
	        	plugin: plugin,
				updateaction: 'upgrade-plugin',
				centnounce: $('#centnounce').val()
	        },
	        success: function(output) {
	        	if (activateurl != null && document.readyState === "complete"){ //only run this part in the wordpress version where we have to activate the plugin after updating
	        		hideLoadingStatus ();
	        		activateWordpressPlugin(activateurl);
	        	} else {
	        		hideLoadingStatus ();
	        		location.reload();
	        	}
	        }
		});
	});
}

function activateWordpressPlugin (activateurl){
	jQuery.ajax ({
        url: activateurl,
        type: "POST", 
		success: function (output) {
            showLoadingStatus(O_ACTIVATE_PLUGIN);
			hideLoadingStatus ();
        	location.reload();
		}
	});
}

function hideLoadingStatus () {
	jQuery(document).ready(function($){
		setTimeout(function() 
		{
		  $('body').waitMe("hide");
		}, 800); 
	});
}

function showLoadingStatus (text) {
	if (text =='')
	{
        text = O_LOADING_TEXT;
	}
	jQuery(document).ready(function($){
		$('body').waitMe({
	        effect : 'facebook',
	        text : text,
	        bg : 'rgba(255,255,255,0.7)',
	        color : '#1BBC9B'
	    });
	});
}