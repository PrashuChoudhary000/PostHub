<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

class CommentApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

   public function test_guest_cannot_list_comments(): void
   {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}/comments");

        $response->assertUnauthorized();
   }

   public function test_only_requested_post_comments_are_returned(): void
   {
          $user = User::factory()-> create();

          Sanctum::actingAs($user);

          $postA = Post::factory()->for($user)->create();

          $postB = Post::factory()->for($user)->create();

          $commentA = $postA->comments()->create([
               'user_id' => $user->id,
               'body' => 'Comment belongs tot Post A'
          ]);

          $commentB = $postB->comments()->create([
               'user_id' => $user->id,
               'body' => 'Comment belongs to Post B'
          ]);

          $response = $this->getJson("/api/posts/{$postA->id}/comments");

          $response->assertOk();

          $response->assertJsonPath('data.0.id', $commentA->id);

          $response->assertJsonMissing([
               'id' => $commentB->id
          ]);
   }

   public function test_comment_author_details_are_returned(): void
   {
          $postOwner = User::factory()->create();

          $commentAuthor = User::factory()->create([
               'name' => 'Prashu Chaudhary'
          ]);

          Sanctum::actingAs($postOwner);

          $post = Post::factory()->for($postOwner)->create();

          $post->comments()->create([
               'user_id' => $commentAuthor->id,
               'body' => "Comment created by Prashu Chaudhary" 
          ]);

          $response = $this->getJson("/api/posts/{$post->id}/comments");

          $response->assertOk();

          $response->assertJsonPath(
               'data.0.author.id',
               $commentAuthor->id
          );

          $response->assertJsonPath(
               'data.0.author.name',
               'Prashu Chaudhary'
          );

          $response->assertJsonMissingPath('data.0.author.password');
   }

   public function test_comments_are_paginated(): void
   {
          $user = User::factory()->create();

          Sanctum::actingAs($user);

          $post = Post::factory()->for($user)->create();

          for($i = 1; $i <=11; $i++) {
               $post->comments()->create([
                    'user_id' => $user->id,
                    'body' => "Comment number {$i}"
               ]);
          }

          $page1 = $this->getJson("/api/posts/{$post->id}/comments?page=1");

          $page1->assertJsonCount(10, 'data');

          $page1->assertJsonPath('meta.total', 11);

          $page2 = $this->getJson("/api/posts/{$post->id}/comments?page=2");

          $page2->assertJsonCount(1, 'data');

          $page1->assertOk();

          $page2->assertOk();


   }

   public function test_newest_comments_are_returned_first(): void
   {
          $user = User::factory()->create();

          Sanctum::actingAs($user);

          $post = Post::factory()->for($user)->create();

          $oldComment = $post->comments()->create([
               'user_id' => $user->id,
               'body' => 'Old comment',
               'created_at' => now()->subDays(2)
          ]);


          $middleComment = $post->comments()->create([
               'user_id' => $user->id,
               'body' => 'Middle comment',
               'created_at' => now()->subDays(1)
          ]);


          $newComment = $post->comments()->create([
               'user_id' => $user->id,
               'body' => 'New comment',
               'created_at' => now()
          ]);

          $response = $this->getJson("/api/posts/{$post->id}/comments");

          $response->assertJsonPath('data.0.id', $newComment->id);
          $response->assertJsonPath('data.1.id', $middleComment->id);
          $response->assertJsonPath('data.2.id', $oldComment->id);

          $response->assertOk();
   }

   public function test_missing_post_returns_not_found(): void
   {
          $user = User::factory()->create();

          Sanctum::actingAs($user);

          $response = $this->getJson("/api/posts/999999/comments");

          $response->assertNotFound();
   }
}
