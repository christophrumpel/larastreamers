<?php

namespace App\Models;

use App\Services\YouTube\StreamData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\IcalendarGenerator\Components\Event;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @property \Illuminate\Support\Carbon $scheduled_start_time
 * @property \Illuminate\Support\Carbon|null $actual_start_time
 */
class Stream extends Model implements Feedable
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'youtube_id',
        'title',
        'description',
        'thumbnail_url',
        'scheduled_start_time',
        'actual_start_time',
        'actual_end_time',
        'hidden_at',
        'status',
        'tweeted_at',
        'upcoming_tweeted_at',
        'language_code',
        'submitted_by_email',
        'approved_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'scheduled_start_time' => 'datetime',
            'actual_start_time' => 'datetime',
            'actual_end_time' => 'datetime',
            'hidden_at' => 'datetime',
            'tweeted_at' => 'datetime',
            'upcoming_tweeted_at' => 'datetime',
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopeFromLastWeek(Builder $query): Builder
    {
        return $query->whereBetween(
            DB::raw('COALESCE(actual_start_time, scheduled_start_time)'),
            [
                Carbon::today()->subWeek()->startOfWeek(),
                Carbon::today()->subWeek()->endOfWeek()->endOfDay(),
            ]
        );
    }

    public static function getFeedItems(): Collection
    {
        return static::query()->approved()->upcoming()->get();
    }

    public function tweetStreamIsLiveWasSend(): bool
    {
        return ! is_null($this->tweeted_at);
    }

    public function tweetStreamIsUpcomingWasSend(): bool
    {
        return ! is_null($this->upcoming_tweeted_at);
    }

    public function markAsTweeted(): self
    {
        $this->update(['tweeted_at' => now()]);

        return $this;
    }

    public function isLive(): bool
    {
        return $this->status === StreamData::STATUS_LIVE;
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', StreamData::STATUS_LIVE);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', StreamData::STATUS_UPCOMING)
            ->where('scheduled_start_time', '>', now());
    }

    public function scopeUpcomingOrLive(Builder $query): Builder
    {
        return $query->where('status', StreamData::STATUS_LIVE)
            ->orWhere(function(Builder $query) {
                /* @phpstan-ignore-next-line */
                return $query->upcoming();
            });
    }

    public static function getNextUpcomingOrLive(): ?Stream
    {
        return self::query()
            ->with('channel:id,name')
            ->approved()
            ->upcomingOrLive()
            ->fromOldestToLatest()
            ->first();
    }

    public function scopeLiveOrFinished(Builder $query): Builder
    {
        return $query->whereIn('status', [
            StreamData::STATUS_LIVE,
            StreamData::STATUS_FINISHED,
        ]);
    }

    public function scopeFromLatestToOldest(Builder $query): Builder
    {
        return $query->orderBy('actual_start_time', 'DESC')
            ->orderBy('scheduled_start_time', 'DESC');
    }

    public function scopeFromOldestToLatest(Builder $query): Builder
    {
        return $query->orderByRaw('COALESCE(actual_start_time, scheduled_start_time) ASC');
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('status', StreamData::STATUS_FINISHED);
    }

    public function scopeNotOlderThanAYear(Builder $query): Builder
    {
        return $query->where(
            'scheduled_start_time',
            '>=',
            now()->subYear()->startOfYear()
        );
    }

    public function scopeWithinUpcomingTweetRange(Builder $query): Builder
    {
        return $query->where('scheduled_start_time', '<=', now()->addMinutes(5));
    }

    public function scopeScheduledTimeNotPassed(Builder $query): Builder
    {
        return $query->where('scheduled_start_time', '>=', now());
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function(Builder $builder, ?string $search) {
            // Escape special LIKE characters to prevent wildcard abuse
            $search = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);
            
            $builder->where(function(Builder $query) use ($search) {
                $query
                    ->where('title', 'like', "%$search%")
                    ->orWhereHas('channel', function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            });
        });
    }

    public function scopeByStreamer(Builder $query, ?string $streamerHashid): Builder
    {
        if (! $streamerHashid) {
            return $query;
        }

        $channelId = Hashids::decode($streamerHashid)[0] ?? null;

        return $query->when(
            $channelId,
            fn (Builder $builder, ?string $streamerHashid) => $builder->where(fn (Builder $query) => $query->where('channel_id', $channelId))
        );
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id((string) $this->id)
            ->title($this->title)
            ->summary((string) $this->description)
            ->updated($this->updated_at ?? now())
            ->link($this->url())
            ->authorName($this->channel()->first(['id', 'name'])?->name ?? '');
    }

    public function url(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    public function toCalendarItem(): Event
    {
        return Event::create()
            ->uniqueIdentifier($this->youtube_id)
            ->name($this->title)
            ->url($this->url())
            ->description(implode(PHP_EOL, [
                $this->title,
                $this->channel?->name,
                $this->url(),
                Str::of((string) $this->description)
                    ->whenNotEmpty(fn (Stringable $description) => $description->prepend(str_repeat('-', 15).PHP_EOL))
                    ->remove("\r"),
            ]))
            ->startsAt($this->scheduled_start_time)
            ->endsAt($this->scheduled_start_time->clone()->addHour())
            ->createdAt($this->created_at ?? now());
    }

    public function language(): HasOne
    {
        return $this->hasOne(Language::class, 'code', 'language_code');
    }

    public function toWebcalLink(): string
    {
        /** @var string[] $url */
        $url = parse_url(route('calendar.ics.stream', $this));

        return "webcal://{$url['host']}{$url['path']}";
    }

    public function approveUrl(): string
    {
        return URL::temporarySignedRoute(
            'stream.approve',
            now()->addMonth(),
            ['stream' => $this],
        );
    }

    public function rejectUrl(): string
    {
        return URL::temporarySignedRoute(
            'stream.reject',
            now()->addMonth(),
            ['stream' => $this],
        );
    }

    public function isApproved(): bool
    {
        return ! is_null($this->approved_at);
    }

    public function isRejected(): bool
    {
        return ! is_null($this->rejected_at);
    }

    public function duration(): Attribute
    {
        return Attribute::get(function(): ?string {
            if (is_null($this->actual_end_time)) {
                return null;
            }

            $startTime = $this->actual_start_time ?? $this->scheduled_start_time;

            return (int) $startTime->diffInHours($this->actual_end_time).'h '.$startTime->diff($this->actual_end_time)->format('%i').'m';
        });
    }

    public function startForHumans(): Attribute
    {
        return Attribute::get(function(): string {
            if ($this->actual_start_time) {
                return "Started {$this->actual_start_time->diffForHumans()}";
            }

            if ($this->scheduled_start_time->isPast()) {
                return "Started {$this->scheduled_start_time->diffForHumans()}";
            }

            return "Starts {$this->scheduled_start_time->diffForHumans()}";
        });
    }

    public function startForRobots(): Attribute
    {
        return Attribute::get(fn () => $this->actual_start_time?->toIso8601String() ?? $this->scheduled_start_time->toIso8601String());
    }
}
