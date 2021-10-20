<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveChannelTitleFromStreamsTable extends Migration
{

    public function up(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->dropColumn('channel_title');
        });
    }
}
