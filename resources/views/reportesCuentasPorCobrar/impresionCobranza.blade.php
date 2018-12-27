<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-6 text-center">
            <h3 class="text-center"><strong>{{$empresa->razon_social}}</strong></h3>
            <font size="3"><strong> Recibo de dinero </strong></font>
            <br>
            {{$empresa->ruc}}
            <br>
            {{$empresa->direccion}}
            <br>
            {{$empresa->telefono}}
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-6">
            <strong>N° Cobranza:</strong> {{$cabecera->getId()}}
            <br>
            <strong>Fecha:</strong> {{$cabecera->getFecha()}}
            <br>
            <strong>Cliente: </strong> {{$cabecera->cliente->getNombreIndex()}}
            <br>
            <strong>Cajero:</strong> {{$cabecera->usuario->name}}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <br>
            <table class="table table-bordered">
                <thead>
                    <tr class="active">
                        <th class="text-center" width="30%"><font size="2">Tipo Comp.</font></th>
                        <th width="40%">N° Comp.</th>
                        <th class="text-center" width="30%">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cabecera->comprobantes as $comprobante)
                        <tr>
                            <td><font size="1">Factura {{$comprobante->factura->getTipoFacturaIndex()}}</font></td>
                            <td class="text-right"><font size="1">{{$comprobante->factura->getNroFacturaIndex()}}</font></td> 
                            <td class="text-right"><font size="1">{{$comprobante->getMontoIndex()}}</font></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <strong>Total a Pagar:</strong> {{number_format($cabecera->comprobantes->sum('monto'), 0, ',', '.')}} Gs.
            <br>
            <strong>Pagado:</strong> {{number_format($cabecera->pagos->sum('monto'), 0, ',', '.')}} Gs.
            <br>
            <strong>Vuelto:</strong> {{number_format($cabecera->getVuelto(), 0, ',', '.')}} Gs.
            <br>
            <br>
            <p class="text-center"><strong>¡Gracias por su preferencia!</strong></p>
        </div>
    </div>
</div>
</body>
</html>