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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('operating_hours')->nullable();
            $table->string('business_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
