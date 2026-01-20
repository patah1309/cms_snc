<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('business_type');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('seo_og_image_path')->nullable()->after('seo_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_og_image_path']);
        });
    }
};
