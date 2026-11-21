<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPostController extends Controller
{
    public function index(Request $request)
    {
        // Validate the status filter.
        $filters = $request->validate([
            'status' => ['sometimes', 'in:all,published,draft'],
        ]);

        $status = $filters['status'] ?? 'all';

        $user = $request->user();

        // Statistics always include all of the user's posts.
        $totalPosts = $user->posts()->count();

        $publishedPosts = $user->posts()
            ->where('published', true)
            ->count();

        $draftPosts = $user->posts()
            ->where('published', false)
            ->count();

        // Start with posts belonging to the authenticated user.
        $query = $user->posts()->with('tags');

        // Apply the selected status filter.
        if ($status === 'published') {
            $query->where('published', true);
        } elseif ($status === 'draft') {
            $query->where('published', false);
        }

        $posts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('posts.my-posts', compact(
            'posts',
            'totalPosts',
            'publishedPosts',
            'draftPosts',
            'status'
        ));
    }
}
