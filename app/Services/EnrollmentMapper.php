<?php

namespace App\Services;

use App\Models\Enrollment;

class EnrollmentMapper
{
    public function __construct(private MediaUrlService $mediaUrlService)
    {
    }

    public function formatEnrollment(Enrollment $enrollment): array
    {
        return [
            'id' => $enrollment->id,
            'title' => $enrollment->title,
            'body' => $enrollment->body,
            'date' => $enrollment->date?->format('Y-m-d'),
            'image_url' => $this->mediaUrlService->mapImageUrl($enrollment->getRawOriginal('image_url')),
        ];
    }
}
