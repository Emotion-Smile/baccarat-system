<?php

namespace Database\Seeders;

use App\Kravanh\Domain\User\Models\Permission;
use App\Kravanh\Domain\User\Models\PermissionRole;
use App\Kravanh\Domain\User\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = [
            'name' => 'admin',
            'label' => 'Administrator'
        ];


        Schema::disableForeignKeyConstraints();

        if (app()->environment('testing')) {
            DB::table('roles')->delete();
            DB::table('permission_role')->delete();
        } else {
            Role::truncate();
            PermissionRole::truncate();
        }

        Schema::enableForeignKeyConstraints();

        Role::create($roleAdmin)
            ->permissions()
            ->attach(Permission::all());

        Role::create([
            'name' => 'company',
            'label' => 'Company'
        ])
            ->permissions()
            ->attach($this->makeCompanyPermissions());

        Role::create([
            'name' => 'super_senior',
            'label' => 'Super Senior'
        ])
            ->permissions()
            ->attach($this->makeSuperSeniorPermissions());

        Role::create([
            'name' => 'senior',
            'label' => 'Senior'
        ])
            ->permissions()
            ->attach($this->makeSeniorPermissions());


        Role::create([
            'name' => 'master_agent',
            'label' => 'Master Agent'
        ])
            ->permissions()
            ->attach($this->makeMasterAgentPermissions());

        Role::create([
            'name' => 'agent',
            'label' => 'Agent'
        ])
            ->permissions()
            ->attach($this->makeAgentPermissions());

        Role::create([
            'name' => 'sub_account',
            'label' => 'Sub Account'
        ])
            ->permissions()
            ->attach($this->makeSubAccountPermission());

    }

    protected function makeCompanyPermissions()
    {
        return Permission::query()
            ->whereIn('name', [
                'Company:full-manage',
                ...$this->permissionGenerator('Environment', ['full-manage']),
                'Company:menu',
                ...$this->permissionGenerator('Trader', ['full-manage', 'delete']),
                ...$this->permissionGenerator('SuperSenior', ['full-manage', 'delete', 'restore', 'force-delete']),
                'Senior:view-any',
                'Senior:view',
                'Senior:menu',
                'MasterAgent:menu',
                'MasterAgent:view-any',
                'MasterAgent:view',
                'Agent:menu',
                'Agent:view-any',
                'Agent:view',
                'Member:menu',
                'Member:view-any',
                'Member:view',
                'Member:direct-deposit',
                'Member:direct-withdraw',
                'Match:view-any',
                'Match:view',
                'Match:update-pending-result',
                'Member:allow-steam',
                'MissingPayout:view-any',
                'Setting:full-manage',
                'LoginHistory:view-any',
                'LoginHistory:view',
                ...$this->permissionGenerator('Group', [
                    'view-any',
                    'view',
                    'create',
                    'update',
                    'menu'
                ], true),
                ...$this->permissionGenerator('SubAccount'),
                ...$this->permissionGenerator('Message'),
                ...$this->permissionGenerator('Domain'),
                ...$this->permissionGenerator('Spread'),
                ...$this->permissionGenerator('MemberType', ['view-any', 'view', 'create', 'delete'], include: true),
                ...$this->permissionGenerator('TraderDragonTiger'),
            ])->get();
    }

    protected function makeSubAccountPermission()
    {
        return Permission::query()
            ->whereIn('name', [
                'WinAndLoss:menu',
                'WinAndLoss:view-any',
                'WinAndLoss:view',
                'Payment:menu',
                'Payment:view-any',
                'Payment:view',
            ])->get();
    }

    protected function makeSuperSeniorPermissions()
    {
        return Permission::query()
            ->whereIn('name', [
                'SuperSenior:view-any',
                ...$this->permissionGenerator('Senior', ['full-manage', 'delete', 'restore', 'force-delete']),
                'MasterAgent:menu',
                'MasterAgent:view-any',
                'MasterAgent:view',
                'Agent:menu',
                'Agent:view-any',
                'Agent:view',
                'Member:menu',
                'Member:view-any',
                'Member:view',
                'LoginHistory:view-any',
                'LoginHistory:view',
                ...$this->permissionGenerator('SubAccount')
            ])->get();
    }

    protected function makeSeniorPermissions()
    {
        return Permission::query()
            ->whereIn('name', [
                'Senior:view-any',
                ...$this->permissionGenerator('MasterAgent', ['full-manage', 'delete', 'restore', 'force-delete']),
                'Agent:menu',
                'Agent:view-any',
                'Agent:view',
                'Member:menu',
                'Member:view-any',
                'Member:view',
                'LoginHistory:view-any',
                'LoginHistory:view',
                ...$this->permissionGenerator('SubAccount')
            ])->get();
    }

    protected function makeMasterAgentPermissions()
    {
        return Permission::query()
            ->whereIn('name', [
                'MasterAgent:view-any',
                ...$this->permissionGenerator('Agent', ['full-manage', 'delete', 'restore', 'force-delete']),
                'Member:menu',
                'Member:view-any',
                'Member:view',
                'LoginHistory:view-any',
                'LoginHistory:view',
                ...$this->permissionGenerator('SubAccount')
            ])->get();
    }

    protected function makeAgentPermissions()
    {
        return Permission::query()
            ->whereIn('name', [
                'Agent:view-any',
                'LoginHistory:view-any',
                'LoginHistory:view',
                ...$this->permissionGenerator('Member', ['full-manage', 'delete', 'restore', 'force-delete']),
                ...$this->permissionGenerator('SubAccount')
            ])->get();
    }

    protected function permissionGenerator($object, $objectPermissions = [], $include = false): array
    {
        $permissions = collect(
            [
                'full-manage',
                'view-any',
                'view',
                'create',
                'update',
                'delete',
                'restore',
                'force-delete',
                'menu'
            ]);

        return $permissions->filter(function ($permission) use ($objectPermissions, $include) {

            if (empty($objectPermissions)) {
                return $permission;
            }

            return $include ?
                in_array($permission, $objectPermissions) :
                !in_array($permission, $objectPermissions);

        })->map(function ($permission) use ($object) {
            return "{$object}:{$permission}";
        })->toArray();
    }
}
