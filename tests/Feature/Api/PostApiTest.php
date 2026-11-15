<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    
    use RefreshDatabase;

    private function createPostFor(User $user) :Post 
    {
        return $user->posts()->create([
            'title' => 'Original title',
            'body' => 'Original body',
            'published' => true,
        ]);
    }

    public function test_guest_cannot_create_a_post(): void
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'A new post',
            'body' => 'Post body'
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_authenticated_user_can_create_a_post(): void 
    {
        $user = user::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'My API post',
            'body' => 'Created during a test.',
            'published' => true,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', 'My API post')
            ->assertJsonPath('data.author.id', $user->id);

        $this->assertDatabaseHas('posts', [
            'title' => 'My API post',
            'user_id' => $user->id,
            'published' =>true
        ]);
    }

    public function test_title_is_required_when_creating_a_post(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'body' => 'A post without a title'
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_author_can_update_their_post(): void
    {
        $author = User::factory()->create();

        $post = $this->createPostFor($author);

        Sanctum::actingAs($author);

        $response = $this->patchJson("/api/posts/{$post->id}", [
            'title' => 'Updated title'
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.title', 'Updated title');

        $this->assertDatabaseHas('posts',  [
            'id' => $post->id,
            'title' => 'Updated title'
        ]);
    }

    public function test_another_user_cannot_update_the_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        $post = $this->createPostFor($author);

        Sanctum::actingAs($otherUser);

        $response = $this->patchJson("/api/posts/{$post->id}", [
            'title' => 'Unauthorized change'
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Original title'
        ]);
    }

    public function test_selected_tags_are_attached_to_a_new_post(): void
    {
        $user = User::factory()->create();

        $tag = new Tag();

        $tag->name = 'Laravel';

        $tag->save();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'Tagged post',
            'body' => 'A post with a tag',
            'tags' => [$tag->id]
        ]);

        $response->assertCreated();

        $postId = $response->json('data.id');

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $postId,
            'tag_id' => $tag->id,
        ]);
    }

    public function  test_patch_preserves_omitted_fields_and_tags(): void
    {
        $author = user::factory()->create();

        $post = $this->createPostFor($author);

        $tag = new Tag();
        $tag->name = 'Eloquent';
        $tag->save();

        $post->tags()->attach($tag->id);

        Sanctum::actingAs($author);

        $response = $this->patchJson("/api/posts/{$post->id}", [
            'title' => 'Only the title changed',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => 'Original body',
            'published' => true,
        ]);

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_author_can_delete_their_post(): void
    {
        $author = User::factory()->create();

        $post = $this->createPostFor($author);

        Sanctum::actingAs($author);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_another_user_cannot_delete_the_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        $post = $this->createPostFor($author);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_missing_post_returns_not_found(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/posts/999999');

        $response->assertNotFound();
    
    }
}
