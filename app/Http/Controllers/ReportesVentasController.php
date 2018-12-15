<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesVentasController extends Controller
{
    public function viewReporteVentas(){
    	$fecha_actual = date("d/m/Y");;
    	return view('reportesVentas.ventasFiltros', compact('fecha_actual'));
    }

    public function verReporteVentas(Request $request){
    	return $request;
    }
}
