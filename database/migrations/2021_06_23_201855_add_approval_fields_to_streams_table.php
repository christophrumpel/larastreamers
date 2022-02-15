<?php

use App\Models\Stream;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('language_code');
            $table->string('submitted_by_email')->nullable()->after('language_code');
        });

        Stream::each(function(Stream $stream) {
             $stream->update(['approved_at' => now()]);
        });
    }
};
