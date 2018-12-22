<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<div class="row">
	    <div class="col-xs-7 well well-sm">
		    <h1 class="text-center">{{$empresa->razon_social}}</h1>
		    {{$empresa->eslogan}}
		    <br>
		    {{$empresa->direccion}}
		    <br>
		    {{$empresa->telefono}}
	    </div>
	    <div class="col-xs-4 text-center well well-sm">
	        <strong>RUC: {{$empresa->ruc}}</strong><br>
	        Inicio Vigencia {{$factura_cab->serie->timbrado->getFechaInicioVigencia()}}<br>
	        Fin Vigencia {{$factura_cab->serie->timbrado->getFechaFinVigencia()}}<br>
	        Timbrado {{$factura_cab->serie->timbrado->getNroTimbrado()}}<br>
	        <p class="text-center"><strong>FACTURA</strong></p>
	        N° {{$factura_cab->getNroFacturaIndex()}}
	    </div>
	</div>
	<br>
	<div class="row">
        <table class="table table-bordered">
        	<tbody>
        		<tr>
        			<td><strong>Fecha de Emision:</strong> {{$factura_cab->getFechaEmision()}}</td>
        			<td width="30%"><strong>Condicion de Venta:</strong> {{$factura_cab->getTipoFacturaIndex()}}</td>
        		</tr>
        		<tr>
        			<td colspan="2"><strong>RUC:</strong> {{$factura_cab->cliente->getNroDocumentoIndex()}}</td>
        		</tr>
        		<tr>
        			<td colspan="2"><strong>Nombre o Razon Social:</strong> {{$factura_cab->cliente->getNombreIndex()}}</td>
        		</tr>
        		<tr>
        			<td><strong>Direccion:</strong> {{$factura_cab->cliente->getDireccion()}}</td>
        			<td width="25%"><strong>Tel:</strong> {{$factura_cab->cliente->getTelefonoCelular()}}</td>
        		</tr>
        	</tbody>
        </table>
    </div>
    <br>
    <div class="row">
    	<table class="table table-bordered">
    		<thead>
    			<tr class="active">
    				<th class="text-center" width="6%"><font size="2">Cant</font></th>
    				<th>Descripcion</th>
    				<th class="text-center" width="10%">Prec. Unit.</th>
                    <th class="text-center" width="10%">Descuento</th>
    				<th class="text-center" width="10%">Exentas</th>
    				<th class="text-center" width="10%">IVA 5%</th>
    				<th class="text-center" width="10%">IVA 10%</th>
    			</tr>
    		</thead>
    		<tbody>
    			@foreach($factura_cab->facturaDetalle as $detalle)
                    <tr>
                        <td class="text-center"><font size="1">{{$detalle->getCantidadNumber()}}</font></td>
                        <td><font size="1">{{$detalle->articulo->getDescripcion()}}</font></td>
                        <td class="text-right"><font size="1">{{$detalle->getPrecioUnitario()}}</font></td> 
                        <td class="text-right"><font size="1">{{$detalle->getMontoDescuento()}}</font></td>
                        @if($detalle->getPorcentajeIva() == 0)
                        	<td class="text-right"><font size="1">{{$detalle->getMontoTotal()}}</font></td>
                        	<td class="text-right"><font size="1">0</font></td>
                        	<td class="text-right"><font size="1">0</font></td>
                        @elseif($detalle->getPorcentajeIva() == 5)
                        	<td class="text-right"><font size="1">0</font></td>
                        	<td class="text-right"><font size="1">{{$detalle->getMontoTotal()}}</font></td>
                        	<td class="text-right"><font size="1">0</font></td>
                        @else
                        	<td class="text-right"><font size="1">0</font></td>
                        	<td class="text-right"><font size="1">0</font></td>
                        	<td class="text-right"><font size="1">{{$detalle->getMontoTotal()}}</font></td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    	<td colspan="5"><strong>Sub-Totales:</strong></td>
                    	<td class="text-right"><font size="1">{{$total_grav_5}}</font></td>
                    	<td class="text-right"><font size="1">{{$total_grav_10}}</font></td>
                    </tr>
                    <tr>
                    	<td colspan="6"><strong>Total a pagar:</strong> {{$total_en_letras}}</td>
                    	<td class="text-right">{{$factura_cab->getMontoTotal()}}</td>
                    </tr>
                    <tr>
                    	<td colspan="2"><strong>Liquidacion del IVA: (5%)</strong><font size="1"> {{$total_iva_5}}</font></td>
                    	<td colspan="3"><strong>(10%)</strong><font size="1"> {{$total_iva_10}}</font></td>
                    	<td colspan="2"><strong>Total IVA:</strong><font size="1"> {{$total_iva}}</font></td>
                    </tr>
    		</tbody>
    	</table>
    </div>
</div>
</body>
</html>