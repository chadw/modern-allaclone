<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Merchantlist extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'merchantlist';

    public function npc(): BelongsTo
    {
        return $this->belongsTo(NpcType::class, 'merchantid', 'merchant_id');
    }
}
