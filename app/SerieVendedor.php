<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SerieVendedor extends Model
{
    protected $table = 'series_vendedores';

    protected $fillable = [
        'serie_id', 'vendedor_id',
    ];

    public function serie()
    {
        return $this->belongsTo('App\Serie');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Vendedor');
    }
}
