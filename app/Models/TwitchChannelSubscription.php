<?php

namespace App\Models;

use App\Enums\TwitchEventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwitchChannelSubscription extends Model
{

    use HasFactory;

    protected $fillable = ['channel_id', 'subscription_event', 'verified'];

    protected $casts = [
        'subscription_event' => TwitchEventType::class,
        'verified' => 'boolean'
    ];
}
