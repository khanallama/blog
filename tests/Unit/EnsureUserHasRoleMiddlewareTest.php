<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureUserHasRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class EnsureUserHasRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    /**
     * Test middleware allows user with correct role
     */
    public function test_middleware_allows_user_with_correct_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new EnsureUserHasRole();

        $this->actingAs($admin);

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        }, 'admin');

        $this->assertEquals('OK', $response->getContent());
    }

    /**
     * Test middleware blocks user without correct role
     */
    public function test_middleware_blocks_user_without_correct_role(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage("Unauthorized. This action requires 'admin' role.");

        $user = User::factory()->create();
        $user->assignRole('user');

        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new EnsureUserHasRole();

        $this->actingAs($user);

        $middleware->handle($request, function ($req) {
            return response('OK');
        }, 'admin');
    }

    /**
     * Test middleware blocks unauthenticated user
     */
    public function test_middleware_blocks_unauthenticated_user(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Unauthorized access. Please login first.');

        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new EnsureUserHasRole();

        $middleware->handle($request, function ($req) {
            return response('OK');
        }, 'admin');
    }
}




