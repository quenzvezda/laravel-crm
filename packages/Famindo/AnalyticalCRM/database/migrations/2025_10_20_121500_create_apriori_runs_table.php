<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('apriori_runs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();

            $table->date('period_start')->nullable()->index();
            $table->date('period_end')->nullable()->index();

            $table->decimal('support', 8, 5)->nullable();
            $table->decimal('confidence', 8, 5)->nullable();
            $table->unsignedInteger('min_items')->default(1);

            $table->json('filters_json')->nullable();

            $table->unsignedInteger('transactions_count')->default(0);
            $table->unsignedInteger('rules_count')->default(0);

            $table->string('status', 32)->default('processing');
            $table->boolean('is_active')->default(false)->index();

            $table->text('error_message')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();

            $table->timestamps();

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });

        Schema::table('apriori_rules', function (Blueprint $table) {
            if (! Schema::hasColumn('apriori_rules', 'run_id')) {
                $table->unsignedBigInteger('run_id')->nullable()->after('id')->index();

                $table->foreign('run_id')
                    ->references('id')->on('apriori_runs')
                    ->cascadeOnDelete();
            }
        });

        Schema::table('apriori_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('apriori_transactions', 'run_id')) {
                $table->unsignedBigInteger('run_id')->nullable()->after('id')->index();

                $table->foreign('run_id')
                    ->references('id')->on('apriori_runs')
                    ->cascadeOnDelete();
            }
        });

        // Ensure newest run is marked active if none exists.
        if (Schema::hasTable('apriori_runs')) {
            $hasActive = DB::table('apriori_runs')->where('is_active', true)->exists();

            if (! $hasActive) {
                $latest = DB::table('apriori_runs')->latest('created_at')->first();

                if ($latest) {
                    DB::table('apriori_runs')->where('id', $latest->id)->update(['is_active' => true]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('apriori_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('apriori_transactions', 'run_id')) {
                $table->dropForeign(['run_id']);
                $table->dropColumn('run_id');
            }
        });

        Schema::table('apriori_rules', function (Blueprint $table) {
            if (Schema::hasColumn('apriori_rules', 'run_id')) {
                $table->dropForeign(['run_id']);
                $table->dropColumn('run_id');
            }
        });

        Schema::dropIfExists('apriori_runs');
    }
};

