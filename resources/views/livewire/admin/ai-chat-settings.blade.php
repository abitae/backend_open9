<section class="space-y-4">
    <div>
        <flux:heading size="xl">Chat IA (Gemini)</flux:heading>
        <flux:text class="text-xs">Configura el asistente del sitio público.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success">{{ session('status') }}</flux:callout>
    @endif

    <form wire:submit="save" class="space-y-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
        <flux:checkbox wire:model="form.is_enabled" label="Chat habilitado" />
        <flux:input wire:model="form.fab_label" label="Texto del botón flotante" />
        <flux:textarea wire:model="form.welcome_message" label="Mensaje de bienvenida" rows="3" />
        <flux:textarea wire:model="form.system_prompt" label="System prompt" rows="6" placeholder="Instrucciones para el asistente OPEN9..." />
        <div class="grid gap-3 md:grid-cols-3">
            <flux:input wire:model="form.model" label="Modelo" />
            <flux:input wire:model="form.temperature" label="Temperature" type="number" step="0.1" />
            <flux:input wire:model="form.max_tokens" label="Max tokens" type="number" />
        </div>
        <flux:input wire:model="api_key" label="API Key Gemini (dejar vacío para no cambiar)" type="password" />

        <flux:button type="submit" variant="primary">Guardar</flux:button>
    </form>

    <div class="space-y-3 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
        <flux:heading size="sm">Probar conexión</flux:heading>
        <flux:input wire:model="test_message" label="Mensaje de prueba" />
        <flux:button type="button" wire:click="testConnection">Enviar prueba</flux:button>
        @if ($test_reply)
            <flux:text class="text-sm whitespace-pre-wrap">{{ $test_reply }}</flux:text>
        @endif
    </div>
</section>
