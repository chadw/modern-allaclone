<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SpellLink extends Component
{
    public $spellId;
    public $spellName;
    public $spellIcon;
    public string $spellClass;
    public bool $effectsOnly;

    public function __construct(
        int $spellId,
        string|null $spellName = null,
        $spellIcon = null,
        string $spellClass = '',
        bool $effectsOnly = false
    ) {
        $this->spellId = $spellId;
        $this->spellName = $spellName;
        $this->spellIcon = $spellIcon;
        $this->spellClass = $spellClass;
        $this->effectsOnly = $effectsOnly;
    }

    public function render(): View|Closure|string
    {
        return view('components.spell-link');
    }
}
