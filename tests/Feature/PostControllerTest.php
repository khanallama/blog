<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    /**
     * Test guest can view posts index
     */
    public function test_guest_can_view_posts_index(): void
    {
        Post::factory()->count(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('posts.index');
        $response->assertViewHas('posts');
    }

    /**
     * Test guest can view single post
     */
    public function test_guest_can_view_single_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertViewIs('posts.show');
        $response->assertSee($post->title);
    }

    /**
     * Test authenticated user can create post
     */
    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Test Post',
            'body' => 'This is a test post content.',
        ]);

        $response->assertRedirect(route('posts.my'));
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test user can only edit their own post
     */
    public function test_user_can_only_edit_own_post(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $otherUser = User::factory()->create();
        $otherUser->assignRole('user');

        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('posts.edit', $post));

        $response->assertStatus(403);
    }

    /**
     * Test user can update their own post
     */
    public function test_user_can_update_own_post(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'body' => 'Updated content',
        ]);

        $response->assertRedirect(route('posts.my'));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * Test user can delete their own post
     */
    public function test_user_can_delete_own_post(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.delete', $post));

        $response->assertRedirect();
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    /**
     * Test user cannot delete other user's post
     */
    public function test_user_cannot_delete_other_users_post(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $otherUser = User::factory()->create();
        $otherUser->assignRole('user');

        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->delete(route('posts.delete', $post));

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    /**
     * Test admin can delete any post
     */
    public function test_admin_can_delete_any_post(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $user = User::factory()->create();
        $user->assignRole('user');

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->delete(route('admin.posts.delete', $post));

        $response->assertRedirect();
    }

    /**
     * Test my posts shows only user's posts
     */
    public function test_my_posts_shows_only_users_posts(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        Post::factory()->count(3)->create(['user_id' => $user->id]);
        Post::factory()->count(2)->create(); // Other user's posts

        $response = $this->actingAs($user)->get(route('posts.my'));

        $response->assertStatus(200);
        $response->assertViewHas('posts', function ($posts) {
            return $posts->count() === 3;
        });
    }

    /**
     * Test post requires title and body
     */
    public function test_post_requires_title_and_body(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('posts.store'), []);

        $response->assertSessionHasErrors(['title', 'body']);
    }
}




