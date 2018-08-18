<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'codigo', 'nombre', 'apellido', 'ruc', 'nro_documento','telefono', 'direccion', 'correo_electronico', 'zona_id','tipo_cliente_id', 'lista_precio_id', 'vendedor_id', 'activo',
    ];

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }

    public function tipoCliente()
    {
        return $this->belongsTo('App\CategoriaCliente');
    }

    public function listaPrecio()
    {
        return $this->belongsTo('App\ListaPrecioCabecera');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Vendedor');
    }
}
