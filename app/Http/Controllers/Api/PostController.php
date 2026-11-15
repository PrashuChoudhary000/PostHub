<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\IndexPostRequest;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(IndexPostRequest $request)
    {

        Gate::authorize('viewAny', Post::class);

        $filters = $request->validated();

        $query = Post::query()
            ->with('user:id,name')
            ->withCount('comments');


        if(array_key_exists('published', $filters)) 
        {
            $query->where(
                'published',
                $request->boolean('published')
            );
        }

        //used scopes -> defined in Post model 

        if(array_key_exists('user_id', $filters)){
            $query->byAuthor((int) $filters['user_id']);
        }

        if(array_key_exists('tag_id', $filters)){
            $query->tagged((int) $filters['tag_id']);
        }



        $posts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request): JsonResponse
    {

        Gate::authorize('create', Post::class);

        $validated = $request->validated();

        $data = Arr::except($validated, ['tags']);

        $data['published'] = $request->boolean('published');

        $user = $request->user();

        $post = DB::transaction(function () use ($user, $data, $validated) {
            $post = $user->posts()->create($data);

            $post->tags()->sync($validated['tags'] ?? []);

            // throw new \RuntimeException('Testing transaction rollback');

            return $post;
        });

        $post->load([
            'user:id,name',
            'tags:id,name'
        ]);

        $post->loadCount('comments');

        return (new PostResource($post))
        ->response()
        ->setStatusCode(201);
    }

    public function show(Post $post) 
    {

        Gate::authorize('view', $post);

        $post->load([
            'user:id,name',
            'tags:id,name',
        ]);

        $post->loadCount('comments');

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post) 
    {

        Gate::authorize('update', $post);

        $validated = $request->validated();

        DB::transaction( function () use ($validated, $post) {
            $data = Arr::except($validated, ['tags']);

            $post->update($data);

            if(array_key_exists('tags', $validated)) 
            {
                $post->tags()->sync($validated['tags']);
            }
        });


        $post->load([
            'user:id,name',
            'tags:id,name',
        ]);

        $post->loadCount('comments');

        return new PostResource($post);
    }

    public function destroy(Post $post) 
    {

        Gate::authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
