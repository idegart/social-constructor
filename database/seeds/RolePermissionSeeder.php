<?php

use App\Models\Trust\Permission;
use App\Models\Trust\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    private $permissions;

    private $roles;

    public function __construct()
    {
        $this->permissions = collect([
            ['name' => Permission::CREATE_BLOCK, 'display_name' => 'Can create block in schema'],
        ]);

        $this->roles = collect([
            ['name' => Role::SCRIPT_TEAM, 'display_name' => 'Script team base role',
                'permissions' => [

                ]
            ],
            ['name' => Role::SCHEMA_TEAM, 'display_name' => 'Schema team base role',
                'permissions' => [
                    Permission::CREATE_BLOCK,
                ]
            ],
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->roles->each(function ($roleData) {
            $role = Role::updateOrCreate([
                'name' => $roleData['name'],
            ], collect($roleData)->only(['display_name'])->toArray());

            $permissions = collect($roleData['permissions'])
                ->map(function ($permissionName) {
                    return $this->permissions->firstWhere('name', '=', $permissionName);
                })
                ->filter(function ($permissionData) {
                    return !! $permissionData;
                })
                ->map(function ($permissionData) {
                    return Permission::updateOrCreate([
                        'name' => $permissionData['name'],
                    ], collect($permissionData)->only(['display_name'])->toArray());
                });

            $role->syncPermissions($permissions);
        });
    }
}
