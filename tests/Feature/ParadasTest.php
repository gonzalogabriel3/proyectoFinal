<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParadasTest extends TestCase
{
        /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ObtenerParadas()
    {
    	$this->get("/parada")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'paradas' => true,
        		]);
    }
    
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function CrearParadas()
    {	

    	$response = $this->json('POST', '/parada', [
            'nombre' => 'Parada Terminal Playa', 
            'latitud' => '-123123',
            'longitud' => '-654321']);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'parada' => [
            			'id',
            			'nombre',
            			'geom'
        		],
    		]);
    }

    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ActualizarParadas()
    {	
    	$response = $this->json('PATCH', '/parada/1411', [
            'nombre' => 'Parada Terminaal Playaa', 
            'latitud' => '-123123',
            'longitud' => '-6543123']);

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
    function ElmininarParadas()
    {	
        
    	$response = $this->json('delete', '/parada/1413');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
