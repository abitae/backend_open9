<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
        <main class="mx-auto max-w-xl px-4 py-8">
            <h1 class="text-2xl font-semibold">Contacto</h1>

            @if (session('status'))
                <p class="mt-3 rounded border border-emerald-300 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="mt-4 space-y-3">
                @csrf
                <input name="name" value="{{ old('name') }}" placeholder="Nombre" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="phone" value="{{ old('phone') }}" placeholder="Telefono" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="subject" value="{{ old('subject') }}" placeholder="Asunto" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <textarea name="message" placeholder="Mensaje" class="min-h-32 w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">{{ old('message') }}</textarea>
                <button class="rounded bg-zinc-900 px-4 py-2 text-sm text-white dark:bg-zinc-100 dark:text-zinc-900">Enviar</button>
            </form>
        </main>
    </body>
</html>
