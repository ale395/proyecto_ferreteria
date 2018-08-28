@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('PedidoVentaController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Pedido de Venta
                    <div class="pull-right btn-group">
                        <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                        <a href="{{route('pedidosVentas.create')}}" type="button" class="btn btn-default">Cancelar</a>
                    </div>
                    
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="nro_pedido" class="col-md-1 control-label">Numero</label>
                        <div class="col-md-2">
                            <input type="number" id="nro_pedido" name="nro_pedido" class="form-control" readonly="readonly">
                        </div>
                        <label for="fecha_emision" class="col-md-3 control-label">Fecha *</label>
                        <div class="col-md-3">
                            <input type="date" id="fecha_emision" name="fecha_emision" class="form-control" value="{{old('fecha_emision')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente *</label>
                        <div class="col-md-5">
                            <input type="text" id="cliente_id" name="cliente_id" class="form-control" value="{{old('cliente_id')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-3">
                            <input type="text" id="moneda_id" name="moneda_id" class="form-control" value="{{old('moneda_id')}}">
                        </div>
                        <label for="valor_cambio" class="col-md-2 control-label">Valor Cambio *</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{old('valor_cambio')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sucursal_id" class="col-md-1 control-label">Sucursal *</label>
                        <div class="col-md-3">
                            <input type="text" id="sucursal_id" name="sucursal_id" class="form-control" value="{{old('sucursal_id')}}">
                        </div>
                    </div>
                    <table id="pedido-detalle" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Código Artículo</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection