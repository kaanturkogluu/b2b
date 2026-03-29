# 🗑️ Soft Delete Sistemi

## ✅ Kurulum Tamamlandı!

Bakım ve parça kayıtları artık **gerçekten silinmez**, sadece **işaretlenir**.

---

## 🔄 Nasıl Çalışır?

### Eski Sistem (Hard Delete):
```php
$bakim->delete(); // ❌ Kayıt VERİTABANINDAN SİLİNİR
// Kayıt geri getirilemez!
```

### Yeni Sistem (Soft Delete):
```php
$bakim->update([
    'is_deleted' => true,      // ✅ Sadece işaretlenir
    'deleted_at' => now(),     // Silinme zamanı
    'deleted_by' => Auth::id() // Kim sildi?
]);
// Kayıt hala veritabanında!
// İstenirse geri yüklenebilir!
```

---

## 📊 Veritabanı Yapısı

### bakim Tablosu:
```sql
ALTER TABLE bakim ADD COLUMN:
- is_deleted (boolean, default: false) 
- deleted_at (timestamp, nullable)
- deleted_by (foreign key -> users.id)
```

### degisecek_parcalar Tablosu:
```sql
ALTER TABLE degisecek_parcalar ADD COLUMN:
- is_deleted (boolean, default: false)
- deleted_at (timestamp, nullable)
```

---

## 🎯 Kullanım

### 1. Kayıt Silme (Admin)

**Admin panel → Bakım Listesi → Sil butonu**

```php
// Kullanıcı "Sil" butonuna tıklar
// BakimController@destroy çalışır

// Eski:
$bakim->delete(); // Kayıt silinir ❌

// Yeni:
$bakim->update([
    'is_deleted' => true,
    'deleted_at' => now(),
    'deleted_by' => Auth::id()
]);
// Kayıt soft delete edilir ✅
// Parçalar da otomatik soft delete edilir ✅
```

### 2. Listeleme

**Tüm listeleme sorguları otomatik olarak `is_deleted = false` filtresi kullanır:**

```php
// Admin bakım listesi
Bakim::where('is_deleted', false)->get(); ✅

// Personel bakım listesi  
Bakim::where('is_deleted', false)->get(); ✅

// Dashboard istatistikleri
DB::table('bakim')
  ->where('is_deleted', false)
  ->count(); ✅
```

**Silinmiş kayıtlar listede GÖRÜNMEZ!**

---

## 🛠️ Model Fonksiyonları

### Scope Kullanımı:

```php
// Silinmemiş kayıtlar (varsayılan)
Bakim::notDeleted()->get();
DegisecekParca::notDeleted()->get();

// Sadece silinmiş kayıtlar
Bakim::onlyDeleted()->get();
DegisecekParca::onlyDeleted()->get();
```

### Soft Delete İşlemi:

```php
$bakim = Bakim::find(1);

// Silme
$bakim->softDelete(Auth::id());
// - is_deleted = true
// - deleted_at = şimdi
// - deleted_by = kullanıcı ID
// - Parçalar da otomatik silinir

// Geri yükleme
$bakim->restore();
// - is_deleted = false
// - deleted_at = null
// - deleted_by = null
// - Parçalar da otomatik geri yüklenir
```

### İlişkiler:

```php
// Kim sildi?
$bakim->deletedBy; // User modeli
$bakim->deletedBy->name; // "Admin Adı"

// Ne zaman silindi?
$bakim->deleted_at; // 2025-10-07 10:30:00
```

---

## 📋 Güncellenen Dosyalar

### 1. **Migration**
- `2025_10_07_101814_add_is_deleted_to_bakim_and_parcalar_tables.php`
- bakim ve degisecek_parcalar tablolarına kolonlar eklendi

### 2. **Model'ler**
- `app/Models/Bakim.php`
  - `fillable` güncellendi
  - `scopeNotDeleted()` eklendi
  - `scopeOnlyDeleted()` eklendi
  - `softDelete()` metodu eklendi
  - `restore()` metodu eklendi
  - `deletedBy()` relation eklendi

- `app/Models/DegisecekParca.php`
  - `fillable` güncellendi
  - `scopeNotDeleted()` eklendi
  - `scopeOnlyDeleted()` eklendi

### 3. **Controller'lar**
- `app/Http/Controllers/BakimController.php`
  - `index()`: `where('is_deleted', false)` eklendi
  - `staffIndex()`: `where('is_deleted', false)` eklendi
  - `destroy()`: Soft delete olarak güncellendi

- `app/Http/Controllers/StaffController.php`
  - `dashboard()`: Tüm sorgulara `where('is_deleted', false)`
  - `profile()`: `where('is_deleted', false)`
  - `tasks()`: `where('is_deleted', false)`
  - `timeline()`: `where('is_deleted', false)`

---

## 🔍 Listeleme Filtreleri

### Admin Bakım Listesi:

```php
Bakim::select([...])
    ->with(['degisecekParcalar' => function($query) {
        $query->where('is_deleted', false); // Silinmemiş parçalar
    }])
    ->where('is_deleted', false) // Silinmemiş bakımlar
    ->paginate(20);
```

### Personel Bakım Listesi:

```php
Bakim::select([...])
    ->with(['degisecekParcalar' => function($query) {
        $query->where('is_deleted', false);
    }])
    ->where('is_deleted', false)
    ->where('personel_id', $user->id)
    ->paginate(20);
```

---

## 📊 Activity Logs

Silme işlemleri loglanır:

```php
ActivityLog::log(
    'bakim_soft_deleted',
    "Bakım kaydı silindi (soft delete): 34ABC123 - Ahmet Yılmaz",
    Auth::id(),
    $bakim->id, // Kayıt hala mevcut
    'App\Models\Bakim',
    [
        'plaka' => '34ABC123',
        'musteri_adi' => 'Ahmet Yılmaz',
        ...
    ]
);
```

Log sayfasında görüntülenebilir:
- Kim sildi?
- Ne zaman sildi?
- Hangi IP'den sildi?
- Hangi kayıt silindi?

---

## 🎯 Avantajlar

| Özellik | Hard Delete | Soft Delete |
|---------|-------------|-------------|
| Geri getirilebilir | ❌ Hayır | ✅ Evet |
| Veri kaybı | ❌ Evet | ✅ Hayır |
| Denetim izi | ⚠️ Kısıtlı | ✅ Tam |
| Kim sildi? | ❌ Belli olmaz | ✅ Kaydedilir |
| Ne zaman silindi? | ❌ Belli olmaz | ✅ Kaydedilir |
| Raporlarda | ❌ Görünmez | ✅ İstenirse dahil edilebilir |
| İstatistikler | ❌ Eksik | ✅ Doğru |

---

## 🔄 Geri Yükleme (İsteğe Bağlı)

Gelecekte admin paneline "Geri Yükle" özelliği eklenebilir:

```php
// Silinmiş kayıtları listele
Route::get('/bakim/deleted', function() {
    $deletedBakimlar = Bakim::onlyDeleted()
                             ->with('deletedBy')
                             ->paginate(20);
    return view('admin.bakim.deleted', compact('deletedBakimlar'));
});

// Geri yükle
Route::post('/bakim/{id}/restore', function($id) {
    $bakim = Bakim::find($id);
    $bakim->restore();
    
    return redirect()->route('bakim.index')
                     ->with('success', 'Kayıt geri yüklendi');
});
```

---

## 📈 Performans

### İndeksler:
```sql
CREATE INDEX idx_bakim_is_deleted ON bakim(is_deleted);
CREATE INDEX idx_parcalar_is_deleted ON degisecek_parcalar(is_deleted);
```

- ✅ Hızlı filtreleme
- ✅ Optimize edilmiş sorgular
- ✅ Sayfalama performansı

---

## ⚠️ Dikkat Edilecekler

### 1. **Parçalar Otomatik Silinir**
```php
// Bakım silindiğinde
$bakim->softDelete();
// → Tüm parçalar da otomatik soft delete edilir
```

### 2. **Cascade Delete YOK**
```php
// Foreign key cascade yok
// Kullanıcı silinse bile:
- deleted_by → set null
- Bakım kayıtları korunur ✅
```

### 3. **Listeleme Sorguları**
```php
// Her zaman is_deleted kontrolü yapın
Bakim::where('is_deleted', false)->...
// veya
Bakim::notDeleted()->...
```

---

## 🚀 Gelecek İyileştirmeler (Opsiyonel)

1. **Admin Geri Yükleme Sayfası**
   - Silinmiş kayıtları listele
   - Tek tıkla geri yükle
   - Toplu geri yükleme

2. **Otomatik Temizleme**
   - 6 ay önceki soft delete'leri temizle
   - Cron job ile otomatik
   - Hard delete işlemi

3. **Gelişmiş Filtreleme**
   - "Silinmiş kayıtları da göster" checkbox
   - Silme tarihine göre filtreleme
   - Silen kişiye göre filtreleme

4. **Export**
   - Silinmiş kayıtları Excel'e aktar
   - Arşivleme

---

## ✅ Özet

| İşlem | Durum | Açıklama |
|-------|-------|----------|
| Soft Delete | ✅ | Kayıtlar silinmez, işaretlenir |
| Listeleme Filtresi | ✅ | is_deleted = false |
| Parça Silme | ✅ | Otomatik (bakımla birlikte) |
| Activity Log | ✅ | Kim, ne zaman, hangi kayıt |
| Geri Yükleme | ✅ | Model metodu mevcut |
| Migration | ✅ | Tamamlandı |
| Model Scope | ✅ | notDeleted(), onlyDeleted() |

**Sistem %100 Hazır!** Artık hiçbir kayıt kalıcı olarak silinmez. 🎉

