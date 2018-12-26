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
                <h4 class="text-center">EXTRACTO DE CLIENTE</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <strong>Proveedor:</strong> {{$cliente->getNombreIndex()}}
                </div>
                <div class="form-group"><strong>Rango de Fechas:</strong> {{$fecha_inicial}} <strong>A</strong> {{$fecha_final}}</div>
                <div class="form-group">
                    <strong>Saldo Anterior:</strong> {{$saldo_anterior}} Gs.
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center">Fecha</th>
                            <th>Descripción</th>
                            <th>Nro Comprobante</th>
                            <th class="text-center">Debito</th>
                            <th class="text-center">Crédito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registros as $registro)
                            <tr>
                                <td>{{$registro->fecha_emision}}</td>
                                <td>{{$registro->descripcion}}</td>
                                <td>{{$registro->nro_comp}}</td>
                                <td class="text-right">{{$registro->debito}} Gs.</td>
                                <td class="text-right">{{$registro->credito}} Gs.</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><strong>{{$total_debito}} Gs.</strong></td>
                            <td class="text-right"><strong>{{$total_credito}} Gs.</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="form-group">
                    <strong>Saldo Final:</strong> {{$saldo}} Gs.
                </div>
            </div>
        </div>
    </div>
</div>
</body>