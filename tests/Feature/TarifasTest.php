<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TarifasTest extends TestCase
{
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ObtenerTarifas()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $this->get("/tarifa")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'tarifas' => true,
        		]);
    }
    
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function CrearTarifas()
    {	
    	$user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/tarifa', ['monto' => '0.50']);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'tarifa' => [
            			'id',
            			'monto',
        		],
    		]);
    }

    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ActualizarTarifas()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('PATCH', '/tarifa/5', ['monto' => '0.90']);

        $response
            ->assertStatus(200);
        $response
    		->assertJsonStructure([
    				'message',
    		]);

    }
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function EliminarTarifas()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('delete', '/tarifa/6');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }

}