<?php

use App\Models\Channel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('twitch_channel_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Channel::class);
            $table->string('subscription_event');
            $table->timestamps();
        });
    }
};
