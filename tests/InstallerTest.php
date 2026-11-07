<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/** Verifies fresh-install safeguards without opening a real database connection. */
final class InstallerTest extends TestCase {

	/** Load the installer class without executing the browser entrypoint below it. */
	public static function setUpBeforeClass(): void {
		$source = file_get_contents(dirname(__DIR__) . '/installer/start.php');
		$end = strpos($source, '$installer = new Installer();');
		if ($end === false) {
			throw new RuntimeException('Installer entrypoint marker not found.');
		}
		eval(substr($source, 5, $end - 5));
	}

	/** Zero affected rows is a successful DDL result and must not stop a fresh installation. */
	public function testDdlSuccess(): void {
		$schema = $this->createStub(PDOStatement::class);
		$schema->method('execute')->willReturn(true);
		$schema->method('fetch')->willReturn(false);
		$pdo = $this->createMock(PDO::class);
		$pdo->method('prepare')->willReturn($schema);
		$pdo->method('setAttribute')->willReturn(true);
		$pdo->expects($this->exactly(4))->method('exec')->willReturn(0);
		$installer = $this->installer($pdo);
		$_POST = ['dbName' => 'pair_test_installer'];
		try {
			$this->assertTrue($installer->createDb());
			$this->assertSame([], $installer->getErrors());
		} finally {
			$_POST = [];
		}
	}

	/** An empty pre-provisioned schema can be initialized even when ALTER reports zero affected rows. */
	public function testEmptyDatabase(): void {
		$schema = $this->createStub(PDOStatement::class);
		$schema->method('execute')->willReturn(true);
		$schema->method('fetch')->willReturn((object)['DEFAULT_CHARACTER_SET_NAME' => 'latin1', 'DEFAULT_COLLATION_NAME' => 'latin1_swedish_ci']);
		$count = $this->createStub(PDOStatement::class);
		$count->method('execute')->willReturn(true);
		$count->method('fetchColumn')->willReturn(0);
		$pdo = $this->createMock(PDO::class);
		$pdo->method('prepare')->willReturnOnConsecutiveCalls($schema, $count);
		$pdo->method('setAttribute')->willReturn(true);
		$pdo->expects($this->exactly(4))->method('exec')->willReturn(0);
		$installer = $this->installer($pdo);
		$_POST = ['dbName' => 'pair_test_installer'];
		try {
			$this->assertTrue($installer->createDb());
			$this->assertSame([], $installer->getErrors());
		} finally {
			$_POST = [];
		}
	}

	/** A populated schema is rejected before ALTER, USE, schema import or seed writes. */
	public function testOccupiedDatabase(): void {
		$schema = $this->createStub(PDOStatement::class);
		$schema->method('execute')->willReturn(true);
		$schema->method('fetch')->willReturn((object)['DEFAULT_CHARACTER_SET_NAME' => 'latin1', 'DEFAULT_COLLATION_NAME' => 'latin1_swedish_ci']);
		$count = $this->createStub(PDOStatement::class);
		$count->method('execute')->willReturn(true);
		$count->method('fetchColumn')->willReturn(1);
		$pdo = $this->createMock(PDO::class);
		$pdo->method('prepare')->willReturnOnConsecutiveCalls($schema, $count);
		$pdo->expects($this->never())->method('exec');
		$installer = $this->installer($pdo);
		$_POST = ['dbName' => 'pair_test_installer'];
		try {
			$this->assertFalse($installer->createDb());
			$this->assertSame(['Use a new or empty database for a fresh installation.'], $installer->getErrors());
		} finally {
			$_POST = [];
		}
	}

	/** Failure to inspect a schema must stop installation before issuing any writes. */
	public function testMetadataFailure(): void {
		$pdo = $this->createMock(PDO::class);
		$pdo->method('prepare')->willThrowException(new PDOException('Synthetic metadata failure'));
		$pdo->expects($this->never())->method('exec');
		$installer = $this->installer($pdo);
		$_POST = ['dbName' => 'pair_test_installer'];
		try {
			$this->assertFalse($installer->createDb());
			$this->assertNotEmpty($installer->getErrors());
		} finally {
			$_POST = [];
		}
	}

	/** Inject a PDO double while keeping the real SQL baseline paths. */
	private function installer(PDO $pdo): Installer {
		$installer = (new ReflectionClass(Installer::class))->newInstanceWithoutConstructor();
		(new ReflectionProperty(Installer::class, 'dbh'))->setValue($installer, $pdo);
		(new ReflectionProperty(Installer::class, 'rootFolder'))->setValue($installer, dirname(__DIR__));
		return $installer;
	}
}
