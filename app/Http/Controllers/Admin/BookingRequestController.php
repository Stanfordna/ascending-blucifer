<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BookingRequest::with('service')->orderByDesc('created_at');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(20));
    }

    public function show(BookingRequest $bookingRequest): JsonResponse
    {
        return response()->json($bookingRequest->load('service'));
    }

    public function update(Request $request, BookingRequest $bookingRequest): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'in:pending,confirmed,cancelled,completed'],
            'scheduled_datetime' => ['nullable', 'date'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'confirmed' && $bookingRequest->status !== 'confirmed') {
                $validated['confirmed_at'] = now();
            }
        }

        $bookingRequest->update($validated);

        return response()->json($bookingRequest->load('service'));
    }
}
