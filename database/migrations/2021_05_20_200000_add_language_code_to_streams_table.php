<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageCodeToStreamsTable extends Migration
{
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->string('language_code')->default('en');
        });
    }
}
