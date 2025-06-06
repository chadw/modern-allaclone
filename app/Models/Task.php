<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'tasks';

    public function getRewardsAttribute()
    {
        $ids = explode('|', $this->reward_id_list);

        return Item::whereIn('id', $ids)
            ->select('id', 'Name', 'icon')
            ->get();
    }

    public function getTaskTypeAttribute(): string
    {
        return match ($this->type) {
            0 => 'Task',
            1 => 'Shared',
            2 => 'Quest',
            default => 'Unknown',
        };
    }

    public function taskActivities(): HasMany
    {
        return $this->hasMany(TaskActivity::class, 'taskid', 'id');
    }
}
