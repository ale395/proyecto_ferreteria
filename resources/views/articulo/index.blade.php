@extends('home')

@section('content')

  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lista de Articulos
                        @can('articulos.create')
                          <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @else
                          <a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <table id="articulo-table" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="50">Codigo</th>
                                <th>Descripción</th>
                                <th>Codigo de barras</th>
                                <th width="80"> Ultimo costo</th>
                                <th width="80"> Costo Promedio</th>
                                <th>Porcentaje ganancia</th>
                                <th>Comentario</th>
                                <th>Vendible</th>
                                <th width="40">Activo</th>
                                <th width="110">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    @include('articulo.form')
@endsection

@section('ajax_datatables')
  <script type="text/javascript">
      var table = $('#articulo-table').DataTable({
                      language: { url: 'datatables/translation/spanish' },
                      processing: true,
                      serverSide: true,
                      ajax: "{{ route('api.articulos') }}",
                      columns: [
                        {data: 'codigo', name: 'codigo'},
                        {data: 'descripcion', name: 'descripcion'},
                        {data: 'codigo_barra', name: 'codigo_barra'},
                        {data: 'ultimo_costo', name: 'ultimo_costo'},
                        {data: 'costo_promedio', name: 'costo_promedio'},
                        {data: 'porcentaje_ganancia', name: 'porcentaje_ganancia'},
                        {data: 'comentario', name: 'comentario'},
                        {data: 'vendible', name: 'vendible'},
                        {data: 'activo', name: 'activo'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                      ]
                    });

      function addForm() {
        save_method = "add";
        $('#error-block').hide();
        $('#activo').attr('checked', true);
        
        $('#select2-impuestos').val("").change();
        $('#select2-familias').val("").change();
        $('#select2-rubros').val("").change();
        $('#select2-lineas').val("").change();
        $('#select2-unidades').val("").change();
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Nuevo Articulo');

        $('#codigo').prop('readonly', false);
        $('#descripcion').prop('readonly', false);
        $('#costo').prop('readonly', false);
        $('#codigo_barra').prop('readonly', false);
        $('#porcentaje_ganancia').prop('readonly', false);
        $('#comentario').prop('readonly', false);
 
        $('#select2-grupos').prop('readonly', false);
        $('#select2-impuestos').prop('disabled', false);
        $('#select2-familias').prop('disabled', false);
        $('#select2-lineas').prop('disabled', false);
        $('#select2-unidades').prop('disabled', false);
        $('#activo').prop('disabled', false);
        $('#form-btn-guardar').prop('disabled', false);
      }

      function showForm(id) {
        save_method = "add";
        $('#error-block').hide();
        $('input[name=_method]').val('GET');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Ver Articulos');

        $.ajax({
          url: "{{ url('articulos') }}" + '/' + id,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');

            $('#id').val(data.id);
            $('#codigo').val(data.codigo);
            $('#codigo_barra').val(data.codigo_barra);
            $('#descripcion').val(data.descripcion);
            $('#ultimo_costo').val(data.ultimo_costo);
            $('#costo_promedio').val(data.costo_promedio);
            $('#porcentaje_ganancia').val(data.porcentaje_ganancia);
            $('#comentario').val(data.comentario);

            $("#select2-impuestos").select2("val", "");
            $('#select2-impuestos').val(data.impuesto_id).change();
            $("#select2-familias").select2("val", "");
            $('#select2-familias').val(data.familia_id).change();
            $("#select2-rubros").select2("val", "");
            $('#select2-rubros').val(data.rubro_id).change();
            $("#select2-lineas").select2("val", "");
            $('#select2-lineas').val(data.linea_id).change();
            $("#select2-unidades").select2("val", "");
            $('#select2-unidades').val(data.linea_id).change();
            if (data.activo) {
              $('#activo').attr('checked', true);
            }else{
              $('#activo').attr('checked', false);
            }

            $('#codigo').prop('readonly', true);
            $('#nombre').prop('readonly', true);
            $('#apellido').prop('readonly', true);
            $('#direccion').prop('readonly', true);
            $('#telefono').prop('readonly', true);
            $('#nro_documento').prop('readonly', true);
            $('#ruc').prop('readonly', true);
            $('#correo_electronico').prop('readonly', true);
            $('#select2-zonas').prop('disabled', true);
            $('#select2-tipos').prop('disabled', true);
            $('#select2-listas').prop('disabled', true);
            $('#select2-vendedores').prop('disabled', true);
            $('#activo').prop('disabled', true);
            $('#form-btn-guardar').prop('disabled', true);
          },
          error : function() {
              $.alert({
                title: 'Atención!',
                content: 'No se encontraron datos!',
                icon: 'fa fa-exclamation-circle',
                type: 'orange',
              });
          }
        });
      }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('articulos') }}";
                    else url = "{{ url('articulos') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //data : $('#modal-form form').serialize(),
                        data : new FormData(this),
                        success : function($data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
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
                            });
                          }
                            
                        }
                    });
                    return false;
                }
            });
        });

      function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#modal-form form')[0].reset();
        $('#error-block').hide();
        $.ajax({
          url: "{{ url('articulos') }}" + '/' + id + "/edit",
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Editar Articulo');
            $('#codigo').prop('readonly', false);

        $('#descripcion').prop('readonly', false);
        $('#ultimo_costo').prop('readonly', true);
        $('#costo_promedio').prop('readonly', true);
        $('#codigo_barra').prop('readonly', false);
        $('#porcentaje_ganancia').prop('readonly', false);
        $('#comentario').prop('readonly', false);
 
        $('#select2-grupos').prop('readonly', false);
        $('#select2-impuestos').prop('disabled', false);
        $('#select2-familias').prop('disabled', false);
        $('#select2-lineas').prop('disabled', false);
        $('#select2-unidades').prop('disabled', false);
        $('#activo').prop('disabled', false);
        $('#vendible').prop('disabled', false);
        $('#control_existencia').prop('disabled',false);
        $('#form-btn-guardar').prop('disabled', false);

        


            $('#id').val(data.id);
            $('#codigo').val(data.codigo);
            $('#codigo_barra').val(data.codigo_barra);
            $('#descripcion').val(data.descripcion);
            $('#ultimo_costo').val(data.ultimo_costo);
            $('#costo_promedio').val(data.costo_promedio);
            $('#porcentaje_ganancia').val(data.porcentaje_ganancia);
            $('#comentario').val(data.comentario);
            //$('#img_producto').val(data.img_producto);
            $('#img_producto_image').attr('src','{{ URL::to('/') }}/images/productos/'+data.img_producto);
            $("#select2-impuestos").select2("val", "");
            $('#select2-impuestos').val(data.impuesto_id).change();
            $("#select2-familias").select2("val", "");
            $('#select2-familias').val(data.familia_id).change();
            $("#select2-rubros").select2("val", "");
            $('#select2-rubros').val(data.rubro_id).change();
            $("#select2-lineas").select2("val", "");
            $('#select2-lineas').val(data.linea_id).change();
            $("#select2-unidades").select2("val", "");
            $('#select2-unidades').val(data.linea_id).change();
            if (data.activo) {
              $('#activo').attr('checked', true);
            }else{
              $('#activo').attr('checked', false);
            }
            if (data.vendible) {
              $('#vendible').attr('checked', true);
            }else{
              $('#vendible').attr('checked', false);
            }
            if (data.control_existencia) {
              $('#control_existencia').attr('checked', true);
            }else{
              $('#control_existencia').attr('checked', false);
            }
          },
          error : function() {
              $.alert({
                title: 'Atención!',
                content: 'No se encontraron datos!',
                icon: 'fa fa-exclamation-circle',
                type: 'orange',
              });
          }
        });
      }

      function deleteData(id){
        $.confirm({
            title: '¿De verdad lo quieres eliminar?',
            content: 'No podrás volver atras',
            type: 'red',
            buttons: {   
                ok: {
                    text: "Eliminar",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function(){
                          var csrf_token = $('meta[name="csrf-token"]').attr('content');
                          
                              $.ajax({
                                  url : "{{ url('articulos') }}" + '/' + id,
                                  type : "POST",
                                  data : {'_method' : 'DELETE', '_token' : csrf_token},
                                  success : function(data) {
                                      table.ajax.reload();
                                  },
                                  error : function () {
                                          $.alert({
                                              title: 'Atención!',
                                              content: 'Ocurrió un error durante el proceso!',
                                              icon: 'fa fa-times-circle-o',
                                              type: 'red',
                                          });
                                  }
                              })
                    }
                },
                cancel: function(){
                        console.log('Cancel');
                }
            }
          });
        }

    </script>
    
@endsection

@section('otros_scripts')
  <script type="text/javascript">
    $('#modal-form').on('shown.bs.modal', function() {
      $("#codigo").focus();
    });
  </script>
      <script type="text/javascript">
        $("#img_producto").change(function(){
          var fichero_seleccionado = $(this).val();
          var nombre_fichero_seleccionado = fichero_seleccionado.replace(/.*[\/\\]/, ''); //Eliminamos el path hasta el fichero seleccionado
          if (fichero_seleccionado != nombre_fichero_seleccionado) {
            $("#label-img_producto").text(nombre_fichero_seleccionado);
          }
          
        });
    </script>
  <script type="text/javascript">
    $('#articulo-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
  </script>
 <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-impuestos').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-familias').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-rubros').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-lineas').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
            $('#select2-unidades').select2({
                placeholder : 'Seleccione una de las opciones',
                tags: false,
                width: 'resolve',
                dropdownParent: $('#modal-form'),
                language: "es"
            });
        });
  </script>
@endsection
