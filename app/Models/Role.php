<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    protected $fillable = ['id', 'name', 'display_name', 'description'];

    protected $hidden = ['created_at', 'updated_at'];

    public function user() { 
        return $this->hasOne('App\User');
    }
}
