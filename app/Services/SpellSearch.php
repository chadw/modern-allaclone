<?php

namespace App\Services;

use App\Filters\SpellFilter;
use App\Models\Spell;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SpellSearch
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function search(): Collection
    {
        if ($this->request->filled('name') || $this->request->filled('class') || $this->request->filled('level')) {
            return (new SpellFilter($this->request))->apply(Spell::query())->get();
        }

        return collect();
    }

    public function groupSpells(Collection $spells): Collection
    {
        $class = $this->request->input('class');

        if ($class) {
            $class_col = "classes{$class}";

            return $spells->groupBy(fn($spell) => $spell->$class_col)
                ->filter(fn($spells, $level) => is_numeric($level))
                ->map(fn($spells, $level) => [
                    'level' => (int) $level,
                    'spells' => $spells,
                ])
                ->sortBy('level')
                ->values();
        }

        return $spells->groupBy(function ($spell) {
            $min = null;
            for ($i = 1; $i <= 16; $i++) {
                $lvl = $spell->{"classes{$i}"} ?? null;
                if (is_numeric($lvl) && $lvl > 0 && $lvl < 255) {
                    $min = is_null($min) ? $lvl : min($min, $lvl);
                }
            }
            return $min;
        })
        ->filter(fn($spells, $level) => is_numeric($level))
        ->map(fn($spells, $level) => [
            'level' => (int) $level,
            'spells' => $spells,
        ])
        ->sortBy('level')
        ->values();
    }

    public function extraSpellsQuery(array $excludeIds)
    {
        $name  = trim($this->request->input('name', ''));
        $class = (int) $this->request->input('class');
        $level = $this->request->filled('level')
            ? (int) $this->request->input('level')
            : config('everquest.server_max_level');

        $opt = (int) $this->request->input('opt', 2);

        if (!in_array($opt, [1, 2, 3], true)) {
            $opt = 2;
        }

        if ($class < 1 || $class > 16) {
            $class = null;
        }

        if (!$this->request->filled('name') || $name === '') {
            return Spell::query()->whereRaw('0 = 1');
        }

        $query = Spell::query()
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])
            ->when(!empty($excludeIds), fn ($q) => $q->whereNotIn('id', $excludeIds));

        $applyLevelFilter = function ($q, $column) use ($level, $opt) {
            return match ($opt) {
                1 => $q->where($column, '=', $level),
                2 => $q->where($column, '>=', $level),
                3 => $q->where($column, '<=', $level),
            };
        };

        if ($class) {
            $classCol = "classes{$class}";

            $query->where($classCol, '>', 0);

            $applyLevelFilter($query, $classCol);

        } else {
            $query->where(function ($q) use ($applyLevelFilter) {
                for ($i = 1; $i <= 16; $i++) {
                    $classCol = "classes{$i}";

                    $q->orWhere(function ($sub) use ($classCol, $applyLevelFilter) {
                        $sub->where($classCol, '>', 0);

                        $applyLevelFilter($sub, $classCol);
                    });
                }
            });
        }

        return $query;
    }

    public function extraSpells(array $excludeIds): array
    {
        $query = $this->extraSpellsQuery($excludeIds);

        return [
            'spells' => $query->limit(25)->get(),
            'count' => $query->count(),
        ];
    }
}
