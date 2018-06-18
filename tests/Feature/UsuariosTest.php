<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsuariosTest extends TestCase
{
        /**
     * Test de control de obtener del controlador ParadasController.
     *
     * @test
     */
    function ObtenerUsuarios()
    {
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);
        
    	$this->get("/usuario")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'usuarios' => true,
        		]);
    }
    
    /**
     * Test de control de crear del controlador ParadasController.
     *
     * @test
     */
    function CrearUsuarios()
    {	
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('POST', '/usuario', [ 
        'nombre'=> 'fulano',
        'email' => 'fulanito@admin.com',
        'password'=>'hola1234']);

        $response
            ->assertStatus(200);

    	$response
    		->assertJsonStructure([
    				'message',
        			'usuario' => [
            			'id',
            			'nombre',
            			'email',
            			'password'
        		],
    		]);
    }

    /**
     * Test de control de actualizar del controlador ParadasController.
     *
     * @test
     */
    function ActualizarUsuarios()
    {	
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);

    	$response = $this->json('PATCH', '/usuario/11', [
            'nombre'=> 'Jorge Sampalobby',
            'email' => ' SampaoliGato@hotmail.com ',
            'password'=>'asdasd'
        ]);

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
    function ElmininarUsuarios()
    {	
        $user = User::where('id', 2)->first();
        \Auth::loginUsingId($user->id);

        $response = $this->json('delete', '/usuario/14');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
