<?php

use Pair\Core\View;
use Pair\Exceptions\AppException;
use Pair\Orm\Collection;

class MigrationsViewDefault extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('MIGRATIONS'));

		if (!$this->model->dbTableCheck()) {
			$this->assign('migrations', new Collection);
			throw new AppException($this->lang('MIGRATIONS_TABLE_NOT_FOUND'));
			$this->app->redirectToUserDefault();
		}

		$migrations = $this->model->getItems('Pair\Models\Migration');

		$this->pagination->count = $this->model->countItems('Pair\Models\Migration');

		$this->assign('migrations', $migrations);

	}

}