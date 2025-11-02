<?php

namespace Tests\Unit;

use App\Http\Middleware\LogActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LogActivityMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'user']);
    }

    /**
     * Test middleware logs authenticated user activity
     */
    public function test_middleware_logs_authenticated_user_activity(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user);

        Log::shouldReceive('channel')
            ->with('user_activity')
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->atLeast()->once(); // At least once for general activity

        $request = Request::create('/posts', 'GET');
        $middleware = new LogActivity();

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());
    }

    /**
     * Test middleware does not log guest activity
     */
    public function test_middleware_does_not_log_guest_activity(): void
    {
        Log::shouldReceive('channel')->never();
        Log::shouldReceive('info')->never();

        $request = Request::create('/posts', 'GET');
        $middleware = new LogActivity();

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());
    }
}

