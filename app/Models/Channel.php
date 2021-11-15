<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vinkla\Hashids\Facades\Hashids;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['platform_id', 'language_code', 'youtube_custom_url', 'name', 'description', 'on_platform_since', 'thumbnail_url', 'country'];

    protected $appends = ['hashid'];

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class);
    }

    public function scopeWithApprovedAndFinishedStreams(Builder $query): Builder
    {
        return $query->whereHas('streams', function(Builder $query) {
            /** @var Builder<Stream> $query*/
            $query->approved()
                    ->finished();
        });
    }

    public function approvedFinishedStreams(): HasMany
    {
        return $this->hasMany(Stream::class)
            ->approved()
            ->finished();
    }

    public function getHashidAttribute(): string
    {
        return Hashids::encode((int) $this->attributes['id']);
    }

    public function scopeAutoImportEnabled(Builder $query): Builder
    {
        return $query->where('auto_import', true);
    }

    public function url(): string
    {
        return "https://www.youtube.com/channel/{$this->platform_id}";
    }
}
