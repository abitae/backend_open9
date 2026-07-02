<?php

namespace Database\Seeders\Concerns;

trait SeedsReferenceImages
{
    protected function referenceImage(string $seed, int $width = 800, int $height = 450): string
    {
        return "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
    }
}
