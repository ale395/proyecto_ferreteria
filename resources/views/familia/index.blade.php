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
			<a href="{{ route('familias.create') }}">
				@can('familias.create')
					<button class="btn btn-success">Nuevo Registro</i></button>
				@else
					<button class="btn btn-success" disabled>Nuevo Registro</i></button>
				@endcan
			</a>
			@foreach($familias as $familia)
				<tr>
					<td>{{$familia->num_familia}}</td>
					<td>{{$familia->descripcion}}</td>
					<td>
						<a href="{{url('/familias/'.$familia->id.'/edit')}}">
							@can('familias.edit')
								<button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
							@else
								<button class="btn btn-warning btn-sm" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
							@endcan
						<form style="display: inline" method="POST" action="{{ route('familias.destroy', $familia->id) }}">
							{!! csrf_field() !!}
							{!! method_field('DELETE') !!}
							@can('familias.destroy')
								<button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
							@else
								<button class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
							@endcan
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop