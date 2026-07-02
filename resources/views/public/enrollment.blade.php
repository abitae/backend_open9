<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
        <main class="mx-auto max-w-xl px-4 py-8">
            <h1 class="text-2xl font-semibold">Inscripcion</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ $course->title }}</p>

            @if (session('status'))
                <p class="mt-3 rounded border border-emerald-300 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('courses.enrollment.store', $course->slug) }}" class="mt-4 space-y-3">
                @csrf
                <input name="full_name" value="{{ old('full_name') }}" placeholder="Nombre completo" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="document_type" value="{{ old('document_type') }}" placeholder="Tipo de documento" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="document_number" value="{{ old('document_number') }}" placeholder="Numero de documento" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="phone" value="{{ old('phone') }}" placeholder="Telefono" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="city" value="{{ old('city') }}" placeholder="Ciudad" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="occupation" value="{{ old('occupation') }}" placeholder="Ocupacion" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <input name="company" value="{{ old('company') }}" placeholder="Empresa" class="w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">
                <textarea name="notes" placeholder="Notas" class="min-h-24 w-full rounded border px-3 py-2 text-sm dark:bg-zinc-900">{{ old('notes') }}</textarea>
                <button class="rounded bg-zinc-900 px-4 py-2 text-sm text-white dark:bg-zinc-100 dark:text-zinc-900">Enviar inscripcion</button>
            </form>
        </main>
    </body>
</html>
