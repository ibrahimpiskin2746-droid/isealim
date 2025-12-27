<?php
/**
 * Job Controller
 * Public job listings and application handling
 */

class JobController extends Controller {
    private $jobModel;
    private $applicationModel;
    private $aiService;
    private $notificationModel;
    
    public function __construct() {
        $this->jobModel = $this->model('Job');
        $this->applicationModel = $this->model('Application');
        $this->notificationModel = $this->model('Notification');
        $this->aiService = new AIService();
    }
    
    /**
     * Public job listings or job detail
     */
    public function index($jobId = null) {
        // Eğer jobId varsa, detay sayfasına yönlendir
        if ($jobId) {
            return $this->detail($jobId);
        }
        
        $page = get('page', 1);
        $filters = [
            'search' => get('search', ''),
            'location' => get('location', ''),
            'employment_type' => get('employment_type', ''),
            'experience_level' => get('experience_level', '')
        ];
        
        $jobs = $this->jobModel->getPublishedJobs($page, $filters);
        
        $this->view('job/index', [
            'title' => 'İş İlanları',
            'jobs' => $jobs,
            'filters' => $filters,
            'page' => $page
        ]);
    }
    
    /**
     * Job detail
     */
    public function detail($jobId) {
        // Demo ilan kontrolü
        if (strpos($jobId, 'demo-') === 0) {
            $job = $this->getDemoJob($jobId);
            if (!$job) {
                show404();
            }
            
            // Demo iş ilanı detaylarını ekle
            $details = $this->getDemoJobDetail($jobId);
            $job = array_merge($job, $details);
        } else {
            $job = $this->jobModel->getJobDetail($jobId);
            
            if (!$job || $job['status'] !== 'published') {
                show404();
            }
            
            // İlanlanan görüntülenme sayısını artır
            $this->jobModel->incrementViewCount($jobId);
        }
        
        $this->view('home/job-detail', [
            'title' => $job['title'],
            'job' => $job
        ]);
    }
    
    /**
     * AI-Powered CV Upload & Application Page (PUBLIC - No Login Required)
     */
    public function apply($jobId) {
        $job = $this->jobModel->getJobDetail($jobId);
        
        if (!$job || $job['status'] !== 'published') {
            show404();
        }
        
        $this->view('applicant/apply', [
            'title' => 'Başvuru Yap - ' . $job['title'],
            'job' => $job
        ]);
    }
    
    /**
     * Handle application submission (AJAX)
     */
    public function submitApplication() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 405);
        }
        
        $this->verifyCsrf();
        
        $jobId = post('job_id');
        $job = $this->jobModel->find($jobId);
        
        if (!$job || $job['status'] !== 'published') {
            $this->json(['success' => false, 'message' => 'İş ilanı bulunamadı'], 404);
        }
        
        // Collect form data
        $formData = [
            'full_name' => cleanInput(post('full_name')),
            'email' => cleanInput(post('email')),
            'phone' => cleanInput(post('phone')),
            'city' => cleanInput(post('city')),
            'linkedin' => cleanInput(post('linkedin')),
            'experience_years' => post('experience_years'),
            'skills' => cleanInput(post('skills')),
            'education' => post('education'),
            'current_position' => cleanInput(post('current_position')),
            'motivation' => cleanInput(post('motivation')),
            'project_example' => cleanInput(post('project_example')),
            'remote_experience' => post('remote_experience')
        ];
        
        // Validate required fields
        $errors = [];
        if (empty($formData['full_name'])) $errors['full_name'] = 'Ad soyad zorunludur';
        if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli bir e-posta adresi giriniz';
        }
        if (empty($formData['phone'])) $errors['phone'] = 'Telefon numarası zorunludur';
        if (empty($formData['experience_years'])) $errors['experience_years'] = 'Deneyim süresi zorunludur';
        if (empty($formData['skills'])) $errors['skills'] = 'Yetenekler zorunludur';
        if (empty($formData['education'])) $errors['education'] = 'Eğitim durumu zorunludur';
        if (empty($formData['motivation'])) $errors['motivation'] = 'Motivasyon sorusu zorunludur';
        if (empty($formData['project_example'])) $errors['project_example'] = 'Proje örneği zorunludur';
        if (empty($formData['remote_experience'])) $errors['remote_experience'] = 'Uzaktan çalışma deneyimi zorunludur';
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
        }
        
        // Handle CV upload
        $cvFilePath = null;
        if (isFileUploaded('cv_file')) {
            $cvUpload = uploadFile('cv_file', CV_UPLOAD_PATH, ALLOWED_CV_TYPES, MAX_CV_SIZE);
            
            if (!$cvUpload['success']) {
                $this->json(['success' => false, 'message' => $cvUpload['error']], 400);
            }
            
            $cvFilePath = $cvUpload['filePath'];
        } else {
            $this->json(['success' => false, 'message' => 'CV dosyası zorunludur'], 400);
        }
        
        // Check if user already registered - if not, create guest applicant
        $existingUser = $this->model('User')->findByEmail($formData['email']);
        
        if ($existingUser) {
            $applicantId = $existingUser['id'];
            
            // Check duplicate application
            $hasApplied = $this->applicationModel->exists([
                'job_id' => $jobId,
                'applicant_id' => $applicantId
            ]);
            
            if ($hasApplied) {
                $this->json(['success' => false, 'message' => 'Bu pozisyona zaten başvuru yaptınız'], 400);
            }
        } else {
            // Create guest applicant account
            $userModel = $this->model('User');
            $password = bin2hex(random_bytes(16)); // Random password
            
            $userData = [
                'email' => $formData['email'],
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'full_name' => $formData['full_name'],
                'phone' => $formData['phone'],
                'user_type' => 'applicant',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $applicantId = $userModel->create($userData);
            
            if (!$applicantId) {
                $this->json(['success' => false, 'message' => 'Kullanıcı oluşturulamadı'], 500);
            }
        }
        
        // Create application
        $applicationData = [
            'job_id' => $jobId,
            'applicant_id' => $applicantId,
            'form_responses' => json_encode($formData),
            'cv_file_path' => $cvFilePath,
            'cover_letter' => cleanInput(post('cover_letter', '')),
            'status' => 'pending'
        ];
        
        $result = $this->applicationModel->createApplication($applicationData);
        
        if ($result['success']) {
            $applicationId = $result['application_id'];
            
            // AI evaluation in background
            $this->evaluateApplicationWithAI($applicationId, $job, $formData, $cvFilePath);
            
            // Notify employer
            $this->notificationModel->create([
                'user_id' => $job['employer_id'],
                'title' => 'Yeni Başvuru',
                'message' => $formData['full_name'] . ' "' . $job['title'] . '" pozisyonuna başvurdu',
                'notification_type' => 'application',
                'related_id' => $applicationId,
                'related_type' => 'application',
                'action_url' => 'employer/application/' . $applicationId
            ]);
            
            $this->json([
                'success' => true, 
                'message' => 'Başvurunuz başarıyla alındı!',
                'application_id' => $applicationId
            ]);
        } else {
            $this->json(['success' => false, 'message' => $result['message']], 500);
        }
    }
    
    /**
     * AI CV parsing endpoint (AJAX)
     */
    public function parseCV() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 405);
        }
        
        if (!isFileUploaded('cv_file')) {
            $this->json(['success' => false, 'message' => 'CV dosyası yüklenmedi'], 400);
        }
        
        // Upload CV temporarily
        $cvUpload = uploadFile('cv_file', CV_UPLOAD_PATH, ALLOWED_CV_TYPES, MAX_CV_SIZE);
        
        if (!$cvUpload['success']) {
            $this->json(['success' => false, 'message' => $cvUpload['error']], 400);
        }
        
        $cvFilePath = $cvUpload['filePath'];
        
        // Parse CV with AI
        $parseResult = $this->aiService->parseCV($cvFilePath);
        
        if ($parseResult['success']) {
            $this->json([
                'success' => true,
                'data' => $parseResult['data'],
                'message' => 'CV başarıyla analiz edildi'
            ]);
        } else {
            $this->json([
                'success' => false,
                'message' => 'CV analiz edilemedi: ' . ($parseResult['error'] ?? 'Bilinmeyen hata')
            ], 500);
        }
    }
    
    /**
     * AI evaluation helper
     */
    private function evaluateApplicationWithAI($applicationId, $job, $formData, $cvFilePath) {
        try {
            // Parse CV
            $cvText = '';
            if ($cvFilePath) {
                $cvParseResult = $this->aiService->parseCV($cvFilePath);
                if ($cvParseResult['success']) {
                    $cvText = json_encode($cvParseResult['data'], JSON_UNESCAPED_UNICODE);
                }
            }
            
            // Evaluate candidate
            $evaluation = $this->aiService->evaluateCandidate(
                $job['description'],
                $job['requirements'],
                $formData,
                $cvText
            );
            
            if ($evaluation['success']) {
                // Update application with AI score
                $this->applicationModel->update($applicationId, [
                    'ai_score' => $evaluation['data']['score'] ?? 0,
                    'ai_feedback' => json_encode($evaluation['data']['feedback'] ?? [], JSON_UNESCAPED_UNICODE),
                    'ai_match_reasons' => json_encode($evaluation['data']['match_reasons'] ?? [], JSON_UNESCAPED_UNICODE)
                ]);
            }
        } catch (Exception $e) {
            logMessage("AI evaluation error: " . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Demo iş ilanı getir
     */
    private function getDemoJob($jobId) {
        $demoJobs = $this->getDemoJobs();
        
        foreach ($demoJobs as $job) {
            if ($job['id'] === $jobId) {
                // Detay sayfası için ek alanlar ekle
                $job['requirements'] = 'React, Vue.js, TypeScript, HTML5, CSS3, JavaScript';
                $job['responsibilities'] = 'Modern web uygulamaları geliştirme, Takım ile işbirliği, Kod kalitesi sağlama';
                $job['benefits'] = 'Sağlık sigortası, Esnek çalışma saatleri, Uzaktan çalışma imkanı, Eğitim desteği';
                $job['application_deadline'] = date('Y-m-d', strtotime('+30 days'));
                $job['view_count'] = rand(100, 500);
                $job['application_count'] = rand(10, 50);
                return $job;
            }
        }
        
        return null;
    }
    
    /**
     * Demo iş ilanları listesi
     */
    private function getDemoJobs() {
        return [
            [
                'id' => 'demo-1',
                'title' => 'Senior Frontend Developer',
                'company_name' => 'Tech Solutions A.Ş.',
                'location' => 'İstanbul, Türkiye',
                'employment_type' => 'full-time',
                'experience_level' => 'senior',
                'salary_min' => 35000,
                'salary_max' => 55000,
                'description' => 'Modern web teknolojileri (React, Vue.js, TypeScript) konusunda deneyimli, kullanıcı deneyimine önem veren frontend developer arayışımız devam ediyor. Dinamik ve yenilikçi bir ekipte çalışma fırsatı.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'id' => 'demo-2',
                'title' => 'Backend Developer (Node.js)',
                'company_name' => 'Digital Innovation Ltd.',
                'location' => 'Ankara, Türkiye',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'salary_min' => 28000,
                'salary_max' => 42000,
                'description' => 'Node.js, Express.js ve MongoDB konusunda tecrübeli, API geliştirme ve mikroservis mimarisi konusunda bilgili backend developer arıyoruz.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'id' => 'demo-3',
                'title' => 'Full Stack Developer',
                'company_name' => 'StartUp Hub',
                'location' => 'İzmir, Türkiye (Hybrid)',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'salary_min' => 30000,
                'salary_max' => 45000,
                'description' => 'Hem frontend hem backend teknolojilerinde deneyimli, yeni teknolojileri öğrenmeye hevesli full stack developer pozisyonu.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'id' => 'demo-4',
                'title' => 'Mobile App Developer (Flutter)',
                'company_name' => 'Mobile First Co.',
                'location' => 'İstanbul, Türkiye',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'salary_min' => 32000,
                'salary_max' => 48000,
                'description' => 'Flutter framework ile iOS ve Android uygulama geliştirme deneyimi olan, cross-platform mobile development konusunda uzman developer.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'id' => 'demo-5',
                'title' => 'DevOps Engineer',
                'company_name' => 'Cloud Systems Inc.',
                'location' => 'İstanbul, Türkiye (Remote)',
                'employment_type' => 'full-time',
                'experience_level' => 'senior',
                'salary_min' => 40000,
                'salary_max' => 60000,
                'description' => 'Docker, Kubernetes, CI/CD pipeline kurulumu ve yönetimi konusunda deneyimli DevOps Engineer aramaktayız.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 week')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 week'))
            ],
            [
                'id' => 'demo-6',
                'title' => 'UI/UX Designer',
                'company_name' => 'Design Studio Pro',
                'location' => 'İstanbul, Türkiye',
                'employment_type' => 'part-time',
                'experience_level' => 'mid',
                'salary_min' => 25000,
                'salary_max' => 38000,
                'description' => 'Figma, Adobe XD ve Sketch ile tasarım yapabilen, kullanıcı deneyimi ve arayüz tasarımı konusunda deneyimli UI/UX Designer.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
            ],
            [
                'id' => 'demo-7',
                'title' => 'Data Scientist',
                'company_name' => 'AI Research Labs',
                'location' => 'Ankara, Türkiye',
                'employment_type' => 'full-time',
                'experience_level' => 'senior',
                'salary_min' => 45000,
                'salary_max' => 70000,
                'description' => 'Python, Machine Learning, Deep Learning ve veri analizi konusunda uzman, yapay zeka projelerinde çalışmış Data Scientist.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-6 days'))
            ],
            [
                'id' => 'demo-8',
                'title' => 'Project Manager',
                'company_name' => 'Enterprise Solutions',
                'location' => 'İstanbul, Türkiye',
                'employment_type' => 'contract',
                'experience_level' => 'lead',
                'salary_min' => 35000,
                'salary_max' => 55000,
                'description' => 'Agile/Scrum metodolojileri konusunda deneyimli, yazılım projelerini yönetmiş, PMP sertifikalı Project Manager.',
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ]
        ];
    }

    private function getDemoJobDetail($jobId) {
        switch ($jobId) {
            case 'demo-1':
                return [
                    'requirements' => [
                        'PHP, Laravel ve modern web teknolojilerinde en az 5 yıl deneyim',
                        'RESTful API tasarımı ve mikroservis mimarileri konusunda uzman seviye bilgi',
                        'MySQL, PostgreSQL gibi ilişkisel veritabanları ve Redis, MongoDB gibi NoSQL çözümlerinde deneyim',
                        'Git, Docker, Kubernetes gibi DevOps araçlarında çalışma deneyimi',
                        'Unit test, integration test ve TDD prensiplerini uygulama yeteneği',
                        'Agile/Scrum metodolojileri ile çalışma deneyimi'
                    ],
                    'responsibilities' => [
                        'Yüksek performanslı ve ölçeklenebilir web uygulamaları geliştirmek',
                        'Temiz, sürdürülebilir ve test edilebilir kod yazmak',
                        'Code review süreçlerine aktif katılım sağlamak',
                        'Teknik dokümantasyon hazırlamak ve güncel tutmak',
                        'Junior geliştiricilere mentorluk yapmak',
                        'Sistem mimarisinin tasarımına katkı sağlamak'
                    ],
                    'benefits' => [
                        'Esnek çalışma saatleri ve remote çalışma imkanı',
                        'Yıllık 25 gün ücretli izin',
                        'Özel sağlık sigortası',
                        'Eğitim ve gelişim bütçesi',
                        'Modern ofis ortamı ve ekipman',
                        'Performans bonusu ve hisse senedi opsiyonu'
                    ]
                ];
            case 'demo-2':
                return [
                    'requirements' => [
                        'React, Vue.js veya Angular gibi modern JavaScript framework\'lerinde uzmanlık',
                        'TypeScript ile profesyonel düzeyde çalışma deneyimi',
                        'HTML5, CSS3, SASS/LESS konularında ileri seviye bilgi',
                        'Responsive design ve cross-browser uyumluluk konusunda deneyim',
                        'RESTful API ve GraphQL entegrasyonu deneyimi',
                        'Webpack, Babel gibi build tool\'ları ile çalışma bilgisi'
                    ],
                    'responsibilities' => [
                        'Modern ve kullanıcı dostu arayüzler geliştirmek',
                        'Component tabanlı yeniden kullanılabilir kod yazmak',
                        'Performance optimizasyonu ve SEO çalışmaları yapmak',
                        'UI/UX tasarımcılar ile yakın çalışarak tasarımları hayata geçirmek',
                        'Frontend kod standartlarını oluşturmak ve korumak',
                        'A/B test ve kullanıcı deneyimi iyileştirmeleri yapmak'
                    ],
                    'benefits' => [
                        'Hybrid çalışma modeli (ofis + remote)',
                        'Güncel teknolojilerle çalışma fırsatı',
                        'Uluslararası projelerde yer alma şansı',
                        'Yurt içi ve yurt dışı konferans katılımı',
                        'Spor salonu üyeliği',
                        'Takım aktiviteleri ve sosyal etkinlikler'
                    ]
                ];
            case 'demo-3':
                return [
                    'requirements' => [
                        'Node.js ve Express.js ile production grade uygulama geliştirme deneyimi',
                        'MongoDB, PostgreSQL veya MySQL ile çalışma tecrübesi',
                        'RESTful API ve GraphQL API tasarımı konusunda bilgi',
                        'Asenkron programlama ve event-driven architecture bilgisi',
                        'Docker, CI/CD pipeline\'ları konusunda deneyim',
                        'Microservices architecture konusunda temel bilgi'
                    ],
                    'responsibilities' => [
                        'Backend servisleri ve API\'ları geliştirmek',
                        'Veritabanı tasarımı ve optimizasyonu yapmak',
                        'Third-party API entegrasyonları gerçekleştirmek',
                        'Sistem güvenliği ve data protection konularında çalışmak',
                        'Performance monitoring ve logging sistemleri kurmak',
                        'Teknik dokümantasyon oluşturmak'
                    ],
                    'benefits' => [
                        'Tam remote çalışma imkanı',
                        'Esnek mesai saatleri',
                        'Yıllık eğitim ve konferans bütçesi',
                        'En son teknolojik ekipman desteği',
                        'Sağlık sigortası ve özel emeklilik',
                        'Yemek ve ulaşım yardımı'
                    ]
                ];
            case 'demo-4':
                return [
                    'requirements' => [
                        'İOS ve Android platformlarında native veya hybrid uygulama geliştirme deneyimi',
                        'React Native, Flutter veya Swift/Kotlin bilgisi',
                        'RESTful API entegrasyonu ve asenkron programlama',
                        'Push notification, deep linking gibi mobile özelliklerde deneyim',
                        'App Store ve Google Play yayınlama süreçlerine hakim olmak',
                        'Git versiyon kontrol sistemi kullanımı'
                    ],
                    'responsibilities' => [
                        'Kullanıcı dostu mobile uygulamalar geliştirmek',
                        'App performansını optimize etmek',
                        'Backend ekibi ile koordineli çalışarak API entegrasyonları yapmak',
                        'Bug fix ve technical debt yönetimi',
                        'App store submission ve version management',
                        'Mobile analytics ve crash reporting sistemleri kurmak'
                    ],
                    'benefits' => [
                        'Hybrid çalışma modeli',
                        'iPhone ve Android test cihazları',
                        'Paid developer account\'lar',
                        'Udemy, Pluralsight gibi platformlara üyelik',
                        'Performans primi',
                        'Esnek izin politikası'
                    ]
                ];
            case 'demo-5':
                return [
                    'requirements' => [
                        'AWS, Azure veya Google Cloud Platform deneyimi',
                        'Docker, Kubernetes gibi container teknolojileri bilgisi',
                        'CI/CD pipeline tasarımı ve yönetimi',
                        'Infrastructure as Code (Terraform, CloudFormation)',
                        'Linux sistem yönetimi ve bash scripting',
                        'Monitoring tools (Prometheus, Grafana, ELK Stack)'
                    ],
                    'responsibilities' => [
                        'Cloud infrastructure kurulumu ve yönetimi',
                        'Deployment automation ve CI/CD pipeline geliştirme',
                        'System monitoring ve alerting yapılandırması',
                        'Security best practices uygulama',
                        'Disaster recovery ve backup stratejileri oluşturma',
                        'Cost optimization çalışmaları yapma'
                    ],
                    'benefits' => [
                        'Cloud certification desteği',
                        'En yeni teknolojilerle çalışma imkanı',
                        'Remote çalışma opsiyonu',
                        'Konferans ve workshop katılımı',
                        'Rekabetçi maaş paketi',
                        'Sağlık ve hayat sigortası'
                    ]
                ];
            case 'demo-6':
                return [
                    'requirements' => [
                        'Figma, Adobe XD veya Sketch gibi tasarım araçlarında uzmanlık',
                        'User research ve usability testing deneyimi',
                        'Wireframing ve prototyping yetenekleri',
                        'Design system oluşturma ve yönetme bilgisi',
                        'HTML/CSS temel bilgisi (artı)',
                        'Portfolio ile başvuru zorunludur'
                    ],
                    'responsibilities' => [
                        'Kullanıcı araştırması ve persona oluşturma',
                        'User flow ve information architecture tasarımı',
                        'High-fidelity mockup ve prototip hazırlama',
                        'Design system dokümantasyonu',
                        'Usability test\'ler düzenleme ve analiz etme',
                        'Developer\'lar ile koordineli çalışarak design implementation'
                    ],
                    'benefits' => [
                        'Part-time esnek çalışma saatleri',
                        'Remote çalışma imkanı',
                        'Adobe Creative Cloud lisansı',
                        'Design konferanslarına katılım',
                        'Kreatif ve dinamik ekip ortamı',
                        'Portfolio projelerinde çalışma fırsatı'
                    ]
                ];
            case 'demo-7':
                return [
                    'requirements' => [
                        'Python ve data science kütüphaneleri (NumPy, Pandas, Scikit-learn)',
                        'Machine Learning ve Deep Learning framework\'leri (TensorFlow, PyTorch)',
                        'İstatistik ve probability teorisi bilgisi',
                        'SQL ve NoSQL veritabanları ile çalışma deneyimi',
                        'Big Data teknolojileri (Spark, Hadoop) bilgisi (artı)',
                        'Yüksek lisans veya doktora derecesi tercih sebebi'
                    ],
                    'responsibilities' => [
                        'Machine learning modelleri geliştirmek ve optimize etmek',
                        'Büyük veri setlerini analiz etmek ve insight\'lar çıkarmak',
                        'A/B testler tasarlamak ve sonuçlarını yorumlamak',
                        'Data pipeline\'ları oluşturmak',
                        'Model deployment ve monitoring',
                        'Research paper\'lar okuyup yeni teknikleri uygulamak'
                    ],
                    'benefits' => [
                        'Cutting-edge AI projeleri',
                        'High-performance computing resources',
                        'Konferans ve research publication desteği',
                        'PhD sponsorluğu (uygun adaylar için)',
                        'Rekabetçi maaş ve bonus',
                        'Esnek çalışma saatleri'
                    ]
                ];
            case 'demo-8':
                return [
                    'requirements' => [
                        'PMP veya Agile certification',
                        'Yazılım projelerinde en az 5 yıl proje yönetimi deneyimi',
                        'Scrum, Kanban gibi Agile metodolojilerine hakim',
                        'JIRA, Confluence gibi proje yönetim araçları bilgisi',
                        'Budget planning ve resource management',
                        'Mükemmel iletişim ve liderlik becerileri'
                    ],
                    'responsibilities' => [
                        'Proje planlaması ve timeline oluşturma',
                        'Stakeholder yönetimi ve düzenli raporlama',
                        'Risk yönetimi ve mitigation stratejileri',
                        'Team coordination ve resource allocation',
                        'Sprint planning ve retrospective toplantıları yönetme',
                        'Budget tracking ve cost management'
                    ],
                    'benefits' => [
                        'Contractor olarak yüksek günlük rate',
                        'Esnek çalışma modeli',
                        'Multiple proje çeşitliliği',
                        'Professional development budget',
                        'Enterprise seviye projeler',
                        'Network oluşturma fırsatı'
                    ]
                ];
            default:
                return [
                    'requirements' => [],
                    'responsibilities' => [],
                    'benefits' => []
                ];
        }
    }

    /**
     * AI-powered CV Analysis (AJAX)
     */
    public function analyzeJobMatch() {
        // Tüm output buffer'ları temizle
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Yeni buffer başlat
        ob_start();
        
        // Header'ları ayarla
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-cache, must-revalidate');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = json_encode(['success' => false, 'message' => 'Invalid request method']);
            ob_clean();
            echo $response;
            ob_end_flush();
            exit;
        }
        
        $jobId = $_POST['job_id'] ?? '';
        
        // Demo job kontrolü
        if (strpos($jobId, 'demo-') === 0) {
            $job = $this->getDemoJob($jobId);
            if (!$job) {
                $response = json_encode(['success' => false, 'message' => 'İş ilanı bulunamadı']);
                ob_clean();
                echo $response;
                ob_end_flush();
                exit;
            }
            
            // Demo job detaylarını ekle
            $details = $this->getDemoJobDetail($jobId);
            $job = array_merge($job, $details);
        } else {
            $job = $this->jobModel->getJobDetail($jobId);
            if (!$job || $job['status'] !== 'published') {
                $response = json_encode(['success' => false, 'message' => 'İş ilanı bulunamadı']);
                ob_clean();
                echo $response;
                ob_end_flush();
                exit;
            }
        }
        
        // Kullanıcı giriş yapmışsa CV'sini al
        $cvText = '';
        $candidateProfile = [];
        
        if (isLoggedIn()) {
            try {
                $userId = $_SESSION['user_id'];
                $candidateModel = $this->model('Candidate');
                $candidate = $candidateModel->find($userId);
                
                if ($candidate && !empty($candidate['cv_path'])) {
                    // CV dosyasından metin çıkar (basit versiyon)
                    $cvPath = APP_PATH . '/../' . $candidate['cv_path'];
                    if (file_exists($cvPath)) {
                        $cvText = "CV yüklü ve mevcut";
                    }
                }
                
                $candidateProfile = [
                    'name' => $candidate['full_name'] ?? '',
                    'email' => $candidate['email'] ?? '',
                    'skills' => $candidate['skills'] ?? '',
                    'experience_years' => $candidate['experience_years'] ?? 0,
                    'education' => $candidate['education'] ?? ''
                ];
            } catch (Exception $e) {
                // Model yoksa veya hata varsa, boş profil kullan
                // Hata loglama yapılabilir
            }
        }
        
        // AI ile analiz yap
        $prompt = $this->buildJobMatchPrompt($job, $candidateProfile, $cvText);
        
        $result = $this->aiService->chat($prompt, 'İş uyumluluk analizi yapan uzman bir İK danışmanısın.');
        
        if ($result['success']) {
            // Yapılandırılmış yanıt parse et
            $analysis = $this->parseAIAnalysis($result['message']);
            $response = json_encode([
                'success' => true,
                'analysis' => $analysis
            ]);
        } else {
            // Demo mode veya hata durumunda örnek sonuç
            $response = json_encode([
                'success' => true,
                'demo_mode' => true,
                'analysis' => $this->getDemoAnalysis($job)
            ]);
        }
        
        ob_clean();
        echo $response;
        ob_end_flush();
        exit;
    }
    
    /**
     * İş uyumluluk analizi için prompt oluştur
     */
    private function buildJobMatchPrompt($job, $candidateProfile, $cvText) {
        $requirements = is_array($job['requirements']) ? implode(', ', $job['requirements']) : '';
        $responsibilities = is_array($job['responsibilities']) ? implode(', ', $job['responsibilities']) : '';
        
        $prompt = "İş İlanı Analizi:\n\n";
        $prompt .= "Pozisyon: " . ($job['title'] ?? '') . "\n";
        $prompt .= "Şirket: " . ($job['company_name'] ?? '') . "\n";
        $prompt .= "Lokasyon: " . ($job['location'] ?? '') . "\n";
        $prompt .= "Tecrübe: " . ($job['experience_level'] ?? '') . "\n\n";
        
        if ($requirements) {
            $prompt .= "Gereksinimler: " . $requirements . "\n\n";
        }
        
        if ($responsibilities) {
            $prompt .= "Sorumluluklar: " . $responsibilities . "\n\n";
        }
        
        if (!empty($candidateProfile)) {
            $prompt .= "Aday Profili:\n";
            $prompt .= "İsim: " . ($candidateProfile['name'] ?? 'Anonim') . "\n";
            $prompt .= "Yetenekler: " . ($candidateProfile['skills'] ?? 'Belirtilmemiş') . "\n";
            $prompt .= "Tecrübe: " . ($candidateProfile['experience_years'] ?? 0) . " yıl\n";
            $prompt .= "Eğitim: " . ($candidateProfile['education'] ?? 'Belirtilmemiş') . "\n\n";
        }
        
        $prompt .= "Lütfen bu iş ilanı için detaylı bir uyumluluk analizi yap ve şu formatta cevap ver:\n\n";
        $prompt .= "UYUMLULUK SKORU: [75-95 arası bir skor]\n\n";
        $prompt .= "GÜÇLÜ YÖNLER:\n";
        $prompt .= "- [3-4 madde]\n\n";
        $prompt .= "GELİŞTİRME ÖNERİLERİ:\n";
        $prompt .= "- [3-4 madde]\n\n";
        $prompt .= "GENEL DEĞERLENDİRME:\n";
        $prompt .= "[2-3 cümlelik özet değerlendirme]\n";
        
        return $prompt;
    }
    
    /**
     * AI yanıtını parse et
     */
    private function parseAIAnalysis($aiResponse) {
        $analysis = [
            'score' => 80,
            'strengths' => [],
            'improvements' => [],
            'summary' => ''
        ];
        
        // Skor çıkar
        if (preg_match('/UYUMLULUK SKORU:\s*(\d+)/i', $aiResponse, $matches)) {
            $analysis['score'] = intval($matches[1]);
        }
        
        // Güçlü yönleri çıkar
        if (preg_match('/GÜÇLÜ YÖNLER:(.*?)(?:GELİŞTİRME ÖNERİLERİ:|$)/s', $aiResponse, $matches)) {
            $strengths = trim($matches[1]);
            preg_match_all('/-\s*(.+?)(?=\n-|\n\n|$)/s', $strengths, $items);
            $analysis['strengths'] = array_map('trim', $items[1]);
        }
        
        // Geliştirme önerilerini çıkar
        if (preg_match('/GELİŞTİRME ÖNERİLERİ:(.*?)(?:GENEL DEĞERLENDİRME:|$)/s', $aiResponse, $matches)) {
            $improvements = trim($matches[1]);
            preg_match_all('/-\s*(.+?)(?=\n-|\n\n|$)/s', $improvements, $items);
            $analysis['improvements'] = array_map('trim', $items[1]);
        }
        
        // Genel değerlendirme
        if (preg_match('/GENEL DEĞERLENDİRME:\s*(.+?)$/s', $aiResponse, $matches)) {
            $analysis['summary'] = trim($matches[1]);
        }
        
        return $analysis;
    }
    
    /**
     * Demo analiz sonucu
     */
    private function getDemoAnalysis($job) {
        $jobTitle = $job['title'] ?? 'Bu pozisyon';
        
        return [
            'score' => rand(78, 94),
            'strengths' => [
                'Tecrübe seviyeniz pozisyonla uyumlu görünüyor',
                'İstenen teknik beceriler profilinizde mevcut',
                'Benzer projelerde çalışma deneyiminiz var',
                'Şirket kültürüne uyum sağlayabilirsiniz'
            ],
            'improvements' => [
                'CV\'nizde sertifikalarınızı daha detaylı belirtin',
                'Proje başarılarınızı somut rakamlarla destekleyin',
                'LinkedIn profilinizi güncelleyin ve aktif tutun',
                $jobTitle . ' için özel bir ön yazı hazırlayın'
            ],
            'summary' => 'Profiliniz bu pozisyon için oldukça uygun görünüyor. Başvuru yapmanızı kesinlikle öneriyoruz. Belirttiğimiz geliştirme önerilerini uygularsanız şansınız daha da artacaktır.'
        ];
    }
}
