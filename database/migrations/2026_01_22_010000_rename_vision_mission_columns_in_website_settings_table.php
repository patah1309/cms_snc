<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->text('core_values')->nullable()->after('about_us');
            $table->text('approach')->nullable()->after('core_values');
        });

        DB::table('website_settings')->update([
            'core_values' => DB::raw('vision'),
            'approach' => DB::raw('mission'),
        ]);

        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['vision', 'mission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->text('vision')->nullable()->after('about_us');
            $table->text('mission')->nullable()->after('vision');
        });

        DB::table('website_settings')->update([
            'vision' => DB::raw('core_values'),
            'mission' => DB::raw('approach'),
        ]);

        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['core_values', 'approach']);
        });
    }
};
