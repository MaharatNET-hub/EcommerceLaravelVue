<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Resources that get the standard view/create/update/delete permission set.
     */
    public const RESOURCES = [
        'categories',
        'brands',
        'products',
        'orders',
        'coupons',
        'pages',
        'sliders',
        'reviews',
        'users',
        'roles',
    ];

    public const ACTIONS = ['view', 'create', 'update', 'delete'];

    public function run(): void
    {
        foreach (self::RESOURCES as $resource) {
            foreach (self::ACTIONS as $action) {
                Permission::findOrCreate("{$resource}.{$action}");
            }
        }

        Permission::findOrCreate('settings.view');
        Permission::findOrCreate('settings.update');
        Permission::findOrCreate('dashboard.view');

        $superAdmin = Role::findOrCreate('Super Admin');
        $superAdmin->syncPermissions(Permission::all());

        $productManager = Role::findOrCreate('Product Manager');
        $productManager->syncPermissions(
            Permission::query()
                ->where(fn ($q) => $q->where('name', 'like', 'categories.%')
                    ->orWhere('name', 'like', 'brands.%')
                    ->orWhere('name', 'like', 'products.%')
                    ->orWhere('name', 'like', 'reviews.%'))
                ->orWhere('name', 'dashboard.view')
                ->get()
        );

        $orderManager = Role::findOrCreate('Order Manager');
        $orderManager->syncPermissions(
            Permission::query()
                ->where(fn ($q) => $q->where('name', 'like', 'orders.%')
                    ->orWhere('name', 'like', 'coupons.%'))
                ->orWhere('name', 'dashboard.view')
                ->get()
        );

        $contentEditor = Role::findOrCreate('Content Editor');
        $contentEditor->syncPermissions(
            Permission::query()
                ->where(fn ($q) => $q->where('name', 'like', 'pages.%')
                    ->orWhere('name', 'like', 'sliders.%'))
                ->orWhere('name', 'settings.view')
                ->orWhere('name', 'dashboard.view')
                ->get()
        );
    }
}
