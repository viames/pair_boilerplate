// Manages the palette editor used by the templates module upload/edit forms.
(function () {
	const HEX_COLOR_PATTERN = /^#(?:[A-F0-9]{3}|[A-F0-9]{4}|[A-F0-9]{6}|[A-F0-9]{8})$/i;

	/**
	 * Normalize a color string to uppercase HEX with a leading # when valid.
	 */
	function normalizeColor(value) {
		let color = String(value || '').trim().toUpperCase();

		if (!color) {
			return '';
		}

		if (!color.startsWith('#')) {
			color = '#' + color;
		}

		return HEX_COLOR_PATTERN.test(color) ? color : '';
	}

	/**
	 * Convert a color to a value accepted by `<input type="color">`.
	 */
	function toColorInputValue(value, fallback) {
		const normalized = normalizeColor(value) || normalizeColor(fallback) || '#57A0E5';

		if (/^#[A-F0-9]{6}$/i.test(normalized)) {
			return normalized;
		}

		if (/^#[A-F0-9]{3}$/i.test(normalized)) {
			return '#'
				+ normalized[1] + normalized[1]
				+ normalized[2] + normalized[2]
				+ normalized[3] + normalized[3];
		}

		return '#57A0E5';
	}

	/**
	 * Resolve the palette editor container related to a clicked control.
	 */
	function getEditorFromElement(element) {
		if (!element) {
			return null;
		}

		return element.closest('[data-role="palette-editor"]')
			|| (element.closest('form') ? element.closest('form').querySelector('[data-role="palette-editor"]') : null);
	}

	/**
	 * Build a palette row with synchronized text and color inputs.
	 */
	function createPaletteRow(color, defaultColor) {
		const normalized = normalizeColor(color) || normalizeColor(defaultColor) || '#57A0E5';
		const pickerColor = toColorInputValue(normalized, defaultColor);
		const row = document.createElement('div');

		row.className = 'd-flex align-items-center gap-2 mb-2';
		row.setAttribute('data-role', 'palette-item');
		row.innerHTML = [
			'<input type="color" class="form-control form-control-color flex-shrink-0" data-role="palette-picker" title="' + normalized + '" value="' + pickerColor + '">',
			'<input type="text" class="form-control" data-role="palette-text" name="palette[]" placeholder="#57A0E5" autocomplete="off" spellcheck="false" value="' + normalized + '">',
			'<button type="button" class="btn btn-outline-danger btn-sm" data-action="palette-remove" title="Remove color"><i class="fal fa-times"></i></button>'
		].join('');

		return row;
	}

	/**
	 * Ensure every palette editor always exposes at least one row.
	 */
	function ensureAtLeastOneRow(editor) {
		if (!editor) {
			return;
		}

		const list = editor.querySelector('[data-role="palette-list"]');

		if (!list || list.querySelectorAll('[data-role="palette-item"]').length > 0) {
			return;
		}

		const defaultColor = editor.getAttribute('data-default-color') || '#57A0E5';
		list.appendChild(createPaletteRow(defaultColor, defaultColor));
	}

	/**
	 * Synchronize the color picker after a text input change.
	 */
	function syncRowFromText(row) {
		const textInput = row.querySelector('[data-role="palette-text"]');
		const pickerInput = row.querySelector('[data-role="palette-picker"]');

		if (!textInput || !pickerInput) {
			return;
		}

		const editor = getEditorFromElement(row);
		const defaultColor = editor ? (editor.getAttribute('data-default-color') || '#57A0E5') : '#57A0E5';
		const normalized = normalizeColor(textInput.value);

		if (normalized) {
			textInput.value = normalized;
			pickerInput.value = toColorInputValue(normalized, defaultColor);
			pickerInput.title = normalized;
			return;
		}

		textInput.value = '';
		pickerInput.value = toColorInputValue(defaultColor, defaultColor);
		pickerInput.title = '';
	}

	/**
	 * Synchronize the text field after a color picker change.
	 */
	function syncRowFromPicker(row) {
		const textInput = row.querySelector('[data-role="palette-text"]');
		const pickerInput = row.querySelector('[data-role="palette-picker"]');

		if (!textInput || !pickerInput) {
			return;
		}

		const normalized = normalizeColor(pickerInput.value);

		if (!normalized) {
			return;
		}

		textInput.value = normalized;
		pickerInput.title = normalized;
	}

	/**
	 * Initialize all editors found in the page.
	 */
	function initializeEditors() {
		document.querySelectorAll('[data-role="palette-editor"]').forEach((editor) => {
			editor.querySelectorAll('[data-role="palette-item"]').forEach((row) => {
				syncRowFromText(row);
			});

			ensureAtLeastOneRow(editor);
		});
	}

	document.addEventListener('DOMContentLoaded', initializeEditors);

	document.addEventListener('click', function (event) {
		const addButton = event.target.closest('[data-action="palette-add"]');

		if (addButton) {
			event.preventDefault();

			const editor = getEditorFromElement(addButton);
			const list = editor ? editor.querySelector('[data-role="palette-list"]') : null;

			if (!list) {
				return;
			}

			const defaultColor = editor.getAttribute('data-default-color') || '#57A0E5';
			list.appendChild(createPaletteRow(defaultColor, defaultColor));
			return;
		}

		const removeButton = event.target.closest('[data-action="palette-remove"]');

		if (!removeButton) {
			return;
		}

		event.preventDefault();

		const row = removeButton.closest('[data-role="palette-item"]');

		if (!row) {
			return;
		}

		const editor = getEditorFromElement(row);
		row.remove();
		ensureAtLeastOneRow(editor);
	});

	document.addEventListener('change', function (event) {
		const picker = event.target.closest('[data-role="palette-picker"]');

		if (picker) {
			const row = picker.closest('[data-role="palette-item"]');

			if (row) {
				syncRowFromPicker(row);
			}

			return;
		}

		const textInput = event.target.closest('[data-role="palette-text"]');

		if (!textInput) {
			return;
		}

		const row = textInput.closest('[data-role="palette-item"]');

		if (row) {
			syncRowFromText(row);
		}
	});

	document.addEventListener('submit', function (event) {
		const form = event.target.closest('form[action="templates/add"], form[action="templates/change"]');

		if (!form) {
			return;
		}

		const editor = form.querySelector('[data-role="palette-editor"]');

		if (!editor) {
			return;
		}

		ensureAtLeastOneRow(editor);

		editor.querySelectorAll('[data-role="palette-item"]').forEach((row) => {
			syncRowFromText(row);
		});
	});
})();
