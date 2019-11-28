<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class DbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
        	'name' => 'admin',
        	'description' => 'admin'
        ]);

        Role::create([
        	'name' => 'user',
        	'description' => 'user'
        ]);

        User::create([
        	'name'		=>	'John Doe',
        	'email'		=>	'admin@admin.com',
        	'password'	=>	bcrypt('admin123'),
        	'role_id'	=> 	(Role::where('name','admin')->first())->id
        ]);

        User::create([
        	'name'		=>	'Jane Doe',
        	'email'		=>	'user@user.com',
        	'password'	=>	bcrypt('user123'),
        	'role_id'	=> 	(Role::where('name','user')->first())->id
        ]);
    }
}
