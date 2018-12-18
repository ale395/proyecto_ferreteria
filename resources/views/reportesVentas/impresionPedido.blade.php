<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="{{asset('assets\bootstrap\dist\css\bootstrap.min.css')}}">-->
</head>
<body class="nav-md">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center">PEDIDO DE VENTA</h4>
            </div>
            <div class="panel-body">
                <div class="form-group"><label>Nro Pedido: {{$cabecera->getNroPedido()}}</label></div>
                <div class="form-group">
                    <label>Cliente: {{$cabecera->cliente->getNombreSelect()}}</label>
                    
                </div>
                <div class="form-group">
                    <label>Moneda: {{$cabecera->moneda->getDescripcion()}}</label>
                </div>
                <div class="form-group">
                    <label>Fecha: {{$cabecera->getFechaEmision()}}</label>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center">Artículo</th>
                            <th class="text-center" width="8%">Cant.</th>
                            <th class="text-center" width="10%">Precio Unitario</th>
                            <th class="text-center" width="11%">Descuento</th>
                            <th class="text-center" width="10%">Exenta</th>
                            <th class="text-center" width="10%">Gravada</th>
                            <th class="text-center" width="8%">IVA</th>
                            <th class="text-center" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cabecera->pedidosDetalle as $detalle)
                            <tr>
                                <td>{{$detalle->articulo->getDescripcion()}}</td>
                                <td class="text-center">{{$detalle->getCantidadNumber()}}</td>
                                <td class="text-right">{{$detalle->getPrecioUnitario()}}</td>
                                <td class="text-right">{{$detalle->getMontoDescuento()}}</td>
                                <td class="text-right">{{$detalle->getMontoExenta()}}</td>
                                <td class="text-right">{{$detalle->getMontoGravada()}}</td>
                                <td class="text-right">{{$detalle->getMontoIva()}}</td>
                                <td class="text-right">{{$detalle->getMontoTotal()}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>TOTAL</strong></td>
                            <td class="text-right">{{$total_exenta}}</td>
                            <td class="text-right">{{$total_gravada}}</td>
                            <td class="text-right">{{$total_iva}}</td>
                            <td class="text-right"><strong>{{$cabecera->getMontoTotal()}}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                <!--<div class="form-group">
                    <label>Comentario: {{$cabecera->getComentario()}}</label>
                </div>-->
            </div>
        </div>
    </div>
</div>
</body>