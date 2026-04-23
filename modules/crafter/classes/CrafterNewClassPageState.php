<?php

declare(strict_types=1);

/**
 * Contratto Pair v4 della lista tabelle non ancora mappate.
 */
final class CrafterNewClassPageState {

	/**
	 * Costruisce il contratto Pair v4 della lista tabelle non ancora mappate.
	 *
	 * @param array<int, string> $unmappedTables
	 */
	public function __construct(
		public array $unmappedTables
	) {

	}

}
