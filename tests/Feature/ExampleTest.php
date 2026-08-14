<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // create a user and authenticate for the request so protected routes return 200
        $user = User::factory()->create();
        $response = $this->actingAs($user)->followingRedirects()->get('/');

        $response->assertStatus(200);
    }
}
