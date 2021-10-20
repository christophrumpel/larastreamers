<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'platform' => 'youtube',
            'platform_id' => $this->faker->word,
            'language_code' => 'en',
            'youtube_custom_url' => $this->faker->slug,
            'name' => collect(['Dr Disrespect', 'Lulu', 'Harris Heller', 'itzTimmy'])->random(),
            'description' => $this->faker->text,
            'thumbnail_url' => collect([
                'https://yt3.ggpht.com/JWtY_fLVUhRudr1y1TqOH60PGjmUjAjW3vKHIBrvge4j_Czh1XEkLekqEACaJEJdWkG00HeOsg=s176-c-k-c0x00ffffff-no-rj',
                'https://yt3.ggpht.com/ytc/AKedOLRLFKZcTc_hXy75Y829rvkXzIAGxKftFRqt222Z7i4=s176-c-k-c0x00ffffff-no-rj',
            ])->random(),
            'country' => $this->faker->countryCode,
            'twitter_handle' => $this->faker->word,
            'on_platform_since' => Carbon::now(),
        ];
    }

    public function autoImportEnabled(): self
    {
        return $this->state(function() {
            return ['auto_import' => true];
        });
    }
}
