<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('streams', function(Blueprint $table) {
            $table->timestamp('upcoming_tweeted_at')->nullable()->after('tweeted_at');
        });
    }
};
