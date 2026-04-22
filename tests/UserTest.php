<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {

	/**
	 * Verify that the Pair user password helper returns a verifiable bcrypt hash.
	 */
	public function testPasswordHashVerifiesOriginalPassword(): void {

		$password = 'Unit-Test-Password-123';
		$hash = Pair\Models\User::getHashedPasswordWithSalt($password);

		$this->assertMatchesRegularExpression('/^\\$2y\\$12\\$/', $hash);
		$this->assertTrue(password_verify($password, $hash));
		$this->assertFalse(password_verify('wrong-password', $hash));

	}

	/**
	 * Verify that remember-me token hashes are deterministic and storage-sized.
	 */
	public function testRememberMeTokenHashIsDeterministic(): void {

		$first = Pair\Models\User::getRememberMeTokenHash('remember-token');
		$second = Pair\Models\User::getRememberMeTokenHash('remember-token');
		$different = Pair\Models\User::getRememberMeTokenHash('different-token');

		$this->assertSame($first, $second);
		$this->assertNotSame($first, $different);
		$this->assertSame(32, strlen($first));

	}

	/**
	 * Verify that timezone validation returns a DateTimeZone without DB access.
	 */
	public function testValidTimeZoneFallback(): void {

		$valid = Pair\Models\User::getValidTimeZone('Europe/Rome');
		$fallback = Pair\Models\User::getValidTimeZone('Invalid/Timezone');

		$this->assertSame('Europe/Rome', $valid->getName());
		$this->assertSame(date_default_timezone_get(), $fallback->getName());

	}

	/**
	 * Verify that the Pair user ActiveRecord metadata still matches the expected schema.
	 */
	public function testUserTableMetadata(): void {

		$this->assertSame('users', Pair\Models\User::TABLE_NAME);
		$this->assertSame('id', Pair\Models\User::TABLE_KEY);

	}

}
