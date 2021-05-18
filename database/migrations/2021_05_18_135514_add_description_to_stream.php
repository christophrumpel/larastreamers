<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToStream extends Migration
{
    public function up(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->text('description')->after('title');
        });
    }
}
