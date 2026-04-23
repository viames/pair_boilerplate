<?php

declare(strict_types=1);

use Pair\Html\Form;
use Pair\Models\Group;
use Pair\Models\User;

/**
 * Contratto Pair v4 del wizard di creazione modulo.
 */
final class CrafterModuleWizardPageState {

	/**
	 * Costruisce il contratto Pair v4 del wizard di creazione modulo.
	 */
	public function __construct(
		public Form $form,
		public iterable $groups
	) {

	}

	/**
	 * Stampa il riepilogo del gruppo nel wizard ACL.
	 */
	public function printGroupDescription(Group $group): void {

		$usersCount = User::countAllObjects(['groupId' => $group->id]);

		print Pair\Helpers\Translator::do('USERS_COUNT', $usersCount)
			. '<br>' . ($group->default ? Pair\Helpers\Translator::do('DEFAULT_GROUP') : '');

	}

}
