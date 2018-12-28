<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprasCab extends Model
{
    CONST MAX_LINEAS_DETALLE = 25;

    protected $table = 'compras_cab';

    protected $fillable = [
        'tipo_factura', 'nro_factura', 'proveedor_id' ,'sucursal_id', 'moneda_id', 
        'valor_cambio', 'fecha_emision', 'monto_total', 'total_exenta', 'total_gravada',
        'total_iva', 'total_descuento','estado','comentario', 
        'timbrado', 'usuario_id', 'fecha_vigencia_timbrado'
    ];

    public function getId(){
        return $this->id;
    }

    public function getTipoFactura(){
    	return $this->tipo_factura;
    }

    public function getTipoFacturaIndex(){
        if ($this->tipo_factura == 'CO') {
            return 'Contado';
        } elseif ($this->tipo_factura == 'CR') {
            return 'Crédito';
        }
    }

    public function setUsuarioId($usuario_id){
        $this->usuario_id = $usuario_id;
    }

    public function setTipoFactura($tipo_factura){
    	$this->tipo_factura = $tipo_factura;
    }

    public function getNroFactura(){
    	return $this->nro_factura;
    }

    public function setNroFactura($nro_factura){
    	$this->nro_factura = $nro_factura;
    }

    public function setProveedorId($proveedor_id){
        $this->proveedor_id = $proveedor_id;
    }

    public function setSucursalId($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }

    public function setMonedaId($moneda_id){
        $this->moneda_id = $moneda_id;
    }

    public function setValorCambio($valor_cambio){
        $this->valor_cambio = $valor_cambio;
    }

    public function getValorCambio(){
        return number_format($this->valor_cambio, 0, ',', '.');
    }

    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    public function getFechaEmision(){
        return date("d-m-Y", strtotime($this->fecha_emision));
    }

    //fecha_vigencia_timbrado
    public function setFechaVigenciaTimbrado($fecha_vigencia_timbrado){
        $this->fecha_vigencia_timbrado = $fecha_vigencia_timbrado;
    }

    public function getFechaVigenciaTimbrado(){
        return date("d-m-Y", strtotime($this->fecha_vigencia_timbrado));
    }

    public function setMontoTotal($monto_total){
        $this->monto_total = $monto_total;
    }

    public function getMontoTotal(){
        return number_format($this->monto_total, 0, ',', '.');
    }

    public function getTotalExenta(){
        return number_format($this->total_exenta, 0, ',', '.');
    }

    public function setTotalExenta($total_exenta){
        $this->total_exenta = $total_exenta;
    }

    public function getTotalGravada(){
        return number_format($this->total_gravada, 0, ',', '.');
    }

    public function setTotalGravada($total_gravada){
        $this->total_gravada = $total_gravada;
    }

    public function getTotalIva(){
        return number_format($this->total_iva, 0, ',', '.');
    }

    public function setTotalIva($total_iva){
        $this->total_iva = $total_iva;
    }


    public function setComentario($comentario){
        $this->comentario = $comentario;
    }

    public function getComentario(){
        return $this->comentario;
    }

    public function setTimbrado($timbrado){
        $this->timbrado = $timbrado;
    }

    public function getTimbrado(){
        return $this->timbrado;
    }

    public function setUsuario($usuario_id){
        $this->usuario_id = $usuario_id;
    }

    public function setOrdenCompraId($orden_compra_id){
        $this->orden_compra_id = $orden_compra_id;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setEstado($estado){
        $this->estado = $esatdo;
    }

    public function getEstadoNombre(){
        if ($this->estado == 'P') {
            return 'Pendiente';
        } elseif ($this->estado == 'C') {
            return 'Cobrada';
        } elseif ($this->estado == 'A') {
            return 'Anulada';
        }
    }

    public function getMontoSaldo(){
        //if $this->estado
        $cuenta_proveedor = CuentaProveedor::where('tipo_comprobante', 'F')
            ->where('comprobante_id', $this->id)->first();
        //dd($cuenta_proveedor->getMontoSaldo());

        //$monto_saldo = $cuenta_proveedor->getMontoSaldo();

        if ($cuenta_proveedor) {
            return $cuenta_proveedor->getMontoSaldo();
        } else {
            return 0;
        }
    }

    public function getMontoSaldoFormat(){
        $cuenta_proveedor = CuentaProveedor::where('tipo_comprobante', 'F')
            ->where('comprobante_id', $this->id)->first();
        return number_format($cuenta_proveedor->getMontoSaldo(), 0, ',', '.');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }

    public function moneda()
    {
        return $this->belongsTo('App\Moneda');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function comprasdetalle(){
        return $this->hasMany('App\ComprasDet', 'compra_cab_id');
    }

    public function ordencompra(){
        return $this->hasOne('App\OrdenCompraCab', 'orden_compra_id');
    }
}
