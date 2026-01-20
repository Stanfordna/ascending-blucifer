<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\BookingRequest;
use App\Models\ContactSubmission;
use App\Models\Service;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedContactSubmissions();
        $this->seedBookingRequests();
        $this->seedBlogPosts();
        $this->seedTestimonials();
    }

    private function seedContactSubmissions(): void
    {
        $submissions = [
            [
                'name' => 'Sarah Mitchell',
                'email' => 'sarah.mitchell@example.com',
                'phone' => '(303) 555-0142',
                'subject' => 'Question about nutrition coaching',
                'message' => "Hi Maggie,\n\nI was recently diagnosed with Type 2 diabetes and I'm feeling overwhelmed by all the dietary changes I need to make. A friend recommended your services. Do you offer virtual consultations? I live in Fort Collins but would love to work with you.\n\nThank you,\nSarah",
                'status' => 'new',
                'submission_time_seconds' => 45,
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'name' => 'Michael Torres',
                'email' => 'mtorres@example.com',
                'phone' => '(720) 555-0198',
                'subject' => 'Insulin pump training inquiry',
                'message' => "Hello,\n\nMy endocrinologist suggested I look into getting an insulin pump. I've been on MDI for 15 years and the idea of switching is intimidating. Do you help with the transition process and pump selection?\n\nThanks,\nMichael",
                'status' => 'new',
                'submission_time_seconds' => 67,
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'name' => 'Jennifer Adams',
                'email' => 'jen.adams@example.com',
                'phone' => null,
                'subject' => 'Speaking engagement request',
                'message' => "Dear Ms. Chamberlain,\n\nI'm the program coordinator for the Colorado Diabetes Association's spring conference. We're looking for speakers who can discuss practical nutrition strategies for diabetes management. Would you be available April 15th?\n\nBest regards,\nJennifer Adams\nCDA Program Coordinator",
                'status' => 'read',
                'read_at' => Carbon::now()->subDay(),
                'submission_time_seconds' => 120,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'David Chen',
                'email' => 'dchen.health@example.com',
                'phone' => '(303) 555-0167',
                'subject' => 'Pediatric T1D support',
                'message' => "Hi Maggie,\n\nOur 8-year-old daughter was diagnosed with Type 1 last month. We're struggling with meal planning and carb counting, especially for school lunches. Do you work with families? Any resources you could recommend?\n\nDavid",
                'status' => 'responded',
                'read_at' => Carbon::now()->subDays(4),
                'responded_at' => Carbon::now()->subDays(3),
                'admin_notes' => 'Sent initial consultation info and pediatric resources. Family scheduled for next week.',
                'submission_time_seconds' => 89,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Amanda Foster',
                'email' => 'afoster@example.com',
                'phone' => '(719) 555-0134',
                'subject' => 'Insurance coverage question',
                'message' => "Hello,\n\nBefore scheduling a consultation, I wanted to check if you accept Blue Cross Blue Shield? Also, do you offer any sliding scale options?\n\nThank you,\nAmanda",
                'status' => 'responded',
                'read_at' => Carbon::now()->subDays(6),
                'responded_at' => Carbon::now()->subDays(5),
                'admin_notes' => 'Sent insurance info sheet. She will call back after checking with her plan.',
                'submission_time_seconds' => 34,
                'created_at' => Carbon::now()->subWeek(),
            ],
            [
                'name' => 'Robert Kim',
                'email' => 'robkim@example.com',
                'phone' => '(303) 555-0189',
                'subject' => 'Follow-up question',
                'message' => "Maggie,\n\nQuick question - you mentioned tracking my blood sugar patterns for two weeks before our next appointment. Should I be using a specific app or spreadsheet format? Want to make sure I capture everything you need.\n\nRobert",
                'status' => 'archived',
                'read_at' => Carbon::now()->subDays(10),
                'responded_at' => Carbon::now()->subDays(9),
                'admin_notes' => 'Existing client - sent MySugr recommendation',
                'submission_time_seconds' => 28,
                'created_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($submissions as $submission) {
            ContactSubmission::create($submission);
        }
    }

    private function seedBookingRequests(): void
    {
        $services = Service::all();
        $diabetesService = $services->firstWhere('title', 'Diabetes Education');
        $nutritionService = $services->firstWhere('title', 'Nutrition Guidance');
        $pumpService = $services->firstWhere('title', 'Pump Training');
        $speakingService = $services->firstWhere('title', 'Speaking');

        $requests = [
            [
                'name' => 'Emily Watson',
                'email' => 'emily.watson@example.com',
                'phone' => '(303) 555-0156',
                'service_id' => $diabetesService?->id,
                'preferred_date_1' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'preferred_time_1' => 'morning',
                'preferred_date_2' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'preferred_time_2' => 'afternoon',
                'message' => 'Newly diagnosed with T2D. Looking forward to learning how to manage this.',
                'is_new_client' => true,
                'status' => 'pending',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'name' => 'James Sullivan',
                'email' => 'jsullivan@example.com',
                'phone' => '(720) 555-0143',
                'service_id' => $pumpService?->id,
                'preferred_date_1' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'preferred_time_1' => 'afternoon',
                'preferred_date_2' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'preferred_time_2' => 'morning',
                'message' => 'Getting the Tandem t:slim X2 next week. Would love help with initial setup and training.',
                'is_new_client' => true,
                'status' => 'pending',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'name' => 'Lisa Patel',
                'email' => 'lisa.p@example.com',
                'phone' => '(303) 555-0178',
                'service_id' => $nutritionService?->id,
                'preferred_date_1' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'preferred_time_1' => 'morning',
                'preferred_date_2' => null,
                'preferred_time_2' => null,
                'message' => 'Follow-up appointment. Want to review my meal plan adjustments.',
                'is_new_client' => false,
                'status' => 'confirmed',
                'confirmed_at' => Carbon::now()->subDays(2),
                'scheduled_datetime' => Carbon::now()->addDays(10)->setHour(10)->setMinute(0),
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'name' => 'Marcus Johnson',
                'email' => 'marcus.j@example.com',
                'phone' => '(719) 555-0192',
                'service_id' => $diabetesService?->id,
                'preferred_date_1' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'preferred_time_1' => 'afternoon',
                'preferred_date_2' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'preferred_time_2' => 'afternoon',
                'message' => 'Referred by Dr. Thompson at UCHealth. Looking for comprehensive diabetes education.',
                'is_new_client' => true,
                'status' => 'confirmed',
                'confirmed_at' => Carbon::now()->subDay(),
                'scheduled_datetime' => Carbon::now()->addDays(2)->setHour(14)->setMinute(30),
                'admin_notes' => 'Dr. Thompson referral - initial consult',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'name' => 'Colorado Springs Diabetes Group',
                'email' => 'events@csdgroup.org',
                'phone' => '(719) 555-0101',
                'service_id' => $speakingService?->id,
                'preferred_date_1' => Carbon::now()->addWeeks(6)->format('Y-m-d'),
                'preferred_time_1' => 'morning',
                'preferred_date_2' => Carbon::now()->addWeeks(7)->format('Y-m-d'),
                'preferred_time_2' => 'morning',
                'message' => 'Annual member meeting speaker request. Topic: "Living Well with Diabetes in 2026." Audience of ~50 members. Honorarium available.',
                'is_new_client' => true,
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Nancy Greene',
                'email' => 'ngreene@example.com',
                'phone' => '(303) 555-0165',
                'service_id' => $nutritionService?->id,
                'preferred_date_1' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'preferred_time_1' => 'morning',
                'preferred_date_2' => null,
                'preferred_time_2' => null,
                'message' => 'Monthly check-in appointment',
                'is_new_client' => false,
                'status' => 'completed',
                'confirmed_at' => Carbon::now()->subWeek(),
                'scheduled_datetime' => Carbon::now()->subDays(3)->setHour(9)->setMinute(0),
                'admin_notes' => 'Great progress on A1C. Next appointment in 4 weeks.',
                'created_at' => Carbon::now()->subWeeks(2),
            ],
        ];

        foreach ($requests as $request) {
            BookingRequest::create($request);
        }
    }

    private function seedBlogPosts(): void
    {
        $posts = [
            [
                'title' => 'The Art of Mindful Eating with Diabetes',
                'slug' => 'art-of-mindful-eating-with-diabetes',
                'excerpt' => 'Discover how presence and intention can transform your relationship with food and blood sugar management.',
                'content' => "## Finding Peace at the Table\n\nLiving with diabetes doesn't mean you have to approach every meal with anxiety. Mindful eating is about bringing awareness and intention to your food choices, not restriction.\n\n### What is Mindful Eating?\n\nMindful eating is the practice of paying full attention to the experience of eating and drinking. It means being present with your food, noticing colors, smells, textures, and flavors.\n\n### Benefits for Blood Sugar Management\n\n1. **Better portion awareness** - When you eat slowly and mindfully, you're more attuned to hunger and fullness cues\n2. **Reduced stress eating** - Awareness helps break automatic eating patterns\n3. **Improved digestion** - Eating slowly aids digestion and can help stabilize blood sugar\n\n### Getting Started\n\nTry this: Before your next meal, take three deep breaths. Look at your plate and appreciate the colors and arrangement. Take your first bite slowly, noticing the flavors.\n\nSmall steps lead to lasting change.",
                'content_format' => 'markdown',
                'published_at' => Carbon::now()->subDays(5),
                'is_featured' => true,
                'meta_title' => 'Mindful Eating with Diabetes | Maggie Chamberlain, RD',
                'meta_description' => 'Learn how mindful eating practices can help manage diabetes and transform your relationship with food.',
            ],
            [
                'title' => 'Mountain Adventures & Diabetes: A Colorado Guide',
                'slug' => 'mountain-adventures-diabetes-colorado-guide',
                'excerpt' => "Tips for managing your glucose while exploring Colorado's beautiful trails and peaks.",
                'content' => "## Don't Let Diabetes Keep You Indoors\n\nColorado's mountains are calling, and having diabetes shouldn't stop you from answering. With proper preparation, you can safely enjoy hiking, skiing, and all our state has to offer.\n\n### Altitude and Blood Sugar\n\nHigh altitude can affect blood sugar in unpredictable ways. Some people see higher readings, others lower. The key is testing more frequently when you're above 8,000 feet.\n\n### Packing Essentials\n\n- **Extra supplies** - Always bring more test strips, insulin, and snacks than you think you'll need\n- **Fast-acting glucose** - Glucose tablets or gels are essential for treating lows on the trail\n- **Insulated case** - Extreme temperatures can damage insulin\n- **Medical ID** - Wear it visibly\n\n### My Favorite Diabetes-Friendly Trails\n\n1. **Bear Lake Loop** - Easy, beautiful, and close to parking if you need to return\n2. **Brainard Lake** - Stunning views with manageable elevation gain\n3. **Garden of the Gods** - Lower altitude option in Colorado Springs\n\nRemember: The mountains will always be there. Take your time and listen to your body.",
                'content_format' => 'markdown',
                'published_at' => Carbon::now()->subWeeks(2),
                'is_featured' => false,
                'meta_title' => 'Hiking with Diabetes in Colorado | Maggie Chamberlain, RD',
                'meta_description' => 'Essential tips for managing diabetes while enjoying Colorado mountain adventures.',
            ],
            [
                'title' => 'Understanding Your Bionic Pancreas',
                'slug' => 'understanding-bionic-pancreas',
                'excerpt' => "A gentle guide to the technology that's revolutionizing automated insulin delivery.",
                'content' => "## The Future of Diabetes Management is Here\n\nThe iLet Bionic Pancreas represents a major leap forward in diabetes technology. As someone who has worked with Beta Bionics on training and education, I'm excited to share what this means for people with diabetes.\n\n### What Makes It Different\n\nUnlike traditional pumps that require extensive carb counting and manual bolusing, the Bionic Pancreas uses only your body weight for initial setup. The algorithm does the heavy lifting.\n\n### What to Expect\n\n**Week 1**: The system is learning. You might see more variability than expected. This is normal.\n\n**Weeks 2-4**: The algorithm refines its understanding of your patterns. Many people see significant improvements.\n\n**Month 2 and beyond**: Most users report spending significantly more time in range with less mental burden.\n\n### Common Concerns\n\n*\"What if it gives me too much insulin?\"*\n\nThe system is designed with safety limits. It also learns from your patterns and adjusts accordingly.\n\n*\"Do I still need to count carbs?\"*\n\nYou'll indicate meal size (less, usual, more) but exact carb counting isn't required.\n\n### Is It Right for You?\n\nThe best candidates are those who:\n- Feel burned out by constant diabetes management decisions\n- Want more flexibility in their eating patterns\n- Are comfortable with pump therapy\n\nReach out if you'd like to discuss whether this technology might be right for your situation.",
                'content_format' => 'markdown',
                'published_at' => Carbon::now()->subWeeks(4),
                'is_featured' => true,
                'meta_title' => 'Understanding the Bionic Pancreas | Maggie Chamberlain, RD, CDCES',
                'meta_description' => 'Learn about the iLet Bionic Pancreas and how automated insulin delivery is changing diabetes management.',
            ],
            [
                'title' => 'Holiday Eating: Beyond the Fear',
                'slug' => 'holiday-eating-beyond-fear',
                'excerpt' => 'How to enjoy festive meals while keeping your diabetes management on track.',
                'content' => "## The Holidays Don't Have to Be Stressful\n\nEvery year, I hear the same concerns: \"How will I manage at Thanksgiving dinner?\" \"Should I skip dessert entirely?\" \"I always feel guilty after holiday meals.\"\n\nLet's reframe this conversation.\n\n### Permission to Enjoy\n\nFirst, please hear this: You are allowed to enjoy holiday food. Having diabetes doesn't mean you must sit on the sidelines while everyone else celebrates.\n\n### Practical Strategies\n\n**Before the meal:**\n- Check your blood sugar so you have a baseline\n- Consider a short walk or some movement\n- Eat regular meals leading up to it (don't \"save up\")\n\n**During the meal:**\n- Survey all options before filling your plate\n- Take smaller portions of many things rather than large portions of a few\n- Sit down, slow down, and savor\n\n**After the meal:**\n- A walk around the block can help with post-meal spikes\n- Check your blood sugar at 2 hours\n- Let go of any guilt - one meal doesn't define your health\n\n### The Real Secret\n\nConsistency over perfection. One indulgent meal in a season of mindful eating is perfectly fine. The stress of restriction often does more harm than the meal itself.\n\nCelebrate with joy.",
                'content_format' => 'markdown',
                'published_at' => Carbon::now()->subMonths(2),
                'is_featured' => false,
                'meta_title' => 'Holiday Eating with Diabetes | Maggie Chamberlain, RD',
                'meta_description' => 'Practical tips for enjoying holiday meals while managing diabetes without guilt or stress.',
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'client_name' => 'Jennifer M.',
                'client_title' => 'Type 2 Diabetes, Denver',
                'quote' => "Maggie completely changed how I think about food and diabetes. Instead of a list of \"don'ts,\" she helped me discover all the delicious options I can enjoy. My A1C dropped from 8.2 to 6.4 in six months.",
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'client_name' => 'Robert & Susan T.',
                'client_title' => 'Parents of T1D child, Boulder',
                'quote' => "When our son was diagnosed at age 10, we were overwhelmed. Maggie met with our whole family and made diabetes feel manageable. She even helped us figure out birthday party strategies. We couldn't have gotten through that first year without her.",
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'client_name' => 'Michael K.',
                'client_title' => 'Type 1 Diabetes, Colorado Springs',
                'quote' => "After 20 years of MDI, switching to a pump felt impossible. Maggie walked me through every step of the Tandem setup and was available whenever I had questions. Now I can't imagine going back.",
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'client_name' => 'Amanda L.',
                'client_title' => 'Gestational Diabetes',
                'quote' => "I was terrified when I got my gestational diabetes diagnosis. Maggie reassured me, gave me a clear meal plan, and checked in regularly. My baby was born healthy and I actually learned habits that stick with me today.",
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'client_name' => 'Dr. Sarah Thompson',
                'client_title' => 'Endocrinologist, UCHealth',
                'quote' => "I refer my patients to Maggie with complete confidence. Her expertise in both nutrition and diabetes technology makes her uniquely qualified to support people with complex needs. She's an invaluable partner in patient care.",
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
