
@extends('layouts.app')

@section('title', 'Edit Post | PostHub')

@section('content')

<div class="mx-auto max-w-3xl">

    <a href="{{ route('posts.show', $post) }}"
       class="mb-6 inline-block text-sm font-medium text-slate-500 hover:text-indigo-600">
        ← Back to Article
    </a>

    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
            Edit Post
        </h1>

        <p class="mt-2 text-slate-500">
            Update your article and manage its publication status.
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

        <form method="POST" action="{{ route('posts.update', $post) }}">

            @csrf
            @method('PUT')

            @include('posts._form')

            <div class="mt-8 flex flex-wrap items-center justify-end gap-3
                        border-t border-slate-100 pt-6">

                <a href="{{ route('posts.show', $post) }}"
                   class="rounded-xl border border-slate-300 px-5 py-3
                          text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>

                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3
                               text-sm font-semibold text-white transition
                               hover:bg-indigo-700">
                    Update Post
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
