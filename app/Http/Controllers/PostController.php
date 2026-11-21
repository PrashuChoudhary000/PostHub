<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Step 1: Validate search parameters
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tag_id' => ['nullable', 'integer', 'exists:tags,id'],
        ]);

        // Step 2: Start the published posts query
        $query = Post::query()
            ->with(['user', 'tags'])
            ->where('published', true);

        // Step 3: Search by title or body
        if (! empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");

            });
        }

        // Step 4: Filter by selected tag
        if (! empty($filters['tag_id'])) {

            $query->whereHas('tags', function ($q) use ($filters) {

                $q->where('tags.id', $filters['tag_id']);

            });
        }

        // Step 5: Get matching posts with pagination
        $posts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Step 6: Get available tags for dropdown
        $tags = Tag::orderBy('name')->get();

        // Step 7: Return view
        return view('posts.index', compact('posts', 'tags'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Post::class);

        return view('posts.create', [
            'post' => new Post,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post = DB::transaction(function () use ($request, $validated) {
            $data = Arr::except($validated, ['tags']);
            $data['published'] = $request->boolean('published');

            $post = $request->user()->posts()->create($data);

            $post->tags()->sync($validated['tags'] ?? []);

            return $post;
        });

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (! $post->published) {
            abort_unless(
                auth()->check() &&
                auth()->id() === $post->user_id,
                404
            );
        }

        $post->load(['user', 'tags']);

        $comments = $post->comments()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('update', $post);

        return view('posts.edit', [
            'post' => $post,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ]);

        DB::transaction(function () use ($request, $post, $validated) {
            $data = Arr::except($validated, ['tags']);
            $data['published'] = $request->boolean('published');
            $post->update($data);
            $post->tags()->sync($validated['tags'] ?? []);
        });

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }
}
