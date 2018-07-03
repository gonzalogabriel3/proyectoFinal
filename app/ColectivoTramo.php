<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColectivoTramo extends Model
{
    //
    protected $table = "colectivo_tramo";
    protected $fillable =["colectivo_id","tramo_id"];
}
