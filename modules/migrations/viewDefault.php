<?php

use Pair\Core\View;
use Pair\Exceptions\PairException;
use Pair\Orm\Collection;

class MigrationsViewDefault extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang('MIGRATIONS');

		if (!$this->model->dbTableCheck()) {
			$this->assign('migrations', new Collection);
			PairException::throw($this->lang('MIGRATIONS_TABLE_NOT_FOUND'));
			$this->app->redirectToUserDefault();
		}

		$migrations = $this->model->getItems('Pair\Models\Migration');

		$this->pagination->count = $this->model->countItems('Pair\Models\Migration');

		$this->assign('migrations', $migrations);

	}

}