<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dname = ['manager','admin kredit','custome service','head teller','teller', 'akuntansi'];
        $dusername = ['manager','admin-kredit','customer-service','head-teller','teller','akuntansi'];
        for ($i=0; $i < count($dname); $i++) {
            $role = new Role;
            $role->name = $dusername[$i];
            $role->save();
            $user = new User;
            $user->name = $dname[$i];
            $user->username = $dusername[$i];
            $user->email = $dusername[$i].'@gmail.com';
            $user->password = Hash::make('password');
            $user->save();
            $permissions = Permission::pluck('id','id')->all();

            $role->syncPermissions($permissions);
            $user->assignRole($dusername[$i]);
        }
    }
}
