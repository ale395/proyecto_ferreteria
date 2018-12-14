<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timbrado extends Model
{
    protected $table = 'timbrados';

    protected $casts = [
    	'created_at' => 'Y-m-d H:i:s',
    	'updated_at' => 'Y-m-d H:i:s',
    	'fecha_inicio_vigencia' => 'date:d/m/Y',
    	'fecha_fin_vigencia' => 'date:d/m/Y'
	];

	protected $dates = [
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia'
    ];

    protected $fillable = [
        'nro_timbrado', 'fecha_inicio_vigencia', 'fecha_fin_vigencia',
    ];

    public function getNroTimbrado(){
        return $this->nro_timbrado;
    }

    public function getFechaFinVigencia(){
        return date("d-m-Y", strtotime($this->fecha_fin_vigencia));
    }

}
