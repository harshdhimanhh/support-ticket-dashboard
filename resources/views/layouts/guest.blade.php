<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Support Ticket Dashboard') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-950">
        <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_#2563eb_0,_transparent_32%),radial-gradient(circle_at_bottom_right,_#0f766e_0,_transparent_30%)] px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-5xl overflow-hidden rounded-3xl bg-white shadow-2xl shadow-slate-950/30">
                <section class="hidden w-5/12 flex-col justify-between bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 p-10 text-white lg:flex">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 text-lg font-bold tracking-tight">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/15 text-xl">S</span>
                        SupportDesk
                    </a>
                    <div>
                        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.22em] text-cyan-100">Customer support</p>
                        <h1 class="text-4xl font-bold leading-tight">Every customer conversation, in one place.</h1>
                        <p class="mt-5 max-w-sm text-base leading-7 text-blue-100">Manage tickets, collaborate on replies, and receive live updates without refreshing the page.</p>
                    </div>
                    <p class="text-sm text-blue-100">Secure access for support agents and customers.</p>
                </section>

                <section class="flex w-full items-center justify-center px-6 py-10 sm:px-12 lg:w-7/12">
                    <div class="w-full max-w-md">
                        <a href="{{ url('/') }}" class="mb-10 flex items-center gap-3 text-lg font-bold tracking-tight text-slate-900 lg:hidden">
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-600 text-xl text-white">S</span>
                            SupportDesk
                        </a>
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
