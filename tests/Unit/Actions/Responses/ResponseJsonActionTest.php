<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Responses;

use App\Actions\Responses\ResponseJsonAction;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ResponseJsonActionTest extends TestCase
{
    public function test_response_json_action_success_call(): void
    {
        $testData = [ 123 => 456 ];

        $response = (new ResponseJsonAction($testData))
            ->execute();

        $this->assertEquals(JsonResponse::class, $response::class);
        $this->assertJson(json_encode($testData));
    }
}
