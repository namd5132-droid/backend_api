<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Movie;
use App\Observers\MovieObserver;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tự động cập nhật APP_URL theo port bạn đang chạy (8000, 8001, v.v.)
        if (request()->getHost()) {
            $currentUrl = request()->getSchemeAndHttpHost(); // ví dụ: http://127.0.0.1:8001
            config(['app.url' => $currentUrl]);
            URL::forceRootUrl($currentUrl);
        }

        // Gắn observer cho model Movie
        Movie::observe(MovieObserver::class);
    }
}
