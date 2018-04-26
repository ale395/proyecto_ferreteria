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
                        <input id="num_familia" required="required" name="num_familia" class="form-control col-md-7 col-xs-12" type="text" value="{{ old('num_familia')}}">
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <br>
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion">
                        Descripcion
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="descripcion" required="required" name="descripcion" class="form-control col-md-7 col-xs-12" type="text" value="{{ old('descripcion')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{url('familias')}}"><button class="btn btn-info">Cancelar</button></a>                
            </div>
        </div>
	</form>
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