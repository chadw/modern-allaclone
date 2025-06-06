<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskActivity extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'task_activities';

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zoneidnumber', 'zones');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'id', 'taskid');
    }
}
