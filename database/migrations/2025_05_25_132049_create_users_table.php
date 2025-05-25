<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('no_wa')->nullable();
            $table->string('password');
            $table->boolean('superadmin')->default(false)->index();
            $table->boolean('manager')->default(false)->index();
            $table->boolean('supervisor')->default(false)->index();
            $table->boolean('staff')->default(false)->index();
            $table->boolean('status')->default(true)->index();
            $table->rememberToken();
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
