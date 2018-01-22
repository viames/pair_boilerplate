/**
 * @version	$Id$
 * @author	Viames Marino
 */

(function ($) {

    // extended functions
    $.extend(			

        /**
         * Display a javascript message box by using iziToast
         *
         * @param	string	Message title.
         * @param	string	Message text.
         * @param	string	Type (info, success, warning, error)
         */
        $.showMessage = function(title, message, type = 'info') {
			
        	if ('info'!=type && 'success'!=type && 'warning'!=type && 'error'!=type) {
        		type = 'info';
        	}
        	
        	iziToast[type]({title: title, message: message});
        	
        },
        	
        /**
         * Sets the event list visible or hidden.
         */
        $.toggleLogEvents = function() {

        	if ($('#log .events').is(':visible')) {
        		$('#log .events').hide().addClass('hidden');
        		$('#logShowEvents').removeClass('active').html('Show <span class="fa fa-caret-down"></span>');
        		Cookies.set('LogShowEvents', 0, {path: '/'});
        	} else {
        		$('#log .events').show().removeClass('hidden');
        		$('#logShowEvents').addClass('active').html('Hide <span class="fa fa-caret-up"></span>');
        		Cookies.set('LogShowEvents', 1, {path: '/'});
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
        		Cookies.set('LogShowQueries', 0, {path: '/'});
        	} else {
        		menuItem.addClass('active');
        		$('#log .query').removeClass('hidden');
        		Cookies.set('LogShowQueries', 1, {path: '/'});
        		if ($('#log .events').is(':hidden')) {
        			$('#logShowEvents').trigger('click');
        		}
        	}
        	
        },

        $.addAjaxLog = function(log) {
        	$('#log > .events').append(log);
        }
		
    );

})(jQuery);

$(document).ready(function() {
	
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

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

});
