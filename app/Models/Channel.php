<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vinkla\Hashids\Facades\Hashids;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'platform_id', 'language_code', 'url', 'name', 'description', 'on_platform_since', 'thumbnail_url', 'country', 'auto_import'];

    protected $appends = ['hashid'];

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class);
    }

    public function scopeWithApprovedAndFinishedStreams(Builder $query): Builder
    {
        return $query->whereHas('streams', function(Builder $query) {
            /** @var Builder<Stream> $query */
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

    public function hashid(): Attribute
    {
        return Attribute::get(fn() => Hashids::encode($this->id));
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
