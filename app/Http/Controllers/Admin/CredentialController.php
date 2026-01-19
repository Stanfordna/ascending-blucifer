<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Credential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CredentialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Credential::ordered()->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'abbreviation' => ['nullable', 'string', 'max:50'],
            'institution' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['sort_order'] = Credential::max('sort_order') + 1;

        $credential = Credential::create($validated);

        return response()->json($credential, 201);
    }

    public function show(Credential $credential): JsonResponse
    {
        return response()->json($credential);
    }

    public function update(Request $request, Credential $credential): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'abbreviation' => ['nullable', 'string', 'max:50'],
            'institution' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $credential->update($validated);

        return response()->json($credential);
    }

    public function destroy(Credential $credential): JsonResponse
    {
        $credential->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:credentials,id'],
        ]);

        foreach ($validated['ids'] as $index => $id) {
            Credential::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Order updated']);
    }
}
