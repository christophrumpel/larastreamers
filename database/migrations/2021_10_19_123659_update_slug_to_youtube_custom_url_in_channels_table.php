<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSlugToYoutubeCustomUrlInChannelsTable extends Migration
{

    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->renameColumn('slug', 'youtube_custom_url');
        });
    }
}
