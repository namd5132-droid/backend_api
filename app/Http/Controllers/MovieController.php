<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MovieController extends Controller
{
    /**
     * Hiển thị danh sách phim.
     */
    public function index()
    {
        $movies = Movie::all()->map(function ($movie) {
            $movie->image = $movie->image 
                ? asset('uploads/movies/' . $movie->image) 
                : asset('uploads/default.jpg');
            return $movie;
        });

        return response()->json($movies);
    }

    /**
     * Tạo phim mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'price' => 'required|numeric',
            'new' => 'boolean',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên gốc
            $file->move(public_path('uploads/movies'), $fileName);
            $validated['image'] = $fileName;
        }

        Movie::create($validated);

        return response()->json(['message' => 'Phim đã được thêm thành công!']);
    }

    /**
     * Cập nhật thông tin phim.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'price' => 'required|numeric',
            'new' => 'boolean',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
        ]);

        $movie = Movie::findOrFail($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên
            $file->move(public_path('uploads/movies'), $fileName);
            $validated['image'] = $fileName;
        }

        $movie->update($validated);

        return response()->json(['message' => 'Phim đã được cập nhật thành công!']);
    }

    /**
     * Xóa phim.
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Phim không tồn tại'], 404);
        }

        $movie->delete();

        return response()->json(['message' => 'Xóa phim thành công']);
    }
    /**
 * Hiển thị chi tiết phim.
 */
public function show($id)
{
    $movie = Movie::find($id);

    if (!$movie) {
        return response()->json(['message' => 'Phim không tồn tại'], 404);
    }

    // Ghép link ảnh đầy đủ
    $movie->image = $movie->image 
        ? asset('uploads/movies/' . $movie->image) 
        : asset('uploads/default.jpg');

    return response()->json($movie);
}

}
