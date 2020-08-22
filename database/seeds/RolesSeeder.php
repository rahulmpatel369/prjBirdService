<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $roles = [
            [ "name" => "admin", "display_name" => "Admin", "description" => "" ],
            [ "name" => "moderator", "display_name" => "Moderator", "description" => "" ],
            [ "name" => "volunteer", "display_name" => "Volunteer", "description" => "" ],
        ];

        foreach($roles as $role){
            Role::firstOrCreate($role);
        }
    }
}
