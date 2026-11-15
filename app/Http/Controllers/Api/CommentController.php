<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post)
    {
        Gate::authorize('view', $post);

        $validated = $request->validated();

        $comment  = $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        $comment->load('user:id,name');

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Post $post)
    {
        Gate::authorize('view', $post);

        $comments = $post->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        
        return CommentResource::collection($comments);
    }
}
