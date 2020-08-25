<?php 

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Role;
use App\Services\UserService;

use JWTAuth;

class AdminController extends Controller {
    public function login(Request $request) {
        $validator = Validator::make($request->all(), User::loginRules());
        $user = UserService::getUserByEmail($request->email);
        $validator->after(function ($validator) use ($request, $user) {
            if($user == null) $validator->errors()->add('login', 'Invalid Credentials Email Or Password');
            if($user && !$user->hasRole('admin')) $validator->errors()->add('login', 'Invalid Credentials Email Or Password');
        });
        
        if($validator->fails()) return $this->errorResponse('Validation Error', $validator->errors());
        if(!$token = auth()->attempt($request->all())) return $this->errorResponse('Unauthorized',['login' => 'Invalid Credentials Email Or Password'], self::$HTTP_UNAUTHORIZED);

        return $this->response('success', [
            'access_token' => $token, 
            'token_type' => 'bearer', 
            'user' => auth()->user(),
            'role' => auth()->user()->role()->first(),
        ]);
    }

    public function logout(Request $request) {
        auth()->logout();
        return $this->response('User logged out successfully');
    }

    public function createModerator(Request $request) {
        $request->request->add(['role' => 'moderator']);
        $validator = Validator::make($request->all(), User::addRules());
        if($validator->fails()) return $this->errorResponse('Validation Error', $validator->errors());

        $user = UserService::createUser($request->all());
        if($user) return $this->response('success', $user);
        return $this->response('Failed', []);
    }

    public function getAllUsers() { 
        $users = UserService::getAllUsers();
        if($users) return $this->response('Success', $users);
        return $this->errorResponse('Data not Found', [], self::$HTTP_NO_CONTENT);
    }

    public function updateUserRole(User $user, Role $role) {
        if($role->name == 'admin') $this->errorResponse('Validation Error', ['user' => 'Admin Role can not updated.']);
        $user = UserService::updateUserRole($user, $role);
        if($user) return $this->response('Success', $user);
        return $this->errorResponse('Data not Found', [], self::$HTTP_NO_CONTENT);
    }

    public function getRoles() {
        $roles = Role::where('id', '!=', 1)->get();
        if($roles) return $this->response('Success', $roles);
        return $this->errorResponse('Data not Found', [], self::$HTTP_NO_CONTENT);
    }

}