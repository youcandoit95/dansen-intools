<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stok', function (Blueprint $table) {
            $table->unsignedInteger('ss_harga_beli')->nullable()->after('berat_kg');
            $table->unsignedInteger('total_harga_beli')->nullable()->after('ss_harga_beli');
        });
    }

    public function down()
    {
        Schema::table('stok', function (Blueprint $table) {
            $table->dropColumn('ss_harga_beli');
            $table->dropColumn('total_harga_beli');
        });
    }
};
