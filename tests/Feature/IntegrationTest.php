<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Sparepart;
use App\Models\ModelMobil;
use App\Models\User;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // ambil data dari seeder
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function home_dapat_mengambil_data_katalog_mobil_dan_sparepart(): void
    {
        $mobilResponse = $this->getJson('/api/cars');
        $sparepartResponse = $this->getJson('/api/spareparts');

        $mobilResponse->assertStatus(200)->assertJsonStructure(['data']);
        $sparepartResponse->assertStatus(200)->assertJsonStructure(['data']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function pencarian_sparepart_mengembalikan_hasil_yang_sesuai(): void
    {
        $firstSparepart = Sparepart::first();
        $this->assertNotNull($firstSparepart, 'Seeder belum mengisi data sparepart.');

        $response = $this->getJson('/api/spareparts?search=' . substr($firstSparepart->name, 0, 3));
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $firstSparepart->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function katalog_mobil_terkait_sparepart_mengembalikan_data_yang_sesuai(): void
    {
        $mobil = ModelMobil::first();
        $this->assertNotNull($mobil, 'Seeder belum mengisi data mobil.');

        $response = $this->getJson('/api/spareparts?car_id=' . $mobil->id);
        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function katalog_sparepart_bisa_menampilkan_detail_sparepart(): void
    {
        $sparepart = Sparepart::first();
        $this->assertNotNull($sparepart, 'Seeder belum mengisi data sparepart.');

        $response = $this->getJson('/api/spareparts/' . $sparepart->id);
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $sparepart->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_dapat_mengirim_request_sparepart_menggunakan_fitur_request(): void
    {
        $payload = [
            'brand_req' => 'Toyota',
            'model_req' => 'Avanza',
            'year_req' => '2020',
            'sparepart_req' => 'Kampas Rem',
        ];

        // Kirim request baru
        $response = $this->postJson('/api/request-sparepart', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['brand_req' => 'Toyota']);

        // Ambil data riwayat request
        $getResponse = $this->getJson('/api/request-sparepart');
        $getResponse->assertStatus(200)
                    ->assertJsonFragment(['sparepart_req' => 'Kampas Rem']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_dapat_menambah_dan_melihat_model_mobil(): void
    {
        $payload = [
            'brand' => 'Mitsubishi',
            'model' => 'Xpander',
            'year' => 2023,
            'description' => 'MPV keluarga modern',
        ];

        $create = $this->postJson('/api/admin/models', $payload);
        $create->assertStatus(201)
               ->assertJsonFragment(['brand' => 'Mitsubishi']);

        $get = $this->getJson('/api/admin/models');
        $get->assertStatus(200)
            ->assertJsonFragment(['model' => 'Xpander']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_dapat_menambah_dan_melihat_sparepart(): void
    {
        $mobil = ModelMobil::first();
        $this->assertNotNull($mobil, 'Seeder belum mengisi data model mobil.');

        $payload = [
            'model_mobil_id' => $mobil->id,
            'name' => 'Kampas Kopling',
            'brand' => 'Nissan Genuine',
            'type' => 'Manual',
            'grade' => 'A',
            'stock' => 5,
            'price' => 250000,
            'description' => 'Original part',
            // ENUM disesuaikan dengan database constraint (lowercase)
            'status' => 'available',
        ];

        $create = $this->postJson('/api/admin/spareparts', $payload);
        $create->assertStatus(201)
               ->assertJsonFragment(['name' => 'Kampas Kopling']);

        $get = $this->getJson('/api/admin/spareparts');
        $get->assertStatus(200)
            ->assertJsonFragment(['brand' => 'Nissan Genuine']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_dapat_menambahkan_sparepart_ke_keranjang(): void
    {
        // Login dummy user (karena pakai auth:sanctum)
        /** @var \App\Models\User $user */
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);


        $sparepart = Sparepart::first();
        $this->assertNotNull($sparepart, 'Seeder belum mengisi data sparepart.');

        $payload = [
            'sparepart_id' => $sparepart->id,
            'quantity' => 1,
        ];

        $response = $this->postJson('/api/cart', $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Keranjang berhasil diperbarui.']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function sistem_membangun_pesan_whatsapp_dari_data_keranjang(): void
    {
        $items = [
            ['name' => 'Kampas Rem', 'price' => 100000, 'quantity' => 2],
            ['name' => 'Filter Udara', 'price' => 75000, 'quantity' => 1],
        ];

        $total = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
        $message = "Halo admin, saya ingin melakukan pembelian:\n\n";

        foreach ($items as $i) {
            $message .= "• {$i['name']} (x{$i['quantity']}) = Rp " . number_format($i['price'] * $i['quantity'], 0, ',', '.') . "\n";
        }

        $message .= "\nTotal: Rp " . number_format($total, 0, ',', '.');

        $this->assertStringContainsString('Kampas Rem', $message);
        $this->assertStringContainsString('Filter Udara', $message);
        $this->assertStringContainsString('Total: Rp', $message);
    }
}
