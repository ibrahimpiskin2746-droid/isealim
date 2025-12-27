# AI PROMPT DOKÜMANTASYONU
## Sistemde Kullanılan AI Promptları

Bu dokümantasyon, platformda kullanılan tüm AI promptlarını içerir.

---

## 1. İŞ İLANI FORM OLUŞTURMA PROMPTU

**Amaç:** İş tanımından otomatik başvuru formu oluşturma

**Kullanım Yeri:** `AIService::generateJobForm()`

**Prompt Şablonu:**

```
İş İlanı: {job_title}

İş Tanımı:
{job_description}

Bu iş ilanı için profesyonel bir başvuru formu oluştur. Form aşağıdaki kategorilerdeki soruları içermelidir:

1. Kişisel Bilgiler (ad, soyad, email, telefon, vb.)
2. Teknik Yetenekler (pozisyonla ilgili teknik sorular)
3. Deneyim (çalışma geçmişi, projeler)
4. Eğitim
5. Yetkinlikler (soft skills)
6. Açık uçlu sorular

Her alan için şu bilgileri JSON formatında döndür:
{
    "fields": [
        {
            "field_type": "text|textarea|select|radio|checkbox|date|number|email|phone",
            "field_label": "Soru metni",
            "field_name": "field_name_snake_case",
            "field_placeholder": "Örnek metin",
            "field_options": ["Option 1", "Option 2"], // sadece select, radio, checkbox için
            "is_required": true|false,
            "field_category": "personal|technical|experience|soft-skill|open-ended",
            "ai_scoring_weight": 0.5-2.0 // bu alanın değerlendirmedeki ağırlığı
        }
    ]
}

10-15 alan oluştur. Türkçe dilinde oluştur.
```

**Örnek Yanıt:**
```json
{
    "fields": [
        {
            "field_type": "text",
            "field_label": "Ad Soyad",
            "field_name": "full_name",
            "field_placeholder": "Adınız ve soyadınız",
            "is_required": true,
            "field_category": "personal",
            "ai_scoring_weight": 0.5
        },
        {
            "field_type": "email",
            "field_label": "E-posta Adresi",
            "field_name": "email",
            "field_placeholder": "ornek@email.com",
            "is_required": true,
            "field_category": "personal",
            "ai_scoring_weight": 0.5
        },
        {
            "field_type": "select",
            "field_label": "PHP deneyim süreniz?",
            "field_name": "php_experience",
            "field_options": ["1 yıldan az", "1-3 yıl", "3-5 yıl", "5+ yıl"],
            "is_required": true,
            "field_category": "technical",
            "ai_scoring_weight": 2.0
        }
    ]
}
```

---

## 2. CV PARSING PROMPTU

**Amaç:** CV'den yapılandırılmış bilgi çıkarma

**Kullanım Yeri:** `AIService::parseCV()`

**Prompt Şablonu:**

```
Aşağıdaki CV metnini analiz et ve aşağıdaki bilgileri JSON formatında çıkar:

{
    "personal_info": {
        "name": "",
        "email": "",
        "phone": "",
        "location": ""
    },
    "summary": "",
    "skills": ["skill1", "skill2"],
    "experience": [
        {
            "title": "",
            "company": "",
            "duration": "",
            "description": ""
        }
    ],
    "education": [
        {
            "degree": "",
            "school": "",
            "year": ""
        }
    ],
    "languages": ["Türkçe", "İngilizce"],
    "keywords": ["keyword1", "keyword2"]
}

CV Metni:
{cv_text}
```

**Sistem Mesajı:**
```
Sen bir CV analiz uzmanısın. CV'leri analiz edip yapılandırılmış veri çıkarıyorsun.
```

---

## 3. ADAY DEĞERLENDİRME PROMPTU

**Amaç:** Başvuranı değerlendirip skorlama

**Kullanım Yeri:** `AIService::evaluateCandidate()`

**Prompt Şablonu:**

```
İş Tanımı:
{job_description}

İş Gereksinimleri:
{job_requirements}

Adayın Form Yanıtları:
{candidate_form_responses}

CV Özeti:
{parsed_cv_summary}

Bu adayı iş pozisyonu için değerlendir ve aşağıdaki formatta JSON döndür:

{
    "score": 85, // 0-100 arası genel uyumluluk skoru
    "strengths": "Adayın güçlü yönleri (kısa liste)",
    "weaknesses": "Adayın zayıf yönleri veya eksiklikleri",
    "summary": "2-3 cümlelik genel değerlendirme",
    "details": {
        "technical_match": 90, // Teknik yetkinlik uyumu (0-100)
        "experience_match": 80, // Deneyim uyumu (0-100)
        "education_match": 85, // Eğitim uyumu (0-100)
        "soft_skills": 88, // Soft skill değerlendirmesi (0-100)
        "culture_fit": 82 // Kültürel uyum tahmini (0-100)
    }
}

Objektif ve adil bir değerlendirme yap.
```

**Sistem Mesajı:**
```
Sen deneyimli bir İK uzmanısın. Adayları objektif kriterlere göre değerlendiriyorsun ve 0-100 arası skor veriyorsun.
```

**Değerlendirme Kriterleri:**

1. **Teknik Yetkinlik (30%):**
   - Gerekli teknolojilerde deneyim
   - Proje örnekleri
   - Sertifikalar

2. **Deneyim Uyumu (25%):**
   - Toplam çalışma süresi
   - İlgili pozisyonlarda deneyim
   - Sektör uyumu

3. **Eğitim (15%):**
   - Eğitim seviyesi
   - İlgili bölüm
   - Ek eğitimler

4. **Soft Skills (15%):**
   - İletişim becerileri
   - Problem çözme
   - Takım çalışması
   - Liderlik

5. **Kültürel Uyum (15%):**
   - Şirket değerleriyle uyum
   - Çalışma tarzı
   - Motivasyon

**Skor Aralıkları:**
- 90-100: Mükemmel eşleşme
- 80-89: Çok iyi eşleşme
- 70-79: İyi eşleşme
- 60-69: Orta seviye eşleşme
- 50-59: Zayıf eşleşme
- 0-49: Uygun değil

---

## 4. ÖZEL PROMTLAR

### 4.1 Backend Developer Pozisyonu Örneği

```
İş İlanı: Senior PHP Backend Developer

İş Tanımı:
E-ticaret platformumuz için 5+ yıl deneyime sahip Senior Backend Developer arıyoruz. 
Adayın PHP, Laravel, MySQL, Redis, RESTful API geliştirme konularında derin bilgisi olmalı.
Microservices mimarisinde çalışmış, Docker ve Kubernetes deneyimi olan adaylar tercih edilecektir.
Scrum/Agile metodolojilerine hakim, takım çalışmasına yatkın olmak gereklidir.

Form alanları oluştur.
```

**Beklenen Form Alanları:**
- Ad Soyad, Email, Telefon (Kişisel)
- PHP deneyim süresi (seçenek)
- Laravel proje sayısı
- MySQL query optimizasyonu deneyimi (evet/hayır)
- RESTful API geliştirme deneyimi
- Docker kullanım deneyimi
- En son geliştirdiğiniz proje (açık uçlu)
- Tercihen microservices deneyimi
- Takım büyüklüğü (kaç kişiyle çalıştınız)
- Agile/Scrum deneyimi

### 4.2 Frontend Developer Pozisyonu Örneği

```
İş İlanı: React Frontend Developer

İş Tanımı:
Modern web uygulamaları geliştiren ekibimize 3+ yıl React deneyimine sahip Frontend Developer arıyoruz.
React, Redux, TypeScript, Next.js teknolojilerinde deneyimli, responsive ve mobile-first yaklaşımı benimsemiş,
REST API entegrasyonu yapabilen, UI/UX prensipleri hakkında bilgili olmak gereklidir.

Form alanları oluştur.
```

---

## 5. AI MODEL AYARLARI

**Kullanılan Model:** GPT-4 Turbo Preview

**Parametreler:**
```php
'model' => 'gpt-4-turbo-preview',
'temperature' => 0.7,        // Yaratıcılık seviyesi (0-1)
'max_tokens' => 4000,        // Maksimum yanıt uzunluğu
'response_format' => ['type' => 'json_object']  // JSON zorunlu
```

**Temperature Ayarları:**
- Form Oluşturma: 0.7 (daha yaratıcı)
- CV Parsing: 0.3 (daha kesin)
- Değerlendirme: 0.5 (dengeli)

---

## 6. HATA YÖNETİMİ

**API Hataları:**
```php
// Timeout
'timeout' => 60 // saniye

// Hata durumunda fallback
if (!$result['success']) {
    // Manuel değerlendirme moduna geç
    // veya default skor ver
}
```

**Hata Logları:**
Tüm AI işlemleri `ai_processing_logs` tablosuna kaydedilir:
- Prompt metni
- Yanıt
- Kullanılan token sayısı
- İşlem süresi
- Başarı durumu

---

## 7. OPTİMİZASYON İPUÇLARI

### Token Tasarrufu
1. Prompt'ları kısa ve öz tutun
2. Gereksiz detay vermeyin
3. Örnekleri sınırlı kullanın

### Kalite Artırma
1. Spesifik talimatlar verin
2. JSON format zorunlu yapın
3. Sistem mesajlarını kullanın
4. Few-shot örnekler ekleyin

### Maliyet Kontrolü
1. Cache kullanın (benzer promtlar)
2. Batch işlemler yapın
3. Rate limiting uygulayın
4. Kritik olmayan işlemlerde arka plan işleme

---

## 8. TEST SENARYOLARI

### Test 1: Form Oluşturma
```php
$jobDescription = "2 yıl Python deneyimi olan Data Scientist arıyoruz";
$result = $aiService->generateJobForm($jobDescription, "Data Scientist");

// Beklenen: 10-15 form alanı
// Kategoriler: personal, technical, experience, education
```

### Test 2: CV Parse
```php
$cvText = "Ahmet Yılmaz\nahmet@email.com\n5 yıl PHP deneyimi...";
$result = $aiService->parseCV('/path/to/cv.pdf');

// Beklenen: Yapılandırılmış JSON
// İçerik: personal_info, skills, experience, education
```

### Test 3: Değerlendirme
```php
$evaluation = $aiService->evaluateCandidate(
    $jobDescription,
    $requirements,
    $candidateData,
    $cvText
);

// Beklenen: Skor 0-100
// Detaylar: 5 kategori skoru
// Metin: strengths, weaknesses, summary
```

---

## 9. EN İYİ UYGULAMALAR

✅ **DO (Yapın):**
- JSON formatını zorunlu tutun
- Hataları yakalayın ve logları
- Timeout değerlerini ayarlayın
- Hassas verileri loglama
- API key'i güvenle saklayın
- Rate limiting uygulayın

❌ **DON'T (Yapmayın):**
- API key'i kodda bırakmayın
- Sınırsız token kullanmayın
- Her istekte yeni bağlantı açmayın
- Kullanıcı verilerini AI'a göndermeden önce temizlemeyin

---

**Son Güncelleme:** 26 Aralık 2025
**Versiyon:** 1.0.0
