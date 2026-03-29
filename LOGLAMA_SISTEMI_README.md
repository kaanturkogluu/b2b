# 🔍 Kapsamlı Loglama Sistemi

Sistem artık **tüm kullanıcı aktivitelerini**, **IP adreslerini**, **giriş-çıkışları** ve **işlem detaylarını** kayıt altına alıyor.

## 📋 Özellikler

### ✅ İşlevsellikler

1. **Aktivite Logları** (`activity_logs` tablosu)
   - Tüm POST, PUT, PATCH, DELETE istekleri otomatik loglanır
   - IP adresi, User Agent, HTTP Method kaydedilir
   - URL ve Session ID takibi
   - Kullanıcı bazlı filtreleme
   - Tarih aralığı filtreleme
   - Metadata (ek bilgiler) desteği

2. **Giriş/Çıkış Logları** (`login_logs` tablosu)
   - Başarılı giriş kayıtları
   - Başarısız giriş denemeleri
   - Çıkış kayıtları
   - IP adresi ve User Agent takibi
   - Hata nedenleri

3. **İstatistikler**
   - Son 7 günlük giriş istatistikleri
   - En aktif kullanıcılar (son 30 gün)
   - En çok kullanılan IP adresleri
   - Başarısız giriş denemeleri (güvenlik)

## 🗂️ Veritabanı Yapısı

### Activity Logs Tablosu
```sql
- id
- type (log tipi)
- description (açıklama)
- user_id (kullanıcı)
- ip_address (IP adresi)
- user_agent (tarayıcı bilgisi)
- request_method (GET, POST, PUT, DELETE)
- request_url (istek URL'i)
- session_id (oturum ID)
- related_id (ilişkili kayıt ID)
- related_type (ilişkili model)
- metadata (JSON - ek bilgiler)
- created_at, updated_at
```

### Login Logs Tablosu
```sql
- id
- user_id (kullanıcı)
- username (kullanıcı adı)
- event_type (login, logout, failed_login)
- ip_address (IP adresi)
- user_agent (tarayıcı bilgisi)
- session_id (oturum ID)
- success (başarılı/başarısız)
- failure_reason (hata nedeni)
- additional_data (ek bilgiler)
- created_at, updated_at
```

## 📍 Yeni Sayfalar

### Admin Paneli Menüsünde "Sistem Logları"

1. **Aktivite Logları** (`/logs/activity`)
   - Tüm sistem aktivitelerini listeler
   - Kullanıcı, tip, IP, tarih filtreleri
   - Detaylı arama özelligi

2. **Giriş Logları** (`/logs/login`)
   - Giriş/çıkış kayıtlarını listeler
   - Başarılı/başarısız filtreleme
   - Güvenlik takibi

3. **İstatistikler** (`/logs/statistics`)
   - Grafik ve tablolarla görselleştirme
   - En aktif kullanıcılar
   - En çok kullanılan IP'ler
   - Güvenlik uyarıları

4. **Log Detay Sayfaları**
   - Her log kaydının detaylı görüntülenmesi
   - Metadata bilgileri
   - İstek detayları

## 🛡️ Güvenlik Özellikleri

1. **Başarısız Giriş Takibi**
   - Tüm başarısız giriş denemeleri kaydedilir
   - IP bazlı takip
   - Güvenlik istatistikleri

2. **Otomatik Loglama**
   - `LogActivity` middleware tüm istekleri yakalar
   - POST, PUT, PATCH, DELETE otomatik loglanır
   - GET istekleri performans için loglanmaz

3. **Şifre Koruması**
   - Şifreler ve hassas bilgiler loglara eklenmez
   - Otomatik sanitizasyon

## 🔧 Kurulum

### 1. Migration'ı Çalıştırın

```bash
php artisan migrate
```

Bu komut 3 yeni migration dosyasını çalıştırır:
- `fix_bakim_foreign_key_constraints` - Foreign key güvenlik yamasi
- `add_tracking_fields_to_activity_logs_table` - Aktivite loglarına IP, User Agent vb. ekler
- `create_login_logs_table` - Giriş/çıkış logları tablosu

### 2. Middleware Aktif

`LogActivity` middleware otomatik olarak aktiftir (`bootstrap/app.php`).

## 📊 Kullanım

### Manuel Log Ekleme

#### Aktivite Logu Eklemek
```php
use App\Models\ActivityLog;

ActivityLog::log(
    'custom_action',  // Tip
    'Kullanıcı özel bir işlem yaptı',  // Açıklama
    auth()->id(),  // Kullanıcı ID (opsiyonel)
    $recordId,  // İlişkili kayıt ID (opsiyonel)
    'App\Models\YourModel',  // İlişkili model (opsiyonel)
    ['key' => 'value']  // Metadata (opsiyonel)
);
```

#### Giriş Logu Eklemek
```php
use App\Models\LoginLog;

// Başarılı giriş
LoginLog::logLogin(
    $userId,
    $username,
    request()->ip(),
    request()->userAgent(),
    true  // başarılı
);

// Başarısız giriş
LoginLog::logLogin(
    null,
    $username,
    request()->ip(),
    request()->userAgent(),
    false,  // başarısız
    'Kullanıcı adı veya şifre hatalı'
);

// Çıkış
LoginLog::logLogout(
    $userId,
    $username,
    request()->ip(),
    request()->userAgent()
);
```

## 🎯 Otomatik Loglanan İşlemler

### AuthController
- ✅ Kullanıcı girişi (başarılı/başarısız)
- ✅ Kullanıcı çıkışı

### BakimController
- ✅ Bakım kaydı oluşturma
- ✅ Bakım kaydı güncelleme
- ✅ Bakım kaydı silme
- ✅ Ödeme onayı

### UserController
- ✅ Kullanıcı oluşturma
- ✅ Kullanıcı güncelleme
- ✅ Kullanıcı silme
- ✅ Kullanıcı durumu değiştirme

### Tüm Diğer İşlemler
- ✅ POST, PUT, PATCH, DELETE istekleri otomatik loglanır

## 📈 Performans

- **İndeksler**: IP, session_id, created_at alanları indekslenmiştir
- **Sayfalama**: Tüm log listeleri sayfalandırılmıştır (20 kayıt/sayfa)
- **GET İstekleri**: Performans için GET istekleri loglanmaz
- **Veri Boyutu**: Çok büyük request dataları sınırlandırılır

## 🔍 Filtreleme Seçenekleri

### Aktivite Logları
- Kullanıcı
- Log tipi
- IP adresi
- Tarih aralığı
- Anahtar kelime arama (açıklama, URL, IP)

### Giriş Logları
- Kullanıcı
- Olay tipi (giriş/çıkış/başarısız)
- Başarı durumu
- IP adresi
- Tarih aralığı
- Anahtar kelime arama

## 📂 Dosya Yapısı

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php (güncellendi)
│   │   ├── BakimController.php (güvenlik yaması)
│   │   └── LogController.php (YENİ)
│   └── Middleware/
│       └── LogActivity.php (YENİ)
├── Models/
│   ├── ActivityLog.php (güncellendi)
│   └── LoginLog.php (YENİ)
database/
├── migrations/
│   ├── 2025_10_07_093947_fix_bakim_foreign_key_constraints.php (YENİ)
│   ├── 2025_10_07_094222_add_tracking_fields_to_activity_logs_table.php (YENİ)
│   └── 2025_10_07_094225_create_login_logs_table.php (YENİ)
resources/
└── views/
    └── admin/
        └── logs/
            ├── activity.blade.php (YENİ)
            ├── login.blade.php (YENİ)
            ├── statistics.blade.php (YENİ)
            ├── show.blade.php (YENİ)
            └── show-login.blade.php (YENİ)
routes/
└── web.php (güncellendi - log route'ları eklendi)
bootstrap/
└── app.php (güncellendi - LogActivity middleware eklendi)
```

## 🔐 Güvenlik Yamasi

Bu güncellemede ayrıca **kritik güvenlik açıkları** da düzeltildi:

1. **Foreign Key Constraints**
   - `admin_id` ve `personel_id` için `onDelete('set null')` eklendi
   - Kullanıcı silindiğinde bakım kayıtları korunur

2. **Parça ID Güvenlik Kontrolü**
   - Parça güncellerken, parçanın gerçekten o bakıma ait olduğu doğrulanır
   - Yanlış parça ID manipülasyonu engellenir
   - Güvenlik ihlali denemeleri loglanır

## 🎨 Görsel Özellikler

- Modern ve responsive tasarım
- Renkli badge'ler (HTTP method'lara göre)
- İstatistik grafikleri
- Filtreleme ve arama
- Detaylı görüntüleme modal/sayfaları
- Mobil uyumlu

## 📝 Notlar

- Loglar sonsuz birikebilir, periyodik temizleme yapılması önerilir
- Yüksek trafikli sistemlerde log tabloları hızla büyüyebilir
- Production ortamında log retention policy belirlenmeli
- Hassas bilgiler otomatik olarak loglardan çıkarılır

## 🚀 Gelecek İyileştirmeler (Opsiyonel)

- [ ] Log temizleme cron job'ı
- [ ] Export to CSV/Excel
- [ ] Real-time log monitoring
- [ ] Email alerts for security events
- [ ] IP blacklisting
- [ ] Geographic IP tracking

---

**Sistem Hazır!** Artık tüm kullanıcı aktiviteleri, giriş-çıkışlar ve işlemler detaylı şekilde loglanıyor. 🎉

