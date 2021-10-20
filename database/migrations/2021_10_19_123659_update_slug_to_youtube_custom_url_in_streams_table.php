<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSlugToYoutubeCustomUrlInStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('youtube_custom_url_in_streams', function (Blueprint $table) {
            $table->renameColumn('slug', 'youtube_custom_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('youtube_custom_url_in_streams', function (Blueprint $table) {
            $table->renameColumn('youtube_custom_url', 'slug');
        });
    }
}
