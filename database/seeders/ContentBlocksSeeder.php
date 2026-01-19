<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use Illuminate\Database\Seeder;

class ContentBlocksSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            // Hero Section
            [
                'key' => 'hero_script',
                'section' => 'hero',
                'label' => 'Hero Script Text',
                'type' => 'text',
                'content' => 'Diabetes Care & Nutrition',
            ],
            [
                'key' => 'hero_title',
                'section' => 'hero',
                'label' => 'Hero Title',
                'type' => 'text',
                'content' => 'Nourishing Your Journey',
            ],
            [
                'key' => 'hero_title_accent',
                'section' => 'hero',
                'label' => 'Hero Title Accent',
                'type' => 'text',
                'content' => 'to Wellness',
            ],
            [
                'key' => 'hero_description',
                'section' => 'hero',
                'label' => 'Hero Description',
                'type' => 'textarea',
                'content' => "Expert guidance in diabetes management and nutrition, rooted in the spirit of the Colorado mountains. Together, we'll find your path to thriving.",
            ],
            [
                'key' => 'hero_cta_primary',
                'section' => 'hero',
                'label' => 'Primary CTA Text',
                'type' => 'text',
                'content' => 'Begin Your Journey',
            ],
            [
                'key' => 'hero_cta_secondary',
                'section' => 'hero',
                'label' => 'Secondary CTA Text',
                'type' => 'text',
                'content' => 'Explore Services',
            ],

            // About Section
            [
                'key' => 'about_script',
                'section' => 'about',
                'label' => 'About Script Text',
                'type' => 'text',
                'content' => 'About Maggie',
            ],
            [
                'key' => 'about_title',
                'section' => 'about',
                'label' => 'About Title',
                'type' => 'text',
                'content' => 'A Colorado Heart, A Healing Mission',
            ],
            [
                'key' => 'about_paragraph_1',
                'section' => 'about',
                'label' => 'About Paragraph 1',
                'type' => 'textarea',
                'content' => "With the determination of Colorado's mountain goats and over seven years of clinical experience, I've dedicated my career to helping people with diabetes discover what's possible for their health.",
            ],
            [
                'key' => 'about_paragraph_2',
                'section' => 'about',
                'label' => 'About Paragraph 2',
                'type' => 'textarea',
                'content' => 'From hospital bedside to cutting-edge medical device innovation with Beta Bionics and Tandem Diabetes Care, my journey has always been guided by one mission: empowering individuals to take control of their health with confidence and joy.',
            ],

            // CTA Section
            [
                'key' => 'cta_script',
                'section' => 'cta',
                'label' => 'CTA Script Text',
                'type' => 'text',
                'content' => "Let's Connect",
            ],
            [
                'key' => 'cta_title',
                'section' => 'cta',
                'label' => 'CTA Title',
                'type' => 'text',
                'content' => 'Ready to Begin?',
            ],
            [
                'key' => 'cta_description',
                'section' => 'cta',
                'label' => 'CTA Description',
                'type' => 'textarea',
                'content' => "Your journey to better health starts with a conversation. Schedule a consultation and let's explore what's possible together.",
            ],
            [
                'key' => 'cta_button',
                'section' => 'cta',
                'label' => 'CTA Button Text',
                'type' => 'text',
                'content' => 'Schedule Your Consultation',
            ],

            // Footer Section
            [
                'key' => 'footer_tagline',
                'section' => 'footer',
                'label' => 'Footer Tagline',
                'type' => 'textarea',
                'content' => 'Registered Dietitian & Certified Diabetes Care and Education Specialist, helping people thrive from the heart of Colorado.',
            ],
        ];

        foreach ($blocks as $block) {
            ContentBlock::updateOrCreate(
                ['key' => $block['key']],
                $block
            );
        }
    }
}
