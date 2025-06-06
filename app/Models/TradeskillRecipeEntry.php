<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeskillRecipeEntry extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'tradeskill_recipe_entries';

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(TradeskillRecipe::class, 'recipe_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
