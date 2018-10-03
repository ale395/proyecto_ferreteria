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
                            <input type="text" id="fecha_emision" name="fecha_emision" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_emision', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-1 control-label">Cliente *</label>
                        <div class="col-md-5">
                            <select id="select2-clientes" name="cliente_id" class="form-control" autofocus style="width: 100%"></select>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Crear Cliente"><i class="fa fa-user-plus" aria-hidden="true" onclick="addForm()"></i></a>
                        </div>
                        <label for="lista_precio_id" class="col-md-1 control-label">Lista Pre.*</label>
                        <div class="col-md-3">
                            <select id="select2-lista-precios" name="lista_precio_id" class="form-control" style="width: 100%">
                                <option value="{{$lista_precio->getId()}}">{{$lista_precio->getNombre()}}</option>
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
                            <input type="number" id="precio_unitario" name="precio_unitario" class="form-control" placeholder="Precio Unitario" onchange="calcularSubtotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="subtotal" name="subtotal" class="form-control" placeholder="Subtotal" readonly>
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
@include('cliente.create-persona-fisica')
@include('cliente.create-persona-juridica')

@endsection
@section('otros_scripts')
<script type="text/javascript">
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
                            //table.ajax.reload();
                            console.log($data);
                            $("#select2-clientes").select2('data', { id: $data.id, text: $data.nombre + ', ' + $data.apellido});
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
                            //table.ajax.reload();
                            console.log($data);
                            console.log($data.id);
                            console.log($data.razon_social);
                            //$("#select2-clientes").select2('data', { id: $data.id, text: $data.razon_social});
                            $('#select2-clientes').val($data.id).trigger('change');
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

    function setCantidadPrecioUnitario() {
        var articulo_id = $("#select2-articulos" ).val();
        $.ajax({
          type: "GET",
          url: "{{ url('api/articulos') }}" + '/cotizacion/' + articulo_id,
          datatype: "json",
          //async: false,
          success: function(data){
            $("#precio_unitario" ).val(data).change();
          }
        });

        if($("#cantidad" ).val().length === 0){
            $("#cantidad" ).val(1).change();
        }
    };

    function calcularSubtotal() {
        var calculo = $("#cantidad" ).val() * $("#precio_unitario" ).val();
        $("#subtotal" ).val(calculo).change();
    };
    /*$(function() {
        $( "#cliente_id" ).autocomplete({
          source: function( request, response ) {
            $.ajax( {
              url: "/api/clientes/ventas",
              dataType: "json",
              data: {
                term: request.term
              },
              success: function( data ) {
                response( data );
              }
            } );
          },
          minLength: 4,
          autoFocus:true,
          select: function( event, ui ) {
            document.getElementById("articulo_id").focus();
          }
        });
    });*/
</script>
<script type="text/javascript">
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
    /*$(function() {
        $( "#articulo_id" ).autocomplete({
          source: function( request, response ) {
            $.ajax( {
              url: "/api/articulos/ventas",
              dataType: "json",
              data: {
                term: request.term
              },
              success: function( data ) {
                response( data );
              }
            } );
          },
          minLength: 4,
          autoFocus:true,
          select: function( event, ui ) {
            document.getElementById("cantidad").value = 1;
            document.getElementById("cantidad").focus();
          }
        });
    });*/
</script>
<script type="text/javascript">
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