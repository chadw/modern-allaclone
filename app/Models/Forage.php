<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forage extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'forage';

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zoneid', 'zoneidnumber');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'itemid', 'id');
    }
}
