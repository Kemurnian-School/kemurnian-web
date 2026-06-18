<?php

namespace App\Services;

class MediaUrlService
{
    public function mapImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $baseUrl = rtrim(config('app.url'), '/') . '/uploads/';
        return $baseUrl . ltrim($path, '/');
    }

    public function mapImageUrls(?array $paths): array
    {
        if (!$paths) {
            return [];
        }

        return array_values(array_filter(array_map(function ($path) {
            return $this->mapImageUrl($path);
        }, $paths)));
    }
}
