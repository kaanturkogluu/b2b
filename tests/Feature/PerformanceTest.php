<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bakim;
use Illuminate\Support\Facades\Cache;

class PerformanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_bakim_index_performance_with_large_dataset()
    {
        // 100 bakım kaydı oluştur
        Bakim::factory()->count(100)->create(['admin_id' => $this->admin->id]);
        
        $startTime = microtime(true);
        
        $response = $this->actingAs($this->admin)->get('/bakim');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // ms
        
        $response->assertStatus(200);
        
        // Performans testi - 500ms'den az olmalı
        $this->assertLessThan(500, $executionTime, "Bakım listesi çok yavaş: {$executionTime}ms");
    }

    public function test_bakim_search_performance()
    {
        // 50 bakım kaydı oluştur
        Bakim::factory()->count(50)->create(['admin_id' => $this->admin->id]);
        
        $startTime = microtime(true);
        
        $response = $this->actingAs($this->admin)->get('/bakim?search=test');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // ms
        
        $response->assertStatus(200);
        
        // Arama performansı - 300ms'den az olmalı
        $this->assertLessThan(300, $executionTime, "Arama çok yavaş: {$executionTime}ms");
    }

    public function test_bakim_filtering_performance()
    {
        // 50 bakım kaydı oluştur
        Bakim::factory()->count(50)->create([
            'admin_id' => $this->admin->id,
            'bakim_durumu' => 'Devam Ediyor'
        ]);
        
        $startTime = microtime(true);
        
        $response = $this->actingAs($this->admin)->get('/bakim?bakim_durumu=Devam Ediyor');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // ms
        
        $response->assertStatus(200);
        
        // Filtreleme performansı - 200ms'den az olmalı
        $this->assertLessThan(200, $executionTime, "Filtreleme çok yavaş: {$executionTime}ms");
    }

    public function test_cache_effectiveness()
    {
        // İlk istek - cache yok
        $startTime = microtime(true);
        $response1 = $this->actingAs($this->admin)->get('/bakim');
        $firstRequestTime = (microtime(true) - $startTime) * 1000;
        
        // İkinci istek - cache'den
        $startTime = microtime(true);
        $response2 = $this->actingAs($this->admin)->get('/bakim');
        $secondRequestTime = (microtime(true) - $startTime) * 1000;
        
        $response1->assertStatus(200);
        $response2->assertStatus(200);
        
        // Cache'den gelen istek daha hızlı olmalı
        $this->assertLessThan($firstRequestTime, $secondRequestTime, 
            "Cache etkili değil. İlk: {$firstRequestTime}ms, İkinci: {$secondRequestTime}ms");
    }

    public function test_memory_usage_under_control()
    {
        $initialMemory = memory_get_usage();
        
        // 100 bakım kaydı oluştur ve listele
        Bakim::factory()->count(100)->create(['admin_id' => $this->admin->id]);
        
        $response = $this->actingAs($this->admin)->get('/bakim');
        
        $finalMemory = memory_get_usage();
        $memoryUsed = ($finalMemory - $initialMemory) / 1024 / 1024; // MB
        
        $response->assertStatus(200);
        
        // Bellek kullanımı 50MB'den az olmalı
        $this->assertLessThan(50, $memoryUsed, "Çok fazla bellek kullanılıyor: {$memoryUsed}MB");
    }

    public function test_database_query_count()
    {
        // Query sayısını izle
        \DB::enableQueryLog();
        
        Bakim::factory()->count(10)->create(['admin_id' => $this->admin->id]);
        
        $this->actingAs($this->admin)->get('/bakim');
        
        $queries = \DB::getQueryLog();
        $queryCount = count($queries);
        
        // N+1 problemi olmamalı - 10'dan az sorgu olmalı
        $this->assertLessThan(10, $queryCount, "Çok fazla sorgu: {$queryCount} adet");
    }

    public function test_export_performance()
    {
        // 100 bakım kaydı oluştur
        Bakim::factory()->count(100)->create(['admin_id' => $this->admin->id]);
        
        $startTime = microtime(true);
        
        $response = $this->actingAs($this->admin)->get('/bakim/export/excel');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // ms
        
        $response->assertStatus(200);
        
        // Export performansı - 1000ms'den az olmalı
        $this->assertLessThan(1000, $executionTime, "Export çok yavaş: {$executionTime}ms");
    }

    public function test_pagination_performance()
    {
        // 200 bakım kaydı oluştur
        Bakim::factory()->count(200)->create(['admin_id' => $this->admin->id]);
        
        $startTime = microtime(true);
        
        // Son sayfaya git
        $response = $this->actingAs($this->admin)->get('/bakim?page=20');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // ms
        
        $response->assertStatus(200);
        
        // Sayfalama performansı - 400ms'den az olmalı
        $this->assertLessThan(400, $executionTime, "Sayfalama çok yavaş: {$executionTime}ms");
    }
}
