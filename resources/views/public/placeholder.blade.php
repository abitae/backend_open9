<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
        <main class="mx-auto max-w-5xl px-4 py-8">
            <h1 class="text-2xl font-semibold">{{ $title }}</h1>
            <p class="mt-2 text-sm text-zinc-500">Public page scaffold ready for the frontend phase.</p>
        </main>
    </body>
</html>
