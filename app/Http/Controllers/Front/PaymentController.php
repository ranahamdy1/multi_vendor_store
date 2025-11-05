<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        return view('front.payments.create',['order'=>$order]);
    }

    public function createStripePaymentIntent(Order $order)
    {
        try {
            \Log::info('Stripe request started for order ' . $order->id);

            $stripe = new StripeClient(config('services.stripe.secret_key'));

            $amount = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // Stripe "cents"
            $amountInCents = $amount * 100;

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            \Log::error('Stripe error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function confirm(Request $request, Order $order)
    {
        $order->update([
            'status' => 'paid',
            'payment_method' => 'stripe',
        ]);

        return response()->json(['message' => 'Payment saved successfully']);
    }

    public function success(Order $order)
    {
        return view('front.payments.success', ['order' => $order]);
    }

}
