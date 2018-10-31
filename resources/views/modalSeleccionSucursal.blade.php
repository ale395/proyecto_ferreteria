<div id="modal-sucursal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h2 class="modal-title" id="modal-title">¿En que sucursal desea operar?</h2>
                </div>

                <div class="modal-body">
                    <div class="list-group">
		      			@foreach(Auth::user()->empleado->sucursales as $sucursal)
		      				<a onclick="actualizaSucursal('{{Auth::user()->empleado->id}}','{{$sucursal->id}}')" class="list-group-item list-group-item-action"><h5>{{$sucursal->getNombre()}}</h5></a>
		      			@endforeach
		  			</div>
                </div>
        </div>
    </div>
</div>
