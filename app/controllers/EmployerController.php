<?php
/**
 * Employer Controller
 * İşveren paneli işlemleri
 */

class EmployerController extends Controller {
    private $jobModel;
    private $applicationModel;
    private $userModel;
    private $aiService;
    private $notificationModel;
    
    public function __construct() {
        $this->requireEmployer();
        $this->jobModel = $this->model('Job');
        $this->applicationModel = $this->model('Application');
        $this->userModel = $this->model('User');
        $this->notificationModel = $this->model('Notification');
        $this->aiService = new AIService();
    }
    
    /**
     * Dashboard
     */
    public function dashboard() {
        $employerId = authId();
        
        // İstatistikler
        $jobStats = $this->jobModel->getEmployerStats($employerId);
        $applicationStats = $this->applicationModel->getEmployerStats($employerId);
        
        // Son ilanlar
        $recentJobs = $this->jobModel->getEmployerJobs($employerId);
        
        // Son başvurular
        $recentApplications = $this->applicationModel->getEmployerApplications($employerId, [], 1);
        
        $this->view('employer/dashboard', [
            'title' => 'İşveren Paneli',
            'job_stats' => $jobStats,
            'application_stats' => $applicationStats,
            'recent_jobs' => array_slice($recentJobs, 0, 5),
            'recent_applications' => array_slice($recentApplications, 0, 10)
        ]);
    }
    
    /**
     * İş ilanları listesi
     */
    public function jobs() {
        $employerId = authId();
        $status = get('status', '');
        
        $jobs = $this->jobModel->getEmployerJobs($employerId, $status);
        
        $this->view('employer/jobs/index', [
            'title' => 'İş İlanlarım',
            'jobs' => $jobs,
            'current_status' => $status
        ]);
    }
    
    /**
     * İş ilanı oluşturma - Adım 1: Tanım
     */
    public function createJob() {
        if ($this->isPost()) {
            $this->verifyCsrf();
            
            // Salary validation - max 99999999 (DECIMAL 10,2 limit)
            $salaryMin = post('salary_min') ? min((float)post('salary_min'), 99999999) : null;
            $salaryMax = post('salary_max') ? min((float)post('salary_max'), 99999999) : null;
            
            $jobData = [
                'employer_id' => authId(),
                'title' => cleanInput(post('title')),
                'description' => cleanInput(post('description')),
                'location' => cleanInput(post('location')),
                'employment_type' => post('employment_type'),
                'experience_level' => post('experience_level'),
                'salary_min' => $salaryMin,
                'salary_max' => $salaryMax,
                'requirements' => cleanInput(post('requirements')),
                'benefits' => cleanInput(post('benefits')),
                'status' => 'draft'
            ];
            
            // Validasyon
            $errors = $this->validate([
                'title' => 'required|min:5',
                'description' => 'required|min:50'
            ]);
            
            if (empty($errors)) {
                $jobId = $this->jobModel->createJob($jobData);
                
                if ($jobId) {
                    redirect('employer/generate-form/' . $jobId);
                }
            }
            
            $this->view('employer/jobs/create', [
                'title' => 'Yeni İş İlanı Oluştur',
                'errors' => $errors,
                'data' => $jobData
            ]);
        } else {
            $this->view('employer/jobs/create', [
                'title' => 'Yeni İş İlanı Oluştur'
            ]);
        }
    }
    
    /**
     * AI ile form oluşturma
     */
    public function generateForm($jobId) {
        $job = $this->jobModel->find($jobId);
        
        if (!$job || $job['employer_id'] != authId()) {
            show404();
        }
        
        if ($this->isPost() && post('action') === 'generate') {
            $this->verifyCsrf();
            
            // AI ile form oluştur
            $result = $this->aiService->generateJobForm(
                $job['description'] . "\n\nGereksinimler: " . $job['requirements'],
                $job['title']
            );
            
            if ($result['success']) {
                // Önce eski alanları sil
                $this->jobModel->deleteFormFields($jobId);
                
                // Form alanlarını veritabanına kaydet
                $order = 0;
                foreach ($result['fields'] as $field) {
                    $fieldData = [
                        'job_id' => $jobId,
                        'field_type' => $field['field_type'],
                        'field_label' => $field['field_label'],
                        'field_name' => $field['field_name'],
                        'field_placeholder' => $field['field_placeholder'] ?? '',
                        'field_options' => isset($field['field_options']) ? json_encode($field['field_options']) : null,
                        'is_required' => $field['is_required'] ? 1 : 0,
                        'field_category' => $field['field_category'],
                        'field_order' => $order++,
                        'ai_generated' => 1,
                        'ai_scoring_weight' => $field['ai_scoring_weight'] ?? 1.0,
                        'validation_rules' => null
                    ];
                    
                    $this->jobModel->addFormField($fieldData);
                }
                
                setFlash('success', 'Form alanları AI tarafından oluşturuldu. İnceleyip düzenleyebilirsiniz.');
                redirect('employer/generate-form/' . $jobId);
            } else {
                setFlash('error', 'Form oluşturulurken hata: ' . ($result['error'] ?? 'Bilinmeyen hata'));
            }
        }
        
        // Form alanlarını çek
        $formFields = $this->jobModel->getFormFields($jobId);
        
        // Eğer form alanları boşsa, demo alanları göster
        if (empty($formFields)) {
            // Job description ve title'ı hazırla
            $jobDescription = ($job['description'] ?? '') . "\n\n" . ($job['requirements'] ?? '');
            $jobTitle = $job['title'] ?? 'Pozisyon';
            
            // Demo form oluştur
            $demoResult = $this->aiService->generateJobForm($jobDescription, $jobTitle);
            if ($demoResult['success'] && !empty($demoResult['fields'])) {
                $formFields = $demoResult['fields'];
            } else {
                // Fallback: En basit demo form
                $formFields = [
                    ['field_type' => 'text', 'field_label' => 'Ad Soyad', 'field_name' => 'full_name', 'field_placeholder' => 'Adınız', 'is_required' => true, 'field_category' => 'personal', 'ai_scoring_weight' => 0.5],
                    ['field_type' => 'email', 'field_label' => 'E-posta', 'field_name' => 'email', 'field_placeholder' => 'ornek@email.com', 'is_required' => true, 'field_category' => 'personal', 'ai_scoring_weight' => 0.5],
                    ['field_type' => 'phone', 'field_label' => 'Telefon', 'field_name' => 'phone', 'field_placeholder' => '0555 123 45 67', 'is_required' => true, 'field_category' => 'personal', 'ai_scoring_weight' => 0.3],
                ];
            }
        }
        
        $this->view('employer/jobs/generate-form', [
            'title' => 'Form Oluştur - ' . $job['title'],
            'job' => $job,
            'form_fields' => $formFields
        ]);
    }
    
    /**
     * Form alanlarını düzenle
     */
    public function editForm($jobId) {
        $job = $this->jobModel->find($jobId);
        
        if (!$job || $job['employer_id'] != authId()) {
            show404();
        }
        
        if ($this->isPost()) {
            $this->verifyCsrf();
            
            $action = post('action');
            
            if ($action === 'add_field') {
                // Yeni alan ekle
                $fieldData = [
                    'job_id' => $jobId,
                    'field_type' => post('field_type'),
                    'field_label' => cleanInput(post('field_label')),
                    'field_name' => post('field_name'),
                    'field_placeholder' => cleanInput(post('field_placeholder')),
                    'field_options' => post('field_options') ? json_encode(explode(',', post('field_options'))) : null,
                    'is_required' => post('is_required') ? 1 : 0,
                    'field_category' => post('field_category'),
                    'field_order' => post('field_order', 999),
                    'ai_generated' => 0
                ];
                
                $this->jobModel->addFormField($fieldData);
                setFlash('success', 'Alan eklendi');
                
            } elseif ($action === 'update_field') {
                // Alan güncelle
                $fieldId = post('field_id');
                $fieldData = [
                    'field_label' => cleanInput(post('field_label')),
                    'field_placeholder' => cleanInput(post('field_placeholder')),
                    'is_required' => post('is_required') ? 1 : 0,
                    'field_order' => post('field_order')
                ];
                
                $this->jobModel->updateFormField($fieldId, $fieldData);
                setFlash('success', 'Alan güncellendi');
                
            } elseif ($action === 'delete_field') {
                // Alan sil
                $fieldId = post('field_id');
                $this->jobModel->deleteFormField($fieldId);
                setFlash('success', 'Alan silindi');
            }
            
            redirect('employer/edit-form/' . $jobId);
        }
        
        $formFields = $this->jobModel->getFormFields($jobId);
        
        $this->view('employer/jobs/edit-form', [
            'title' => 'Formu Düzenle - ' . $job['title'],
            'job' => $job,
            'form_fields' => $formFields
        ]);
    }
    
    /**
     * İş ilanını yayınla
     */
    public function publishJob($jobId) {
        $job = $this->jobModel->find($jobId);
        
        if (!$job || $job['employer_id'] != authId()) {
            show404();
        }
        
        // Form alanları var mı kontrol et
        $formFields = $this->jobModel->getFormFields($jobId);
        
        if (empty($formFields)) {
            setFlash('error', 'İlanı yayınlamak için önce form alanları oluşturmalısınız');
            redirect('employer/generate-form/' . $jobId);
        }
        
        $this->jobModel->publishJob($jobId);
        setFlash('success', 'İş ilanı yayınlandı');
        redirect('employer/jobs');
    }
    
    /**
     * İş ilanını kapat
     */
    public function closeJob($jobId) {
        $job = $this->jobModel->find($jobId);
        
        if (!$job || $job['employer_id'] != authId()) {
            show404();
        }
        
        $this->jobModel->closeJob($jobId);
        setFlash('success', 'İş ilanı kapatıldı');
        redirect('employer/jobs');
    }
    
    /**
     * İş ilanı başvuruları
     */
    public function applications($jobId = null) {
        $employerId = authId();
        
        if ($jobId) {
            // Belirli bir iş ilanının başvuruları
            $job = $this->jobModel->find($jobId);
            
            if (!$job || $job['employer_id'] != $employerId) {
                show404();
            }
            
            $applications = $this->applicationModel->getJobApplications($jobId);
            $viewData = [
                'title' => 'Başvurular - ' . $job['title'],
                'job' => $job,
                'applications' => $applications
            ];
        } else {
            // Tüm başvurular
            $page = get('page', 1);
            $filter = get('filter', 'all'); // Yeni: filter parametresi
            $filters = [
                'status' => get('status', ''),
                'job_id' => get('job_id', ''),
                'min_score' => get('min_score', '')
            ];
            
            // Filter'a göre özel sorgular
            if ($filter === 'top') {
                $filters['min_score'] = 80; // En iyi eşleşmeler (80+ skor)
            } elseif ($filter === 'new') {
                $filters['days'] = 7; // Son 7 gündeki başvurular
            } elseif ($filter === 'pending') {
                $filters['status'] = 'pending';
            } elseif ($filter === 'reviewed') {
                $filters['status'] = 'reviewed';
            }
            
            $applications = $this->applicationModel->getEmployerApplications($employerId, $filters, $page);
            $jobs = $this->jobModel->getEmployerJobs($employerId);
            
            // İstatistikler
            $stats = $this->applicationModel->getEmployerStats($employerId);
            
            $viewData = [
                'title' => 'Tüm Başvurular',
                'applications' => $applications,
                'jobs' => $jobs,
                'filters' => $filters,
                'current_filter' => $filter,
                'stats' => $stats
            ];
        }
        
        $this->view('employer/applications/index', $viewData);
    }
    
    /**
     * Başvuru detayı
     */
    public function applicationDetail($applicationId) {
        $application = $this->applicationModel->getApplicationDetail($applicationId);
        
        if (!$application) {
            show404();
        }
        
        // İşveren kontrolü
        $job = $this->jobModel->find($application['job_id']);
        if ($job['employer_id'] != authId()) {
            show403();
        }
        
        // Görüldü olarak işaretle
        $this->applicationModel->markAsViewed($applicationId);
        
        // Form alanlarını al
        $formFields = $this->jobModel->getFormFields($application['job_id']);
        
        $this->view('employer/applications/detail', [
            'title' => 'Başvuru Detayı',
            'application' => $application,
            'form_fields' => $formFields
        ]);
    }
    
    /**
     * Başvuru durumunu güncelle
     */
    public function updateApplicationStatus() {
        if (!$this->isPost() || !$this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Geçersiz istek'], 400);
        }
        
        $this->verifyCsrf();
        
        $applicationId = post('application_id');
        $status = post('status');
        $notes = cleanInput(post('notes'));
        
        $result = $this->applicationModel->updateStatus($applicationId, $status, $notes);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Durum güncellendi']);
        } else {
            $this->json(['success' => false, 'message' => 'Güncelleme başarısız'], 500);
        }
    }
    
    /**
     * Bildirimleri listele
     */
    public function notifications() {
        $notifications = $this->notificationModel->getUserNotifications(authId());
        
        $this->view('employer/notifications', [
            'title' => 'Bildirimler',
            'notifications' => $notifications
        ]);
    }
    
    /**
     * AI Chat endpoint
     */
    public function aiChat() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Geçersiz istek'], 400);
            return;
        }
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $message = isset($input['message']) ? trim($input['message']) : '';
        
        if (empty($message)) {
            $this->json(['success' => false, 'message' => 'Mesaj boş olamaz'], 400);
            return;
        }
        
        try {
            // Use AI service to get response
            $response = $this->aiService->getChatResponse($message);
            
            $this->json([
                'success' => true,
                'response' => $response
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'AI yanıt verirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * İşveren profil görüntüleme
     */
    public function profile() {
        $userId = authId();
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            redirect('auth/login');
        }
        
        $this->view('employer/profile', [
            'title' => 'Şirket Profilim',
            'user' => $user
        ]);
    }
    
    /**
     * İşveren profil güncelleme
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('employer/profile');
        }
        
        $userId = authId();
        $user = $this->userModel->find($userId);
        
        // Form verilerini al
        $data = [
            'full_name' => cleanInput(post('full_name')),
            'email' => cleanInput(post('email')),
            'phone' => cleanInput(post('phone')),
            'company_name' => cleanInput(post('company_name')),
            'company_description' => cleanInput(post('company_description')),
            'company_website' => cleanInput(post('company_website')),
            'company_address' => cleanInput(post('company_address')),
            'company_size' => cleanInput(post('company_size')) ?: null,
            'company_industry' => cleanInput(post('company_industry')),
            'company_founded_year' => cleanInput(post('company_founded_year')) ?: null,
            'linkedin_url' => cleanInput(post('linkedin_url')),
            'twitter_url' => cleanInput(post('twitter_url')),
            'facebook_url' => cleanInput(post('facebook_url'))
        ];
        
        // Validasyon
        $errors = [];
        
        if (empty($data['company_name'])) {
            $errors['company_name'] = 'Şirket adı zorunludur';
        }
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Yetkili kişi adı zorunludur';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'E-posta zorunludur';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli bir e-posta adresi giriniz';
        }
        
        // URL validasyonları
        if (!empty($data['company_website']) && !filter_var($data['company_website'], FILTER_VALIDATE_URL)) {
            $errors['company_website'] = 'Geçerli bir website URL giriniz';
        }
        
        if (!empty($data['linkedin_url']) && !filter_var($data['linkedin_url'], FILTER_VALIDATE_URL)) {
            $errors['linkedin_url'] = 'Geçerli bir LinkedIn URL giriniz';
        }
        
        // Logo yükleme
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadCompanyLogo($_FILES['company_logo']);
            if ($uploadResult['success']) {
                $data['profile_image'] = $uploadResult['path'];
            } else {
                $errors['company_logo'] = $uploadResult['message'];
            }
        }
        
        if (!empty($errors)) {
            $this->view('employer/profile', [
                'title' => 'Şirket Profilim',
                'user' => $user,
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }
        
        // Profili güncelle
        $updated = $this->userModel->updateProfile($userId, $data);
        
        if ($updated) {
            setFlash('success', 'Şirket profiliniz başarıyla güncellendi');
        } else {
            setFlash('error', 'Profil güncellenirken hata oluştu');
        }
        
        redirect('employer/profile');
    }
    
    /**
     * Şirket logosu yükleme
     */
    /**
     * Demo AI Form Sayfası
     */
    public function demoForm() {
        // Demo job verisi oluştur
        $demoJob = [
            'id' => 0,
            'title' => 'Frontend Developer',
            'description' => 'Modern web teknolojileri kullanarak kullanıcı arayüzleri geliştiren deneyimli bir Frontend Developer arıyoruz.',
            'requirements' => 'React, Vue.js veya Angular framework deneyimi, JavaScript/TypeScript bilgisi, Responsive design prensipleri',
            'employer_id' => authId()
        ];
        
        // Demo form alanları
        $demoFormFields = [
            [
                'field_type' => 'text',
                'field_label' => 'Ad Soyad',
                'field_name' => 'full_name',
                'field_placeholder' => 'Adınız ve soyadınız',
                'is_required' => true,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.5
            ],
            [
                'field_type' => 'email',
                'field_label' => 'E-posta Adresi',
                'field_name' => 'email',
                'field_placeholder' => 'ornek@email.com',
                'is_required' => true,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.5
            ],
            [
                'field_type' => 'phone',
                'field_label' => 'Telefon Numarası',
                'field_name' => 'phone',
                'field_placeholder' => '0555 123 45 67',
                'is_required' => true,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.3
            ],
            [
                'field_type' => 'url',
                'field_label' => 'LinkedIn Profil URL',
                'field_name' => 'linkedin_url',
                'field_placeholder' => 'https://linkedin.com/in/...',
                'is_required' => false,
                'field_category' => 'personal',
                'ai_scoring_weight' => 0.7
            ],
            [
                'field_type' => 'radio',
                'field_label' => 'JavaScript/TypeScript deneyim seviyeniz nedir?',
                'field_name' => 'js_level',
                'field_options' => json_encode(['Başlangıç', 'Orta', 'İleri', 'Uzman']),
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 1.5
            ],
            [
                'field_type' => 'checkbox',
                'field_label' => 'Hangi modern JavaScript framework\'lerinde deneyiminiz var?',
                'field_name' => 'frameworks',
                'field_options' => json_encode(['React', 'Vue.js', 'Angular', 'Svelte', 'Next.js']),
                'is_required' => true,
                'field_category' => 'technical',
                'ai_scoring_weight' => 2.0
            ],
            [
                'field_type' => 'checkbox',
                'field_label' => 'CSS framework ve preprocessor deneyiminiz',
                'field_name' => 'css_tools',
                'field_options' => json_encode(['Tailwind CSS', 'Bootstrap', 'SASS', 'LESS', 'Styled Components']),
                'is_required' => false,
                'field_category' => 'technical',
                'ai_scoring_weight' => 1.2
            ],
            [
                'field_type' => 'textarea',
                'field_label' => 'State management konusunda hangi araçları kullandınız?',
                'field_name' => 'state_management',
                'field_placeholder' => 'Redux, MobX, Zustand, Context API vb.',
                'is_required' => false,
                'field_category' => 'technical',
                'ai_scoring_weight' => 1.3
            ],
            [
                'field_type' => 'select',
                'field_label' => 'Frontend geliştirme alanında toplam kaç yıl deneyiminiz var?',
                'field_name' => 'experience_years',
                'field_options' => json_encode(['0-1 yıl', '1-3 yıl', '3-5 yıl', '5-8 yıl', '8+ yıl']),
                'is_required' => true,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.8
            ],
            [
                'field_type' => 'textarea',
                'field_label' => 'En son üzerinde çalıştığınız proje hakkında bilgi verin',
                'field_name' => 'recent_project',
                'field_placeholder' => 'Proje detayları, kullanılan teknolojiler, rolünüz...',
                'is_required' => true,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.5
            ],
            [
                'field_type' => 'url',
                'field_label' => 'GitHub veya portfolyo linkiniz',
                'field_name' => 'portfolio_url',
                'field_placeholder' => 'https://github.com/... veya https://portfolio.com',
                'is_required' => false,
                'field_category' => 'experience',
                'ai_scoring_weight' => 1.4
            ],
            [
                'field_type' => 'textarea',
                'field_label' => 'Responsive design ve mobile-first yaklaşımı konusundaki yaklaşımınızı açıklayın',
                'field_name' => 'responsive_approach',
                'field_placeholder' => 'Yaklaşımınız, kullandığınız teknikler...',
                'is_required' => false,
                'field_category' => 'additional',
                'ai_scoring_weight' => 1.1
            ],
            [
                'field_type' => 'textarea',
                'field_label' => 'Web performans optimizasyonu için hangi teknikleri uygularsınız?',
                'field_name' => 'performance_optimization',
                'field_placeholder' => 'Lazy loading, code splitting, caching vb.',
                'is_required' => false,
                'field_category' => 'additional',
                'ai_scoring_weight' => 1.2
            ],
            [
                'field_type' => 'radio',
                'field_label' => 'Uzaktan çalışma tercihiniz',
                'field_name' => 'work_preference',
                'field_options' => json_encode(['Ofiste', 'Hibrit', 'Tam uzaktan']),
                'is_required' => false,
                'field_category' => 'additional',
                'ai_scoring_weight' => 0.5
            ],
            [
                'field_type' => 'date',
                'field_label' => 'Ne zaman işe başlayabilirsiniz?',
                'field_name' => 'start_date',
                'field_placeholder' => 'GG/AA/YYYY',
                'is_required' => false,
                'field_category' => 'additional',
                'ai_scoring_weight' => 0.6
            ],
            [
                'field_type' => 'file',
                'field_label' => 'CV / Özgeçmiş',
                'field_name' => 'cv_file',
                'field_placeholder' => 'PDF, DOC, DOCX - Max 5MB',
                'is_required' => true,
                'field_category' => 'files',
                'ai_scoring_weight' => 2.0
            ],
            [
                'field_type' => 'file',
                'field_label' => 'Portfolyo veya proje örnekleri (opsiyonel)',
                'field_name' => 'portfolio_files',
                'field_placeholder' => 'Çoklu dosya yüklenebilir',
                'is_required' => false,
                'field_category' => 'files',
                'ai_scoring_weight' => 1.3
            ]
        ];
        
        $this->view('employer/jobs/demo-form', [
            'title' => 'AI Oluşturulmuş Form Önizlemesi',
            'job' => $demoJob,
            'form_fields' => $demoFormFields
        ]);
    }
    
    private function uploadCompanyLogo($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Sadece JPG, JPEG ve PNG formatları kabul edilir'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Dosya boyutu en fazla 2MB olabilir'];
        }
        
        $uploadDir = APP_PATH . '/../storage/uploads/companies/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'company_' . authId() . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'path' => 'storage/uploads/companies/' . $filename];
        }
        
        return ['success' => false, 'message' => 'Dosya yüklenirken hata oluştu'];
    }
    
    /**
     * AI Raporu Sayfası
     */
    public function aiReport() {
        $employerId = authId();
        
        // İstatistikler
        $jobStats = $this->jobModel->getEmployerStats($employerId);
        $applicationStats = $this->applicationModel->getEmployerStats($employerId);
        
        // Raporlar için demo veriler
        $reportData = [
            'period' => 'Son 7 gün',
            'generated_at' => date('d.m.Y H:i'),
            'summary' => [
                'total_views' => $jobStats['total_views'] ?? 0,
                'total_applications' => $applicationStats['total'] ?? 0,
                'avg_match_score' => 78.5,
                'response_rate' => 45.2
            ]
        ];
        
        $this->view('employer/ai-report', [
            'title' => 'AI Haftalık Rapor',
            'report' => $reportData,
            'job_stats' => $jobStats,
            'application_stats' => $applicationStats
        ]);
    }
}
