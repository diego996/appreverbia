<?php

namespace App\Livewire\Pages;

use App\Models\Item;
use App\Models\Membership as MembershipModel;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.reverbia-shell', ['title' => 'Membership'])]
class Membership extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Get current active membership (status = active AND end_date >= today)
        $currentMembership = MembershipModel::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->orderBy('end_date', 'desc')
            ->first();

        // Get membership items (item_property_id = 2)
        // Hide Duetto items if user doesn't have duetto_id
        $membershipItems = Item::where('active', true)
            ->where('item_property_id', 2)
            ->when(!$user->duetto_id, function ($query) {
                // Hide Duetto items if user doesn't have duetto_id
                $query->where('descrizione', 'NOT LIKE', '%duetto%');
            })
            ->orderBy('costo')
            ->get();

        return view('livewire.pages.membership', [
            'currentMembership' => $currentMembership,
            'membershipItems' => $membershipItems
        ]);
    }
}
