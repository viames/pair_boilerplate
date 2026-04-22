<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;
use Pair\Models\Group;
use Pair\Models\User;
use Pair\Orm\Collection;

/**
 * Typed state for the module creation wizard.
 */
final readonly class CrafterNewModuleWizardPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 */
	public function __construct(
		public Form $form,
		public Collection $groups
	) {}

	/**
	 * Return the descriptive text for a group ACL checkbox.
	 */
	public function groupDescription(Group $group): string {

		$usersCount = User::countAllObjects(['groupId' => $group->id]);

		return BoilerplateLayout::translate('USERS_COUNT', (string)$usersCount)
			. '<br>' . ($group->default ? BoilerplateLayout::translate('DEFAULT_GROUP') : '');

	}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'groups' => $this->groups,
		];

	}

}
