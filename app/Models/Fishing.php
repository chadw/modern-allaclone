<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fishing extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'fishing';

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zoneid', 'zoneidnumber');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'itemid', 'id');
    }
}
