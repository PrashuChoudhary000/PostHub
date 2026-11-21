<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        //User cannot comment on another user's draft post
        abort_unless(
            $post->published ||
            $post->user_id === $request->user()->id,
            404
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000']
        ]);

        //The relationship automatically assigns post_id.
        $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment added successfully.');
    }
}
