<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase {

    /** @test */
    public function createNewUser(): void {

        $user = new Pair\User();

        $user->groupId = 1;
        $user->localeId = 82;
        $user->username = 'unit@test.com';
        $user->hash = '';
        $user->name = 'Unit';
        $user->surname = 'Test';
        $user->email = 'unit@test.com';
        $user->admin = FALSE;
        $user->enabled = FALSE;
        $user->lastLogin = NULL;
        $user->faults = 0;
        $user->pwReset = NULL;

        $result = $user->store();

        $this->assertTrue($result);
        $this->assertTrue($user->areKeysPopulated());

        $user = new Pair\User($user->id);
        $user->delete();
    
    }

    /** @test */
    public function createWrongUser(): void {

        $user = new Pair\User();

        // missing required data

        try {
            $result = $user->store();
        } catch (Exception $e) {
            $this->assertEquals('Missing required data', $e->getMessage());
        }

        $this->assertFalse($result);
        $this->assertFalse($user->areKeysPopulated());
    
    }

}