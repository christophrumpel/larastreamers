<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->renameColumn('youtube_id', 'stream_id');
        });
    }
};
