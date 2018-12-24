@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('CobranzaController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Cobranza
                    <div class="pull-right btn-group">
                        <button data-toggle="tooltip" data-placement="top" title="Guardar" type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Cancelar carga" href="{{route('pedidosVentas.create')}}" type="button" class="btn btn-warning"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('pedidosVentas.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                                {{ session('status') }}
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
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente *</label>
                        <div class="col-md-5">
                            <select id="select2-clientes" name="cliente_id" class="form-control" autofocus style="width: 100%"></select>
                        </div>
                        <div class="col-md-1">
                            <a onclick="addForm()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Crear Cliente"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
                        </div>
                        <label for="lista_precio_id" class="col-md-1 control-label">Lista Pre.*</label>
                        <div class="col-md-3">
                            <a data-toggle="tooltip" data-placement="top" title="Lista de Precios">
                                <select id="select2-lista-precios" name="lista_precio_id" class="form-control" style="width: 100%">
                                    <option value="{{$lista_precio->getId()}}">{{$lista_precio->getNombre()}}</option>
                                </select>
                            </a>
                        </div>
                    </div>
                    <input type="hidden" name="moneda_id" value="{{$moneda->getId()}}">
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-3">
                            <select id="select2-monedas" name="moneda_select" class="form-control" style="width: 100%">
                                <option value="{{$moneda->getId()}}">{{$moneda->getDescripcion()}}</option>
                            </select>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio*</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{old('valor_cambio', $cambio)}}" readonly>
                        </div>
                        <label for="comentario" class="col-md-1 control-label">Comentario</label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="2" id="comentario" name="comentario"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="lista_precio_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%">
                            </select>
                        </div>
                        <input type="hidden" id="indi_exis" name="indi_exis" class="form-control">
                        <div class="col-md-1">
                            <a data-toggle="tooltip" data-placement="top" title="Cantidad"><input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cant." onchange="calcularSubtotal()" onkeyup="calcularSubtotal()"></a>
                        </div>
                        <input type="hidden" id="existencia" name="existencia">
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Precio Unitario"><input type="text" id="precio_unitario" name="precio_unitario" class="form-control" placeholder="Precio Unitario" onchange="calcularSubtotal()" readonly></a>
                        </div>
                        <div class="col-md-1">
                            <a data-toggle="tooltip" data-placement="top" title="% Descuento">
                            <input type="number" id="porcentaje_descuento" name="porcentaje_descuento" class="form-control" placeholder="% Desc." min="0" max="100" onchange="calcularSubtotal()"></a>

                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Subtotal">
                            <input type="text" id="subtotal" name="subtotal" class="form-control" placeholder="Subtotal" readonly></a>
                        </div>
                        <input type="hidden" id="porcentaje_iva" name="porcentaje_iva" class="form-control">
                        <div class="col-md-1">
                            <a id="btn-add-articulo" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir al pedido" onclick="addArticulo()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <span class="help-block with-errors"></span>

                    <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Artículo</th>
                                <th width="6%">Cant.</th>
                                <th width="9%">Precio U.</th>
                                <th width="9%">Descuento</th>
                                <th width="9%">Exenta</th>
                                <th width="9%">Gravada</th>
                                <th width="6%">IVA</th>
                                <th width="9%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_articulo_id'))->count(); $i++)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{old('tab_articulo_nombre.'.$i)}}</td>
                                        <td>{{old('tab_cantidad.'.$i)}}</td>
                                        <td>{{old('tab_precio_unitario.'.$i)}}</td>
                                        <td>{{old('tab_monto_descuento.'.$i)}}</td>
                                        <td>{{old('tab_exenta.'.$i)}}</td>
                                        <td>{{old('tab_gravada.'.$i)}}</td>
                                        <td>{{old('tab_iva.'.$i)}}</td>
                                        <td>{{old('tab_subtotal.'.$i)}}</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th class="total">0</th>
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

@endsection