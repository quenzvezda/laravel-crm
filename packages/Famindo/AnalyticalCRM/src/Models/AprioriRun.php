<?php

namespace Famindo\AnalyticalCRM\Models;

use Illuminate\Database\Eloquent\Model;

class AprioriRun extends Model
{
    protected $table = 'apriori_runs';

    protected $guarded = [];

    protected $casts = [
        'period_start'        => 'date',
        'period_end'          => 'date',
        'support'             => 'float',
        'confidence'          => 'float',
        'filters_json'        => 'array',
        'is_active'           => 'bool',
        'transactions_count'  => 'int',
        'rules_count'         => 'int',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_active')->orderByDesc('created_at');
    }

    public function activate(): void
    {
        static::query()->update(['is_active' => false]);

        $this->forceFill([
            'is_active' => true,
        ])->save();
    }
}

