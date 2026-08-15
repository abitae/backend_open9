<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#f7f9ff] text-zinc-900 antialiased">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-full focus:bg-[#4f83ff] focus:px-4 focus:py-2 focus:text-sm focus:text-white">
            Saltar al contenido
        </a>

        <header class="border-b border-zinc-200/80 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-xl items-center justify-between gap-4 px-4 py-4">
                <a href="{{ config('app.frontend_url', '/') }}" class="flex items-center gap-2 font-semibold tracking-tight">
                    <span class="flex size-8 items-center justify-center rounded-md bg-[#4f83ff] text-white">
                        <x-app-logo-icon class="size-5 fill-current" />
                    </span>
                    OPEN9
                </a>
                <a href="{{ config('app.frontend_url', '/') }}" class="text-sm text-zinc-500 transition hover:text-[#315ed1]">
                    Volver al sitio
                </a>
            </div>
        </header>

        <main id="main-content" class="mx-auto max-w-xl px-4 py-10">
            {{ $slot }}
        </main>
    </body>
</html>
