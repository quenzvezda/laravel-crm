<?php

namespace Famindo\AnalyticalCRM\Models;

use Illuminate\Database\Eloquent\Model;

class AprioriRule extends Model
{
    protected $table = 'apriori_rules';

    protected $guarded = [];

    protected $casts = [
        'support'    => 'float',
        'confidence' => 'float',
        'lift'       => 'float',
    ];

    public function run()
    {
        return $this->belongsTo(AprioriRun::class, 'run_id');
    }
}
