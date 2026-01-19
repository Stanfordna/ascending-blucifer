<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:5120'], // 5MB max
            'folder' => ['nullable', 'string', 'max:100'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('image');
        $folder = $validated['folder'] ?? 'uploads';
        $path = $file->store($folder, 'public');

        $dimensions = @getimagesize($file->getRealPath());

        $image = Image::create([
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'alt_text' => $validated['alt_text'] ?? null,
            'title' => $validated['title'] ?? null,
            'folder' => $folder,
        ]);

        return response()->json($image, 201);
    }

    public function destroy(Image $image): JsonResponse
    {
        Storage::disk($image->disk)->delete($image->path);
        $image->delete();

        return response()->json(null, 204);
    }
}
