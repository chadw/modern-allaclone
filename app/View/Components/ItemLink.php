<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemLink extends Component
{
    public $itemId;
    public $itemName;
    public $itemIcon;

    public function __construct($itemId, $itemName, $itemIcon = null)
    {
        $this->itemId = $itemId;
        $this->itemName = $itemName;
        $this->itemIcon = $itemIcon;
    }

    public function render(): View|Closure|string
    {
        return view('components.item-link');
    }
}
