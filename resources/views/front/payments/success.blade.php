<x-front-layout title="Payment Success">
    <div class="text-center mt-5">
        <h2>✅ Payment Successful!</h2>
        <p>Thank you for your order #{{ $order->id }}.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
    </div>
</x-front-layout>
