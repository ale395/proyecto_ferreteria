<?php

namespace App;

use App\Ciudad;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';

    protected $fillable = ['descripcion'];

    public function ciudad()
    {
    	return $this->hasMany(Ciudad::class);
    }
}
