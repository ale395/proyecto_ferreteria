@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
    <form method="post" action="{{action('AjusteInventarioController@update', $ajuste_inventario_cab->getId())}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Ajuste de inventario
                    <div class="pull-right btn-group">
                        <button data-toggle="tooltip" data-placement="top" title="Guardar" type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Cancelar edicion"  href="{{route('ajustesInventarios.create')}}" type="button" class="btn btn-warning"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('ajustesInventarios.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                    <input type="hidden" id="id" name="id" name="id" value="{{$ajuste_inventario_cab->getId()}}">
                    <div class="form-group">
                        <label for="nro_ajuste" class="col-md-1 control-label">Número Ajuste</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_ajuste" name="nro_ajuste" class="form-control" value="{{old('nro_ajuste', $nro_ajuste)}}">
                        </div>

                        <label for="fecha_emision" class="col-md-1 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div>   
                        <label for="sucursal_id" class="col-md-1 control-label">Sucursal *</label>
                        <div class="col-md-3">
                            <select id="select2-sucursales" name="sucursal_id" class="form-control" style="width: 100%">
                                <option value="{{$sucursal->getId()}}">{{$sucursal->getNombre()}}</option>
                            </select>
                        </div>                
                    </div>
                    <label for="concepto_ajuste_id" class="col-md-1 control-label">Concepto de Ajuste *</label>
                          <div class="col-md-5">
                            <select id="select2-conceptosAjustes" name="concepto_ajuste_id" class="form-control" style="width: 100%">
                            <option></option>
                                @foreach($concepto_ajuste as $id => $concepto_ajuste)
                                  <option value="{{ $concepto_ajuste->id }}"> {{ $concepto_ajuste->descripcion }}</option>
                                @endforeach
                            </select>    
                        </div>
                    <div class="form-group">
                       
                       <label for="motivo" class="col-md-1 control-label">Observacion</label>
                       <div class="col-md-4">
                           <textarea class="form-control" rows="2" id="motivo" name="motivo"></textarea>
                       </div>
                   </div>
                    <div class="form-group">
                         <label class="col-md-1 control-label">Registrado por: </label>
                         <div class="col-md-5">
                            <h4>{{ Auth::user()->name }}</h4>
                         </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="lista_precio_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%" >

                            </select>
                        </div>
                        <div class="col-md-2">
                             <input type="text" id="existencia" name="existencia" class="form-control" placeholder="existencia">
                        </div>
                        <div class="col-md-2">
                             <input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad" onchange="calcularSubtotal()" onkeyup="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="costo_unitario" name="costo_unitario" class="form-control" placeholder="Costo Unitario" onchange="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="subtotal" name="subtotal" class="form-control" placeholder="Subtotal" readonly>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir al pedido" onclick="addArticulo()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <span class="help-block with-errors"></span>

                    <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Artículo</th>
                                <th width="6%">Existencia</th>
                                <th width="6%">Cant.</th>
                                <th width="9%">Costo U.</th>
                                <th width="9%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_articulo_id'))->count(); $i++)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{old('tab_articulo_nombre.'.$i)}}</td>
                                        <td>{{old('tab_existencia.'.$i)}}</td>
                                        <td>{{old('tab_cantidad.'.$i)}}</td>
                                        <td>{{old('tab_costo_unitario.'.$i)}}</td>
                                        <td>{{old('tab_subtotal.'.$i)}}</td>
                                    </tr>
                                @endfor
                                @else
                                @foreach ($pedido_cab->pedidosDetalle as $pedido_det)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{$ajuste_inventario_det->articulo->getNombreSelect()}}</td>
                                        <td>{{$ajuste_inventario_det->getExistencia()}}</td>
                                        <td>{{$ajuste_inventario_det->getCantidad()}}</td>
                                        <td>{{$ajuste_inventario_det->getCostoUnitario()}}</td>
                                        <td>{{$pedido_det->getMontoTotal()}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
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
                                <th width="6%">Exist.</th>
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
                                        <th><input type="text" name="tab_existencia[]" value="{{old('tab_existencia.'.$i)}}"></th>
                                        <th><input type="text" name="tab_cantidad[]" value="{{old('tab_cantidad.'.$i)}}"></th>
                                        <th><input type="text" name="tab_costo_unitario[]" value="{{old('tab_costo_unitario.'.$i)}}"></th>
                                        <th><input type="text" name="tab_subtotal[]" value="{{old('tab_subtotal.'.$i)}}"></th>
                                    </tr>
                                @endfor
                                @else
                                @foreach ($pedido_cab->pedidosDetalle as $pedido_det)
                                    <tr>
                                        <th><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></th>
                                        <th><input type="text" name="tab_articulo_id[]" value="{{$ajuste_inventario_det->articulo->getId()}}"></th>
                                        <th><input type="text" name="tab_articulo_nombre[]" value="{{$ajuste_inventario_det->articulo->getDescripcion()}}"></th>
                                        <th><input type="text" name="tab_cantidad[]" value="{{$ajuste_inventario_det->getCantidad()}}"></th>
                                        <th><input type="text" name="tab_costo_unitario[]" value="{{$ajuste_inventario_det->getCostoUnitario()}}"></th>
                                        <th><input type="text" name="tab_subtotal[]" value="{{$pedido_det->getMontoTotal()}}"></th>
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
@include('cliente.create-persona-fisica')
@include('cliente.create-persona-juridica')

@endsection
@section('otros_scripts')
<script type="text/javascript">

    $('[data-toggle="tooltip"]').tooltip({
        trigger : 'hover'
    });

    function addForm() {
        $.confirm({
            title: 'Tipo de Persona',
            content: 'Por favor seleccione el tipo de persona a registrar',
            type: 'blue',
            backgroundDismiss: true,
            theme: 'modern',
            buttons: {
                confirm: {
                    text: "Física",
                    btnClass: 'btn-blue',
                    action: function(){
                        save_method = "add";
                        $('#error-block').hide();
                        $('input[name=_method]').val('POST');
                        $('#tipo_persona_fisica').val('F');
                        $('#modal-form-fisica').modal('show');
                        $('#modal-form-fisica form')[0].reset();
                        $('.modal-title').text('Nuevo Cliente - Persona Física');
                    }
                },
                cancel: {
                    text: "Jurídica",
                    btnClass: 'btn-default',
                    action: function(){
                        save_method = "add";
                        $('#error-block-juridica').hide();
                        $('input[name=_method]').val('POST');
                        $('#tipo_persona_juridica').val('J');
                        $('#modal-form-juridica').modal('show');
                        $('#modal-form-juridica form')[0].reset();
                        $('.modal-title').text('Nuevo Cliente - Persona Jurídica');
                    }
                }
            }
        });
    }

    $(function(){
            $('#modal-form-fisica form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('clientes') }}";
                    else url = "{{ url('clientes') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form-fisica form').serialize(),
                        success : function($data) {
                            $('#modal-form-fisica').modal('hide');
                            var data = {
                                id: $data.id,
                                text: $data.nro_cedula + ' - ' + $data.nombre + ', ' + $data.apellido
                            };
                            $('#select2-clientes').val(null).trigger('change');
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#select2-clientes').append(newOption).trigger('change');
                            var obj = $.alert({
                                title: 'Información',
                                content: 'Cliente guardado correctamente!',
                                icon: 'fa fa-check',
                                type: 'green',
                                backgroundDismiss: true,
                                theme: 'modern',
                            });
                            setTimeout(function(){
                                obj.close();
                            },4000); 
                        },
                        error : function(data){
                            var errors = '';
                            var errores = '';
                            if(data.status === 422) {
                                var errors = data.responseJSON;
                                $.each(data.responseJSON.errors, function (key, value) {
                                    errores += '<li>' + value + '</li>';
                                });
                                $('#error-block').show().html(errores);
                            }else{
                              $.alert({
                              title: 'Atención!',
                              content: 'Ocurrió un error durante el proceso!',
                              icon: 'fa fa-times-circle-o',
                              type: 'red',
                              theme: 'modern',
                            });
                          }
                            
                        }
                    });
                    return false;
                }
            });
        });

    $(function(){
            $('#modal-form-juridica form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('clientes') }}";
                    else url = "{{ url('clientes') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form-juridica form').serialize(),
                        success : function($data) {
                            $('#modal-form-juridica').modal('hide');
                            var data = {
                                id: $data.id,
                                text: $data.ruc + ' - ' + $data.razon_social
                            };
                            $('#select2-clientes').val(null).trigger('change');
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#select2-clientes').append(newOption).trigger('change');

                            var obj = $.alert({
                                title: 'Información',
                                content: 'Cliente guardado correctamente!',
                                icon: 'fa fa-check',
                                type: 'green',
                                backgroundDismiss: true,
                                theme: 'modern',
                            });
                            setTimeout(function(){
                                obj.close();
                            },4000);
                        },
                        error : function(data){
                            var errors = '';
                            var errores = '';
                            if(data.status === 422) {
                                var errors = data.responseJSON;
                                $.each(data.responseJSON.errors, function (key, value) {
                                    errores += '<li>' + value + '</li>';
                                });
                                $('#error-block-juridica').show().html(errores);
                            }else{
                                $.alert({
                                  title: 'Atención!',
                                  content: 'Ocurrió un error durante el proceso!',
                                  icon: 'fa fa-times-circle-o',
                                  type: 'red',
                                  theme: 'modern',
                                });
                          }
                            
                        }
                    });
                    return false;
                }
            });
        });

    $("#btn-add-articulo").attr("disabled", true);
    var table = $('#pedido-detalle').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
          { className: "dt-center", "targets": [0,2,3,4,5,6,7,8] },
          { className: "dt-left", "targets": [1] }
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
                .column(8)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 8 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });

    /*Evento onchange del select de artículos, para que recupere el precio si es seleccionado algún artículo distinto a nulo*/
    $("#select2-articulos").change(function (e) {
        var valor = $(this).val();
        
        if (valor != null) {
            var articulo_id = $("#select2-articulos" ).val();
            var lista_precio_id = $("#select2-lista-precios" ).val();
            $.ajax({
              type: "GET",
              url: "{{ url('api/articulos') }}" + '/cotizacion/' + articulo_id + '/' + lista_precio_id,
              datatype: "json",
              success: function(data){
                $("#porcentaje_iva" ).val(data.iva.porcentaje).change();
                $("#precio_unitario" ).val(data.precio).change();
                $("#porcentaje_descuento" ).val(0).change();
                $("#btn-add-articulo").attr("disabled", false);
              }
            });

            $("#cantidad" ).val(1).change();
            $("#cantidad").focus();
        } else {
            $("#btn-add-articulo").attr("disabled", true);
        }
    });

    function calcularSubtotal() {
        var cantidad = $("#cantidad" ).val();
        var precio_unitario = $("#precio_unitario" ).val();
        var porcentaje_descuento = $("#porcentaje_descuento" ).val();
        cantidad = cantidad.replace(".", "");
        precio_unitario = precio_unitario.replace(".", "");
        var calculo = cantidad * (precio_unitario - (precio_unitario * (porcentaje_descuento/100)));
        if($("#cantidad" ).val().length != 0 && $("#precio_unitario" ).val().length != 0){
            $("#subtotal" ).val(calculo).change();
        }
    };

    function addArticulo() {
        /*Se obtienen los valores de los campos correspondientes*/
        var decimales = 0;
        var articulo = $('#select2-articulos').select2('data')[0].text;
        var articulo_id = $('#select2-articulos').select2('data')[0].id;
        var cantidad = $("#cantidad").val();
        var precio_unitario = $("#precio_unitario").val();
        var porcentaje_descuento = $("#porcentaje_descuento" ).val();
        var monto_descuento = cantidad * precio_unitario.replace(".", "") * (porcentaje_descuento/100);
        var subtotal = $("#subtotal").val();
        var porcentaje_iva = $("#porcentaje_iva" ).val();
        var exenta = 0;
        var gravada = 0;
        var iva = 0;
        if (porcentaje_iva == 0) {
            exenta = subtotal;
        } else {
            gravada = Math.round(subtotal/((porcentaje_iva/100)+1));
            iva = Math.round(gravada*(porcentaje_iva/100));
        }
        /*Se le da formato numérico a los valores. Separador de miles y la coma si corresponde*/
        precio_unitario = $.number(precio_unitario,decimales, ',', '.');
        cantidad = $.number(cantidad,decimales, ',', '.');
        monto_descuento = $.number(monto_descuento,decimales, ',', '.');
        exenta = $.number(exenta,decimales, ',', '.');
        gravada = $.number(gravada,decimales, ',', '.');
        iva = $.number(iva,decimales, ',', '.');
        subtotal = $.number(subtotal,decimales, ',', '.');  
        
        /*Se agrega una fila a la tabla*/
        var tabla = $("#pedido-detalle").DataTable();
        tabla.row.add( [
            "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a>",
            articulo,
            cantidad,
            precio_unitario,
            monto_descuento,
            exenta,
            gravada,
            iva,
            subtotal
        ] ).draw( false );

        var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' name='tab_articulo_id[]' value='" + articulo_id + "'></th> <th> <input type='text' name='tab_articulo_nombre[]' value='" + articulo + "'></th> <th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_precio_unitario[]' value='" + precio_unitario + "'></th> <th> <input type='text' name='tab_porcentaje_descuento[]' value='" + porcentaje_descuento + "'></th> <th> <input type='text' name='tab_monto_descuento[]' value='" + monto_descuento + "'></th> <th> <input type='text' name='tab_porcentaje_iva[]' value='" + porcentaje_iva + "'></th> <th> <input type='text' name='tab_exenta[]' value='"+ exenta +"'> </th> <th> <input type='text' name='tab_gravada[]' value='"+ gravada +"'> </th> <th> <input type='text' name='tab_iva[]' value='"+ iva +"'> </th> <th> <input type='text' name='tab_subtotal[]' value='" + subtotal + "'> </th> </tr>";
        $("#tab-hidden").append(markup);

        /*Se restauran a nulos los valores del bloque para la selección del articulo*/
        $('#cantidad').number(false);
        $('#precio_unitario').number(false);
        $('#subtotal').number(false);
        
        $('#cantidad').val("");
        $('#precio_unitario').val("");
        $('#porcentaje_descuento').val("");
        $('#porcentaje_iva').val("");
        $('#subtotal').val("");
        $('#select2-articulos').val(null).trigger('change');
        $("#select2-articulos").focus();
    };

    /*Elimina el articulo del pedido*/
    var tabla = $("#pedido-detalle").DataTable();
    $('#pedido-detalle tbody').on( 'click', 'a.btn-delete-row', function () {
        var row = $(this).closest('tr').index();
        row = row + 1;
        tabla
            .row( $(this).parents('tr') )
            .remove()
            .draw();
        $("#tab-hidden tr:eq("+row+")").remove();
    } );

</script>
<script type="text/javascript">
    //JS para que al abrir el modal de persona fisica se quede en foco en el campo nro_cedula
    $('#modal-form-fisica').on('shown.bs.modal', function() {
      $("#nro_cedula").focus();
    });
    //JS para que al abrir el modal de persona jurídica se quede en foco en el campo ruc
    $('#modal-form-juridica').on('shown.bs.modal', function() {
      $("#ruc_juridica").focus();
    });
    $('#cliente-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
    $('#cliente-form-juridica').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
    //JS para el formato con separador de miles al ingresar el nro de cedula
    $('#nro_cedula').number(true, 0, ',', '.');
    /*JS para la inclusión del guión en el campo RUC del modal para persona física*/
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
    /*JS para la inclusión del guión en el campo RUC del modal para persona jurídica*/
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
    $('#valor_cambio').number(true, 0, ',', '.');
    $('#cantidad').number(true, 2, ',', '.');
    $('#precio_unitario').number(true, 0, ',', '.');
    $('#subtotal').number(true, 0, ',', '.');
</script>

<script type="text/javascript">
    /*JS del componente Select2*/
    $(document).ready(function(){
        $('#select2-clientes').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 4,
            ajax: {
                url: "{{ route('api.clientes.ventas') }}",
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
        $('#select2-estados').select2({
            placeholder: 'Seleccione una opción',
            language: "es"
        });
    });

    /*JS para el DatePicker de fecha_emision*/
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