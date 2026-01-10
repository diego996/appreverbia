<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function checkout(Item $item)
    {
        try {
            $user = Auth::user();
            
            // Create Checkout Session
            $session = $this->stripeService->createCheckoutSession(
                $item,
                route('payment.success'),
                route('payment.cancel'),
                $user->email
            );

            // Create Pending Payment Record
            // We use provider_ref to store the Stripe Session ID for now, 
            // verifying it later via webhook or success page.
            Payment::create([
                'user_id' => $user->id,
                'item_id' => $item->id, // Linking directly to item
                'amount' => $item->costo * 100, // Storing in cents
                'currency' => 'EUR',
                'status' => 'pending',
                'provider_ref' => $session->id,
                'meta' => [
                    'session_id' => $session->id,
                    'item_name' => $item->descrizione,
                ]
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Error initiating checkout: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        // In a real app, we might retrieve the session from Stripe here to confirm status immediately
        // allowing for instant UI update even if webhook is slightly delayed.
        // For now, we display the success page.
        
        return view('payment.success', ['session_id' => $sessionId]);
    }

    public function cancel()
    {
        return view('payment.cancel');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response('Invalid payload', 400);
        } catch(SignatureVerificationException $e) {
            // Invalid signature
            return response('Invalid signature', 400);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $this->handleCheckoutSessionCompleted($session);
        }

        return response('Received', 200);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        $payment = Payment::where('provider_ref', $session->id)->first();

        if ($payment && $payment->status !== 'paid') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'meta' => array_merge($payment->meta ?? [], ['stripe_payment_intent' => $session->payment_intent])
            ]);

            // Found item via payment
            $item = $payment->item; // Assuming relation exists and is loaded or accessible
            
            if ($item) {
                // Handle Membership (Property ID = 2)
                if ($item->item_property_id == 2) {
                     $membership = \App\Models\Membership::firstOrNew(['user_id' => $payment->user_id]);
                     
                     // Determine start date for calculation
                     $startDate = ($membership->exists && $membership->end_date && $membership->end_date->isFuture()) 
                         ? $membership->end_date 
                         : now();

                     // Add years based on token value
                     $newEndDate = $startDate->copy()->addYears($item->token);
                     
                     $membership->fill([
                         'start_date' => $membership->start_date ?? now(), // Keep original start date if exists, else now
                         'end_date' => $newEndDate,
                         'payment_id' => $payment->id, // Link latest payment
                         'status' => 'active', // You might want a status field
                     ])->save();
                     
                     Log::info("Membership updated for User {$payment->user_id}. New end date: {$newEndDate->toDateString()}");
                }

                // Credit the wallet (Standard Logic)
                // Check if wallet entry already exists to avoid duplicates (idempotency)
                // We use the payment as the unique model reference
                $exists = Wallet::where('model_type', Payment::class)
                    ->where('model_id', $payment->id)
                    ->exists();

                if (!$exists) {
                    Wallet::create([
                        'user_id' => $payment->user_id,
                        'model_type' => Payment::class,
                        'model_id' => $payment->id,
                        'token_delta' => $item->token,
                        'token' => $item->token, // Initial balance for this packet
                        'reason' => 'purchase',
                        'provider' => 'stripe',
                        'expires_at' => $item->validity_months ? now()->addMonths($item->validity_months) : null,
                        'meta' => ['description' => $item->descrizione]
                    ]);
                    
                    Log::info("Wallet credited for User {$payment->user_id} with {$item->token} tokens.");
                }
            }
        }
    }
}
