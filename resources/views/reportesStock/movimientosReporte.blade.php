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
                <h4 class="text-center"><font size="5">Reporte de Movimientos</font></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <strong>Periodo:</strong> {{$fecha_inicial}} - {{$fecha_final}}<br>
                    <strong>Articulo:</strong> {{$articulo}}<br>
                    <strong>Sucursal:</strong> {{$sucursal}}<br>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center" width="6%">Fecha</th>
                            <th class="text-center" width="6%">Tipo Comp</th>
                            <th class="text-center" width="11%">Nro Comp</th>
                            <th>Articulo</th>
                            <th class="text-center" width="9%">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($movimientos as $movimiento)
                        <tr>
                            <td class="text-center"><font size="1">{{$movimiento->fecha_movimiento}}</font></td>
                            <td><font size="1"></font>{{$movimiento->tipo_movimiento}}</td>
                            <td><font size="1"></font>{{$movimiento->movimiento_id}}</td>
                            <td><font size="1"></font>{{$movimiento->descripcion}}</td>
                            <td class="text-right"><font size="1"></font>{{$movimiento->cantidad}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-center"><strong>saldo</strong></td>
                            <td class="text-right"><strong><font size="1">{{$saldo}}</font></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</body>