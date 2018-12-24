<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body class="nav-md">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center"><font size="5">Reporte de Ventas</font></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <strong>Periodo:</strong> {{$fecha_inicial}} - {{$fecha_final}}<br>
                    <strong>Cliente:</strong> {{$cliente}}<br>
                    <strong>Sucursal:</strong> {{$sucursal}}<br>
                    <strong>Vendedor:</strong> {{$vendedor}}<br>
                    <strong>Incluir anulados:</strong> {{$incluye_anulados}}<br>
                </div>
                @foreach($facturas->groupBy('sucursal') as $nomb_sucursal => $sucursal)
                <strong>Sucursal:</strong> {{$nomb_sucursal}}
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center" width="6%">Fecha</th>
                            <th class="text-center" width="6%">Tipo Comp</th>
                            <th class="text-center" width="11%">Nro Comp</th>
                            <th class="text-center" width="6%">Estado</th>
                            <th>Cliente</th>
                            <th class="text-center" width="8%">Descuento</th>
                            <th class="text-center" width="8%">Exenta</th>
                            <th class="text-center" width="8%">Gravada</th>
                            <th class="text-center" width="7%">IVA</th>
                            <th class="text-center" width="9%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sucursal as $factura_cab)
                        <tr>
                            <td class="text-center"><font size="1">{{$factura_cab->fecha_emision}}</font></td>
                            <td><font size="1">{{$factura_cab->tipo_comp}}</font></td>
                            <td><font size="1">{{$factura_cab->nro_comp}}</font></td>
                            <td><font size="1">{{$factura_cab->estado}}</font></td>
                            <td><font size="1">{{$factura_cab->cliente}}</font></td>
                            <td class="text-right"><font size="1">{{number_format($factura_cab->total_descuento, 0, ',', '.')}}</font></td>
                            <td class="text-right"><font size="1">{{number_format($factura_cab->total_exenta, 0, ',', '.')}}</font></td>
                            <td class="text-right"><font size="1">{{number_format($factura_cab->total_gravada, 0, ',', '.')}}</font></td>
                            <td class="text-right"><font size="1">{{number_format($factura_cab->total_iva, 0, ',', '.')}}</font></td>
                            <td class="text-right"><font size="1">{{number_format($factura_cab->total_comprobante, 0, ',', '.')}}</font></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-center"><strong>TOTALES</strong></td>
                            <td class="text-right"><strong><font size="1">{{number_format($sucursal->sum('total_descuento'), 0, ',', '.')}} Gs.</font></strong></td>
                            <td class="text-right"><strong><font size="1">{{number_format($sucursal->sum('total_exenta'), 0, ',', '.')}} Gs.</font></strong></td>
                            <td class="text-right"><strong><font size="1">{{number_format($sucursal->sum('total_gravada'), 0, ',', '.')}} Gs.</font></strong></td>
                            <td class="text-right"><strong><font size="1">{{number_format($sucursal->sum('total_iva'), 0, ',', '.')}} Gs.</font></strong></td>
                            <td class="text-right"><strong><font size="1">{{number_format($sucursal->sum('total_comprobante'), 0, ',', '.')}} Gs.</font></strong></td>
                        </tr>
                    </tfoot>
                </table>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>