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

    public function getFixedDescriptionAttribute()
    {
        $desc = $this->description ?? '';
        if (!$desc) return '';

        $regex = '/\[(.*?)\]/';

        $result = [
            'global' => $this->replaceDescriptionContent(trim(preg_replace($regex, '', $desc))),
            'activities' => []
        ];

        if (preg_match_all($regex, $desc, $matches)) {
            foreach ($matches[1] as $match) {
                $desc_split = array_map('trim', explode(',', $match));
                $steps = [];

                // desc can be associated with multiple steps
                while (!empty($desc_split) && is_numeric($desc_split[0])) {
                    // pluck the step
                    $steps[] = (int) array_shift($desc_split);
                }

                if ($steps && $desc_split) {
                    $description = $this->replaceDescriptionContent(implode(',', $desc_split));

                    $result['activities'][] = [
                        'steps' => $steps,
                        'text' => $description,
                    ];
                }
            }
        }

        return $result;
    }

    private function replaceDescriptionContent($desc): string
    {
        $desc = trim($desc);
        $desc = str_replace(']', '', $desc);
        $desc = str_ireplace('<br>', '<br />', $desc);
        $desc = preg_replace_callback('/<c\s+"(#[0-9A-Fa-f]{3,6})">/', function ($matches) {
            return '<span style="color: ' . $matches[1] . '">';
        }, $desc);
        $desc = str_ireplace('</c>', '</span>', $desc);

        return $desc;
    }

    public function taskActivities(): HasMany
    {
        return $this->hasMany(TaskActivity::class, 'taskid', 'id')
            ->orderBy('activityid', 'asc')
            ->orderBy('step', 'asc');
    }
}
