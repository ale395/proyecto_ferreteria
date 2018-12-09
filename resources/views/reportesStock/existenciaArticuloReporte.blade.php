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
                <h4 class="text-center">EXISTENCIA DE ARTICULOS</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                <label>Cliente: {{$sucursal->getNombre()}}</label>
                </div>
                <div class="form-group"><label>Fecha: {{$fecha_final}}</label></div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center">Articulo nombre</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registros as $registro)
                            <tr>
                            <td>{{$registro->cantidad}}</td>
                                <td>{{$registro->articulo_id}}</td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</body>