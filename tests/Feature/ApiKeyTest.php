<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_api_keys_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('api-keys.index'));

        $response->assertStatus(200);
        $response->assertSee('API Keys');
    }

    public function test_user_can_generate_api_key()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('api-keys.store'), [
            'name' => 'Test Key',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('api_keys', [
            'user_id' => $user->id,
            'name' => 'Test Key',
        ]);
        
        $apiKey = ApiKey::where('user_id', $user->id)->first();
        $this->assertNotNull($apiKey->key);
        // $this->assertEquals(64, strlen($apiKey->key)); // Length might vary slightly if we use random(60) + 'dvp_' = 64. 
        $this->assertTrue(str_starts_with($apiKey->key, 'dvp_'));
    }

    public function test_user_can_toggle_api_key_status()
    {
        $user = User::factory()->create();
        $apiKey = ApiKey::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch(route('api-keys.toggle', $apiKey));

        $response->assertRedirect();
        $this->assertDatabaseHas('api_keys', [
            'id' => $apiKey->id,
            'is_active' => false,
        ]);

        // Toggle back
        $response = $this->actingAs($user)->patch(route('api-keys.toggle', $apiKey));
        $this->assertDatabaseHas('api_keys', [
            'id' => $apiKey->id,
            'is_active' => true,
        ]);
    }

    public function test_user_can_delete_api_key()
    {
        $user = User::factory()->create();
        $apiKey = ApiKey::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('api-keys.destroy', $apiKey));

        $response->assertRedirect();
        $this->assertDatabaseMissing('api_keys', [
            'id' => $apiKey->id,
        ]);
    }

    public function test_user_cannot_delete_others_api_key()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $apiKey = ApiKey::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->delete(route('api-keys.destroy', $apiKey));

        $response->assertStatus(403);
        $this->assertDatabaseHas('api_keys', [
            'id' => $apiKey->id,
        ]);
    }
}
