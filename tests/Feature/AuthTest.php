<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up roles and permissions
    $permission = Permission::firstOrCreate(['name' => 'view leads', 'guard_name' => 'sanctum']);
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'sanctum']);
    $role->givePermissionTo($permission);
});

test('user can log in with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'admin@salescrm.local',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('Admin');

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@salescrm.local',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                    'permissions',
                ],
            ],
        ])
        ->assertJsonPath('data.user.email', 'admin@salescrm.local')
        ->assertJsonPath('data.user.roles.0', 'Admin');

    expect($response->json('data.token'))->not->toBeEmpty();
});

test('user cannot log in with invalid password', function () {
    User::factory()->create([
        'email' => 'admin@salescrm.local',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@salescrm.local',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('authenticated user can fetch their details via auth/me', function () {
    $user = User::factory()->create([
        'email' => 'agent@salescrm.local',
    ]);
    $agentRole = Role::firstOrCreate(['name' => 'Agent', 'guard_name' => 'sanctum']);
    $user->assignRole($agentRole);

    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/v1/auth/me');

    $response->assertOk()
        ->assertJsonPath('data.user.email', 'agent@salescrm.local')
        ->assertJsonPath('data.user.roles.0', 'Agent');
});

test('unauthenticated request to auth/me returns 401', function () {
    $response = $this->getJson('/api/v1/auth/me');

    $response->assertStatus(401);
});

test('authenticated user can log out and revoke token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/v1/auth/logout');

    $response->assertOk()
        ->assertJson(['message' => 'Logged out successfully']);

    expect($user->tokens()->count())->toBe(0);
});
