<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_entries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('plan_month_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('day');
            $t->string('status', 24)->default('');
            $t->timestamps();
            $t->unique(['plan_month_id', 'user_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_entries');
    }
};
