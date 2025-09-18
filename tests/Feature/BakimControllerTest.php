<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bakim;
use App\Models\DegisecekParca;

class BakimControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'personel']);
    }

    public function test_admin_can_view_bakim_index()
    {
        $response = $this->actingAs($this->admin)->get('/bakim');
        $response->assertStatus(200);
        $response->assertSee('Servis Yönetimi');
    }

    public function test_staff_cannot_access_admin_bakim_index()
    {
        $response = $this->actingAs($this->staff)->get('/bakim');
        $response->assertStatus(403);
    }

    public function test_staff_can_view_their_bakim_index()
    {
        $response = $this->actingAs($this->staff)->get('/staff/bakim');
        $response->assertStatus(200);
        $response->assertSee('Servislerim');
    }

    public function test_admin_can_create_bakim()
    {
        $bakimData = [
            'plaka' => '34ABC123',
            'sase' => 'VIN123456789',
            'tahmini_teslim_tarihi' => now()->addDays(3)->format('Y-m-d'),
            'telefon_numarasi' => '05551234567',
            'musteri_adi' => 'Test Müşteri',
            'ucret' => 1500.50,
            'genel_aciklama' => 'Test bakım',
            'bakim_tarihi' => now()->format('Y-m-d'),
            'personel_id' => $this->staff->id
        ];

        $response = $this->actingAs($this->admin)->post('/bakim', $bakimData);
        
        $response->assertRedirect('/bakim');
        $this->assertDatabaseHas('bakim', [
            'plaka' => '34ABC123',
            'musteri_adi' => 'Test Müşteri'
        ]);
    }

    public function test_bakim_creation_requires_validation()
    {
        $response = $this->actingAs($this->admin)->post('/bakim', []);
        
        $response->assertSessionHasErrors(['plaka', 'musteri_adi', 'ucret']);
    }

    public function test_admin_can_view_bakim_details()
    {
        $bakim = Bakim::factory()->create(['admin_id' => $this->admin->id]);
        
        $response = $this->actingAs($this->admin)->get("/bakim/{$bakim->id}");
        $response->assertStatus(200);
        $response->assertSee($bakim->plaka);
    }

    public function test_admin_can_update_bakim()
    {
        $bakim = Bakim::factory()->create(['admin_id' => $this->admin->id]);
        
        $updateData = [
            'plaka' => '34XYZ789',
            'sase' => $bakim->sase,
            'tahmini_teslim_tarihi' => $bakim->tahmini_teslim_tarihi->format('Y-m-d'),
            'telefon_numarasi' => $bakim->telefon_numarasi,
            'musteri_adi' => 'Güncellenmiş Müşteri',
            'odeme_durumu' => 1,
            'bakim_durumu' => 'Tamamlandı',
            'ucret' => $bakim->ucret,
            'genel_aciklama' => $bakim->genel_aciklama,
            'bakim_tarihi' => $bakim->bakim_tarihi->format('Y-m-d'),
            'personel_id' => $this->staff->id
        ];

        $response = $this->actingAs($this->admin)->put("/bakim/{$bakim->id}", $updateData);
        
        $response->assertRedirect('/bakim');
        $this->assertDatabaseHas('bakim', [
            'id' => $bakim->id,
            'plaka' => '34XYZ789',
            'musteri_adi' => 'Güncellenmiş Müşteri'
        ]);
    }

    public function test_admin_can_delete_bakim()
    {
        $bakim = Bakim::factory()->create(['admin_id' => $this->admin->id]);
        
        $response = $this->actingAs($this->admin)->delete("/bakim/{$bakim->id}");
        
        $response->assertRedirect('/bakim');
        $this->assertDatabaseMissing('bakim', ['id' => $bakim->id]);
    }

    public function test_bakim_filtering_works()
    {
        Bakim::factory()->create([
            'plaka' => '34ABC123',
            'bakim_durumu' => 'Devam Ediyor',
            'admin_id' => $this->admin->id
        ]);
        
        Bakim::factory()->create([
            'plaka' => '34XYZ789',
            'bakim_durumu' => 'Tamamlandı',
            'admin_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get('/bakim?bakim_durumu=Tamamlandı');
        
        $response->assertStatus(200);
        $response->assertSee('34XYZ789');
        $response->assertDontSee('34ABC123');
    }

    public function test_bakim_search_works()
    {
        Bakim::factory()->create([
            'plaka' => '34ABC123',
            'musteri_adi' => 'Ahmet Yılmaz',
            'admin_id' => $this->admin->id
        ]);
        
        Bakim::factory()->create([
            'plaka' => '34XYZ789',
            'musteri_adi' => 'Mehmet Demir',
            'admin_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get('/bakim?search=Ahmet');
        
        $response->assertStatus(200);
        $response->assertSee('34ABC123');
        $response->assertDontSee('34XYZ789');
    }

    public function test_export_excel_works()
    {
        Bakim::factory()->count(3)->create(['admin_id' => $this->admin->id]);
        
        $response = $this->actingAs($this->admin)->get('/bakim/export/excel');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
