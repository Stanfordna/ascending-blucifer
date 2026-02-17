<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('color_primary', 7)->nullable()->after('meta_description');
            $table->string('color_secondary', 7)->nullable()->after('color_primary');
            $table->string('color_accent', 7)->nullable()->after('color_secondary');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('color_primary', 7)->nullable()->after('link_url');
            $table->string('color_secondary', 7)->nullable()->after('color_primary');
            $table->string('color_accent', 7)->nullable()->after('color_secondary');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('color_primary', 7)->nullable()->after('is_featured');
            $table->string('color_secondary', 7)->nullable()->after('color_primary');
            $table->string('color_accent', 7)->nullable()->after('color_secondary');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['color_primary', 'color_secondary', 'color_accent']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['color_primary', 'color_secondary', 'color_accent']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['color_primary', 'color_secondary', 'color_accent']);
        });
    }
};
