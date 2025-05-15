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
        Schema::create('cabang', function (Blueprint $table) {
            $table->id();

            $table->string('nama_cabang')->index();
            $table->string('alamat');
            $table->string('telepon');
            $table->string('nama_pic');
            $table->boolean('status')->default(true)->index();

            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at

            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};
