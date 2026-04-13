<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AaRankPrereq extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'aa_rank_prereqs';
    public $timestamps = false;

    public function rank(): BelongsTo
    {
        return $this->belongsTo(AaRank::class, 'rank_id');
    }

    public function prerequisiteAa(): BelongsTo
    {
        return $this->belongsTo(AaRank::class, 'aa_id', 'id');
    }

    public function ability(): BelongsTo
    {
        return $this->belongsTo(AaAbility::class, 'aa_id')
            ->select('id', 'name');
    }
}
