<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes()->index(); // kolom deleted_at
            $table->timestamps();

            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
