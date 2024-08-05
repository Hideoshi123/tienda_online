<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
		];
		$permissionsAdmin = array_merge([
		], $permissionsBuyer);

        //Roles
        $admin = Role::create(['name' => 'admin']);
        $Buyer = Role::create(['name' => 'buyer']);

        foreach ($permissionsAdmin as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $admin->givePermissionTo($permission);
        }

        foreach ($permissionsBuyer as $permission) {
            $permission = Permission::where(['name' => $permission])->first();
            $Buyer->givePermissionTo($permission);
        }
    }
}
