<?php

declare(strict_types=1);

namespace App\Actions\Responses;

class ResponseJsonAction
{
    public function __construct(private array $json)
    {
    }

    public function execute(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->json);
    }
}
