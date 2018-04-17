<?php

namespace App;

use App\Departamento;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';

    protected $fillable = ['descripcion'];

    public function departamento()
    {
    	return $this->hasMany(Departamento::class);
    }
}
