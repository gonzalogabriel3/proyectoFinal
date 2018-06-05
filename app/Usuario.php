<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table="usuarios";

    protected $dateFormat = 'Y-m-d H:i';

    protected $fillable=[
        'id','nombre','password','email','created_at','updated_at'
    ];
}
