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
                        <label for="nro_pedido" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="number" id="nro_pedido" name="nro_pedido" class="form-control" readonly="readonly">
                        </div>
                        <label for="fecha_emision" class="col-md-5 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control" value="{{old('fecha_emision')}}" placeholder="dd/mm/aaaa">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente *</label>
                        <div class="col-md-6">
                            <input type="text" id="cliente_id" name="cliente_id" class="form-control" value="{{old('cliente_id')}}" autofocus>
                        </div>
                        <label for="lista_precio_id" class="col-md-1 control-label">Lista Pre.*</label>
                        <div class="col-md-3">
                            <select id="select2-lista-precios" name="lista_precio_id" class="form-control" style="width: 100%">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-3">
                            <select id="select2-monedas" name="moneda_id" class="form-control" style="width: 100%">
                                
                            </select>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio*</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{old('valor_cambio')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="lista_precio_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <input type="text" id="articulo_id" name="articulo_id" class="form-control" placeholder="Artículo">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="precio_unitario" name="precio_unitario" class="form-control" placeholder="Precio Unitario">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="Subtotal" name="Subtotal" class="form-control" placeholder="Subtotal" readonly>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir al pedido"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
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
@section('otros_scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#select2-monedas').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            ajax: {
                url: "{{ route('api.zonas.select') }}",
                delay: 250,
                data: function (params) {
                    var queryParameters = {
                      q: params.term
                    }

                    return queryParameters;
                  },
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#select2-lista-precios').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            ajax: {
                url: "{{ route('api.tipos.clientes.select') }}",
                delay: 250,
                data: function (params) {
                    var queryParameters = {
                      q: params.term
                    }

                    return queryParameters;
                  },
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection