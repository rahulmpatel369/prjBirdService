<?php 

namespace App\Services;
use App\Models\Role;
use App\User;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model {
    public static function getUserByEmail($email){
        return User::where("email", $email)->first();
    }

    public static function getUserById($id){
        return User::find($id);
    }

    public static function createUser($userRequest){
        $user = new User;
        $user = self::buildUserObj($user, $userRequest);

        $role = Role::where("name", $userRequest['role'])->first();
        if(!$role) return null;

        $user->role_id = $role->id;
        $status = $user->save();

        return $status ? self::getUserById($user) : null;
    }

    public static function updateUser($user, $userRequest){
        $user = self::buildUserObj($user, $userRequest);
        $status = $user->save();
        
        return $status ? self::getUserById($user) : null;
    }

    public static function buildUserObj($user, $userRequest){
        $user->name = $userRequest['name'];
        $user->email = $userRequest['email'];
        $user->mobile_no = $userRequest['mobile_no'];
        $user->password = bcrypt($userRequest['password']);
        return $user;
    }
}