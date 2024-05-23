<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100);
            $table->text('description');
            $table->timestamps();
        });


        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El volate se encuentra limpio?',
                'created_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los botones del volante funcionan correctamente?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El interruptor de limpiaparabrisas funciona correctamente?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿La consola esta limpia?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los botones de la  consola funcionan correctamente?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );


        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Las rendijas de AC se encuentran limpias?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );


        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El tablero esta limpio?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿La palanca de cambios esta limpia?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );


        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los parabrisas (frontal y trasero) están limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Las puertas están limpias (sin manchas u polvo acumulado)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los botones de las puertas se encuentran limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los marcos de las puertas se encuentran limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los vidrios de las puertas se encuentran limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los asientos se encuentran limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los tapetes se encuentran limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Las alfombras se encuentran limpias?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los botones para deslizar los asientos funcionan correctamente (en caso de que el vehículo cuente con los mismos)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Las palancas para deslizar los asientos funcionan correctamente (en caso de que el vehículo cuente con los mismos)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los cinturones de seguridad se encuentran limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los cinturones de seguridad funcionan correctamente?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El cielo se encuentra limpio?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Las alfombras de la cajuela se encuentran limpias?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿Los marcos de la cajuela están limpios?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El empaque de la cajuela se encuentra en buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El quemacocos se encuentra limpio (en caso de que el vehículo cuente con el mismo)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Interior',
                'description' => '¿El quemacocos funciona correctamente (en caso de que el vehículo cuente con el mismo)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿El vehículo se encuentra limpio?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Las loderas se encuentran limpias?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Las parrillas se encuentran limpias?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Los faros se encuentran limpios y pulidos (sin marcas amarillas)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿El parabrisas se encuentra limpio?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Las gomas del limpia parabrisas se encuentran en buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿La facia delantera y laterales frontales se encuentra libre de rayones, golpes o detalles de pintura?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿El motor se encuentra limpio?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Los faros y los cuartos funcionan correctamente?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        
        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿El vehículo se encuentra libre de rayones, golpes o detalles de pintura?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Los retrovisores se encuentran el buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Las puertas cierran perfectamente (Verificar que no hagan ruidos extraños o estén descuadradas)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Las luces traseras funcionan correctamente?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿La facia trasera se encuentra libre de rayones, golpes o detalles de pintura?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Los rines se encuentran limpios (sin sarro o grasa)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Los rines se encuentran en buen estado (sin rayones)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Exterior',
                'description' => '¿Las llantas se encuentran en buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        //Nuevos campos añadidos 

        DB::table('items')->insert(
            array(
                'category' => 'Hojalateria',
                'description' => '¿El chasis se encuentran en buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Hojalateria',
                'description' => '¿La pintura se encuentran en buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Hojalateria',
                'description' => '¿El cuerpo se encuentran en buen estado?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Hojalateria',
                'description' => '¿Cual es la condición del interior?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Hojalateria',
                'description' => '¿Cual es la condición del parabrisas?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        //Mecanica
        //condicion Oculta
        DB::table('items')->insert(
            array(
                'category' => 'Oculto',
                'description' => 'Condiciones del tren motriz',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        // Pregunta oculta 
        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Cual es la condición del motor (verificar si el checking se encuentra encendido)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Cual es la condición mecánica (verificar los mensajes del tablero)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        //condicion Oculta

        DB::table('items')->insert(
            array(
                'category' => 'Oculto',
                'description' => 'Condición neumáticos',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Cual es la condición de los rines?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        //condicion Oculta


        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Con que tipo de rin cuenta (Aluminio, Acero)?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Cual es el tamaño del rin?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Cual es el nivel de desgaste de las llantas?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('items')->insert(
            array(
                'category' => 'Mecanica',
                'description' => '¿Cual es la marca de las llantas?',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
