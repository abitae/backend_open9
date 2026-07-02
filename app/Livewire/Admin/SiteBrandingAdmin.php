<?php

namespace App\Livewire\Admin;

use App\Models\SiteBranding;
use App\Services\MediaStorageService;
use App\Services\SiteConfigService;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class SiteBrandingAdmin extends Component
{
    use WithFileUploads;

    /** @var array<string, mixed> */
    public array $form = [];

    public ?TemporaryUploadedFile $logoUpload = null;

    public ?TemporaryUploadedFile $logoDarkUpload = null;

    public ?TemporaryUploadedFile $faviconUpload = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('site-branding.view'), 403);

        $branding = SiteBranding::query()->firstOrCreate(['id' => 1], [
            'site_name' => 'Open9',
        ]);

        $this->form = $branding->only([
            'site_name', 'tagline', 'hero_title', 'hero_subtitle',
            'hero_cta_primary_label', 'hero_cta_primary_url',
            'hero_cta_secondary_label', 'hero_cta_secondary_url',
            'background_video_url', 'contact_email', 'contact_phone',
            'contact_address', 'website_url', 'footer_description',
            'copyright_text', 'seo_description', 'logo_path', 'logo_dark_path', 'favicon_path',
        ]);
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->can('site-branding.update'), 403);

        $this->validate([
            'form.site_name' => ['required', 'string', 'max:255'],
            'form.tagline' => ['nullable', 'string', 'max:255'],
            'form.hero_title' => ['nullable', 'string', 'max:255'],
            'form.hero_subtitle' => ['nullable', 'string'],
            'form.hero_cta_primary_label' => ['nullable', 'string', 'max:255'],
            'form.hero_cta_primary_url' => ['nullable', 'string', 'max:500'],
            'form.hero_cta_secondary_label' => ['nullable', 'string', 'max:255'],
            'form.hero_cta_secondary_url' => ['nullable', 'string', 'max:500'],
            'form.background_video_url' => ['nullable', 'url', 'max:500'],
            'form.contact_email' => ['nullable', 'email', 'max:255'],
            'form.contact_phone' => ['nullable', 'string', 'max:255'],
            'form.contact_address' => ['nullable', 'string', 'max:500'],
            'form.website_url' => ['nullable', 'url', 'max:500'],
            'form.footer_description' => ['nullable', 'string'],
            'form.copyright_text' => ['nullable', 'string', 'max:255'],
            'form.seo_description' => ['nullable', 'string'],
            'logoUpload' => ['nullable', 'image', 'max:4096'],
            'logoDarkUpload' => ['nullable', 'image', 'max:4096'],
            'faviconUpload' => ['nullable', 'image', 'max:1024'],
        ]);

        $storage = app(MediaStorageService::class);

        if ($this->logoUpload instanceof TemporaryUploadedFile) {
            $this->form['logo_path'] = $storage->store($this->logoUpload, 'branding/logo');
        }

        if ($this->logoDarkUpload instanceof TemporaryUploadedFile) {
            $this->form['logo_dark_path'] = $storage->store($this->logoDarkUpload, 'branding/logo-dark');
        }

        if ($this->faviconUpload instanceof TemporaryUploadedFile) {
            $this->form['favicon_path'] = $storage->store($this->faviconUpload, 'branding/favicon');
        }

        SiteBranding::query()->updateOrCreate(['id' => 1], $this->form);
        app(SiteConfigService::class)->clearCache();

        $this->logoUpload = null;
        $this->logoDarkUpload = null;
        $this->faviconUpload = null;

        session()->flash('status', 'Identidad del sitio actualizada.');
    }

    public function mediaUrl(?string $path): ?string
    {
        return app(MediaStorageService::class)->url($path);
    }

    public function render(): View
    {
        return view('livewire.admin.site-branding');
    }
}
