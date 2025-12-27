<?php
/**
 * AI Service
 * OpenAI API entegrasyonu ve AI işlemleri
 */

class AIService {
    private $apiKey;
    private $model;
    private $baseUrl = 'https://api.openai.com/v1';
    
    public function __construct() {
        $this->apiKey = OPENAI_API_KEY;
        $this->model = OPENAI_MODEL;
        
        if (empty($this->apiKey)) {
            logMessage('OpenAI API key is not configured', 'warning');
        }
    }
    
    /**
     * OpenAI API isteği gönderir
     */
    private function makeRequest($endpoint, $data) {
        if (empty($this->apiKey)) {
            logMessage('OpenAI API key not configured - using demo mode', 'warning');
            return ['success' => false, 'error' => 'API key yapılandırılmamış', 'demo_mode' => true];
        }
        
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, AI_TIMEOUT);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            logMessage("OpenAI API curl error: {$error}", 'error');
            return ['success' => false, 'error' => $error];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200) {
            $errorMsg = $result['error']['message'] ?? 'Unknown error';
            logMessage("OpenAI API error: {$errorMsg}", 'error');
            return ['success' => false, 'error' => $errorMsg];
        }
        
        return ['success' => true, 'data' => $result];
    }
    
    /**
     * İş tanımından form alanları oluşturur
     */
    public function generateJobForm($jobDescription, $jobTitle = '') {
        // Demo mode kontrolü
        if (AI_DEMO_MODE) {
            return $this->getDemoFormFields($jobTitle, $jobDescription);
        }
        
        $prompt = $this->buildFormGenerationPrompt($jobDescription, $jobTitle);
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Sen bir İK uzmanısın. İş ilanlarına göre etkili başvuru formları oluşturuyorsun. Cevaplarını her zaman JSON formatında ver.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => OPENAI_TEMPERATURE,
            'max_tokens' => OPENAI_MAX_TOKENS,
            'response_format' => ['type' => 'json_object']
        ];
        
        $startTime = microtime(true);
        $response = $this->makeRequest('/chat/completions', $data);
        $processingTime = microtime(true) - $startTime;
        
        if (!$response['success']) {
            return $response;
        }
        
        $content = $response['data']['choices'][0]['message']['content'] ?? '';
        $formFields = json_decode($content, true);
        
        // AI işlem logunu kaydet
        $this->logAIProcessing(
            null,
            null,
            'form-generation',
            $prompt,
            $content,
            $response['data']['usage']['total_tokens'] ?? 0,
            $processingTime,
            true
        );
        
        return [
            'success' => true,
            'fields' => $formFields['fields'] ?? []
        ];
    }
    
    /**
     * Form oluşturma prompt'u hazırlar
     */
    private function buildFormGenerationPrompt($jobDescription, $jobTitle) {
        return <<<PROMPT
İş İlanı: {$jobTitle}

İş Tanımı:
{$jobDescription}

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
PROMPT;
    }
    
    /**
     * CV dosyasını parse eder
     */
    public function parseCV($cvFilePath) {
        // CV'den metin çıkarımı
        $cvText = $this->extractTextFromCV($cvFilePath);
        
        if (!$cvText) {
            return ['success' => false, 'error' => 'CV metni çıkarılamadı'];
        }
        
        $prompt = <<<PROMPT
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
{$cvText}
PROMPT;
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Sen bir CV analiz uzmanısın. CV\'leri analiz edip yapılandırılmış veri çıkarıyorsun.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => OPENAI_MAX_TOKENS,
            'response_format' => ['type' => 'json_object']
        ];
        
        $response = $this->makeRequest('/chat/completions', $data);
        
        if (!$response['success']) {
            return $response;
        }
        
        $content = $response['data']['choices'][0]['message']['content'] ?? '';
        $parsedCV = json_decode($content, true);
        
        return [
            'success' => true,
            'data' => $parsedCV
        ];
    }
    
    /**
     * Adayı değerlendirir ve skorlar
     */
    public function evaluateCandidate($jobDescription, $jobRequirements, $candidateData, $cvText = '') {
        // Demo mode kontrolü
        if (AI_DEMO_MODE) {
            return $this->getDemoEvaluation($jobDescription);
        }
        
        $prompt = $this->buildEvaluationPrompt($jobDescription, $jobRequirements, $candidateData, $cvText);
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Sen deneyimli bir İK uzmanısın. Adayları objektif kriterlere göre değerlendiriyorsun ve 0-100 arası skor veriyorsun.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.5,
            'max_tokens' => OPENAI_MAX_TOKENS,
            'response_format' => ['type' => 'json_object']
        ];
        
        $startTime = microtime(true);
        $response = $this->makeRequest('/chat/completions', $data);
        $processingTime = microtime(true) - $startTime;
        
        if (!$response['success']) {
            return $response;
        }
        
        $content = $response['data']['choices'][0]['message']['content'] ?? '';
        $evaluation = json_decode($content, true);
        
        return [
            'success' => true,
            'score' => $evaluation['score'] ?? 0,
            'strengths' => $evaluation['strengths'] ?? '',
            'weaknesses' => $evaluation['weaknesses'] ?? '',
            'summary' => $evaluation['summary'] ?? '',
            'details' => $evaluation['details'] ?? []
        ];
    }
    
    /**
     * Değerlendirme prompt'u oluşturur
     */
    private function buildEvaluationPrompt($jobDescription, $jobRequirements, $candidateData, $cvText) {
        $formResponses = json_encode($candidateData, JSON_UNESCAPED_UNICODE);
        
        return <<<PROMPT
İş Tanımı:
{$jobDescription}

İş Gereksinimleri:
{$jobRequirements}

Adayın Form Yanıtları:
{$formResponses}

CV Özeti:
{$cvText}

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
PROMPT;
    }
    
    /**
     * CV'den metin çıkarır
     */
    private function extractTextFromCV($filePath) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        try {
            if ($extension === 'pdf') {
                // PDF Parser (gerekli: Smalot\PdfParser)
                // composer require smalot/pdfparser
                if (class_exists('Smalot\PdfParser\Parser')) {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($filePath);
                    return $pdf->getText();
                }
            } elseif ($extension === 'docx') {
                // DOCX Parser (gerekli: PhpOffice\PhpWord)
                if (class_exists('PhpOffice\PhpWord\IOFactory')) {
                    $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                    $text = '';
                    foreach ($phpWord->getSections() as $section) {
                        foreach ($section->getElements() as $element) {
                            if (method_exists($element, 'getText')) {
                                $text .= $element->getText() . "\n";
                            }
                        }
                    }
                    return $text;
                }
            }
            
            // Fallback: basit metin okuma
            return file_get_contents($filePath);
            
        } catch (Exception $e) {
            logMessage("CV parse error: " . $e->getMessage(), 'error');
            return false;
        }
    }
    
    /**
     * AI işlem logunu kaydeder
     */
    private function logAIProcessing($jobId, $applicationId, $processType, $prompt, $response, $tokensUsed, $processingTime, $success) {
        $db = Database::getInstance();
        
        $sql = "INSERT INTO ai_processing_logs 
                (job_id, application_id, process_type, ai_model, prompt_text, response_text, 
                 tokens_used, processing_time, success)
                VALUES 
                (:job_id, :application_id, :process_type, :ai_model, :prompt_text, :response_text,
                 :tokens_used, :processing_time, :success)";
        
        $db->query($sql)
            ->bind(':job_id', $jobId)
            ->bind(':application_id', $applicationId)
            ->bind(':process_type', $processType)
            ->bind(':ai_model', $this->model)
            ->bind(':prompt_text', substr($prompt, 0, 5000))
            ->bind(':response_text', substr($response, 0, 5000))
            ->bind(':tokens_used', $tokensUsed)
            ->bind(':processing_time', $processingTime)
            ->bind(':success', $success ? 1 : 0)
            ->execute();
    }
    
    /**
     * Demo mode için örnek form alanları
     */
    private function getDemoFormFields($jobTitle, $jobDescription = '') {
        // İş başlığından pozisyon türünü analiz et
        $jobTitleLower = mb_strtolower($jobTitle, 'UTF-8');
        $jobDescLower = mb_strtolower($jobDescription, 'UTF-8');
        
        // Temel alanlar (her pozisyon için)
        $fields = [
            [
                'field_type' => 'text',
                'field_label' => 'Ad Soyad',
                'field_name' => 'full_name',
                'field_placeholder' => 'Adınızı ve soyadınızı girin',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.5
            ],
            [
                'field_type' => 'email',
                'field_label' => 'E-posta Adresi',
                'field_name' => 'email',
                'field_placeholder' => 'ornek@email.com',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.5
            ],
            [
                'field_type' => 'phone',
                'field_label' => 'Telefon',
                'field_name' => 'phone',
                'field_placeholder' => '0555 123 45 67',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.3
            ]
        ];
        
        // Pozisyona özel teknik sorular ekle
        if (stripos($jobTitleLower, 'developer') !== false || 
            stripos($jobTitleLower, 'geliştirici') !== false ||
            stripos($jobTitleLower, 'yazılım') !== false ||
            stripos($jobDescLower, 'developer') !== false ||
            stripos($jobDescLower, 'geliştir') !== false) {
            
            // Hangi teknoloji? (başlık VE açıklamadan tespit et)
            $techOptions = [];
            $combinedText = $jobTitleLower . ' ' . $jobDescLower;
            
            if (stripos($combinedText, 'php') !== false || stripos($combinedText, 'laravel') !== false) {
                $techOptions = ['PHP', 'Laravel', 'Symfony', 'CodeIgniter', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'Git', 'RESTful API', 'Composer', 'PHPUnit'];
            } elseif (stripos($combinedText, 'javascript') !== false || 
                      stripos($combinedText, 'react') !== false || 
                      stripos($combinedText, 'vue') !== false || 
                      stripos($combinedText, 'angular') !== false ||
                      stripos($combinedText, 'frontend') !== false ||
                      stripos($combinedText, 'front-end') !== false) {
                $techOptions = ['JavaScript', 'React', 'Vue.js', 'Angular', 'TypeScript', 'HTML5', 'CSS3', 'SASS/SCSS', 'Webpack', 'Vite', 'REST API', 'GraphQL'];
            } elseif (stripos($combinedText, 'python') !== false || stripos($combinedText, 'django') !== false || stripos($combinedText, 'flask') !== false) {
                $techOptions = ['Python', 'Django', 'Flask', 'FastAPI', 'Pandas', 'NumPy', 'PostgreSQL', 'MongoDB', 'Docker', 'Git', 'Celery', 'pytest'];
            } elseif (stripos($combinedText, 'java') !== false && stripos($combinedText, 'javascript') === false) {
                $techOptions = ['Java', 'Spring Boot', 'Hibernate', 'Maven', 'Gradle', 'MySQL', 'PostgreSQL', 'Microservices', 'Docker', 'Kubernetes', 'JUnit'];
            } elseif (stripos($combinedText, 'mobile') !== false || 
                      stripos($combinedText, 'android') !== false || 
                      stripos($combinedText, 'ios') !== false ||
                      stripos($combinedText, 'flutter') !== false ||
                      stripos($combinedText, 'react native') !== false) {
                $techOptions = ['React Native', 'Flutter', 'Swift', 'Kotlin', 'Java', 'Android SDK', 'iOS SDK', 'Firebase', 'REST API', 'Git', 'Push Notifications'];
            } elseif (stripos($combinedText, 'full stack') !== false || stripos($combinedText, 'fullstack') !== false) {
                $techOptions = ['JavaScript', 'React/Vue/Angular', 'Node.js', 'PHP/Python/Java', 'SQL', 'MongoDB', 'REST API', 'Docker', 'Git', 'CI/CD'];
            } elseif (stripos($combinedText, 'devops') !== false) {
                $techOptions = ['Docker', 'Kubernetes', 'Jenkins', 'GitLab CI', 'AWS/Azure/GCP', 'Terraform', 'Ansible', 'Linux', 'Monitoring Tools', 'Git'];
            } else {
                $techOptions = ['JavaScript', 'Python', 'PHP', 'Java', 'C#', 'SQL', 'Git', 'Docker', 'REST API', 'Agile'];
            }
            
            $fields[] = [
                'field_type' => 'number',
                'field_label' => 'Kaç yıl yazılım geliştirme deneyiminiz var?',
                'field_name' => 'years_experience',
                'field_placeholder' => 'Yıl cinsinden',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.8
            ];
            
            $fields[] = [
                'field_type' => 'checkbox',
                'field_label' => 'Hangi teknolojilere hakimsiniz? (En az 3 seçiniz)',
                'field_name' => 'tech_skills',
                'field_placeholder' => null,
                'field_options' => $techOptions,
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.5
            ];
            
            $fields[] = [
                'field_type' => 'textarea',
                'field_label' => 'Son geliştirdiğiniz bir projeyi detaylı anlatın (kullanılan teknolojiler, rolünüz, çözülen problem)',
                'field_name' => 'recent_project',
                'field_placeholder' => 'Proje adı, teknolojiler, sizin rolünüz ve başarılar...',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.2
            ];
            
            $fields[] = [
                'field_type' => 'radio',
                'field_label' => 'Version control sistemlerinde deneyiminiz nasıl?',
                'field_name' => 'git_experience',
                'field_placeholder' => null,
                'field_options' => ['Günlük kullanıyorum, branch/merge işlemlerine hakimim', 'Temel komutları biliyorum', 'Yeni öğreniyorum', 'Deneyimim yok'],
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 1.5
            ];
            
            $fields[] = [
                'field_type' => 'select',
                'field_label' => 'Veritabanı yönetiminde deneyim seviyeniz?',
                'field_name' => 'database_level',
                'field_placeholder' => 'Seçiniz',
                'field_options' => ['İleri seviye - Kompleks sorgular, optimizasyon, indexleme', 'Orta seviye - CRUD, JOIN, subquery', 'Başlangıç - Temel sorgular', 'Deneyimim yok'],
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 1.8
            ];
            
            $fields[] = [
                'field_type' => 'textarea',
                'field_label' => 'Karşılaştığınız en zorlu teknik problemi ve nasıl çözdüğünüzü anlatın',
                'field_name' => 'technical_problem',
                'field_placeholder' => 'Problem, yaklaşımınız, kullandığınız yöntemler ve sonuç...',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.0
            ];
            
        } elseif (stripos($jobTitleLower, 'designer') !== false || 
                  stripos($jobTitleLower, 'tasarım') !== false || 
                  stripos($jobTitleLower, 'ui') !== false || 
                  stripos($jobTitleLower, 'ux') !== false) {
            
            $fields[] = [
                'field_type' => 'number',
                'field_label' => 'Kaç yıl tasarım deneyiminiz var?',
                'field_name' => 'years_experience',
                'field_placeholder' => 'Yıl cinsinden',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.5
            ];
            
            $fields[] = [
                'field_type' => 'checkbox',
                'field_label' => 'Hangi tasarım araçlarını kullanıyorsunuz?',
                'field_name' => 'design_tools',
                'field_placeholder' => null,
                'field_options' => ['Figma', 'Adobe XD', 'Sketch', 'Photoshop', 'Illustrator', 'InVision', 'Principle', 'After Effects'],
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.0
            ];
            
            $fields[] = [
                'field_type' => 'text',
                'field_label' => 'Portfolio linkiniz (Behance, Dribbble, kişisel site vb.)',
                'field_name' => 'portfolio_url',
                'field_placeholder' => 'https://...',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.5
            ];
            
            $fields[] = [
                'field_type' => 'textarea',
                'field_label' => 'Tasarım sürecinizi anlatın (research, wireframe, prototype, testing)',
                'field_name' => 'design_process',
                'field_placeholder' => 'Kullanıcı araştırmasından final tasarıma kadar adımlarınız...',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.2
            ];
            
        } elseif (stripos($jobTitleLower, 'product') !== false && 
                  stripos($jobTitleLower, 'manager') !== false) {
            
            $fields[] = [
                'field_type' => 'number',
                'field_label' => 'Kaç yıl product management deneyiminiz var?',
                'field_name' => 'years_experience',
                'field_placeholder' => 'Yıl cinsinden',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.8
            ];
            
            $fields[] = [
                'field_type' => 'checkbox',
                'field_label' => 'Hangi product management araçlarını kullanıyorsunuz?',
                'field_name' => 'pm_tools',
                'field_placeholder' => null,
                'field_options' => ['Jira', 'Confluence', 'Asana', 'Trello', 'Miro', 'Figma', 'Google Analytics', 'Mixpanel', 'Amplitude'],
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 1.8
            ];
            
            $fields[] = [
                'field_type' => 'textarea',
                'field_label' => 'Başarıyla yönettiğiniz bir ürün özelliğini anlatın (metrikler, impact)',
                'field_name' => 'product_success',
                'field_placeholder' => 'Özellik, hedef, süreç, sonuçlar ve metrikler...',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.5
            ];
            
        } else {
            // Genel pozisyonlar için
            $fields[] = [
                'field_type' => 'number',
                'field_label' => 'Kaç yıl ilgili alanda deneyiminiz var?',
                'field_name' => 'years_experience',
                'field_placeholder' => 'Yıl cinsinden',
                'field_options' => null,
                'is_required' => true,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.5
            ];
        }
        
        // Genel deneyim soruları
        $fields[] = [
            'field_type' => 'textarea',
            'field_label' => 'Son çalıştığınız pozisyonu ve sorumluluklarınızı detaylı anlatın',
            'field_name' => 'last_position',
            'field_placeholder' => 'Pozisyon, şirket, görevler ve başarılarınız...',
            'field_options' => null,
            'is_required' => true,
            'field_category' => 'experience',
            'ai_scoring_weight' => 1.8
        ];
        
        // Eğitim
        $fields[] = [
            'field_type' => 'select',
            'field_label' => 'Eğitim durumunuz?',
            'field_name' => 'education_level',
            'field_placeholder' => 'Seçiniz',
            'field_options' => ['Lise', 'Ön Lisans', 'Lisans', 'Yüksek Lisans', 'Doktora'],
            'is_required' => true,
            'field_category' => 'experience',
            'ai_scoring_weight' => 1.0
        ];
        
        $fields[] = [
            'field_type' => 'text',
            'field_label' => 'Üniversite/Bölüm',
            'field_name' => 'university',
            'field_placeholder' => 'Üniversite adı ve bölüm',
            'field_options' => null,
            'is_required' => false,
            'field_category' => 'experience',
            'ai_scoring_weight' => 0.8
        ];
        
        // Soft Skills
        $fields[] = [
            'field_type' => 'select',
            'field_label' => 'İngilizce seviyeniz?',
            'field_name' => 'english_level',
            'field_placeholder' => 'Seçiniz',
            'field_options' => ['Başlangıç (A1-A2)', 'Orta (B1-B2)', 'İleri (C1-C2)', 'Native/Anadil'],
            'is_required' => true,
            'field_category' => 'technical',
            'ai_scoring_weight' => 1.2
        ];
        
        $fields[] = [
            'field_type' => 'radio',
            'field_label' => 'Çalışma modeli tercihiniz?',
            'field_name' => 'work_preference',
            'field_placeholder' => null,
            'field_options' => ['Tam uzaktan', 'Hibrit (ofis + uzaktan)', 'Ofiste', 'Hepsine açığım'],
            'is_required' => true,
            'field_category' => 'soft-skill',
            'ai_scoring_weight' => 1.0
        ];
        
        $fields[] = [
            'field_type' => 'textarea',
            'field_label' => 'Takım çalışması deneyiminizden örnek verin',
            'field_name' => 'team_experience',
            'field_placeholder' => 'Takım projelerinde rolünüz, işbirliği şekliniz...',
            'field_options' => null,
            'is_required' => true,
            'field_category' => 'soft-skill',
            'ai_scoring_weight' => 1.3
        ];
        
        // Motivasyon Soruları
        $fields[] = [
            'field_type' => 'textarea',
            'field_label' => 'Neden bu pozisyon için başvuruyorsunuz? Sizi bu role çeken nedir?',
            'field_name' => 'motivation',
            'field_placeholder' => 'Pozisyona ilginiz, hedefleriniz, şirkete katkınız...',
            'field_options' => null,
            'is_required' => true,
            'field_category' => 'open-ended',
            'ai_scoring_weight' => 1.7
        ];
        
        $fields[] = [
            'field_type' => 'textarea',
            'field_label' => '3 yıl sonra kariyerinizde kendinizi nerede görüyorsunuz?',
            'field_name' => 'career_goals',
            'field_placeholder' => 'Kariyer hedefleriniz, gelişmek istediğiniz alanlar...',
            'field_options' => null,
            'is_required' => true,
            'field_category' => 'open-ended',
            'ai_scoring_weight' => 1.2
        ];
        
        $fields[] = [
            'field_type' => 'date',
            'field_label' => 'En erken ne zaman başlayabilirsiniz?',
            'field_name' => 'start_date',
            'field_placeholder' => 'GG/AA/YYYY',
            'field_options' => null,
            'is_required' => true,
            'field_category' => 'personal',
            'ai_scoring_weight' => 0.6
        ];
        
        return [
            'success' => true,
            'fields' => $fields,
            'demo_mode' => true
        ];
    }
    
    /**
     * Demo mode için aday değerlendirmesi
     */
    private function getDemoEvaluation($jobTitle) {
        $score = rand(65, 95);
        
        return [
            'success' => true,
            'score' => $score,
            'strengths' => '✅ Güçlü teknik bilgi birikimi
✅ İyi iletişim becerileri
✅ Pozisyonla ilgili deneyim',
            'weaknesses' => '⚠️ Bazı modern framework\'lerde daha fazla deneyim gerekebilir
⚠️ Liderlik deneyimi sınırlı',
            'summary' => "Aday {$jobTitle} pozisyonu için genel olarak uygun görünüyor. Teknik yetkinlikleri ve deneyimi pozisyon gereksinimleriyle örtüşüyor. Takım çalışmasına yatkın ve öğrenmeye açık bir profil.",
            'details' => [
                'technical_match' => min(100, $score + rand(-5, 10)),
                'experience_match' => min(100, $score + rand(-10, 5)),
                'education_match' => min(100, $score + rand(-5, 5)),
                'soft_skills' => min(100, $score + rand(-8, 8)),
                'culture_fit' => min(100, $score + rand(-10, 10))
            ],
            'demo_mode' => true
        ];
    }
    
    /**
     * AI Chat Response - Get response for user messages
     */
    public function getChatResponse($message) {
        // Demo mode kontrolü
        if (AI_DEMO_MODE) {
            return $this->getDemoChatResponse($message);
        }
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Sen bir İK uzmanı AI asistanısın. İşverenlere işe alım süreçlerinde yardımcı oluyorsun. Türkçe, samimi ve profesyonel bir dille konuş. Kısa ve öz cevaplar ver.'
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 500
        ];
        
        $response = $this->makeRequest('/chat/completions', $data);
        
        if (!$response['success']) {
            throw new Exception('AI yanıt verirken hata oluştu: ' . ($response['error'] ?? 'Bilinmeyen hata'));
        }
        
        return $response['data']['choices'][0]['message']['content'] ?? 'Üzgünüm, yanıt oluşturamadım.';
    }
    
    /**
     * Demo mode chat response with advanced conversation tracking
     */
    private function getDemoChatResponse($message) {
        $message = strtolower(trim($message));
        $originalMessage = $message;
        
        // Session-based conversation tracking
        if (!isset($_SESSION['ai_conversation_count'])) {
            $_SESSION['ai_conversation_count'] = 0;
        }
        if (!isset($_SESSION['ai_last_topic'])) {
            $_SESSION['ai_last_topic'] = null;
        }
        if (!isset($_SESSION['ai_previous_responses'])) {
            $_SESSION['ai_previous_responses'] = [];
        }
        $_SESSION['ai_conversation_count']++;
        
        $conversationCount = $_SESSION['ai_conversation_count'];
        $lastTopic = $_SESSION['ai_last_topic'];
        
        // Advanced keyword detection with scoring
        $keywords = [
            'aday' => ['aday', 'adaylar', 'başvuran', 'cv', 'özgeçmiş', 'profil'],
            'ilan' => ['ilan', 'iş ilanı', 'pozisyon', 'açık', 'job'],
            'başvuru' => ['başvuru', 'application', 'başvuran'],
            'analiz' => ['analiz', 'rapor', 'istatistik', 'data', 'metrik'],
            'öneri' => ['öneri', 'tavsiye', 'ne yapmalı', 'nasıl', 'yardım'],
            'ai' => ['ai', 'yapay zeka', 'otomasyon', 'akıllı'],
            'hızlı' => ['hızlı', 'acil', 'öncelik', 'urgent'],
            'kalite' => ['kalite', 'iyi', 'en iyi', 'kaliteli', 'mükemmel'],
            'süreç' => ['süreç', 'işlem', 'adım', 'flow'],
            'maliyet' => ['maliyet', 'fiyat', 'ücret', 'bütçe', 'cost']
        ];
        
        $detectedTopics = [];
        foreach ($keywords as $topic => $words) {
            foreach ($words as $word) {
                if (strpos($message, $word) !== false) {
                    $detectedTopics[] = $topic;
                    break;
                }
            }
        }
        
        // Greeting detection
        if (preg_match('/\b(merhaba|selam|hey|hi|hello|günaydın|iyi günler)\b/', $message)) {
            $greetings = [
                "👋 Merhaba! Ben AI asistanınızım. " . ($conversationCount > 1 ? "Tekrar hoş geldiniz! " : "") . "İşe alım sürecinizle ilgili nasıl yardımcı olabilirim?\n\n💡 Deneyebileceğiniz sorular:\n• \"En iyi 3 adayı göster\"\n• \"Bu hafta kaç başvuru var?\"\n• \"İlan performansı nasıl?\"\n• \"Bana strateji öner\"",
                "Merhaba! 🎯 Size özel AI asistan hazır. " . ($conversationCount > 1 ? "Görüşmemiz devam ediyor, " : "") . "size nasıl destek olabilirim?\n\n📊 Hızlı erişim:\n• Dashboard analizleri\n• Aday değerlendirme\n• İlan optimizasyonu\n• Stratejik öneriler",
                "Hey! 🚀 AI işe alım asistanınız burada. " . ($conversationCount == 1 ? "İlk görüşmemiz, hoş geldiniz!" : "Devam edelim!") . " Ne üzerinde çalışalım?\n\n⚡ Popüler: Aday analizi | İlan oluşturma | Performans raporları"
            ];
            $_SESSION['ai_last_topic'] = 'greeting';
            $response = $greetings[array_rand($greetings)];
            $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
            return $response;
        }
        
        // Multi-topic smart responses
        if (in_array('aday', $detectedTopics)) {
            $_SESSION['ai_last_topic'] = 'aday';
            
            if (in_array('kalite', $detectedTopics) || strpos($message, 'en iyi') !== false) {
                $responses = [
                    "⭐ **En Kaliteli Adayları Bulmak:**\n\n**AI Filtreleme Sistemi:**\n• Minimum AI skoru: 85+ (mükemmel eşleşme)\n• Deneyim uyumu: %90+ (pozisyon gereksinimleri)\n• Beceri matrisi: Tüm kritik beceriler var mı?\n\n**Şu An Mevcut:**\n✨ " . rand(2, 5) . " aday → AI skoru 90+\n🎯 " . rand(3, 8) . " aday → AI skoru 85-89\n📊 " . rand(5, 12) . " aday → AI skoru 75-84\n\n**Aksiyon:** En üstteki " . rand(2, 4) . " adayla bu hafta görüşmenizi öneriyorum!\n\nDetaylı profilleri görmek ister misiniz?",
                    "🎯 **Kaliteli Aday Bulma Stratejisi:**\n\n**1. AI Önceliklendirme**\n→ Skorlama: Otomatik 0-100 puan\n→ Eşleşme: Pozisyon gereksinimleriyle karşılaştırma\n→ Tahmin: Başarı olasılığı hesaplama\n\n**2. Kritik Faktörler**\n✅ Teknik beceriler (%40 ağırlık)\n✅ Deneyim seviyesi (%30 ağırlık)\n✅ Eğitim uyumu (%15 ağırlık)\n✅ Soft skills (%15 ağırlık)\n\n**Bugünkü Top 3:**\n🥇 " . ['Ahmet K.', 'Ayşe M.', 'Mehmet T.', 'Zeynep A.'][rand(0,3)] . " - Skor: " . rand(92, 98) . "\n🥈 " . ['Fatma S.', 'Ali Y.', 'Elif B.', 'Can D.'][rand(0,3)] . " - Skor: " . rand(88, 91) . "\n🥉 " . ['Deniz L.', 'Emre K.', 'Selin P.', 'Burak N.'][rand(0,3)] . " - Skor: " . rand(85, 87) . "\n\nHangisiyle başlayalım?",
                    "💎 **Premium Aday Seçim Rehberi:**\n\n**AI Değerlendirme Metrikleri:**\n📍 Pozisyon Uyumu → " . rand(85, 95) . "% ortalama\n🧠 Yetenek Uyumu → " . rand(80, 92) . "% ortalama\n⚡ Hızlı Adaptasyon → " . rand(75, 88) . "% olasılık\n🎯 Uzun Dönem Başarı → " . rand(70, 90) . "% tahmin\n\n**Filtreleme İpuçları:**\n1. İlk olarak 85+ skorlu adaylara bakın\n2. Kritik becerilerin hepsine sahip olanları öncelikleyin\n3. Son 3 yıldaki deneyime odaklanın\n4. Referans skorlarına göz atın\n\n💡 **Pro Tip:** İlk 48 saat içinde yanıt veren adaylar %67 daha yüksek kabul oranına sahip!\n\nFiltreli listeyi oluşturayım mı?"
                ];
                $response = $responses[array_rand($responses)];
                $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
                return $response;
            }
            
            if (in_array('hızlı', $detectedTopics)) {
                $response = "⚡ **Hızlı Aday Değerlendirme:**\n\n**30 Saniyede Değerlendirme:**\n1️⃣ AI skoruna bakın (85+ = yeşil ışık)\n2️⃣ Kritik 3 beceriyi kontrol edin\n3️⃣ Deneyim yılına göz atın\n4️⃣ Konum uyumu var mı?\n\n**Şu An Bekleyenler:**\n🔴 Acil: " . rand(2, 5) . " aday (24 saat geçti)\n🟡 Önemli: " . rand(3, 8) . " aday (12 saat geçti)\n🟢 Normal: " . rand(5, 15) . " aday (yeni)\n\n**Hızlı Aksiyon:**\n→ Acil olanları şimdi inceleyin\n→ Ön eleme yapın (2 dk/aday)\n→ Shortlist'e ekleyin\n→ Görüşme daveti gönderin\n\n⏱️ Tahmini süre: " . rand(15, 30) . " dakika\n\nHemen başlayalım mı?";
                $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
                return $response;
            }
            
            $responses = [
                "👥 **Aday Yönetimi Araçlarınız:**\n\n**Akıllı Özellikler:**\n• 🤖 Otomatik AI skorlama (her aday 0-100 puan alır)\n• 🎯 Pozisyon eşleştirme (hangi ilanla ne kadar uyumlu)\n• 📊 Karşılaştırmalı analiz (adayları yan yana koyun)\n• 💡 AI önerileri (hangi adayla görüşmelisiniz)\n\n**Filtreler:**\n✓ AI skoru aralığı → 85+ öneririm\n✓ Deneyim yılı → Pozisyona göre\n✓ Beceri eşleşmesi → %80+ ideal\n✓ Konum → Uzaktan/hibrit seçenekleri\n\n**Son 7 Gün:**\n📈 " . rand(25, 45) . " yeni başvuru\n⭐ " . rand(8, 15) . " yüksek skorlu\n🎯 " . rand(3, 7) . " mükemmel eşleşme\n\nDetaylı filtreleme yapalım mı?",
                "🔍 **Aday Havuzu Analizi:**\n\n**Genel Durum:**\n→ Toplam aktif aday: " . rand(50, 150) . "\n→ Bu ay yeniler: " . rand(20, 60) . "\n→ İncelenmedi: " . rand(5, 20) . "\n→ Shortlist'te: " . rand(8, 25) . "\n\n**Kalite Dağılımı:**\n🌟 Mükemmel (90-100): " . rand(5, 15) . "%\n⭐ Çok İyi (80-89): " . rand(20, 35) . "%\n✨ İyi (70-79): " . rand(30, 45) . "%\n💫 Orta (60-69): " . rand(15, 25) . "%\n\n**AI Önerisi:**\nEn üst %15'lik dilime odaklanın. Bu size " . rand(8, 20) . " aday demek.\n\n**Hızlı Erişim:**\n• Bekleyen incelemeleri tamamla\n• En yeni başvuruları gözden geçir\n• Shortlist güncellemesi yap\n\nHangi grubu inceleyelim?",
                "📋 **Aday Pipeline Yönetimi:**\n\n**Aşamalar:**\n\n1️⃣ **Yeni Başvurular**\n→ " . rand(10, 25) . " aday bekliyor\n→ AI otomatik ön eleme yapıldı\n→ %65'i minimum kriterleri karşılıyor\n\n2️⃣ **İnceleme Aşaması**\n→ " . rand(8, 18) . " aday değerlendiriliyor\n→ Ortalama skor: " . rand(72, 85) . "\n→ " . rand(3, 8) . " aday öne çıkıyor\n\n3️⃣ **Görüşme Hazır**\n→ " . rand(5, 12) . " aday shortlist'te\n→ Görüşme planı yapılabilir\n→ Ortalama uyum: %87\n\n4️⃣ **Son Aşama**\n→ " . rand(2, 5) . " finalist mevcut\n→ Karar aşamasında\n\n**Sonraki Adım:** Hangi aşamayı detaylandırayım?",
                "🎓 **Aday Segmentasyonu:**\n\n**Deneyim Seviyesi:**\n• 🔰 Junior (0-2 yıl): " . rand(25, 40) . "%\n• 🎯 Mid-level (3-5 yıl): " . rand(30, 45) . "%\n• 🏆 Senior (5+ yıl): " . rand(20, 35) . "%\n\n**Lokasyon Tercihi:**\n• 🏢 Ofis: " . rand(20, 35) . "%\n• 🏠 Uzaktan: " . rand(30, 50) . "%\n• 🔄 Hibrit: " . rand(25, 40) . "%\n\n**Beceri Profilleri:**\n• 💻 Teknik odaklı: " . rand(40, 60) . "%\n• 👥 Yönetim becerileri: " . rand(15, 30) . "%\n• 🎨 Kreatif: " . rand(10, 25) . "%\n• 📊 Analitik: " . rand(20, 35) . "%\n\n**Özel Filtre Oluşturalım mı?**\nÖrnek: \"Senior, uzaktan, Python uzmanlığı olan adaylar\""
            ];
            $response = $responses[array_rand($responses)];
            $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
            return $response;
        }
        
        if (in_array('ilan', $detectedTopics)) {
            $_SESSION['ai_last_topic'] = 'ilan';
            
            if (strpos($message, 'oluştur') !== false || strpos($message, 'yeni') !== false || strpos($message, 'ekle') !== false) {
                $responses = [
                    "✨ **AI Destekli İlan Oluşturma Wizard:**\n\n**🎯 Hızlı Başlangıç (5 dakika):**\n\n**Adım 1: Temel Bilgiler**\n→ Pozisyon adı (ör: Senior React Developer)\n→ Departman ve takım bilgisi\n→ Lokasyon ve uzaktan çalışma seçenekleri\n\n**Adım 2: AI Sihri** ✨\n→ AI açıklamayı optimize eder\n→ Gerekli becerileri önerir\n→ Maaş bandını analiz eder\n→ Form sorularını oluşturur\n\n**Adım 3: Özelleştir**\n→ AI'nın önerilerini gözden geçir\n→ Şirket kültürünü ekle\n→ Benefitleri listele\n\n**Adım 4: Yayınla** 🚀\n→ Otomatik SEO optimizasyonu\n→ Multi-platform paylaşım\n→ AI aday eşleştirme başlat\n\n**Bonus:** AI destekli ilanlar %47 daha fazla kaliteli başvuru çekiyor!\n\n📝 Hemen başlayalım mı?",
                    "🚀 **Yeni İş İlanı Oluştur - Pro Mod:**\n\n**Şablon Seç:**\n1️⃣ Hızlı Şablon → 3 dakikada hazır\n2️⃣ AI Destekli → Tam optimizasyon\n3️⃣ Özel Şablon → Sıfırdan oluştur\n\n**AI Yardımcınız Hazırlayacak:**\n✅ Çekici iş tanımı (SEO optimize)\n✅ Gereksinim listesi (önceliklendirilmiş)\n✅ Dinamik form alanları (pozisyona özel)\n✅ Ön eleme soruları (akıllı filtreleme)\n✅ Değerlendirme kriterleri (otomatik puanlama)\n\n**Önerilen Ek Özellikler:**\n• 🎥 Video tanıtım ekle → %35 daha fazla başvuru\n• 💰 Maaş bandı belirt → %2x başvuru\n• 🌍 Uzaktan seçenek → %58 daha geniş havuz\n• ⚡ Hızlı başvuru → %40 daha yüksek tamamlanma\n\n**Mevcut İlanlarınızdan Kopyala:**\nBaşarılı ilanlarınızı şablon olarak kullanabilirsiniz.\n\nHangi yolla ilerleyelim?",
                    "📝 **İlan Oluşturma - Adım Adım:**\n\n**1. Pozisyon Analizi** (AI yapıyor)\n• Piyasa araştırması\n• Benzer ilanları analiz et\n• Beceri trendlerini tespit et\n• Maaş aralığı öner\n\n**2. Açıklama Oluştur** (AI yardımıyla)\n• Çekici başlık oluştur\n• Rol ve sorumluluklar\n• Gereksinimler (must-have vs nice-to-have)\n• Şirket kültürü ve değerler\n\n**3. Akıllı Form Tasarla** (Tam otomatik)\n• Pozisyona özel sorular\n• Beceri değerlendirme\n• Deneyim kontrolü\n• Portfolio/örnek proje isteme\n\n**4. Yayınlama Stratejisi**\n• Job boards seçimi\n• Social media planı\n• Timing optimizasyonu\n• Target audience belirleme\n\n**⚡ Hızlı Başlangıç:**\nSadece pozisyon adını söyleyin, gerisini AI halleder!\n\nÖrnek: \"Senior Frontend Developer, React, Remote\" yazsanız yeterli.\n\nPozisyonu söyler misiniz?"
                ];
                $response = $responses[array_rand($responses)];
                $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
                return $response;
            }
            
            if (in_array('analiz', $detectedTopics) || strpos($message, 'performans') !== false) {
                $response = "📊 **İlan Performans Analizi:**\n\n**En İyi Performans:**\n🏆 #1: " . ['Senior Developer', 'Marketing Manager', 'Product Owner', 'UX Designer'][rand(0,3)] . "\n→ Görüntüleme: " . rand(450, 850) . "\n→ Başvuru: " . rand(45, 95) . "\n→ Dönüşüm: %" . rand(8, 15) . "\n→ Kalite skoru: " . rand(85, 95) . "/100\n\n**İyileştirme Gereken:**\n⚠️ " . ['Junior Developer', 'Sales Rep', 'Customer Support', 'Content Writer'][rand(0,3)] . "\n→ Görüntüleme: " . rand(150, 300) . "\n→ Başvuru: " . rand(8, 20) . "\n→ Problem: Başlık optimize edilmeli\n\n**AI Önerileri:**\n1. Düşük performanslı ilanda maaş bandı ekleyin → %2x başvuru\n2. 'Uzaktan çalışma' vurgusunu artırın → %40+ görüntüleme\n3. Gereksinimler listesini kısaltın → %25+ tamamlanma\n\n**Benchmark:**\nOrtalama dönüşüm oranı: %12\nSizin ortalamanız: %" . rand(8, 14) . "\n\n💡 Detaylı analiz raporu oluşturayım mı?";
                $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
                return $response;
            }
            
            $responses = [
                "📢 **İlan Yönetim Merkezi:**\n\n**Aktif İlanlarınız:**\n✅ " . rand(3, 8) . " ilan yayında\n📊 " . rand(2, 5) . " ilan iyi performans gösteriyor\n⚠️ " . rand(0, 2) . " ilan optimizasyon gerekiyor\n\n**Bu Hafta:**\n• Toplam görüntüleme: " . rand(850, 2500) . "\n• Toplam başvuru: " . rand(45, 120) . "\n• Ortalama dönüşüm: %" . rand(8, 15) . "\n\n**Hızlı Aksiyonlar:**\n• 📝 Yeni ilan oluştur\n• 🔧 Mevcut ilanı düzenle\n• 📊 Performans raporu al\n• 🎯 SEO optimizasyonu yap\n\nNe yapmak istersiniz?",
                "🎯 **İlan Stratejisi Dashboard:**\n\n**Durum Özeti:**\n→ Aktif: " . rand(3, 10) . " ilan\n→ Taslak: " . rand(0, 3) . " ilan\n→ Arşiv: " . rand(5, 15) . " ilan\n\n**Performans Metrikleri:**\n📈 Görüntüleme trendi: +" . rand(15, 45) . "%\n🎯 Başvuru kalitesi: " . rand(75, 90) . "/100\n⚡ Ortalama dolum süresi: " . rand(12, 28) . " gün\n💰 Başvuru başına maliyet: $" . rand(5, 15) . "\n\n**AI Insight:**\n" . ['Pazartesi sabahları %35 daha fazla başvuru alıyorsunuz', 'Remote pozisyonlarınız 2x daha fazla görüntüleniyor', 'Senior seviye ilanlarınızda dönüşüm oranı yüksek', 'Başlıklarınızda action word kullanımı etkili'][rand(0,3)] . "\n\nStratejinizi iyileştirmek için öneriler verebilirim!"
            ];
            $response = $responses[array_rand($responses)];
            $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
            return $response;
        }
        
        if (in_array('başvuru', $detectedTopics)) {
            $_SESSION['ai_last_topic'] = 'başvuru';
            
            $responses = [
                "📬 **Başvuru Yönetim Sistemi:**\n\n**Bekleyen İncelemeler:**\n🔴 Acil (" . rand(2, 6) . " aday) → 24+ saat geçti\n🟡 Önemli (" . rand(5, 12) . " aday) → 12-24 saat\n🟢 Yeni (" . rand(8, 20) . " aday) → Son 12 saat\n\n**AI Otomatik İşlemler:**\n✅ " . rand(15, 30) . " başvuru otomatik skorlandı\n✅ " . rand(8, 15) . " CV parse edildi\n✅ " . rand(5, 10) . " aday ön elemeyi geçti\n✅ " . rand(2, 5) . " yüksek öncelikli işaretlendi\n\n**Hızlı Erişim:**\n• 👀 Tüm yeni başvuruları görüntüle\n• ⭐ Yüksek skorluları filtrele (85+)\n• 📋 Pozisyona göre grupla\n• 📊 Detaylı analiz raporu\n\n**Aksiyon Gerekli:**\nAcil kategorideki adayları öncelikle inceleyin → Yanıt süresi başarıyı %45 etkiliyor!\n\nBaşlayalım mı?",
                "🎯 **Başvuru Analytics Dashboard:**\n\n**Bugün:**\n📨 " . rand(5, 15) . " yeni başvuru\n⭐ " . rand(2, 6) . " yüksek skorlu\n🎯 " . rand(1, 3) . " mükemmel eşleşme\n\n**Bu Hafta:**\n📊 Toplam: " . rand(25, 65) . " başvuru\n📈 Trend: +" . rand(15, 35) . "% (geçen haftaya göre)\n🎓 Kalite ortalaması: " . rand(70, 85) . "/100\n⚡ Hızlı başvuru oranı: %" . rand(65, 85) . "\n\n**Kaynak Analizi:**\n• LinkedIn: " . rand(35, 55) . "%\n• Direkt: " . rand(20, 35) . "%\n• Indeed: " . rand(10, 25) . "%\n• Referans: " . rand(5, 15) . "%\n\n**AI Tahmin:**\nBu tempo devam ederse bu ay " . rand(80, 180) . "+ başvuru alacaksınız.\n\nDetaylı conversion funnel görmek ister misiniz?",
                "🔍 **Başvuru Kalite Analizi:**\n\n**Skor Dağılımı:**\n🌟 90-100 (Mükemmel): " . rand(5, 12) . "%\n⭐ 80-89 (Çok İyi): " . rand(15, 25) . "%\n✨ 70-79 (İyi): " . rand(25, 40) . "%\n💫 60-69 (Orta): " . rand(20, 30) . "%\n⚪ <60 (Düşük): " . rand(10, 20) . "%\n\n**Eşleşme Metrikleri:**\n• Teknik beceri uyumu: %" . rand(75, 90) . "\n• Deneyim uyumu: %" . rand(70, 88) . "\n• Eğitim uyumu: %" . rand(80, 95) . "\n• Lokasyon uyumu: %" . rand(85, 98) . "\n\n**İnceleme Durumu:**\n✅ İncelendi: " . rand(40, 70) . "%\n⏳ Bekliyor: " . rand(15, 35) . "%\n📋 Shortlist: " . rand(8, 18) . "%\n❌ Reddedildi: " . rand(10, 25) . "%\n\n**Önerilen Aksiyon:**\nBekleyen başvuruların %60'ı yüksek skorlu → Öncelikle bunları inceleyin!\n\nFiltrelenmiş liste oluşturayım mı?"
            ];
            $response = $responses[array_rand($responses)];
            $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
            return $response;
        }
        
        if (in_array('analiz', $detectedTopics)) {
            $_SESSION['ai_last_topic'] = 'analiz';
            
            $responses = [
                "📊 **Haftalık AI Performans Raporu:**\n\n**İşe Alım Metrikleri:**\n• Ortalama dolum süresi: " . rand(15, 28) . " gün\n• Başvuru kalitesi: ↗️ +" . rand(12, 25) . "%\n• Görüşme/başvuru oranı: %" . rand(15, 30) . "\n• Teklif/görüşme oranı: %" . rand(35, 55) . "\n• Kabul oranı: %" . rand(75, 90) . "\n\n**AI Etkisi:**\n✨ AI ön eleme → %" . rand(45, 65) . " zaman tasarrufu\n🎯 Otomatik puanlama → %" . rand(80, 95) . " doğruluk\n📊 Tahmin başarısı → %" . rand(75, 88) . " isabetli\n⚡ Süreç hızlandırma → " . rand(30, 50) . "% daha hızlı\n\n**Trend Analizi:**\n📈 En yüksek: " . ['Pazartesi sabahı', 'Perşembe öğleden sonra', 'Salı öğlen', 'Cuma sabahı'][rand(0,3)] . " başvuruları\n🎯 En kaliteli: " . ['Referans', 'LinkedIn', 'Direkt başvuru'][rand(0,2)] . " kaynaklı adaylar\n💼 En hızlı dolum: " . ['Senior', 'Mid-level', 'Teknik'][rand(0,2)] . " pozisyonlar\n\n**Öneriler:**\n→ " . ['Posting zamanını optimize edin', 'Başvuru formunu kısaltın', 'Video tanıtım ekleyin'][rand(0,2)] . "\n\nDetaylı PDF rapor oluşturayım mı?",
                "🎯 **AI Analytics Deep Dive:**\n\n**Funnel Analizi:**\n1️⃣ İlan Görüntüleme: " . rand(1200, 3500) . "\n2️⃣ Başvuru Başlatma: " . rand(180, 450) . " (%" . rand(12, 18) . ")\n3️⃣ Başvuru Tamamlama: " . rand(120, 350) . " (%" . rand(65, 85) . ")\n4️⃣ AI Ön Eleme Geçer: " . rand(80, 250) . " (%" . rand(60, 75) . ")\n5️⃣ Manuel İnceleme: " . rand(40, 120) . " (%" . rand(45, 65) . ")\n6️⃣ Görüşme: " . rand(15, 50) . " (%" . rand(30, 50) . ")\n7️⃣ Teklif: " . rand(5, 20) . " (%" . rand(35, 55) . ")\n8️⃣ İşe Alım: " . rand(2, 10) . " (%" . rand(70, 90) . ")\n\n**Drop-off Analizi:**\n⚠️ En yüksek kayıp: " . ['Başvuru başlatma → tamamlama', 'İnceleme → görüşme', 'Teklif → kabul'][rand(0,2)] . "\n💡 İyileştirme potansiyeli: %" . rand(25, 45) . "\n\n**ROI Hesaplaması:**\n💰 Yatırım: $" . rand(500, 1500) . "/ay\n🎯 Kazanç: $" . rand(2000, 8000) . "/ay\n📈 ROI: %" . rand(200, 500) . "\n\nİyileştirme stratejisi hazırlayalım mı?"
            ];
            $response = $responses[array_rand($responses)];
            $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
            return $response;
        }
        
        if (in_array('öneri', $detectedTopics)) {
            $_SESSION['ai_last_topic'] = 'öneri';
            
            $dynamicSuggestions = [
                "💡 **Bugün İçin Kişiselleştirilmiş Öneriler:**\n\n**Acil Aksiyonlar:**\n1. 🚨 " . rand(2, 6) . " bekleyen başvuru var → Bugün yanıtlayın!\n2. ⭐ AI skoru 92+ olan " . rand(1, 3) . " adayla görüşme planlayın\n3. 📊 '" . ['Frontend Dev', 'Marketing Manager', 'Product Designer'][rand(0,2)] . "' ilanınız düşük performansta → Başlığı güncelleyin\n\n**Bu Hafta İçin:**\n• 📝 " . rand(1, 2) . " yeni pozisyon için ilan oluşturun\n• 🎯 Shortlist'teki " . rand(3, 8) . " adayı final aşamasına taşıyın\n• 📊 AI analytics raporunu inceleyin\n• 💬 En az " . rand(5, 12) . " adayla ön görüşme yapın\n\n**Strateji Önerileri:**\n→ Remote pozisyonlara %40+ başvuru geliyor → Daha fazla remote ilan açın\n→ Pazartesi postları en etkili → Yeni ilanları Pzt sabahı yayınlayın\n→ Video tanıtım ekleyin → %35+ engagement\n\n**Tahmin:** Bu önerileri uygularsanız " . rand(3, 7) . " gün içinde " . rand(15, 30) . "+ kaliteli başvuru bekliyorum!\n\nHangi öneriyle başlayalım?",
                "🎯 **AI-Powered Strateji Paketi:**\n\n**Hızlı Kazançlar (1-3 gün):**\n\n**1. İlan Optimizasyonu**\n→ Problem: '" . ['Senior Developer', 'Sales Manager', 'UX Lead'][rand(0,2)] . "' ilanı düşük dönüşüm\n→ Çözüm: Başlığa 'Remote + High Salary' ekleyin\n→ Beklenen etki: %2x başvuru\n\n**2. Aday Takibi**\n→ Problem: " . rand(8, 15) . " yüksek skorlu aday yanıt bekliyor\n→ Çözüm: Bugün kişisel mesaj gönderin\n→ Beklenen etki: %70 yanıt oranı\n\n**3. Süreç İyileştirme**\n→ Problem: Ortalama yanıt süresi " . rand(48, 96) . " saat\n→ Çözüm: Otomatik email templateleri kullanın\n→ Beklenen etki: " . rand(30, 50) . "% hız artışı\n\n**Orta Vadeli (1-2 hafta):**\n• AI form alanları tüm ilanlara ekleyin\n• Referans programı başlatın\n• Video JD'ler hazırlayın\n\n**Uzun Vadeli (1 ay):**\n• Employer branding stratejisi\n• Talent pipeline oluşturma\n• Predictive analytics kullanımı\n\nAdım adım başlayalım mı?",
                "⚡ **Performans Booster Plan:**\n\n**Şu An Yapın (5 dakika):**\n✅ Bekleyen " . rand(3, 8) . " başvuruya hızlı yanıt\n✅ En iyi 3 adayı shortlist'e ekleyin\n✅ Düşük skorlu başvuruları otomatik reddedin\n\n**Bugün Yapın (30 dakika):**\n📝 1 yeni pozisyon için AI ile ilan oluşturun\n📊 Haftalık performans raporunu inceleyin\n💬 " . rand(2, 5) . " high-potential adayla iletişim kurun\n\n**Bu Hafta Yapın:**\n🎯 " . rand(5, 10) . " görüşme planlayın\n🔧 " . rand(2, 4) . " ilanı optimize edin\n📈 Conversion rate'leri analiz edin\n🎥 " . rand(1, 2) . " video JD hazırlayın\n\n**Öncelikli Focus Alanlar:**\n1. " . ['Yanıt süresi', 'Başvuru kalitesi', 'İlan görünürlüğü'][rand(0,2)] . " → En kritik metrik\n2. " . ['Aday deneyimi', 'Process speed', 'Communication'][rand(0,2)] . " → İyileştirme gerekli\n3. " . ['Employer brand', 'Referral rate', 'Offer acceptance'][rand(0,2)] . " → Güçlü taraf\n\n💪 **Motivasyon:** Sektör ortalamasının %" . rand(20, 45) . " üzerindesiniz!\n\nDetaylı action plan ister misiniz?"
            ];
            $response = $dynamicSuggestions[array_rand($dynamicSuggestions)];
            $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
            return $response;
            $suggestions = [
                "💡 **Bugün İçin AI Önerileri:**\n\n1. 🎯 3 yeni başvurunuz var - hemen inceleyin!\n2. ⭐ 2 adayın AI skoru 90+, görüşme ayarlayın\n3. 📊 'Frontend Developer' ilanınız %40 daha fazla görüntüleniyor\n4. 🚀 AI form alanları ekleyerek kaliteli başvuru oranını artırın\n\n**Hızlı Aksiyonlar:**\n• En iyi 3 adayı favorilere ekleyin\n• Düşük performanslı ilanları güncelleyin\n• AI'nın önerdiği form sorularını ekleyin\n\nHangi öneriyle başlamak istersiniz?",
                "🎯 **Stratejik İyileştirme Önerileri:**\n\n**İlan Optimizasyonu:**\n• Başlıklarda spesifik olun (ör: 'Developer' yerine 'Senior React Developer')\n• AI form alanları kullanın (%47 daha kaliteli başvuru)\n• Maaş aralığı belirtin (2x daha fazla başvuru)\n\n**Aday Yönetimi:**\n• AI skoru 85+ adaylarla öncelikli görüşün\n• İlk 48 saatte yanıt verin (3x daha yüksek kabul oranı)\n• Otomatik bildirimler açık olsun\n\n**Performans Artışı:**\n📈 Bu önerileri uygulayan işverenler %65 daha hızlı işe alım yapıyor!\n\nDetaylı strateji planı ister misiniz?",
                "⚡ **Hızlı Kazanç Önerileri:**\n\n**Şu An Yapabilecekleriniz:**\n1. ✅ Bekleyen " . rand(3, 8) . " başvuruya yanıt verin\n2. 🔥 AI skoru 90+ olan " . rand(1, 3) . " adayı görüşmeye çağırın\n3. 📝 Taslak durumundaki ilanı yayına alın\n4. 🎨 AI'dan yeni ilan formu oluşturmasını isteyin\n\n**Bu Hafta İçin:**\n• LinkedIn'de ilişkilerinizi paylaşın\n• Mevcut ilanları social media'da tanıtın\n• AI analytics raporunu inceleyin\n\n⏱️ **Tahmin:** Bu aksiyonları alarak 3-5 gün içinde " . rand(10, 20) . "+ başvuru alabilirsiniz!\n\nHangisiyle başlayalım?"
            ];
            return $suggestions[array_rand($suggestions)];
        }
        
        // Context-aware follow-up responses based on conversation history
        if ($conversationCount > 1 && $lastTopic) {
            $followUps = [
                'aday' => ["Aday konusunda devam edelim! Spesifik olarak neyi merak ediyorsunuz: filtreleme, değerlendirme, yoksa karşılaştırma mı?", "Aday yönetimi hakkında başka sorularınız var mı? Scoring sistemi, pipeline yönetimi veya segmentasyon konularında yardımcı olabilirim.", "Hangi aday grubuna odaklanmak istersiniz? Yeni başvurular, high-performers, veya bekleyenler mi?"],
                'ilan' => ["İlan konusunda size daha fazla yardımcı olabilirim. Yeni ilan mı oluşturmak istiyorsunuz, yoksa mevcut ilanları mı optimize edelim?", "İlan stratejinizi geliştirmek için SEO, timing, veya content konularında destek verebilirim.", "Hangi ilan türüyle ilgileniyorsunuz? Teknik pozisyonlar, yönetim rolleri, yoksa entry-level mi?"],
                'başvuru' => ["Başvurular hakkında başka ne öğrenmek istersiniz? Analiz, filtreleme veya süreç iyileştirme konusunda yardım edebilirim.", "Başvuru sürecinizin hangi aşamasına odaklanmak istersiniz?", "Conversion funnel, kaynak analizi veya kalite metrikleri hakkında detay verebilirim."],
                'analiz' => ["Analitik verilerinize derinlemesine bakalım. Hangi metriklere odaklanmak istersiniz?", "Performance tracking, trend analysis veya predictive modeling hakkında daha fazla bilgi istiyorsanız söyleyin.", "Hangi zaman aralığını analiz etmek istersiniz? Günlük, haftalık veya aylık?"]
            ];
            if (isset($followUps[$lastTopic]) && rand(0, 3) == 0) {
                $response = $followUps[$lastTopic][array_rand($followUps[$lastTopic])];
                $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
                return $response;
            }
        }
        
        // Smart contextual default responses with variety
        $contextualResponses = [
            "🤖 **AI İşe Alım Asistanınız Aktif!**\n\n" . ($conversationCount == 1 ? "İlk kez görüşüyoruz, hoş geldiniz! 🎉\n\n" : "Görüşmemize devam ediyoruz! 💪\n\n") . "**Popüler Talepler:**\n• 🌟 \"En iyi 5 adayı göster\"\n• 📝 \"Yeni ilan oluştur\"\n• 📊 \"Bu hafta ne kadar başvuru var?\"\n• 💡 \"Bana strateji öner\"\n• 🎯 \"Hangi ilanım en iyi performans gösteriyor?\"\n\n**AI Güçlü Yönlerim:**\n→ Aday değerlendirme ve skorlama\n→ İlan optimizasyonu ve oluşturma\n→ Performans analizi ve tahminler\n→ Kişiselleştirilmiş stratejik öneriler\n\nDoğal dille konuşun, anlıyorum! 😊",
            "👋 **" . ($conversationCount == 1 ? "İlk Kez Mi?" : "Tekrar Merhaba!") . "**\n\n" . ($conversationCount >= 3 ? "Bu " . $conversationCount . ". sohbetimiz! Harika. 🎊\n\n" : "") . "**Hızlı Erişim Menüsü:**\n\n1️⃣ **Aday Yönetimi**\n   → En iyi adaylar, filtreleme, karşılaştırma\n\n2️⃣ **İlan Operasyonları**\n   → Yeni ilan, optimizasyon, performans\n\n3️⃣ **Analytics & Raporlar**\n   → Metrikler, trendler, tahminler\n\n4️⃣ **Strateji & Öneriler**\n   → AI insights, action plans, best practices\n\n**Örnek Sorular:**\n• \"Bekleyen başvuruları göster\"\n• \"AI skoruna göre sırala\"\n• \"Bu ay kaç teklif yaptık?\"\n• \"Hangi kaynak en kaliteli aday getiriyor?\"\n\nSorularınızı bekliyorum! 🚀",
            "💼 **AI Asistan Hazır & Beklemede!**\n\n**Güncel Durum Özeti:**\n📊 Dashboard'unuzda " . rand(15, 40) . "+ metrik takip ediliyor\n🤖 AI bu hafta " . rand(25, 60) . " işlem otomatik yaptı\n⭐ " . rand(5, 15) . " yüksek potansiyelli aday tespit edildi\n\n**Size Yardımcı Olabileceğim Konular:**\n\n**🎯 Operasyonel:**\nBaşvuru inceleme, aday filtreleme, ilan yönetimi\n\n**📊 Analitik:**\nPerformans metrikleri, trend analizi, tahminler\n\n**💡 Stratejik:**\nSüreç optimizasyonu, best practices, öneriler\n\n**🤖 Otomasyon:**\nAI skorlama, otomatik eleme, akıllı eşleştirme\n\n**İpucu:** Spesifik sorular sorun, daha iyi yardımcı olabilirim!\n\nÖrnek: \"Senior developer için en iyi 3 aday kimler?\"",
            "🎨 **AI İşe Alım Stüdyosu**\n\n" . ($conversationCount % 2 == 0 ? "Birlikte harika işler çıkaralım! 🚀\n\n" : "Ne üzerinde çalışalım? 🎯\n\n") . "**Bugünkü Öne Çıkanlar:**\n🔥 " . rand(2, 5) . " yeni mükemmel eşleşme\n📈 Başvuru kalitesi %" . rand(15, 35) . " arttı\n⚡ " . rand(3, 8) . " pozisyon aktif\n\n**AI Önerileri:**\n💡 " . ['Yeni bir remote pozisyon açın', 'LinkedIn postlarınızı artırın', 'Referans programı başlatın'][rand(0,2)] . "\n💡 " . ['Video JD ekleyin', 'Maaş bandını belirtin', 'Başvuru formunu kısaltın'][rand(0,2)] . "\n💡 " . ['Pazartesi sabahları post atın', 'High-performers ile görüşün', 'AI formları kullanın'][rand(0,2)] . "\n\n**Nasıl Yardımcı Olabilirim?**\nSorunuz veya talebiniz nedir? Ben tam bir AI asistan olarak her konuda size destek olabilirim! 🤖✨",
            "🌟 **İşe Alımınızı Süpercharge Edin!**\n\n**AI Gücünüz:**\n• ⚡ Otomatik aday skorlama → " . rand(80, 95) . "% doğruluk\n• 🎯 Akıllı eşleştirme → %" . rand(45, 65) . " zaman tasarrufu\n• 📊 Tahminsel analiz → %" . rand(75, 90) . " isabetlilik\n• 💡 Stratejik öneriler → 24/7 aktif\n\n**Son 7 Gün Başarılarınız:**\n✅ " . rand(20, 50) . " başvuru işlendi\n✅ " . rand(5, 15) . " kaliteli aday bulundu\n✅ " . rand(2, 8) . " görüşme planlandı\n✅ " . rand(1, 3) . " teklif gönderildi\n\n**Şimdi Ne Yapalım?**\nAday analizi | İlan oluşturma | Performance review | Strateji geliştirme\n\nSeçiminizi yapın veya doğrudan sorun! 💪",
            "🚀 **Welcome to Your AI Command Center**\n\n**Real-Time Status:**\n• Active jobs: " . rand(3, 10) . "\n• Pending applications: " . rand(5, 20) . "\n• High-score candidates: " . rand(2, 8) . "\n• Today's new applications: " . rand(1, 6) . "\n\n**AI Working For You:**\n🔄 Auto-screening " . rand(3, 12) . " applications\n🎯 Matching candidates to " . rand(2, 5) . " positions\n📊 Analyzing performance trends\n💡 Generating recommendations\n\n**Quick Actions:**\n• Review top candidates\n• Create new job posting\n• Check analytics dashboard\n• Get strategic advice\n\n**Your Turn!** Nasıl yardımcı olabilirim? 🎯"
        ];
        
        $response = $contextualResponses[array_rand($contextualResponses)];
        $_SESSION['ai_previous_responses'][] = substr($response, 0, 50);
        return $response;
    }

    /**
     * Simple chat method for AI conversations
     */
    public function chat($userMessage, $systemPrompt = '') {
        if (empty($systemPrompt)) {
            $systemPrompt = 'Sen yardımsever bir AI asistanısın. Türkçe cevap ver.';
        }
        
        // Demo mode kontrolü
        if (AI_DEMO_MODE || empty($this->apiKey)) {
            return [
                'success' => false,
                'demo_mode' => true,
                'message' => 'AI Demo Mode: Bu bir örnek yanıttır. Gerçek AI için API key yapılandırmanız gerekiyor.'
            ];
        }
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 500
        ];
        
        $startTime = microtime(true);
        $response = $this->makeRequest('/chat/completions', $data);
        $processingTime = microtime(true) - $startTime;
        
        if (!$response['success']) {
            return $response;
        }
        
        $content = $response['data']['choices'][0]['message']['content'] ?? '';
        
        return [
            'success' => true,
            'message' => $content,
            'processing_time' => $processingTime
        ];
    }
}
