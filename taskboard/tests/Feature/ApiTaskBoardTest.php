<?php

namespace Tests\Feature;

use App\Models\Comercio;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTaskBoardTest extends TestCase
{
    use RefreshDatabase;

    private User $ana;
    private User $carlos;
    private Comercio $comercioAna;
    private Comercio $comercioCarlos;
    private Transaccion $transaccionAna;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ana = User::factory()->create([
            'name' => 'Ana López',
            'email' => 'ana@taskboard.test',
            'password' => 'password123',
        ]);
        $this->carlos = User::factory()->create([
            'name' => 'Carlos Pérez',
            'email' => 'carlos@taskboard.test',
            'password' => 'password123',
        ]);

        $this->comercioAna = $this->ana->comercios()->create([
            'nombre_comercio' => 'Café Amanecer',
            'rubro' => 'Restaurante',
        ]);
        $this->ana->comercios()->create([
            'nombre_comercio' => 'Pupusería Doña Marta',
            'rubro' => 'Restaurante',
        ]);
        $this->comercioCarlos = $this->carlos->comercios()->create([
            'nombre_comercio' => 'Ferretería El Tornillo',
            'rubro' => 'Ferretería',
        ]);

        $this->transaccionAna = $this->comercioAna->transacciones()->create([
            'cliente_nombre' => 'María López',
            'monto' => 45,
            'estado' => 'Completada',
        ]);
    }

    public function test_login_valido_responde_201_con_token(): void
    {
        $this->postJson('/api/login', [
            'email' => 'ana@taskboard.test',
            'password' => 'password123',
            'dispositivo' => 'prueba',
        ])->assertCreated()->assertJsonStructure(['token', 'tipo']);
    }

    public function test_login_con_clave_incorrecta_responde_422(): void
    {
        $this->postJson('/api/login', [
            'email' => 'ana@taskboard.test',
            'password' => 'incorrecta',
            'dispositivo' => 'prueba',
        ])->assertUnprocessable();
    }

    public function test_sin_token_responde_401(): void
    {
        $this->getJson('/api/comercios')->assertUnauthorized();
    }

    public function test_yo_devuelve_a_ana(): void
    {
        Sanctum::actingAs($this->ana);

        $this->getJson('/api/yo')->assertOk()->assertJsonPath('name', 'Ana López');
    }

    public function test_ana_solo_ve_sus_2_comercios(): void
    {
        Sanctum::actingAs($this->ana);

        $this->getJson('/api/comercios')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_no_se_expone_user_id(): void
    {
        Sanctum::actingAs($this->ana);

        $this->getJson('/api/comercios')->assertOk()->assertJsonMissingPath('data.0.user_id');
    }

    public function test_carlos_solo_ve_su_comercio(): void
    {
        Sanctum::actingAs($this->carlos);

        $this->getJson('/api/comercios')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_comercio_ajeno_responde_404(): void
    {
        Sanctum::actingAs($this->ana);

        $this->getJson('/api/comercios/' . $this->comercioCarlos->id)->assertNotFound();
    }

    public function test_crear_en_comercio_ajeno_responde_422(): void
    {
        Sanctum::actingAs($this->ana);

        $this->postJson('/api/transacciones', [
            'comercio_id' => $this->comercioCarlos->id,
            'cliente_nombre' => 'Intruso',
            'monto' => 10,
        ])->assertUnprocessable();
    }

    public function test_carlos_no_puede_borrar_transaccion_de_ana(): void
    {
        Sanctum::actingAs($this->carlos);

        $this->deleteJson('/api/transacciones/' . $this->transaccionAna->id)->assertNotFound();
    }

    public function test_logout_revoca_el_token(): void
    {
        $token = $this->postJson('/api/login', [
            'email' => 'ana@taskboard.test',
            'password' => 'password123',
            'dispositivo' => 'prueba',
        ])->json('token');

        $this->withToken($token)->getJson('/api/yo')->assertOk();
        $this->withToken($token)->postJson('/api/logout')->assertNoContent();

        // Limpia el usuario que el guard guardó en memoria durante la prueba
        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/yo')->assertUnauthorized();
    }
}
