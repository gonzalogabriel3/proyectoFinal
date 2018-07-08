<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sugerencia extends Model
{
    //
    protected $table = 'sugerencias';
    protected $fillable = ['estado','sugerencia','usuario_id'];
    public $timestamps = false;

}
