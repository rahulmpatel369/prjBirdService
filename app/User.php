<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Arr;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile_no', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role_id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static $roles = [
        'moderator', 'volunteer'
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function role() {
        return $this->belongsTo('App\Models\Role');
    }

    public static function loginRules() {
        return [
            'email' => ['required', 'email', 'string', 'exists:users,email'],
            'password' => ['required']
        ];
    }

    public function hasRole($roleNames) {
        $role = $this->role()->first();
        if(is_array($roleNames) && $role && $role->name) return in_array($role->name, $roleNames);
        else if($role && $role->name == $roleNames) return true;
        else return false;
    }

    public static function addRules(){
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'unique:users,email', 'max:255'],
            'mobile_no' => ['required', 'unique:users,mobile_no', 'digits:10'],
            'password' => ['required', 'min:5'],
            'role' => ['required', Rule::in(self::$roles)]
        ];
    }
}
