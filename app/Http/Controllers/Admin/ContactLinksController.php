<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLink;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\SchoolGroup;
use App\Enums\SchoolLevel;

class ContactLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/ContactLinks/Index', [
            'contactLinks' => ContactLink::all(),
            'schoolGroups' => collect(SchoolGroup::cases())->map(fn($g) => [
                'value' => $g->value,
                'label' => $g->label(),
            ])->values(),
            'schoolLevels' => collect(SchoolLevel::cases())->map(fn($l) => [
                'value' => $l->value,
                'label' => $l->label(),
            ])->values(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactLink $contactLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactLink $contactLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactLink $contactLink)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_group' => 'required|string|max:255',
            'school_level' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        $contactLink->update($validated);

        return back()->with('success', 'Contact link updated.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactLink $contactLink)
    {
        //
    }
}
