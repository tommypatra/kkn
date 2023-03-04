(function($) {
	$(document).ajaxSend(function() {
		$("body").addClass("loading");
	});

	$(document).ajaxStop(function() {
		$("body").removeClass("loading");		
	});

	$(document).ajaxError(function() {
		$("body").removeClass("loading");		
	});
})(jQuery);
