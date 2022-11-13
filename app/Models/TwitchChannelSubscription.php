<?php

namespace App\Models;

use App\Enums\TwitchEventSubscription;
use Illuminate\Database\Eloquent\Model;

class TwitchChannelSubscription extends Model
{

    protected $fillable = ['channel_id', 'subscription_event'];

    protected $casts = [
        'subscription_event' => TwitchEventSubscription::class,
    ];
}
