<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class MClassModel extends Model 
{
    protected $table = 'm_class';
    protected $primaryKey = 'class_id';

    // public function user(){
    //     return $this->belongsTo(MUsersModel::class, 'teachers_user_id');
    // }
}
