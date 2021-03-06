<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEmpleado extends Model
{
    protected $table = 'tipos_empleados';

    protected $fillable = ['codigo', 'nombre'];

    public function empleados()
    {
    	return $this->belongsToMany('App\Empleado')->withTimestamps();
    }
}
