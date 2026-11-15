<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr as SupportArr;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'tags'])->latest()->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create', [
            'post' => new Post,
            'users' => User::all(),
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $data = SupportArr::except($validated, ['tags']);
        $data['published'] = $request->boolean('published');

        $post = Post::create($data);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'tags']);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post,
            'users' => User::all(),
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $data = SupportArr::except($validated, ['tags']);
        $data['published'] = $request->boolean('published');

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfulyy');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }
}
