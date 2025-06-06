<?php
use Illuminate\Support\Facades\Config;

if (!function_exists('eq_race')) {
    function eq_race($id)
    {
        $races = config('everquest.races');
        return $races[$id] ?? 'Unknown';
    }
}

if (!function_exists('eq_class')) {
    function eq_class($id)
    {
        $classes = config('everquest.classes');
        return $classes[$id] ?? 'Unknown';
    }
}

if (!function_exists('eq_language')) {
    function eq_language($id)
    {
        $lang = config('everquest.languages');
        return $lang[$id] ?? 'Unknown';
    }
}

if (!function_exists('eq_deity')) {
    function eq_deity($id)
    {
        $deity = config('everquest.deity');
        return $deity[$id] ?? 'Unknown';
    }
}

if (!function_exists('item_bagtypes')) {
    function item_bagtypes($id) {
        $bagtypes = config('everquest.bagtypes');
        return $bagtypes[$id] ?? 'Unknown';
    }
}

if (!function_exists('eq_skills')) {
    function eq_skills() {
        return config('everquest.skills');
    }
}

if (!function_exists('eq_aa_types')) {
    function eq_aa_types() {
        return config('everquest.aa_types');
    }
}

if (!function_exists('seconds_to_human')) {
    function seconds_to_human($seconds): string
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        if ($days > 0) {
            $parts[] = "{$days}d";
        }
        if ($hours > 0 || $days > 0) {
            $parts[] = "{$hours}h";
        }
        $parts[] = "{$minutes}m";

        return implode(' ', $parts);
    }
}

if (!function_exists('sign')) {
    function sign($val) {
        return ($val > 0 ? '+' : '') . $val;
    }
}

if (!function_exists('get_class_usable_string')) {
    function get_class_usable_string($val): string {
        $classes = config('everquest.classes_short');
        $result = [];

        // Special case for "ALL"
        if (isset($classes[65535]) && $val == 65535) {
            return $classes[65535];
        }

        foreach ($classes as $key => $short) {
            if (($val & $key) === $key && $key != 65535) {
                $result[] = $short;
            }
        }

        return implode(' ', $result);
    }
}

if (!function_exists('get_race_usable_string')) {
    function get_race_usable_string($val): string {
        $races = config('everquest.races_short');
        $result = [];

        // Special case for "ALL"
        if (isset($races[65535]) && $val == 65535) {
            return $races[65535];
        }

        foreach ($races as $key => $short) {
            if (($val & $key) === $key && $key != 65535) {
                $result[] = $short;
            }
        }

        return implode(' ', $result);
    }
}

if (!function_exists('get_deity_usable_string')) {
    function get_deity_usable_string($val): string {
        $deities = config('everquest.deities_short');
        $result = [];

        foreach ($deities as $bit => $name) {
            if (($val & $bit) === $bit) {
                $result[] = $name;
            }
        }

        return implode(', ', $result);
    }
}

if (!function_exists('get_slots_string')) {
    function get_slots_string($val): string {
        $slots = config('everquest.slots');
        $result = [];

        foreach ($slots as $bit => $name) {
            if (($val & $bit) === $bit) {
                $result[] = $name;
            }
        }

        return implode(', ', $result);
    }
}

if (!function_exists('item_aug_data')) {
    function item_aug_data($item) {
        $augdb = config('everquest.db_aug_restrict');
        $html = '';

        if (($item->itemtype ?? 0) == 54) {
            $html .= '<div class="mt-6 text-sm">';

            if (($item->augtype ?? 0) > 0) {
                $augType = $item->augtype;
                $augSlots = [];

                for ($i = 1, $bit = 1; $i <= 24; $i++, $bit *= 2) {
                    if ($bit <= $augType && ($augType & $bit)) {
                        $augSlots[] = $i;
                    }
                }

                $slotsText = implode(', ', $augSlots);
                $html .= "<p><strong>Aug Slot Type:</strong> {$slotsText}</p>";
            } else {
                $html .= "<p><strong>Aug Slot Type:</strong> All Slots</p>";
            }

            // Handle Augmentation Restriction
            $augRestrict = $item->augrestrict ?? 0;

            if ($augRestrict > 0) {
                if ($augRestrict > 12 || !isset($augdb[$augRestrict])) {
                    $html .= "<p><strong>Aug Restriction:</strong> Unknown Type</p>";
                } else {
                    $restriction = $augdb[$augRestrict];
                    $html .= "<p><strong>Aug Restriction:</strong> {$restriction}</p>";
                }
            }
            $html .= '</div>';
        }

        return $html;
    }
}

if (!function_exists('calculate_item_price')) {
    function calculate_item_price($price) {
        $platinum = intdiv($price, 1000);
        $price -= $platinum * 1000;

        $gold = intdiv($price, 100);
        $price -= $gold * 100;

        $silver = intdiv($price, 10);
        $price -= $silver * 10;

        $copper = $price;

        return [
            'platinum' => $platinum,
            'gold'     => $gold,
            'silver'   => $silver,
            'copper'   => $copper,
        ];
    }
}

if (!function_exists('get_food_drink_desc')) {
    function get_food_drink_desc(int $key, int $type) {
        if ($key <= 0) {
            return null;
        }

        if ($type == 14) {
            $str = config('everquest.food_types');
        } elseif($type == 15) {
            $str = config('everquest.drink_types');
        }

        if ($key >= 1 && $key <= 5) {
            return $str[0];
        } elseif ($key <= 20) {
            return $str[1];
        } elseif ($key <= 30) {
            return $str[2];
        } elseif ($key <= 40) {
            return $str[3];
        } elseif ($key <= 50) {
            return $str[4];
        } elseif ($key <= 60) {
            return $str[5];
        } else {
            return $str[6];
        }
    }
}

if (!function_exists('price')) {
    function price(int $price): string
    {
        if ($price <= 0) {
            return '0 cp';
        }

        $p = intdiv($price, 1000);
        $price %= 1000;

        $g = intdiv($price, 100);
        $price %= 100;

        $s = intdiv($price, 10);
        $c = $price % 10;

        $parts = [];

        if ($p > 0) {
            $parts[] = "{$p} pp";
        }
        if ($g > 0) {
            $parts[] = "{$g} gp";
        }
        if ($s > 0) {
            $parts[] = "{$s} sp";
        }
        if ($c > 0 || empty($parts)) {
            $parts[] = "{$c} cp";
        }

        return implode(' ', $parts);
    }
}

if (!function_exists('spell_desc')) {
    /*
    * @TODO
    * fix/update
    */
    function spell_desc($spell, $n)
    {
        $serverMaxLvl = config('everquest.server_max_level');
        $dbspelleffects = config('everquest.spell_effects');
        $dbspelltargets = config('everquest.spell_targets');
        $dbiracenames = config('everquest.races');

        $print_buffer = '<ul>';

        if (($spell["effectid$n"] != 254) AND ($spell["effectid$n"] != 10)) {
            $maxlvl = $spell["effect_base_value$n"];
            $minlvl = $serverMaxLvl;
            for ($i = 1; $i <= 16; $i++) {
                if ($spell["classes" . $i] < $minlvl) {
                    $minlvl = $spell["classes" . $i];
                }
            }
            $min        = calc_spelleffect_value(
                $spell["formula" . $n],
                $spell["effect_base_value$n"],
                $spell["max$n"],
                $minlvl
            );
            $max        = calc_spelleffect_value(
                $spell["formula" . $n],
                $spell["effect_base_value$n"],
                $spell["max$n"],
                config('everquest.server_max_level')
            );
            $base_limit = $spell["effect_limit_value$n"];
            if (($min < $max) AND ($max < 0)) {
                $tn  = $min;
                $min = $max;
                $max = $tn;
            }

            $print_buffer .= "<b>$n : Effect type : </b>";

            switch ($spell["effectid$n"]) {
                case 3: // Increase Movement (% / 0)
                    if ($max < 0) { // Decrease
                        $print_buffer .= "Decrease Movement";
                        if ($min != $max) {
                            $print_buffer .= " by " . abs($min) . "% to " . abs($max) . "%";
                        } else {
                            $print_buffer .= " by " . abs(100) . "%";
                        }
                    } else {
                        $print_buffer .= "Increase Movement";
                        if ($min != $max) {
                            $print_buffer .= " by " . $min . "% to " . ($max) . "%";
                        } else {
                            $print_buffer .= " by " . ($max) . "%";
                        }
                    }
                    break;
                case 11: // Decrease OR Inscrease AttackSpeed (max/min = percentage of speed / normal speed, IE, 70=>-30% 130=>+30%
                    if ($max < 100) { // Decrease
                        $print_buffer .= "Decrease Attack Speed";
                        if ($min != $max) {
                            $print_buffer .= " by " . (100 - $min) . "% to " . (100 - $max) . "%";
                        } else {
                            $print_buffer .= " by " . (100 - $max) . "%";
                        }
                    } else {
                        $print_buffer .= "Increase Attack Speed";
                        if ($min != $max) {
                            $print_buffer .= " by " . ($min - 100) . "% to " . ($max - 100) . "%";
                        } else {
                            $print_buffer .= " by " . ($max - 100) . "%";
                        }
                    }
                    break;
                case 21: // stun
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    if ($min != $max) {
                        $print_buffer .= " (" . ($min / 1000) . " sec (L$minlvl) to " . ($max / 1000) . " sec (L$maxlvl))";
                    } else {
                        $print_buffer .= " (" . ($max / 1000) . " sec)";
                    }
                    break;
                case 32: // summonitem
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    //$name         = get_field_result("name", "SELECT name FROM $items_table WHERE id=" . $spell["effect_base_value$n"]);
                    $name = '[PLACEHOLDER]';
                    if ($name != "") {
                        $print_buffer .= " : <a href=?a=item&id=" . $spell["effect_base_value$n"] . ">$name</a>";
                    }
                    break;
                case 87: // Increase Magnification
                case 98: // Increase Haste v2
                case 114: // Increase Agro Multiplier
                case 119: // Increase Haste v3
                case 123: // Increase Spell Damage
                case 124: // Increase Spell Damage
                case 125: // Increase Spell Healing
                case 127: // Increase Spell Haste
                case 128: // Increase Spell Duration
                case 129: // Increase Spell Range
                case 130: // Decrease Spell/Bash Hate
                case 131: // Decrease Chance of Using Reagent
                case 132: // Decrease Spell Mana Cost
                case 158: // Increase Chance to Reflect Spell
                case 168: // Increase Melee Mitigation
                case 169: // Increase Chance to Critical Hit
                case 172: // Increase Chance to Avoid Melee
                case 173: // Increase Chance to Riposte
                case 174: // Increase Chance to Dodge
                case 175: // Increase Chance to Parry
                case 176: // Increase Chance to Dual Wield
                case 177: // Increase Chance to Double Attack
                case 180: // Increase Chance to Resist Spell
                case 181: // Increase Chance to Resist Fear Spell
                case 183: // Increase All Skills Skill Check
                case 184: // Increase Chance to Hit With all Skills
                case 185: // Increase All Skills Damage Modifier
                case 186: // Increase All Skills Minimum Damage Modifier
                case 188: // Increase Chance to Block
                case 200: // Increase Proc Modifier
                case 201: // Increase Range Proc Modifier
                case 216: // Increase Accuracy
                case 227: // Reduce Skill Timer
                case 266: // Add Attack Chance
                case 273: // Increase Critical Dot Chance
                case 294: // Increase Critical Spell Chance
                    $name = $dbspelleffects[$spell["effectid$n"]];
                    // For several of these cases, we have better information on
                    // the range of values for the focus effect.
                    switch ($spell["effectid$n"]) {
                        case 123: // Increase Spell Damage
                        case 124: // Increase Spell Damage
                        case 125: // Increase Spell Healing
                        case 131: // Decrease Chance of Using Reagent
                        case 132: // Decrease Spell Mana Cost
                            $min = $spell["effect_base_value$n"];
                            $max = $spell["effect_limit_value$n"];
                            break;
                        // Reword this effect to seem more natural, matching
                        // Allakhazam.
                        case 130: // Decrease Spell/Bash Hate
                            $min = $spell["effect_base_value$n"];
                            $max = $spell["effect_limit_value$n"];
                            $name = str_replace("Decrease", "Increase", $name);
                            break;
                    }
                    $print_buffer .= $name;
                    if ($min != $max) {
                        $print_buffer .= " by $min% to $max%";
                    } else {
                        $print_buffer .= " by $max%";
                    }
                    break;
                case 15: // Increase Mana per tick
                case 100: // Increase Hitpoints v2 per tick
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    if ($min != $max) {
                        $print_buffer .= " by " . abs($min) . " to " . abs(
                                $max
                            ) . " per tick (total " . abs($min * $spell['duration']) . " to " . abs(
                                             $max * $spell['duration']
                                         ) . ")";
                    } else {
                        $print_buffer .= " by $max per tick (total " . abs($max * $spell['duration']) . ")";
                    }
                    break;
                case 30: // Frenzy Radius
                case 86: // Reaction Radius
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " (" . $spell["effect_base_value$n"] . "/" . $spell["effect_limit_value$n"] . ")";
                    break;
                case 22: // Charm
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " up to level " . $spell["max1"];
                    break;
                case 23: // Fear
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " up to level " . $spell["max1"];
                    break;
                case 31: // Mesmerize
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " up to level " . $spell["max1"];
                    break;
                case 33: // Summon Pet:
                case 68: // Summon Skeleton Pet:
                case 106: // Summon Warder:
                case 108: // Summon Familiar:
                case 113: // Summon Horse:
                case 152: // Summon Pets:
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " <a href=?a=pet&name=" . $spell["teleport_zone"] . ">" . $spell["teleport_zone"] . "</a>";
                    break;
                case 13: // See Invisible
                case 18: // Pacify
                case 20: // Blindness
                case 25: // Bind Affinity
                case 26: // Gate
                case 28: // Invisibility versus Undead
                case 29: // Invisibility versus Animals
                case 40: // Invunerability
                case 41: // Destroy Target
                case 42: // Shadowstep
                case 44: // Lycanthropy
                case 52: // Sense Undead
                case 53: // Sense Summoned
                case 54: // Sense Animals
                case 56: // True North
                case 57: // Levitate
                case 61: // Identify
                case 64: // SpinStun
                case 65: // Infravision
                case 66: // UltraVision
                case 67: // Eye of Zomm
                case 68: // Reclaim Energy
                case 73: // Bind Sight
                case 74: // Feign Death
                case 75: // Voice Graft
                case 76: // Sentinel
                case 77: // Locate Corpse
                case 82: // Summon PC
                case 90: // Cloak
                case 93: // Stop Rain
                case 94: // Make Fragile (Delete if combat)
                case 95: // Sacrifice
                case 96: // Silence
                case 99: // Root
                case 101: // Complete Heal (with duration)
                case 103: // Call Pet
                case 104: // Translocate target to their bind point
                case 105: // Anti-Gate
                case 115: // Food/Water
                case 117: // Make Weapons Magical
                case 135: // Limit: Resist(Magic allowed)
                case 137: // Limit: Effect(Hitpoints allowed)
                case 138: // Limit: Spell Type(Detrimental only)
                case 141: // Limit: Instant spells only
                case 150: // Death Save - Restore Full Health
                case 151: // Suspend Pet - Lose Buffs and Equipment
                case 154: // Remove Detrimental
                case 156: // Illusion: Target
                case 178: // Lifetap from Weapon Damage
                case 179: // Instrument Modifier
                case 182: // Hundred Hands Effect
                case 194: // Fade
                case 195: // Stun Resist
                case 205: // Rampage
                case 206: // Area of Effect Taunt
                case 311: // Limit: Combat Skills Not Allowed
                case 314: // Fixed Duration Invisbility
                case 315:
                case 316:
                case 299: // Wake the Dead
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    break;
                case 58: // Illusion:
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= $dbiracenames[$spell["effect_base_value$n"]];
                    break;
                case 63: // Memblur
                case 120: // Set Healing Effectiveness
                case 330: // Critical Damage Mob
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " ($max%)";
                    break;
                case 81: // Resurrect
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " and restore " . $spell["effect_base_value$n"] . "% experience";
                    break;
                case 83: // Teleport
                case 88: // Evacuate
                case 145: // Teleport v2
                    //$print_buffer .= " (Need to add zone to spells table)";
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " <a href=?a=zone&name=" . $spell["teleport_zone"] . ">" . $spell["teleport_zone"] . "</a>";
                    break;
                case 85: // Add Proc:
                case 289: // Improved Spell Effect:
                case 323: // Add Defensive Proc:
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    //$name         = get_field_result("name", "SELECT name FROM $spells_table WHERE id=" . $spell["effect_base_value$n"]);
                    $name = '[656: PLACEHOLDER]';
                    $print_buffer .= "<a href=?a=spell&id=" . $spell["effect_base_value$n"] . ">$name</a>";
                    break;
                case 89: // Increase Player Size
                    $name = $dbspelleffects[$spell["effectid$n"]];
                    $min  -= 100;
                    $max  -= 100;
                    if ($max < 0) {
                        $name = str_replace("Increase", "Decrease", $name);
                    }
                    $print_buffer .= $name;
                    if ($min != $max) {
                        $print_buffer .= " by $min% to $max%";
                    } else {
                        $print_buffer .= " by $max%";
                    }
                    break;
                case 27: // Cancel Magic
                case 134: // Limit: Max Level
                case 157: // Spell-Damage Shield
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " ($max)";
                    break;
                case 121: // Reverse Damage Shield
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " ($max)";
                    break;
                case 91: // Summon Corpse
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " (max level $max)";
                    break;
                case 136: // Limit: Target
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    if ($max < 0) {
                        $max = -$max;
                        $v   = " excluded";
                    } else {
                        $v = "";
                    }
                    $print_buffer .= " (" . $dbspelltargets[$max] . "$v)";
                    break;
                case 139: // Limit: Spell
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $max          = $spell["effect_base_value$n"];
                    if ($max < 0) {
                        $max = -$max;
                        $v   = " excluded";
                    }
                    //$name = get_field_result("name", "SELECT name FROM $spells_table WHERE id=$max");
                    $name = '[PLACEHOLDER]';
                    $print_buffer .= "($name)";
                    break;
                case 140: // Limit: Min Duration
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $min          *= 6;
                    $max          *= 6;
                    if ($min != $max) {
                        $print_buffer .= " ($min sec (L$minlvl) to $max sec (L$maxlvl))";
                    } else {
                        $print_buffer .= " ($max sec)";
                    }
                    break;
                case 143: // Limit: Min Casting Time
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $min          *= 6;
                    $max          *= 6;
                    if ($min != $max) {
                        $print_buffer .= " (" . ($min / 6000) . " sec (L$minlvl) to " . ($max / 6000) . " sec (L$maxlvl))";
                    } else {
                        $print_buffer .= " (" . ($max / 6000) . " sec)";
                    }
                    break;
                case 148: // Stacking: Overwrite existing spell
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " if slot " . ($spell["effectid$n"] - 200) . " is effect '" . $dbspelleffects[$spell["effect_base_value$n"]] . "' and <" . $spell["effect_limit_value$n"];
                    break;
                case 149: // Stacking: Overwrite existing spell
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " if slot " . ($spell["effectid$n"] - 200) . " is effect '" . $dbspelleffects[$spell["effect_base_value$n"]] . "' and <" . $spell["effect_limit_value$n"];
                    break;
                case 147: // Increase Hitpoints (%)
                    $name = $dbspelleffects[$spell["effectid$n"]];
                    if ($max < 0) {
                        $name = str_replace("Increase", "Decrease", $name);
                    }
                    $print_buffer .= $name . " by " . $spell["effect_limit_value$n"] . " ($max% max)";
                    break;
                case 153: // Balance Party Health
                    $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                    $print_buffer .= " ($max% penalty)";
                    break;
                case 0: // In/Decrease hitpoints
                case 1: // Increase AC
                case 2: // Increase ATK
                case 4: // Increase STR
                case 5: // Increase DEX
                case 6: // Increase AGI
                case 7: // Increase STA
                case 8: // Increase INT
                case 9: // Increase WIS
                case 19: // Increase Faction
                case 35: // Increase Disease Counter
                case 36: // Increase Poison Counter
                case 46: // Increase Magic Fire
                case 47: // Increase Magic Cold
                case 48: // Increase Magic Poison
                case 49: // Increase Magic Disease
                case 50: // Increase Magic Resist
                case 55: // Increase Absorb Damage
                case 59: // Increase Damage Shield
                case 69: // Increase Max Hitpoints
                case 78: // Increase Absorb Magic Damage
                case 79: // Increase HP when cast
                case 92: // Increase hate
                case 97: // Increase Mana Pool
                case 111: // Increase All Resists
                case 112: // Increase Effective Casting
                case 116: // Decrease Curse Counter
                case 118: // Increase Singing Skill
                case 159: // Decrease Stats
                case 167: // Pet Power Increase
                case 192: // Increase hate
                default:
                    $name = $dbspelleffects[$spell["effectid$n"]];
                    if ($max < 0) {
                        $name = str_replace("Increase", "Decrease", $name);
                    }
                    $print_buffer .= $name;
                    if ($min != $max) {
                        $print_buffer .= " by $min to $max";
                    } else {
                        if ($max < 0) {
                            $max = -$max;
                        }
                        $print_buffer .= " by $max";
                    }
                    break;
            }
            $print_buffer .= '</ul>';
        }

        return $print_buffer;
    }
}

if (!function_exists('calc_spelleffect_value')) {
    // spell_effects.cpp int Mob::CalcSpellEffectValue_formula(int formula, int base, int max, int caster_level, int16 spell_id)
    function calc_spelleffect_value($form, $base, $max, $lvl)
    {
        // $return_buffer .= " (base=$base form=$form max=$max, lvl=$lvl)";
        $sign   = 1;
        $ubase  = abs($base);
        $result = 0;
        if (($max < $base) AND ($max != 0)) {
            $sign = -1;
        }
        switch ($form) {
            case 0:
            case 100:
                $result = $ubase;
                break;
            case 101:
                $result = $ubase + $sign * ($lvl / 2);
                break;
            case 102:
                $result = $ubase + $sign * $lvl;
                break;
            case 103:
                $result = $ubase + $sign * $lvl * 2;
                break;
            case 104:
                $result = $ubase + $sign * $lvl * 3;
                break;
            case 105:
            case 107:
                $result = $ubase + $sign * $lvl * 4;
                break;
            case 108:
                $result = floor($ubase + $sign * $lvl / 3);
                break;
            case 109:
                $result = floor($ubase + $sign * $lvl / 4);
                break;
            case 110:
                $result = floor($ubase + $lvl / 5);
                break;
            case 111:
                $result = $ubase + 5 * ($lvl - 16);
                break;
            case 112:
                $result = $ubase + 8 * ($lvl - 24);
                break;
            case 113:
                $result = $ubase + 12 * ($lvl - 34);
                break;
            case 114:
                $result = $ubase + 15 * ($lvl - 44);
                break;
            case 115:
                $result = $ubase + 15 * ($lvl - 54);
                break;
            case 116:
                $result = floor($ubase + 8 * ($lvl - 24));
                break;
            case 117:
                $result = $ubase + 11 * ($lvl - 34);
                break;
            case 118:
                $result = $ubase + 17 * ($lvl - 44);
                break;
            case 119:
                $result = floor($ubase + $lvl / 8);
                break;
            case 121:
                $result = floor($ubase + $lvl / 3);
                break;

            default:
                if ($form < 100) {
                    $result = $ubase + ($lvl * $form);
                }
        } // end switch

        if ($max != 0) {
            if ($sign == 1) {
                if ($result > $max) {
                    $result = $max;
                }
            } else {
                if ($result < $max) {
                    $result = $max;
                }
            }
        }
        if (($base < 0) && ($result > 0)) {
            $result *= -1;
        }

        return $result;
    }
}

if (!function_exists('ucRomanNumeral')) {
    function ucRomanNumeral(string $string): string
    {
        return preg_replace_callback('/\b(?=[mdclxvi])([mdclxvi]+)\b/i', function ($matches) {
            $possibleRoman = strtoupper($matches[1]);

            $valid = '/^(M{0,4})(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';

            if (preg_match($valid, $possibleRoman)) {
                return $possibleRoman;
            }

            return $matches[0];
        }, $string);
    }
}
