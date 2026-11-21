<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::query()
            ->with(['user:id,name', 'tags:id,name'])
            ->where('published', true)
            ->latest()
            ->take(3)
            ->get();

        $totalPosts = Post::where('published', true)->count();

        $totalWriters = User::whereHas('posts', function ($query) {
            $query->where('published', true);
        })->count();

        return view('home', compact(
            'featuredPosts',
            'totalPosts',
            'totalWriters'
        ));
    }
}
