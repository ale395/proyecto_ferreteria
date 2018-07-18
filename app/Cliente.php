<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'id', 'nombre', 'apellido','nro_cedula', 'ruc','nro_telefono', 'correo_electronico', 'pais_id', 'ciudad_id', 'direccion','categoria_id', 'lista_precio_id', 'vendedor_id', 'estado',
    ];

    public function pais()
    {
        return $this->belongsTo('App\Pais');
    }

    public function ciudad()
    {
        return $this->belongsTo('App\Ciudad');
    }

    public function categoria()
    {
        return $this->belongsTo('App\CategoriaCliente');
    }

    public function lista_precio()
    {
        return $this->belongsTo('App\ListaPrecio');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Vendedor');
    }
}
