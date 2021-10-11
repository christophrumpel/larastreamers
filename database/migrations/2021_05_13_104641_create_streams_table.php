<?php

use App\Services\YouTube\StreamData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
    public function up(): void
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('youtube_id');
            $table->string('channel_title');
            $table->string('title');
            $table->string('thumbnail_url');
            $table->dateTime('scheduled_start_time');
            $table->string('status')->default(StreamData::STATUS_UPCOMING);
            $table->timestamps();
        });
    }
}
