@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('GestionCajasController@habilitarCaja')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Habilitar Caja</h4>
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
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}">
                    <div class="form-group">
                        <label for="user_name" class="col-md-4 control-label">Usuario</label>
                        <div class="col-md-5">
                            <input type="text" id="user_name" name="user_name" class="form-control" value="{{Auth::user()->name}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="caja_id" class="col-md-4 control-label">Caja*</label>
                        <div class="col-md-5">
                            <select id="select2-cajas" name="caja_id" class="form-control" style="width: 100%">
                            	<option></option>
                            	@foreach($cajas as $caja)
                            		@if(old('caja_id') == $caja->getId())
                                        <option value="{{$caja->getId()}}" selected>{{$caja->getNombre()}}</option>
                                    @else
                            			<option value="{{$caja->getId()}}">{{$caja->getNombre()}}</option>
                            		@endif
                            	@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label for="saldo_inicial" class="col-md-4 control-label">Saldo Inicial*</label>
                    	<div class="col-md-5">
                            <input type="text" id="saldo_inicial" name="saldo_inicial" class="form-control" value="{{old('saldo_inicial')}}">
                        </div>
                    </div>
            	</div>
            	<div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Habilitar Caja</button>
                </div>
        	</div>
    	</form>
	</div>
</div>
@endsection
@section('otros_scripts')
<script type="text/javascript">
	$('#saldo_inicial').number(true, 0, ',', '.');

	$('#select2-cajas').select2({
        placeholder : 'Seleccione una opción',
        tags: false,
        width: 'resolve',
        language: "es"
    });
</script>
@endsection