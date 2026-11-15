use App\Models\Post;

{{-- <h1>Posts</h1>

@forelse ( $posts as $post )
    <article>
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->body }}</p>
    </article>
@empty
    <p>No posts yet.</p>
@endforelse  --}}


dd(Post::where('published', true)->get());