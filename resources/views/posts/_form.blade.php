
{{-- Validation Errors --}}
@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
        <p class="mb-2 font-semibold text-red-700">
            Please correct the following errors:
        </p>

        <ul class="list-inside list-disc text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">

    {{-- Title --}}
    <div>
        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">
            Post Title <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $post->title) }}"
            required
            maxlength="255"
            placeholder="Enter an interesting title..."
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                   text-slate-900 outline-none transition
                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
        >
    </div>

    {{-- Body --}}
    <div>
        <label for="body" class="mb-2 block text-sm font-semibold text-slate-700">
            Article Content <span class="text-red-500">*</span>
        </label>

        <textarea
            name="body"
            id="body"
            rows="10"
            required
            placeholder="Start writing your article..."
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                   text-slate-900 outline-none transition
                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
        >{{ old('body', $post->body) }}</textarea>

        <p class="mt-2 text-xs text-slate-500">
            Share your ideas, experiences, or knowledge with the community.
        </p>
    </div>

    {{-- Tags --}}
    <div>
        <label class="mb-3 block text-sm font-semibold text-slate-700">
            Tags
        </label>

        @php
            $selectedTags = session()->hasOldInput()
                ? old('tags', [])
                : $post->tags->pluck('id')->all();
        @endphp

        <div class="flex flex-wrap gap-3">

            @forelse ($tags as $tag)

                <label class="flex cursor-pointer items-center gap-2
                              rounded-lg border border-slate-200 bg-slate-50
                              px-4 py-2.5 text-sm transition
                              hover:border-indigo-300 hover:bg-indigo-50">

                    <input
                        type="checkbox"
                        name="tags[]"
                        value="{{ $tag->id }}"
                        @checked(in_array($tag->id, $selectedTags))
                        class="rounded border-slate-300 text-indigo-600
                               focus:ring-indigo-500"
                    >

                    <span class="font-medium text-slate-700">
                        #{{ $tag->name }}
                    </span>

                </label>

            @empty

                <p class="text-sm text-slate-500">
                    No tags available yet.
                </p>

            @endforelse

        </div>
    </div>

    {{-- Publish Status --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

        {{-- Hidden field preserves the unchecked value --}}
        <input type="hidden" name="published" value="0">

        <label class="flex cursor-pointer items-start gap-3">

            <input
                type="checkbox"
                name="published"
                value="1"
                @checked(old('published', $post->published))
                class="mt-1 rounded border-slate-300 text-indigo-600
                       focus:ring-indigo-500"
            >

            <span>
                <span class="block text-sm font-semibold text-slate-900">
                    Publish this post
                </span>

                <span class="mt-1 block text-xs text-slate-500">
                    Published posts are visible to everyone.
                    Unpublished posts remain private drafts.
                </span>
            </span>

        </label>

    </div>

</div>
