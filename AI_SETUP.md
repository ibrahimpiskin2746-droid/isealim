# 🤖 AI Entegrasyonu - Kurulum Rehberi

## 🚀 Hızlı Başlangıç

### Mod 1: Demo Mode (Ücretsiz - Hemen Kullanın!)
✅ **Herhangi bir ayar yapmaya gerek YOK!**  
Sistem otomatik olarak demo modda çalışır ve simüle edilmiş AI yanıtları kullanır.

**Demo Mode Özellikleri:**
- ✨ Otomatik form oluşturma (örnek alanlar)
- 📊 Aday değerlendirme (simüle skorlar)
- 🎯 Tüm AI özellikleri çalışır
- 💰 Tamamen ücretsiz

### Mod 2: Gerçek AI (OpenAI API)

Gerçek OpenAI API kullanarak daha akıllı ve özelleştirilmiş AI özellikleri:

#### Adım 1: API Key Alın
1. [platform.openai.com](https://platform.openai.com/api-keys) adresine gidin
2. Ücretsiz hesap oluşturun (kredi kartı gerekebilir)
3. "Create new secret key" butonuna tıklayın
4. API key'inizi kopyalayın (örn: `sk-...`)

#### Adım 2: API Key'i Ekleyin

**Yöntem A: config.php Dosyası**
```php
// c:\Program Files\Ampps\www\isealim\config\config.php
define('OPENAI_API_KEY', 'sk-your-api-key-here');
```

**Yöntem B: Çevre Değişkeni**
```bash
# Windows PowerShell
$env:OPENAI_API_KEY = "sk-your-api-key-here"

# Linux/Mac
export OPENAI_API_KEY="sk-your-api-key-here"
```

#### Adım 3: Test Edin
Tarayıcınızda açın:
```
http://localhost/isealim/public/ai-setup.php
```

"AI Test Et" butonuna tıklayarak API'nizin çalıştığını doğrulayın.

## 🎯 AI Özellikleri

### 1. Otomatik Form Oluşturma
İş ilanı tanımından akıllı başvuru formu oluşturur:
- Pozisyona özel sorular
- Teknik yetenek değerlendirmesi
- Deneyim ve eğitim soruları
- Soft skill değerlendirmesi

### 2. Aday Değerlendirme & Skorlama
Her başvuru otomatik olarak değerlendirilir:
- 0-100 arası skor
- Güçlü/zayıf yönler analizi
- Teknik uyum skoru
- Deneyim uyumu
- Kültürel uyum tahmini

### 3. CV Analizi
PDF/DOCX formatındaki CV'leri otomatik parse eder:
- Kişisel bilgiler
- İş deneyimi
- Eğitim geçmişi
- Yetenekler ve anahtar kelimeler

## ⚙️ Gelişmiş Ayarlar

### Model Seçimi
```php
// Önerilen: uygun fiyatlı ve hızlı
define('OPENAI_MODEL', 'gpt-4o-mini');

// Alternatifler:
// 'gpt-4o'               // En güçlü model
// 'gpt-4-turbo'          // Hızlı ve güçlü
// 'gpt-3.5-turbo'        // En ucuz
```

### Token ve Temperature
```php
define('OPENAI_MAX_TOKENS', 4000);     // Yanıt uzunluğu
define('OPENAI_TEMPERATURE', 0.7);     // Yaratıcılık (0-2)
define('AI_TIMEOUT', 60);              // Timeout (saniye)
```

## 💰 Maliyet Bilgisi

**gpt-4o-mini** (önerilen):
- Input: $0.15 / 1M token
- Output: $0.60 / 1M token
- Ortalama form oluşturma: ~500 token = **$0.0003** ✨

**Tahmini Maliyetler:**
- 100 form oluşturma: ~$0.03
- 100 aday değerlendirme: ~$0.05
- Aylık (1000 başvuru): ~$0.50

## 🔒 Güvenlik

✅ API key'ler asla client-side'a gönderilmez  
✅ Tüm AI istekleri server-side  
✅ Rate limiting koruması  
✅ Error handling ve logging

## 🐛 Sorun Giderme

### "API key yapılandırılmamış" Hatası
- config.php dosyasında API key'i kontrol edin
- Tırnak işaretlerini doğru kullandığınızdan emin olun
- Sayfayı yeniledikten sonra değişiklik olmuyorsa cache'i temizleyin

### "Rate limit exceeded" Hatası
- OpenAI hesabınızın kullanım limitini kontrol edin
- Faturalama ayarlarınızı platform.openai.com'dan kontrol edin

### Demo Mode'dan Çıkamıyorum
- AI Setup sayfasına gidin ve durumu kontrol edin
- API key doğru girilmiş mi?
- Web sunucusunu yeniden başlatın

## 📚 Dokümantasyon

- [OpenAI API Docs](https://platform.openai.com/docs)
- [Model Pricing](https://openai.com/pricing)
- [Best Practices](https://platform.openai.com/docs/guides/production-best-practices)

## 🎉 Hazırsınız!

Artık AI özellikleri kullanıma hazır:
- ✅ İş ilanı oluşturun: `/employer/create-job`
- ✅ AI formu oluşturun: `/employer/generate-form/{job_id}`
- ✅ Başvuruları değerlendirin: `/employer/applications`

**Demo Mode'da mı çalışıyorsunuz?**  
Sorun değil! Tüm özellikler çalışır, sadece gerçek AI yerine örnek veriler kullanılır.

---

**İyi Çalışmalar! 🚀**
