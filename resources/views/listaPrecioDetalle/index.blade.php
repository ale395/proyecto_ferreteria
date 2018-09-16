@extends('home')

@section('content')

	<div class="row">
				<div class="col-md-12">
						<div class="panel panel-default">
								<div class="panel-heading">
										<h4>Lista de Precios <b>{{$lista_precio_cab->nombre}}</b>
												@can('listapreciodet.asignar')
														<a onclick="addForm({{$lista_precio_cab->moneda->getManejaDecimal()}})" class="btn btn-primary pull-right" style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
												@else
														<a class="btn btn-primary pull-right" disabled style="margin-top: -8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar</a>
												@endcan
											<a href="{{route('listaPrecios.index')}}" class="btn btn-default pull-right" style="margin-top: -8px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver al Listado anterior</a>
										</h4>
								</div>
								<div class="panel-body">
										<table id="listaPreciosDetalle-table" class="table table-striped table-responsive">
												<thead>
														<tr>
																<th>Artículo</th>
																<th>Nombre</th>
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
												{data: 'fecha_vigencia', name: 'fecha_vigencia'},
												{data: 'precio', name: 'precio'},
												{data: 'action', name: 'action', orderable: false, searchable: false}
											]
										});

			function addForm(manejaDecimal) {
				save_method = "add";
				$('input[name=_method]').val('POST');
				$('#modal-form').modal('show');
				$('#modal-form form')[0].reset();
				$('#error-block').hide();
        $('#select2-articulos').val("").change();
        $("#fecha_vigencia").datepicker().datepicker("setDate", new Date());
        if (manejaDecimal) {
            $("#precio").on({
		            "focus": function (event) {
		                $(event.target).select();
		            },
		            "keyup": function (event) {
		                $(event.target).val(function (index, value ) {
		                    return value.replace(/\D/g, "")
		                                .replace(/([0-9])([0-9]{2})$/, '$1,$2')//genera 2 posiciones decimales
		                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		                });
		            }
		        });
        } else {
        		$("#precio").on({
		            "focus": function (event) {
		                $(event.target).select();
		            },
		            "keyup": function (event) {
		                $(event.target).val(function (index, value ) {
		                    return value.replace(/\D/g, "")
		                                //.replace(/([0-9])([0-9]{2})$/, '$1,$2')//genera 2 posiciones decimales
		                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
		                });
		            }
		        });
        }
				$('.modal-title').text('Agregar Artículo a lista de precios');
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
				$('#modal-form form')[0].reset();
				$('#error-block').hide();
				$.ajax({
					url: "{{ url('listaPreciosDet') }}" + '/' + id + "/edit",
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('#modal-form').modal('show');
						$('.modal-title').text('Editar Precio');

						$('#id').val(data.id);
						$("#select2-articulos").select2("val", "");
            $('#select2-articulos').val(data.articulo_id).change();
						$('#fecha_vigencia').val(data.fecha_vigencia);
						$('#precio').val(data.precio);
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
                                  url : "{{ url('listaPreciosDet') }}" + '/' + id,
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
      $("#articulo_id").focus();
    });
  </script>
  
  <script type="text/javascript">
    $('#articulo-form').validator().off('input.bs.validator change.bs.validator focusout.bs.validator');
  </script>

		<script type="text/javascript">
	    $(function() {
      $('.dpvigencia').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        todayBtn: true,
        todayHighlight: true,
        autoclose: true
      });
      $('#fecha_vigencia').click(function(e){
          e.stopPropagation();
          $('.dpvigencia').datepicker('update');
          });
   		});
		</script>

		<script type="text/javascript">
    	$(document).ready(function(){
        $('#select2-articulos').select2({
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
        $(".precio-decimal").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                                .replace(/([0-9])([0-9]{2})$/, '$1,$2')//genera 2 posiciones decimales
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
        });

        $(document).ready(function(){
        $(".precio-sin-decimal").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                                //.replace(/([0-9])([0-9]{2})$/, '$1,$2')//genera 2 posiciones decimales
                                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
        });
    </script>

@endsection