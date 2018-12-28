@extends('home')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{action('GestionCajasController@cerrarCaja')}}" class="form-horizontal" data-toggle="validator">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Cerrar Caja</h4>
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
                    <input type="hidden" id="id" name="id" value="{{$habilitacion->getId()}}">
                    <input type="hidden" id="user_id" name="user_id" value="{{$habilitacion->usuario->getId()}}">
                    <div class="form-group">
                        <label for="user_name" class="col-md-4 control-label">Usuario</label>
                        <div class="col-md-5">
                            <input type="text" id="user_name" name="user_name" class="form-control" value="{{$habilitacion->usuario->getName()}}" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="caja_id" value="{{$habilitacion->caja->getId()}}">
                    <div class="form-group">
                        <label for="caja_name" class="col-md-4 control-label">Caja</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="caja_nombre" value="{{$habilitacion->caja->getNombre()}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label for="saldo_inicial" class="col-md-4 control-label">Saldo Inicial</label>
                    	<div class="col-md-5">
                            <input type="text" id="saldo_inicial" name="saldo_inicial" class="form-control" value="{{$habilitacion->getSaldoInicialNumber()}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="saldo_final" class="col-md-4 control-label">Saldo Final</label>
                        <div class="col-md-5">
                            <input type="text" id="saldo_final" name="saldo_final" class="form-control" value="{{number_format($saldo_final, 0, ',', '.')}}" readonly>
                        </div>
                    </div>
            	</div>
            	<div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Cerrar Caja</button>
                </div>
        	</div>
    	</form>
	</div>
</div>
@endsection
@section('otros_scripts')
<script type="text/javascript">
	$('#saldo_inicial').number(true, 0, ',', '.');
    $('#saldo_final').number(true, 0, ',', '.');
</script>
@endsection