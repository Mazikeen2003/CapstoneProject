<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_can_check_role_by_slug(): void
    {
        $user = new User();
        $user->role_id = 1;

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('department'));
    }
}
