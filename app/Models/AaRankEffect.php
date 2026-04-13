<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AaRankEffect extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'aa_rank_effects';
    public $timestamps = false;

    public function rank(): BelongsTo
    {
        return $this->belongsTo(AaRank::class, 'rank_id');
    }
}
