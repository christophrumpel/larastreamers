<?php

namespace App\Http\Requests;

use App\Rules\YouTubeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitStreamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'youtube_id' => ['required', Rule::unique('streams', 'youtube_id'), new YouTubeRule()],
            'email' => ['required', 'email']
        ];
    }
}
