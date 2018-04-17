@extends('home')

@section('content')

	<form method="POST" action="{{ route('paises.update', $pais) }}">
	{!! method_field('PUT') !!}
	{!! csrf_field() !!}

		<div class="form-group">
			<label>Descripción
				<input type="text" name="descripcion" value="{{ $pais->descripcion }}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>		

	</form>
	<a href="{{url('paises')}}"><button class="btn btn-info">Cancelar</button></a>

@stop