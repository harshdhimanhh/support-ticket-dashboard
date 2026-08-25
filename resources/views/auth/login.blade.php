<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600">Welcome back</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Sign in to SupportDesk</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Access your support workspace and stay on top of every conversation.</p>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email address</label>
            <input id="email" class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus autocomplete="username" />
            @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-blue-600 transition hover:text-blue-700" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <input id="password" class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password" />
            @error('password')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" name="remember">
            <label for="remember_me" class="text-sm text-slate-600">Keep me signed in</label>
        </div>

        <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Sign in to dashboard
        </button>

        @if (Route::has('register'))
            <p class="pt-1 text-center text-sm text-slate-600">
                New customer?
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 transition hover:text-blue-700">Create an account</a>
            </p>
        @endif
    </form>
</x-guest-layout>
