$(document).ready(function() {

	$('input[name="password"]').pwstrength({
        ui: { showVerdictsInsideProgressBar: true }
    });
	
	// unmask password checkbox
	$('input[name="showPassword"]').click(function(){
		var type = $(this).is(':checked') ? 'text' : 'password';
		$('input[name="password"]').attr('type', type);
	});
	
});