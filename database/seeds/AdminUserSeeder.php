<?php

use Illuminate\Database\Seeder;
use App\User;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = [ 
            "name" => env('ADMIN_NAME', 'admin'), 
            "email" => env('ADMIN_EMAIL', 'admin@gmail.com'), 
            "mobile_no" => env('ADMIN_MOBILE_NO', '9999999999'), 
            "password" => bcrypt(env('ADMIN_PASSWORD', '123123')),
            "role_id" => 1
        ];
        $user = User::firstOrCreate($adminUser);
    }
}
