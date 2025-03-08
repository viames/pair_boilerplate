<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Exceptions\AppException;
use Pair\Exceptions\CriticalException;
use Pair\Exceptions\ErrorCodes;
use Pair\Exceptions\PairException;
use Pair\Html\Breadcrumb;

class CrafterViewPlayground extends View {

	public function render(): void {

		$this->setPageTitle($this->lang('CRAFTER'));

		Breadcrumb::path([
			'Playground' => 'crafter/playground',
		]);

		$results = [];

		$choise = Router::get(0);

		switch ($choise) {

			case 'AppException':
				throw new AppException('AppException message');

			case 'PairException':
				throw new PairException('PairException message');
				
			case 'PairExceptionWithCode':
				throw new PairException('PairException message', ErrorCodes::VIEW_ERROR);

			case 'CriticalException':
				throw new CriticalException('CriticalException message');

			case 'FailureQuery':
				Pair\Orm\Database::load('SELECT `test` FROM `users`');

			default:
				break;

		}

		$this->assign('results', $results);

	}

}