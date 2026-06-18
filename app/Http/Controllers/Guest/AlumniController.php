<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Inertia\Inertia;

class AlumniController extends Controller
{
    public function index()
    {
        $alumni = Alumni::select('id', 'name', 'graduation_year', 'image_url')
            ->orderBy('graduation_year', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'graduation_year' => $item->graduation_year,
                    'image_url' => $item->image_url ? asset('uploads/' . $item->image_url) : null,
                ];
            });

        return Inertia::render('Guest/Alumni', [
            'alumni' => $alumni,
        ]);
    }
}
