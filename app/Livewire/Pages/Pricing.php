<?php

namespace App\Livewire\Pages;

use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.reverbia-shell', ['title' => 'Acquista Crediti'])]
class Pricing extends Component
{
    public function render()
    {
        // Get active items, sorted by cost
        $items = Item::where('active', true)->orderBy('costo')->get();

        return view('livewire.pages.pricing', [
            'items' => $items
        ]);
    }
}
