@extends('home')

@section('content')

	<form method="POST" id="create_familia" action="{{ route('familias.store') }}">
	{!! csrf_field() !!}
		<div id="ok"></div>
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
			<label>Codigo
				<input type="text" class="mayusculas" name="num_familia" id="num_familia" value="{{ old('num_familia')}}">

			</label>
		</div>

		<div class="form-group">
			<label>Descripcion
				<input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion')}}">
			</label>
		</div>

		<button type="submit" class="btn btn-success">Guardar</button>
		

	</form>

	<a href="{{url('familias')}}"><button class="btn btn-info">Cancelar</button></a>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#ok").hide();

            $("#create_familia").validate({
                rules: {
                    num_familia: { required: true, minlength: 2},
                    descripcion: { required: true, minlength: 2}
                },
                messages: {
                    num_familia: "Debe introducir su nombre.",
                    descripcion: "Debe introducir su apellido."
                },

                
                submitHandler: function(form){
                    var dataString = 'num_familia='+$('#num_familia').val()+'&descripcion='+$('#descripcion').val()+'...';
                    $.ajax({
                        type: "POST",
                        url:"{{ url('familias.store') }}",
                        data: dataString,
                        success: function(data){
                            $("#ok").html(data);
                            $("#ok").show();
                            $("#formid").hide();
                        }
                    });
                }
                
            });
        });
    </script> 
@stop