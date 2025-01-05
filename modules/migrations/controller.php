<?php

use Pair\Core\Controller;
use Pair\Html\Breadcrumb;

class MigrationsController extends Controller {

	protected function init(): void {

		Breadcrumb::path($this->lang('MIGRATIONS'), 'migrations');

	}

	/**
	 * Check the migrations performed and launch those not yet performed.
	 */
	public function migrateAction() {

		$this->view = 'default';

		$result = $this->model->runMigration();

		if (!$result) {
			$this->modal('Errore', $this->lang('MIGRATIONS_FAILED') . ': ' . implode(', ', $this->model->getErrors()), 'error')->confirm('Chiudi');
		}

		$this->modal('Eseguito', $this->lang('MIGRATIONS_SUCCESSFUL'))->confirm('Chiudi');

	}

}