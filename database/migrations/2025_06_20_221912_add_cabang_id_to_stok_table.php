<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCabangIdToStokTable extends Migration
{
    public function up()
    {
        Schema::table('stok', function (Blueprint $table) {
            $table->unsignedBigInteger('cabang_id')->after('product_id')->nullable();

            // Tambahkan foreign key dengan nama custom yang unik
            $table->foreign('cabang_id', 'fk_stok_cabang_id_custom')
                  ->references('id')
                  ->on('cabang')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('stok', function (Blueprint $table) {
            // Hapus foreign key dan kolom
            $table->dropForeign('fk_stok_cabang_id_custom');
            $table->dropColumn('cabang_id');
        });
    }
}
