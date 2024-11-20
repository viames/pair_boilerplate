/**
 * Set a cookie for the active filter.
 * @param {string} name
 * @param {string} value
 */
function setPersistentState(name, value) {

	let cookieName = cookiePrefix + name.charAt(0).toUpperCase() + name.slice(1);

	Cookies.set(cookieName, JSON.stringify(value), {
		path: '/',
		sameSite: 'strict'
	});

}

function unsetPersistentState(name) {

	let cookieName = cookiePrefix + name.charAt(0).toUpperCase() + name.slice(1);

	Cookies.remove(cookieName, {
		path: '/',
		sameSite: 'strict'
	});

}

$(document).ready(function () {

	/**
	 * Show a modal dialog with a waiting message.
	 */
	$('.modal-processing').click(function () {
		Swal.fire({
			title: "Operazione in corso",
			text: "Si prega di attendere...",
			icon: 'info',
			showConfirmButton: false,
			allowOutsideClick: false
		});
	});

	/**
	 * Insert a random string in the password field when the button is clicked.
	 */
	$('button.password-generator').click(function () {
		var elements = document.getElementsByClassName('pwstrength');
		var newPw = createRandomString(10);
		elements[0].value = newPw; // campo in chiaro
		elements[1].value = newPw; // campo con asterischi
	});

	/**
	 * Create a string with random characters of desired length.
	 * @param length
	 * @returns
	 */
	function createRandomString(length) {
		var text = '';
		var possible = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789-.";
		for (var i = 0; i < length; i++) {
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return text;
	}

});
