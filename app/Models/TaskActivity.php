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

    public function getNpcsAttribute()
    {
        $npc_list = $this->attributes['npc_match_list'] ?? '';
        $zone_version = $this->attributes['zone_version'] ?? null;
        $zone_ids = explode(',', $this->attributes['zones'] ?? '');
        $zone = null;

        if (!empty($zone_ids)) {
            $zone = Zone::whereIn('zoneidnumber', $zone_ids)
                ->select('id', 'zoneidnumber', 'short_name', 'long_name', 'version')
                ->first();
        }

        //if ($this->attributes['activityid'] == 2) {
        //    dd($npc_list, $zone_ids);
        //}

        if (!$npc_list) {
            return collect();
        }

        $npcs = array_map('trim', explode('|', $npc_list));

        $result = NpcType::where($this->npcFilter($npcs))
            ->whereHas('spawnentries.spawn2', function ($query) use ($zone, $zone_version) {
                if ($zone) {
                    $query->where('zone', $zone->short_name);
                    //@todo needs testing
                    if ($zone_version !== null) {
                        $query->whereIn('version', [-1, (int) $zone_version]);
                    }
                }
            })
            // we want zoneData because this shit is a mess, and since tasks don't require a zone id
            // we have to grab the npc, and related data and hope all relations are solid
            ->with(['spawnentries.spawn2.zoneData'])
            ->select('id', 'name')
            ->get();

        // this is failsafe? what a shit way to associate npcs
        if ($result->isEmpty()) {
            $npcWTF = NpcType::where($this->npcFilter($npcs))
                ->select('id', 'name')
                ->get();

            return $npcWTF->filter(function ($npc) use ($zone_ids) {
                $zoneid = (int) substr((string) $npc->id, 0, -3);
                return in_array($zoneid, $zone_ids);
            })->values();
        }

        return $result ?? collect();
    }

    public function getZonesAttribute()
    {
        $zone_list = $this->attributes['zones'] ?? '';
        $zone_version = $this->attributes['zone_version'] ?? '';

        if ($zone_list) {
            $zones = array_filter(explode(',', $zone_list));

            $zone = Zone::whereIn('zoneidnumber', $zones)
                ->select('id', 'zoneidnumber', 'long_name');

            if ($zone_version && $zone_version !== -1) {
                $zone->where('version', (int) $zone_version);
            }

            return $zone->get();
        }

        return collect();
    }

    public function getItemsAttribute()
    {
        $item_list = $this->attributes['item_id_list'] ?? '';

        if ($item_list) {
            $items = array_filter(explode('|', $item_list));

            return Item::whereIn('id', $items)
                ->select('id', 'Name', 'icon')
                ->get();
        }

        return collect();
    }

    private function npcFilter(array $npcs): \Closure
    {
        return function ($query) use ($npcs) {
            foreach ($npcs as $npc) {
                if (is_numeric($npc)) {
                    $query->orWhere('id', $npc);
                } elseif (!empty($npc)) {
                    $query->orWhere('name', 'like', "%{$npc}%");
                }
            }
        };
    }
}
