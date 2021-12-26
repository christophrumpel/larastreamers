<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateStreamStatusNoneToFinished extends Migration
{
    public function up(): void
    {
        DB::table('streams')
            ->where('status', 'none')
            ->update([
                'status' => 'finished',
            ]);
    }
}
