@extends('home')

@section('content')

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>Descripción</th>
				<th>País</th>
				<th>Acciones</th>
			</tr>	
		</thead>
		<tbody>
			<a href="{{ route('departamentos.create') }}">
				<button class="btn btn-success">Nuevo</button>
			</a>
			@foreach($departamentos as $departamento)
				<tr>
					<td>{{$departamento->descripcion}}</td>
					<td>{{$departamento->pais->descripcion}}</td>
					<td>
						<a href="{{route('departamentos.edit', $departamento)}}"><button class="btn btn-warning btn-sm">
							<span class="glyphicon glyphicon-edit"></span> Editar</button></a>
						<form style="display: inline" method="POST" action="{{ route('departamentos.destroy', $departamento) }}">
							{!! csrf_field() !!}
							{!! method_field('DELETE') !!}
							<button class="btn btn-danger btn-sm">
							<span class="glyphicon glyphicon-trash"></span> Eliminar</button>
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop