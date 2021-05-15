<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // setUp the roles and user
        $roles = Role::factory()
                    ->count(2)
                    ->state(new Sequence(
                        ['name' => 'admin'],
                        ['name' => 'waiter'],
                    ))
                    ->create();
        $this->user_admin = User::factory()->create([
            'name' => 'TestGuy',
            'email' => 'testguy@local',
            'password' => Hash::make('test123'),
            'role_id' => $roles[0]->id
        ]);
        $this->user_non_admin = User::factory()->create([
            'name' => 'TestNonAdmin',
            'email' => 'testNonAdmin@local',
            'password' => Hash::make('test123'),
            'role_id' => $roles[1]->id
        ]);
        $this->user_experiment = User::factory()->create([
            'name' => 'XXXX',
            'email' => 'XXXX@local',
            'password' => Hash::make('test123'),
            'role_id' => $roles[1]->id
        ]);
    }

    public function test_get_all_user()
    {
        Sanctum::actingAs(
            $this->user_admin,
            ['auth_token']
        );
        $response = $this->get('/api/user/');

        $response->assertStatus(200);
    }
}
