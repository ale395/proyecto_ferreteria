@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="put" action="{{action('OrdenCompraController@update', $orden_compra->id)}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Orden de Compra
                    <div class="pull-right btn-group">
                        <button data-toggle="tooltip" data-placement="top" title="Guardar" type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Cancelar Edición" href="{{route('ordencompra.index')}}" type="button" class="btn btn-warning"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('ordencompra.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>                    
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
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id" value="{{$orden_compra->id}}">
                    <div class="form-group">
                        <label for="nro_orden" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_orden" name="nro_orden" class="form-control"  value="{{$orden_compra->nro_orden}}" readonly="readonly">
                        </div>
                        <label for="fecha_emision" class="col-md-5 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{$orden_compra->fecha_emision}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="proveedor_id" class="col-md-1 control-label">Proveedor *</label>
                        <div class="col-md-5">
                            <select id="select2-proveedores" name="proveedor_id" class="form-control" autofocus style="width: 100%">
                                <option value="{{$orden_compra->proveedor_id}}">{{$orden_compra->proveedor->codigo.' - '.$orden_compra->proveedor->razon_social}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-3">
                            <select id="select2-monedas" name="moneda_id" class="form-control" style="width: 100%">
                                <option value="{{$orden_compra->moneda->getId()}}">{{$orden_compra->moneda->getDescripcion()}}</option>
                            </select>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio*</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{$orden_compra->valor_cambio}}">
                        </div>
                    </div>
                    <div class="form-group">
                         <label for="estado" class="col-md-1 control-label">Estado</label>
                        <div class="col-md-2">
                            <select id="select2-estados" name="estado" class="form-control" style="width: 100%">
                                @if ($orden_compra->estado == 'A')
                                    <option value="P" selected>Pendiente</option>
                                    <option value="C">Cancelado</option>
                                @elseif ($orden_compra->estado == 'C')
                                    <option value="P">Pendiente</option>
                                    <option value="C" selected>Cancelado</option>
                                @endif
                                <option value="F" disabled>Facturado</option>
                            </select>
                        </div>  
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="lista_precio_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%" onchange="setCantidadCosto()">

                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad" onchange="calcularSubtotal()" onkeyup="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="costo_unitario" name="costo_unitario" class="form-control" placeholder="Costo Unitario" onchange="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="subtotal" name="subtotal" class="form-control" placeholder="Total Articulo" readonly>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir al pedido" onclick="addArticulo()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Artículo</th>
                                <th width="6%">Cant.</th>
                                <th width="9%">Precio U.</th>
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
                                        <td>{{old('tab_costounitario.'.$i)}}</td>>
                                        <td>{{old('tab_subtotal.'.$i)}}</td>
                                    </tr>
                                @endfor
                            @else
                                @foreach ($orden_compra->ordenCompraDetalle as $pedido_det)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{$pedido_det->articulo->getNombreSelect()}}</td>
                                        <td>{{$pedido_det->getCantidadNumber()}}</td>
                                        <td>{{$pedido_det->getCostoUnitario()}}</td>
                                        <td>{{$pedido_det->getSubTotal()}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th class="total">0</th>
                            </tr>
                        </tfoot>
                    </table>

                    <table id="tab-hidden" class="hidden">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Artículo ID</th>
                                <th>Nombre Artículo</th>
                                <th width="6%">Cant.</th>
                                <th width="9%">Costo U.</th>
                                <th width="9%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_articulo_id'))->count(); $i++)
                                    <tr>
                                        <th><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a></th>
                                        <th><input type="text" name="tab_articulo_id[]" value="{{old('tab_articulo_id.'.$i)}}"></th>
                                        <th><input type="text" name="tab_articulo_nombre[]" value="{{old('tab_articulo_nombre.'.$i)}}"></th>
                                        <th><input type="text" name="tab_cantidad[]" value="{{old('tab_cantidad.'.$i)}}"></th>
                                        <th><input type="text" name="tab_costounitario[]" value="{{old('tab_costounitario.'.$i)}}"></th>
                                        <th><input type="text" name="tab_subtotal[]" value="{{old('tab_subtotal.'.$i)}}"></th>
                                    </tr>
                                @endfor
                            @else
                                @foreach ($orden_compra->ordenCompraDetalle as $pedido_det)
                                    <tr>
                                        <th><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></th>
                                        <th><input type="text" name="tab_articulo_id[]" value="{{$pedido_det->articulo->getId()}}"></th>
                                        <th><input type="text" name="tab_articulo_nombre[]" value="{{$pedido_det->articulo->getDescripcion()}}"></th>
                                        <th><input type="text" name="tab_cantidad[]" value="{{$pedido_det->getCantidad()}}"></th>
                                        <th><input type="text" name="tab_costounitario[]" value="{{$pedido_det->getCostoUnitario()}}"></th>
                                        <th><input type="text" name="tab_subtotal[]" value="{{$pedido_det->getSubTotal()}}"></th>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('otros_scripts')
<script type="text/javascript">
 
    function setCantidadCosto() {
        var articulo_id = $("#select2-articulos" ).val();
        $.ajax({
          type: "GET",
          url: "{{ url('api/articulos') }}" + '/costo/' + articulo_id,
          datatype: "json",
          //async: false,
          success: function(data){
            $("#costo_unitario" ).val(data).change();
          }
        });

        if($("#cantidad" ).val().length === 0){
            $("#cantidad" ).val(1).change();
        }
        $("#cantidad").focus();
    };

    function calcularSubtotal() {
        var cantidad = $("#cantidad" ).val();
        cantidad = cantidad.replace(".", "");
        var calculo = cantidad * $("#costo_unitario" ).val();
        if($("#cantidad" ).val().length != 0 && $("#costo_unitario" ).val().length != 0){
            $("#subtotal" ).val(calculo).change();
        }
    };

     function addArticulo() {
        /*Se obtienen los valores de los campos correspondientes*/
        var decimales = 0;
        var articulo = $('#select2-articulos').select2('data')[0].text;
        var articulo_id = $('#select2-articulos').select2('data')[0].id;
        var cantidad = $("#cantidad").val();
        var precio_unitario = $("#costo_unitario").val();
        var subtotal = $("#subtotal").val();

        /*Se le da formato numérico a los valores. Separador de miles y la coma si corresponde*/
        precio_unitario = $.number(precio_unitario,decimales, ',', '.');
        cantidad = $.number(cantidad,decimales, ',', '.');
        subtotal = $.number(subtotal,decimales, ',', '.');  
        
        /*Se agrega una fila a la tabla*/
        var tabla = $("#pedido-detalle").DataTable();
        tabla.row.add( [
            "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>",
            articulo,
            cantidad,
            precio_unitario,
            subtotal
        ] ).draw( false );

        var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' name='tab_articulo_id[]' value='" + articulo_id + "'></th> <th> <input type='text' name='tab_articulo_nombre[]' value='" + articulo + "'></th> <th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_costounitario[]' value='" + precio_unitario + "'></th> </th> <th> <input type='text' name='tab_subtotal[]' value='" + subtotal + "'> </th> </tr>";
        $("#tab-hidden").append(markup);

        /*Se restauran a nulos los valores del bloque para la selección del articulo*/
        $('#cantidad').number(false);
        $('#costo_unitario').number(false);
        $('#subtotal').number(false);
        
        $('#cantidad').val("");
        $('#costo_unitario').val("");
        $('#porcentaje_descuento').val("");
        $('#porcentaje_iva').val("");
        $('#subtotal').val("");
        $('#select2-articulos').val(null).trigger('change');
        $("#select2-articulos").focus();
    };

    $("#btn-add-articulo").attr("disabled", true);
    var table = $('#pedido-detalle').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            var decimales = 0;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(".", "")*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column(4)//se refiere a la columna del datatable donde está el sub-total
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });


    /*Elimina el articulo del pedido*/
    var tabla = $("#pedido-detalle").DataTable();
    $('#pedido-detalle tbody').on( 'click', 'a.btn-delete-row', function () {
        var row = $(this).parent().index('#pedido-detalle tbody tr');
        tabla
            .row( $(this).parents('tr') )
            .remove()
            .draw();
        $("#tab-hidden tr:eq("+row+")").remove();
    } );
</script>
<script type="text/javascript">
    /*
    $('#modal-form-fisica').on('shown.bs.modal', function() {
      $("#nro_cedula").focus();
    });

    $('#modal-form-juridica').on('shown.bs.modal', function() {
      $("#ruc_juridica").focus();
    });
    $('#cliente-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
    $('#cliente-form-juridica').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
    $("#nro_cedula").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
    });
    */
    $("#cantidad").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
            });
        }
    });

    $("#ruc").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                            .replace(/([0-9])([0-9]{1})$/, '$1-$2');
            });
        }
    });

    $("#ruc_juridica").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                            .replace(/([0-9])([0-9]{1})$/, '$1-$2');
            });
        }
    });
    
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#select2-proveedores').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 4,
            ajax: {
                url: "{{ route('api.proveedores.buscador') }}",
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
        $('#select2-articulos').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 4,
            ajax: {
                url: "{{ route('api.articulos.ventas') }}",
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
        $('#select2-monedas').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            ajax: {
                url: "{{ route('api.monedas.select') }}",
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

        /*
        $('#select2-lista-precios').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            ajax: {
                url: "{{ route('api.listaPrecios.select') }}",
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
        */

    });

    $(function() {
      $('.dpfecha').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        todayBtn: true,
        todayHighlight: true,
        autoclose: true
      });
      $('#fecha_emision').click(function(e){
                e.stopPropagation();
                $('.dpfecha').datepicker('update');
            });  
    });
</script>
@endsection