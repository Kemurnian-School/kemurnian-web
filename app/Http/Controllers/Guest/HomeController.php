<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Hero;
use App\Models\Kurikulum;
use App\Services\EnrollmentMapper;
use App\Services\NewsMapper;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        private NewsMapper $newsMapper,
        private EnrollmentMapper $enrollmentMapper
    ) {
    }

    public function home()
    {
        $hero = Hero::orderBy('order')->get()->map(function (Hero $item) {
            return [
                'id' => $item->id,
                'header_text' => $item->header_text,
                'button_text' => $item->button_text,
                'href_text' => $item->href,
                'image_urls' => $item->desktop_image,
                'tablet_image_urls' => $item->tablet_image,
                'mobile_image_urls' => $item->mobile_image,
                'order' => $item->order,
            ];
        })->values();

        $kurikulum = Kurikulum::orderBy('order')->get();
        $latestNews = $this->newsMapper->getLatestNews();
        $enrollment = Enrollment::first();

        return Inertia::render('Guest/Home', [
            'hero' => $hero,
            'kurikulum' => $kurikulum,
            'latestNews' => $latestNews,
            'enrollment' => $enrollment ? $this->enrollmentMapper->formatEnrollment($enrollment) : null,
        ]);
    }

    public function about()
    {
        return Inertia::render('Guest/About', [
        ]);
    }
}
