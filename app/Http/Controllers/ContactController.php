<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function config(): JsonResponse
    {
        $settings = SiteSetting::getByGroup('contact_form');
        $contactSettings = SiteSetting::getByGroup('contact');
        $services = [];

        if ($settings['contact_form_show_services'] ?? false) {
            $services = Service::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'title']);
        }

        return response()->json([
            'form_enabled' => (bool) ($settings['contact_form_enabled'] ?? false),
            'booking_enabled' => (bool) ($settings['booking_requests_enabled'] ?? false),
            'show_services' => (bool) ($settings['contact_form_show_services'] ?? true),
            'show_availability' => (bool) ($settings['contact_form_show_availability'] ?? true),
            'show_phone' => (bool) ($settings['contact_form_show_phone'] ?? true),
            'heading' => $settings['contact_form_heading'] ?? 'Get in Touch',
            'description' => $settings['contact_form_description'] ?? '',
            'success_message' => $settings['contact_form_success_message'] ?? 'Thank you!',
            'service_label' => $settings['contact_form_service_label'] ?? 'Which service are you interested in?',
            'availability_label' => $settings['contact_form_availability_label'] ?? 'Preferred availability',
            'message_label' => $settings['contact_form_message_label'] ?? 'How can I help you?',
            'email_subject' => $settings['email_template_subject'] ?? 'Consultation Inquiry',
            'email_body' => $settings['email_template_body'] ?? '',
            'contact_email' => $contactSettings['contact_email'] ?? '',
            'services' => $services,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'availability' => 'nullable|string|max:500',
            'message' => 'required|string|max:5000',
            'honeypot_field' => 'nullable|string',
            'submission_time_seconds' => 'nullable|integer',
        ]);

        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => !empty($validated['service'])
                ? "Service: {$validated['service']}"
                : ($validated['subject'] ?? null),
            'message' => $this->buildMessage($validated),
            'honeypot_field' => $validated['honeypot_field'] ?? null,
            'submission_time_seconds' => $validated['submission_time_seconds'] ?? null,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);

        // Silent accept for spam
        return response()->json(['message' => 'Thank you!']);
    }

    private function buildMessage(array $data): string
    {
        $parts = [];
        if (!empty($data['service'])) {
            $parts[] = "Service: {$data['service']}";
        }
        if (!empty($data['availability'])) {
            $parts[] = "Availability: {$data['availability']}";
        }
        $parts[] = $data['message'];

        return implode("\n\n", $parts);
    }
}
