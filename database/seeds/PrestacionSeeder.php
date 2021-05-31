<?php

use App\Prestacion;
use Illuminate\Database\Seeder;

class PrestacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Prestacion::class)->create([
            'nombre' => 'Relevamiento Bucal',
            'descripcion' => 'El primer paso para una salud bucal es proceder a un relevamiento bucal, no por eso es el menos importante. El conocer el problema es lo mas importante para darle solución y nosotros estamos preparados para ayudarte a encontrar tu salud bucal.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Restauracion de piezas dentales',
            'descripcion' => 'Luego de un correcto relevamiento bucal, llega el momento de la restauracion de la pieza que lo necesita, devolviendole la función perdida mediante el uso de técnicas y materiales especificas.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Tratamiento de caries',
            'descripcion' => 'Es posible que no se presenten sintomas frente a una carie, pero pueden causar infección, dolor e inclusive perdida de los dientes, por eso hay que actuar rapidamente y aplicar tratamiento, evitando futuros problemas y promoviendo la salud dental.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Endodoncia',
            'descripcion' => 'En caso de encontrar un diente con caries, una de los técnicas que se aplican es la Endodoncia. Consiste en la extirpación de la pulpa dental y el posterior relleno y sellado de la cavidad pulpar con un material inerte.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Extracciónes de piezas dentales',
            'descripcion' => 'Hay casos en el que conservar la pieza dental no es posible debido a su deterioro, la unica posibilidad, en un principio, es la extracción de la misma, para luego poder aplicar un tratamiento estetico bucal.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Limpieza bucal',
            'descripcion' => 'La limpieza oral es una practica fundamental diaria, pero tambien es un tratamiento que ejecuta tu especialista bucal, el mismo remueve las placas mas aferradas al diente con la intencion de prevenir cavidades, gengivitis, y enfermedades peridontales.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Reconstrucción de retracción gingival',
            'descripcion' => 'Si quieres saber si tienes retracción de las encías o crees que la tienes, lo primero que tienes que hacer es pedir cita al dentista y obtener asesoramiento profesional sobre cómo detener la retracción de las encías.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Blanqueamiento Bucal',
            'descripcion' => 'En la odontología estética, el blanqueamiento dental es un tratamiento dental estético que logra reducir varios tonos el color original de las piezas dentales, dejando los dientes más blancos y brillantes. Podras disfrutar de una sonrisa resplandeciente.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Carillas',
            'descripcion' => 'Son unas finas láminas de cerámica generalmente, también pueden ser de porcelana o cerómeros que se adhieren a la parte frontal o cara vestibular de los dientes mediante un cemento o una resina especial. Se utilizan para corregir la forma y el tamaño de los dientes.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Implantes',
            'descripcion' => 'Los implantes dentales se realizan sobre el hueso mandibular del paciente cuando la pieza dental se ha perdido. El objetivo del implante es, además de estético, funcional. Se trata de una solución segura y duradera.'
        ]);

        factory(Prestacion::class)->create([
            'nombre' => 'Ortodoncia Estética',
            'descripcion' => 'La Ortodoncia Estética es idéntica a la tradicional pero con aros y brackets de materiales diferentes que, a diferencia de los de metal, son muy poco visibles y resultan altamente discretos, por lo que no generan el problema visual propio de los convencionales.'
        ]);
    }
}
