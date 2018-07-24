@extends('home')

@section('content')

	<div class="row">
				<div class="col-md-12">
						<div class="panel panel-default">
								<div class="panel-heading">
										<h4>Lista de Precios
												@can('listaprecio.create')
														<a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Registro</a>
												@else
														<a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Registro</a>
												@endcan
										</h4>
								</div>
								<div class="panel-body">
										<table id="listaprecio-table" class="table table-striped table-responsive">
												<thead>
														<tr>
																<th>Código</th>
																<th>Descripción</th>
																<th>Moneda</th>
																<th width="310">Acciones</th>
														</tr>
												</thead>
												<tbody>
														@foreach($lista_precios as $lista_precio)
															<tr>
																<td>{{$lista_precio->lista_precio}}</td>
																<td>{{$lista_precio->descripcion}}</td>
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
				$('#modal-form form')[0].reset();
				$('.modal-title').text('Agregar Lista de Precio');
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
												error : function(){
														alert('Oops! Something Error!');
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
				$.ajax({
					url: "{{ url('listaPrecios') }}" + '/' + id + "/edit",
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('#modal-form').modal('show');
						$('.modal-title').text('Editar Lista de Precio');

						$('#id').val(data.id);
						$('#lista_precio').val(data.lista_precio);
						$('#descripcion').val(data.descripcion);
						$('#moneda_id').val(data.moneda_id);
					},
					error : function() {
							alert("Nothing Data");
					}
				});
			}

			function deleteData(id){
						var popup = confirm("Are you sure for delete this data ?");
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						if (popup == true ){
								$.ajax({
										url : "{{ url('listaPrecios') }}" + '/' + id,
										type : "POST",
										data : {'_method' : 'DELETE', '_token' : csrf_token},
										success : function(data) {
												//table.ajax.reload();
												location.reload();
										},
										error : function () {
												alert("Oops! Something Wrong!");
										}
								})
						}
				}

		</script>

		<script>
      $(document).ready(function() {
          $('.js-moneda').select2({
          	placeholder: 'Select an option'
          });
      });
    </script>
@endsection