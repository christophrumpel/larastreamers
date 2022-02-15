<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('platform')->default('youtube');
            $table->string('platform_id');
            $table->string('slug');
            $table->string('name');
            $table->text('description');
            $table->string('thumbnail_url');
            $table->string('country');
            $table->dateTime('on_platform_since');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
