<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    /** @test */
    public function regular_user_can_create_post()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Test Post',
            'body' => 'Test content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function regular_user_can_edit_own_post()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'body' => 'Updated content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title'
        ]);
    }

    /** @test */
    public function regular_user_cannot_edit_others_post()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Hacked Title',
            'body' => 'Hacked content',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_cannot_edit_posts_from_public_interface()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Admins manage posts through admin panel, not public interface
        $response = $this->actingAs($admin)->put(route('posts.update', $post), [
            'title' => 'Admin Updated',
            'body' => 'Admin content',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_delete_own_comment()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('comments.delete', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /** @test */
    public function admin_can_delete_any_comment()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin)->delete(route('comments.delete', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
