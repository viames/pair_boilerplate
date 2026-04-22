// Initialize user form password controls after the DOM is ready.
$(document).ready(function() {

	// Bind password strength feedback to the password field.
	$('input[name="password"]').pwstrength({
        ui: { showVerdictsInsideProgressBar: true }
    });
	
	// unmask password checkbox
	$('input[name="showPassword"]').click(function(){
		var type = $(this).is(':checked') ? 'text' : 'password';
		$('input[name="password"]').attr('type', type);
	});
	
});
