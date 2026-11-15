<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Posts</title>
</head>

<body>
    <h1>All Posts</h1>

    <a href="{{ route('posts.create')}}" >+ New Post</a>

    @foreach ($posts as $post)
        <div style="border: 2px solid black; margin:2px; padding: 2px 4px;">
            <h2>{{ $post->title }}</h2>
            <p>By: {{ $post->user->name }} | {{ $post->created_at->diffForHumans() }}</p>
            <p>Tags: {{ $post->tags->pluck('name')->join(' , ') ?: 'No tags attached' }}</p>
        </div>
    @endforeach
</body>

</html>
