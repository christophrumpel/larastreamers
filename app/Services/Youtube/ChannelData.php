<?php

namespace App\Services\Youtube;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class ChannelData extends DataTransferObject
{
    public string $slug;
    public string $name;
    public string $description;
    public Carbon $since;
    public string $thumbnailUrl;
    public string $country;
}
