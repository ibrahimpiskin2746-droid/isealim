<?php
/**
 * Home Controller
 * Ana sayfa ve genel sayfalar
 */

class HomeController extends Controller {
    private $jobModel;
    
    public function __construct() {
        $this->jobModel = $this->model('Job');
    }
    
    /**
     * Ana sayfa
     */
    public function index() {
        // Yayındaki son ilanları getir
        $recentJobsData = $this->jobModel->getPublishedJobs(1);
        $recentJobs = $recentJobsData['jobs'] ?? [];
        
        // Eğer veritabanında iş ilanı yoksa, demo ilanlar ekle
        if (empty($recentJobs)) {
            $recentJobs = $this->getDemoJobs();
        }
        
        // İstatistikler
        $stats = [
            'total_jobs' => $this->jobModel->count(['status' => 'published']) ?: count($recentJobs),
            'total_employers' => (new User())->count(['user_type' => 'employer', 'is_active' => 1]) ?: 50,
            'total_applicants' => (new User())->count(['user_type' => 'applicant']) ?: 1200
        ];
        
        $this->view('home/index', [
            'title' => 'Ana Sayfa',
            'jobs' => $recentJobs,
            'stats' => $stats
        ]);
    }
    
    /**
     * İş ilanları listesi
     */
    public function jobs() {
        $page = get('page', 1);
        $filters = [
            'search' => get('search', ''),
            'location' => get('location', ''),
            'employment_type' => get('employment_type', ''),
            'experience_level' => get('experience_level', '')
        ];
        
        $jobsData = $this->jobModel->getPublishedJobs($page, $filters);
        $jobs = $jobsData['jobs'] ?? [];
        
        // Eğer veritabanında iş ilanı yoksa, demo ilanlar ekle
        if (empty($jobs)) {
            $jobs = $this->getDemoJobs();
        }
        
        $this->view('home/jobs', [
            'title' => 'İş İlanları',
            'jobs' => $jobs,
            'total' => $jobsData['total'] ?? count($jobs),
            'filters' => $filters,
            'page' => $page
        ]);
    }
    
    /**
     * Demo iş ilanları - Veritabanında iş olmadığında gösterilir
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
                'description' => 'Modern web teknolojileri (React, Vue.js, TypeScript) konusunda deneyimli, kullanıcı deneyimine önem veren frontend developer arayışımız devam ediyor.',
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
    
    /**
     * İş Başvuru Sayfası - Özel başvuru merkezi sayfası
     */
    public function apply() {
        // Debug - Bu satırı görüyorsanız metod çalışıyor demektir
        error_log("HomeController::apply() çağrıldı");
        
        // Öne çıkan iş ilanlarını getir (en yeni ve popüler)
        $featuredJobsData = $this->jobModel->getPublishedJobs(1, [], 6);
        $featuredJobs = $featuredJobsData['jobs'] ?? [];
        
        $viewPath = 'home/application-form';
        error_log("View path: " . $viewPath);
        
        $this->view($viewPath, [
            'title' => 'İş Başvuru Merkezi',
            'featured_jobs' => $featuredJobs
        ]);
    }
    
    /**
     * İş ilanı detayı (kısa URL)
     */
    public function job($jobId) {
        return $this->jobDetail($jobId);
    }
    
    /**
     * İş ilanı detayı
     */
    public function jobDetail($jobId) {
        // Demo ilan kontrolü
        if (strpos($jobId, 'demo-') === 0) {
            $job = $this->getDemoJobDetail($jobId);
            if (!$job) {
                show404();
            }
            
            $this->view('home/job-detail', [
                'title' => $job['title'],
                'job' => $job,
                'form_fields' => []
            ]);
            return;
        }
        
        $job = $this->jobModel->getJobDetail($jobId);
        
        if (!$job || $job['status'] !== 'published') {
            show404();
        }
        
        // Görüntülenme sayısını artır
        $this->jobModel->incrementViewCount($jobId);
        
        // Form alanlarını getir
        $formFields = $this->jobModel->getFormFields($jobId);
        
        $this->view('home/job-detail', [
            'title' => $job['title'],
            'job' => $job,
            'form_fields' => $formFields
        ]);
    }
    
    /**
     * Demo iş ilanı detayı getir
     */
    private function getDemoJobDetail($jobId) {
        $demoJobs = $this->getDemoJobs();
        
        foreach ($demoJobs as $job) {
            if ($job['id'] === $jobId) {
                // İlan türüne göre özelleştirilmiş detaylar
                switch($jobId) {
                    case 'demo-1':
                        $job['requirements'] = 'React, Vue.js, TypeScript, HTML5, CSS3, JavaScript ES6+, Responsive Design, Git, REST API, Modern Frontend Tools';
                        $job['responsibilities'] = 'Modern web uygulamaları geliştirme, Component-based mimari oluşturma, Takım ile işbirliği içinde çalışma, Kod kalitesi ve performans optimizasyonu sağlama, UI/UX tasarımları ile uyumlu arayüz geliştirme';
                        $job['benefits'] = 'Sağlık sigortası, Esnek çalışma saatleri, Uzaktan çalışma imkanı, Eğitim ve kişisel gelişim desteği, Yemek kartı, Performans primi';
                        break;
                    case 'demo-2':
                        $job['requirements'] = 'Node.js, Express.js, MongoDB, REST API, Mikroservis Mimarisi, Docker, Redis, Git';
                        $job['responsibilities'] = 'Backend servisleri geliştirme, API tasarımı ve implementasyonu, Veritabanı optimizasyonu, Mikroservis mimarisi kurgusu, Teknik dokümantasyon hazırlama';
                        $job['benefits'] = 'Özel sağlık sigortası, Esnek mesai, Hybrid çalışma modeli, Eğitim bütçesi, Spor salonu üyeliği';
                        break;
                    case 'demo-3':
                        $job['requirements'] = 'JavaScript, React/Vue.js, Node.js, MongoDB/PostgreSQL, RESTful API, Git, Agile metodoloji';
                        $job['responsibilities'] = 'Full stack uygulama geliştirme, Frontend ve backend entegrasyonu, Veritabanı yönetimi, Agile sprint süreçlerine katılım';
                        $job['benefits'] = 'Sağlık sigortası, Esnek çalışma, Remote çalışma, Teknoloji eğitim programları, Takım aktiviteleri';
                        break;
                    case 'demo-4':
                        $job['requirements'] = 'Flutter, Dart, Firebase, REST API, iOS/Android platform bilgisi, Git, State Management (Provider/Riverpod)';
                        $job['responsibilities'] = 'Cross-platform mobil uygulama geliştirme, iOS ve Android platformları için optimize edilmiş kod yazma, API entegrasyonları, Uygulama performans optimizasyonu';
                        $job['benefits'] = 'Sağlık sigortası, Esnek mesai, Remote çalışma, Eğitim desteği, Yıllık teknoloji bütçesi';
                        break;
                    case 'demo-5':
                        $job['requirements'] = 'Docker, Kubernetes, CI/CD (Jenkins/GitLab), AWS/Azure/GCP, Linux, Terraform, Monitoring Tools, Git';
                        $job['responsibilities'] = 'DevOps süreçlerini yönetme, CI/CD pipeline kurulumu, Container orchestration, Infrastructure as Code, Sistem monitoring ve alerting';
                        $job['benefits'] = 'Üst seviye sağlık sigortası, Tam remote çalışma, Konferans katılım desteği, Sertifikasyon programları, Yüksek performans primi';
                        break;
                    case 'demo-6':
                        $job['requirements'] = 'Figma, Adobe XD, Sketch, UI/UX Design, Prototyping, User Research, Design Systems, Responsive Design';
                        $job['responsibilities'] = 'Kullanıcı arayüzü tasarımı, UX araştırması ve analizi, Prototip oluşturma, Design system yönetimi, Frontend ekibi ile işbirliği';
                        $job['benefits'] = 'Sağlık sigortası, Esnek çalışma saatleri, Creative cloud lisansı, Design konferansları, Uzaktan çalışma';
                        break;
                    case 'demo-7':
                        $job['requirements'] = 'Python, Machine Learning, Deep Learning, TensorFlow/PyTorch, Pandas, NumPy, SQL, Data Visualization, Git';
                        $job['responsibilities'] = 'ML modelleri geliştirme, Veri analizi ve işleme, Model deployment, Veri görselleştirme, Araştırma ve inovasyon';
                        $job['benefits'] = 'Premium sağlık sigortası, Araştırma bütçesi, Konferans ve yayın destekleri, GPU iş istasyonu, Yüksek maaş';
                        break;
                    case 'demo-8':
                        $job['requirements'] = 'Agile/Scrum, PMP sertifikası, Yazılım projesi yönetimi, JIRA, Risk yönetimi, Stakeholder yönetimi';
                        $job['responsibilities'] = 'Proje planlama ve yönetimi, Sprint organizasyonu, Takım koordinasyonu, Risk analizi ve yönetimi, Raporlama';
                        $job['benefits'] = 'Sağlık sigortası, Araç tahsisi, Sertifikasyon destekleri, Performans bonusu, Esnek çalışma';
                        break;
                    default:
                        $job['requirements'] = 'İlgili alanda deneyim, Problem çözme becerisi, Takım çalışması';
                        $job['responsibilities'] = 'Proje geliştirme, Takım işbirliği, Kalite güvence';
                        $job['benefits'] = 'Sağlık sigortası, Esnek çalışma saatleri, Eğitim desteği';
                }
                
                $job['application_deadline'] = date('Y-m-d', strtotime('+30 days'));
                $job['view_count'] = rand(150, 600);
                $job['application_count'] = rand(15, 85);
                
                return $job;
            }
        }
        
        return null;
    }
    
    /**
     * Hakkımızda
     */
    public function about() {
        $this->view('home/about', [
            'title' => 'Hakkımızda'
        ]);
    }
    
    /**
     * İletişim
     */
    public function contact() {
        if ($this->isPost()) {
            // İletişim formu işleme
            $name = cleanInput(post('name'));
            $email = cleanInput(post('email'));
            $subject = cleanInput(post('subject'));
            $message = cleanInput(post('message'));
            
            // E-posta gönder veya veritabanına kaydet
            setFlash('success', 'Mesajınız alındı. En kısa sürede dönüş yapacağız.');
            redirect('contact');
        }
        
        $this->view('home/contact', [
            'title' => 'İletişim'
        ]);
    }
}
