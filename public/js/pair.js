(function ($) {

    // extended functions
    $.extend(

        /**
         * Binds “timego” plugin for real-time data convertion.
         */
		$.fn.bindTimeago = function() {

            // prevents to call function before its library being loaded
            if ($.isFunction($.fn.timeago)) {
				$.timeago.settings.allowFuture = true;
				$(this).timeago();
            } else {
                setTimeout("$('" + $(this) + "').bindTimeago()", 2000);
            }

		},

         /**
         * Sets the event list visible or hidden.
         */
        $.toggleLogEvents = function() {

        	if ($('#log .events').is(':visible')) {
        		$('#log .events').hide().addClass('hidden');
        		$('#logShowEvents').removeClass('active').html('Show <span class="fa fa-caret-down"></span>');
        		Cookies.set('LogShowEvents', 0, {path: '/', sameSite: 'strict'});
        	} else {
        		$('#log .events').show().removeClass('hidden');
        		$('#logShowEvents').addClass('active').html('Hide <span class="fa fa-caret-up"></span>');
        		Cookies.set('LogShowEvents', 1, {path: '/', sameSite: 'strict'});
        		$('html, body').stop().animate({'scrollTop': $('#log .events').offset().top-60}, 200, 'swing');
        	}

        },

        /**
         * Sets the queries visible or hidden in log area.
         */
        $.toggleLogQueries = function() {

        	var menuItem = $('#log .head .item.database');

        	if (menuItem.hasClass('active')) {
        		menuItem.removeClass('active');
        		$('#log .query').addClass('hidden');
        		Cookies.set('LogShowQueries', 0, {path: '/', sameSite: 'strict'});
        	} else {
        		menuItem.addClass('active');
        		$('#log .query').removeClass('hidden');
        		Cookies.set('LogShowQueries', 1, {path: '/', sameSite: 'strict'});
        		if ($('#log .events').is(':hidden')) {
        			$('#logShowEvents').trigger('click');
        		}
        	}

        },

        $.addAjaxLog = function(log) {
        	$('#log > .events').append(log);
        },

        /**
         * Return today date in italian format (dd-mm-yyyy).
         */
		$.getTodayDate = function() {

			if (typeof Modernizr == 'object' && Modernizr.inputtypes.date) {
				var fullDate = new Date();
				var day   = fullDate.getDate() < 10 ? '0'+(fullDate.getDate()) : fullDate.getDate();
				var month = fullDate.getMonth() < 9 ? '0'+(fullDate.getMonth()+1) : fullDate.getMonth()+1;
				var year  = fullDate.getFullYear();
				var today = Modernizr.inputtypes.date ? year + '-' + month + '-' + day : day + '-' + month + '-' + year;
				return today;
			}

		},

		/**
		 * Bind of datepicker.
		 */
		$.fn.bindDatepickers = function() {

        	// wrap input field into group with calendar icon
        	$.each($(this), function(key, field) {
        		$(field).wrap('<div class="input-group"></div>');
        		$(field).before('<span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>');
        	});

        	// bind datepicker
        	if (!$(this).is('[readonly]')) {
				if (typeof Modernizr == 'object' && !Modernizr.inputtypes.date) {
					$(this).datepicker({
						autoclose: true,
						todayHighlight: true,
						format: 'yyyy-mm-dd',
						language: 'it'
					});
					Cookies.set('InputTypesDate', 0, {path: '/', sameSite: 'strict'});
				} else {
					Cookies.set('InputTypesDate', 1, {path: '/', sameSite: 'strict'});
				}
        	}

		},

		/**
		 * Bind of datetimepicker.
		 */
		$.fn.bindDatetimepickers = function() {

        	// wrap input field into group with calendar icon
        	$.each($(this), function(key, field) {
        		$(field).wrap('<div class="input-group"></div>');
        		$(field).before('<span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>');
        	});

        	// bind datetimepicker
        	if (!$(this).is('[readonly]')) {
				if (typeof Modernizr == 'object' && !Modernizr.inputtypes.datetime) {
					$(this).datetimepicker({
						format: 'yyyy-mm-dd hh:ii'
					});
					Cookies.set('InputTypesDatetime', 0, {path: '/', sameSite: 'strict'});
				} else {
					Cookies.set('InputTypesDatetime', 1, {path: '/', sameSite: 'strict'});
				}
        	}

		}

	);

})(jQuery);

$(document).ready(function() {

	/**
	 * Disable links with "disabled" class.
	 */
	$('a.disabled').click(function(event) {
		event.preventDefault();
		return false;
	});

	/**
	 * Time ago binding.
	 */
	$('.timeago').bindTimeago();

	/**
	 * Run method that binds datepicker.
	 */
	$("input[type='date']").bindDatepickers();

	/**
	 * Run method that binds datetimepicker.
	 */
	$("input[type='datetime']").bindDatetimepickers();

	/**
	 * Listener for click on log showEvents button.
	 */
	$('#logShowEvents').click(function() {
		$.toggleLogEvents();
	});

	/**
	 * Show events when clicked the warning item in the log header
	 */
	$('#log .head a.item.warning').click(function() {
		if ($('#log .events').is(':hidden')) {
			$('#logShowEvents').trigger('click');
		}
	});

	/**
	 * Show events when clicked the error item in the log header
	 */
	$('#log .head a.item.error').click(function() {
		if ($('#log .events').is(':hidden')) {
			$('#logShowEvents').trigger('click');
		}
	});

	/**
	 * Listener for click on log showQueries item.
	 */
	$("#log .head .item.database").click(function() {
		$.toggleLogQueries();
	});

	/**
	 * Select all rows in the same column of this clicked th.
	 */
	$('table .selectAllRows').click(function(){
		var table = $(this).closest('table');
		var pos = table.find('th').index($(this).parent('th'));
		table.find('td:nth-child(' + (pos+1) + ')').find('input:checkbox').prop('checked', true);
		$(this).addClass('hidden');
		$('table .deselectAllRows').removeClass('hidden');
	});

	/**
	 * Deselect all rows in the same column of this clicked th.
	 */
	$('table .deselectAllRows').click(function(){
		var table = $(this).closest('table');
		var pos = table.find('th').index($(this).parent('th'));
		table.find('td:nth-child(' + (pos+1) + ')').find('input:checkbox').prop('checked', false);
		$(this).addClass('hidden');
		$('table .selectAllRows').removeClass('hidden');
	});

});