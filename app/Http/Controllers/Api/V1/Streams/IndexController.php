<?php

namespace App\Http\Controllers\Api\V1\Streams;

use App\Actions\PrepareStreams;
use App\Http\Controllers\Controller;
use App\Http\Resources\StreamResource;
use App\Models\Stream;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            data: StreamResource::collection(
                resource: Stream::query()->approved()->latest()->get(),
            ),
            status: Response::HTTP_OK,
            headers: [
                'Content-Type' => 'application/vnd.api+json',
            ],
        );
    }
}
