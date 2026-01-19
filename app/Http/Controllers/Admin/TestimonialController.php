<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Testimonial::with('photo')->ordered()->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
            'photo_id' => ['nullable', 'exists:images,id'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['sort_order'] = Testimonial::max('sort_order') + 1;

        $testimonial = Testimonial::create($validated);

        return response()->json($testimonial->load('photo'), 201);
    }

    public function show(Testimonial $testimonial): JsonResponse
    {
        return response()->json($testimonial->load('photo'));
    }

    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validated = $request->validate([
            'client_name' => ['sometimes', 'required', 'string', 'max:255'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'quote' => ['sometimes', 'required', 'string'],
            'photo_id' => ['nullable', 'exists:images,id'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $testimonial->update($validated);

        return response()->json($testimonial->load('photo'));
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(null, 204);
    }
}
