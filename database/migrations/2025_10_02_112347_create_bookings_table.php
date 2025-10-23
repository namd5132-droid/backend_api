<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Liên kết đơn hàng (orders)
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Liên kết phim
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');

            // Liên kết suất chiếu (nếu có bảng showtimes thì đổi thành foreignId)
            $table->unsignedBigInteger('showtime_id');

            // Ghế
            $table->string('seats'); // JSON hoặc chuỗi "A1,A2,A3"

            // Thông tin khách
            $table->string('customer_name');
            $table->string('customer_email');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
