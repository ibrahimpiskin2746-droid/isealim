<?php
/**
 * Applicant Controller
 * Başvuran paneli işlemleri
 * 
 * @package isealim
 * @author Your Name
 * @version 2.0
 */

class ApplicantController extends Controller {
    private $jobModel;
    private $applicationModel;
    private $userModel;
    private $aiService;
    private $notificationModel;
    
    /**
     * Constructor - Controller başlatma
     */
    public function __construct() {
        $this->requireApplicant();
        $this->jobModel = $this->model('Job');
        $this->applicationModel = $this->model('Application');
        $this->userModel = $this->model('User');
        $this->notificationModel = $this->model('Notification');
        $this->aiService = new AIService();
    }
    
    /**
     * Dashboard - Ana panel sayfası
     * 
     * @return void
     */
    public function dashboard() {
        try {
            $applicantId = authId();
            
            if (!$applicantId) {
                redirect('auth/login');
            }
            
            // İstatistikler
            $stats = $this->applicationModel->getApplicantStats($applicantId);
            
            // Son başvurular (en son 5 tanesi)
            $recentApplications = $this->applicationModel->getApplicantApplications($applicantId, 1);
            $recentApplications = array_slice($recentApplications, 0, 5);
            
            // Önerilen iş ilanları (yeni yayınlananlar)
            $recommendedJobs = $this->jobModel->getPublishedJobs(1, []);
            $recommendedJobs = array_slice($recommendedJobs, 0, 6);
            
            // Okunmamış bildirim sayısı
            $unreadNotifications = $this->notificationModel->getUnreadCount($applicantId);
            
            $this->view('applicant/dashboard', [
                'title' => 'Başvuran Paneli',
                'stats' => $stats ?? [],
                'recent_applications' => $recentApplications,
                'recommended_jobs' => $recommendedJobs,
                'unread_notifications' => $unreadNotifications ?? 0
            ]);
        } catch (Exception $e) {
            logMessage("Dashboard error: " . $e->getMessage(), 'error');
            setFlash('error', 'Dashboard yüklenirken bir hata oluştu');
            redirect('applicant/applications');
        }
    }
    
    /**
     * İş ilanlarına göz at - Filtreleme ve arama ile
     * 
     * @return void
     */
    public function browseJobs() {
        try {
            $page = max(1, (int)get('page', 1));
            
            // Filtreleri temizle ve güvenli hale getir
            $filters = [
                'search' => cleanInput(get('search', '')),
                'location' => cleanInput(get('location', '')),
                'employment_type' => cleanInput(get('employment_type', '')),
                'experience_level' => cleanInput(get('experience_level', '')),
                'salary_min' => (int)get('salary_min', 0),
                'salary_max' => (int)get('salary_max', 0)
            ];
            
            // Geçerli employment_type değerleri kontrolü
            $validEmploymentTypes = ['full-time', 'part-time', 'contract', 'internship', 'remote'];
            if (!empty($filters['employment_type']) && !in_array($filters['employment_type'], $validEmploymentTypes)) {
                $filters['employment_type'] = '';
            }
            
            // Geçerli experience_level değerleri kontrolü
            $validExperienceLevels = ['entry', 'mid', 'senior', 'executive'];
            if (!empty($filters['experience_level']) && !in_array($filters['experience_level'], $validExperienceLevels)) {
                $filters['experience_level'] = '';
            }
            
            $jobs = $this->jobModel->getPublishedJobs($page, $filters);
            
            // Toplam sayfa sayısı için (pagination için)
            $totalJobs = count($jobs);
            $perPage = defined('JOBS_PER_PAGE') ? JOBS_PER_PAGE : 10;
            $totalPages = ceil($totalJobs / $perPage);
            
            $this->view('applicant/browse-jobs', [
                'title' => 'İş İlanları',
                'jobs' => $jobs,
                'filters' => $filters,
                'page' => $page,
                'total_pages' => $totalPages,
                'total_jobs' => $totalJobs
            ]);
        } catch (Exception $e) {
            logMessage("Browse jobs error: " . $e->getMessage(), 'error');
            setFlash('error', 'İş ilanları yüklenirken bir hata oluştu');
            redirect('applicant/dashboard');
        }
    }
    
    /**
     * İş ilanı detayı ve başvuru formu
     * 
     * @param int $jobId İş ilanı ID'si
     * @return void
     */
    public function jobDetail($jobId) {
        try {
            $jobId = (int)$jobId;
            
            if ($jobId <= 0) {
                show404();
            }
            
            $job = $this->jobModel->getJobDetail($jobId);
            
            if (!$job || $job['status'] !== 'published') {
                setFlash('error', 'Bu iş ilanı bulunamadı veya yayından kaldırılmış');
                redirect('applicant/browse-jobs');
            }
            
            $applicantId = authId();
            
            // Daha önce başvuru yapılmış mı?
            $hasApplied = $this->applicationModel->exists([
                'job_id' => $jobId,
                'applicant_id' => $applicantId
            ]);
            
            // Form alanlarını getir
            $formFields = $this->jobModel->getFormFields($jobId);
            
            // Görüntülenme sayısını artır (async olarak yapılabilir)
            $this->jobModel->incrementViewCount($jobId);
            
            // Benzer iş ilanları öner (aynı konumda veya benzer tipte)
            $similarJobs = [];
            if (!empty($job['location'])) {
                $similarJobs = $this->jobModel->getPublishedJobs(1, [
                    'location' => $job['location'],
                    'employment_type' => $job['employment_type'] ?? ''
                ]);
                // Mevcut işi listeden çıkar
                $similarJobs = array_filter($similarJobs, function($j) use ($jobId) {
                    return $j['id'] != $jobId;
                });
                $similarJobs = array_slice($similarJobs, 0, 3);
            }
            
            $this->view('applicant/job-detail', [
                'title' => $job['title'],
                'job' => $job,
                'form_fields' => $formFields,
                'has_applied' => $hasApplied,
                'similar_jobs' => $similarJobs ?? []
            ]);
        } catch (Exception $e) {
            logMessage("Job detail error: " . $e->getMessage(), 'error');
            setFlash('error', 'İş ilanı detayı yüklenirken bir hata oluştu');
            redirect('applicant/browse-jobs');
        }
    }
    
    /**
     * AI-Powered CV Upload & Application Page
     */
    public function apply($jobId) {
        $job = $this->jobModel->getJobDetail($jobId);
        
        if (!$job || $job['status'] !== 'published') {
            show404();
        }
        
        // Daha önce başvuru yapılmış mı?
        $hasApplied = $this->applicationModel->exists([
            'job_id' => $jobId,
            'applicant_id' => authId()
        ]);
        
        if ($hasApplied) {
            setFlash('info', 'Bu pozisyona zaten başvuru yaptınız.');
            redirect('applicant/applications');
        }
        
        $this->view('applicant/apply', [
            'title' => 'Başvuru Yap - ' . $job['title'],
            'job' => $job
        ]);
    }
    
    /**
     * Başvuru gönderme - Form işleme
     * 
     * @param int $jobId İş ilanı ID'si
     * @return void
     */
    public function applyJob($jobId) {
        if (!$this->isPost()) {
            redirect('applicant/job/' . $jobId);
        }
        
        $this->verifyCsrf();
        
        try {
            $jobId = (int)$jobId;
            $applicantId = authId();
            
            if ($jobId <= 0 || !$applicantId) {
                $this->json(['success' => false, 'message' => 'Geçersiz istek'], 400);
            }
            
            $job = $this->jobModel->find($jobId);
            
            if (!$job || $job['status'] !== 'published') {
                $this->json(['success' => false, 'message' => 'İş ilanı bulunamadı veya yayından kaldırılmış'], 404);
            }
            
            // Daha önce başvuru yapılmış mı kontrol et
            $hasApplied = $this->applicationModel->exists([
                'job_id' => $jobId,
                'applicant_id' => $applicantId
            ]);
            
            if ($hasApplied) {
                $this->json(['success' => false, 'message' => 'Bu pozisyona zaten başvuru yaptınız'], 400);
            }
            
            // Form yanıtlarını topla ve validate et
            $formFields = $this->jobModel->getFormFields($jobId);
            $formResponses = [];
            $errors = [];
            
            foreach ($formFields as $field) {
                $fieldName = $field['field_name'];
                $value = post($fieldName);
                
                // Zorunlu alan kontrolü
                if ($field['is_required'] && empty($value)) {
                    $errors[$fieldName] = $field['field_label'] . ' alanı zorunludur';
                    continue;
                }
                
                // Validation rules kontrolü
                if (!empty($value) && !empty($field['validation_rules'])) {
                    $validationError = $this->validateField($value, $field['validation_rules'], $field['field_label']);
                    if ($validationError) {
                        $errors[$fieldName] = $validationError;
                        continue;
                    }
                }
                
                // Güvenli hale getir
                $formResponses[$fieldName] = is_array($value) ? cleanInput($value) : cleanInput($value);
            }
            
            if (!empty($errors)) {
                $this->json(['success' => false, 'errors' => $errors], 400);
            }
            
            // CV yükleme (opsiyonel ama önerilir)
            $cvFilePath = null;
            if (isFileUploaded('cv_file')) {
                $cvUpload = uploadFile('cv_file', CV_UPLOAD_PATH, ALLOWED_CV_TYPES, MAX_CV_SIZE);
                
                if (!$cvUpload['success']) {
                    $this->json(['success' => false, 'message' => $cvUpload['error']], 400);
                }
                
                $cvFilePath = $cvUpload['filePath'];
            }
            
            // Ön yazı (cover letter) temizleme
            $coverLetter = cleanInput(post('cover_letter', ''));
            if (strlen($coverLetter) > 5000) {
                $this->json(['success' => false, 'message' => 'Ön yazı çok uzun (maksimum 5000 karakter)'], 400);
            }
            
            // Başvuru oluştur
            $applicationData = [
                'job_id' => $jobId,
                'applicant_id' => $applicantId,
                'form_responses' => json_encode($formResponses, JSON_UNESCAPED_UNICODE),
                'cv_file_path' => $cvFilePath,
                'cover_letter' => $coverLetter,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->applicationModel->createApplication($applicationData);
            
            if ($result['success']) {
                $applicationId = $result['application_id'];
                
                // AI değerlendirmesi başlat (arka planda)
                $this->evaluateApplicationWithAI($applicationId, $job, $formResponses, $cvFilePath);
                
                // İşveren'e bildirim gönder
                $user = auth();
                $this->notificationModel->create([
                    'user_id' => $job['employer_id'],
                    'title' => 'Yeni Başvuru',
                    'message' => ($user['full_name'] ?? 'Bir aday') . ' "' . $job['title'] . '" pozisyonuna başvurdu',
                    'notification_type' => 'application',
                    'related_id' => $applicationId,
                    'related_type' => 'application',
                    'action_url' => 'employer/application/' . $applicationId
                ]);
                
                logMessage("Application created: {$applicationId} for job {$jobId} by applicant {$applicantId}", 'info');
                
                setFlash('success', 'Başvurunuz başarıyla gönderildi!');
                $this->json([
                    'success' => true, 
                    'message' => 'Başvuru başarılı', 
                    'redirect' => url('applicant/applications')
                ]);
            } else {
                $this->json(['success' => false, 'message' => $result['message'] ?? 'Başvuru oluşturulamadı'], 400);
            }
        } catch (Exception $e) {
            logMessage("Apply job error: " . $e->getMessage(), 'error');
            $this->json(['success' => false, 'message' => 'Başvuru gönderilirken bir hata oluştu'], 500);
        }
    }
    
    /**
     * AI ile başvuru değerlendirmesi
     */
    private function evaluateApplicationWithAI($applicationId, $job, $formResponses, $cvFilePath) {
        try {
            // CV'yi parse et
            $cvText = '';
            if ($cvFilePath) {
                $cvParseResult = $this->aiService->parseCV($cvFilePath);
                if ($cvParseResult['success']) {
                    $cvText = json_encode($cvParseResult['data'], JSON_UNESCAPED_UNICODE);
                }
            }
            
            // Adayı değerlendir
            $evaluation = $this->aiService->evaluateCandidate(
                $job['description'],
                $job['requirements'],
                $formResponses,
                $cvText
            );
            
            if ($evaluation['success']) {
                // Değerlendirmeyi kaydet
                $this->applicationModel->saveAIEvaluation($applicationId, $evaluation);
                
                // Yüksek skorlu başvuru ise işveren'e özel bildirim
                if ($evaluation['score'] >= 80) {
                    $this->notificationModel->create([
                        'user_id' => $job['employer_id'],
                        'title' => 'Yüksek Skorlu Başvuru!',
                        'message' => 'AI değerlendirmesine göre uygun bir aday başvurdu (Skor: ' . $evaluation['score'] . ')',
                        'notification_type' => 'application',
                        'related_id' => $applicationId,
                        'related_type' => 'application',
                        'action_url' => 'employer/application/' . $applicationId
                    ]);
                }
            }
            
        } catch (Exception $e) {
            logMessage("AI evaluation error for application {$applicationId}: " . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Başvurularım - Liste sayfası
     * 
     * @return void
     */
    public function applications() {
        try {
            $applicantId = authId();
            $page = max(1, (int)get('page', 1));
            $statusFilter = cleanInput(get('status', ''));
            
            // Geçerli status değerleri
            $validStatuses = ['pending', 'under-review', 'shortlisted', 'interviewed', 'accepted', 'rejected'];
            if (!empty($statusFilter) && !in_array($statusFilter, $validStatuses)) {
                $statusFilter = '';
            }
            
            // Tüm başvuruları getir (filtreleme için)
            $allApplications = $this->applicationModel->getApplicantApplications($applicantId, 1);
            // Daha fazla sayfa için tüm kayıtları almak gerekirse, şimdilik ilk sayfayı alıyoruz
            // TODO: Model'e status filtresi desteği eklenebilir
            
            // Status filtresi uygula (eğer varsa)
            if (!empty($statusFilter)) {
                $allApplications = array_filter($allApplications, function($app) use ($statusFilter) {
                    return isset($app['status']) && $app['status'] === $statusFilter;
                });
            }
            
            // Pagination
            $perPage = defined('APPLICATIONS_PER_PAGE') ? APPLICATIONS_PER_PAGE : 10;
            $totalApplications = count($allApplications);
            $totalPages = ceil($totalApplications / $perPage);
            $offset = ($page - 1) * $perPage;
            $applications = array_slice($allApplications, $offset, $perPage);
            
            // İstatistikler
            $stats = $this->applicationModel->getApplicantStats($applicantId);
            
            $this->view('applicant/applications', [
                'title' => 'Başvurularım',
                'applications' => $applications,
                'page' => $page,
                'total_pages' => $totalPages,
                'total_applications' => $totalApplications,
                'status_filter' => $statusFilter,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            logMessage("Applications list error: " . $e->getMessage(), 'error');
            setFlash('error', 'Başvurular yüklenirken bir hata oluştu');
            redirect('applicant/dashboard');
        }
    }
    
    /**
     * Başvuru detayı
     * 
     * @param int $applicationId Başvuru ID'si
     * @return void
     */
    public function applicationDetail($applicationId) {
        try {
            $applicationId = (int)$applicationId;
            $applicantId = authId();
            
            if ($applicationId <= 0) {
                show404();
            }
            
            $application = $this->applicationModel->getApplicationDetail($applicationId);
            
            if (!$application || $application['applicant_id'] != $applicantId) {
                setFlash('error', 'Bu başvuruya erişim yetkiniz yok');
                redirect('applicant/applications');
            }
            
            // Form alanlarını al
            $formFields = $this->jobModel->getFormFields($application['job_id']);
            
            // Form yanıtlarını decode et
            $formResponses = [];
            if (!empty($application['form_responses'])) {
                $formResponses = json_decode($application['form_responses'], true) ?? [];
            }
            
            // AI değerlendirmesini decode et
            $aiEvaluation = [];
            if (!empty($application['ai_evaluation'])) {
                $aiEvaluation = json_decode($application['ai_evaluation'], true) ?? [];
            }
            
            $this->view('applicant/application-detail', [
                'title' => 'Başvuru Detayı',
                'application' => $application,
                'form_fields' => $formFields,
                'form_responses' => $formResponses,
                'ai_evaluation' => $aiEvaluation
            ]);
        } catch (Exception $e) {
            logMessage("Application detail error: " . $e->getMessage(), 'error');
            setFlash('error', 'Başvuru detayı yüklenirken bir hata oluştu');
            redirect('applicant/applications');
        }
    }
    
    /**
     * Başvuruyu iptal et / geri çek
     * 
     * @param int $applicationId Başvuru ID'si
     * @return void
     */
    public function withdrawApplication($applicationId) {
        if (!$this->isPost()) {
            redirect('applicant/applications');
        }
        
        $this->verifyCsrf();
        
        try {
            $applicationId = (int)$applicationId;
            $applicantId = authId();
            
            $application = $this->applicationModel->find($applicationId);
            
            if (!$application || $application['applicant_id'] != $applicantId) {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Başvuru bulunamadı'], 404);
                }
                setFlash('error', 'Başvuru bulunamadı');
                redirect('applicant/applications');
            }
            
            // Sadece pending veya under-review durumundaki başvurular iptal edilebilir
            if (!in_array($application['status'], ['pending', 'under-review'])) {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Bu başvuru iptal edilemez'], 400);
                }
                setFlash('error', 'Bu başvuru iptal edilemez');
                redirect('applicant/applications');
            }
            
            // Başvuruyu iptal et
            $result = $this->applicationModel->updateStatus($applicationId, 'withdrawn', 'Başvuran tarafından iptal edildi');
            
            if ($result) {
                // İşveren'e bildirim gönder
                $job = $this->jobModel->find($application['job_id']);
                if ($job) {
                    $this->notificationModel->create([
                        'user_id' => $job['employer_id'],
                        'title' => 'Başvuru İptal Edildi',
                        'message' => auth()['full_name'] . ' başvurusunu geri çekti',
                        'notification_type' => 'application',
                        'related_id' => $applicationId,
                        'related_type' => 'application',
                        'action_url' => 'employer/application/' . $applicationId
                    ]);
                }
                
                logMessage("Application withdrawn: {$applicationId} by applicant {$applicantId}", 'info');
                
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Başvuru iptal edildi']);
                }
                setFlash('success', 'Başvurunuz iptal edildi');
            } else {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Başvuru iptal edilemedi'], 500);
                }
                setFlash('error', 'Başvuru iptal edilemedi');
            }
            
            redirect('applicant/applications');
        } catch (Exception $e) {
            logMessage("Withdraw application error: " . $e->getMessage(), 'error');
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Bir hata oluştu'], 500);
            }
            setFlash('error', 'Bir hata oluştu');
            redirect('applicant/applications');
        }
    }
    
    /**
     * Şifre değiştirme
     * 
     * @return void
     */
    public function changePassword() {
        if (!$this->isPost()) {
            redirect('applicant/profile');
        }
        
        $this->verifyCsrf();
        
        try {
            $applicantId = authId();
            $currentPassword = post('current_password');
            $newPassword = post('new_password');
            $confirmPassword = post('confirm_password');
            
            // Validasyon
            if (empty($currentPassword)) {
                setFlash('error', 'Mevcut şifrenizi giriniz');
                redirect('applicant/profile');
            }
            
            if (empty($newPassword)) {
                setFlash('error', 'Yeni şifrenizi giriniz');
                redirect('applicant/profile');
            }
            
            if ($newPassword !== $confirmPassword) {
                setFlash('error', 'Yeni şifreler eşleşmiyor');
                redirect('applicant/profile');
            }
            
            $minLength = defined('PASSWORD_MIN_LENGTH') ? PASSWORD_MIN_LENGTH : 6;
            if (strlen($newPassword) < $minLength) {
                setFlash('error', 'Şifre en az ' . $minLength . ' karakter olmalıdır');
                redirect('applicant/profile');
            }
            
            // Güçlü şifre kontrolü (opsiyonel)
            if (!preg_match('/[A-Z]/', $newPassword) || !preg_match('/[a-z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
                setFlash('error', 'Şifre en az bir büyük harf, bir küçük harf ve bir rakam içermelidir');
                redirect('applicant/profile');
            }
            
            $result = $this->userModel->changePassword($applicantId, $currentPassword, $newPassword);
            
            if ($result['success']) {
                setFlash('success', 'Şifreniz başarıyla güncellendi');
                logMessage("Password changed for applicant {$applicantId}", 'info');
            } else {
                setFlash('error', $result['message'] ?? 'Şifre değiştirilemedi');
            }
            
            redirect('applicant/profile');
        } catch (Exception $e) {
            logMessage("Change password error: " . $e->getMessage(), 'error');
            setFlash('error', 'Şifre değiştirilirken bir hata oluştu');
            redirect('applicant/profile');
        }
    }
    
    /**
     * Bildirimler - Liste sayfası
     * 
     * @return void
     */
    public function notifications() {
        try {
            $applicantId = authId();
            $page = max(1, (int)get('page', 1));
            
            $limit = 20;
            $notifications = $this->notificationModel->getUserNotifications($applicantId, $limit);
            $unreadCount = $this->notificationModel->getUnreadCount($applicantId);
            
            $this->view('applicant/notifications', [
                'title' => 'Bildirimler',
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'page' => $page
            ]);
        } catch (Exception $e) {
            logMessage("Notifications error: " . $e->getMessage(), 'error');
            setFlash('error', 'Bildirimler yüklenirken bir hata oluştu');
            redirect('applicant/dashboard');
        }
    }
    
    /**
     * Bildirimi okundu işaretle
     * 
     * @param int $notificationId Bildirim ID'si
     * @return void
     */
    public function markNotificationRead($notificationId) {
        try {
            $notificationId = (int)$notificationId;
            $applicantId = authId();
            
            if ($notificationId <= 0) {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Geçersiz bildirim'], 400);
                }
                back();
            }
            
            // Bildirimin kullanıcıya ait olduğunu kontrol et
            $notification = $this->notificationModel->find($notificationId);
            if (!$notification || $notification['user_id'] != $applicantId) {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Yetkisiz erişim'], 403);
                }
                back();
            }
            
            $result = $this->notificationModel->markAsRead($notificationId);
            
            if ($this->isAjax()) {
                $this->json(['success' => $result]);
            } else {
                back();
            }
        } catch (Exception $e) {
            logMessage("Mark notification read error: " . $e->getMessage(), 'error');
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Bir hata oluştu'], 500);
            }
            back();
        }
    }
    
    /**
     * Tüm bildirimleri okundu işaretle
     * 
     * @return void
     */
    public function markAllNotificationsRead() {
        if (!$this->isPost()) {
            redirect('applicant/notifications');
        }
        
        $this->verifyCsrf();
        
        try {
            $applicantId = authId();
            $result = $this->notificationModel->markAllAsRead($applicantId);
            
            if ($this->isAjax()) {
                $this->json(['success' => $result]);
            } else {
                setFlash('success', 'Tüm bildirimler okundu olarak işaretlendi');
                redirect('applicant/notifications');
            }
        } catch (Exception $e) {
            logMessage("Mark all notifications read error: " . $e->getMessage(), 'error');
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Bir hata oluştu'], 500);
            }
            setFlash('error', 'Bir hata oluştu');
            redirect('applicant/notifications');
        }
    }
    
    /**
     * Alan validasyonu yardımcı metodu
     * 
     * @param mixed $value Değer
     * @param string $rules Validasyon kuralları
     * @param string $fieldLabel Alan etiketi
     * @return string|null Hata mesajı veya null
     */
    private function validateField($value, $rules, $fieldLabel) {
        $ruleList = explode('|', $rules);
        
        foreach ($ruleList as $rule) {
            if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $fieldLabel . ' geçerli bir e-posta adresi olmalıdır';
            }
            
            if ($rule === 'numeric' && !is_numeric($value)) {
                return $fieldLabel . ' sayısal olmalıdır';
            }
            
            if ($rule === 'url' && !filter_var($value, FILTER_VALIDATE_URL)) {
                return $fieldLabel . ' geçerli bir URL olmalıdır';
            }
            
            if (str_starts_with($rule, 'min:')) {
                $min = (int)substr($rule, 4);
                if (strlen($value) < $min) {
                    return $fieldLabel . " en az {$min} karakter olmalıdır";
                }
            }
            
            if (str_starts_with($rule, 'max:')) {
                $max = (int)substr($rule, 4);
                if (strlen($value) > $max) {
                    return $fieldLabel . " en fazla {$max} karakter olmalıdır";
                }
            }
        }
        
        return null;
    }
    
    /**
     * Profil görüntüleme
     */
    public function profile() {
        $userId = authId();
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            redirect('auth/login');
        }
        
        $this->view('applicant/profile', [
            'title' => 'Profilim',
            'user' => $user
        ]);
    }
    
    /**
     * Profil güncelleme
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('applicant/profile');
        }
        
        $userId = authId();
        $user = $this->userModel->find($userId);
        
        // Form verilerini al
        $data = [
            'full_name' => cleanInput(post('full_name')),
            'email' => cleanInput(post('email')),
            'phone' => cleanInput(post('phone')),
            'date_of_birth' => cleanInput(post('date_of_birth')),
            'gender' => cleanInput(post('gender')),
            'nationality' => cleanInput(post('nationality')),
            'location' => cleanInput(post('location')),
            'bio' => cleanInput(post('bio')),
            'linkedin_url' => cleanInput(post('linkedin_url')),
            'github_url' => cleanInput(post('github_url')),
            'portfolio_url' => cleanInput(post('portfolio_url')),
            'skills' => cleanInput(post('skills')),
            'languages' => cleanInput(post('languages')),
            'experience_years' => (int)post('experience_years'),
            'education' => cleanInput(post('education')),
            'certifications' => cleanInput(post('certifications')),
            'work_experience' => cleanInput(post('work_experience')),
            'projects' => cleanInput(post('projects')),
            'current_position' => cleanInput(post('current_position')),
            'expected_salary' => cleanInput(post('expected_salary')),
            'work_preference' => cleanInput(post('work_preference')),
            'availability' => cleanInput(post('availability'))
        ];
        
        // Validasyon
        $errors = [];
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Ad Soyad zorunludur';
        }
        
        if (!empty($data['linkedin_url']) && !filter_var($data['linkedin_url'], FILTER_VALIDATE_URL)) {
            $errors['linkedin_url'] = 'LinkedIn URL geçersiz';
        }
        
        if (!empty($data['github_url']) && !filter_var($data['github_url'], FILTER_VALIDATE_URL)) {
            $errors['github_url'] = 'GitHub URL geçersiz';
        }
        
        if (!empty($data['portfolio_url']) && !filter_var($data['portfolio_url'], FILTER_VALIDATE_URL)) {
            $errors['portfolio_url'] = 'Portfolio URL geçersiz';
        }
        
        // Profil fotoğrafı yükleme
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadProfileImage($_FILES['profile_image']);
            if ($uploadResult['success']) {
                $data['profile_image'] = $uploadResult['path'];
            } else {
                $errors['profile_image'] = $uploadResult['message'];
            }
        }
        
        // CV yükleme
        if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadCV($_FILES['cv_file']);
            if ($uploadResult['success']) {
                $data['cv_path'] = $uploadResult['path'];
            } else {
                $errors['cv_file'] = $uploadResult['message'];
            }
        }
        
        if (!empty($errors)) {
            $this->view('applicant/profile', [
                'title' => 'Profilim',
                'user' => $user,
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }
        
        // Profili güncelle
        $updated = $this->userModel->updateProfile($userId, $data);
        
        if ($updated) {
            setFlash('success', 'Profiliniz başarıyla güncellendi');
        } else {
            setFlash('error', 'Profil güncellenirken hata oluştu');
        }
        
        redirect('applicant/profile');
    }
    
    /**
     * Profil fotoğrafı yükleme
     */
    private function uploadProfileImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Sadece JPG, JPEG ve PNG formatları kabul edilir'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Dosya boyutu en fazla 2MB olabilir'];
        }
        
        $uploadDir = APP_PATH . '/../storage/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . authId() . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'path' => 'storage/uploads/profiles/' . $filename];
        }
        
        return ['success' => false, 'message' => 'Dosya yüklenirken hata oluştu'];
    }
    
    /**
     * CV yükleme
     */
    private function uploadCV($file) {
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Sadece PDF, DOC ve DOCX formatları kabul edilir'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'CV dosya boyutu en fazla 5MB olabilir'];
        }
        
        $uploadDir = APP_PATH . '/../storage/uploads/cvs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'cv_' . authId() . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'path' => 'storage/uploads/cvs/' . $filename];
        }
        
        return ['success' => false, 'message' => 'CV yüklenirken hata oluştu'];
    }
}
