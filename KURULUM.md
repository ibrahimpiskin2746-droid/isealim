# AI Destekli İş Başvuru ve Değerlendirme Platformu
## Kurulum Kılavuzu

### 📋 Sistem Gereksinimleri

- **PHP:** 7.4 veya üzeri
- **MySQL:** 5.7 veya üzeri
- **Web Server:** Apache (mod_rewrite aktif)
- **PHP Eklentileri:**
  - PDO
  - PDO MySQL
  - cURL
  - JSON
  - mbstring
  - fileinfo

### 🚀 Kurulum Adımları

#### 1. Dosyaları Sunucuya Yükleyin
```bash
# Projeyi kopyalayın
cd C:\Program Files\Ampps\www
# isealım klasörü zaten mevcut
```

#### 2. Veritabanını Oluşturun
```sql
# MySQL'e bağlanın
mysql -u root -p

# database.sql dosyasını import edin
source C:\Program Files\Ampps\www\isealım\database.sql

# Veya phpMyAdmin üzerinden:
# - Yeni veritabanı oluşturun: job_platform
# - database.sql dosyasını import edin
```

#### 3. Konfigürasyon Ayarları

`config/config.php` dosyasını düzenleyin:

```php
// Veritabanı Ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_platform');
define('DB_USER', 'root');
define('DB_PASS', 'mysql'); // AMPPS varsayılan şifresi

// Site URL
define('SITE_URL', 'http://localhost/isealim');

// OpenAI API Key (ÖNEMLİ!)
define('OPENAI_API_KEY', 'sk-your-openai-api-key-here');

// Güvenlik Anahtarı (Production için değiştirin!)
define('ENCRYPTION_KEY', 'your-32-character-secret-key-here-change-in-production');
```

#### 4. Klasör İzinleri

Windows üzerinde genellikle gerekli değildir, ancak kontrol edin:
```bash
# storage klasörü yazılabilir olmalı
# storage/uploads
# storage/logs
# storage/cache
```

Linux/Mac için:
```bash
chmod -R 755 storage
chmod -R 755 public/assets
```

#### 5. Apache .htaccess Kontrolü

`.htaccess` dosyasının root dizinde olduğundan emin olun.

AMPPS Apache ayarlarında `mod_rewrite` aktif olmalı:
- AMPPS Control Panel → Apache → Configuration → httpd.conf
- `LoadModule rewrite_module modules/mod_rewrite.so` satırı açık olmalı

#### 6. OpenAI API Key Alma

1. https://platform.openai.com adresine gidin
2. Hesap oluşturun veya giriş yapın
3. API Keys bölümünden yeni bir key oluşturun
4. Key'i `config/config.php` dosyasına ekleyin

**ÖNEMLİ:** API kullanımı ücretlidir, free tier limitleri vardır.

### 🔐 İlk Giriş Bilgileri

Sistem kurulumdan sonra aşağıdaki kullanıcılarla test edebilirsiniz:

**Admin:**
- E-posta: admin@jobplatform.com
- Şifre: admin123

**İşveren:**
- E-posta: isveren@example.com
- Şifre: employer123

**Başvuran:**
- E-posta: basvuran@example.com
- Şifre: applicant123

### 📱 Siteye Erişim

Kurulum tamamlandıktan sonra:
```
http://localhost/isealim
```

adresinden siteye erişebilirsiniz.

### 🛠️ Özellik Testleri

#### İşveren Paneli Test
1. İşveren hesabı ile giriş yapın
2. "Yeni İş İlanı Oluştur" tıklayın
3. İş bilgilerini doldurun
4. "AI ile Form Oluştur" butonuna tıklayın
5. Form alanları otomatik oluşturulacak
6. Düzenleyip yayınlayın

#### Başvuran Paneli Test
1. Başvuran hesabı ile giriş yapın
2. İş ilanlarına göz atın
3. Bir ilana başvurun
4. CV yükleyin ve formu doldurun
5. AI otomatik değerlendirme yapacak

### 🔧 Sorun Giderme

#### "Page Not Found" Hatası
- `.htaccess` dosyasının olduğundan emin olun
- Apache `mod_rewrite` modülünün aktif olduğunu kontrol edin
- `AllowOverride All` ayarının Apache config'de olduğundan emin olun

#### "Database Connection Error"
- MySQL servisinin çalıştığından emin olun
- Veritabanı adı, kullanıcı adı ve şifrenin doğru olduğunu kontrol edin
- Veritabanının oluşturulduğundan emin olun

#### "OpenAI API Error"
- API key'in doğru girildiğinden emin olun
- OpenAI hesabınızda bakiye olup olmadığını kontrol edin
- Internet bağlantınızı kontrol edin

#### "File Upload Error"
- `storage/uploads` klasörünün yazılabilir olduğunu kontrol edin
- PHP `upload_max_filesize` ve `post_max_size` ayarlarını kontrol edin

### 📊 Veritabanı Yapısı

Platform şu tablolardan oluşur:
- **users**: Kullanıcılar (işveren, başvuran, admin)
- **jobs**: İş ilanları
- **job_form_fields**: Dinamik form alanları
- **applications**: Başvurular
- **notifications**: Bildirimler
- **messages**: Mesajlaşma sistemi
- **sessions**: Oturum yönetimi
- **activity_logs**: Aktivite logları
- **ai_processing_logs**: AI işlem logları
- **system_settings**: Sistem ayarları

### 🔒 Güvenlik Önerileri

**Production'a geçmeden önce:**

1. Tüm şifreleri değiştirin
2. `ENVIRONMENT` değişkenini 'production' yapın
3. `ENCRYPTION_KEY` benzersiz bir değer verin
4. HTTPS sertifikası kurun
5. OpenAI API key'i güvenli şekilde saklayın
6. Veritabanı yedeklerini düzenli alın
7. Güvenlik güncellemelerini takip edin

### 📦 Opsiyonel: Composer Paketleri

PDF ve DOCX parse özellikleri için:

```bash
composer require smalot/pdfparser
composer require phpoffice/phpword
```

Bu paketler olmadan da çalışır, ancak CV parse özelliği sınırlı olur.

### 📞 Destek

Sorularınız için:
- E-posta: support@jobplatform.com
- Dokümantasyon: [Proje Wiki'si]

### 📄 Lisans

Bu proje özel bir proje olarak geliştirilmiştir.
Ticari kullanım için lisans gereklidir.

---

**Not:** Bu bir production-ready platformdur. Test ortamında denedikten sonra production'a alınmalıdır.
