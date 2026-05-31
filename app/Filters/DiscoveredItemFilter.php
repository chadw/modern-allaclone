<?php

namespace App\Filters;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DiscoveredItemFilter
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->character($query, $this->request->input('character'));
        $this->item($query, $this->request->input('item'));
        $this->dateRange(
            $query,
            $this->request->input('from'),
            $this->request->input('to')
        );

        return $query;
    }

    protected function character(Builder $query, $value): void
    {
        if (!$value) {
            return;
        }

        $value = trim((string) $value);

        $query->where(function ($q) use ($value) {
            if (is_numeric($value)) {
                $q->where('character_data.id', (int) $value);
            }
            $q->orWhere('discovered_items.char_name', 'like', "%{$value}%");
        });
    }

    protected function item(Builder $query, $value): void
    {
        if (!$value) return;

        $value = trim((string) $value);

        if (is_numeric($value)) {
            $query->where('item_id', (int) $value);
            return;
        }

        $itemIds = Item::query()
            ->where('Name', 'like', "%{$value}%")
            ->pluck('id');

        if ($itemIds->isEmpty()) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->whereIn('item_id', $itemIds);
    }

    protected function dateRange(Builder $query, ?string $from, ?string $to): void
    {
        if ($from) {
            $query->where(
                'discovered_date',
                '>=',
                Carbon::parse($from)->startOfDay()->timestamp
            );
        }

        if ($to) {
            $query->where(
                'discovered_date',
                '<=',
                Carbon::parse($to)->endOfDay()->timestamp
            );
        }
    }
}
