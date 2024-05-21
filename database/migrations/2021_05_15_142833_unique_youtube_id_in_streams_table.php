<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('streams', static function(Blueprint $table) {
            $table->unique(['youtube_id']);
        });
    }

    public function down(): void
    {
        Schema::table('streams', static function(Blueprint $table) {
            $table->dropUnique(['youtube_id']);
        });
    }
};
