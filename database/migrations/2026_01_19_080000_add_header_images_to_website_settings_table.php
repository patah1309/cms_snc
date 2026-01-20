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
            $table->string('header_home_path')->nullable();
            $table->string('header_about_path')->nullable();
            $table->string('header_services_path')->nullable();
            $table->string('header_news_path')->nullable();
            $table->string('header_kontak_path')->nullable();
            $table->string('header_seo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'header_home_path',
                'header_about_path',
                'header_services_path',
                'header_news_path',
                'header_kontak_path',
                'header_seo_path',
            ]);
        });
    }
};
