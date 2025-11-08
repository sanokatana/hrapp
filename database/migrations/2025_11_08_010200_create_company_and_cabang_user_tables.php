<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('tb_pt')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'company_id']);
        });

        Schema::create('cabang_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cabang_id')->constrained('cabang')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'cabang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabang_user');
        Schema::dropIfExists('company_user');
    }
};
