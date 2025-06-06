<?php
return [

    // create a custom itemtype select since the db itemtypes are fucking dirty
    'item_types_select' => [
        'Weapons & Armor' => [
            0  => '1H Slashing',
            1  => '2H Slashing',
            2  => 'Piercing',
            35 => '2H Piercing',
            3  => '1H Blunt',
            4  => '2H Blunt',
            45 => 'Hand to Hand',
            5  => 'Archery',
            8  => 'Shield',
            10 => 'Armor',
            27 => 'Arrow',
            7  => 'Throwing',
        ],
        'Bags & TS Containers' => [
            555 => 'Bags/Boxes',
            556 => 'Quest Bags',
            557 => 'Tradeskill Bags',
        ],
        'Consumables' => [
            14 => 'Food',
            15 => 'Drink',
            18 => 'Bandages',
            21 => 'Potion',
            38 => 'Alcohol',
        ],
        'Spells' => [
            20 => 'Scroll',
        ],
        'Augments' => [
            54 => 'Augment',
            55 => 'Augment Solvent',
            56 => 'Augment Distiller',
        ],
        'Bard Instruments' => [
            23 => 'Wind',
            24 => 'Stringed',
            25 => 'Brass',
            26 => 'Percussion',
            //50 => 'Singing', // Not used?
            //51 => 'All Instruments', // Not used?
        ],
        'Crafting & Tradeskills' => [
            42 => 'Poison',
            12 => 'Lockpicks',
            17 => 'Combinable',
            29 => 'Jewelry',
            36 => 'Fishing Pole',
            37 => 'Fishing Bait',
            60 => 'Cultural Armor Recipe Book',
            61 => 'Cultural Weapon Recipe Book',
            67 => 'Container',
        ],
        'Mounts & Illusions' => [
            68 => 'Mount',
            69 => 'Illusion',
        ],
        'Misc' => [
            63 => 'Alternate Currency',
            11 => 'Misc',
            53 => 'Armor Dye',
            31 => 'Book',
            32 => 'Note',
            33 => 'Keys',
            16 => 'Light',
            52 => 'Charm',
            30 => 'Skull',
            34 => 'Coin',
            40 => 'Compass',
            //9  => 'Spell', // Not used?
            //41 => 'Metal Key', // Not used?
            //58 => 'Guild Banner Kit',
            //59 => 'Guild Banner Modify Token',
        ],
    ],

    'item_stats_select' => [
        'hp' => 'HP',
        'mana' => 'Mana',
        'endur' => 'Endurance',
        'ac' => 'AC',
        'haste' => 'Haste',
        'aagi' => 'AGI',
        'acha' => 'CHA',
        'adex' => 'DEX',
        'aint' => 'INT',
        'asta' => 'STA',
        'astr' => 'STR',
        'awis' => 'WIS',
        'heroic_agi' => 'Heroic AGI',
        'heroic_cha' => 'Heroic CHA',
        'heroic_dex' => 'Heroic DEX',
        'heroic_int' => 'Heroic INT',
        'heroic_sta' => 'Heroic STA',
        'heroic_str' => 'Heroic STR',
        'heroic_wis' => 'Heroic WIS',
        'damage' => 'Damage',
        'attack' => 'Attack',
        'delay' => 'Delay',
        'ratio' => 'Ratio',
        'regen' => 'HP Regen',
        'manaregen' => 'Mana Regen',
        'enduranceregen' => 'End Regen',
        'spellshield' => 'Spell Shield',
        'combateffects' => 'Combat Effects',
        'shielding' => 'Shielding',
        'damageshield' => 'Damage Shield',
        'dotshielding' => 'DoT Shielding',
        'dsmitigation' => 'Damage Shield Mitigation',
        'avoidance' => 'Avoidance',
        'accuracy' => 'Accuracy',
        'stunresist' => 'Stun Resist',
        'strikethrough' => 'Strikethrough',
        'spelldmg' => 'Spell Damage',
    ],
];
