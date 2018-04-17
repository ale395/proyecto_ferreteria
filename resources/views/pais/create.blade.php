@extends('home')

@section('content')

	<form method="POST" action="{{ route('paises.store') }}">
	{!! csrf_field() !!}

		@if ($errors->any())
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif

		<div class="form-group">
			<label>Descripción
				<input type="text" name="descripcion" value="{{ old('descripcion')}}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		
	</form>
	<a href="{{url('paises')}}"><button class="btn btn-info">Cancelar</button></a>

@stop