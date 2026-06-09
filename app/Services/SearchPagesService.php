<?php

namespace App\Services;

use App\Models\Kurikulum;
use App\Models\News;

class SearchPagesService
{
    public function buildSearchPages(): array
    {
        $pages = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'About', 'url' => '/about'],
            ['title' => 'News', 'url' => '/news'],
            ['title' => 'Enrollment', 'url' => '/enrollment'],
            ['title' => 'Alumni', 'url' => '/alumni'],
        ];

        $kurikulums = Kurikulum::orderBy('order')->get(['id', 'title']);
        foreach ($kurikulums as $item) {
            $pages[] = [
                'title' => $item->title,
                'url' => '/kurikulum/' . $item->id,
            ];
        }

        $news = News::orderBy('date', 'desc')->get(['id', 'title']);
        foreach ($news as $item) {
            $pages[] = [
                'title' => $item->title,
                'url' => '/news-detail/' . $item->id,
            ];
        }

        return $pages;
    }
}
