<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Responses;

use App\Actions\Responses\ResponseRedirectAction;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

class ResponseRedirectActionTest extends TestCase
{
    public function test_response_redirect_action_route_success_call(): void
    {
        $route = 'bitcoin.index';

        $response = (new ResponseRedirectAction($route))
            ->execute();

        $this->assertEquals(get_class($response), 'Illuminate\Http\RedirectResponse');
        $this->assertStringContainsString(route($route), $response->content());
    }

    public function test_response_redirect_action_to_success_call(): void
    {
        $route = 'bitcoin.index';

        $response = (new ResponseRedirectAction())
            ->execute($route);

        $this->assertEquals(RedirectResponse::class, $response::class);
        $this->assertStringContainsString(route($route), $response->content());
    }
}
