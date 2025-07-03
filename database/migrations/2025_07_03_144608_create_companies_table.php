<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('nama')->index();

            $table->unsignedBigInteger('domisili_id')->nullable()->index();
            $table->string('telepon')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->text('alamat')->nullable();

            $table->boolean('blacklist')->default(false)->index();
            $table->text('alasan_blacklist')->nullable();

            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes()->index();

            // Foreign Keys
            $table->foreign('domisili_id')->references('id')->on('domisili')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
