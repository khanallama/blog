<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommentControllerTest extends TestCase
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
     * Test authenticated user can create comment
     */
    public function test_authenticated_user_can_create_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.comments.store', $post), [
            'body' => 'This is a test comment.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'body' => 'This is a test comment.',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    /**
     * Test guest cannot create comment
     */
    public function test_guest_cannot_create_comment(): void
    {
        $post = Post::factory()->create();

        $response = $this->post(route('posts.comments.store', $post), [
            'body' => 'Test comment',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can delete their own comment
     */
    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($user)->delete(route('comments.delete', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test user cannot delete other user's comment
     */
    public function test_user_cannot_delete_other_users_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $otherUser = User::factory()->create();
        $otherUser->assignRole('user');
        $post = Post::factory()->create();
        
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($user)->delete(route('comments.delete', $comment));

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    /**
     * Test admin can delete any comment
     */
    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create();
        
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('comments.delete', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test comment requires body
     */
    public function test_comment_requires_body(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.comments.store', $post), []);

        $response->assertSessionHasErrors(['body']);
    }

    /**
     * Test user can view edit page for their own comment
     */
    public function test_user_can_view_edit_page_for_own_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($user)->get(route('comments.edit', $comment));

        $response->assertStatus(200);
        $response->assertViewIs('comments.edit');
        $response->assertViewHas('comment');
    }

    /**
     * Test user cannot view edit page for other user's comment
     */
    public function test_user_cannot_view_edit_page_for_other_users_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $otherUser = User::factory()->create();
        $otherUser->assignRole('user');
        $post = Post::factory()->create();
        
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($user)->get(route('comments.edit', $comment));

        $response->assertStatus(403);
    }

    /**
     * Test user can update their own comment
     */
    public function test_user_can_update_own_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => 'Original comment',
        ]);

        $response = $this->actingAs($user)->put(route('comments.update', $comment), [
            'body' => 'Updated comment',
        ]);

        $response->assertRedirect(route('posts.show', $post->id));
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment',
        ]);
    }

    /**
     * Test user cannot update other user's comment
     */
    public function test_user_cannot_update_other_users_comment(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $otherUser = User::factory()->create();
        $otherUser->assignRole('user');
        $post = Post::factory()->create();
        
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $post->id,
            'body' => 'Original comment',
        ]);

        $response = $this->actingAs($user)->put(route('comments.update', $comment), [
            'body' => 'Attempted update',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Original comment',
        ]);
    }

    /**
     * Test guest cannot edit comment
     */
    public function test_guest_cannot_edit_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $response = $this->get(route('comments.edit', $comment));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test guest cannot update comment
     */
    public function test_guest_cannot_update_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $response = $this->put(route('comments.update', $comment), [
            'body' => 'Updated comment',
        ]);

        $response->assertRedirect(route('login'));
    }
}




