<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConceptoAjuste extends Model
{
    protected $table = 'conceptos_ajuste';

    protected $fillable = ['num_concepto', 'descripcion'];}
