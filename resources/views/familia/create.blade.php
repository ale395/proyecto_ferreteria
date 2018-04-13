@extends('home')

@section('content')

	<form method="POST" action="{{ route('modulos.store') }}">
	{!! csrf_field() !!}

		<div class="form-group">
			<label>Modulo
				<input type="text" class="mayusculas" name="modulo" value="{{ old('modulo')}}">
			</label>
		</div>

		<div class="form-group">
			<label>Descripcion
				<input type="text" name="descripcion" value="{{ old('descripcion')}}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		

	</form>
	<a href="{{url('modulos')}}"><button class="btn btn-info">Cancelar</button></a>

@stop