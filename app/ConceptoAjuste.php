<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConceptoAjuste extends Model
{
    protected $table = 'conceptos_ajustes';

    protected $fillable = ['num_concepto', 'descripcion'];
}
