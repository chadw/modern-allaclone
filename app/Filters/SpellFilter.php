<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SpellFilter
{
    protected $request;
    protected $builder;

    protected array $filters = [
        'name',
        'class',
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
        $this->builder->where('name', 'like', "%{$value}%");
    }

    protected function level($value)
    {
        $level = (int) $value;
        $opt = 3;
        $class = $this->request->get('class');

        if ($class && $level !== null && is_numeric($level) && $this->request->get('opt')) {
            $opt = (int) $this->request->get('opt');
            $class_col = "classes{$class}";

            if ($opt === 1) {
                $this->builder->where($class_col, $level);
            } elseif ($opt === 2) {
                $this->builder->where($class_col, '>=', $level);
            } elseif ($opt === 3) {
                $this->builder->where($class_col, '<=', $level);
            }
        }
    }

    protected function class($value)
    {
        if ($value !== null && is_numeric($value)) {
            $class_col = "classes{$value}";

            $this->builder->where($class_col, '>', 0)
                ->where($class_col, '<=', config('everquest.server_max_level'));
        }
    }
}
