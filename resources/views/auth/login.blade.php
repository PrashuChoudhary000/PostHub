<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | PostHub</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    <div class="flex min-h-screen items-center justify-center px-4 py-12">

        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">

            <!-- Logo -->
            <div class="mb-8 text-center">

                <a href="/" class="text-3xl font-bold text-indigo-600">
                    PostHub
                </a>

                <h1 class="mt-5 text-2xl font-semibold text-slate-900">
                    Welcome back
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Sign in to your PostHub account
                </p>

            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">

                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                        Email address
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="you@example.com"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3
                               outline-none transition focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3
                               outline-none transition focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="rounded border-slate-300 text-indigo-600"
                        >
                        Remember me
                    </label>

                    <a href="{{ route('password.request') }}"
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        Forgot password?
                    </a>

                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-3
                           font-semibold text-white transition
                           hover:bg-indigo-700 cursor-pointer"
                >
                    Sign In
                </button>

            </form>

            <!-- Register -->
            <p class="mt-8 text-center text-sm text-slate-600">

                Don't have an account?

                <a href="{{ route('register') }}"
                   class="font-semibold text-indigo-600 hover:text-indigo-700">
                    Create an account
                </a>

            </p>

        </div>

    </div>

</body>
</html> 