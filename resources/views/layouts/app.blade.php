<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PostHub')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    <!-- Navigation -->

    <header class="border-b border-slate-200 bg-white">

        <nav
            class="mx-auto flex max-w-6xl flex-col gap-4
                px-4 py-4 sm:px-6 lg:flex-row lg:items-center
                lg:justify-between">

            {{-- Logo --}}
            <div class="flex items-center justify-between">

                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight text-indigo-600">
                    PostHub
                </a>

                @auth
                    <span class="text-xs font-medium text-slate-500 lg:hidden">
                        {{ auth()->user()->name }}
                    </span>
                @endauth

            </div>

            {{-- Navigation --}}
            <div class="flex flex-wrap items-center gap-x-5 gap-y-3">

                <a href="{{ route('posts.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">
                    Explore
                </a>

                @auth

                    <a href="{{ route('my-posts.index') }}"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600">
                        My Posts
                    </a>

                    <a href="{{ route('posts.create') }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2
                          text-sm font-semibold text-white hover:bg-indigo-700">
                        Create Post
                    </a>

                    <span class="hidden text-sm text-slate-500 lg:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="text-sm font-medium text-slate-600
                                   hover:text-red-600">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2
                          text-sm font-semibold text-white hover:bg-indigo-700">
                        Sign Up
                    </a>

                @endauth

            </div>

        </nav>

    </header>


    <!-- Flash Message -->
    @if (session('success'))
        <div class="mx-auto mt-6 max-w-6xl px-6">
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="mx-auto max-w-6xl px-6 py-10">

        @yield('content')

    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-slate-200 bg-white">

        <div class="mx-auto max-w-6xl px-6 py-6 text-center text-sm text-slate-500">
            © {{ date('Y') }} PostHub. Built with Laravel.
        </div>

    </footer>

</body>

</html>
