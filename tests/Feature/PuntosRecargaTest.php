<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PuntosRecargaTest extends TestCase
{
    function ObtenerPuntos()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $this->get("/punto")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'puntos' => true,
        		]);
    }
    
    /**
     * Test de control de crear del controlador ParadasController.
     *
     * @test
     */
    function CrearPuntos()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/punto', [
            'nombre' => 'Punto de recarga Terminal Playa', 
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
    function ActualizarPuntos()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('PATCH', '/punto/1409', [
            'nombre' => 'Punto de recarga Terminal Playaa', 
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
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
    	$response = $this->json('delete', '/punto/1415');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
