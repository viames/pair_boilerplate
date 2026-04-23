<?php

declare(strict_types=1);

/**
 * Contratto Pair v4 della lista classi non ancora materializzate come tabella.
 */
final class CrafterNewTablePageState {

	/**
	 * Costruisce il contratto Pair v4 della lista classi non ancora materializzate come tabella.
	 *
	 * @param array<int, string> $unmappedClasses
	 */
	public function __construct(
		public array $unmappedClasses
	) {

	}

}
