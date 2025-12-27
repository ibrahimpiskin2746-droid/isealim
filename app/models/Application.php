<?php
/**
 * Application Model
 * Başvuru işlemleri
 */

class Application extends Model {
    protected $table = 'applications';
    
    /**
     * Başvuru oluşturur
     */
    public function createApplication($data) {
        // Aynı iş ilanına daha önce başvuru yapılmış mı kontrol et
        $exists = $this->exists([
            'job_id' => $data['job_id'],
            'applicant_id' => $data['applicant_id']
        ]);
        
        if ($exists) {
            return ['success' => false, 'message' => 'Bu iş ilanına daha önce başvurdunuz'];
        }
        
        $applicationId = $this->create($data);
        
        if ($applicationId) {
            // İş ilanının başvuru sayısını artır
            $jobModel = new Job();
            $jobModel->incrementApplicationCount($data['job_id']);
            
            return ['success' => true, 'application_id' => $applicationId];
        }
        
        return ['success' => false, 'message' => 'Başvuru oluşturulamadı'];
    }
    
    /**
     * Başvuran'ın başvurularını getirir
     */
    public function getApplicantApplications($applicantId, $page = 1) {
        $sql = "SELECT a.*, j.title as job_title, j.location, j.employment_type,
                u.company_name, u.full_name as employer_name
                FROM {$this->table} a
                LEFT JOIN jobs j ON a.job_id = j.id
                LEFT JOIN users u ON j.employer_id = u.id
                WHERE a.applicant_id = :applicant_id
                ORDER BY a.created_at DESC";
        
        $perPage = APPLICATIONS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$offset}, {$perPage}";
        
        return $this->db->query($sql)->bind(':applicant_id', $applicantId)->fetchAll();
    }
    
    /**
     * İş ilanına gelen başvuruları getirir
     */
    public function getJobApplications($jobId, $orderBy = 'ai_score DESC') {
        $sql = "SELECT a.*, u.full_name, u.email, u.phone, u.profile_image
                FROM {$this->table} a
                LEFT JOIN users u ON a.applicant_id = u.id
                WHERE a.job_id = :job_id
                ORDER BY {$orderBy}";
        
        return $this->db->query($sql)->bind(':job_id', $jobId)->fetchAll();
    }
    
    /**
     * İşveren'in tüm başvurularını getirir
     */
    public function getEmployerApplications($employerId, $filters = [], $page = 1) {
        $sql = "SELECT a.*, j.title as job_title, u.full_name, u.email
                FROM {$this->table} a
                LEFT JOIN jobs j ON a.job_id = j.id
                LEFT JOIN users u ON a.applicant_id = u.id
                WHERE j.employer_id = :employer_id";
        
        // Filtreleme
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
        }
        
        if (!empty($filters['job_id'])) {
            $sql .= " AND a.job_id = :job_id";
        }
        
        if (!empty($filters['min_score'])) {
            $sql .= " AND a.ai_score >= :min_score";
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        
        $perPage = APPLICATIONS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$offset}, {$perPage}";
        
        $query = $this->db->query($sql)->bind(':employer_id', $employerId);
        
        if (!empty($filters['status'])) {
            $query->bind(':status', $filters['status']);
        }
        
        if (!empty($filters['job_id'])) {
            $query->bind(':job_id', $filters['job_id']);
        }
        
        if (!empty($filters['min_score'])) {
            $query->bind(':min_score', $filters['min_score']);
        }
        
        return $query->fetchAll();
    }
    
    /**
     * Başvuru detayı
     */
    public function getApplicationDetail($applicationId) {
        $sql = "SELECT a.*, 
                j.title as job_title, j.description as job_description, j.requirements,
                u.full_name, u.email, u.phone, u.profile_image,
                emp.company_name, emp.full_name as employer_name
                FROM {$this->table} a
                LEFT JOIN jobs j ON a.job_id = j.id
                LEFT JOIN users u ON a.applicant_id = u.id
                LEFT JOIN users emp ON j.employer_id = emp.id
                WHERE a.id = :id
                LIMIT 1";
        
        return $this->db->query($sql)->bind(':id', $applicationId)->fetch();
    }
    
    /**
     * Başvuru durumunu günceller
     */
    public function updateStatus($applicationId, $status, $notes = null) {
        $data = ['status' => $status];
        
        if ($notes) {
            $data['employer_notes'] = $notes;
        }
        
        $result = $this->update($applicationId, $data);
        
        if ($result) {
            // Başvuran'a bildirim gönder
            $application = $this->find($applicationId);
            $notificationModel = new Notification();
            
            $statusMessages = [
                'under-review' => 'Başvurunuz inceleniyor',
                'shortlisted' => 'Kısa listeye alındınız',
                'interviewed' => 'Görüşme için davet edildiniz',
                'accepted' => 'Başvurunuz kabul edildi',
                'rejected' => 'Başvurunuz değerlendirildi'
            ];
            
            $message = $statusMessages[$status] ?? 'Başvuru durumunuz güncellendi';
            
            $notificationModel->create([
                'user_id' => $application['applicant_id'],
                'title' => 'Başvuru Durumu Güncellendi',
                'message' => $message,
                'notification_type' => 'status-change',
                'related_id' => $applicationId,
                'related_type' => 'application',
                'action_url' => 'applicant/applications/' . $applicationId
            ]);
        }
        
        return $result;
    }
    
    /**
     * AI değerlendirmesini kaydeder
     */
    public function saveAIEvaluation($applicationId, $evaluation) {
        return $this->update($applicationId, [
            'ai_score' => $evaluation['score'],
            'ai_evaluation' => json_encode($evaluation['details']),
            'ai_strengths' => $evaluation['strengths'],
            'ai_weaknesses' => $evaluation['weaknesses'],
            'ai_summary' => $evaluation['summary']
        ]);
    }
    
    /**
     * Başvuruyu görüldü olarak işaretle
     */
    public function markAsViewed($applicationId) {
        return $this->update($applicationId, [
            'viewed_by_employer' => 1,
            'viewed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * İstatistikler - Başvuran için
     */
    public function getApplicantStats($applicantId) {
        $sql = "SELECT 
                COUNT(*) as total_applications,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                COUNT(CASE WHEN status = 'under-review' THEN 1 END) as under_review,
                COUNT(CASE WHEN status = 'shortlisted' THEN 1 END) as shortlisted,
                COUNT(CASE WHEN status = 'interviewed' THEN 1 END) as interviewed,
                COUNT(CASE WHEN status = 'accepted' THEN 1 END) as accepted,
                COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected,
                AVG(ai_score) as avg_score
                FROM {$this->table}
                WHERE applicant_id = :applicant_id";
        
        return $this->db->query($sql)->bind(':applicant_id', $applicantId)->fetch();
    }
    
    /**
     * İstatistikler - İşveren için
     */
    public function getEmployerStats($employerId) {
        $sql = "SELECT 
                COUNT(DISTINCT a.id) as total_applications,
                COUNT(CASE WHEN a.status = 'pending' THEN 1 END) as pending,
                COUNT(CASE WHEN a.status = 'shortlisted' THEN 1 END) as shortlisted,
                COUNT(CASE WHEN a.status = 'accepted' THEN 1 END) as accepted,
                AVG(a.ai_score) as avg_score
                FROM {$this->table} a
                LEFT JOIN jobs j ON a.job_id = j.id
                WHERE j.employer_id = :employer_id";
        
        return $this->db->query($sql)->bind(':employer_id', $employerId)->fetch();
    }
    
    /**
     * En iyi skorlu başvuruları getirir
     */
    public function getTopApplications($jobId, $limit = 10) {
        $sql = "SELECT a.*, u.full_name, u.email
                FROM {$this->table} a
                LEFT JOIN users u ON a.applicant_id = u.id
                WHERE a.job_id = :job_id AND a.ai_score IS NOT NULL
                ORDER BY a.ai_score DESC
                LIMIT {$limit}";
        
        return $this->db->query($sql)->bind(':job_id', $jobId)->fetchAll();
    }
}
