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
            'message' => 'message',
            'values' => json_encode([
                'value' => 'value',
            ]),
            'url' => 'url',
            'status' => 400,
        ]);

        $response->assertStatus(201);
    }
}
