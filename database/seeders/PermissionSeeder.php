<?php

namespace Database\Seeders;

use App\Kravanh\Domain\User\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{

    public function run(): void
    {
        // don't modify actions
        $actions = [
            'full-manage',
            'view-any',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'force-delete',
            'menu',
        ];

        // don't rotate order of an object, new object must add it to last array

        $objects = [
            'Permission',
            'Role',
            'User',
            'Company',
            'Environment',
            'SuperSenior',
            'Senior',
            'MasterAgent',
            'Agent',
            'Member',
            'Trader',
            'AuthenticationLog',
            'Match',
            'Group',
            'SubAccount',
            'Channel',
            'Message',
            'Domain',
            'Spread',
            'MemberType',
            'TraderDragonTiger'
        ];

        // truncate reset primary start from 1
        Schema::disableForeignKeyConstraints();
        if (app()->environment('testing')) {
            DB::table('permissions')->delete();
        } else {
            Permission::truncate();
        }
        Schema::enableForeignKeyConstraints();

        $permissions = $this->makePermissions($objects, $actions);

        $permissions[] = [
            'name' => 'Match:update-pending-result',
            'label' => 'Update Pending Result'
        ];

        $permissions[] = [
            'name' => 'Member:allow-stream',
            'label' => 'Allow member to stream video'
        ];

        $permissions[] = [
            'name' => 'Member:direct-withdraw',
            'label' => 'Allow user to direct withdraw balance from member'
        ];

        $permissions[] = [
            'name' => 'Member:direct-deposit',
            'label' => 'Allow user to direct deposit balance to member'
        ];

        $permissions[] = [
            'name' => 'MissingPayout:view-any',
            'label' => 'Allow user to view any missing payouts'
        ];

        $permissions[] = [
            'name' => 'Setting:full-manage',
            'label' => 'Allow user to full manage setting'
        ];

        $permissions[] = [
            'name' => 'LoginHistory:view-any',
            'label' => 'Allow user to view any login histories'
        ];

        $permissions[] = [
            'name' => 'LoginHistory:view',
            'label' => 'Allow user to view login histories'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'label' => $permission['label']
            ]);
        }
    }

    private function makePermissions(array $objects, array $actions): array
    {
        $permissions = [];

        foreach ($objects as $object) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => $object . ':' . $action,
                    'label' => Str::title($action . ' ' . $object)
                ];
            }
        }

        return $permissions;
    }
}
