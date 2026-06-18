<?php

namespace App\Services;

use App\Models\News;
use Carbon\Carbon;

class NewsMapper
{
    public function __construct(private MediaUrlService $mediaUrlService)
    {
    }

    public function mapNewsRecord(News $news): array
    {
        $raw = $news->getRawOriginal('image_urls');
        $paths = is_array($raw) ? $raw : (is_string($raw) ? json_decode($raw, true) : []);

        return [
            'id' => $news->id,
            'title' => $news->title,
            'body' => $news->body,
            'date' => $news->date?->format('Y-m-d'),
            'from' => $news->from,
            'embed' => $news->embed,
            'image_urls' => $this->mediaUrlService->mapImageUrls(is_array($paths) ? $paths : []),
        ];
    }

    public function mapNewsCollection($collection)
    {
        return $collection->map(function (News $news) {
            return $this->mapNewsRecord($news);
        });
    }

    public function getLatestNews()
    {
        $latestDate = News::orderBy('date', 'desc')->value('date');

        if (!$latestDate) {
            return $this->mapNewsCollection(News::orderBy('date', 'desc')->limit(9)->get());
        }

        $cutoff = Carbon::parse($latestDate)->subYears(2)->toDateString();

        return $this->mapNewsCollection(
            News::where('date', '>=', $cutoff)->orderBy('date', 'desc')->limit(9)->get()
        );
    }
}
