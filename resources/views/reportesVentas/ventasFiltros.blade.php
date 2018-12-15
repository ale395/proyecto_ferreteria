@extends('home')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{action('ReportesVentasController@verReporteVentas')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Reporte de ventas - Filtros</h4>
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
                    <div class="form-group">
                        <label for="fecha_emision" class="col-md-2 control-label">Fecha Final*</label>
                        <div class="col-md-2">
                            <input type="text" id="fecha_final" name="fecha_final" class="form-control dpfecha" placeholder="dd/mm/aaaa" value="{{old('fecha_final', $fecha_actual)}}" data-inputmask="'mask': '99/99/9999'">
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="cliente_id" class="col-md-2 control-label">Cliente*</label>
                        <div class="col-md-7">
                            <select id="select2-clientes" name="cliente_id" class="form-control" autofocus style="width: 100%">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" formtarget="_blank" class="btn btn-primary btn-save">Ver Reporte <i class="fa fa-file-text-o" aria-hidden="true"></i></button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@section('otros_scripts')
<script type="text/javascript">
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

    $(function() {
      $('.dpfecha').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        todayBtn: true,
        todayHighlight: true,
        autoclose: true
      });
        $('#fecha_corte').click(function(e){
            e.stopPropagation();
            $('.dpfecha').datepicker('update');
        });  
    });
</script>
@endsection