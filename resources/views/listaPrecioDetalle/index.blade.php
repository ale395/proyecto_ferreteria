@extends('home')

@section('content')

	<div class="row">
				<div class="col-md-12">
						<div class="panel panel-default">
								<div class="panel-heading">
										<h4>Lista de Precios <b>{{$lista_precio_cab->descripcion}}</b>
												@can('listapreciodet.asignar')
														<a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Registro</a>
												@else
														<a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Registro</a>
												@endcan
											<a href="{{route('listaPrecios.index')}}" class="btn btn-default pull-right" style="margin-top: -8px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver al Listado anterior</a>
										</h4>
								</div>
								<div class="panel-body">
										<table id="listaPreciosDetalle-table" class="table table-striped table-responsive">
												<thead>
														<tr>
																<th>Artículo</th>
																<th>Descripción</th>
																<th>Fecha Vigencia</th>
																<th>Precio</th>
																<th width="80">Acciones</th>
														</tr>
												</thead>
												<tbody></tbody>
										</table>
								</div>
						</div>
				</div>

		@include('listaPrecioDetalle.form')
		
@endsection

@section('ajax_datatables')
	<script type="text/javascript">
			var table = $('#listaPreciosDetalle-table').DataTable({
											language: { url: '/datatables/translation/spanish' },
											processing: true,
											serverSide: true,
											ajax: "{{ route('api.listaPreciosDet', ['list_prec_id' => $list_prec_id]) }}",
											columns: [
												{data: 'articulo', name: 'articulo'},
												{data: 'descripcion', name: 'descripcion'},
												{data: 'fecha_cast', name: 'fecha_cast'},
												{data: 'precio', name: 'precio'},
												{data: 'action', name: 'action', orderable: false, searchable: false}
											]
										});

			function addForm() {
				save_method = "add";
				$('input[name=_method]').val('POST');
				$('#modal-form').modal('show');
				$('#modal-form form')[0].reset();
				$('.modal-title').text('Agregar Artículo');
			}

			$(function(){
						$('#modal-form form').validator().on('submit', function (e) {
								if (!e.isDefaultPrevented()){
										var id = $('#id').val();
										if (save_method == 'add') url = "{{ url('listaPreciosDet') }}";
										else url = "{{ url('listaPreciosDet') . '/' }}" + id;

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
					url: "{{ url('listaPreciosDet') }}" + '/' + id + "/edit",
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('#modal-form').modal('show');
						$('.modal-title').text('Editar Precio');

						$('#id').val(data.id);
						$('#articulo_id').val(data.articulo_id);
						$('#fecha_vigencia').val(data.fecha_vigencia);
						$('#precio').val(data.precio);
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
										url : "{{ url('listaPreciosDet') }}" + '/' + id,
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

		<script type="text/javascript">
    $(document).ready(function() {
      $('.fecha_vigencia').datepicker();
    }); 
		</script>

@endsection