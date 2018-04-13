@extends('home')

@section('content')

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>Familia</th>
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>	
		</thead>
		<tbody>
			<a href="{{ route('modulos.create') }}">
				<button class="btn btn-success">Nuevo Registro</i></button>
			</a>
			@foreach($familias as $familia)
				<tr>
					<td>{{$familia->num_familia}}</td>
					<td>{{$familia->descripcion}}</td>
					<td>
						<a href="{{url('/modulos/'.$modulo->id.'/edit')}}"><button class="btn btn-warning btn-sm">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button></a>
						<form style="display: inline" method="POST" action="{{ route('modulos.destroy', $modulo->id) }}">
							{!! csrf_field() !!}
							{!! method_field('DELETE') !!}
							<button class="btn btn-danger btn-sm">
							<i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop