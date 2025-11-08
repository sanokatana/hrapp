<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('level')->default('Admin');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('department', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('department')->nullOnDelete();
            $table->string('nama');
            $table->string('level')->nullable();
            $table->timestamps();
        });

        Schema::create('tb_pt', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->string('long_name');
            $table->timestamps();
        });

        Schema::create('konfigurasi_lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kantor')->unique();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('radius')->default(100);
            $table->timestamps();
        });

        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->nullable()->unique();
            $table->string('no_hp')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_resign')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('department')->nullOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->foreignId('pt_id')->nullable()->constrained('tb_pt')->nullOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('konfigurasi_lokasi')->nullOnDelete();
            $table->string('status_kar')->default('Aktif');
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->index(['nik', 'status_kar']);
        });

        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('konfigurasi_lokasi')->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->timestamps();
            $table->unique(['karyawan_id', 'tanggal']);
            $table->index('tanggal');
        });

        Schema::create('libur_nasional', function (Blueprint $table) {
            $table->date('tanggal')->primary();
            $table->string('nama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libur_nasional');
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('konfigurasi_lokasi');
        Schema::dropIfExists('tb_pt');
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('department');
        Schema::dropIfExists('users');
    }
};
