<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComentarioTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    function ObtenerComentarios()
    {
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $this->get("/comentario")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'comentarios' => true,
        		]);
    }
    
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function CrearComentarios()
    {	
    	$user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/comentario', ['comentario' => 'Esto es un comentario desde el test','usuario_id' => '1']);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'comentario' => [
            			'id',
                        'comentario',
                        'usuario_id'
        		],
    		]);
    }

    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ActualizarComentarios()
    {	
        $user = User::where('id', 1)->first();
        \Auth::loginUsingId($user->id);
        
        $response = $this->json('PATCH', '/comentario/5', ['comentario' => 'comentario','usuario_id' => '6']);

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
        
        $response = $this->json('delete', '/comentario/6');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
