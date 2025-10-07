# 📊 Log Sayfası Kullanım Rehberi

## ✅ Kurulum Tamamlandı!

Migration başarıyla çalıştırıldı. Sistem logları artık kullanıma hazır!

---

## 🔍 Log Sayfalarına Erişim

### Admin Paneli Menüsünde:

Sol menüde **"Sistem Logları"** butonuna tıklayın:

```
📍 Menü Konumu:
├── Dashboard
├── Servis Yönetimi
├── Kullanıcı Yönetimi
├── Raporlar
├── 🔍 Sistem Logları ← BURASI
└── Fatura Ayarları
```

---

## 📄 Mevcut Log Sayfaları

### 1. **Aktivite Logları** (`/logs/activity`)

**Ne gösterir:**
- Tüm sistem aktiviteleri
- POST, PUT, PATCH, DELETE istekleri
- Kullanıcı işlemleri

**Filtreleme Seçenekleri:**
- ✅ Kullanıcı bazlı
- ✅ İşlem tipi bazlı
- ✅ IP adresi
- ✅ Tarih aralığı
- ✅ Anahtar kelime arama

**Gösterilen Bilgiler:**
```
┌─────────────────────────────────────────────────────┐
│ Tarih/Saat | Kullanıcı | Tip | Açıklama | IP | Method │
├─────────────────────────────────────────────────────┤
│ 07.10.2025 │ Admin     │ ... │ ...      │ .. │ POST   │
└─────────────────────────────────────────────────────┘
```

---

### 2. **Giriş Logları** (`/logs/login`)

**Ne gösterir:**
- Başarılı girişler
- Başarısız giriş denemeleri
- Çıkış kayıtları

**Filtreleme Seçenekleri:**
- ✅ Kullanıcı bazlı
- ✅ Olay tipi (giriş/çıkış/başarısız)
- ✅ Başarı durumu
- ✅ IP adresi
- ✅ Tarih aralığı

**Gösterilen Bilgiler:**
```
┌────────────────────────────────────────────────────────────┐
│ Tarih/Saat | Kullanıcı | Olay | IP | Durum | Hata Nedeni │
├────────────────────────────────────────────────────────────┤
│ 07.10.2025 │ admin     │ Giriş│... │ ✓     │ -           │
│ 07.10.2025 │ hacker    │ Hata │... │ ✗     │ Şifre hatalı│
└────────────────────────────────────────────────────────────┘
```

---

### 3. **İstatistikler** (`/logs/statistics`)

**Ne gösterir:**
- Son 7 günlük giriş istatistikleri
- En aktif kullanıcılar (son 30 gün)
- En çok kullanılan IP adresleri
- ⚠️ Başarısız giriş denemeleri (güvenlik)

**İstatistik Grafikleri:**
```
📊 Giriş İstatistikleri
├── Toplam giriş
├── Başarılı
├── Başarısız
└── Başarı oranı (%)

👥 En Aktif Kullanıcılar
├── Kullanıcı adı
└── Aktivite sayısı

🌐 En Çok Kullanılan IP'ler
├── IP adresi
└── İstek sayısı

🚨 Başarısız Giriş Denemeleri (Son 24 Saat)
├── Tarih/Saat
├── Kullanıcı adı
├── IP adresi
└── Hata nedeni
```

---

### 4. **Detay Sayfaları**

Her log kaydına tıklayarak **detaylı bilgi** görüntüleyebilirsiniz:

**Aktivite Log Detayı:**
- Tarih/Saat
- Kullanıcı bilgisi
- İşlem tipi
- IP adresi
- Session ID
- HTTP Method & URL
- User Agent (tarayıcı bilgisi)
- Metadata (ek bilgiler - JSON formatında)

**Giriş Log Detayı:**
- Tarih/Saat
- Kullanıcı bilgisi
- Olay tipi (giriş/çıkış/hata)
- Başarı durumu
- Hata nedeni (varsa)
- IP adresi
- Session ID
- User Agent

---

## 🎯 Kullanım Örnekleri

### Örnek 1: Kayıt Silen Kişiyi Bulma

1. **Sistem Logları** > **Aktivite Logları**
2. **Tip**: `bakim_deleted` seçin
3. **Tarih Aralığı**: Sorunlu tarihi girin
4. **Filtrele** butonuna tıklayın
5. Listede **kim hangi kaydı sildi** görünür

### Örnek 2: Başarısız Giriş Denemelerini Takip

1. **Sistem Logları** > **Giriş Logları**
2. **Durum**: `Başarısız` seçin
3. Hangi IP'den kaç deneme yapıldığını görün
4. Şüpheli aktivite varsa IP'yi bloklayın

### Örnek 3: Belirli Kullanıcının İşlemlerini İzleme

1. **Sistem Logları** > **Aktivite Logları**
2. **Kullanıcı**: İzlemek istediğiniz kullanıcıyı seçin
3. **Tarih Aralığı**: İstediğiniz dönemi girin
4. Kullanıcının tüm işlemleri görünür

### Örnek 4: IP Bazlı Takip

1. **Sistem Logları** > **İstatistikler**
2. **En Çok Kullanılan IP Adresleri** bölümünü inceleyin
3. Şüpheli IP varsa, aktivite loglarında IP'yi aratın
4. O IP'den yapılan tüm işlemleri görün

---

## 📊 Kaydedilen Bilgiler

### Her İşlemde Kaydedilir:

| Bilgi | Açıklama |
|-------|----------|
| **Tarih/Saat** | İşlemin yapıldığı an |
| **Kullanıcı** | İşlemi yapan kullanıcı |
| **IP Adresi** | İsteğin geldiği IP |
| **User Agent** | Tarayıcı ve işletim sistemi bilgisi |
| **Session ID** | Oturum kimliği |
| **İşlem Tipi** | Ne yapıldı (created, updated, deleted, vb) |
| **HTTP Method** | GET, POST, PUT, DELETE |
| **URL** | Hangi sayfadan işlem yapıldı |
| **Metadata** | İşleme özel ek bilgiler (JSON) |

---

## 🔍 Arama ve Filtreleme

### Gelişmiş Arama:

**Aktivite Logları:**
```
🔍 Arama yapabileceğiniz alanlar:
├── Açıklama
├── URL
└── IP adresi
```

**Giriş Logları:**
```
🔍 Arama yapabileceğiniz alanlar:
├── Kullanıcı adı
├── IP adresi
└── Hata mesajı
```

### Hızlı Filtreler:

**Aktivite Logları:**
- Son 24 saat
- Son 7 gün
- Bu ay
- Özel tarih aralığı

**Giriş Logları:**
- Sadece başarılı
- Sadece başarısız
- Sadece giriş
- Sadece çıkış

---

## 🚨 Güvenlik Uyarıları

### Dikkat Edilecek Durumlar:

1. **Çok Sayıda Başarısız Giriş**
   - 📍 İstatistikler sayfasında görünür
   - ⚠️ Brute force saldırısı olabilir
   - 🛡️ IP'yi bloklayın

2. **Bilinmeyen IP'den İşlem**
   - 📍 Aktivite loglarında görünür
   - ⚠️ Hesap ele geçirilmiş olabilir
   - 🛡️ Kullanıcıyı uyarın, şifre değiştirin

3. **Gece Saatlerinde İşlem**
   - 📍 Aktivite loglarında tarih/saat kontrolü
   - ⚠️ Yetkisiz erişim olabilir
   - 🛡️ Kullanıcıyla teyit edin

4. **Toplu Silme İşlemleri**
   - 📍 Aktivite loglarında `deleted` tip filtresi
   - ⚠️ Kötü niyetli olabilir
   - 🛡️ İşlemi yapan kişiyi tespit edin

---

## 📱 Sayfa Özellikleri

### ✅ Responsive Tasarım
- Mobil cihazlarda da kullanılabilir
- Tablet uyumlu
- Modern arayüz

### ✅ Sayfalama
- Her sayfada 20 kayıt
- Hızlı sayfa geçişi
- Toplam kayıt sayısı gösterimi

### ✅ Renk Kodları
- 🟢 Başarılı işlemler: Yeşil
- 🔴 Başarısız işlemler: Kırmızı
- 🔵 Bilgi: Mavi
- 🟡 Uyarı: Sarı

### ✅ İkonlar
- 📊 HTTP Method badge'leri
- ✓/✗ Durum ikonları
- 🔍 Detay görüntüleme

---

## 🎨 Sayfa Görünümü

### Menü Butonu:
```
┌────────────────────────┐
│ 🔍 Sistem Logları      │ ← Tıkla
└────────────────────────┘
```

### Alt Sayfalar:
```
┌─────────────────────────────────────────┐
│ 📊 Aktivite Logları                     │
│ 🔐 Giriş Logları                        │
│ 📈 İstatistikler                        │
└─────────────────────────────────────────┘
```

Her sayfanın üst kısmında diğer sayfalara hızlı geçiş butonları var!

---

## 🔧 Teknik Bilgiler

### Veritabanı Tabloları:
- `activity_logs` - Tüm aktiviteler
- `login_logs` - Giriş/çıkış kayıtları

### İndeksler (Performans için):
- `ip_address`
- `user_id`
- `created_at`
- `type`
- `session_id`

### Otomatik Loglama:
- ✅ Middleware aktif (`LogActivity`)
- ✅ AuthController'da giriş/çıkış
- ✅ BakimController'da CRUD işlemleri
- ✅ UserController'da kullanıcı işlemleri

---

## 📞 Destek

Herhangi bir sorun olursa:
1. `storage/logs/laravel.log` dosyasını kontrol edin
2. Log middleware'inin aktif olduğunu doğrulayın
3. Veritabanı bağlantısını kontrol edin

---

**Sistem Hazır!** Artık tüm işlemleri detaylı şekilde takip edebilirsiniz! 🎉

