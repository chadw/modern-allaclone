<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZonePoint extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'zone_points';

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone', 'short_name');
    }

    public function targetZones(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'target_zone_id', 'zoneidnumber');
    }
}
