<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAjukanColumnsToPurchaseOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->timestamp('ajukan_at')->nullable()->after('catatan');
            $table->unsignedBigInteger('ajukan_by')->nullable()->after('ajukan_at');

            $table->foreign('ajukan_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['ajukan_by']);
            $table->dropColumn(['ajukan_at', 'ajukan_by']);
        });
    }
}
