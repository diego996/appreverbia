<?php

namespace App\Livewire\Pages;

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.reverbia-shell', ['title' => 'Pagamenti'])]
class PaymentsHistory extends Component
{
    public function render()
    {
        $user = auth()->user();

        $payments = Payment::query()
            ->where('user_id', $user->id)
            ->with('item')
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Payment $payment) {
                $item = $payment->item;
                $itemType = $item?->item_property_id === 2 ? 'membership' : 'lezioni';
                $label = $item?->item_property_id === 2 ? 'Membership' : 'Lezioni';

                return [
                    'id' => $payment->id,
                    'amount' => number_format($payment->amount / 100, 2, ',', '.'),
                    'currency' => strtoupper($payment->currency ?? 'EUR'),
                    'status' => $payment->status,
                    'date' => ($payment->paid_at ?? $payment->created_at),
                    'reason' => $payment->meta['item_name'] ?? $item?->descrizione ?? $label,
                    'type' => $itemType,
                ];
            });

        return view('livewire.pages.payments-history', [
            'payments' => $payments,
        ]);
    }
}
