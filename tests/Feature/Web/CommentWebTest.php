
<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test 1: Guest cannot create a comment
test('guest cannot create a comment', function () {

    $post = Post::factory()->create([
        'published' => true,
    ]);

    $response = $this->post(
        "/posts/{$post->id}/comments",
        ['body' => 'Guest comment']
    );

    $response->assertRedirect(route('login'));

    $this->assertDatabaseCount('comments', 0);
});


// Test 2: Authenticated user can comment
test('authenticated user can create a comment', function () {

    $postOwner = User::factory()->create();
    $commentAuthor = User::factory()->create();

    $post = Post::factory()->for($postOwner)->create([
        'published' => true,
    ]);

    $this->actingAs($commentAuthor);

    $response = $this->post(
        "/posts/{$post->id}/comments",
        ['body' => 'This is a useful article!']
    );

    $response->assertRedirect(
        route('posts.show', $post)
    );

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $commentAuthor->id,
        'body' => 'This is a useful article!',
    ]);
});


// Test 3: User cannot impersonate another comment author
test('comment author comes from authenticated user', function () {

    $user = User::factory()->create();

    $anotherUser = User::factory()->create();

    $post = Post::factory()->for($anotherUser)->create([
        'published' => true,
    ]);

    $this->actingAs($user);

    $response = $this->post(
        "/posts/{$post->id}/comments",
        [
            'body' => 'Ownership test comment',
            'user_id' => $anotherUser->id,
        ]
    );

    $response->assertRedirect(
        route('posts.show', $post)
    );

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'Ownership test comment',
    ]);

    $this->assertDatabaseMissing('comments', [
        'post_id' => $post->id,
        'user_id' => $anotherUser->id,
        'body' => 'Ownership test comment',
    ]);
});


// Test 4: Empty comments are rejected
test('comment body is required', function () {

    $user = User::factory()->create();

    $post = Post::factory()->for($user)->create([
        'published' => true,
    ]);

    $this->actingAs($user);

    $response = $this->post(
        "/posts/{$post->id}/comments",
        ['body' => '']
    );

    $response->assertSessionHasErrors('body');

    $this->assertDatabaseCount('comments', 0);
});


// Test 5: Users cannot comment on another user's draft
test('user cannot comment on another users draft', function () {

    $owner = User::factory()->create();

    $anotherUser = User::factory()->create();

    $post = Post::factory()->for($owner)->create([
        'published' => false,
    ]);

    $this->actingAs($anotherUser);

    $response = $this->post(
        "/posts/{$post->id}/comments",
        ['body' => 'Unauthorized comment']
    );

    $response->assertNotFound();

    $this->assertDatabaseCount('comments', 0);
});


// Test 6: Published post displays its comments
test('published post displays comments with their authors', function () {

    $owner = User::factory()->create();

    $commentAuthor = User::factory()->create([
        'name' => 'Comment Test Author',
    ]);

    $post = Post::factory()->for($owner)->create([
        'published' => true,
    ]);

    $post->comments()->create([
        'user_id' => $commentAuthor->id,
        'body' => 'Visible discussion comment',
    ]);

    $response = $this->get("/posts/{$post->id}");

    $response->assertOk();

    $response->assertSeeText('Comment Test Author');

    $response->assertSeeText('Visible discussion comment');
});
