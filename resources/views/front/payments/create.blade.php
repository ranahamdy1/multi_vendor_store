<x-front-layout title="Order Payment">

    <div class="account-login section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="card shadow p-4 rounded-3">
                        <h4 class="text-center mb-4">💳 Enter Your Card Details</h4>

                        <div id="card-element" class="form-control mb-3" style="height:auto;"></div>

                        <button id="pay-button" class="btn btn-primary w-100 mt-2">Pay $10</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f9fafb;
        }

        #card-element {
            background: #fff;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .card {
            border: none;
        }

        #pay-button {
            font-size: 17px;
            padding: 10px;
            border-radius: 8px;
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config("services.stripe.public_key") }}');
        const elements = stripe.elements();

        // 👇 تحسين شكل عنصر البطاقة
        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": { color: "#aab7c4" }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        const cardElement = elements.create('card', { style });
        cardElement.mount('#card-element');

        document.getElementById('pay-button').addEventListener('click', async () => {
            const response = await fetch('/orders/{{ $order->id }}/stripe/payment-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            const { error } = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: { card: cardElement }
            });

            if (error) {
                alert('❌ Payment failed: ' + error.message);
            } else {
                alert('✅ Payment successful!');
                window.location.href = '/orders/{{ $order->id }}/success';
            }
        });
    </script>

</x-front-layout>
