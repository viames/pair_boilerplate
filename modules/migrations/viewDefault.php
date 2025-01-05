<?php

use Pair\Core\View;

class MigrationsViewDefault extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('MIGRATIONS');

		$migrations = $this->model->getItems('Pair\Models\Migration');

		$this->pagination->count = $this->model->countItems('Pair\Models\Migration');

		$this->assign('migrations', $migrations);

	}

}