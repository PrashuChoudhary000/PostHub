<h1>Edit Post</h1>

<form method="POST" action="{{ route('posts.update', $post) }}">
    @csrf
    @method('PUT')
    @include('posts._form')
    <button type="submit">Update</button>
</form>