# 🔧 Veritabanı Bağlantı Sorunu Çözüm Rehberi

## Hızlı Çözüm Adımları

### 1️⃣ Test Scriptini Çalıştırın

Tarayıcıda şu adresi açın:
```
http://localhost/isealim/test-db-connection.php
```

Bu script otomatik olarak:
- ✅ PHP eklentilerini kontrol eder
- ✅ MySQL sunucusuna bağlanmayı dener
- ✅ Veritabanının var olup olmadığını kontrol eder
- ✅ Veritabanını otomatik oluşturabilir
- ✅ Detaylı hata mesajları gösterir

### 2️⃣ Manuel Kontroller

#### A) AMPPS MySQL Servisi
1. **AMPPS Control Panel**'i açın
2. **MySQL** servisinin **yeşil** (çalışıyor) olduğundan emin olun
3. Eğer kırmızıysa, **Start** butonuna tıklayın

#### B) Config Dosyası Ayarları

`config/config.php` dosyasını açın ve şu satırları kontrol edin:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_platform');
define('DB_USER', 'root');
define('DB_PASS', '');  // AMPPS'te genellikle boş veya 'mysql'
```

**AMPPS için önerilen ayarlar:**
- `DB_HOST`: `localhost`
- `DB_USER`: `root`
- `DB_PASS`: `''` (boş) veya `'mysql'`

#### C) Veritabanını Oluşturma

**Yöntem 1: phpMyAdmin ile**
1. Tarayıcıda: `http://localhost/phpmyadmin`
2. Sol menüden **"Yeni"** (New) tıklayın
3. Veritabanı adı: `job_platform`
4. Karakter seti: `utf8mb4_unicode_ci`
5. **Oluştur** butonuna tıklayın
6. `database.sql` dosyasını import edin

**Yöntem 2: Terminal ile**
```bash
# MySQL'e bağlan
mysql -u root -p

# Veritabanını oluştur
CREATE DATABASE IF NOT EXISTS job_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Veritabanını kullan
USE job_platform;

# SQL dosyasını import et
SOURCE "C:/Program Files/Ampps/www/isealim/database.sql";
```

**Yöntem 3: Test Script ile**
- `test-db-connection.php` sayfasında "Veritabanını Oluştur" butonuna tıklayın

### 3️⃣ Yaygın Hatalar ve Çözümleri

#### ❌ "Access denied for user 'root'@'localhost'"
**Çözüm:**
- `config/config.php` dosyasındaki `DB_PASS` değerini kontrol edin
- AMPPS'te genellikle boş (`''`) veya `'mysql'` olmalı
- phpMyAdmin'den giriş yaparak şifrenizi doğrulayın

#### ❌ "Unknown database 'job_platform'"
**Çözüm:**
- Veritabanını oluşturun (yukarıdaki adımlara bakın)
- Veritabanı adının doğru olduğundan emin olun

#### ❌ "Connection refused" veya "Can't connect to MySQL server"
**Çözüm:**
- MySQL servisinin çalıştığından emin olun
- AMPPS Control Panel'den MySQL'i başlatın
- Port 3306'nın açık olduğundan emin olun

#### ❌ "PDO extension not loaded"
**Çözüm:**
- `php.ini` dosyasını açın
- Şu satırların başındaki `;` işaretini kaldırın:
  ```ini
  extension=pdo
  extension=pdo_mysql
  ```
- Apache'yi yeniden başlatın

### 4️⃣ Veritabanı Şifresi Değiştirme

Eğer AMPPS'te MySQL şifresi değiştirildiyse:

1. `config/config.php` dosyasını açın
2. `DB_PASS` değerini güncelleyin:
   ```php
   define('DB_PASS', 'yeni_sifreniz');
   ```

### 5️⃣ Test ve Doğrulama

Bağlantı başarılı olduğunda:

1. ✅ `test-db-connection.php` sayfasında tüm testler yeşil olmalı
2. ✅ Ana sayfa açılmalı: `http://localhost/isealim`
3. ✅ Giriş sayfası çalışmalı: `http://localhost/isealim/auth/login`

### 6️⃣ Güvenlik Notu

**ÖNEMLİ:** Test scriptini (`test-db-connection.php`) production ortamında kullanmayın!
- Test tamamlandıktan sonra bu dosyayı silin veya erişimi kısıtlayın

### 7️⃣ Hala Çalışmıyorsa

1. **Log dosyalarını kontrol edin:**
   - `storage/logs/` klasöründeki son log dosyasını açın
   - Hata mesajlarını okuyun

2. **phpMyAdmin'den test edin:**
   - `http://localhost/phpmyadmin`
   - Aynı bilgilerle giriş yapabiliyor musunuz?

3. **MySQL portunu kontrol edin:**
   - AMPPS'te varsayılan port: `3306`
   - Farklı bir port kullanıyorsanız, `DB_HOST` değerini güncelleyin:
     ```php
     define('DB_HOST', 'localhost:3307'); // Örnek
     ```

### 8️⃣ Başarılı Kurulum Sonrası

Tüm testler başarılı olduğunda:
- ✅ `test-db-connection.php` dosyasını silebilirsiniz
- ✅ Siteyi kullanmaya başlayabilirsiniz
- ✅ Test kullanıcıları ile giriş yapabilirsiniz

---

## 📞 Ek Yardım

Eğer hala sorun yaşıyorsanız:
1. `test-db-connection.php` sayfasının tam çıktısını kaydedin
2. `storage/logs/` klasöründeki hata loglarını kontrol edin
3. AMPPS versiyonunuzu ve PHP versiyonunuzu not edin

**Başarılar!** 🚀


