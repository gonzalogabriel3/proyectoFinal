<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HorarioTest extends TestCase
{
    /**
     * Test de control de metodos del controlador HorariosController.
     *
     * @test
     */
    function ObtenerHorarios()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $this->get("/horario")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'horarios' => true,
        		]);
    }
    
    /**
     * Test de control de metodos del controlador HorariosController.
     *
     * @test
     */
    function CrearHorarios()
    {	
    	$user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/horario', ['salida' => '00:00','llegada' => '00:30','dias'=> 'Todos los Dias','tramo'=> 1 ]);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'colectivo' => [
            			'id',
            			'salida',
                        'llegada',
                        'dias',
                        'tramo_id'
        		],
    		]);
    }

    /**
     * Test de control de metodos del controlador HorariosController.
     *
     * @test
     */
    function ActualizarHorarios()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('PATCH', '/horario/36', [ 'llegada'=>'00:30', 'salida' => '00:00', 'dias'=> 'Lunes a Viernes' ,'tramo'=> 1]);

        $response
            ->assertStatus(200);
        $response
    		->assertJsonStructure([
    				'message',
    		]);

    }
    /**
     * Test de control de metodos del controlador HorariosController.
     *
     * @test
     */
    function ElmininarHorarios()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('delete', '/colectivo/43');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
