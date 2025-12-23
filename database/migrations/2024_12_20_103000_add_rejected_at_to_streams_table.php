<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('streams', function(Blueprint $table) {
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->index('rejected_at');
        });
    }

    public function down(): void
    {
        Schema::table('streams', function(Blueprint $table) {
            $table->dropIndex(['rejected_at']);
            $table->dropColumn('rejected_at');
        });
    }
};
