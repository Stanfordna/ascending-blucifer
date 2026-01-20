<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\BookingRequest;
use App\Models\ContactSubmission;
use App\Models\Testimonial;
use Illuminate\Console\Command;

class ClearDemoData extends Command
{
    protected $signature = 'demo:clear {--force : Skip confirmation prompt}';

    protected $description = 'Clear all demo data (contact submissions, booking requests, blog posts, testimonials)';

    public function handle(): int
    {
        if (!$this->option('force')) {
            $this->warn('This will permanently delete:');
            $this->line('  - ' . ContactSubmission::count() . ' contact submissions');
            $this->line('  - ' . BookingRequest::count() . ' booking requests');
            $this->line('  - ' . BlogPost::count() . ' blog posts');
            $this->line('  - ' . Testimonial::count() . ' testimonials');
            $this->newLine();

            if (!$this->confirm('Are you sure you want to delete all demo data?')) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
        }

        $this->info('Clearing demo data...');

        $contactCount = ContactSubmission::count();
        ContactSubmission::truncate();
        $this->line("  ✓ Deleted {$contactCount} contact submissions");

        $bookingCount = BookingRequest::count();
        BookingRequest::truncate();
        $this->line("  ✓ Deleted {$bookingCount} booking requests");

        $blogCount = BlogPost::count();
        BlogPost::truncate();
        $this->line("  ✓ Deleted {$blogCount} blog posts");

        $testimonialCount = Testimonial::count();
        Testimonial::truncate();
        $this->line("  ✓ Deleted {$testimonialCount} testimonials");

        $this->newLine();
        $this->info('Demo data cleared successfully!');

        return self::SUCCESS;
    }
}
