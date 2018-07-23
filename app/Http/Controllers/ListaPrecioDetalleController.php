<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\ListaPrecioDetalle;
use App\ListaPrecioCabecera;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Auth;

class ListaPrecioDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista_precios_detalle = ListaPrecioDetalle::all();
        return view('listaPrecioDetalle.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'lista_precio_id' => $request['lista_precio_id'],
            'articulo_id' => $request['articulo_id'],
            'fecha_vigencia' => $request['fecha_vigencia'],
            'precio' => $request['precio']
        ];

        $lista_precio_detalle = ListaPrecioDetalle::create($data);

        return $lista_precio_detalle;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lista_precio_detalle = ListaPrecioDetalle::where('lista_precio_id', $id)->get();
        $list_prec_id = $id;
        $articulos = Articulo::all();
        $lista_precio_cab = ListaPrecioCabecera::findOrFail($id);
        return view('listaPrecioDetalle.index', compact('list_prec_id', 'articulos', 'lista_precio_cab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
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
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListaPrecioDetalle $listaPrecioDetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ListaPrecioDetalle  $listaPrecioDetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ListaPrecioDetalle::destroy($id);
    }

    public function apiListaPrecios($list_prec_id)
    {
        $permiso_editar = Auth::user()->can('numeseries.edit');
        $permiso_eliminar = Auth::user()->can('numeseries.destroy');
        $lista_precios_detalle = ListaPrecioDetalle::where('lista_precio_id', $list_prec_id)->get();

        if ($permiso_eliminar) {
                return Datatables::of($lista_precios_detalle)
                ->addColumn('articulo', function($lista_precios_detalle){
                    if (empty($lista_precios_detalle->articulo)) {
                         return null;
                     } else {
                        return $lista_precios_detalle->articulo->articulo;
                    }
                })
                ->addColumn('descripcion', function($lista_precios_detalle){
                    if (empty($lista_precios_detalle->articulo)) {
                         return null;
                     } else {
                        return $lista_precios_detalle->articulo->descripcion;
                    }
                })
                ->addColumn('action', function($lista_precios_detalle){
                    return '<a onclick="deleteData('. $lista_precios_detalle->id .')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Eliminar</a>';
                })->make(true);
        } else {
            return Datatables::of($lista_precios_detalle)
            ->addColumn('articulo', function($lista_precios_detalle){
                 if (empty($lista_precios_detalle->articulo)) {
                     return null;
                 } else {
                     return $lista_precios_detalle->articulo->articulo;
                 }
            })
            ->addColumn('descripcion', function($lista_precios_detalle){
                if (empty($lista_precios_detalle->articulo)) {
                     return null;
                 } else {
                     return $lista_precios_detalle->articulo->descripcion;
                 }
            })
            ->addColumn('action', function($nume_series){
                return '<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o"></i> Eliminar</a>';
            })->make(true);
        }
    }
}
