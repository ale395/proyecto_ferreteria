@extends('layout.layout')

@section('contenido')

	<form method="POST" action="{{ route('departamentos.update', $departamento) }}">
	{!! method_field('PUT') !!}
	{!! csrf_field() !!}

		<div class="form-group">
			<label>Descripción
				<input type="text" name="descripcion" value="{{ $departamento->descripcion }}">
			</label>
		</div>

		<div class="form-group">
			<label>País
				<select name="pais_id">

					@foreach($paises as $pais)
						@if($pais->id == $departamento->pais_id)
							<option value="{{$pais->id}}" selected>{{$pais->descripcion}}</option>
						@else
							<option value="{{$pais->id}}">{{$pais->descripcion}}</option>
						@endif	
					@endforeach

				</select>
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>		

	</form>
	<a href="{{url('departamentos')}}"><button class="btn btn-info">Cancelar</button></a>

@stop