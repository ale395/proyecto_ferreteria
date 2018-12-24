@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
    <form method="post" action="{{action('AjusteInventarioController@update', $ajuste_inventario_cab->getId())}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4>Editar Ajuste
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
                    <input type="hidden" id="id" name="id"  value="{{$ajuste_inventario_cab->getId()}}">
                    <div class="form-group">
                        <label for="nro_ajuste" class="col-md-1 control-label">Número Ajuste</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_ajuste" name="nro_ajuste" class="form-control" value="{{$ajuste_inventario_cab->getId()}}">
                        </div>
                        <label for="fecha_emision" class="col-md-1 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{$ajuste_inventario_cab->getFechaEmision()}}" data-inputmask="'mask': '99/99/9999'">
                        </div>   
                        <label for="sucursal_id" class="col-md-1 control-label">Sucursal *</label>
                        <div class="col-md-3">
                            <select id="select2-sucursales" name="sucursal_id" class="form-control" style="width: 100%">
                                <option value="{{$ajuste_inventario_cab->sucursal->getId()}}" selected>{{$ajuste_inventario_cab->sucursal->getNombre()}}</option>
                            </select>
                        </div> 
                     </div> 
                        <label for="concepto_ajuste_id" class="col-md-1 control-label">Concepto de Ajuste *</label>
                        <div class="col-md-5">
                            <select id="select2-conceptosAjustes" name="concepto_ajuste_id" class="form-control" style="width: 100%">
                            <option></option>
                                  <option value="{{$ajuste_inventario_cab->conceptoAjuste->getDescripcion()}}"  selected>{{$ajuste_inventario_cab->conceptoAjuste->getDescripcion()}}</option>
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
                        <label for="articulo_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%">
                            </select>
                        </div>
                        <div class="col-md-1">
                             <input type="text" id="existencia" name="existencia" class="form-control" placeholder="existencia">
                         </div>   
                         <div class="col-md-1">
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
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{old('tab_articulo_nombre.'.$i)}}</td>
                                        <td>{{old('tab_existencia.'.$i)}}</td>
                                        <td>{{old('tab_cantidad.'.$i)}}</td>
                                        <td>{{old('tab_costo_unitario.'.$i)}}</td>
                                        <td>{{old('tab_subtotal.'.$i)}}</td>
                                    </tr>
                                @endfor
                            @else
                            @foreach ($ajuste_inventario_cab->ajusteInventarioDetalle as $ajuste_inventario_det)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{$ajuste_inventario_det->articulo->getNombreSelect()}}</td>
                                        <td>{{$ajuste_inventario_det->getExistencia()}}</td>
                                        <td>{{$ajuste_inventario_det->getCantidad()}}</td>
                                        <td>{{$ajuste_inventario_det->getCostoUnitario()}}</td>
                                        <td>{{$ajuste_inventario_det->getSubTotal()}}</td>
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
                                @foreach ($ajuste_inventario_cab->ajusteInventarioDetalle as $ajuste_inventario_det)
                                    <tr> 
                                    <th><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></th>
                                        <th><input type="text" name="tab_articulo_id[]" value="{{$ajuste_inventario_det->articulo->getId()}}"></th>
                                        <th><input type="text" name="tab_articulo_nombre[]" value="{{$ajuste_inventario_det->articulo->getDescripcion()}}"></th>
                                        <th><input type="text" name="tab_cantidad[]" value="{{$ajuste_inventario_det->getCantidad()}}"></th>
                                        <th><input type="text" name="tab_costo_unitario[]" value="{{$ajuste_inventario_det->getCostoUnitario()}}"></th>
                                        <th><input type="text" name="tab_subtotal[]" value="{{$ajuste_inventario_det->getSubTotal()}}"></th>
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
       /*Evento onchange del select de artículos, para que recupere el costo si es seleccionado algún artículo distinto a nulo*/
       $("#select2-articulos").change(function (e) {
        var valor = $(this).val();
        
        if (valor != null) {
            var articulo_id = $("#select2-articulos" ).val();
            $.ajax({
                type: "GET",
                url: "{{ url('api/articulos') }}" + '/costo/' + articulo_id,
                datatype: "json",
                //async: false,
                success: function(data){
                    $("#costo_unitario" ).val(data.ultimo_costo).change();                    
                    $("#btn-add-articulo").attr("disabled", false);
                }
            });

            if($("#cantidad" ).val().length === 0){
                $("#cantidad" ).val(1).change();
            }
            $("#cantidad").focus();

        } else {
            $("#btn-add-articulo").attr("disabled", true);
        }
    });
 
 
    /*Evento onchange del select de artículos, para que recupere el precio si es seleccionado algún artículo distinto a nulo*/
    $("#select2-articulos").change(function (e) {
        var valor = $(this).val();
        
        if (valor != null) {
            var articulo_id = $("#select2-articulos" ).val();
            var sucursal_id= $("#select2-sucursales" ).val();
            $.ajax({
              type: "GET",
              url: "{{ url('api/articulos') }}" + '/existencia/' + articulo_id + '/' + sucursal_id,
              datatype: "json",
              success: function(data){
                $("#existencia" ).val(data.cantidad).change();
                $("#btn-add-articulo").attr("disabled", false);
              }
            });

        } else {
            $("#btn-add-articulo").attr("disabled", true);
        }
    });
    function calcularSubtotal() {
        var cantidad = $("#cantidad" ).val();
        var costo_unitario = $("#costo_unitario" ).val();
        cantidad = cantidad.replace(".", "");
        costo_unitario = costo_unitario.replace(".", "");
        var calculo = cantidad * $("#costo_unitario" ).val();
        if($("#cantidad" ).val().length != 0 && $("#costo_unitario" ).val().length != 0){
            $("#subtotal" ).val(calculo).change();
        }
    };

    function addArticulo() {
        var indexColumn = 0;
        var articulos_detalle = $('input[name="tab_articulo_id[]"]').map(function () {
            return this.value;
        }).get();

        /*Se obtienen los valores de los campos correspondientes*/
        var cantidad = $("#cantidad").val();
        var existencia = $("#existencia").val();
        console.log('Antes de add: '+articulos_detalle);

            var decimales = 0;
            var articulo = $('#select2-articulos').select2('data')[0].text;
            var articulo_id = $('#select2-articulos').select2('data')[0].id;
            if (articulos_detalle.includes(articulo_id)) {
                var obj = $.alert({
                    title: 'Atención',
                    content: 'El artículo que intenta agregar ya está incluido en el detalle de la factura!',
                    icon: 'fa fa-exclamation-triangle',
                    type: 'orange',
                    backgroundDismiss: true,
                    theme: 'modern',
                });
                setTimeout(function(){
                    obj.close();
                },3000); 
            } else {
                articulos_detalle.push(articulo_id);
                console.log('Despues de add: '+articulos_detalle);
            var costo_unitario = $("#costo_unitario").val();
            var subtotal = $("#subtotal").val();
            /*Se le da formato numérico a los valores. Separador de miles y la coma si corresponde*/
            existencia = $.number(existencia,decimales, ',', '.');
            costo_unitario = $.number(costo_unitario,decimales, ',', '.');
            cantidad = $.number(cantidad,decimales, ',', '.');
            subtotal = $.number(subtotal,decimales, ',', '.');  
            
            /*Se agrega una fila a la tabla*/
            var tabla = $("#pedido-detalle").DataTable();
            tabla.row.add( [
                "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>",
                articulo,
                existencia,
                cantidad,
                costo_unitario,
                subtotal
            ] ).draw( false );

            var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' id='tab_articulo_id' name='tab_articulo_id[]' value='" + articulo_id + "'></th> <th> <input type='text' name='tab_articulo_nombre[]' value='" + articulo + "'></th><th><input type='text' name='tab_existencia[]' value='" + existencia + "'></th><th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_costo_unitario[]' value='" + costo_unitario + "'></th> </th> <th> <input type='text' name='tab_subtotal[]' value='" + subtotal + "'> </th> </tr>";
            $("#tab-hidden").append(markup);

            /*Se restauran a nulos los valores del bloque para la selección del articulo*/
            $('#cantidad').number(false);
            $('#costo_unitario').number(false);
            $('#subtotal').number(false);
            $('#existencia').val("");
            $('#cantidad').val("");
            $('#costo_unitario').val("");
            $('#porcentaje_descuento').val("");
            $('#porcentaje_iva').val("");
            $('#subtotal').val("");
            $('#select2-articulos').val(null).trigger('change');
            $("#select2-articulos").focus();
           }

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
                .column(5)//se refiere a la columna del datatable donde está el sub-total
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5).footer() ).html(
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

</script>
<script type="text/javascript">
   $(document).ready(function(){
    $('#select2-conceptosAjustes').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            ajax: {
                url: "{{ route('api.conceptosAjustes.buscador') }}",
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
        $('#select2-sucursales').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            ajax: {
                url: "{{ route('api.sucursales.buscador') }}",
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