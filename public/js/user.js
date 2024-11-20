$(document).ready(function() {
	
	// show password content in user edit
	$('.show-hide-password').click(function() {
		var input = $(this).prev('input');
		if ('text' == input.attr('type')) {
			input.attr('type', 'password');
			$(this).children('.fa').removeClass('fa-eye-slash').addClass('fa-eye');
			//$('input[name="password"]').attr('type', type);
		} else {
			input.attr('type', 'text');
			$(this).children('.fa').removeClass('fa-eye').addClass('fa-eye-slash');
		}
	});

	var timezone = jstz.determine();
	$("input[name='timezone']").val(timezone.name());

});