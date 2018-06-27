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
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
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
    	$user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('POST', '/colectivo',[
         'tramo' => 'asd',
         'tarifa_id' => '2',
         'horarios' => ['1','2','3','4','5','6']]);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'colectivo' => [
            			'id',
            			'tramo',
            			'tarifa_id'
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
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('PATCH', '/colectivo/17', [
         'tramo' => 'asd',
         'tarifa_id' => '2',
         'horarios' => ['1','2','3','4','5','6']
        ]);

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
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('delete', '/colectivo/18');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }

}
