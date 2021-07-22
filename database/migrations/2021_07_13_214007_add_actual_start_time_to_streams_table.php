<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActualStartTimeToStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streams', function(Blueprint $table) {
            $table->timestamp('actual_start_time')->nullable()->after('scheduled_start_time');
            $table->timestamp('actual_end_time')->nullable()->after('actual_start_time');
        });
    }
}
