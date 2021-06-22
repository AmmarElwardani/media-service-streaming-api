<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        try{
            Permission::create(['name' => 'view']);
            Permission::create(['name' => 'add']);
            Permission::create(['name' => 'delete']);
            Permission::create(['name' => 'update']);

            //TODO
                //add upload and live permissions
        }
        catch ( Throwable $e ){
            report($e);
        }

        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->guard_name = 'api';
        $role_admin->save();

        $role_company = new Role();
        $role_company->name = 'Company';
        $role_company->guard_name = 'api';
        $role_company->save();

        $role_user = new Role();
        $role_user->name = 'User';
        $role_user->guard_name = 'api';
        $role_user->save();

        
        $role_admin->givePermissionTo(['add', 'view', 'update', 'delete']);
        $role_company->givePermissionTo(['add', 'view', 'update', 'delete']);
        $role_user->givePermissionTo('view');
        

        $role_user = Role::where('name','User')->first();
        $role_company = Role::where('name','Company')->first();
        $role_admin = Role::where('name','Admin')->first();

        try{
            $admin = new User();
            $admin->name = 'Victor Chikabala';
            $admin->email = 'admin@example.com';
            $admin->password = bcrypt('admin');
            $admin->phone = '55123456';

            $admin->save();
            $admin->assignRole($role_admin);
           

            $user = new User();
            $user->name = 'Alex Trax';
            $user->email = 'user@example.com';
            $user->password = bcrypt('user');
            $user->phone = '55123457';

            $user->save();
            $user->assignRole($role_user);

            $company = new User();
            $company->name = 'Amazon';
            $company->email = 'company@example.com';
            $company->password = bcrypt('company');
            $company->phone = '55123458';

            $company->save();
            $company->assignRole($role_company);
            
        }catch( Throwable $e ){
             report($e);
        }
    }
}
