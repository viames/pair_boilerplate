$(document).ready(function() {

	// animates the cog icon
	$('button.btn').click(function(event) {
		$(this).children('i.fa-play-circle').replaceWith('<i class="fa fa-cog fa-fw fa-spin"></i>');
	});

});
