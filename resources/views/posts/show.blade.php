<h1>Title: {{ $post->title }}</h1>

<p>By {{ $post->user->name }} | {{ $post->published ? 'Published' : 'Draft' }}</p>

<p>{{ $post->body }}</p>

<p>Tags: {{ $post->tags->pluck('name')->join(', ') }}</p>

<a href="{{ route('posts.edit', $post) }}">Edit</a>

<a href="{{ route('posts.index') }}">Back</a><br><br>

<form method="POST" action="{{ route('posts.destroy', $post) }}">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Delete this post?')">Delete Post</button>
</form>