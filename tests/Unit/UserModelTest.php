<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user has many posts
     */
    public function test_user_has_many_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(5)->create(['user_id' => $user->id]);

        $this->assertCount(5, $user->posts);
        $this->assertInstanceOf(Post::class, $user->posts->first());
    }

    /**
     * Test user has many comments
     */
    public function test_user_has_many_comments(): void
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(3)->create();
        
        foreach ($posts as $post) {
            Comment::factory()->create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
        }

        $this->assertCount(3, $user->comments);
        $this->assertInstanceOf(Comment::class, $user->comments->first());
    }

    /**
     * Test user can have roles
     */
    public function test_user_can_have_roles(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertCount(1, $user->roles);
    }

    /**
     * Test user password is hashed
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    /**
     * Test user fillable attributes
     */
    public function test_user_fillable_attributes(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ];

        $user = new User($data);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }
}




