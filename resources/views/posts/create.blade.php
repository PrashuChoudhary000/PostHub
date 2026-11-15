<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Form</title>
</head>

<body>
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        @include('posts._form')
        <button type="submit">Save</button>
    </form>
</body>

</html>
