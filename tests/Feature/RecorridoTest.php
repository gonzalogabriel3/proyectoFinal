<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecorridoTest extends TestCase
{
    /**
     * Test de control de obtener del controlador RecorridoController.
     *
     * @test
     */
    function ObtenerRecorridos()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $this->get("/recorrido")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'recorridos' => true,
                ]);
    }
    /**
     * Test de control de crear del controlador RecorridoController.
     *
     * @test
     */
    function CrearRecorrido()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/recorrido', [
            'nombre' => 'RECORRIDO EJEMPLO', 
            'puntos' => ['-123123,-1123123','-1212331,-1231231','-123123,-123123'],
            ]);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'recorrido' => [
            			'id',
            			'nombre',
            			'geom'
        		],
    		]);
    }

    /**
     * Test de control de actualizar del controlador RecorridoController.
     *
     * @test
     */
    function ActualizarRecorrido()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('PATCH', '/recorrido/3', [
            'nombre' => 'Directo Rawson - Playa Union vuelta', 
            'puntos' => ['-123123,-1123123','-1212331,-1231231','-123123,-123123','-1212121,-121212']
            ]);

        $response
            ->assertStatus(200);
        $response
    		->assertJsonStructure([
    				'message',
    		]);

    }
    /**
     * Test de control de eliminar del controlador RecorridoController.
     *
     * @test
     */
    function ElmininarRecorrido()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
    	$response = $this->json('delete', '/recorrido/5');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
