<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\IcalendarGenerator\Components\Event;

class Stream extends Model implements Feedable
{
    use HasFactory;

    protected $fillable = ['channel_title', 'youtube_id', 'title', 'thumbnail_url', 'scheduled_start_time'];

    protected $casts = [
        'scheduled_start_time' => 'datetime',
    ];

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('scheduled_start_time', '>', Carbon::today());
    }

    public static function getFeedItems(): Collection
    {
        return Stream::query()->upcoming()->get();
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary('Dummy summary') //TODO: use real summary
            ->updated($this->updated_at)
            ->link($this->link())
            ->author($this->channel_title); //TODO: implement
    }

    public function toCalendarItem(): Event
    {
        return Event::create()
            ->uniqueIdentifier($this->youtube_id)
            ->name($this->title)
            ->description(implode(PHP_EOL, [
                $this->title,
                $this->channel_title,
                $this->link(),
            ]))
            ->startsAt($this->scheduled_start_time)
            ->createdAt($this->created_at);
    }

    public function link(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }
}
