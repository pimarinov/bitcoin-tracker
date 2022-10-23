<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Responses;

use App\Actions\Responses\ResponseViewAction;
use Illuminate\View\View;
use Tests\TestCase;

class ResponseViewActionTest extends TestCase
{
    public function test_response_view_action_success_call(): void
    {
        $view = 'bitcoin';
        $testData = [ 'testing' => 456 ];

        $response = (new ResponseViewAction($view, $testData))
            ->execute();

        $this->assertEquals(View::class, $response::class);
        $this->assertEquals($testData['testing'], $response->gatherData()['testing']);
    }
}
