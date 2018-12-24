@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('OrdenPagoController@store')}}" onsubmit="return Validar()" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Orden de Pago
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
                        <label for="nro_orden" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_orden" name="nro_orden" class="form-control text-right" value="{{old('nro_orden')}}" readonly="readonly">  
                        </div>
                        <label for="fecha_emision" class="col-md-1 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="proveedor_id" class="col-md-1 control-label">Proveedor *</label>
                        <div class="col-md-7">
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
                    <br>
                    <div class="form-group">
                        <label for="facturas-afectadas" class="col-md-3 control-label">Facturas Afectadas</label>
                    </div>
                    <div class="form-group">
                        <label for="lista_costo_id" class="col-md-1 control-label">Compra</label>
                        <div class="col-md-4">
                            <select id="select2-compras" name="compra_id" class="form-control" style="width: 100%" >

                            </select>
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Importe"><input type="text" id="costo_unitario" name="costo_unitario" class="form-control" placeholder="Importe Afectado" onchange="calcularSubtotal()"></a>
                        </div>

                        <div class="col-md-1">
                            <a id="btn-add-articulo" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir a la factura" onclick="addArticulo()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <span class="help-block with-errors"></span>
        
                    <table id="pedido-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Compra</th>
                                <th width="6%">Importe Afectado.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_compra_id'))->count(); $i++)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{old('tab_nro_compra.'.$i)}}</td>
                                        <td>{{old('tab_importe_afectado.'.$i)}}</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><p class="text-right"> Total </p></td>
                                <th class="total">0</th>
                            </tr>
                        </tfoot>
                    </table>

                    <table id="tab-hidden" class="hidden">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Compra ID</th>
                                <th>COmpra</th>
                                <th width="9%">importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_compra_id'))->count(); $i++)
                                    <tr>
                                        <th><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></th>
                                        <th><input type="text" id="tab_compra_id" name="tab_compra_id[]" value="{{old('tab_compra_id.'.$i)}}"></th>
                                        <th><input type="text" name="tab_nro_compra[]" value="{{old('tab_nro_compra.'.$i)}}"></th>
                                        <th><input type="text" name="tab_importe_afectado[]" value="{{old('tab_importe_afectado.'.$i)}}"></th>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                    <br>
                    <div class="form-group">
                        <label for="facturas-afectadas" class="col-md-3 control-label">Cheques</label>
                    </div>
                    <div class="form-group">                        
                        <label for="bancos_id" class="col-md-1 control-label">Banco</label>
                        <div class="col-md-2">
                            <select id="select2-banco-pago" name="bancos_id" class="form-control" style="width: 100%" >

                            </select>
                        </div>
                        <div class="col-md-2">
                            <a data-toggle="tooltip" data-placement="top" title="Nro. Cuenta"><input type="text" id="nro-cuenta" name="nro_cuenta" class="form-control" placeholder="Cuenta"></a>
                        </div>
                        <label for="moneda_id" class="col-md-1 control-label">Moneda</label>
                        <div class="col-md-2">
                            <select id="select2-monedas-che" name="moneda_che_id" class="form-control" style="width: 100%">
                                <option value="{{$moneda->getId()}}">{{$moneda->getDescripcion()}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a data-toggle="tooltip" data-placement="top" title="Librador"><input type="text" id="liibrador" name="librador" class="form-control" placeholder="Librador"></a>
                        </div>
                    </div>
                    <span class="help-block with-errors"></span>
                    <div class="form-group">
                        <label for="fecha_emision" class="col-md-1 control-label">Emisión </label>
                        <div class="col-md-2">
                            <input type="text" id="fecha-emision-che" name="fecha_emision_che" class="form-control dpfecha" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
                        </div>   
                        <label for="fecha_vencimiento" class="col-md-1 control-label">Vencimiento </label>
                        <div class="col-md-2">
                            <input type="text" id="fecha-vencimiento" name="fecha_vencimiento" class="form-control dpfecha" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'">
                        </div>   
                        <div class="col-md-3">
                            <a data-toggle="tooltip" data-placement="top" title="Importe"><input type="text" id="importe-che" name="importe" class="form-control" placeholder="Importe"></a>
                        </div>
                        <div class="col-md-1">
                            <a id="btn-add-forma-pago-che" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir forma de pago" onclick="addFormaPago()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <span class="help-block with-errors"></span>
        
                    <table id="cheques-pago-detalle" class="table table-striped table-responsive display" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">Acción</th>
                                <th>Banco</th>
                                <th width="15%">Cuenta</th>
                                <th width="30%">Librador</th>
                                <th width="9%">Moneda</th>
                                <th width="9%">Emisión</th>
                                <th width="9%">Vencimiento</th>
                                <th width="9%">Importe</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_banco_id'))->count(); $i++)
                                    <tr>
                                        <td><a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a></td>
                                        <td>{{old('tab_banco_nombre.'.$i)}}</td>
                                        <td>{{old('tab_cuenta.'.$i)}}</td>
                                        <td>{{old('tab_librador.'.$i)}}</td>
                                        <td>{{old('tab_moneda_che.'.$i)}}</td>
                                        <td>{{old('tab_fecha_emi.'.$i)}}</td>
                                        <td>{{old('tab_fecha_venc.'.$i)}}</td>
                                        <td>{{old('tab_importe_che.'.$i)}}</td>
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
                                <th>Total</th>
                                <th class="total-fp">0</th>
                            </tr>
                        </tfoot>
                    </table>

                    <table id="tab-hidden-che" class="hidden">
                        <thead>
                            <tr>
                                <th>Id Banco</th>
                                <th>Banco</th>
                                <th width="15%">Cuenta</th>
                                <th width="30%">Librador</th>
                                <th width="9%">Moneda</th>
                                <th width="9%">Emisión</th>
                                <th width="9%">Vencimiento</th>
                                <th width="9%">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($errors->any())
                                @for ($i=0; $i < collect(old('tab_banco_id'))->count(); $i++)
                                    <tr>
                                        <td>{{old('tab_banco_id.'.$i)}}</td>
                                        <td>{{old('tab_banco_nombre.'.$i)}}</td>
                                        <td>{{old('tab_cuenta.'.$i)}}</td>
                                        <td>{{old('tab_librador.'.$i)}}</td>
                                        <td>{{old('tab_moneda_che.'.$i)}}</td>
                                        <td>{{old('tab_fecha_emi.'.$i)}}</td>
                                        <td>{{old('tab_fecha_venc.'.$i)}}</td>
                                        <td>{{old('tab_importe_che.'.$i)}}</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>                        
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
    $("#btn-add-forma-pago-che").attr("disabled", true);
    
    var table = $('#pedido-detalle').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
          { className: "dt-center", "targets": [0,2] },
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
                .column(2)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 2 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });

    var table_che = $('#cheques-pago-detalle').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        language: { url: '/datatables/translation/spanish' },
        "columnDefs": [
          { className: "dt-center", "targets": [0,2,3,4,5,6] },
          { className: "dt-left", "targets": [1] },
          { className: "dt-right", "targets": [7] }
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
                .column(7)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 7 ).footer() ).html(
                $.number(total,decimales, ',', '.')
            );
        }
    });

    /*Evento onchange del select de artículos, para que recupere el costo si es seleccionado algún artículo distinto a nulo*/
    $("#select2-compras").change(function (e) {
        var valor = $(this).val();
        
        if (valor != null) {
            var compra_id = $("#select2-compras" ).val();
            $.ajax({
                type: "GET",
                url: "{{ url('api/articulos') }}" + '/costo/' + compra_id,
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

    /*Evento onchange del select de monedas, para que recupere la última cotización cotización*/
    $("#select2-monedas").change(function (e) {
        var valor = $(this).val();
        //api/cotizaciones/venta/{moneda}
        if (valor != null) {
            var moneda_id = $("#select2-monedas" ).val();
            var valor_cambio = 0

            var url_cotizacion = "{{ url('api/cotizaciones') }}" + '/venta/' + moneda_id
            
            $.ajax({
                type: "GET",
                url: url_cotizacion,
                datatype: "json",
                success: function(data){
                    //$("#valor_cambio").val(data).change();
                    valor_cambio = data;
                }
            });

            if( valor_cambio == 0 ){
                var obj = $.alert({
                    title: 'Atención',
                    content: 'Moneda no tiene cotización cargada!.',
                    icon: 'fa fa-exclamation-triangle',
                    type: 'orange',
                    backgroundDismiss: true,
                    theme: 'modern',
                });
                setTimeout(function(){
                    obj.close();
                },3000);
            } else {
                $("#valor_cambio").val(valor_cambio).change();
            }

        }
        else {
            var obj = $.alert({
                    title: 'Atención',
                    content: 'No seleccionó una moneda!.',
                   icon: 'fa fa-exclamation-triangle',
                    type: 'orange',
                    backgroundDismiss: true,
                    theme: 'modern',
                });
                setTimeout(function(){
                    obj.close();
                },3000);
        }
    });

    /*Evento onchange del select de artículos, para que recupere el costo si es seleccionado algún artículo distinto a nulo*/
    $("#select2-banco-pago").change(function (e) {
        var valor = $(this).val();
        
        if (valor != null) {
            /*    
            var compra_id = $("#select2-compras" ).val();
            $.ajax({
                type: "GET",
                url: "{{ url('api/articulos') }}" + '/costo/' + compra_id,
                datatype: "json",
                //async: false,
                success: function(data){
                    $("#porcentaje_iva" ).val(data.iva.porcentaje).change();
                    $("#costo_unitario" ).val(data.ultimo_costo).change();                    
                    $("#porcentaje_descuento" ).val(0).change();
                    $("#btn-add-articulo").attr("disabled", false);
                }
            });
            */    
            $("#btn-add-articulo").attr("disabled", false);
            /*
            if($("#cantidad" ).val().length === 0){
                $("#cantidad" ).val(1).change();
            }
            $("#cantidad").focus();CON
            */CON
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
        var costo_unitario = $("#costo_unitario" ).val();
        var porcentaje_descuento = $("#porcentaje_descuento" ).val();
        cantidad = cantidad.replace(".", "");
        //cantidad = cantidad.replace(",", ".");
        costo_unitario = costo_unitario.replace(".", "");
        //costo_unitario = costo_unitario.replace(".", ",");
        var calculo = cantidad * (costo_unitario - (costo_unitario * (porcentaje_descuento/100)));
        if($("#cantidad" ).val().length != 0 && $("#costo_unitario" ).val().length != 0){
            $("#subtotal" ).val(calculo).change();
        }
    };

    function Validar(){

        var modalidad_con = "";
        var modalidad_cre = "";

        if (document.getElementById('radioContado').checked) {
            modalidad_con = document.getElementById("radioContado").value;        
        }

        if (document.getElementById('radioContado').checked) {
            modalidad_cre = document.getElementById("radioCredito").value;
        }


        cheques_detalle = $('input[name="tab_banco_id[]"]').map(function () {
            return this.value;
        }).get();

        if (modalidad_cre == "CRE" && cheques_detalle.length > 0 ) {
                var obj = $.alert({
                    title: 'Atención',
                    content: 'La modalidad de pago es crédito, no se debe ingresar la forma de pago',
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
        else {
            if (modalidad_con == "CO" && cheques_detalle.length == 0 ) {
                var obj = $.alert({
                    title: 'Atención',
                    content: 'Debe ingresar la forma de pago.',
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
            else {


                return true
            }
        }
    };

    function addArticulo() {
        var indexColumn = 0;
        var articulos_detalle = $('input[name="tab_compra_id[]"]').map(function () {
            return this.value;
        }).get();

        /*Se obtienen los valores de los campos correspondientes*/
        var cantidad = $("#cantidad").val();
        //var existencia = $("#existencia").val();
        console.log('Antes de add: '+articulos_detalle);
        
        var decimales = 0;
            var articulo = $('#select2-compras').select2('data')[0].text;
            var compra_id = $('#select2-compras').select2('data')[0].id;
            if (articulos_detalle.includes(compra_id)) {
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
                articulos_detalle.push(compra_id);
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

                var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' id='tab_compra_id' name='tab_compra_id[]' value='" + compra_id + "'></th> <th> <input type='text' name='tab_nro_compra[]' value='" + articulo + "'></th> <th> <input type='text' name='tab_cantidad[]' value='" + cantidad + "'></th> <th> <input type='text' name='tab_costo_unitario[]' value='" + costo_unitario + "'></th> <th> <input type='text' name='tab_porcentaje_descuento[]' value='" + porcentaje_descuento + "'></th> <th> <input type='text' name='tab_monto_descuento[]' value='" + monto_descuento + "'></th> <th> <input type='text' name='tab_porcentaje_iva[]' value='" + porcentaje_iva + "'></th> <th> <input type='text' name='tab_exenta[]' value='"+ exenta +"'> </th> <th> <input type='text' name='tab_gravada[]' value='"+ gravada +"'> </th> <th> <input type='text' name='tab_iva[]' value='"+ iva +"'> </th> <th> <input type='text' name='tab_importe_afectado[]' value='" + subtotal + "'> </th> </tr>";
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
                $('#select2-compras').val(null).trigger('change');
                $("#select2-compras").focus();
            }
    
    };

    function addChequePago() {
        var indexColumn = 0;
        var banco_detalle = $('input[name="tab_banco_id[]"]').map(function () {
            return this.value;
        }).get();
        var cuenta_detalle = $('input[name="tab_cuenta[]"]').map(function () {
            return this.value;
        }).get();
        var fecha_detalle = $('input[name="tab_fecha_venc[]"]').map(function () {
            return this.value;
        }).get();

        //console.log('Antes de add: '+articulos_detalle);
       
        var banco = $('#select2-banco-pago').select2('data')[0].text;
        var banco_id = $('#select2-banco-pago').select2('data')[0].id;
        var cuenta = $('#nro-cuenta').val();
        var moneda_id = $('#select2-moneda-che').select2('data')[0].id;
        var moneda = $('#select2-moneda-che').select2('data')[0].text;
        var fecha_emision = $('#fecha-emision-che').val();
        var fecha_vencimiento = $('#fecha-vencimiento').val();
        if (banco_detalle.includes(banco_id) && cuenta_detalle.includes(cuenta) && fecha_detalle.includes(fehca_vencimiento))  {
            var obj = $.alert({
                title: 'Atención',
                content: 'Ya se ingresó este cheque',
                icon: 'fa fa-exclamation-triangle',
                type: 'orange',
                backgroundDismiss: true,
                theme: 'modern',
            });
            setTimeout(function(){
                obj.close();
            },3000); 
        } else {
            banco_detalle.push(banco_id);
            //console.log('Despues de add: '+articulos_detalle);
            //var cantidad = $("#cantidad").val();
            var importe = $("#importe-che").val();
            var librador = $('#librador').val();
            /*Se le da formato numérico a los valores. Separador de miles y la coma si corresponde*/
            importe = $.number(importe,decimales, ',', '.');
            
            /*Se agrega una fila a la tabla*/
            var tabla = $("#cheques-pago-detalle").DataTable();
            tabla.row.add( [
                "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></a>",
                banco,
                cuenta,
                librador,
                moneda,
                fecha_emision,
                fecha_vencimiento,
                importe
            ] ).draw( false );

            var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm btn-delete-row' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th> <input type='text' id='tab_banco_id' name='tab_banco_id[]' value='" + banco_id + "'></th> <th> <input type='text' name='tab_banco_nombre[]' value='" + banco + "'></th> <th> <input type='text' name='tab_cuenta[]' value='" + cuenta + "'></th> <th> <input type='text' name='tab_librador[]' value='" + librador+ "'></th> <th> <input type='text' name='tab_fecha_venc[]' value='" + fecha_vencimiento + "'> </th> <th> <input type='text' name='tab_importe_che[]' value='" + importe + "'> </th> </tr>";
            $("#tab-hidden-che").append(markup);

            /*Se restauran a nulos los valores del bloque para la selección del articulo*/
            $('#importe-che').number(false);
            
            $('#nro-cuenta').val("");
            $('#librador').val("");
            $('#fecha-vencimiento').val("");
            $('#select2-banco-pago').val(null).trigger('change');
            $("#select2-banco-pago").focus();
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

    /*Elimina el cheque del detalle del pago*/
    var tabla = $("#cheques-pago-detalle").DataTable();
    $('#cheques-pago-detalle tbody').on( 'click', 'a.btn-delete-row', function () {
        var row = $(this).parent().index('#cheques-pago-detalle tbody tr');
        tabla
            .row( $(this).parents('tr') )
            .remove()
            .draw();
        $("#tab-hidden-che tr:eq("+row+")").remove();
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

        /*  
        $('#select2-compras').select2({
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
        */
        
        var moneda_id = $("#select2-proveedores" ).val();
        var url_compras = "{{ route('api.compra.proveedorop', @1) }}";
        
        var res = url_compras.replace('@1', moneda_id)
        
        $('#select2-compras').select2({

            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 4,
            ajax: {
                url: res,
                type: "GET",
                delay: 250,
                data: {
                    id_cliente: $('#select2-compras').val()
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

        $('#select2-forma-pago').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('api.formasPagos.compras') }}",
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

        $('#select2-banco-pago').select2({
            placeholder: 'Seleccione una opción',
            language: "es",
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('api.bancos.compraspagos') }}",
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

        $('#select2-monedas-che').select2({
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