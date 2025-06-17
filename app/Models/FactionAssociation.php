<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactionAssociation extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'faction_association';

    public function factionList()
    {
        return $this->hasOne(FactionList::class, 'id', 'id');
    }
}
