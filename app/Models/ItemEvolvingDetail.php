<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemEvolvingDetail extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'items_evolving_details';

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')
            ->select('id', 'Name', 'icon', 'evolvinglevel', 'evoid');
    }
}
