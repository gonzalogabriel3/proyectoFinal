<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsuariosTest extends TestCase
{
        /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ObtenerUsuarios()
    {

    	$this->get("/usuario")
        	 ->assertStatus(200)
        	 ->assertJson([
        	 		'usuarios' => true,
        		]);
    }
    
    /**
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function CrearUsuarios()
    {	
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
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ActualizarUsuarios()
    {	
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
     * Test de control de metodos del controlador ColectivosController.
     *
     * @test
     */
    function ElmininarUsuarios()
    {	
    	$response = $this->json('delete', '/usuario/12');

        $response
            ->assertStatus(200);
    	$response
    		->assertJsonStructure([
    				'message',
    		]);
    }
}
