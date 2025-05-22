<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->tinyInteger('kategori'); // 1 = loaf/kg, 2 = cut/kg, 3 = pcs/pack
            $table->tinyInteger('brand');

            $table->unsignedBigInteger('mbs_id')->nullable()->index();
            $table->foreign('mbs_id', 'fk_products_mbs')
                ->references('id')->on('mbs')
                ->nullOnDelete();

            $table->unsignedBigInteger('bagian_daging_id')->nullable()->index();
            $table->foreign('bagian_daging_id', 'fk_products_bagian_daging')
                ->references('id')->on('bagian_daging')
                ->nullOnDelete();


            $table->string('nama')->index();
            $table->text('deskripsi')->nullable();
            $table->boolean('status')->default(true); // true = aktif

            $table->timestamps();
            $table->softDeletes()->index();

            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
