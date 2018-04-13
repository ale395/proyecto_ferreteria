@extends('home2')

@section('content')

	<form method="POST" action="{{ route('modulos.update', $modulo->id) }}">
	{!! method_field('PUT') !!}
	{!! csrf_field() !!}

		<div class="form-group">
			<label>Modulo
				<input type="text" name="modulo" value="{{ $modulo->modulo }}">
			</label>
		</div>

		<div class="form-group">
			<label>Descripcion
				<input type="text" name="descripcion" value="{{ $modulo->descripcion }}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		

	</form>
	<a href="{{url('modulos')}}"><button class="btn btn-info">Cancelar</button></a>

@stop