<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $users = User::where('store_id', $order->store_id)->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new OrderCreatedNotification($order));
        } else {
            if ($order->user) {
                $order->user->notify(new OrderCreatedNotification($order));
            }
        }
    }
}
