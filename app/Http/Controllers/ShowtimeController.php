<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;

class ShowtimeController extends Controller
{
    // Lấy toàn bộ suất chiếu
   public function index()
{
    $showtimes = \App\Models\Showtime::with('movie')->orderBy('time')->get()->map(function($s) {
        return [
            'id' => $s->id,
            'movie_id' => $s->movie_id,
            'time' => $s->time, // thời gian chuẩn định dạng ISO
            'room' => $s->room,
            'movie_title' => optional($s->movie)->title,
        ];
    });

    return response()->json($showtimes);
}


    // Tạo suất chiếu mới (nếu cần)
   public function store(Request $request)
    {
        $validated = $request->validate([
            'room' => 'required|string|max:255',
            'time' => 'required|string|max:255',
        ]);

        $showtime = Showtime::create($validated);
        return response()->json($showtime, 201);
    }

    public function update(Request $request, $id)
{
    $showtime = Showtime::findOrFail($id);
    $validated = $request->validate([
        'room' => 'sometimes|string|max:255',
        'time' => 'sometimes|string|max:255',
    ]);
    $showtime->update($validated);
    return response()->json(['message' => 'Đã cập nhật suất chiếu', 'data' => $showtime]);
}

public function destroy($id)
{
    $showtime = Showtime::findOrFail($id);
    $showtime->delete();
    return response()->json(['message' => 'Đã xóa suất chiếu']);
}

}
