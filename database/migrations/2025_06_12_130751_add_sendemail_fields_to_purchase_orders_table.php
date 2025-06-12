<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('purchase_orders', function (Blueprint $table) {
        $table->timestamp('sendemail_at')->nullable()->after('ajukan_by');
        $table->unsignedBigInteger('sendemail_by')->nullable()->after('sendemail_at');

        // Index custom
        $table->index('sendemail_at', 'idx_purchase_orders_sendemail_at');
        $table->index('sendemail_by', 'idx_purchase_orders_sendemail_by');

        // Foreign key
        $table->foreign('sendemail_by')
            ->references('id')
            ->on('users')
            ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('purchase_orders', function (Blueprint $table) {
        // Drop foreign first
        $table->dropForeign(['sendemail_by']);

        // Drop custom indexes
        $table->dropIndex('idx_purchase_orders_sendemail_at');
        $table->dropIndex('idx_purchase_orders_sendemail_by');

        // Drop columns
        $table->dropColumn(['sendemail_at', 'sendemail_by']);
    });
}

};
