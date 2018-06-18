<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParadasTest extends TestCase
{
        /**
     * Test de control de obtener del controlador ParadasController.
     *
     * @test
     */
    function ObtenerParadas()
    {
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);

        $this->get("/parada")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'paradas' => true,
        		]);
    }
    
    /**
     * Test de control de crear del controlador ParadasController.
     *
     * @test
     */
    function CrearParadas()
    {	
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);

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
     * Test de control de actualizar del controlador ParadasController.
     *
     * @test
     */
    function ActualizarParadas()
    {	
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('PATCH', '/parada/1409', [
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
     * Test de control de eliminar del controlador ParadasController.
     *
     * @test
     */
    function ElmininarParadas()
    {	
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);
        
    	$response = $this->json('delete', '/parada/1415');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
