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
        
        // Get current active membership (any active membership record)
        $currentMembership = MembershipModel::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('end_date', 'desc')
            ->first();
        
        // Get membership items (item_property_id = 2)
        $membershipItems = Item::where('active', true)
            ->where('item_property_id', 2)
            ->orderBy('costo')
            ->get();
        
        return view('livewire.pages.membership', [
            'currentMembership' => $currentMembership,
            'membershipItems' => $membershipItems
        ]);
    }
}
