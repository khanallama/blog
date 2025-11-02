<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test post belongs to a user
     */
    public function test_post_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    /**
     * Test post has many comments
     */
    public function test_post_has_many_comments(): void
    {
        $post = Post::factory()->create();
        Comment::factory()->count(3)->create(['post_id' => $post->id]);

        $this->assertCount(3, $post->comments);
        $this->assertInstanceOf(Comment::class, $post->comments->first());
    }

    /**
     * Test post can be soft deleted
     */
    public function test_post_can_be_soft_deleted(): void
    {
        $post = Post::factory()->create();
        $postId = $post->id;

        $post->delete();

        $this->assertSoftDeleted('posts', ['id' => $postId]);
        $this->assertNotNull(Post::withTrashed()->find($postId)->deleted_at);
    }

    /**
     * Test post can be restored
     */
    public function test_post_can_be_restored(): void
    {
        $post = Post::factory()->create();
        $post->delete();

        $post->restore();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test post published scope
     */
    public function test_post_published_scope(): void
    {
        Post::factory()->create([
            'published_at' => now()->subDay(),
        ]);
        
        Post::factory()->create([
            'published_at' => now()->addDay(),
        ]);

        $publishedPosts = Post::published()->get();

        $this->assertEquals(1, $publishedPosts->count());
    }

    /**
     * Test post fillable attributes
     */
    public function test_post_fillable_attributes(): void
    {
        $data = [
            'user_id' => 1,
            'title' => 'Test Title',
            'body' => 'Test Body',
            'published_at' => now(),
        ];

        $post = new Post($data);

        $this->assertEquals('Test Title', $post->title);
        $this->assertEquals('Test Body', $post->body);
        $this->assertEquals(1, $post->user_id);
    }

    /**
     * Test post relationships are loaded correctly
     */
    public function test_post_eager_loading(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Comment::factory()->count(2)->create(['post_id' => $post->id]);

        $loadedPost = Post::with(['user', 'comments'])->first();

        $this->assertTrue($loadedPost->relationLoaded('user'));
        $this->assertTrue($loadedPost->relationLoaded('comments'));
    }
}




