<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('cabang', function (Blueprint $table) {
        $table->string('initial', 3)->nullable()->after('nama_cabang');
    });
}

public function down(): void
{
    Schema::table('cabang', function (Blueprint $table) {
        $table->dropColumn('initial');
    });
}

};
