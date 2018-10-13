@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('OrdenCompraController@store')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Orden de Compra
                    <div class="pull-right btn-group">
                        <button type="submit" class="btn btn-primary btn-save">Guardar</button>
                        <a href="{{route('ordencompra.index')}}" type="button" class="btn btn-default">Cancelar</a>
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
                        <label for="nro_orden" class="col-md-1 control-label">Número</label>
                        <div class="col-md-2">
                            <input type="text" id="nro_orden" name="nro_orden" class="form-control" value="{{old('nro_orden', $nro_orden)}}" readonly="readonly">
                        </div>
                        <label for="fecha_emision" class="col-md-5 control-label">Fecha *</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="proveedor_id" class="col-md-1 control-label">Proveedor *</label>
                        <div class="col-md-5">
                            <select id="select2-proveedores" name="proveedor_id" class="form-control" autofocus style="width: 100%"></select>
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
                            <input type="text" id="valor_cambio" name="valor_cambio" class="form-control" value="{{old('valor_cambio', $cambio)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="lista_precio_id" class="col-md-1 control-label">Artículo</label>
                        <div class="col-md-4">
                            <select id="select2-articulos" name="articulo_id" class="form-control" style="width: 100%" onchange="setCantidadPrecioUnitario()">

                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad" onchange="calcularSubtotal()" onkeyup="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="costo_unitario" name="costo_unitario" class="form-control" placeholder="Costo Unitario" onchange="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="sub_total" name="sub_total" class="form-control" placeholder="Total Articulo" readonly>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Añadir al pedido" onclick="addArticulo()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <table id="pedido-detalle" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="10%">Acción</th>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Costo Unitario</th>
                                <th>Sub-Total</th>
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
                            <th id="total-th" name="monto_total">0</th>
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
 
    function setCantidadPrecioUnitario() {
        var articulo_id = $("#select2-articulos" ).val();
        $.ajax({
          type: "GET",
          url: "{{ url('api/articulos') }}" + '/cotizacion/' + articulo_id,
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
            $("#sub_total" ).val(calculo).change();
        }
    };

    function addArticulo() {
        var articulo = $('#select2-articulos').select2('data')[0].text;
        var cantidad = $("#cantidad").val();
        var costo_unitario = $("#costo_unitario").val();
        var monto_total = $("#monto_total").val();
        var markup = "<tr> <th>" + "<a class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Eliminar del pedido' onclick='deleteArticulo()'><i class='fa fa-trash' aria-hidden='true'></i></a>" + "</th> <th>" + articulo + "</th> <th>" + cantidad + "</th> <th>" + costo_unitario + "</th> <th>" + monto_total + "</th> </tr>";
        $("table tbody").append(markup);
        $('#select2-articulos').val(null).trigger('change');
        $('#cantidad').val('');
        $('#costo_unitario').val('');
        $('#monto_total').val('');
        $("#select2-articulos").focus();
    };
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