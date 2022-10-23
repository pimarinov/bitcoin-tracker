<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class BitcoinControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index_method_success()
    {
        $response = $this->get(route('bitcoin.index'));

        $response->assertStatus(200);
    }

    public function test_snapshots_method_success()
    {
        $response = $this->get(route('bitcoin.snapshots'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_subscribe_method_fail()
    {
        $response = $this->post(route('bitcoin.subscribe-for-price-reach'));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHasErrors(['price']);
    }

    public function test_subscribe_method_success()
    {
        $response = $this->post(route('bitcoin.subscribe-for-price-reach'), [
            'email' => 'johndowe@tes.com',
            'price' => 123,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas('status');
    }
}
