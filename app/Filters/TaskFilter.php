<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilter
{
    protected $request;
    protected $builder;

    protected array $filters = [
        'name',
        'level',
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
        $this->builder->where('title', 'like', "%{$value}%");
    }

    protected function level($value)
    {
        $this->builder->where(function ($query) use ($value) {
            $query->where(function ($q) use ($value) {
                $q->where('min_level', 0)->orWhere('min_level', '<=', $value);
            })->where(function ($q) use ($value) {
                $q->where('max_level', 0)->orWhere('max_level', '>=', $value);
            });
        });
    }
}
