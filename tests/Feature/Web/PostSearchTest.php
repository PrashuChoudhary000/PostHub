
<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test 1: Search articles by title
test('users can search published posts by title', function () {

    $user = User::factory()->create();

    Post::factory()->for($user)->create([
        'title' => 'Learning Laravel',
        'body' => 'A tutorial about frameworks.',
        'published' => true,
    ]);

    Post::factory()->for($user)->create([
        'title' => 'Understanding MySQL',
        'body' => 'A database tutorial.',
        'published' => true,
    ]);

    $response = $this->get('/posts?search=Laravel');

    $response->assertOk();

    $response->assertSeeText('Learning Laravel');

    $response->assertDontSeeText('Understanding MySQL');
});


// Test 2: Search articles by body
test('users can search posts by article content', function () {

    $user = User::factory()->create();

    Post::factory()->for($user)->create([
        'title' => 'Backend Development',
        'body' => 'Eloquent relationships are powerful.',
        'published' => true,
    ]);

    Post::factory()->for($user)->create([
        'title' => 'Frontend Development',
        'body' => 'Building responsive interfaces.',
        'published' => true,
    ]);

    $response = $this->get('/posts?search=Eloquent');

    $response->assertOk();

    $response->assertSeeText('Backend Development');

    $response->assertDontSeeText('Frontend Development');
});


// Test 3: Filter posts by tag
test('users can filter published posts by tag', function () {

    $user = User::factory()->create();

    $laravelTag = new Tag();
    $laravelTag->name = 'Laravel';
    $laravelTag->save();

    $databaseTag = new Tag();
    $databaseTag->name = 'Database';
    $databaseTag->save();

    $laravelPost = Post::factory()->for($user)->create([
        'title' => 'Laravel Guide',
        'published' => true,
    ]);

    $databasePost = Post::factory()->for($user)->create([
        'title' => 'Database Guide',
        'published' => true,
    ]);

    $laravelPost->tags()->attach($laravelTag->id);
    $databasePost->tags()->attach($databaseTag->id);

    $response = $this->get(
        "/posts?tag_id={$laravelTag->id}"
    );

    $response->assertOk();

    $response->assertSeeText('Laravel Guide');

    $response->assertDontSeeText('Database Guide');
});


// Test 4: Search and tag filter work together
test('search and tag filters are combined', function () {

    $user = User::factory()->create();

    $tag = new Tag();
    $tag->name = 'Laravel';
    $tag->save();

    $matchingPost = Post::factory()->for($user)->create([
        'title' => 'Laravel Authentication',
        'published' => true,
    ]);

    $otherPost = Post::factory()->for($user)->create([
        'title' => 'Laravel Database Tutorial',
        'published' => true,
    ]);

    $matchingPost->tags()->attach($tag->id);

    $response = $this->get(
        "/posts?search=Laravel&tag_id={$tag->id}"
    );

    $response->assertOk();

    $response->assertSeeText('Laravel Authentication');

    $response->assertDontSeeText('Laravel Database Tutorial');
});


// Test 5: Search must not expose unpublished posts
test('search never exposes unpublished posts', function () {

    $user = User::factory()->create();

    Post::factory()->for($user)->create([
        'title' => 'Secret Laravel Draft',
        'body' => 'Private draft content.',
        'published' => false,
    ]);

    Post::factory()->for($user)->create([
        'title' => 'Public Laravel Tutorial',
        'body' => 'Public tutorial content.',
        'published' => true,
    ]);

    $response = $this->get('/posts?search=Laravel');

    $response->assertOk();

    $response->assertSeeText('Public Laravel Tutorial');

    $response->assertDontSeeText('Secret Laravel Draft');
});
