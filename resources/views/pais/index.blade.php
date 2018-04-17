@extends('home')

@section('content')

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>	
		</thead>
		<tbody>
			<a href="{{ route('paises.create') }}">
				<button class="btn btn-success">Nuevo</button>
			</a>
			@foreach($paises as $pais)
				<tr>
					<td>{{$pais->descripcion}}</td>
					<td>
						<!--<a href="{{url('/paises/'.$pais->id.'/edit')}}"><button class="btn btn-warning btn-sm">-->
						<a href="{{route('paises.edit', $pais)}}"><button class="btn btn-warning btn-sm">
							<span class="glyphicon glyphicon-edit"></span> Editar</button></a>
						<form style="display: inline" method="POST" action="{{ route('paises.destroy', $pais) }}">
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
		{!!$paises->render()!!}
@stop