<?php

use App\Models\Stream;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalFieldsToStreamsTable extends Migration
{
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable();
            $table->string('submitted_by_email')->nullable();
        });

        Stream::each(function(Stream $stream) {
             $stream->update(['approved_at' => now()]);
        });
    }
}
