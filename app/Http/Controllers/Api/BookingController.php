<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Danh sách booking
    public function index()
    {
        return Booking::with('movie')->latest()->get();
    }

    // Đặt vé mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'movie_id'        => 'required|exists:movies,id',
            'showtime_id'     => 'required',
            'seats'           => 'required|array|min:1',
            'customer'        => 'required|array',
            'customer.name'   => 'required|string|max:255',
            'customer.email'  => 'required|email',
        ]);

        // ✅ Kiểm tra ghế đã được đặt trước đó chưa
        $existingSeats = Booking::where('movie_id', $data['movie_id'])
            ->where('showtime_id', $data['showtime_id'])
            ->pluck('seats')
            ->map(fn($s) => json_decode($s, true))
            ->flatten()
            ->toArray();

        $conflict = array_intersect($data['seats'], $existingSeats);
        if (!empty($conflict)) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Một số ghế đã có người đặt',
                'conflict' => $conflict
            ], 422);
        }

        $booking = Booking::create([
            'movie_id'       => $data['movie_id'],
            'showtime_id'    => $data['showtime_id'],
            'seats'          => json_encode($data['seats']), // ✅ lưu dạng JSON
            'customer_name'  => $data['customer']['name'],
            'customer_email' => $data['customer']['email'],
            'status'         => 'confirmed',
        ]);

        return response()->json([
            'message' => 'Đặt vé thành công',
            'booking' => $booking
        ], 201);
    }

    // Xem chi tiết booking
    public function show($id)
    {
        return Booking::with('movie')->findOrFail($id);
    }
    
    // Lấy danh sách trong giỏ hàng
    public function cartIndex(Request $request)
    {
        $query = Booking::with('movie')->where('status', 'pending');

        if ($request->has('email')) {
            $query->where('customer_email', $request->email);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get()
        ]);
    }

    // Thêm vào giỏ hàng
    public function cartStore(Request $request)
    {
        $data = $request->validate([
            'movie_id'        => 'required|exists:movies,id',
            'showtime_id'     => 'required|integer',
            'seats'           => 'required|array|min:1',
            'customer'        => 'required|array',
            'customer.name'   => 'required|string|max:255',
            'customer.email'  => 'required|email',
        ]);

        // ✅ Check ghế trùng trong giỏ hàng/booking
        $existingSeats = Booking::where('movie_id', $data['movie_id'])
            ->where('showtime_id', $data['showtime_id'])
            ->pluck('seats')
            ->map(fn($s) => json_decode($s, true))
            ->flatten()
            ->toArray();

        $conflict = array_intersect($data['seats'], $existingSeats);
        if (!empty($conflict)) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Một số ghế đã có trong giỏ hoặc đã đặt',
                'conflict' => $conflict
            ], 422);
        }

        $cartItem = Booking::create([
            'movie_id'       => $data['movie_id'],
            'showtime_id'    => $data['showtime_id'],
            'seats'          => json_encode($data['seats']),
            'customer_name'  => $data['customer']['name'],
            'customer_email' => $data['customer']['email'],
            'status'         => 'pending', // trong giỏ hàng
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thêm vào giỏ hàng thành công',
            'data'    => $cartItem
        ]);
    }

    // Xóa khỏi giỏ hàng
    public function destroy($id)
{
    $booking = Booking::findOrFail($id);
    $booking->delete();

    return response()->json(['message' => 'Booking deleted']);
}
public function clear($order_id)
{
    Booking::where('order_id', $order_id)->delete();
    return response()->json(['message' => 'All bookings cleared']);
}

}
