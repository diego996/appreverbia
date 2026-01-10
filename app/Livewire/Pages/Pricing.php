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
        // Get active token items (item_property_id = 1), sorted by cost
        $tokenItems = Item::where('active', true)
            ->where('item_property_id', 1)
            ->orderBy('token')
            ->orderBy('costo')
            ->get();

        // Group items by token amount for duetto/singolo selection
        $groupedItems = $tokenItems->groupBy('token');

        return view('livewire.pages.pricing', [
            'groupedItems' => $groupedItems
        ]);
    }
}
