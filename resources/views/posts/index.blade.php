@extends('layouts.app')

@section('title', 'Explore Posts | PostHub')

@section('content')

    <!-- Page Header -->
    <section class="mb-10">

        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

            <div>
                <span
                    class="mb-3 inline-block rounded-full bg-indigo-50 px-3 py-1
                             text-xs font-semibold text-indigo-600">
                    COMMUNITY
                </span>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Explore Posts
                </h1>

                <p class="mt-3 max-w-xl text-slate-500">
                    Discover ideas, stories, and insights shared by our community.
                </p>
            </div>

            @guest
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center rounded-xl
              bg-indigo-600 px-5 py-3 text-sm font-semibold text-white
              shadow-sm transition hover:bg-indigo-700">
                    Join the Community
                </a>
            @endguest
            

        </div>

    </section>


    {{-- Search and Filter Section --}}
    <section class="mb-10 rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

        <form action="{{ route('posts.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-12">

            {{-- Search Input --}}
            <div class="md:col-span-7">

                <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">
                    Search Articles
                </label>

                <input type="text" name="search" id="search" value="{{ request('search') }}" maxlength="100"
                    placeholder="Search by title or content..."
                    class="w-full rounded-xl border border-slate-300
                       bg-white px-4 py-3 text-sm outline-none
                       focus:border-indigo-500
                       focus:ring-2 focus:ring-indigo-100">

            </div>

            {{-- Tag Filter --}}
            <div class="md:col-span-3">

                <label for="tag_id" class="mb-2 block text-sm font-semibold text-slate-700">
                    Filter by Tag
                </label>

                <select name="tag_id" id="tag_id"
                    class="w-full rounded-xl border border-slate-300
                       bg-white px-4 py-3 text-sm outline-none
                       focus:border-indigo-500
                       focus:ring-2 focus:ring-indigo-100">

                    <option value="">All Tags</option>

                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected(request('tag_id') == $tag->id)>
                            {{ $tag->name }}
                        </option>
                    @endforeach

                </select>

            </div>

            {{-- Search Button --}}
            <div class="flex items-end md:col-span-2">

                <button type="submit"
                    class="w-full rounded-xl bg-indigo-600
                       px-5 py-3 text-sm font-semibold
                       text-white transition hover:bg-indigo-700">
                    Search
                </button>

            </div>

        </form>

        {{-- Clear Filters --}}
        @if (request()->filled('search') || request()->filled('tag_id'))
            <div class="mt-4 border-t border-slate-100 pt-4">

                <a href="{{ route('posts.index') }}"
                    class="text-sm font-medium text-indigo-600
                      hover:text-indigo-700">
                    Clear all filters
                </a>

            </div>
        @endif

    </section>


    <!-- Posts Count -->
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Latest Articles
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $posts->total() }} published posts
            </p>
        </div>

        <span
            class="rounded-lg border border-slate-200 bg-white
                     px-3 py-2 text-xs font-medium text-slate-600">

            Newest First

        </span>

    </div>


    <!-- Posts Grid -->
    @if ($posts->count() > 0)

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

            @foreach ($posts as $post)
                <article
                    class="flex h-full flex-col overflow-hidden rounded-2xl
                                border border-slate-200 bg-white shadow-sm
                                transition duration-200 hover:-translate-y-1
                                hover:shadow-lg">

                    <!-- Card Content -->
                    <div class="flex flex-1 flex-col p-6">

                        <!-- Author -->
                        <div class="mb-5 flex items-center gap-3">

                            <div
                                class="flex h-11 w-11 items-center justify-center
                                        rounded-full bg-indigo-100
                                        font-bold text-indigo-700">

                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}

                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $post->user->name ?? 'Unknown Author' }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $post->created_at->format('M d, Y') }}
                                </p>
                            </div>

                        </div>


                        <!-- Title -->
                        <h3 class="mb-3 text-xl font-bold leading-snug text-slate-900">

                            <a href="{{ route('posts.show', $post) }}" class="transition hover:text-indigo-600">

                                {{ $post->title }}

                            </a>

                        </h3>


                        <!-- Description -->
                        <p class="mb-5 flex-1 text-sm leading-7 text-slate-600">

                            {{ \Illuminate\Support\Str::limit(strip_tags($post->body), 150) }}

                        </p>


                        <!-- Tags -->
                        @if ($post->tags->isNotEmpty())
                            <div class="mb-5 flex flex-wrap gap-2">

                                @foreach ($post->tags->take(3) as $tag)
                                    <span
                                        class="rounded-full bg-slate-100
                                                 px-3 py-1 text-xs font-medium
                                                 text-slate-600">

                                        #{{ $tag->name }}

                                    </span>
                                @endforeach

                            </div>
                        @endif


                        <!-- Footer -->
                        <div
                            class="flex items-center justify-between
                                    border-t border-slate-100 pt-4">

                            <a href="{{ route('posts.show', $post) }}"
                                class="text-sm font-semibold text-indigo-600
                                      hover:text-indigo-700">

                                Read Article →

                            </a>

                            @auth
                                @can('update', $post)
                                    <span
                                        class="rounded-full bg-green-50
                                                 px-3 py-1 text-xs font-medium
                                                 text-green-700">

                                        Your Post

                                    </span>
                                @endcan
                            @endauth

                        </div>

                    </div>

                </article>
            @endforeach

        </div>


        <!-- Pagination -->
        <div class="mt-10">

            {{ $posts->links() }}

        </div>
    @else
        <!-- Empty State -->
        <div class="rounded-2xl border border-dashed border-slate-300
                    bg-white px-6 py-20 text-center">

            <div
                class="mx-auto mb-5 flex h-16 w-16 items-center justify-center
                        rounded-full bg-indigo-50 text-3xl">

                📝

            </div>

            <h3 class="text-xl font-bold text-slate-900">
                No posts yet
            </h3>

            <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                There are no published posts available right now.
                Be the first to share something with the community.
            </p>

            @auth
                <a href="{{ route('posts.create') }}"
                    class="mt-6 inline-flex rounded-lg bg-indigo-600
                          px-5 py-3 text-sm font-semibold text-white
                          hover:bg-indigo-700">

                    Create Your First Post

                </a>
            @else
                <a href="{{ route('register') }}"
                    class="mt-6 inline-flex rounded-lg bg-indigo-600
                          px-5 py-3 text-sm font-semibold text-white
                          hover:bg-indigo-700">

                    Get Started

                </a>
            @endauth

        </div>

    @endif

@endsection
