<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //
   protected $table = 'departamentos';

    protected $fillable = ['descripcion', 'pais_id'];

    public function pais()
    {
    	return $this->belongsTo(Pais::class);
    }
}
