<section class="space-y-4">
    <div>
        <flux:heading size="xl">Identidad y Hero</flux:heading>
        <flux:text class="text-xs">Logo, textos del hero, contacto y SEO.</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success">{{ session('status') }}</flux:callout>
    @endif

    <form wire:submit="save" class="space-y-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
        <div class="grid gap-3 md:grid-cols-2">
            <flux:input wire:model="form.site_name" label="Nombre del sitio" />
            <flux:input wire:model="form.tagline" label="Tagline" />
        </div>

        <div class="grid gap-3 md:grid-cols-3">
            <label class="space-y-1 text-sm">
                <span class="font-medium">Logo</span>
                <input type="file" wire:model="logoUpload" accept="image/*" class="block w-full text-xs">
                @if ($form['logo_path'] ?? null)
                    <img src="{{ $this->mediaUrl($form['logo_path']) }}" alt="Logo" class="mt-2 h-10">
                @endif
            </label>
            <label class="space-y-1 text-sm">
                <span class="font-medium">Logo oscuro</span>
                <input type="file" wire:model="logoDarkUpload" accept="image/*" class="block w-full text-xs">
            </label>
            <label class="space-y-1 text-sm">
                <span class="font-medium">Favicon</span>
                <input type="file" wire:model="faviconUpload" accept="image/*" class="block w-full text-xs">
            </label>
        </div>

        <flux:input wire:model="form.hero_title" label="Título del hero" />
        <flux:textarea wire:model="form.hero_subtitle" label="Subtítulo del hero" rows="3" />
        <div class="grid gap-3 md:grid-cols-2">
            <flux:input wire:model="form.hero_cta_primary_label" label="CTA primario - etiqueta" />
            <flux:input wire:model="form.hero_cta_primary_url" label="CTA primario - URL" />
            <flux:input wire:model="form.hero_cta_secondary_label" label="CTA secundario - etiqueta" />
            <flux:input wire:model="form.hero_cta_secondary_url" label="CTA secundario - URL" />
        </div>
        <flux:input wire:model="form.background_video_url" label="URL video de fondo" />

        <div class="grid gap-3 md:grid-cols-2">
            <flux:input wire:model="form.contact_email" label="Email de contacto" />
            <flux:input wire:model="form.contact_phone" label="Teléfono" />
            <flux:input wire:model="form.contact_address" label="Dirección" />
            <flux:input wire:model="form.website_url" label="Sitio web" />
        </div>

        <flux:textarea wire:model="form.footer_description" label="Descripción del footer" rows="3" />
        <flux:input wire:model="form.copyright_text" label="Copyright" />
        <flux:textarea wire:model="form.seo_description" label="SEO description" rows="2" />

        <flux:button type="submit" variant="primary">Guardar</flux:button>
    </form>
</section>
