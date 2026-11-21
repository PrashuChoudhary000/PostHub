<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | PostHub</title>

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
                    Create your account
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Join PostHub and start sharing your ideas.
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

            <!-- Registration Form -->
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">

                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                        Full name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your full name"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3
                               outline-none transition focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

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
                        autocomplete="new-password"
                        placeholder="Create a password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3
                               outline-none transition focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                        Confirm password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3
                               outline-none transition focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-3
                           font-semibold text-white transition
                           hover:bg-indigo-700 cursor-pointer"
                >
                    Create Account
                </button>

            </form>

            <!-- Login Link -->
            <p class="mt-8 text-center text-sm text-slate-600">

                Already have an account?

                <a href="{{ route('login') }}"
                   class="font-semibold text-indigo-600 hover:text-indigo-700">
                    Sign in
                </a>

            </p>

        </div>

    </div>

</body>
</html>