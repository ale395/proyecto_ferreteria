@extends('home')

@section('content')

	<div class="row">
				<div class="col-md-12">
						<div class="panel panel-default">
								<div class="panel-heading">
										<h4>Lista de Precios
												@can('listaprecio.create')
														<a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
												@else
														<a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
												@endcan
										</h4>
								</div>
								<div class="panel-body">
										<table id="listaprecio-table" class="table table-striped table-responsive">
												<thead>
														<tr>
																<th>Código</th>
																<th>Nombre</th>
																<th>Moneda</th>
																<th width="310">Acciones</th>
														</tr>
												</thead>
												<tbody>
														@foreach($lista_precios as $lista_precio)
															<tr>
																<td>{{$lista_precio->codigo}}</td>
																<td>{{$lista_precio->nombre}}</td>
																<td>{{$lista_precio->moneda->descripcion}}</td>
																<td width="310">
																	 @can('listaprecio.edit')
																				<a onclick="editForm('{{$lista_precio->id}}')" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</a>
																		@else
																				<a class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</a>
																		@endcan
																		@can('listaprecio.destroy')
																				<a onclick="deleteData('{{$lista_precio->id}}')" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</a>
																		@else
																				<a class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</a>
																		@endcan
																		@can('listapreciodet.asignar')
																				<a href="{{route('listaPreciosDet.show', ['id' => $lista_precio->id])}}" class="btn btn-info btn-sm"><i class="fa fa-share-square-o" aria-hidden="true"></i> Asignar Precios</a>
																		@else
																				<a class="btn btn-info btn-sm" disabled><i class="fa fa-share-square-o" aria-hidden="true"></i> Asignar Precios</a>
																		@endcan
																</td>
															</tr>
														@endforeach
												</tbody>
										</table>
								</div>
						</div>
				</div>

		@include('listaPrecioCabecera.form')
		
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
			var table = $('#listaprecio-table')/*.DataTable({
											language: { url: 'datatables/translation/spanish' },
											processing: true,
											serverSide: true,
											ajax: "{{ route('api.rolepermission') }}",
											columns: [
												//{data: 'slug', name: 'slug'},
												{data: 'name', name: 'name'},
												{data: 'description', name: 'description'},
												{data: 'action', name: 'action', orderable: false, searchable: false}
											]
										})*/;

			function addForm() {
				save_method = "add";
				$('input[name=_method]').val('POST');
				$('#modal-form').modal('show');
				$('#error-block').hide();
				$('#modal-form form')[0].reset();
				$('.modal-title').text('Nueva Lista de Precios');
				$('#moneda_id').val("").change();
			}

			$(function(){
						$('#modal-form form').validator().on('submit', function (e) {
								if (!e.isDefaultPrevented()){
										var id = $('#id').val();
										if (save_method == 'add') url = "{{ url('listaPrecios') }}";
										else url = "{{ url('listaPrecios') . '/' }}" + id;

										$.ajax({
												url : url,
												type : "POST",
												data : $('#modal-form form').serialize(),
												success : function($data) {
														$('#modal-form').modal('hide');
														//table.ajax.reload();
														location.reload();
												},
												error : function(data){
                            var errors = '';
                            var errores = '';
                            if(data.status === 422) {
                                var errors = data.responseJSON;
                                $.each(data.responseJSON.errors, function (key, value) {
                                    errores += value + '<br>';
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
				$('#error-block').hide();
				$('#modal-form form')[0].reset();
				$.ajax({
					url: "{{ url('listaPrecios') }}" + '/' + id + "/edit",
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('#modal-form').modal('show');
						$('.modal-title').text('Editar Lista de Precios');

						$('#id').val(data.id);
						$('#codigo').val(data.codigo);
						$('#nombre').val(data.nombre);
						$("#moneda_id").select2("val", "");
            $('#moneda_id').val(data.moneda_id).change();
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
                                  url : "{{ url('listaPrecios') }}" + '/' + id,
                                  type : "POST",
                                  data : {'_method' : 'DELETE', '_token' : csrf_token},
                                  success : function(data) {
                                      //table.ajax.reload();
                                      location.reload();
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
    $('#cliente-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
  </script>

		<script>
      $(document).ready(function() {
          $('#moneda_id').select2({
          	placeholder : 'Seleccione una de las opciones',
            tags: false,
            width: 'resolve',
            dropdownParent: $('#modal-form'),
            language: "es"
          });
      });
    </script>
@endsection