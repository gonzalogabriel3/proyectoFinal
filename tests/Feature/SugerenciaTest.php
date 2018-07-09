<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SugerenciaTest extends TestCase
{
    /**
     * Test de control de obtener del controlador SugerenciaController.
     *
     * @test
     */
    function ObtenerSugerencias()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $this->get("/sugerencia")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'sugerencia' => true,
                ]);
    }
    /**
     * Test de control de crear del controlador SugerenciaController.
     *
     * @test
     */
    function CrearSugerencia()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/sugerencia', [
            'sugerencia' => 'Muy buena la app, pero le hacen falta retoques',
            'id' => 23
            ]);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'sugerencia' => [
            			'id',
            			'sugerencia',
                        'usuario_id',
        		],
    		]);
    }

    /**
     * Test de control de actualizar del controlador SugerenciaController.
     *
     * @test
     */
    function ActualizarSugerencia()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('PATCH', '/sugerencia/13', [
            'sugerencia' => 'muy buena la app',
            ]);

        $response
            ->assertStatus(200);
        $response
    		->assertJsonStructure([
    				'message',
    		]);

    }
    /**
     * Test de control de eliminar del controlador SugerenciaController.
     *
     * @test
     */
    function ElmininarSugerencia()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
    	$response = $this->json('delete', '/sugerencia/13');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
