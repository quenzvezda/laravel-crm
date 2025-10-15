<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->string('end_user_name', 150);
            $table->string('address', 255)->nullable();
            $table->string('machine_name', 255);
            $table->text('machine_function')->nullable();
            $table->text('machine_system')->nullable();
            $table->text('order_chronology')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('customer_orders');
    }
};
