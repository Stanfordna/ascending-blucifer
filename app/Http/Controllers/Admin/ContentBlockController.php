<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentBlockController extends Controller
{
    public function index(): JsonResponse
    {
        $blocks = ContentBlock::all()->groupBy('section');

        return response()->json($blocks);
    }

    public function publicIndex(): JsonResponse
    {
        return response()->json(
            ContentBlock::pluck('content', 'key')->toArray()
        );
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blocks' => ['required', 'array'],
            'blocks.*.key' => ['required', 'string'],
            'blocks.*.content' => ['nullable', 'string'],
        ]);

        foreach ($validated['blocks'] as $block) {
            ContentBlock::where('key', $block['key'])
                ->update(['content' => $block['content']]);
        }

        return response()->json(['message' => 'Content blocks updated']);
    }
}
