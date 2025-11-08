<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('tb_pt')->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};
