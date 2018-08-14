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

    public static $rules = [
            'nro_timbrado' => 'required|unique:timbrados,nro_timbrado',
            'fecha_inicio_vigencia' => 'required',
            'fecha_fin_vigencia' => 'required|after_or_equal:fecha_inicio_vigencia'
        ];

    public function getRules(){
        return $rules;
    }
}
