<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Zone;

class NpcFilter
{
    protected $request;
    protected $builder;

    protected array $filters = [
        'name',
        'min_lvl',
        'max_lvl',
        'zone',
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters as $filter) {
            if (method_exists($this, $filter) && $this->request->filled($filter)) {
                $this->{$filter}($this->request->get($filter));
            }
        }

        return $this->builder;
    }

    protected function name($value)
    {
        if ($value === null || $value === '') {
            return;
        }

        $value = str_replace(' ', '_', $value);
        $value = str_replace('`', '-', $value);

        $this->builder->where('name', 'like', "%{$value}%");
    }

    protected function min_lvl($value)
    {
        $this->builder->where('level', '>=', $value);
    }

    protected function max_lvl($value)
    {
        $this->builder->where('level', '<=', $value);
    }

    protected function zone($value)
    {
        if ($value === null || $value === '') return;

        $value = trim($value);

        $zoneQuery = Zone::query();
        if (is_numeric($value)) {
            $zoneQuery->where('zoneidnumber', (int)$value);
        } else {
            $zoneQuery->where('short_name', 'like', "%{$value}%")
                ->orWhere('long_name', 'like', "%{$value}%");
        }

        $zoneIds = $zoneQuery->pluck('zoneidnumber')->unique()->values()->all();
        if (empty($zoneIds)) {
            return;
        }

        $this->builder->where(function ($q) use ($zoneIds) {
            $q->whereHas('spawnEntries', function ($q2) use ($zoneIds) {
                $q2->whereHas('spawn2', function ($q3) use ($zoneIds) {
                    $q3->whereHas('zoneData', function ($q4) use ($zoneIds) {
                        $q4->whereIn('zoneidnumber', $zoneIds);
                    });
                });
            });

            $inList = implode(',', array_map('intval', $zoneIds));
            $q->orWhereRaw("CAST(SUBSTRING(id, 1, LENGTH(id) - 3) AS UNSIGNED) IN ({$inList})");
        });
    }
}
