<?php

declare(strict_types=1);

/**
 * Contratto Pair v4 della pagina playground tecnica.
 */
final class CrafterPlaygroundPageState {

	/**
	 * Costruisce il contratto Pair v4 della pagina playground.
	 *
	 * @param array<int, mixed> $results
	 */
	public function __construct(
		public array $results
	) {

	}

}
