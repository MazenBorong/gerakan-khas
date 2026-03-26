<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_metas', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('role', 16)->default('staff');
            $t->timestamps();
            $t->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_metas');
    }
};
