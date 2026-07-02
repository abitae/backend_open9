<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaStorageService
{
    /**
     * @var list<string>
     */
    private const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov', 'qt'];

    public function __construct(
        private readonly StorageConfigService $storageConfig,
    ) {}

    public function store(TemporaryUploadedFile $file, string $path, ?string $disk = null): string
    {
        $diskName = $disk ?? $this->storageConfig->activeDisk();
        $storedPath = $file->store($path, $diskName);

        if ($storedPath === false) {
            throw new \RuntimeException('No se pudo guardar el archivo subido.');
        }

        return $storedPath;
    }

    public function url(?string $path, ?string $disk = null): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if ($disk !== null) {
            return Storage::disk($disk)->url($path);
        }

        return $this->storageConfig->publicUrl($path);
    }

    public function isVideoPath(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, self::VIDEO_EXTENSIONS, true);
    }

    public function activeDisk(): string
    {
        return $this->storageConfig->activeDisk();
    }
}
