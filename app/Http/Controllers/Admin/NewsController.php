<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('date', 'desc')->get()->map(function (News $item) {
            $paths = $this->getStoredImages($item);

            return [
                'id' => $item->id,
                'title' => $item->title,
                'body' => $item->body,
                'date' => $item->date,
                'from' => $item->from,
                'embed' => $item->embed,
                'image_urls' => $this->mapImageUrls($paths),
            ];
        });

        return Inertia::render('Admin/News/Index', [
            'news' => $news,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'from' => 'required|string',
            'body' => 'nullable|string',
            'embed' => 'nullable|string',
            'images' => 'nullable',
            'images.*' => 'image|max:10240',
        ]);

        $paths = $this->storeImages($request, $request->title, $request->date);

        News::create([
            'title' => $request->title,
            'body' => $this->sanitizeBody($request->body),
            'date' => $request->date,
            'from' => $request->from,
            'embed' => $request->embed,
            'image_urls' => $paths,
        ]);

        return redirect()->route('admin.news')->with('success', 'News created successfully');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $paths = $this->getStoredImages($news);

        return Inertia::render('Admin/News/Edit', [
            'news' => [
                'id' => $news->id,
                'title' => $news->title,
                'body' => $news->body,
                'date' => $news->date?->format('Y-m-d'),
                'from' => $news->from,
                'embed' => $news->embed,
                'image_urls' => $this->mapImageUrls($paths),
                'image_paths' => $paths,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'from' => 'required|string',
            'body' => 'nullable|string',
            'embed' => 'nullable|string',
            'existingImages' => 'nullable',
            'images' => 'nullable',
            'images.*' => 'image|max:10240',
        ]);

        $news = News::findOrFail($id);
        $currentImages = $this->getStoredImages($news);
        $existingImages = $this->normalizeImagePaths($request->input('existingImages', []));
        $deleteImages = array_values(array_diff($currentImages, $existingImages));
        $this->deleteImages($deleteImages);

        $newPaths = $this->storeImages($request, $request->title, $request->date);
        $nextImages = array_values(array_merge($existingImages, $newPaths));

        $news->update([
            'title' => $request->title,
            'body' => $this->sanitizeBody($request->body),
            'date' => $request->date,
            'from' => $request->from,
            'embed' => $request->embed,
            'image_urls' => $nextImages,
        ]);

        return redirect()->route('admin.news')->with('success', 'News updated successfully');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $paths = $this->getStoredImages($news);
        $this->deleteImages($paths);

        $news->delete();

        return back()->with('success', 'News deleted successfully');
    }

    private function sanitizeBody(?string $body): ?string
    {
        if ($body === null) {
            return null;
        }

        $cleaned = preg_replace('/&nbsp;|\xC2\xA0/i', ' ', $body);
        return $cleaned !== null ? trim($cleaned) : $body;
    }

    private function storeImages(Request $request, string $title, string $date): array
    {
        $files = $request->file('images', []);
        if (!$files) {
            return [];
        }

        if ($files instanceof \Illuminate\Http\UploadedFile) {
            $files = [$files];
        }

        $folder = $this->buildNewsFolder($title, $date);
        $paths = [];

        foreach ($files as $index => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $filename = now()->timestamp . '_' . $index . '_' . $file->hashName();
            $paths[] = $this->storeFile($file, $folder, $filename);
        }

        return array_values(array_filter($paths));
    }

    private function buildNewsFolder(string $title, string $date): string
    {
        $newsDate = Carbon::parse($date);
        $year = $newsDate->format('Y');
        $month = $newsDate->format('F');
        $sanitizedTitle = preg_replace('/[^a-zA-Z0-9.-]/', '_', $title) ?? 'news';

        return 'news/' . $year . '/' . $month . '/' . $sanitizedTitle;
    }

    private function storeFile($file, string $folder, string $filename): ?string
    {
        $relativePath = $folder . '/' . $filename;
        Storage::disk('public_html')->putFileAs($folder, $file, $filename);
        return $relativePath;
    }

    private function deleteImages(array $paths): void
    {
        foreach ($paths as $path) {
            $relativePath = ltrim($path, '/');
            Storage::disk('public_html')->delete($relativePath);
        }
    }

    private function getStoredImages(News $news): array
    {
        $raw = $news->getRawOriginal('image_urls');
        if (is_array($raw)) {
            return $raw;
        }

        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    private function mapImageUrls(array $paths): array
    {
        $baseUrl = rtrim(config('app.url'), '/') . '/uploads/';

        return array_map(function ($path) use ($baseUrl) {
            $relativePath = ltrim($path, '/');
            return $baseUrl . $relativePath;
        }, $paths);
    }

    private function normalizeImagePaths($input): array
    {
        $paths = $input;
        if (is_string($input)) {
            $decoded = json_decode($input, true);
            $paths = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($paths)) {
            return [];
        }

        $baseUrl = rtrim(config('app.url'), '/') . '/uploads/';

        return array_values(array_filter(array_map(function ($path) use ($baseUrl) {
            if (!is_string($path)) {
                return null;
            }

            $normalized = str_replace($baseUrl, '', $path);
            return ltrim($normalized, '/');
        }, $paths)));
    }
}
