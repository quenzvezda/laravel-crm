<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('apriori_transactions');

        Schema::create('apriori_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('lead_id')->nullable()->index();
            $table->unsignedInteger('quote_id')->nullable()->index();

            $table->json('items');

            $table->timestamps();

            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->nullOnDelete();

            $table->foreign('quote_id')
                ->references('id')->on('quotes')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apriori_transactions');
    }
};

