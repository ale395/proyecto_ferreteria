@extends('home')

@section('content')

	<form method="POST" action="{{ route('familias.store') }}">
	{!! csrf_field() !!}

		<div class="form-group">
			<label>Codigo
				<input type="text" class="mayusculas" name="num_familia" value="{{ old('num_familia')}}" required>

			</label>
		</div>

		<div class="form-group">
			<label>Descripcion
				<input type="text" name="descripcion" value="{{ old('descripcion')}}" required>
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		

	</form>
	<a href="{{url('familias')}}"><button class="btn btn-info">Cancelar</button></a>

@stop