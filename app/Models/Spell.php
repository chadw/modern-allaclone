<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spell extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'spells_new';

    public function desc(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'descnum', 'id')->where('type', 6);
    }

    public function typedesc(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'typedescnum', 'id')->where('type', 6);
    }

    public function effectdesc(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'effectdescnum', 'id')->where('type', 6);
    }

    public function effectdesc2(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'effectdescnum2', 'id')->where('type', 6);
    }
}
