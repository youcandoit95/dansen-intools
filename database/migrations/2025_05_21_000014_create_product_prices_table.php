<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();

            // Buat kolom terlebih dahulu
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('supplier_id')->index();

            $table->unsignedBigInteger('harga')->index();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->index('created_at');
            $table->index('updated_at');

            // Baru kemudian tambahkan constraint foreign key
            $table->foreign('product_id', 'fk_product_prices_product')
                ->references('id')->on('products')->restrictOnDelete();

            $table->foreign('supplier_id', 'fk_product_prices_supplier')
                ->references('id')->on('suppliers')->restrictOnDelete();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
