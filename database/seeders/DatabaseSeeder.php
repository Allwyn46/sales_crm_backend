<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Permissions
        $permissions = [
            'view leads',
            'create leads',
            'edit leads',
            'delete leads',
            'import leads',
            'export leads',
            'view pipeline',
            'manage pipeline',
            'view followups',
            'create followups',
            'edit followups',
            'view calls',
            'log calls',
            'view users',
            'manage users',
            'manage roles',
            'view reports',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'sanctum']);
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'sanctum']);
        $agent = Role::firstOrCreate(['name' => 'Agent', 'guard_name' => 'sanctum']);

        $admin->givePermissionTo($permissions);
        $manager->givePermissionTo([
            'view leads', 'create leads', 'edit leads', 'delete leads',
            'import leads', 'export leads',
            'view pipeline', 'manage pipeline',
            'view followups', 'create followups', 'edit followups',
            'view calls', 'log calls',
            'view users', 'manage users',
            'view reports', 'manage settings',
        ]);
        $agent->givePermissionTo([
            'view leads', 'create leads', 'edit leads',
            'view pipeline',
            'view followups', 'create followups', 'edit followups',
            'view calls', 'log calls',
        ]);

        // Demo users
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@salescrm.local'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $adminUser->assignRole('Admin');

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@salescrm.local'],
            ['name' => 'Manager User', 'password' => bcrypt('password')]
        );
        $managerUser->assignRole('Manager');

        $agentUser = User::firstOrCreate(
            ['email' => 'agent@salescrm.local'],
            ['name' => 'Agent User', 'password' => bcrypt('password')]
        );
        $agentUser->assignRole('Agent');

        // Tags
        $tags = collect([
            ['name' => 'Hot', 'color' => '#ef4444'],
            ['name' => 'Cold', 'color' => '#3b82f6'],
            ['name' => 'Warm', 'color' => '#f59e0b'],
            ['name' => 'VIP', 'color' => '#a855f7'],
        ])->map(fn ($attr) => Tag::firstOrCreate(['name' => $attr['name']], $attr));

        // Demo leads
        $statuses = ['new', 'contacted', 'qualified', 'converted', 'lost', 'do_not_call'];
        $sources = ['website', 'phone', 'email', 'referral', 'walk_in', 'social_media', 'other'];

        $users = User::all();

        for ($i = 0; $i < 20; $i++) {
            $status = fake()->randomElement($statuses);
            $lead = Lead::create([
                'assigned_to_id' => $users->random()->id,
                'created_by_id' => $users->random()->id,
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'email' => fake()->optional()->email(),
                'company' => fake()->optional()->company(),
                'status' => $status,
                'source' => fake()->randomElement($sources),
                'value' => fake()->optional()->randomFloat(2, 100, 10000),
                'notes' => fake()->optional()->sentence(),
                'follow_up_at' => $status === 'new' ? fake()->dateTimeBetween('+1 day', '+14 days') : null,
            ]);

            $lead->tags()->sync(
                $tags->random(rand(0, 2))->pluck('id')->all()
            );
        }
    }
}
