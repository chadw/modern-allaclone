<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterData extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'character_data';

    public function discoveredItems(): HasMany
    {
        return $this->hasOne(DiscoveredItem::class, 'char_name', 'name')
            ->select('id', 'name');
    }
}
