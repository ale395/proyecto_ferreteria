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


window.location='{{route('articulos.create')}}'
save_method = "add";
        $('#error-block').hide();
        $('#activo').attr('checked', true);
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
      }













    //  function showForm(id) {
      //  var show_page = '/articulos/' + id;
       // window.location.href = show_page;
         //    }

      $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('articulos') }}";
                    else url = "{{ url('articulos') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        data : $('#modal-form form').serialize(),
                        ///data : new FormData(this),
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
     
        window.location="{{ url('articulos') }}" + '/' + id +'/edit';
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
   <script type="text/javascript">
    $('#articulo-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
  </script>
@endsection
