<?php

declare(strict_types=1);

use Pair\Html\Form;

/**
 * Contratto Pair v4 del wizard di creazione classe.
 */
final class CrafterClassWizardPageState {

	/**
	 * Costruisce il contratto Pair v4 del wizard di creazione classe.
	 */
	public function __construct(
		public Form $form
	) {

	}

}
