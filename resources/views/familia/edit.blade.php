@extends('home')

@section('content')

	<form method="POST" action="{{ route('familias.update', $familia->id) }}">
	{!! method_field('PUT') !!}
	{!! csrf_field() !!}

		<div class="form-group">
			<label>Num. Familia
				<input type="text" name="num_familia" value="{{ $familia->num_familia }}">
			</label>
		</div>

		<div class="form-group">
			<label>Descripcion
				<input type="text" name="descripcion" value="{{ $familia->descripcion }}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		

	</form>
	<a href="{{url('familias')}}"><button class="btn btn-info">Cancelar</button></a>

@stop