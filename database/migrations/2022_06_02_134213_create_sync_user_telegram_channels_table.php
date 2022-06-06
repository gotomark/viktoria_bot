<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sync_user_telegram_channels', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('telegram_channel_id');
            $table->integer('user_telegram_channel_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sync_user_telegram_channels');
    }
};
