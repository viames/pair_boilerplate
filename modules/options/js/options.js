$(document).ready(function() {
	
	// bind password field
	$("input[type='password']").each(function(key, field) {

		$(field).wrap('<div class="input-group"></div>');
		$(field).before('<span class="input-group-addon input-group-text show-hide-password"><i class="fal fa-fw fa-eye"></i></span>');
		
	});

	// show/hide password content when toggled
	$(".show-hide-password").click(function() {
		var input = $(this).next('input');
		if ('text' == input.attr('type')) {
			input.attr('type', 'password');
			$(this).children('.fal').removeClass('fa-eye-slash').addClass('fa-eye');
		} else {
			input.attr('type', 'text');
			$(this).children('.fal').removeClass('fa-eye').addClass('fa-eye-slash');
		}
	});
	
});