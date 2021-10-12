<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['platform_id', 'language_code', 'slug', 'name', 'description', 'on_platform_since', 'thumbnail_url', 'country'];

    public function scopeAutoImportEnabled(Builder $query): Builder
    {
        return $query->where('auto_import', true);
    }


}
