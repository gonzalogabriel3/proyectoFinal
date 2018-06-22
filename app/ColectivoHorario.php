<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColectivoHorario extends Model
{
    //
    protected $table = "colectivo_horario";
    protected $fillable =["colectivo_id","horario_id"];
}
