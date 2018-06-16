<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColectivosTest extends TestCase
{
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ObtenerColectivos()
    {
    	$this->get("/colectivo")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'colectivos' => true,
        		]);
    }
    
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function CrearColectivos()
    {	
    	
    	$response = $this->json('POST', '/colectivo', ['tramo' => 'asd']);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'colectivo' => [
            			'id',
            			'tramo',
            			'tarifa_id',
            			'horario_id'
        		],
    		]);
    }

    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ActualizarColectivos()
    {	
    	
    	$response = $this->json('PATCH', '/colectivo/13', ['tramo' => 'asda']);

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
    function ElmininarColectivos()
    {	
    	$response = $this->json('delete', '/colectivo/15');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }

}
