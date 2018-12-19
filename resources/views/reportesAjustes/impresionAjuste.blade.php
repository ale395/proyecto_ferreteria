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
                <h4 class="text-center">Ajuste de inventario</h4>
            </div>
            <div class="panel-body">
                <div class="form-group"><label>Nro Pedido: {{$ajuste_inventario_cab->getId()}}</label></div>
                <div class="form-group">
                    <label>Sucursal: {{$ajuste_inventario_cab->sucursal->getNombre()}}</label>
                    
                </div>
                <div class="form-group">
                    <label>Fecha: {{$ajuste_inventario_cab->getFechaEmision()}}</label>
                </div>
                <div class="form-group">
                    <label>Motivo: {{$ajuste_inventario_cab->getMotivo()}}</label>
                </div>
                <div class="form-group">
                    <label>Concepto Ajuste: {{$ajuste_inventario_cab->conceptoAjuste->getDescripcion()}}</label>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center">Artículo</th>
                            <th class="text-center" width="10%">Existencia Actual</th>
                            <th class="text-center" width="10%">Cant. Ajustada</th>
                            <th class="text-center" width="10%">Precio Unitario</th>
                            <th class="text-center" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ajuste_inventario_cab->ajusteInventarioDetalle as $ajuste_inventario_det)
                            <tr>
                                <td>{{$ajuste_inventario_det->articulo->getNombreSelect()}}</td>
                                <td class="text-center">{{$ajuste_inventario_det->getExistencia()}}</td>
                                <td class="text-center">{{$ajuste_inventario_det->getCantidad()}}</td>
                                <td class="text-right">{{$ajuste_inventario_det->getCostoUnitario()}}</td>
                                <td class="text-right">{{$ajuste_inventario_det->getSubTotal()}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>TOTAL</strong></td>
                            <td class="text-right"><strong>{{$ajuste_inventario_cab->getMontoTotal()}}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</body>