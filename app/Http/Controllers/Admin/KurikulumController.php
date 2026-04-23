<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KurikulumController extends Controller
{
    public function index()
    {
        $kurikulums = Kurikulum::orderBy('order')->get();
        return Inertia::render('Admin/Kurikulum/Index', [
            'kurikulums' => $kurikulums
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Kurikulum/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'preview' => 'nullable|string',
        ]);

        Kurikulum::create([
            'title' => $request->title,
            'body' => $request->body,
            'preview' => $request->preview,
            'order' => Kurikulum::max('order') + 1,
        ]);

        return redirect()->route('admin.kurikulum')
            ->with('success', 'Kurikulum created successfully');
    }

    public function edit($id)
    {
        $kurikulum = Kurikulum::findOrFail($id);
        return Inertia::render('Admin/Kurikulum/Edit', [
            'kurikulum' => $kurikulum
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'preview' => 'nullable|string',
        ]);

        Kurikulum::findOrFail($id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'preview' => $request->preview,
        ]);

        return redirect()->route('admin.kurikulum')
            ->with('success', 'Kurikulum updated successfully');
    }

    public function destroy($id)
    {
        Kurikulum::findOrFail($id)->delete();
        return back()->with('success', 'Kurikulum deleted successfully');
    }
}
