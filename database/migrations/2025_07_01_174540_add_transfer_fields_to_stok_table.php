<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stok', function (Blueprint $table) {
            // Cabang asal sebelum transfer (cabang_id adalah tujuan)
            $table->unsignedBigInteger('trfstok_cabang_asal_id')->nullable()->after('cabang_id');

            // Kurir pengiriman (1=Kurir Toko, 2=Gojek, 3=Grab, 4=Lalamove, 5=Paxel, 6=Maxim)
            $table->tinyInteger('trfstok_kurir')
                  ->nullable()
                  ->after('trfstok_cabang_asal_id')
                  ->comment('1=Kurir Toko, 2=Gojek, 3=Grab, 4=Lalamove, 5=Paxel, 6=Maxim');

            $table->string('trfstok_no_resi')->nullable()->after('trfstok_kurir');
            $table->string('trfstok_nama_kurir')->nullable()->after('trfstok_no_resi');
            $table->text('trfstok_keterangan')->nullable()->after('trfstok_nama_kurir');

            // Status transfer: 1=Dikirim, 2=Sampai, 3=Batal
            $table->tinyInteger('trfstok_status')->nullable()->after('trfstok_keterangan');
            $table->timestamp('trfstok_status_tanggal')->nullable()->after('trfstok_status');

            // Foreign key
            $table->foreign('trfstok_cabang_asal_id')->references('id')->on('cabang')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('stok', function (Blueprint $table) {
            $table->dropForeign(['trfstok_cabang_asal_id']);
            $table->dropColumn([
                'trfstok_cabang_asal_id',
                'trfstok_kurir',
                'trfstok_no_resi',
                'trfstok_nama_kurir',
                'trfstok_keterangan',
                'trfstok_status',
                'trfstok_status_tanggal',
            ]);
        });
    }
};
