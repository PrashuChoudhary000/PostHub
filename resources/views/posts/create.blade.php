
@extends('layouts.app')

@section('title', 'Create Post | PostHub')

@section('content')

<div class="mx-auto max-w-3xl">

    <a href="{{ route('posts.index') }}"
       class="mb-6 inline-block text-sm font-medium text-slate-500 hover:text-indigo-600">
        ← Back to Posts
    </a>

    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
            Create a New Post
        </h1>

        <p class="mt-2 text-slate-500">
            Share something valuable with the PostHub community.
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

        <form method="POST" action="{{ route('posts.store') }}">

            @csrf

            @include('posts._form')

            <div class="mt-8 flex flex-wrap items-center justify-end gap-3
                        border-t border-slate-100 pt-6">

                <a href="{{ route('posts.index') }}"
                   class="rounded-xl border border-slate-300 px-5 py-3
                          text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>

                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3
                               text-sm font-semibold text-white transition
                               hover:bg-indigo-700">
                    Save Post
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
