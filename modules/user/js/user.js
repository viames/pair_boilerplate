// Initialize profile form helpers after the DOM is ready.
$(document).ready(function() {
	
	// show/hide password content in user edit
	$('input[name="showPassword"]').click(function(){
		var type = $(this).is(':checked') ? 'text' : 'password';
		$('input[name="password"]').attr('type', type);
	});
	
	// Store the browser timezone in the profile form.
	var timezone = jstz.determine();
	$("input[name='timezone']").val(timezone.name());

});
