@extends('layouts.app')

@section('title', 'My Posts | PostHub')

@section('content')

    <div class="mx-auto max-w-6xl">

        {{-- Page Header --}}
        <div class="mb-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    My Posts
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage your published articles and drafts.
                </p>
            </div>

            <a href="{{ route('posts.create') }}"
                class="inline-flex items-center justify-center rounded-xl
                  bg-indigo-600 px-5 py-3 text-sm font-semibold
                  text-white hover:bg-indigo-700">
                + Create New Post
            </a>
        </div>

        {{-- Statistics --}}
        <div class="mb-10 grid grid-cols-1 gap-5 sm:grid-cols-3">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Posts</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $totalPosts }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Published</p>
                <p class="mt-3 text-3xl font-bold text-green-600">
                    {{ $publishedPosts }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Drafts</p>
                <p class="mt-3 text-3xl font-bold text-amber-600">
                    {{ $draftPosts }}
                </p>
            </div>

        </div>


        {{-- Post Status Filters --}}
        <div class="mb-6 flex flex-wrap items-center gap-3">

            <a href="{{ route('my-posts.index', ['status' => 'all']) }}" @class([
                'rounded-xl px-5 py-2.5 text-sm font-semibold transition',
                'bg-indigo-600 text-white' => $status === 'all',
                'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' =>
                    $status !== 'all',
            ])>
                All Posts
            </a>

            <a href="{{ route('my-posts.index', ['status' => 'published']) }}" @class([
                'rounded-xl px-5 py-2.5 text-sm font-semibold transition',
                'bg-indigo-600 text-white' => $status === 'published',
                'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' =>
                    $status !== 'published',
            ])>
                Published
            </a>

            <a href="{{ route('my-posts.index', ['status' => 'draft']) }}" @class([
                'rounded-xl px-5 py-2.5 text-sm font-semibold transition',
                'bg-indigo-600 text-white' => $status === 'draft',
                'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' =>
                    $status !== 'draft',
            ])>
                Drafts
            </a>

        </div>


        {{-- Posts List --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">
                    Your Articles
                </h2>
            </div>

            @forelse ($posts as $post)

                <div
                    class="flex flex-col gap-4 border-b border-slate-100
                        px-6 py-5 last:border-0
                        sm:flex-row sm:items-center sm:justify-between">

                    <div class="min-w-0 flex-1">

                        <div class="mb-2 flex flex-wrap items-center gap-3">

                            <h3 class="font-semibold text-slate-900">
                                {{ $post->title }}
                            </h3>

                            @if ($post->published)
                                <span
                                    class="rounded-full bg-green-50 px-3 py-1
                                         text-xs font-semibold text-green-700">
                                    Published
                                </span>
                            @else
                                <span
                                    class="rounded-full bg-amber-50 px-3 py-1
                                         text-xs font-semibold text-amber-700">
                                    Draft
                                </span>
                            @endif

                        </div>

                        <p class="text-sm text-slate-500">
                            {{ \Illuminate\Support\Str::limit($post->body, 110) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Created {{ $post->created_at->format('M d, Y') }}
                        </p>

                        @if ($post->tags->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($post->tags as $tag)
                                    <span
                                        class="rounded-full bg-slate-100 px-2.5 py-1
                                             text-xs text-slate-600">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    {{-- Actions --}}
                    <div class="flex shrink-0 items-center gap-3">

                        <a href="{{ route('posts.show', $post) }}"
                            class="rounded-lg border border-slate-200
                              px-3 py-2 text-sm font-medium
                              text-slate-700 hover:bg-slate-50">
                            View
                        </a>

                        <a href="{{ route('posts.edit', $post) }}"
                            class="rounded-lg bg-indigo-50 px-3 py-2
                              text-sm font-medium text-indigo-700
                              hover:bg-indigo-100">
                            Edit
                        </a>

                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                            onsubmit="return confirm('Delete this post permanently?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="rounded-lg bg-red-50 px-3 py-2
                                       text-sm font-medium text-red-600
                                       hover:bg-red-100">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            @empty

                <div class="px-6 py-16 text-center">

                    <h3 class="text-lg font-semibold text-slate-900">
                        You haven't written any posts yet
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Start by creating your first article.
                    </p>

                    <a href="{{ route('posts.create') }}"
                        class="mt-5 inline-flex rounded-lg bg-indigo-600
                          px-5 py-3 text-sm font-semibold text-white
                          hover:bg-indigo-700">
                        Write Your First Post
                    </a>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $posts->links() }}
        </div>

    </div>

@endsection
