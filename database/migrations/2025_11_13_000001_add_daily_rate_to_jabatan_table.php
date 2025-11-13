<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jabatan', function (Blueprint $table) {
            if (!Schema::hasColumn('jabatan', 'daily_rate')) {
                $table->decimal('daily_rate', 12, 2)->default(0)->after('level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jabatan', function (Blueprint $table) {
            if (Schema::hasColumn('jabatan', 'daily_rate')) {
                $table->dropColumn('daily_rate');
            }
        });
    }
};
