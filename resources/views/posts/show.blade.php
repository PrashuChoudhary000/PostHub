@extends('layouts.app')

@section('title', $post->title . ' | PostHub')

@section('content')

    <div class="mx-auto max-w-4xl">

        <!-- Back Link -->
        <a href="{{ route('posts.index') }}"
            class="mb-8 inline-flex items-center gap-2 text-sm
                  font-medium text-slate-500 transition hover:text-indigo-600">

            ← Back to Posts

        </a>

        <!-- Article -->
        <article class="overflow-hidden rounded-2xl border border-slate-200
                        bg-white shadow-sm">

            <div class="p-6 sm:p-10">

                <!-- Publication Status -->
                <div class="mb-6">

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


                <!-- Title -->
                <h1
                    class="mb-6 text-3xl font-bold leading-tight
                           tracking-tight text-slate-900 sm:text-4xl">

                    {{ $post->title }}

                </h1>


                <!-- Author Details -->
                <div class="mb-8 flex items-center gap-4
                            border-b border-slate-100 pb-8">

                    <div
                        class="flex h-12 w-12 items-center justify-center
                                rounded-full bg-indigo-100
                                text-lg font-bold text-indigo-700">

                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}

                    </div>

                    <div>

                        <p class="font-semibold text-slate-900">
                            {{ $post->user->name ?? 'Unknown Author' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $post->created_at->format('F d, Y') }}
                        </p>

                    </div>

                </div>


                <!-- Full Article Content -->
                <div
                    class="mb-10 whitespace-pre-line break-words
                            text-base leading-8 text-slate-700">

                    {{ $post->body }}

                </div>


                <!-- Tags -->
                @if ($post->tags->isNotEmpty())

                    <div class="mb-8 flex flex-wrap gap-2">

                        @foreach ($post->tags as $tag)
                            <span
                                class="rounded-full bg-indigo-50 px-3 py-1.5
                                         text-xs font-semibold text-indigo-700">

                                #{{ $tag->name }}

                            </span>
                        @endforeach

                    </div>

                @endif


                <!-- Author Actions -->
                @auth

                    @can('update', $post)
                        <div
                            class="flex flex-wrap items-center gap-3
                                    border-t border-slate-100 pt-6">

                            <!-- Edit -->
                            <a href="{{ route('posts.edit', $post) }}"
                                class="rounded-lg bg-indigo-600 px-5 py-2.5
                                      text-sm font-semibold text-white
                                      transition hover:bg-indigo-700">

                                Edit Post

                            </a>


                            <!-- Delete -->
                            @can('delete', $post)
                                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this post?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="rounded-lg border border-red-200
                                               bg-red-50 px-5 py-2.5
                                               text-sm font-semibold text-red-600
                                               transition hover:bg-red-100">

                                        Delete Post

                                    </button>

                                </form>
                            @endcan

                        </div>
                    @endcan

                @endauth

            </div>

        </article>


        {{-- Comments Section --}}
        <section class="mt-10">

            {{-- Heading --}}
            <div class="mb-6 flex items-center justify-between">

                <div>
                    <h2 class="text-2xl font-bold text-slate-900">
                        Discussion
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Join the conversation and share your thoughts.
                    </p>
                </div>

                <span
                    class="rounded-full bg-indigo-50 px-4 py-2
                     text-sm font-semibold text-indigo-700">
                    {{ $comments->total() }} Comments
                </span>

            </div>

            {{-- Comment Form --}}
            @auth

                <div class="mb-8 rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

                    <h3 class="mb-2 text-lg font-semibold text-slate-900">
                        Write a Comment
                    </h3>

                    <p class="mb-5 text-sm text-slate-500">
                        Share your opinion about this article.
                    </p>

                    @if ($errors->has('body'))
                        <div class="mb-4 rounded-lg bg-red-50 p-3
                            text-sm text-red-600">
                            {{ $errors->first('body') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('posts.comments.store', $post) }}">

                        @csrf

                        <textarea name="body" rows="4" maxlength="5000" required placeholder="Write a thoughtful comment..."
                            class="w-full resize-y rounded-xl border border-slate-300
                           px-4 py-3 text-sm text-slate-900
                           outline-none transition
                           focus:border-indigo-500
                           focus:ring-2 focus:ring-indigo-100">{{ old('body') }}</textarea>

                        <div class="mt-4 flex justify-end">

                            <button type="submit"
                                class="rounded-xl bg-indigo-600 px-6 py-3
                               text-sm font-semibold text-white
                               transition hover:bg-indigo-700">
                                Post Comment
                            </button>

                        </div>

                    </form>

                </div>
            @else
                {{-- Guest Message --}}
                <div class="mb-8 rounded-2xl border border-indigo-100
                    bg-indigo-50 p-6 text-center">

                    <p class="font-semibold text-slate-900">
                        Want to join the discussion?
                    </p>

                    <p class="mt-2 text-sm text-slate-600">
                        Sign in to share your thoughts with the community.
                    </p>

                    <a href="{{ route('login') }}"
                        class="mt-4 inline-flex rounded-lg bg-indigo-600
                      px-5 py-2.5 text-sm font-semibold
                      text-white hover:bg-indigo-700">
                        Sign In to Comment
                    </a>

                </div>

            @endauth

            {{-- Comments List --}}
            <div class="overflow-hidden rounded-2xl border
                border-slate-200 bg-white shadow-sm">

                @forelse ($comments as $comment)
                    <div class="border-b border-slate-100
                        p-6 last:border-0">

                        <div class="flex items-start gap-4">

                            {{-- Author Avatar --}}
                            <div
                                class="flex h-11 w-11 shrink-0
                                items-center justify-center
                                rounded-full bg-indigo-100
                                font-bold text-indigo-700">

                                {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}

                            </div>

                            <div class="min-w-0 flex-1">

                                {{-- Author --}}
                                <div class="flex flex-wrap items-center gap-3">

                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $comment->user?->name ?? 'Unknown User' }}
                                    </p>

                                    <span class="text-xs text-slate-400">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>

                                </div>

                                {{-- Comment Body --}}
                                <p
                                    class="mt-3 whitespace-pre-line break-words
                                  text-sm leading-7 text-slate-600">
                                    {{ $comment->body }}</p>

                            </div>

                        </div>

                    </div>

                @empty

                    {{-- Empty State --}}
                    <div class="px-6 py-12 text-center">

                        <div class="mb-3 text-3xl">
                            💬
                        </div>

                        <h3 class="text-lg font-semibold text-slate-900">
                            No comments yet
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            Be the first to start the conversation.
                        </p>

                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            @if ($comments->hasPages())
                <div class="mt-6">
                    {{ $comments->links() }}
                </div>
            @endif

        </section>


    </div>

@endsection
