<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            $table->string('foto_surat_jalan_1')->nullable()->change();
            $table->string('foto_surat_jalan_2')->nullable()->change();
            $table->string('foto_surat_jalan_3')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            $table->string('foto_surat_jalan_1')->nullable(false)->change();
            $table->string('foto_surat_jalan_2')->nullable(false)->change();
            $table->string('foto_surat_jalan_3')->nullable(false)->change();
        });
    }
};
