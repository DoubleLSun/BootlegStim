<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        // '/' route redirects to store.index
        $response = $this->get('/')->followRedirects('store.index');

        $response->assertStatus(200);
    }
}
