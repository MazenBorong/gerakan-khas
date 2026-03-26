<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_months', function (Blueprint $t) {
            $t->id();
            $t->unsignedSmallInteger('year');
            $t->unsignedTinyInteger('month');
            $t->timestamps();
            $t->unique(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_months');
    }
};
