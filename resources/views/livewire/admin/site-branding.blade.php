<section class="space-y-4">
    <div>
        <flux:heading size="xl">Identidad y marca</flux:heading>
        <flux:text class="text-xs">Logo, video de fondo, contacto, footer y SEO. El contenido del hero se edita en «Card hero principal».</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success">{{ session('status') }}</flux:callout>
    @endif

    <form wire:submit="save" class="space-y-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
        <div class="grid gap-3 md:grid-cols-2">
            <flux:input
                wire:model="form.site_name"
                label="Nombre del sitio"
                placeholder="Opcional: déjalo vacío para mostrar solo el logo"
            />
            <flux:input wire:model="form.tagline" label="Tagline" />
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <label class="space-y-1 text-sm">
                <span class="font-medium">Logo</span>
                <flux:text class="text-xs text-zinc-500">PNG, JPG, WebP o SVG. Máximo 4 MB.</flux:text>
                <input type="file" wire:model="logoUpload" accept="image/png,image/jpeg,image/webp,image/svg+xml,.svg" class="block w-full text-xs">
                @error('logoUpload')
                    <flux:text class="text-xs text-red-600">{{ $message }}</flux:text>
                @enderror
                @if ($form['logo_path'] ?? null)
                    <img src="{{ $this->mediaUrl($form['logo_path']) }}" alt="Logo actual" class="mt-2 h-10 max-w-full object-contain">
                @endif
            </label>

            <label class="space-y-1 text-sm">
                <span class="font-medium">Logo oscuro</span>
                <flux:text class="text-xs text-zinc-500">Variante para fondos claros. PNG, JPG, WebP o SVG.</flux:text>
                <input type="file" wire:model="logoDarkUpload" accept="image/png,image/jpeg,image/webp,image/svg+xml,.svg" class="block w-full text-xs">
                @error('logoDarkUpload')
                    <flux:text class="text-xs text-red-600">{{ $message }}</flux:text>
                @enderror
                @if ($form['logo_dark_path'] ?? null)
                    <div class="mt-2 rounded-lg border border-zinc-200 bg-zinc-100 p-2 dark:border-zinc-700 dark:bg-zinc-800">
                        <img src="{{ $this->mediaUrl($form['logo_dark_path']) }}" alt="Logo oscuro actual" class="h-10 max-w-full object-contain">
                    </div>
                @endif
            </label>

            <label class="space-y-1 text-sm">
                <span class="font-medium">Favicon</span>
                <flux:text class="text-xs text-zinc-500">ICO, PNG, SVG o WebP. Máximo 2 MB.</flux:text>
                <input type="file" wire:model="faviconUpload" accept="image/x-icon,image/vnd.microsoft.icon,image/png,image/svg+xml,image/webp,.ico" class="block w-full text-xs">
                @error('faviconUpload')
                    <flux:text class="text-xs text-red-600">{{ $message }}</flux:text>
                @enderror
                @if ($form['favicon_path'] ?? null)
                    <div class="mt-2 flex items-center gap-2">
                        <img src="{{ $this->mediaUrl($form['favicon_path']) }}" alt="Favicon actual" class="h-8 w-8 object-contain">
                        <flux:text class="text-xs text-zinc-500">Favicon actual</flux:text>
                    </div>
                @endif
            </label>
        </div>

        <flux:input wire:model="form.background_video_url" label="URL externa del video de fondo (opcional)" placeholder="https://..." />

        <label class="space-y-1 text-sm">
            <span class="font-medium">Subir video de fondo</span>
            <flux:text class="text-xs text-zinc-500">MP4, WebM o MOV. Máximo 100 MB. Si subes un archivo, reemplaza la URL externa.</flux:text>
            <input type="file" wire:model="backgroundVideoUpload" accept="video/mp4,video/webm,video/quicktime" class="block w-full text-xs">
            @error('backgroundVideoUpload')
                <flux:text class="text-xs text-red-600">{{ $message }}</flux:text>
            @enderror
            @if ($form['background_video_url'] ?? null)
                <div class="mt-2 w-1/4 min-w-[9rem]">
                    <video
                        src="{{ $this->mediaUrl($form['background_video_url']) }}"
                        class="aspect-video w-full rounded-lg border border-zinc-200 object-cover dark:border-zinc-700"
                        controls
                        muted
                        playsinline
                    ></video>
                </div>
            @endif
        </label>

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
