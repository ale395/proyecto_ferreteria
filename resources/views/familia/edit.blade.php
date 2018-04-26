@extends('home')

@section('content')

	<form method="POST" action="{{ route('familias.update', $familia->id) }}">
	{!! method_field('PUT') !!}
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

        <div class="x_panel"> 
            <h2>
                <div class="x_title">
                        Familias                 
                </div>
            </h2>
            <br>
            <div class="x_content">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="num_familia">
                        Codigo
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="num_familia" required="required" name="num_familia" class="form-control col-md-7 col-xs-12" type="text" value="{{ $familia->num_familia }}" readonly>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <br>
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion">
                        Descripcion
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="descripcion" required="required" name="descripcion" class="form-control col-md-7 col-xs-12" type="text" value="{{ $familia->descripcion }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{url('familias')}}"><button class="btn btn-info">Cancelar</button></a>                
            </div>
        </div>

        <!--
		<div class="form-group">
<<<<<<< HEAD
			<label>Codigo	
=======
			<label>Num. Familia
>>>>>>> f689cf6b2212b5585f51371f770401b7e031d4f9
				<input type="text" name="num_familia" value="{{ $familia->num_familia }}" readonly>
			</label>
		</div>

		<div class="form-group">
			<label>Descripcion
				<input type="text" class="mayusculas" name="descripcion" value="{{ $familia->descripcion }}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		-->

	</form>
	<!--
	<a href="{{url('familias')}}"><button class="btn btn-info">Cancelar</button></a>
	-->
@stop