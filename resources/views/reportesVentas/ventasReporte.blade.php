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
                <h4 class="text-center">Reporte de Ventas</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Periodo: {{$fecha_inicial}} - {{$fecha_final}}</label><br>
                    <label>Cliente: {{$cliente}}</label><br>
                    <label>Sucursal: {{$sucursal}}</label><br>
                    <label>Vendedor: {{$vendedor}}</label><br>
                </div>
                @foreach($facturas->groupBy('sucursal') as $nomb_sucursal => $sucursal)
                <strong>Sucursal:</strong> {{$nomb_sucursal}}
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center" width="8%">Fecha</th>
                            <th width="14%">Nro Factura</th>
                            <th>Cliente</th>
                            <th class="text-center" width="10%">Descuento</th>
                            <th class="text-center" width="10%">Exenta</th>
                            <th class="text-center" width="10%">Gravada</th>
                            <th class="text-center" width="10%">IVA</th>
                            <th class="text-center" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sucursal as $factura_cab)
                        <tr>
                            <td class="text-center">{{$factura_cab->fecha_emision}}</td>
                            <td>{{$factura_cab->nro_comp}}</td>
                            <td>{{$factura_cab->cliente}}</td>
                            <td class="text-right">{{number_format($factura_cab->total_descuento, 0, ',', '.')}}</td>
                            <td class="text-right">{{number_format($factura_cab->total_exenta, 0, ',', '.')}}</td>
                            <td class="text-right">{{number_format($factura_cab->total_gravada, 0, ',', '.')}}</td>
                            <td class="text-right">{{number_format($factura_cab->total_iva, 0, ',', '.')}}</td>
                            <td class="text-right">{{number_format($factura_cab->total_comprobante, 0, ',', '.')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center"><strong>TOTALES</strong></td>
                            <td class="text-right"><strong>{{number_format($sucursal->sum('total_descuento'), 0, ',', '.')}} Gs.</strong></td>
                            <td class="text-right"><strong>{{number_format($sucursal->sum('total_exenta'), 0, ',', '.')}} Gs.</strong></td>
                            <td class="text-right"><strong>{{number_format($sucursal->sum('total_gravada'), 0, ',', '.')}} Gs.</strong></td>
                            <td class="text-right"><strong>{{number_format($sucursal->sum('total_iva'), 0, ',', '.')}} Gs.</strong></td>
                            <td class="text-right"><strong>{{number_format($sucursal->sum('total_comprobante'), 0, ',', '.')}} Gs.</strong></td>
                        </tr>
                    </tfoot>
                </table>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>