<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ContactSubmission::orderByDesc('created_at');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(20));
    }

    public function show(ContactSubmission $contactSubmission): JsonResponse
    {
        if ($contactSubmission->status === 'new') {
            $contactSubmission->markAsRead();
        }

        return response()->json($contactSubmission);
    }

    public function updateStatus(Request $request, ContactSubmission $contactSubmission): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,read,responded,archived'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $contactSubmission->update($validated);

        if ($validated['status'] === 'responded' && !$contactSubmission->responded_at) {
            $contactSubmission->update(['responded_at' => now()]);
        }

        return response()->json($contactSubmission);
    }
}
