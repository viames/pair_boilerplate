<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Html\Form;

/**
 * Typed state for the add-ACL page.
 */
final readonly class UsersAclNewPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the add-ACL page state.
	 *
	 * @param	UsersAclRuleItemState[]	$rules	ACL rules that can still be assigned to the group.
	 */
	public function __construct(
		public Form $form,
		public int $groupId,
		public string $groupName,
		public array $rules
	) {}

	/**
	 * Export the page state as an array for debugging and tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'form' => $this->form,
			'groupId' => $this->groupId,
			'groupName' => $this->groupName,
			'rules' => array_map(
				static fn (UsersAclRuleItemState $rule): array => $rule->toArray(),
				$this->rules
			),
		];

	}

}
