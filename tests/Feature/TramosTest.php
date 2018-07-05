<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TramosTest extends TestCase
{
    /**
     * Test de control de obtener del controlador TramoController.
     *
     * @test
     */
    function ObtenerTramos()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $this->get("/tramo")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'tramos' => true,
                ]);
    }
    /**
     * Test de control de crear del controlador TramoController.
     *
     * @test
     */
    function CrearTramo()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/tramo', [
            'nombre' => 'Rawson Urbano 1',
            'inicio' => '-43.5674',
            'fin' => '-69.9000',
            'recorrido_id' => 1,
            'colectivos' => [1,2,3,4]
            ]);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'tramo' => [
            			'id',
            			'nombre',
                        'inicio',
                        'fin',
                        'recorrido_id'
        		],
    		]);
    }

    /**
     * Test de control de actualizar del controlador TramoController.
     *
     * @test
     */
    function ActualizarTramo()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('PATCH', '/tramo/3', [
            'nombre' => 'Rawson Urbano 1',
            'inicio' => '-43.5678',
            'fin' => '-69.9001',
            'recorrido_id' => 1,
            'colectivos' => [1,4]
            ]);

        $response
            ->assertStatus(200);
        $response
    		->assertJsonStructure([
    				'message',
    		]);

    }
    /**
     * Test de control de eliminar del controlador TramoController.
     *
     * @test
     */
    function ElmininarTramo()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
    	$response = $this->json('delete', '/tramo/5');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}

