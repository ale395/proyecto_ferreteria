<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    //aca podemos ejecutar los seeders para completar datos por defecto nomas ya.
    public function run()
    {
	    // Creamos los permisos
    	$this->call(PermissionTableSeeder::class);

	    // La creación de datos de roles debe ejecutarse primero
    	$this->call(RoleTableSeeder::class);	

	    // Los usuarios necesitarán los roles previamente generados
    	$this->call(UserTableSeeder::class);

        //Carga los Modulos del Sistema
        $this->call(ModuloTableSeeder::class);

        //Carga los Conceptos del Sistema
        $this->call(ConceptoTableSeeder::class);

        //carga los datos de inicio
        //$this->call(DefaultSeeder::class);
    }
}
