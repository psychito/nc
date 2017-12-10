(function($)
{
	$(document).ready(function(){
	  // Add smooth scrolling to all links in navbar + footer link
	  $("nav a, #back-top").on('click', function(event) {

	  // Make sure this.hash has a value before overriding default behavior
	  if (this.hash !== "") {
	    event.preventDefault();
	    var hash = this.hash;
	    $('html, body').animate({
	      scrollTop: $(hash).offset().top
	    }, 1200, "easeOutQuart", function(){
	      window.location.hash = hash;
      });
	    } // End if
	  });
	})
})(jQuery);
