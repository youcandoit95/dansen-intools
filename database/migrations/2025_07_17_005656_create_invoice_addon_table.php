<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_addon', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inv_id');
            $table->string('nama');
            $table->integer('qty')->default(1);
            $table->integer('harga')->default(0);
            $table->integer('total')->default(0);
            $table->text('catatan')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign keys dengan nama unik
            $table->foreign('inv_id', 'fk_invoice_addon_inv_id')
                  ->references('id')->on('invoices')
                  ->onDelete('cascade');

            $table->foreign('created_by', 'fk_invoice_addon_created_by')
                  ->references('id')->on('users')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_addon');
    }
};
