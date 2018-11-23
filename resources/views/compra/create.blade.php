@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('CompraController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Compra
                    <div class="pull-right btn-group">
                        <button data-toggle="tooltip" data-placement="top" title="Guardar" type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Cancelar carga" href="{{route('compra.create')}}" type="button" class="btn btn-warning"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('compra.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                        <label for="tipo_factura" class="col-md-1 control-label">Tipo Fac.</label>
                        <div class="col-md-3">
                            <div id="tipo_factura" class="btn-group" data-toggle="buttons">
                                @if(old('tipo_factura') === 'CR')
                                    <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input id="radioContado" type="radio" name="tipo_factura" value="CO">Contado</label>
                                    <label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input type="radio" name="tipo_factura" value="CR" checked> Crédito</label>
                                @else
                                    <label class="btn btn-default active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input id="radioContado" type="radio" name="tipo_factura" value="CO" checked>&nbsp;Contado&nbsp;</label>
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input type="radio" name="tipo_factura" value="CR"> Crédito </label>
                                @endif
                            </div>
                        </div>
                        <label for="nro_factura" class="col-md-2 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_factura" name="nro_factura" class="form-control text-right" readonly="readonly" value="{{old('nro_factura')}}" >  
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fecha_emision" class="col-md-1 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                        <!--
                        <div class="col-md-1">
                            <a onclick="addForm()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Buscar Pedido"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                        -->
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Proveedor *</label>
                        <div class="col-md-7">
                            <select id="select2-proveedores" name="proveedor_id" class="form-control" autofocus style="width: 100%">
                                @if ($errors->any())
                                    @foreach($proveedores as $proveedor)
                                        @if(old('proveedor_id') == $proveedor->getId())
                                            <option value="{{old('cliente_id')}}" selected>{{$proveedor->getNombreSelect()}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-3">
                            <select id="select2-monedas" name="moneda_id" class="form-control" style="width: 100%" onchange="setValorCambio()>
                                <option value="{{$moneda->getId()}}">{{$moneda->getDescripcion()}}</option>
                            </select>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio*</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{old('valor_cambio')}}">
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
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home">Detalle</a></li>
                            <li><a data-toggle="tab" href="#menu1">Formas de Pago</a></li>
                            <li><a data-toggle="tab" href="#menu2">Cuotas</a></li>
                        </ul>

                        <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            
                            <div class="form-group">
                                <br>
                                <label for="lista_costo_id" class="col-md-1 control-label">Artículo</label>
                                <div class="col-md-4">
                                    <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%" >

                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <a data-toggle="tooltip" data-placement="top" title="Cantidad"><input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cant." onchange="calcularSubtotal()" onkeyup="calcularSubtotal()"></a>
                                </div>
                                <input type="hidden" id="existencia" name="existencia">
                                <div class="col-md-2">
                                    <a data-toggle="tooltip" data-placement="top" title="Costo Unitario"><input type="text" id="costo_unitario" name="costo_unitario" class="form-control" placeholder="Costo Unitario" onchange="calcularSubtotal()" readonly></a>
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
                                    <a id="btn-add-articulo" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir a la factura" onclick="addArticulo()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <span class="help-block with-errors"></span>
                 
                            <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="5%">Acción</th>
                                        <th>Artículo</th>
                                        <th width="6%">Cant.</th>
                                        <th width="9%">Costo U.</th>
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
                                                <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                                <td>{{old('tab_articulo_nombre.'.$i)}}</td>
                                                <td>{{old('tab_cantidad.'.$i)}}</td>
                                                <td>{{old('tab_costo_unitario.'.$i)}}</td>
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

                            <table id="tab-hidden" class="hidden">
                                <thead>
                                    <tr>
                                        <th width="5%">Acción</th>
                                        <th>Artículo ID</th>
                                        <th>Nombre Artículo</th>
                                        <th width="6%">Cant.</th>
                                        <th width="9%">Costo U.</th>
                                        <th width="9%">% Descuento</th>
                                        <th width="9%">Monto Descuento</th>
                                        <th width="9%">% IVA</th>
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
                                                <th><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></th>
                                                <th><input type="text" id="tab_articulo_id" name="tab_articulo_id[]" value="{{old('tab_articulo_id.'.$i)}}"></th>
                                                <th><input type="text" name="tab_articulo_nombre[]" value="{{old('tab_articulo_nombre.'.$i)}}"></th>
                                                <th><input type="text" name="tab_cantidad[]" value="{{old('tab_cantidad.'.$i)}}"></th>
                                                <th><input type="text" name="tab_costo_unitario[]" value="{{old('tab_costo_unitario.'.$i)}}"></th>
                                                <th><input type="text" name="tab_porcentaje_descuento[]" value="{{old('tab_porcentaje_descuento.'.$i)}}"></th>
                                                <th><input type="text" name="tab_monto_descuento[]" value="{{old('tab_monto_descuento.'.$i)}}"></th>
                                                <th><input type="text" name="tab_porcentaje_iva[]" value="{{old('tab_porcentaje_iva.'.$i)}}"></th>
                                                <th><input type="text" name="tab_exenta[]" value="{{old('tab_exenta.'.$i)}}"></th>
                                                <th><input type="text" name="tab_gravada[]" value="{{old('tab_gravada.'.$i)}}"></th>
                                                <th><input type="text" name="tab_iva[]" value="{{old('tab_iva.'.$i)}}"></th>
                                                <th><input type="text" name="tab_subtotal[]" value="{{old('tab_subtotal.'.$i)}}"></th>
                                            </tr>
                                        @endfor
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <h3> En Construcción xD </h3>

                        </div>
                        <div id="menu2" class="tab-pane fade">
                        <h3> En Construcción xD </h3>

                        </div>
                        </div>
                    </div>
                    <br>

                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('otros_scripts')
<script type="text/javascript">
    
    var articulos_detalle = [];

    $('[data-toggle="tooltip"]').tooltip({
        trigger : 'hover'
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
                    $("#costo_unitario" ).val(data).change();
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

    //Función para recuperar el valor de cambio al cambiar de moneda (?)
    function setCantidadCosto() {
        var articulo_id = $("#select2-articulos" ).val();
        $.ajax({
          type: "GET",
          url: "{{ url('api/cotizaciones') }}" + '/venta/' + articulo_id,
          datatype: "json",
          //async: false,
          success: function(data){
            $("#valor_cambio" ).val(data).change();
          }
        });
    };

    function calcularSubtotal() {
        var cantidad = $("#cantidad" ).val();
        var costo_unitario = $("#costo_unitario" ).val();
        var porcentaje_descuento = $("#porcentaje_descuento" ).val();
        cantidad = cantidad.replace(".", "");
        costo_unitario = costo_unitario.replace(".", "");
        var calculo = cantidad * (costo_unitario - (costo_unitario * (porcentaje_descuento/100)));
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
                //var cantidad = $("#cantidad").val();
                var costo_unitario = $("#costo_unitario").val();
                var porcentaje_descuento = $("#porcentaje_descuento" ).val();
                var monto_descuento = cantidad * costo_unitario.replace(".", "") * (porcentaje_descuento/100);
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
                costo_unitario = $.number(costo_unitario,decimales, ',', '.');
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
                    costo_unitario,
                    monto_descuento,
                    exenta,
                    gravada,
                    iva,
                    subtotal
                ] ).draw( false );

                var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' id='tab_articulo_id' name='tab_articulo_id[]' value='" + articulo_id + "'></th> <th> <input type='text' name='tab_articulo_nombre[]' value='" + articulo + "'></th> <th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_costo_unitario[]' value='" + costo_unitario + "'></th> <th> <input type='text' name='tab_porcentaje_descuento[]' value='" + porcentaje_descuento + "'></th> <th> <input type='text' name='tab_monto_descuento[]' value='" + monto_descuento + "'></th> <th> <input type='text' name='tab_porcentaje_iva[]' value='" + porcentaje_iva + "'></th> <th> <input type='text' name='tab_exenta[]' value='"+ exenta +"'> </th> <th> <input type='text' name='tab_gravada[]' value='"+ gravada +"'> </th> <th> <input type='text' name='tab_iva[]' value='"+ iva +"'> </th> <th> <input type='text' name='tab_subtotal[]' value='" + subtotal + "'> </th> </tr>";
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
            }
    
    };

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
    /*JS del componente Select2*/
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