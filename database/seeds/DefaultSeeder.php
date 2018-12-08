<?php

use Illuminate\Database\Seeder;
use App\Familia;
use App\Linea;
use App\Rubro;
use App\UnidadMedida;
use App\ConceptoAjuste;
use App\ClasificacionCliente;
use App\Cajero;
use App\User;
use App\TipoProveedor;
use App\Proveedor;
use App\DatosDefault;
use App\Impuesto;
use App\FormaPago;
use App\Cotizacion;
use App\Banco;
use App\MotivoAnulacion;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Aca podemos cargar otros datos que podemos ir cargando por default nomas ya
     * como para la primera instalacion	
     * @return void
     */
    public function run()
    {
    	$motivo_anulacion = new MotivoAnulacion;
        $motivo_anulacion->setNombre('Error de impresión');
        $motivo_anulacion->save();

        $motivo_anulacion = new MotivoAnulacion;
        $motivo_anulacion->setNombre('Datos incorrectos');
        $motivo_anulacion->save();

        $motivo_anulacion = new MotivoAnulacion;
        $motivo_anulacion->setNombre('Cliente no acepta el comprobante');
        $motivo_anulacion->save();

        //Familia de producto por default - Generico
        $familia = new Familia();
        $familia->num_familia = '001';
        $familia->descripcion = 'GENERICO';
        $familia->save();

        $familia = new Familia();
        $familia->num_familia = '002';
        $familia->descripcion = 'Duchas';
        $familia->save();

        $familia = new Familia();
        $familia->num_familia = '005';
        $familia->descripcion = 'Máquinas';
        $familia->save();

        //Linea de producto por default - Generico
        $linea = new Linea();
        $linea->num_linea = '001';
        $linea->descripcion = 'GENERICO';
        $linea->save();

        $linea = new Linea();
        $linea->num_linea = '004';
        $linea->descripcion = 'Tokyo';
        $linea->save();

        $linea = new Linea();
        $linea->num_linea = '005';
        $linea->descripcion = 'Speed';
        $linea->save();

        $linea = new Linea();
        $linea->num_linea = '008';
        $linea->descripcion = 'Amanecer';
        $linea->save();

    	//Rubro de producto por default - Generico
        $rubro = new Rubro();
        $rubro->num_rubro = '001';
        $rubro->descripcion = 'GENERICO';
        $rubro->save();

        $rubro = new Rubro();
        $rubro->num_rubro = '003';
        $rubro->descripcion = 'Pinturas';
        $rubro->save();

        $rubro = new Rubro();
        $rubro->num_rubro = '006';
        $rubro->descripcion = 'Baño';
        $rubro->save();

    	//Unidad de medida por default - Unidad
        $unidadmedida = new UnidadMedida();
        $unidadmedida->num_umedida = 'UNI';
        $unidadmedida->descripcion = 'UNIDAD';
        $unidadmedida->save();

       	//Concepto ajuste por default - ajuste de existencia
        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '001';
        $conceptoajuste->descripcion = 'Consignacion';
        $conceptoajuste->signo = '+';
        $conceptoajuste->save();


        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '002';
        $conceptoajuste->descripcion = 'Faltante Inventario';
        $conceptoajuste->signo = '-';
        $conceptoajuste->save();

        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '003';
        $conceptoajuste->descripcion = 'Sobrante Inventario';
        $conceptoajuste->signo = '+';
        $conceptoajuste->save();

        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '004';
        $conceptoajuste->descripcion = 'Traslado-Salida';
        $conceptoajuste->signo = '-';
        $conceptoajuste->save();

        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '005';
        $conceptoajuste->descripcion = 'Traslado-Entrada';
        $conceptoajuste->signo = '+';
        $conceptoajuste->save();


        $conceptoajuste = new ConceptoAjuste();
        $conceptoajuste->num_concepto = '006';
        $conceptoajuste->descripcion = 'Averia o Rotura';
        $conceptoajuste->signo = '-';
        $conceptoajuste->save();
        //tipo proveedor 
        $tipoproveedor = new TipoProveedor();
        $tipoproveedor->codigo = '001';
        $tipoproveedor->nombre = 'Proveedor Local';
        $tipoproveedor->save();

        $proveedor = new Proveedor();
        $proveedor->codigo = 'xxxxxxx-x';
        $proveedor->nombre = 'Proveedor Generico';
        $proveedor->razon_social = 'Proveedor Generico';
        $proveedor->save();

        $datos_default = new DatosDefault();
        $datos_default->moneda_nacional_id = 1;
        $datos_default->lista_precio_id = 1;
        $datos_default->save();

        //Impuestos
        $impuesto = new Impuesto();
        $impuesto->codigo = '0';
        $impuesto->descripcion = 'Exento';
        $impuesto->porcentaje = 0;
        $impuesto->save();

        $impuesto = new Impuesto();
        $impuesto->codigo = '5';
        $impuesto->descripcion = 'IVA 5%';
        $impuesto->porcentaje = 5;
        $impuesto->save();

        $impuesto = new Impuesto();
        $impuesto->codigo = '10';
        $impuesto->descripcion = 'IVA 10%';
        $impuesto->porcentaje = 10;
        $impuesto->save();

        //Formas de Pago (y de cobro creo yo)
        $forma_pago = new FormaPago();
        $forma_pago->codigo = 'EFE';
        $forma_pago->descripcion = 'Efectivo';
        $forma_pago->control_valor = 1;
        $forma_pago->save();

        $forma_pago = new FormaPago();
        $forma_pago->codigo = 'CHE';
        $forma_pago->descripcion = 'Cheque';
        $forma_pago->control_valor = 1;
        $forma_pago->save();

        $forma_pago = new FormaPago();
        $forma_pago->codigo = 'TAR';
        $forma_pago->descripcion = 'Tarjeta';
        $forma_pago->control_valor = 1;
        $forma_pago->save();


        $forma_pago = new FormaPago();
        $forma_pago->codigo = 'NC';
        $forma_pago->descripcion = 'Nota de Credito';
        $forma_pago->control_valor = 1;
        $forma_pago->save();

        $forma_pago = new FormaPago();
        $forma_pago->codigo = 'RET';
        $forma_pago->descripcion = 'Retencion';
        $forma_pago->control_valor = 1;
        $forma_pago->save();

        //Formas de Pago (y de cobro creo yo)
        $banco = new Banco();
        $banco->codigo = 'ITA';
        $banco->nombre = 'Itau';
        $banco->activo = true;
        $banco->save();

        $banco = new Banco();
        $banco->codigo = 'VIS';
        $banco->nombre = 'Vision Banco';
        $banco->activo = true;
        $banco->save();

        $banco = new Banco();
        $banco->codigo = 'SUD';
        $banco->nombre = 'Sudameris';
        $banco->activo = true;
        $banco->save();

        $banco = new Banco();
        $banco->codigo = 'GNB';
        $banco->nombre = 'Banco GNB';
        $banco->activo = true;
        $banco->save();

        $banco = new Banco();
        $banco->codigo = 'BAS';
        $banco->nombre = 'Banco BASA';
        $banco->activo = true;
        $banco->save();

        //seeder de la forma de pago

        /*
        //traemos el usuario
        $usuario = User::where('email', 'admin@ferregest.com')->first();

        //creamos el cajero
        $cajero = new Cajero();
        $cajero->num_cajero = '001';
        $cajero->descripcion = 'Administrador';
        $cajero->save();
        
        //asignamos el usuario al cajeros
        $cajero->usuario()->associate($usuario);
        $cajero->save();
        */
    }
}
