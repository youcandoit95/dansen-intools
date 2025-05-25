<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_blacklisted')->default(false)->after('alamat_lengkap')->index('idx_customers_is_blacklisted');
            $table->string('alasan_blacklist')->nullable()->after('is_blacklisted');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_is_blacklisted');
            $table->dropColumn(['is_blacklisted', 'alasan_blacklist']);
        });
    }
};
