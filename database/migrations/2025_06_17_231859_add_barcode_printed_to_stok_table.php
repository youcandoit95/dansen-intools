<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('stok', function (Blueprint $table) {
            $table->boolean('barcode_printed')->default(false)->after('barcode_stok')->index();
        });
    }

    public function down(): void {
        Schema::table('stok', function (Blueprint $table) {
            $table->dropColumn('barcode_printed');
        });
    }
};
