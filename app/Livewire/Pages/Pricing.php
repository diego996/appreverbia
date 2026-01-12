<?php

namespace App\Livewire\Pages;

use App\Models\Item;
use App\Models\Membership;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.reverbia-shell', ['title' => 'Acquista Crediti'])]
class Pricing extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Get active token items (item_property_id = 1), sorted by cost
        // Hide Duetto items if user doesn't have duetto_id
        $tokenItems = Item::where('active', true)
            ->where('item_property_id', 1)
            ->when(!$user->duetto_id, function ($query) {
                // Hide Duetto items if user doesn't have duetto_id
                $query->where('descrizione', 'NOT LIKE', '%duetto%');
            })
            ->orderBy('token')
            ->orderBy('costo')
            ->get();

        // Group items by token amount for duetto/singolo selection
        $groupedItems = $tokenItems->groupBy('token');

        return view('livewire.pages.pricing', [
            'groupedItems' => $groupedItems,
            'hasActiveMembership' => Membership::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('end_date', '>=', now()->toDateString())
                ->exists(),
        ]);
    }
}
