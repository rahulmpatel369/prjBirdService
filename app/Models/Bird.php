<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bird extends Model {
    protected $fillable = ['local_name', 'image', 'residential_status', 'diet', 'is_verified', 'verify_by', 'created_by', 'updated_by'];

    protected $hidden = ['created_at', 'updated_at', 'verify_by', 'created_by', 'updated_by'];

    public static $verifyStatusFlag = ['verified' => '1', 'unverified' => 0];
    
    public static function addRules() {
        return [
            'local_name' => ['required', 'max:255'],
            'image' => ['required'],
            'extension' => ['required'],
            'residential_status' => ['required', 'max:255'],
            'diet' => ['required', 'max:255']
        ];
    }

    public function verifyByUser() {
        return $this->belongsTo('App\User', 'verify_by');
    }

    public function createdByUser() {
        return $this->belongsTo('App\User', 'created_by');
    }
}
