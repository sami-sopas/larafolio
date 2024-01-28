<?php

namespace Tests\Feature\Hero;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InfoTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function hero_info_component_can_be_rendered()
    {
        $this->get('/')
            ->assertStatus(200) //Verificar que se carga la pagina
            ->assertSeeLivewire('hero.info'); //Verificar que se renderiza el componente
    }
}
