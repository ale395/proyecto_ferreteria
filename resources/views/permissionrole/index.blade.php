@extends('home')

@section('content')

	<div class="row">
				<div class="col-md-12">
						<div class="panel panel-default">
								<div class="panel-heading">
										<h4>Lista de Roles
										</h4>
								</div>
								<div class="panel-body">
										<table id="rolepermission-table" class="table table-striped table-responsive">
												<thead>
														<tr>
																<th>Nombre</th>
																<th>Descripción</th>
																<th width="270">Acciones</th>
														</tr>
												</thead>
												<tbody>
														@foreach($roles as $role)
															<tr>
																<td>{{$role->name}}</td>
																<td>{{$role->description}}</td>
																<td width="270">
																	<a href="{{route('gestionpermisos.show', $role->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Ver Permisos</a>
																	<a href="{{route('gestionpermisos.edit', $role->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar Permisos</a>
																</td>
															</tr>
														@endforeach
												</tbody>
										</table>
								</div>
						</div>
				</div>

		@include('role.form')
		
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
			var table = $('#rolepermission-table').DataTable({
				language: { url: 'datatables/translation/spanish' }
			});

			function addForm() {
				save_method = "add";
				$('input[name=_method]').val('POST');
				$('#modal-form').modal('show');
				$('#modal-form form')[0].reset();
				$('.modal-title').text('Agregar Permiso');
			}

			$(function(){
						$('#modal-form form').validator().on('submit', function (e) {
								if (!e.isDefaultPrevented()){
										var id = $('#id').val();
										if (save_method == 'add') url = "{{ url('roles') }}";
										else url = "{{ url('roles') . '/' }}" + id;

										$.ajax({
												url : url,
												type : "POST",
												data : $('#modal-form form').serialize(),
												success : function($data) {
														$('#modal-form').modal('hide');
														table.ajax.reload();
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
					url: "{{ url('roles') }}" + '/' + id + "/edit",
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('#modal-form').modal('show');
						$('.modal-title').text('Editar Rol');

						$('#id').val(data.id);
						$('#slug').val(data.slug);
						$('#name').val(data.name);
						$('#description').val(data.description);
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
										url : "{{ url('roles') }}" + '/' + id,
										type : "POST",
										data : {'_method' : 'DELETE', '_token' : csrf_token},
										success : function(data) {
												table.ajax.reload();
										},
										error : function () {
												alert("Oops! Something Wrong!");
										}
								})
						}
				}

		</script>
@endsection