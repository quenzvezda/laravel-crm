<?php

namespace Famindo\AnalyticalCRM\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class RunAprioriJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Options passed to the analytics:apriori command.
     */
    protected array $options;

    /**
     * Create a new job instance.
     */
    public function __construct(array $options = [])
    {
        $this->onQueue('analytics');
        $this->options = $options;

        // Long-running analysis: allow generous timeout at worker level.
        // Configure worker with --timeout accordingly (e.g., 3600).
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Delegate to existing console command to keep logic in one place.
        Artisan::call('analytics:apriori', $this->options);
    }
}

