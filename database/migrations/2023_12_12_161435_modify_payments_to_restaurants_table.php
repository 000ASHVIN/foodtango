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
        Schema::table('payments_to_restaurants', function (Blueprint $table) {
            $table->decimal('total_payment', 10, 2)->nullable()->change();
            $table->decimal('total_order_payment', 10, 2)->nullable()->change();
            $table->decimal('total_service_fees', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments_to_restaurants', function (Blueprint $table) {
            //
        });
    }
};
