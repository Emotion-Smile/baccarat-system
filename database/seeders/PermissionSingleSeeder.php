<?php

namespace Database\Seeders;

use App\Kravanh\Domain\User\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class PermissionSingleSeeder extends Seeder
{

    public function run(string $object): void
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
            'menu'
        ];

        $objects[] = $object;
        $permissions = $this->makePermissions($objects, $actions);
        foreach ($permissions as $permission) {
            Permission::UpdateOrCreate([
                'name' => $permission['name'],
                'label' => $permission['label']
            ]);
        }
    }

    #[Pure]
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
