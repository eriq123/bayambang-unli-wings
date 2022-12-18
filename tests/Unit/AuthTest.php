<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function loginTest($role_id = 2, $isSuperAdmin = null)
    {
        $password = 'password';

        $user = User::factory()->create([
            'role_id' => $role_id,
            'password' => bcrypt($password),
        ]);

        $parameters = [
            'email' => $user->email,
            'password' => $password,
        ];

        if ($isSuperAdmin !== null) $parameters['isSuperAdmin'] = $isSuperAdmin;

        $response = $this->post(route('login.post'), $parameters);

        return compact('response', 'user');
    }

    public function test_admin_login_without_superAdmin_check()
    {
        $result = $this->loginTest(2);
        $result['response']->assertRedirectToRoute('index');
        $this->assertAuthenticatedAs($result['user']);
    }

    public function test_admin_login_with_superAdmin_check()
    {
        $result = $this->loginTest(2, 'true');
        $result['response']->assertRedirectToRoute('login');
        $result['response']->assertInvalid(['email']);
        $this->assertGuest();
    }

    public function test_superAdmin_login_with_superAdmin_check()
    {
        $result = $this->loginTest(1, 'true');
        $result['response']->assertRedirectToRoute('index');
        $this->assertAuthenticatedAs($result['user']);
    }

    public function test_superAdmin_login_without_superAdmin_check()
    {
        $result = $this->loginTest(1);
        $result['response']->assertRedirectToRoute('login');
        $result['response']->assertInvalid(['email']);
        $this->assertGuest();
    }
}
