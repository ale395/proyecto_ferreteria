@extends('home')

@section('content')

	<form method="POST" action="{{ route('departamentos.store') }}">
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

		<div class="form-group">
			<label>País
				<select name="pais_id">
					@foreach($paises as $pais)
						<option value="{{$pais->id}}">{{$pais->descripcion}}</option>
					@endforeach
				</select>
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		
	</form>
	<a href="{{url('departamentos')}}"><button class="btn btn-info">Cancelar</button></a>

@stop