<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['platform_id' ,'slug', 'name', 'description', 'on_platform_since', 'thumbnail_url', 'country'];
}
