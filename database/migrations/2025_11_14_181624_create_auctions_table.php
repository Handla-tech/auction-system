<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_auctions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->decimal('current_bid', 10, 2)->default(0);
            $table->foreignId('winner_id')->nullable()->constrained('users');
            $table->enum('status', ['active', 'ended', 'cancelled'])->default('active'); // تأكد من القيم المسموحة
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auctions');
    }
}