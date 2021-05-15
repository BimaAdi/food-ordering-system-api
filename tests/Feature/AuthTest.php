<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public $role;
    public $test_user;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->role = Role::factory()->create([
            'name' => 'admin'
        ]);
        
        $this->test_user = User::factory()->create([
            'name' => 'TestGuy',
            'email' => 'testguy@local',
            'password' => Hash::make('test123'),
            'role_id' => $this->role->id
        ]);
    }

    public function test_login()
    {
        $response = $this->postJson('api/login', [
            'email' => $this->test_user->email,
            'password' => 'test123',
        ]);
        
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'email' => 'string',
                    'role' => 'string',
                    'token' => 'string'
                ])
        );

    }

    public function test_logout()
    {
        Sanctum::actingAs(
            $this->test_user,
            ['auth_token']
        );

        $response = $this->postJson('/api/logout');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'you are logged out'
            ]);
    }

    public function test_auth_user()
    {
        Sanctum::actingAs(
            $this->test_user,
            ['auth_token']
        );

        $response = $this->postJson('/api/auth/user/');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'email' => 'string',
                    'role' => 'string'
                ])
        );
    }
}
