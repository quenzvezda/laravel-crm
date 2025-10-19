<?php

namespace Famindo\AnalyticalCRM\DataGrids;

use Famindo\AnalyticalCRM\Models\AprioriRun;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AprioriRulesDataGrid extends DataGrid
{
    protected $sortColumn = 'created_at';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('apriori_rules')->select(
            'apriori_rules.id',
            'apriori_rules.run_id',
            'apriori_rules.lhs',
            'apriori_rules.rhs',
            'apriori_rules.support',
            'apriori_rules.confidence',
            'apriori_rules.lift',
            'apriori_rules.period_start',
            'apriori_rules.period_end',
            'apriori_rules.created_at',
            'runs.name as run_name'
        );

        $queryBuilder->leftJoin('apriori_runs as runs', 'runs.id', '=', 'apriori_rules.run_id');

        $runId = request()->input('run_id');

        if (! $runId) {
            $runId = AprioriRun::where('is_active', true)->value('id');
        }

        if ($runId) {
            $queryBuilder->where('apriori_rules.run_id', $runId);
        }

        $this->addFilter('support', 'support');
        $this->addFilter('confidence', 'confidence');
        $this->addFilter('lift', 'lift');
        $this->addFilter('period_start', 'period_start');
        $this->addFilter('period_end', 'period_end');
        $this->addFilter('created_at', 'created_at');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'run_name',
            'label'      => 'Snapshot',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'lhs',
            'label'      => 'LHS',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => fn ($row) => implode(', ', (array) json_decode($row->lhs, true)),
        ]);

        $this->addColumn([
            'index'      => 'rhs',
            'label'      => 'RHS',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => fn ($row) => implode(', ', (array) json_decode($row->rhs, true)),
        ]);

        $this->addColumn([
            'index'      => 'support',
            'label'      => 'Support',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => number_format((float) $row->support, 4),
        ]);

        $this->addColumn([
            'index'      => 'confidence',
            'label'      => 'Confidence',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => number_format((float) $row->confidence, 4),
        ]);

        $this->addColumn([
            'index'      => 'lift',
            'label'      => 'Lift',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => number_format((float) $row->lift, 4),
        ]);

        $this->addColumn([
            'index'      => 'period_start',
            'label'      => 'Period Start',
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'period_end',
            'label'      => 'Period End',
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Created At',
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }
}
