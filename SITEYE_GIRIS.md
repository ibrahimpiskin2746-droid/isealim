# 🚀 Siteye Giriş Rehberi

## Hızlı Başlangıç

### 1️⃣ AMPPS Servislerini Başlatın

1. **AMPPS Control Panel**'i açın
2. **Apache** ve **MySQL** servislerinin çalıştığından emin olun (yeşil ışık)
3. Eğer çalışmıyorsa, **Start** butonlarına tıklayın

### 2️⃣ Veritabanını Kontrol Edin

**phpMyAdmin üzerinden:**
1. Tarayıcıda şu adrese gidin: `http://localhost/phpmyadmin`
2. Sol menüden `job_platform` veritabanının var olduğunu kontrol edin
3. **Eğer yoksa:**
   - Yeni veritabanı oluşturun: `job_platform`
   - `database.sql` dosyasını import edin

**Veya terminal üzerinden:**
```bash
mysql -u root -p
CREATE DATABASE IF NOT EXISTS job_platform;
USE job_platform;
SOURCE "C:/Program Files/Ampps/www/isealim/database.sql";
```

### 3️⃣ Siteye Giriş

**Ana Sayfa:**
```
http://localhost/isealim
```

**Veya direkt public klasörü:**
```
http://localhost/isealim/public
```

### 4️⃣ Test Kullanıcıları ile Giriş

#### 🔐 Başvuran (Applicant) Hesabı
- **E-posta:** `basvuran@example.com`
- **Şifre:** `applicant123`
- **Giriş URL:** `http://localhost/isealim/auth/login`

#### 🏢 İşveren (Employer) Hesabı
- **E-posta:** `isveren@example.com`
- **Şifre:** `employer123`
- **Giriş URL:** `http://localhost/isealim/auth/login`

#### 👨‍💼 Admin Hesabı
- **E-posta:** `admin@jobplatform.com`
- **Şifre:** `admin123`
- **Giriş URL:** `http://localhost/isealim/auth/login`

### 5️⃣ İlk Kullanım Adımları

#### Başvuran Olarak:
1. Giriş yapın
2. Dashboard'a gidin: `http://localhost/isealim/applicant/dashboard`
3. İş ilanlarına göz atın: `http://localhost/isealim/applicant/browse-jobs`
4. Bir ilana başvurun

#### İşveren Olarak:
1. Giriş yapın
2. Dashboard'a gidin: `http://localhost/isealim/employer/dashboard`
3. Yeni iş ilanı oluşturun
4. AI ile form oluşturun

### 6️⃣ Sorun Giderme

#### ❌ "Page Not Found" Hatası
- `.htaccess` dosyasının root dizinde olduğundan emin olun
- Apache `mod_rewrite` modülünün aktif olduğunu kontrol edin
- AMPPS Control Panel → Apache → Configuration → `mod_rewrite` aktif olmalı

#### ❌ "Database Connection Error"
- MySQL servisinin çalıştığından emin olun
- `config/config.php` dosyasındaki veritabanı bilgilerini kontrol edin:
  ```php
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'job_platform');
  define('DB_USER', 'root');
  define('DB_PASS', ''); // AMPPS varsayılan şifresi boş olabilir
  ```
- Veritabanının oluşturulduğundan emin olun

#### ❌ "500 Internal Server Error"
- `storage/logs/` klasöründeki log dosyalarını kontrol edin
- PHP hata loglarını kontrol edin
- Klasör izinlerini kontrol edin (Windows'ta genellikle sorun olmaz)

#### ❌ "Session Error"
- `storage/` klasörünün yazılabilir olduğundan emin olun
- Tarayıcı çerezlerini temizleyin

### 7️⃣ Önemli URL'ler

| Sayfa | URL |
|-------|-----|
| Ana Sayfa | `http://localhost/isealim` |
| Giriş | `http://localhost/isealim/auth/login` |
| Kayıt | `http://localhost/isealim/auth/register` |
| Başvuran Dashboard | `http://localhost/isealim/applicant/dashboard` |
| İşveren Dashboard | `http://localhost/isealim/employer/dashboard` |
| İş İlanları | `http://localhost/isealim/applicant/browse-jobs` |

### 8️⃣ İlk Kurulum Kontrol Listesi

- [ ] AMPPS Apache çalışıyor
- [ ] AMPPS MySQL çalışıyor
- [ ] `job_platform` veritabanı oluşturuldu
- [ ] `database.sql` import edildi
- [ ] `config/config.php` ayarları doğru
- [ ] `storage/` klasörü yazılabilir
- [ ] Site açılıyor: `http://localhost/isealim`
- [ ] Giriş sayfası çalışıyor
- [ ] Test kullanıcıları ile giriş yapılabiliyor

### 9️⃣ OpenAI API Key (Opsiyonel)

AI özelliklerini kullanmak için:
1. https://platform.openai.com adresine gidin
2. API Key oluşturun
3. `config/config.php` dosyasına ekleyin:
   ```php
   define('OPENAI_API_KEY', 'sk-your-key-here');
   ```

**Not:** API key olmadan da site çalışır, ancak AI özellikleri (otomatik form oluşturma, CV değerlendirme) çalışmaz.

---

## ✅ Başarılı Kurulum Sonrası

Siteye başarıyla giriş yaptıysanız:
1. ✅ Dashboard'u görüyorsunuz
2. ✅ Menüler çalışıyor
3. ✅ Sayfalar yükleniyor
4. ✅ Veritabanı bağlantısı çalışıyor

**Artık platformu kullanmaya başlayabilirsiniz!** 🎉


