<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gk_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('max_wfh_per_day')->default(2);
            $table->unsignedSmallInteger('lead_days_ahead')->default(7);
            $table->string('malaysia_holidays_url', 512)->nullable();
            $table->string('default_new_user_role', 16)->default('staff');
            $table->timestamps();
        });

        DB::table('gk_settings')->insert([
            'max_wfh_per_day' => 2,
            'lead_days_ahead' => 7,
            'malaysia_holidays_url' => null,
            'default_new_user_role' => 'staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('gk_settings');
    }
};
