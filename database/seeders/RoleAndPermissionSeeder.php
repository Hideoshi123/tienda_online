<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionsBuyer = [
            'users.log',
            'cart.show',
            'cart.edit',
            'cart.getCartQuantity',
            'cartproducts.store',
            'cartproducts.update',
            'cartproducts.destroy',
			'products.getForCategory',
        ];

        $permissionsAdmin = [
            'users.index',
            'users.create',
            'users.store',
            'users.edit',
            'users.update',
            'users.destroy',
			'categories.index',
			'categories.get-all',
			'categories.store',
			'categories.update',
			'categories.destroy',
			'categories.get-all-dt',
			'categories.get-all',
			'categories.show',
			'products.index',
			'products.get-all-dt',
			'products.store',
			'products.show',
			'products.update',
			'products.destroy',
        ];

        // Crear Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $buyer = Role::firstOrCreate(['name' => 'buyer']);

        // Crear y Asignar Permisos a Admin
        foreach ($permissionsAdmin as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $admin->givePermissionTo($permission);
        }

        // Crear y Asignar Permisos a Buyer
        foreach ($permissionsBuyer as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $buyer->givePermissionTo($permission);
        }
    }
}
