@if ($errors->any())
    <ul style="color: red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif


<p>
    <label for="">Title</label><br>
    <input type="text" name="title" value="{{ old('title', $post->title) }}">
</p>

<p>
    <label for="">Body</label><br>
    <textarea name="body">{{ old('body', $post->body) }}</textarea>
</p>

<p>
    <label for="user_id">Author</label>
    <select name="user_id" id="user_id">
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                @selected(old('user_id', $post->user_id) == $user->id)>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</p>

<p>
    <label for="">Tags</label><br>
    @foreach ($tags as $tag)
        <label>
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
            @checked(in_array($tag->id, old('tags', $post->tags->pluck('id')->all())))>
                {{ $tag->name }}
        </label>
    @endforeach
</p>


<p>
    <label>
        <input type="checkbox" name="published" value="1" 
        @checked(old('published', $post->published))>
        Published   
    </label>
</p>