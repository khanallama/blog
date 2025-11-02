<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test comment belongs to a post
     */
    public function test_comment_belongs_to_post(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Post::class, $comment->post);
        $this->assertEquals($post->id, $comment->post->id);
    }

    /**
     * Test comment belongs to a user
     */
    public function test_comment_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    /**
     * Test comment can have a parent comment
     */
    public function test_comment_can_have_parent(): void
    {
        $parentComment = Comment::factory()->create();
        $childComment = Comment::factory()->create(['parent_id' => $parentComment->id]);

        $this->assertInstanceOf(Comment::class, $childComment->parent);
        $this->assertEquals($parentComment->id, $childComment->parent->id);
    }

    /**
     * Test comment can have replies
     */
    public function test_comment_can_have_replies(): void
    {
        $comment = Comment::factory()->create();
        Comment::factory()->count(3)->create(['parent_id' => $comment->id]);

        $this->assertCount(3, $comment->replies);
        $this->assertInstanceOf(Comment::class, $comment->replies->first());
    }

    /**
     * Test comment fillable attributes
     */
    public function test_comment_fillable_attributes(): void
    {
        $data = [
            'post_id' => 1,
            'user_id' => 1,
            'body' => 'Test Comment',
            'parent_id' => null,
        ];

        $comment = new Comment($data);

        $this->assertEquals('Test Comment', $comment->body);
        $this->assertEquals(1, $comment->post_id);
        $this->assertEquals(1, $comment->user_id);
    }
}




