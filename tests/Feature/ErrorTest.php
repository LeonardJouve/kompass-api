<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ErrorTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_error_index()
    {
        $response = $this->get('/api/errors');

        $this->assertIsArray($response->getData());
        $response->assertStatus(200);
    }

    /**
     *
     * @return void
     */
    public function test_error_store()
    {
        $response = $this->post('api/errors', [
            'data' => [
                'value' => 'value',
            ],
            'url' => 'url',
            'status' => 400,
        ]);

        $this->assertIsObject($response->getData());
        $this->assertEquals(true, $response->getData()->data);
        $response->assertStatus(201);
    }
}
