@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('NotaCreditoComprasController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Nota de Crédito - Compras
                    <div class="pull-right btn-group">
                        <button data-toggle="tooltip" data-placement="top" title="Guardar" type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Cancelar carga" href="{{route('notacreditocompra.create')}}" type="button" class="btn btn-warning"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <a data-toggle="tooltip" data-placement="top" title="Volver al Listado" href="{{route('notacreditocompra.index')}}" type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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
                        <label for="tipo_nota_credito" class="col-md-1 control-label">Tipo</label>
                        <div class="col-md-3">
                            <div id="tipo_nota_credito" class="btn-group" data-toggle="buttons">
                                @if(old('tipo_nota_credito') === 'DV')
                                    <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input id="radioDevolucion" type="radio" name="tipo_nota_credito" value="DV">Devolución</label>
                                    <label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input type="radio" name="tipo_nota_credito" value="DC" checked> Descuento</label>
                                @else
                                    <label class="btn btn-default active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input id="radioDevolucion" type="radio" name="tipo_nota_credito" value="DV" checked>&nbsp;Devolución&nbsp;</label>
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                    <input type="radio" name="tipo_nota_credito" value="DC"> Descuento </label>
                                @endif
                            </div>
                        </div>
                        <label for="nro_nota_credito" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_nota_credito" name="nro_nota_credito" class="form-control text-right" value="{{old('nro_nota_credito')}}" >  
                        </div>
                       
                    </div>
                    <div class="form-group">
                        <label for="fecha_emision" class="col-md-1 control-label">Fecha*</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                        <label for="timbrado" class="col-md-2 control-label">Timbrado*</label>
                        <div class="col-md-2">
                            <input type="text" id="timbrado" name="timbrado" class="form-control text-right" value="{{old('timbrado')}}" >  
                        </div>
                        <label for="fecha_vigencia_timbrado" class="col-md-1 control-label">Validez*</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_vigencia_timbrado" name="fecha_vigencia_timbrado" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_vigencia_timbrado')}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="proveedor_id" class="col-md-1 control-label">Proveedor *</label>
                        <div class="col-md-5">
                            <select id="select2-proveedores" name="proveedor_id" class="form-control" autofocus style="width: 100%">
                                @if ($errors->any())
                                    @foreach($proveedores as $proveedor)
                                        @if(old('proveedor_id') == $proveedor->id)
                                            <option value="{{old('proveedor_id')}}" selected>{{$proveedor->getNombreSelect()}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>                           
                        </div>
                        <div class="col-md-1">
                            <a onclick="showPedidosForm()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Buscar Factura"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                        <input type="text" id="pedidos_id" class="hidden" name="pedidos_id" value="{{old('pedidos_id')}}">
                        <label for="factura_nro" class="col-md-1 control-label">Compra*</label>
                        <div class="col-md-2">
                            <input type="text" id="factura_nro" class="form-control text-right" name="factura_nro" value="{{old('factura_nro')}}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="moneda_id" class="col-md-1 control-label">Moneda *</label>
                        <div class="col-md-3">
                            <select id="select2-monedas" name="moneda_id" class="form-control" style="width: 100%">
                                <option value="{{$moneda->getId()}}">{{$moneda->getDescripcion()}}</option>
                            </select>
                        </div>
                        <label for="valor_cambio" class="col-md-1 control-label">Cambio*</label>
                        <div class="col-md-2">
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{old('valor_cambio', $valor_cambio)}}">
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
                        <label for="articulo_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%">

                            </select>
                        </div>
                        <div class="col-md-1">
                            <a data-toggle="tooltip" data-placement="top" title="Cantidad"><input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cant." onchange="calcularSubtotal()" onkeyup="calcularSubtotal()" readonly></a>
                        </div>
                        <input type="hidden" id="existencia" name="existencia">
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Costo Unitario"><input type="text" id="precio_unitario" name="precio_unitario" class="form-control" placeholder="Costo Unitario" onchange="calcularSubtotal()"></a>
                        </div>
                        <div class="col-md-1">
                            <a data-toggle="tooltip" data-placement="top" title="% Descuento">
                            <input type="number" id="porcentaje_descuento" name="porcentaje_descuento" class="form-control" placeholder="% Desc." min="0" max="100" onchange="calcularSubtotal()" readonly></a>

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
            </div>
        </form>
    </div>
</div>
@include('notaCreditoVenta.facturasForm')

@endsection
@section('otros_scripts')
<script type="text/javascript">
    
    var articulos_detalle = [];

    $('[data-toggle="tooltip"]').tooltip({
        trigger : 'hover'
    });

    $("#btn-add-articulo").attr("disabled", true);

    var cliente_id = $('#select2-proveedores').val();
    var tablePedidos = null;

    function showPedidosForm(){
        if ($('#select2-proveedores').val() == null) {
            var obj = $.alert({
                title: 'Atención',
                content: 'Debe seleccionar el cliente para buscar las facturas relacionados al mismo!',
                icon: 'fa fa-exclamation-triangle',
                type: 'orange',
                backgroundDismiss: true,
                theme: 'modern',
            });
            setTimeout(function(){
                obj.close();
            },3000); 
        } else {
            if ($.fn.DataTable.isDataTable('#tabla-facturas')) {
                $('#tabla-facturas').DataTable().clear();
                $('#tabla-facturas').DataTable().destroy();    
            }

            id_proveedor = $('#select2-proveedores').val();

            url_tabla = "{{ route('api.compra')}}" + "/proveedor/" + id_proveedor;

            tablePedidos = $('#tabla-facturas').DataTable({
                
                language: { url: '/datatables/translation/spanish' },
                processing: true,
                serverSide: true,
                ajax: {"url": url_tabla },
                select: {
                    style: 'single'
                },
                columns: [
                    {data: 'nro_factura'},
                    {data: 'fecha'},
                    {data: 'moneda'},
                    {data: 'monto_total'},
                    {data: 'comentario'}
                    ],
                'columnDefs': [
                { className: "dt-center", "targets": [1,2,3,4] }]
            });
            
            $('#modal-factura-venta').modal('show');
            $('#modal-factura-venta form')[0].reset();
            $('.modal-title').text('Lista de Facturas');
        }
    }
    
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
                    $("#porcentaje_iva" ).val(data.iva.porcentaje).change();
                    $("#costo_unitario" ).val(data.ultimo_costo).change();                    
                    $("#porcentaje_descuento" ).val(0).change();
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

    //Función para recuperar el valor de cambio al cambiar de moneda (?)
    function setValorCambio() {
        var moneda_id = $("#select2-monedas" ).val();
        $.ajax({
          type: "GET",
          url: "{{ url('api/cotizaciones') }}" + '/venta/' + moneda_id,
          datatype: "json",
          //async: false,
          success: function(data){
            $("#valor_cambio" ).val(data).change();
          }
        });
    };

    function calcularSubtotal() {
        var cantidad = $("#cantidad" ).val();
        var precio_unitario = $("#precio_unitario" ).val();
        var porcentaje_descuento = $("#porcentaje_descuento" ).val();
        cantidad = cantidad.replace(",", ".");
        precio_unitario = precio_unitario.replace(".", "");
        var calculo = cantidad * (precio_unitario - (precio_unitario * (porcentaje_descuento/100)));
        if($("#cantidad" ).val().length != 0 && $("#precio_unitario" ).val().length != 0){
            $("#subtotal" ).val(calculo).change();
        }
    };

        function Validar(){

            var valor_cambio = document.getElementById("valor_cambio").value;   

            if (valor_cambio == 0 ) {
                    var obj = $.alert({
                        title: 'Atención',
                        content: 'El valor de cambio no puede ser cero!',
                        icon: 'fa fa-exclamation-triangle',
                        type: 'orange',
                        backgroundDismiss: true,
                        theme: 'modern',
                    });
                    setTimeout(function(){
                        obj.close();
                    },3000);

                    return false; 
            }

        };

    function addArticulo() {
        var indexColumn = 0;
        var articulos_detalle = $('input[name="tab_articulo_id[]"]').map(function () {
            return this.value;
        }).get();

        /*Se obtienen los valores de los campos correspondientes*/
        var cantidad = $("#cantidad").val();
        cantidad = cantidad.replace(",", ".");
        var existencia = $("#existencia").val();
        
        /*if (Number(cantidad) > Number(existencia)) {
            var obj = $.alert({
                title: 'Atención',
                content: 'La cantidad cargada supera a la existencia actual! Existencia: '+existencia,
                icon: 'fa fa-exclamation-triangle',
                type: 'orange',
                backgroundDismiss: true,
                theme: 'modern',
            });
            setTimeout(function(){
                obj.close();
            },3000); 
        } else {*/
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
                //iva = Math.round(gravada*(porcentaje_iva/100));
                iva = subtotal - gravada;
            }
            /*Se le da formato numérico a los valores. Separador de miles y la coma si corresponde*/
            precio_unitario = $.number(precio_unitario,decimales, ',', '.');
            cantidad = $.number(cantidad, 2, ',', '.');
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

            var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' id='tab_articulo_id' name='tab_articulo_id[]' value='" + articulo_id + "'></th> <th> <input type='text' name='tab_articulo_nombre[]' value='" + articulo + "'></th> <th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_costo_unitario[]' value='" + precio_unitario + "'></th> <th> <input type='text' name='tab_porcentaje_descuento[]' value='" + porcentaje_descuento + "'></th> <th> <input type='text' name='tab_monto_descuento[]' value='" + monto_descuento + "'></th> <th> <input type='text' name='tab_porcentaje_iva[]' value='" + porcentaje_iva + "'></th> <th> <input type='text' name='tab_exenta[]' value='"+ exenta +"'> </th> <th> <input type='text' name='tab_gravada[]' value='"+ gravada +"'> </th> <th> <input type='text' name='tab_iva[]' value='"+ iva +"'> </th> <th> <input type='text' name='tab_subtotal[]' value='" + subtotal + "'> </th> </tr>";
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
            /*}*/
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

    function cargarPedidos(){
        var datos = tablePedidos.rows( { selected: true } ).data();
        var i;
        var array_pedidos = [];
        var tipo_ncre = document.querySelector('input[name="tipo_nota_credito"]:checked').value;
        //console.log(datos);
        for (i = 0; i < datos.length; i++) {
            array_pedidos.push(datos[i].id);
            document.getElementById("factura_nro").value = datos[i].nro_factura;
        }

        url_tabla = "{{ route('api.compra')}}" + "/proveedor/detalles/" + array_pedidos;

        if (array_pedidos.length > 0) {
            if (tipo_ncre == 'DV') {
                $.ajax({
                    type: "GET",
                    url: url_tabla,
                    datatype: "json",
                    success: function(data){
                        console.log(data);
                        if(data.length > 10){
                            var obj = $.alert({
                                title: 'Atención',
                                content: 'La Factura seleccionada supera la cantidad de lineas de detalles permitidos en una nota de crédito!',
                                icon: 'fa fa-exclamation-triangle',
                                type: 'orange',
                                backgroundDismiss: true,
                                theme: 'modern',
                            });
                            setTimeout(function(){
                                obj.close();
                            },3000);
                        } else{
                            document.getElementById("pedidos_id").value = array_pedidos;
                            for (i = 0; i < data.length; i++) {
                                //console.log(data[i]);
                                //INSERTAR EN LAS TABLAS DE DETALLES
                                var articulo = '('+data[i].codigo+') '+data[i].descripcion;
                                var precio_unitario = data[i].precio_unitario;
                                var cantidad = data[i].cantidad;
                                var monto_descuento = data[i].monto_descuento;
                                var porcentaje_iva = Number(data[i].porcentaje_iva);
                                var porcentaje_descuento = Number(data[i].porcentaje_descuento);
                                var exenta = 0;
                                var gravada = 0;
                                var iva = 0;
                                var subtotal = (precio_unitario *  cantidad) - monto_descuento;
                                if (porcentaje_iva == 0) {
                                    exenta = subtotal;
                                } else {
                                    gravada = Math.round(subtotal/((porcentaje_iva/100)+1));
                                    iva = Math.round(gravada*(porcentaje_iva/100));
                                }
                                cantidad = $.number(cantidad, 2, ',', '.');
                                precio_unitario = $.number(precio_unitario, 0, ',', '.');
                                monto_descuento = $.number(monto_descuento, 0, ',', '.');
                                var tabla_deta = $("#pedido-detalle").DataTable();
                                tabla_deta.row.add( [
                                    "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a>",
                                    articulo,
                                    $.number(data[i].cantidad, 2, ',', '.'),
                                    $.number(data[i].precio_unitario, 0, ',', '.'),
                                    $.number(data[i].monto_descuento, 0, ',', '.'),
                                    $.number(exenta, 0, ',', '.'),
                                    $.number(gravada, 0, ',', '.'),
                                    $.number(iva, 0, ',', '.'),
                                    $.number(subtotal, 0, ',', '.')
                                ]).draw( false );

                                var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' id='tab_articulo_id' name='tab_articulo_id[]' value='" + data[i].articulo_id + "'></th> <th> <input type='text' name='tab_articulo_nombre[]' value='" + articulo + "'></th> <th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_costo_unitario[]' value='" + precio_unitario + "'></th> <th> <input type='text' name='tab_porcentaje_descuento[]' value='" + porcentaje_descuento + "'></th> <th> <input type='text' name='tab_monto_descuento[]' value='" + monto_descuento + "'></th> <th> <input type='text' name='tab_porcentaje_iva[]' value='" + porcentaje_iva + "'></th> <th> <input type='text' name='tab_exenta[]' value='"+ exenta +"'> </th> <th> <input type='text' name='tab_gravada[]' value='"+ gravada +"'> </th> <th> <input type='text' name='tab_iva[]' value='"+ iva +"'> </th> <th> <input type='text' name='tab_subtotal[]' value='" + subtotal + "'> </th> </tr>";
                                $("#tab-hidden").append(markup);

                                $('#modal-factura-venta').modal('hide');
                            }
                        }
                    }
                });
            } else {
                //Recuperar articulo de descuento
                //Asignar articulo recuperado en la grilla de articulos
                //Asignar el valor 1(uno) en el campo cantidad
                //El monto debe ser ingresado por el cliente.
                document.getElementById("pedidos_id").value = array_pedidos;
                $('#modal-factura-venta').modal('hide');
                $("#select2-articulos").focus();
            }
        }
    }

</script>
<script type="text/javascript">
    $('#valor_cambio').number(true, 0, ',', '.');
    $('#cantidad').number(true, 2, ',', '.');
    $('#precio_unitario').number(true, 0, ',', '.');
    $('#subtotal').number(true, 0, ',', '.');
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