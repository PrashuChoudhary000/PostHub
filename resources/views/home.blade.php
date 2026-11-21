
@extends('layouts.app')

@section('title', 'Welcome to PostHub')

@section('content')

{{-- Hero --}}
<section class="py-12 text-center sm:py-20">

    <span class="inline-flex rounded-full bg-indigo-50
                 px-4 py-2 text-sm font-semibold text-indigo-600">
        A Community for Curious Minds
    </span>

    <h1 class="mx-auto mt-8 max-w-4xl text-4xl font-bold
               tracking-tight text-slate-900
               sm:text-6xl sm:leading-tight">
        Discover ideas.
        <span class="text-indigo-600">Share knowledge.</span>
        Inspire others.
    </h1>

    <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-500">
        PostHub is a place to discover interesting articles,
        share what you've learned, and connect through meaningful discussions.
    </p>

    <div class="mt-9 flex flex-wrap justify-center gap-4">

        <a href="{{ route('posts.index') }}"
           class="rounded-xl bg-indigo-600 px-7 py-3.5
                  font-semibold text-white hover:bg-indigo-700">
            Explore Articles
        </a>

        @guest
            <a href="{{ route('register') }}"
               class="rounded-xl border border-slate-300 bg-white
                      px-7 py-3.5 font-semibold text-slate-700
                      hover:bg-slate-50">
                Start Writing
            </a>
        @else
            <a href="{{ route('my-posts.index') }}"
               class="rounded-xl border border-slate-300 bg-white
                      px-7 py-3.5 font-semibold text-slate-700
                      hover:bg-slate-50">
                My Dashboard
            </a>
        @endguest

    </div>

</section>

{{-- Community Statistics --}}
<section class="mb-20 grid grid-cols-1 gap-5 sm:grid-cols-2">

    <div class="rounded-2xl border border-slate-200 bg-white
                p-8 text-center shadow-sm">
        <p class="text-4xl font-bold text-indigo-600">
            {{ $totalPosts }}
        </p>
        <p class="mt-2 text-sm text-slate-500">
            Published Articles
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white
                p-8 text-center shadow-sm">
        <p class="text-4xl font-bold text-indigo-600">
            {{ $totalWriters }}
        </p>
        <p class="mt-2 text-sm text-slate-500">
            Community Writers
        </p>
    </div>

</section>

{{-- Latest Articles --}}
<section class="pb-16">

    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">

        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Latest from the Community
            </h2>
            <p class="mt-2 text-slate-500">
                Fresh ideas and stories worth reading.
            </p>
        </div>

        <a href="{{ route('posts.index') }}"
           class="text-sm font-semibold text-indigo-600
                  hover:text-indigo-700">
            View All Articles →
        </a>

    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

        @forelse ($featuredPosts as $post)

            <article class="flex flex-col rounded-2xl border
                            border-slate-200 bg-white p-6
                            shadow-sm transition hover:shadow-lg">

                <div class="mb-5 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-full bg-indigo-100
                                font-bold text-indigo-700">
                        {{ strtoupper(substr($post->user?->name ?? 'U', 0, 1)) }}
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $post->user?->name ?? 'Unknown Author' }}
                        </p>

                        <p class="text-xs text-slate-400">
                            {{ $post->created_at->format('M d, Y') }}
                        </p>
                    </div>

                </div>

                <h3 class="text-xl font-bold text-slate-900">
                    <a href="{{ route('posts.show', $post) }}"
                       class="hover:text-indigo-600">
                        {{ $post->title }}
                    </a>
                </h3>

                <p class="mt-3 flex-1 text-sm leading-7 text-slate-600">
                    {{ \Illuminate\Support\Str::limit(strip_tags($post->body), 130) }}
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach ($post->tags->take(3) as $tag)
                        <span class="rounded-full bg-slate-100
                                     px-3 py-1 text-xs text-slate-600">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <a href="{{ route('posts.show', $post) }}"
                       class="text-sm font-semibold text-indigo-600
                              hover:text-indigo-700">
                        Read Article →
                    </a>
                </div>

            </article>

        @empty

            <div class="rounded-2xl border border-dashed border-slate-300
                        bg-white p-10 text-center md:col-span-3">

                <h3 class="text-lg font-semibold text-slate-900">
                    No articles published yet
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    New community articles will appear here.
                </p>

            </div>

        @endforelse

    </div>

</section>

@endsection
