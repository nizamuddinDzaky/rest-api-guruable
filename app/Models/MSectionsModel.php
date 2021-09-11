<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class MSectionsModel extends Model 
{
    protected $table = 'm_sections';
    protected $primaryKey = 'section_id';

    public function section_class(){
        return $this->belongsTo(MClassModel::class, 'section_class_id');
    }
}
