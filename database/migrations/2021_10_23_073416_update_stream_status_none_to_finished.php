<?php

use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Database\Migrations\Migration;

class UpdateStreamStatusNoneToFinished extends Migration
{
    public function up()
    {
        Stream::where('status', 'none')->update(['status' => StreamData::STATUS_FINISHED]);
    }
}
