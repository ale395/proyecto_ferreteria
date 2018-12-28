<?php

namespace App\Http\Controllers;

use Validator;
use App\Banco;
use App\Moneda;
use App\Empresa;
use App\FormaPago;
use App\CobranzaCab;
use App\CobranzaDet;
use App\CobranzaComp;
use App\CuentaCliente;
use App\HabilitacionCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class CobranzaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha_actual = date('d/m/Y');
        $usuario = Auth::user();
        $habilitacion = HabilitacionCaja::where('user_id', $usuario->getId())
            ->whereNull('fecha_hora_cierre')->first();
        $moneda = Moneda::where('codigo', 'GS')->first();
        $valor_cabmio = 1;
        $pagos = FormaPago::all();
        $bancos = Banco::all();

        if (count($habilitacion) == 0) {
            return redirect('/gestionCajas/habilitarCaja')->with('status', 'Debe habilitar una caja para poder realizar cobranzas!');
        }

        return view('cobranza.create', compact('fecha_actual', 'habilitacion', 'moneda', 'valor_cabmio', 'pagos', 'bancos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'habilitacion_id' => 'required',
            'fecha' => 'required|date_format:d/m/Y',
            'sucursal_id' => 'required',
            'cliente_id' => 'required',
            'moneda_id' => 'required',
            'tab_comp_id' => 'required|array|min:1',
            'tab_forma_pago_id' => 'required|array|min:1',
        ];

        $mensajes = [
            'tab_comp_id.required' => 'No se puede realizar la cobranza sin cargar algun comprobante a cobrar',
            'tab_forma_pago_id.required' => 'No se puede realizar la cobranza sin cargar la forma de pago!',
            'tab_comp_id.min' => 'Como mínimo se debe cargar :min comprobante a cobrar!',
            'tab_forma_pago_id.min' => 'Como mínimo se debe cargar :min forma de pago!',
            'cliente_id.required' => 'Debe seleccionar un cliente!',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes)->validate();

        $total_comp = 0;
        $total_cobrado = 0;

        $cabecera = new CobranzaCab;
        $cabecera->setHabilitacionId($request['habilitacion_id']);
        $cabecera->setSucursalId($request['sucursal_id']);
        $cabecera->setClienteId($request['cliente_id']);
        $cabecera->setMonedaId($request['moneda_id']);
        $cabecera->setValorCambio(1);
        $cabecera->setCajeroId(Auth::user()->id);
        $cabecera->setFecha($request['fecha']);
        $cabecera->setComentario($request['comentario']);
        $cabecera->save();

        for ($i=0; $i < collect($request['tab_comp_id'])->count(); $i++){
            $comprobante = new CobranzaComp;
            $comprobante->setCobranzaCabId($cabecera->getId());
            $comprobante->setComprobanteId($request['tab_comp_id'][$i]);
            $comprobante->setMonto($request['tab_comp_monto'][$i]);
            $comprobante->save();
            $total_comp = $total_comp + $comprobante->getMonto();

            //Resta el saldo de la factura cobrada
            $cuenta_cliente = CuentaCliente::where('tipo_comprobante', 'F')
                ->where('cliente_id', $request['cliente_id'])
                ->where('comprobante_id', $request['tab_comp_id'][$i])->first();
            $cuenta_cliente->setMontoSaldo($cuenta_cliente->getMontoSaldo() - $request['tab_comp_monto'][$i]);
            $cuenta_cliente->save();

            if ($cuenta_cliente->getMontoSaldo() == 0) {
                $factura = $cuenta_cliente->factura;
                $factura->setEstado('C');//Cobrado
                $factura->update();
            }
        }

        for ($i=0; $i < collect($request['tab_forma_pago_id'])->count(); $i++){
            $detalle = new CobranzaDet;
            $detalle->setCobranzaCabId($cabecera->getId());
            $forma_pago = FormaPago::where('codigo', $request['tab_forma_pago_id'][$i])->first();
            $detalle->setFormaPagoId($forma_pago->getId());
            $detalle->setMonto($request['tab_pago_monto'][$i]);
            if ($request['tab_forma_pago_id'][$i] != 'EFE') {
                $detalle->setBancoId($request['tab_banco_id'][$i]);
                $detalle->setFechaEmision($request['tab_fecha_emision'][$i]);
                $detalle->setNroValor($request['tab_nro_valor'][$i]);
            }
            $detalle->save();

            $total_cobrado = $total_cobrado + $detalle->getMonto();
        }

        $cuenta = new CuentaCliente;
        $cuenta->setTipoComprobante('C');
        $cuenta->setClienteId($request['cliente_id']);
        $cuenta->setComprobanteId($cabecera->getId());
        $cuenta->setMontoComprobante($total_comp);
        $cuenta->setMontoSaldo(0);
        $cuenta->save();

        $cabecera->setMontoTotal($total_comp);
        $cabecera->setVuelto($total_cobrado - $total_comp);
        $cabecera->update();

        return redirect()->route('cobranza.show', ['cobranza' => $cabecera->getId()])->with('status', 'Cobranza guardada correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cabecera = CobranzaCab::findOrFail($id);
        return view('cobranza.show', compact('cabecera'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function impresionCobranza($cobranza_id){
        $cabecera = CobranzaCab::findOrFail($cobranza_id);
        $empresa = Empresa::first();
        $pdf = PDF::loadView('reportesCuentasPorCobrar.impresionCobranza', compact('cabecera', 'empresa'));
        return $pdf->stream('Cobranza.pdf',array('Attachment'=>1));
    }
}
