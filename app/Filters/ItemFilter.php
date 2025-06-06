<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ItemFilter
{
    protected $request;
    protected $builder;

    protected array $filters = [
        'name',
        'slot',
        'type',
        'class',
        'bagslots',
        /* stats */
        'stat1',
        'stat1comp',
        'stat1val',
        'stat2',
        'stat2comp',
        'stat2val',
        'stat3',
        'stat3comp',
        'stat3val',
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

        $this->applyLevelFilter();

        return $this->builder;
    }

    protected function name($value)
    {
        $this->builder->where('Name', 'like', "%{$value}%");
    }

    protected function type($value)
    {
        if ($value === null || $value === '') {
            return;
        }

        $value = (int) $value;

        // handle bagslots if custom bag type selected
        $bagSlots = ($this->request->get('bagslots') >= 1 ? $this->request->get('bagslots') : 1);

        if ($value === 7 || $value === 19) {
            // combine throwing
            $this->builder->whereIn('itemtype', [7, 19]);
        } elseif ($value == 56 || $value == 64) {
            // combine augment distillers
            $this->builder->whereIn('itemtype', [56, 64]);
        } elseif ($value == 33 || $value == 39) {
            // combine keys
            $this->builder->whereIn('itemtype', [33, 39]);
        } elseif ($value == 555) {
            // custom bag filter
            $this->builder->where('bagslots', '>=', $bagSlots)
                ->whereIn('bagtype', [0, 1, 2, 3, 4, 5, 6, 7]);
        } elseif ($value == 556) {
            // custom quest bag filter
            $this->builder->where('bagslots', '>=', $bagSlots)
                ->where('bagtype', 13);
        } elseif ($value == 557) {
            // custom ts bags filter
            $this->builder->where('bagslots', '>=', $bagSlots)
                ->where('bagtype', '>=', 9)
                ->where('bagtype', '!=', 13);
        } else {
            $this->builder->where('itemtype', $value);
        }
    }

    protected function bagslots($value)
    {
        if ($value === null || $value === '') {
            return;
        }

        $value = (int) $value;

        // if custom bag itemtype is selected, lets get it
        $hasType = (in_array($this->request->get('type'), [555, 556, 557]));
        if ($hasType) {
            return;
        }

        $this->builder->where('bagslots', '>=', $value);
    }

    protected function slot($value)
    {
        if ($value !== null && is_numeric($value)) {
            $bitmask = (int) $value;

            $this->builder->whereRaw("(slots & ?) != 0", [$bitmask]);
        }
    }

    protected function class($value)
    {
        if ($value !== null && is_numeric($value)) {
            $bitmask = (int) $value;

            $this->builder->whereRaw("(classes & ?) != 0", [$bitmask]);
        }
    }

    protected function stat1()
    {
        $this->applyStat('stat1');
    }

    protected function stat2()
    {
        $this->applyStat('stat2');
    }

    protected function stat3()
    {
        $this->applyStat('stat3');
    }

    protected function applyStat($statKey)
    {
        $stat = $this->request->get($statKey);
        $comp = $this->request->get($statKey . 'comp', 1);
        $val  = $this->request->get($statKey . 'val');

        if ($stat && $val !== null) {

            // fuck operators in url
            $op = match ((int) $comp) {
                1 => '>=',
                2 => '<=',
                5 => '=',
                default => '>='
            };

            $this->builder->where($stat, $op, $val);
        }
    }

    protected function applyLevelFilter()
    {
        $maxServerLevel = config('everquest.server_max_level');
        $minLevel = (int) $this->request->get('min_lvl');
        $maxLevel = (int) $this->request->get('max_lvl');

        if ($minLevel > 0 && $maxLevel === 0) {
            $maxLevel = $maxServerLevel;
        }

        if ($maxLevel > 0 && $minLevel === 0) {
            $minLevel = 0;
        }

        $maxLevel = min($maxLevel, $maxServerLevel);

        if ($minLevel > 0 || $maxLevel > 0) {
            $this->builder->whereBetween('reqlevel', [$minLevel, $maxLevel]);
        } else {
            $this->builder->where('reqlevel', '<=', $maxServerLevel);
        }
    }
}
