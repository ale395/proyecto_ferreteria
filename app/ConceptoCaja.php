<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConceptoCaja extends Model
{
    protected $table = 'conceptos_caja';

    protected $fillable = ['num_concepto', 'descripcion', 'ingresos', 'egresos'];
}
