<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table="comentarios";

    public $timestamps = false;
    protected $fillable=[
        'comentario','usuario_id'
    ];

    

}
