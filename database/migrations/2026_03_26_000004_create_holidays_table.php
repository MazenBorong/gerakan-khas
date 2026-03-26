<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $t) {
            $t->id();
            $t->date('on_date');
            $t->string('label')->nullable();
            $t->timestamps();
            $t->unique('on_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
