<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ParkingApiTest extends TestCase
{
    #[Test]
    public function lista_todos_los_parkings_existentes(): void
    {
        $this->getJson('/api/parkings')
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'name', 'address', 'latitude', 'longitude', 'created_at', 'updated_at']
            ]);
    }

    #[Test]
    public function obtiene_un_parking_por_id(): void
    {
        $all = $this->getJson('/api/parkings')->json();
        $id = $all[0]['id'];

        $this->getJson("/api/parkings/{$id}")
            ->assertOk()
            ->assertJsonFragment(['id' => $id]);
    }

    #[Test]
    public function devuelve_404_si_el_parking_no_existe(): void
    {
        $this->getJson('/api/parkings/999999')
            ->assertStatus(404);
    }

    #[Test]
    public function crea_un_nuevo_parking_y_valida_datos_requeridos(): void
    {
        $this->postJson('/api/parkings', [])->assertStatus(422);

        $nuevo = $this->postJson('/api/parkings', [
            'name' => 'Parking Test',
            'address' => 'Calle Falsa 123',
            'latitude' => -34.60,
            'longitude' => -58.38,
        ])->assertCreated()->json();

        $this->assertNotNull($nuevo['id']);

        $this->getJson('/api/parkings')
            ->assertOk()
            ->assertJsonFragment(['id' => $nuevo['id'], 'name' => 'Parking Test']);
    }

    #[Test]
    public function busca_el_parking_mas_cercano_y_valida_fuera_de_rango(): void
    {
        $near = $this->postJson('/api/parking/nearest', [
            'latitude' => -34.6037,
            'longitude' => -58.3816,
        ])->assertOk()->json();

        $this->assertArrayHasKey('parking', $near);
        $this->assertFalse($near['exceeds_500']);

        $far = $this->postJson('/api/parking/nearest', [
            'latitude' => -34.90,
            'longitude' => -58.90,
        ])->assertOk()->json();

        $this->assertTrue($far['exceeds_500']);

        $this->assertDatabaseHas('request_logs', [
            'latitude' => -34.90,
            'longitude' => -58.90
        ]);
    }

    #[Test]
    public function valida_payloads_invalidos_en_nearest(): void
    {
        $this->postJson('/api/parking/nearest', [])->assertStatus(422);

        $this->postJson('/api/parking/nearest', [
            'latitude' => 'abc',
            'longitude' => -58.38
        ])->assertStatus(422);
    }

    #[Test]
    public function valida_que_los_datos_persisten_en_bd(): void
    {
        $nuevo = $this->postJson('/api/parkings', [
            'name' => 'Persistencia Test',
            'address' => 'Persistencia 500',
            'latitude' => -34.55,
            'longitude' => -58.40,
        ])->assertCreated()->json();

        $this->assertDatabaseHas('parkings', [
            'id' => $nuevo['id'],
            'name' => 'Persistencia Test'
        ]);
    }
}
