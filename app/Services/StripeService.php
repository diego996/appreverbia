<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;
use App\Models\Membership;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Item $item, string $successUrl, string $cancelUrl, ?string $customerEmail = null): Session
    {
        $lineItem = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item->descrizione,
                ],
                // Stripe expects amount in cents
                'unit_amount' => (int) ($item->costo * 100),
            ],
            'quantity' => 1,
        ];

        $payload = [
            'payment_method_types' => ['card'],
            'line_items' => [$lineItem],
            'mode' => 'payment',
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'item_id' => $item->id,
                'type' => 'item_purchase',
                'action_by' => auth()->id(), // Just in case, though we rely on webhook mostly or session retrieval
            ],
        ];

        if ($customerEmail) {
            $payload['customer_email'] = $customerEmail;
        }

        return Session::create($payload);
    }

    public function retrieveCheckoutSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }
}
