<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelIdToStreams extends Migration
{
    public function up(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->string('channel_id')->after('youtube_id')->nullable();
        });
    }

}
