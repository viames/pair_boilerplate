(function () {
	"use strict";

	/**
	 * Restituisce il costruttore Tom Select esposto dalla pagina corrente.
	 */
	function getTomSelectConstructor() {

		return typeof window.TomSelect === "function" ? window.TomSelect : null;

	}

	/**
	 * Verifica se la pagina puo inizializzare Tom Select nella card demo di Crafter.
	 */
	function canEnhanceCrafterSelects() {

		return Boolean(getTomSelectConstructor());

	}

	/**
	 * Verifica se la pagina puo inizializzare i tooltip Bootstrap nel riferimento UI.
	 */
	function canEnhanceCrafterTooltips() {

		return Boolean(
			window.bootstrap &&
			window.bootstrap.Tooltip &&
			typeof window.bootstrap.Tooltip.getOrCreateInstance === "function"
		);

	}

	/**
	 * Verifica se la pagina puo inizializzare i toast Bootstrap nel riferimento UI.
	 */
	function canEnhanceCrafterToasts() {

		return Boolean(
			window.bootstrap &&
			window.bootstrap.Toast &&
			typeof window.bootstrap.Toast.getOrCreateInstance === "function"
		);

	}

	/**
	 * Inizializza le select demo Tom Select senza reidratare controlli gia decorati.
	 */
	function initCrafterReferenceSelects() {

		const scope = document.querySelector("[data-crafter-ui-reference]");

		if (!scope || !canEnhanceCrafterSelects()) {
			return;
		}

		const TomSelect = getTomSelectConstructor();

		if (!TomSelect) {
			return;
		}

		scope.querySelectorAll("[data-crafter-tomselect]").forEach((element) => {
			if (!(element instanceof HTMLSelectElement) || element.dataset.crafterTomSelectBound === "true") {
				return;
			}

			element.dataset.crafterTomSelectBound = "true";

			new TomSelect(element, {
				allowEmptyOption: true,
				create: false,
			});
		});

	}

	/**
	 * Inizializza i tooltip Bootstrap del riferimento UI riusando eventuali istanze gia create.
	 */
	function initCrafterReferenceTooltips() {

		const scope = document.querySelector("[data-crafter-ui-reference]");

		if (!scope || !canEnhanceCrafterTooltips()) {
			return;
		}

		scope.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((element) => {
			if (!(element instanceof HTMLElement)) {
				return;
			}

			window.bootstrap.Tooltip.getOrCreateInstance(element);
		});

	}

	/**
	 * Collega i pulsanti demo che mostrano un toast Bootstrap gia presente in pagina.
	 */
	function initCrafterReferenceToasts() {

		const scope = document.querySelector("[data-crafter-ui-reference]");

		if (!scope || !canEnhanceCrafterToasts()) {
			return;
		}

		scope.querySelectorAll("[data-crafter-toast-trigger]").forEach((trigger) => {
			if (!(trigger instanceof HTMLElement) || trigger.dataset.crafterToastBound === "true") {
				return;
			}

			trigger.dataset.crafterToastBound = "true";

			trigger.addEventListener("click", () => {
				const targetSelector = trigger.dataset.crafterToastTarget || "";
				const toastElement = document.querySelector(targetSelector);

				if (!(toastElement instanceof HTMLElement)) {
					return;
				}

				window.bootstrap.Toast.getOrCreateInstance(toastElement).show();
			});
		});

	}

	/**
	 * Avvia gli enhancement client-side della dashboard Crafter a DOM pronto.
	 */
	function initCrafterPage() {

		initCrafterReferenceSelects();
		initCrafterReferenceTooltips();
		initCrafterReferenceToasts();

	}

	document.addEventListener("DOMContentLoaded", initCrafterPage);
})();
