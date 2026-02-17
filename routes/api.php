<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BookingRequestController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\ContentBlockController;
use App\Http\Controllers\Admin\CredentialController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/content-blocks', [ContentBlockController::class, 'publicIndex']);
Route::get('/blog-posts', [BlogPostController::class, 'publicIndex']);
Route::get('/blog-posts/{slug}', [BlogPostController::class, 'publicShow']);
Route::get('/testimonials', [TestimonialController::class, 'publicIndex']);
Route::get('/testimonials/all', [TestimonialController::class, 'allPublic']);
Route::get('/services', [ServiceController::class, 'publicIndex']);
Route::get('/services/all', [ServiceController::class, 'allPublic']);
Route::get('/services/{slug}', [ServiceController::class, 'publicShow'])->where('slug', '[a-z0-9\-]+');
Route::get('/credentials', [CredentialController::class, 'publicIndex']);
Route::get('/contact-config', [ContactController::class, 'config']);
Route::post('/contact', [ContactController::class, 'store']);

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// Admin routes (protected)
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Password change
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Site Settings
    Route::get('/settings', [SiteSettingController::class, 'index']);
    Route::put('/settings', [SiteSettingController::class, 'bulkUpdate']);
    Route::get('/contact-form-config', [SiteSettingController::class, 'contactFormConfig']);
    Route::put('/contact-form-config', [SiteSettingController::class, 'updateContactFormConfig']);

    // Content Blocks
    Route::get('/content-blocks', [ContentBlockController::class, 'index']);
    Route::put('/content-blocks', [ContentBlockController::class, 'bulkUpdate']);

    // Services
    Route::apiResource('services', ServiceController::class);
    Route::post('/services/reorder', [ServiceController::class, 'reorder']);

    // Credentials
    Route::apiResource('credentials', CredentialController::class);
    Route::post('/credentials/reorder', [CredentialController::class, 'reorder']);

    // Blog Posts
    Route::apiResource('blog-posts', BlogPostController::class);
    Route::post('/blog-posts/reorder-featured', [BlogPostController::class, 'reorderFeatured']);

    // Testimonials
    Route::apiResource('testimonials', TestimonialController::class);

    // Contact Submissions (read-only + status updates)
    Route::get('/contact-submissions', [ContactSubmissionController::class, 'index']);
    Route::get('/contact-submissions/{contactSubmission}', [ContactSubmissionController::class, 'show']);
    Route::patch('/contact-submissions/{contactSubmission}', [ContactSubmissionController::class, 'updateStatus']);

    // Booking Requests
    Route::get('/booking-requests', [BookingRequestController::class, 'index']);
    Route::get('/booking-requests/{bookingRequest}', [BookingRequestController::class, 'show']);
    Route::patch('/booking-requests/{bookingRequest}', [BookingRequestController::class, 'update']);

    // Images
    Route::post('/images', [ImageController::class, 'store']);
    Route::delete('/images/{image}', [ImageController::class, 'destroy']);
});
