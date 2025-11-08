<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('nama')->constrained('tb_pt')->nullOnDelete();
            $table->foreignId('cabang_id')->nullable()->after('company_id')->constrained('cabang')->nullOnDelete();
        });

        Schema::table('jabatan', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('department_id')->constrained('tb_pt')->nullOnDelete();
            $table->foreignId('cabang_id')->nullable()->after('company_id')->constrained('cabang')->nullOnDelete();
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('pt_id')->constrained('tb_pt')->nullOnDelete();
            $table->foreignId('cabang_id')->nullable()->after('company_id')->constrained('cabang')->nullOnDelete();
        });

        Schema::table('konfigurasi_lokasi', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('radius')->constrained('tb_pt')->nullOnDelete();
            $table->foreignId('cabang_id')->nullable()->after('company_id')->constrained('cabang')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('konfigurasi_lokasi', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'cabang_id']);
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'cabang_id']);
        });

        Schema::table('jabatan', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'cabang_id']);
        });

        Schema::table('department', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'cabang_id']);
        });
    }
};
