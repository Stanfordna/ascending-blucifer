<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Public endpoint: Get published blog posts
     */
    public function publicIndex(Request $request): JsonResponse
    {
        $query = BlogPost::published()
            ->with('featuredImage')
            ->recent();

        // Filter by featured if requested
        if ($request->boolean('featured')) {
            $query->featured()->orderBy('featured_order');
        }

        $limit = $request->integer('limit', 10);

        return response()->json(
            $query->take($limit)->get()
        );
    }

    /**
     * Public endpoint: Get single published post by slug
     */
    public function publicShow(string $slug): JsonResponse
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with('featuredImage')
            ->firstOrFail();

        return response()->json($post);
    }

    public function index(): JsonResponse
    {
        return response()->json(
            BlogPost::with('featuredImage')
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_format' => ['nullable', 'string', 'in:html,markdown'],
            'featured_image_id' => ['nullable', 'exists:images,id'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'color_primary' => ['nullable', 'string', 'max:7'],
            'color_secondary' => ['nullable', 'string', 'max:7'],
            'color_accent' => ['nullable', 'string', 'max:7'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $post = BlogPost::create($validated);

        return response()->json($post->load('featuredImage'), 201);
    }

    public function show(BlogPost $blogPost): JsonResponse
    {
        return response()->json($blogPost->load('featuredImage'));
    }

    public function update(Request $request, BlogPost $blogPost): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug,' . $blogPost->id],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_format' => ['nullable', 'string', 'in:html,markdown'],
            'featured_image_id' => ['nullable', 'exists:images,id'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'color_primary' => ['nullable', 'string', 'max:7'],
            'color_secondary' => ['nullable', 'string', 'max:7'],
            'color_accent' => ['nullable', 'string', 'max:7'],
        ]);

        $blogPost->update($validated);

        // Refresh to get the model with properly cast attributes
        $blogPost->refresh();

        return response()->json($blogPost->load('featuredImage'));
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        $blogPost->delete();

        return response()->json(null, 204);
    }

    /**
     * Reorder featured posts
     */
    public function reorderFeatured(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:blog_posts,id'],
        ]);

        foreach ($validated['order'] as $position => $id) {
            BlogPost::where('id', $id)->update(['featured_order' => $position]);
        }

        return response()->json(['message' => 'Featured posts reordered']);
    }
}
