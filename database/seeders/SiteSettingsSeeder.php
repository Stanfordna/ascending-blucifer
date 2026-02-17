<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'group' => 'general', 'value' => 'Maggie Chamberlain, MS, RD, CDCES', 'type' => 'text', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'group' => 'general', 'value' => 'Diabetes Care & Nutrition', 'type' => 'text', 'label' => 'Tagline'],

            // Colors
            ['key' => 'color_primary', 'group' => 'colors', 'value' => '#4A7C89', 'type' => 'color', 'label' => 'Primary Color (Mountain Blue)'],
            ['key' => 'color_secondary', 'group' => 'colors', 'value' => '#C4785A', 'type' => 'color', 'label' => 'Secondary Color (Terracotta)'],
            ['key' => 'color_accent', 'group' => 'colors', 'value' => '#D4A84B', 'type' => 'color', 'label' => 'Accent Color (Gold)'],

            // Contact
            ['key' => 'contact_email', 'group' => 'contact', 'value' => 'maggie@example.com', 'type' => 'email', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'group' => 'contact', 'value' => '', 'type' => 'text', 'label' => 'Contact Phone'],
            ['key' => 'contact_location', 'group' => 'contact', 'value' => 'Denver, Colorado', 'type' => 'text', 'label' => 'Location'],

            // Social
            ['key' => 'social_linkedin', 'group' => 'social', 'value' => '', 'type' => 'url', 'label' => 'LinkedIn URL'],
            ['key' => 'social_instagram', 'group' => 'social', 'value' => '', 'type' => 'url', 'label' => 'Instagram URL'],

            // Contact Form
            ['key' => 'contact_form_enabled', 'group' => 'contact_form', 'value' => '0', 'type' => 'boolean', 'label' => 'Enable Contact Form'],
            ['key' => 'booking_requests_enabled', 'group' => 'contact_form', 'value' => '0', 'type' => 'boolean', 'label' => 'Enable Booking Requests'],
            ['key' => 'contact_form_show_services', 'group' => 'contact_form', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Service Selection'],
            ['key' => 'contact_form_show_availability', 'group' => 'contact_form', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Availability Preferences'],
            ['key' => 'contact_form_show_phone', 'group' => 'contact_form', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Phone Field'],
            ['key' => 'contact_form_heading', 'group' => 'contact_form', 'value' => 'Get in Touch', 'type' => 'text', 'label' => 'Form Heading'],
            ['key' => 'contact_form_description', 'group' => 'contact_form', 'value' => "Fill out the form below and I'll get back to you within 1-2 business days.", 'type' => 'text', 'label' => 'Form Description'],
            ['key' => 'contact_form_success_message', 'group' => 'contact_form', 'value' => "Thank you for reaching out! I'll be in touch soon.", 'type' => 'text', 'label' => 'Success Message'],
            ['key' => 'contact_form_service_label', 'group' => 'contact_form', 'value' => 'Which service are you interested in?', 'type' => 'text', 'label' => 'Service Field Label'],
            ['key' => 'contact_form_availability_label', 'group' => 'contact_form', 'value' => 'Preferred availability', 'type' => 'text', 'label' => 'Availability Field Label'],
            ['key' => 'contact_form_message_label', 'group' => 'contact_form', 'value' => 'How can I help you?', 'type' => 'text', 'label' => 'Message Field Label'],
            ['key' => 'email_template_subject', 'group' => 'contact_form', 'value' => 'Consultation Inquiry', 'type' => 'text', 'label' => 'Email Subject'],
            ['key' => 'email_template_body', 'group' => 'contact_form', 'value' => "Hi Maggie,\n\nI'm reaching out to inquire about your services.\n\nService of interest: [Please specify]\nPreferred availability: [Your preferred days/times]\n\nThank you!", 'type' => 'textarea', 'label' => 'Email Body Template'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
