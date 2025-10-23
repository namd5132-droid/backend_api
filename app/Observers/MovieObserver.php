<?php
// app/Observers/MovieObserver.php
namespace App\Observers;

use App\Models\Movie;
use Illuminate\Support\Facades\File;

class MovieObserver
{
    public function created(Movie $movie)
    {
        $sourcePath = public_path('uploads/' . $movie->image); // nơi bạn upload ảnh
        $destinationPath = storage_path('app/public/movies/' . $movie->image);

        if (File::exists($sourcePath) && !File::exists($destinationPath)) {
            File::copy($sourcePath, $destinationPath);
        }
    }
}
