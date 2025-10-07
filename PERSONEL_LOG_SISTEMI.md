# 👨‍🔧 Personel Log Sistemi

## ✅ Personel İşlemleri Tam Loglama

Tüm personel aktiviteleri detaylı şekilde kaydediliyor.

---

## 📊 Loglanan Personel İşlemleri

### 1. **Dashboard Erişimi** 🏠
```php
Tip: staff_dashboard_view
Açıklama: "Personel dashboard'a erişti: Mehmet Yılmaz"
Metadata:
- role: staff
```

**Ne zaman:** Personel panele her girdiğinde

---

### 2. **Bakım Görüntüleme** 👁️
```php
Tip: staff_bakim_view
Açıklama: "Personel bakım detayını görüntüledi: 34ABC123 - Ahmet Yılmaz"
Metadata:
- plaka: 34ABC123
- musteri_adi: Ahmet Yılmaz
- bakim_durumu: Devam Ediyor
```

**Ne zaman:** Personel bir bakım detayına baktığında

---

### 3. **Bakım Tamamlama** ✅
```php
Tip: staff_bakim_completed
Açıklama: "PERSONEL: Mehmet Yılmaz bakım tamamladı - Plaka: 34ABC123, Müşteri: Ahmet Yılmaz"
Metadata:
- action: completed
- role: staff
- staff_name: Mehmet Yılmaz
- staff_username: mehmet
- plaka: 34ABC123
- musteri_adi: Ahmet Yılmaz
- tamamlanma_notu: "Motor yağı değiştirildi"
- tamamlanma_tarihi: 2025-10-07 12:30:00
- was_assigned_to: 1 (atanan personel ID)
- completed_by: 2 (tamamlayan personel ID)
```

**Ne zaman:** Personel bir bakımı tamamladığında

---

### 4. **Middleware Logları** 🔄

Tüm POST/PUT/PATCH/DELETE istekleri otomatik loglanır:

```php
Tip: http_request
Açıklama: "Mehmet Yılmaz - POST isteği: staff.bakim.complete (created)"
Metadata:
- route_name: staff.bakim.complete
- action: created
- status_code: 302
- request_data: {...}
```

**Ne zaman:** Personel herhangi bir işlem yaptığında

---

## 🔍 Log Görüntüleme

### Admin Panel → Sistem Logları → Aktivite Logları

**Personel işlemlerini filtrelemek:**

1. **Kullanıcı Filtresinden** personeli seçin
2. **Tip Filtresinden:**
   - `staff_dashboard_view` - Dashboard erişimleri
   - `staff_bakim_view` - Bakım görüntülemeler
   - `staff_bakim_completed` - Bakım tamamlamalar
   - `http_request` - Tüm HTTP istekleri

3. **Tarih Aralığı** belirleyin
4. **Filtrele** butonuna tıklayın

---

## 📈 İstatistikler

### En Aktif Personeller

**Sistem Logları → İstatistikler**

- Son 30 günde en çok işlem yapan personeller
- Aktivite sayıları
- Performans karşılaştırması

### Personel Bazlı Analiz

```sql
-- Bir personelin tüm aktiviteleri
SELECT * FROM activity_logs 
WHERE user_id = [personel_id]
ORDER BY created_at DESC;

-- Personelin tamamladığı bakımlar
SELECT * FROM activity_logs 
WHERE type = 'staff_bakim_completed'
AND user_id = [personel_id];

-- Personelin IP geçmişi
SELECT DISTINCT ip_address, COUNT(*) as count
FROM activity_logs 
WHERE user_id = [personel_id]
GROUP BY ip_address;
```

---

## 🎯 Detaylı Metadata

Her personel işleminde kaydedilen bilgiler:

| Alan | Açıklama | Örnek |
|------|----------|-------|
| **user_id** | Personel ID | 2 |
| **ip_address** | IP adresi | 192.168.1.10 |
| **user_agent** | Tarayıcı | Chrome 120.0 |
| **request_method** | HTTP metodu | POST |
| **request_url** | İstek URL'i | /staff/bakim/5/complete |
| **session_id** | Oturum ID | abc123... |
| **metadata** | Ek bilgiler (JSON) | {...} |

---

## 🔐 Güvenlik Takibi

### Şüpheli Aktivite Tespiti:

#### 1. Aynı Personel Farklı IP'lerden
```sql
-- Aynı gün içinde farklı IP'lerden erişim
SELECT user_id, DATE(created_at) as date, 
       COUNT(DISTINCT ip_address) as ip_count
FROM activity_logs
WHERE user_id IN (SELECT id FROM users WHERE role = 'staff')
GROUP BY user_id, DATE(created_at)
HAVING ip_count > 2;
```

#### 2. Olağandışı Saatlerde İşlem
```sql
-- Gece 00:00 - 06:00 arası işlemler
SELECT * FROM activity_logs
WHERE user_id IN (SELECT id FROM users WHERE role = 'staff')
AND HOUR(created_at) BETWEEN 0 AND 6
AND type IN ('staff_bakim_completed', 'staff_bakim_view');
```

#### 3. Aşırı Hızlı İşlemler
```sql
-- 1 dakikada 10+ işlem yapan personeller
SELECT user_id, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as minute,
       COUNT(*) as action_count
FROM activity_logs
WHERE user_id IN (SELECT id FROM users WHERE role = 'staff')
GROUP BY user_id, minute
HAVING action_count > 10;
```

---

## 📊 Rapor Örnekleri

### 1. Personel Performans Raporu

```sql
SELECT 
    u.name as personel_adi,
    COUNT(CASE WHEN al.type = 'staff_bakim_completed' THEN 1 END) as tamamlanan,
    COUNT(CASE WHEN al.type = 'staff_bakim_view' THEN 1 END) as goruntulenen,
    COUNT(CASE WHEN al.type = 'staff_dashboard_view' THEN 1 END) as giris_sayisi,
    MIN(al.created_at) as ilk_islem,
    MAX(al.created_at) as son_islem
FROM users u
LEFT JOIN activity_logs al ON u.id = al.user_id
WHERE u.role = 'staff'
AND al.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY u.id, u.name
ORDER BY tamamlanan DESC;
```

### 2. Günlük Aktivite Raporu

```sql
SELECT 
    DATE(created_at) as tarih,
    type,
    COUNT(*) as islem_sayisi
FROM activity_logs
WHERE user_id IN (SELECT id FROM users WHERE role = 'staff')
AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(created_at), type
ORDER BY tarih DESC, islem_sayisi DESC;
```

### 3. IP Bazlı Güvenlik Raporu

```sql
SELECT 
    u.name,
    al.ip_address,
    COUNT(*) as islem_sayisi,
    MIN(al.created_at) as ilk_kullanim,
    MAX(al.created_at) as son_kullanim
FROM activity_logs al
JOIN users u ON al.user_id = u.id
WHERE u.role = 'staff'
AND al.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY u.name, al.ip_address
ORDER BY islem_sayisi DESC;
```

---

## 🎯 Kullanım Senaryoları

### Senaryo 1: Personel Verimlilik Analizi

**Soru:** "Mehmet bu ay kaç bakım tamamladı?"

**Çözüm:**
1. Sistem Logları → Aktivite Logları
2. Kullanıcı: Mehmet
3. Tip: `staff_bakim_completed`
4. Tarih: Bu ay
5. Sonuç: 45 bakım tamamladı

### Senaryo 2: Yetkisiz Erişim Tespiti

**Soru:** "Mehmet gece saat 02:00'de işlem yapmış mı?"

**Çözüm:**
1. Sistem Logları → Aktivite Logları
2. Kullanıcı: Mehmet
3. Tarih: Şüpheli gün
4. Logları incele
5. Saat kontrolü yap

### Senaryo 3: Ekip Performans Karşılaştırması

**Soru:** "Hangi personel en çok bakım tamamlıyor?"

**Çözüm:**
1. Sistem Logları → İstatistikler
2. "En Aktif Kullanıcılar" bölümüne bak
3. Tip: `staff_bakim_completed` filtrele
4. Sıralama: Aktivite sayısı

---

## 🔄 Otomatik İşlemler

### 1. Middleware (Otomatik)

Tüm personel işlemleri otomatik loglanır:
- ✅ POST istekleri
- ✅ PUT istekleri
- ✅ PATCH istekleri
- ✅ DELETE istekleri

**Loglanmaz:**
- ❌ GET istekleri (performans için)
- ❌ Statik dosyalar
- ❌ API çağrıları

### 2. Manuel Loglar

Özel durumlarda manuel log:
- ✅ Dashboard erişimi
- ✅ Bakım görüntüleme
- ✅ Bakım tamamlama

---

## 📱 Mobil Erişim Takibi

User Agent bilgisi ile cihaz tespiti:

```php
// Metadata'da kaydedilir
'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0...'

// Analiz:
- Mobil mi? Desktop mu?
- Hangi işletim sistemi?
- Hangi tarayıcı?
```

**Örnek:**
- iPhone Safari → Mobil personel
- Chrome Desktop → Ofis personeli

---

## ⚠️ Dikkat Edilecekler

### 1. Performans

Dashboard loglaması her erişimde çalışır:
- Çok sık giriş yapan personel = Çok log
- Veritabanı büyüyebilir
- İsteğe bağlı kapatılabilir

### 2. Gizlilik

Loglar hassas bilgi içerebilir:
- Sadece admin görebilir
- Personel kendi loglarını göremez
- KVKK uyumlu

### 3. Saklama Süresi

Loglar sonsuza kadar saklanır:
- Periyodik temizleme önerilir
- 6-12 ay tutmak yeterli
- Eski logları arşivle

---

## ✅ Özet

| İşlem | Log Tipi | Detay Seviyesi |
|-------|----------|----------------|
| Dashboard Erişimi | staff_dashboard_view | Temel |
| Bakım Görüntüleme | staff_bakim_view | Orta |
| Bakım Tamamlama | staff_bakim_completed | **Çok Detaylı** |
| Diğer İşlemler | http_request | Orta |

**Tüm personel aktiviteleri artık tam takip altında!** 🎯

---

## 🚀 Gelecek İyileştirmeler

1. **Gerçek Zamanlı Bildirimler**
   - Personel işlem yaptığında admin'e bildirim
   - Email/SMS uyarıları

2. **Grafik Raporlar**
   - Günlük aktivite grafikleri
   - Performans trendleri
   - Karşılaştırmalı analiz

3. **Otomatik Uyarılar**
   - Şüpheli aktivite tespiti
   - Anormal davranış uyarısı
   - Güvenlik ihlali bildirimi

**Sistem hazır ve çalışıyor!** 🎉

