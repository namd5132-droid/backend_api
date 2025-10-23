<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Booking;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total' => 'required|numeric',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
        ]);

        // 1. Tạo order
        $order = Order::create([
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'total'          => $request->total,
            'status'         => 'paid',
        ]);

        // 2. Ghi order_items từ bookings tạm
        foreach ($request->items as $item) {
            OrderItem::create([
    'order_id'    => $order->id,
    'movie_id'    => $item['movie_id'],
    'showtime_id' => $item['showtime_id'],
    'seats'       => json_encode($item['seats']),
]);
        }

        // 3. Xoá các booking tạm (frontend phải gửi id booking)
        $ids = collect($request->items)->pluck('id');
        if ($ids->count() > 0) {
            Booking::whereIn('id', $ids)->delete();
        }

        return response()->json([
            'message'  => '✅ Thanh toán thành công!',
            'order_id' => $order->id,
        ], 201);
    }
}
