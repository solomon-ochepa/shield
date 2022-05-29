<?php

namespace Tests\Collectors;

use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Collectors\Auth;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class AuthTest extends TestCase
{
    use DatabaseTestTrait;

    protected $namespace;
    protected $refresh = true;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = fake(UserModel::class, ['username' => 'John Smith']);
    }

    public function testDisplayNotLoggedIn()
    {
        $collector = new Auth();

        $output = $collector->display();

        $this->assertStringContainsString('Not logged in', $output);
    }

    public function testtestDisplayLoggedIn()
    {
        /** @var Session $authenticator */
        $authenticator = service('auth')->getAuthenticator();
        $authenticator->login($this->user);
        $this->user->addGroup('admin', 'beta');

        $collector = new Auth();

        $output = $collector->display();

        $this->assertStringContainsString('Current Use', $output);
        $this->assertStringContainsString('<td>Username</td><td>John Smith</td>', $output);
        $this->assertStringContainsString('<td>Groups</td><td>admin, beta</td>', $output);
    }
}
