<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Test 1: Guests cannot create posts.
 */
test('guest cannot create a post', function () {

    $response = $this->post('/posts', [
        'title' => 'Guest Post',
        'body' => 'This post should not be created.',
    ]);

    $response->assertRedirect(route('login'));

    $this->assertDatabaseMissing('posts', [
        'title' => 'Guest Post',
    ]);
});


/**
 * Test 2: Authenticated users can create posts.
 */
test('authenticated user can create a post', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/posts', [
        'title' => 'My First Post',
        'body' => 'This is my first web post.',
        'published' => 1,
    ]);

    $post = Post::where('title', 'My First Post')->firstOrFail();

    $this->assertEquals($user->id, $post->user_id);

    $response->assertRedirect(route('posts.show', $post));
});


/**
 * Test 3: Users cannot assign posts to another author.
 */
test('user cannot create a post for another user', function () {

    $user = User::factory()->create();

    $anotherUser = User::factory()->create();

    $this->actingAs($user);

    $this->post('/posts', [
        'title' => 'Ownership Test',
        'body' => 'Testing post ownership.',
        'user_id' => $anotherUser->id,
    ]);

    $post = Post::where('title', 'Ownership Test')->firstOrFail();

    $this->assertEquals($user->id, $post->user_id);

    $this->assertNotEquals($anotherUser->id, $post->user_id);
});


/**
 * Test 4: Another user cannot update a post.
 */
test('another user cannot update the post', function () {

    $owner = User::factory()->create();

    $anotherUser = User::factory()->create();

    $post = Post::factory()->for($owner)->create([
        'title' => 'Original Title',
    ]);

    $this->actingAs($anotherUser);

    $response = $this->put("/posts/{$post->id}", [
        'title' => 'Updated Title',
        'body' => 'Unauthorized update.',
    ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Original Title',
    ]);
});


/**
 * Test 5: Authors can update their own posts.
 */
test('author can update their own post', function () {

    $user = User::factory()->create();

    $post = Post::factory()->for($user)->create();

    $this->actingAs($user);

    $response = $this->put("/posts/{$post->id}", [
        'title' => 'Updated By Author',
        'body' => 'The author updated this post.',
        'published' => 1,
    ]);

    $response->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated By Author',
    ]);
});

test('another user cannot delete the post', function () {

    $owner = User::factory()->create();

    $anotherUser = User::factory()->create();

    $post = Post::factory()->for($owner)->create();

    $this->actingAs($anotherUser);

    $response = $this->delete("/posts/{$post->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
    ]);
});

test('author can delete their own post', function () {

    $user = User::factory()->create();

    $post = Post::factory()->for($user)->create();

    $this->actingAs($user);

    $response = $this->delete("/posts/{$post->id}");

    $response->assertRedirect(route('posts.index'));

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});

test('guest cannot view an unpublished post', function () {

    $user = User::factory()->create();

    $post = Post::factory()->for($user)->create([
        'published' => false,
    ]);

    $response = $this->get("/posts/{$post->id}");

    $response->assertNotFound();
});

test('author can view their own unpublished post', function () {

    $user = User::factory()->create();

    $post = Post::factory()->for($user)->create([
        'published' => false,
    ]);

    $this->actingAs($user);

    $response = $this->get("/posts/{$post->id}");

    $response->assertOk();

    $response->assertViewHas('post', function ($viewPost) use ($post) {
        return $viewPost->id === $post->id;
    });
});



test('my posts shows only authenticated users posts', function () {

    $user = User::factory()->create();

    $anotherUser = User::factory()->create();

    $myPost = Post::factory()->for($user)->create([
        'title' => 'My Private Draft',
        'published' => false,
    ]);

    Post::factory()->for($anotherUser)->create([
        'title' => 'Another Users Post',
        'published' => true,
    ]);

    $this->actingAs($user);

    $response = $this->get('/my-posts');

    $response->assertOk();

    $response->assertSeeText('My Private Draft');

    $response->assertDontSeeText('Another Users Post');
});



test('my posts filters published and draft posts', function () {

    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    Post::factory()->for($user)->create([
        'title' => 'My Published Article',
        'published' => true,
    ]);

    Post::factory()->for($user)->create([
        'title' => 'My Private Draft',
        'published' => false,
    ]);

    Post::factory()->for($otherUser)->create([
        'title' => 'Another Users Published Article',
        'published' => true,
    ]);

    $this->actingAs($user);

    // Filter published posts
    $publishedResponse = $this->get('/my-posts?status=published');

    $publishedResponse->assertOk();
    $publishedResponse->assertSeeText('My Published Article');
    $publishedResponse->assertDontSeeText('My Private Draft');
    $publishedResponse->assertDontSeeText('Another Users Published Article');

    // Filter draft posts
    $draftResponse = $this->get('/my-posts?status=draft');

    $draftResponse->assertOk();
    $draftResponse->assertSeeText('My Private Draft');
    $draftResponse->assertDontSeeText('My Published Article');
    $draftResponse->assertDontSeeText('Another Users Published Article');
});

