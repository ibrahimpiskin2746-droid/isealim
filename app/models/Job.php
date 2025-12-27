<?php
/**
 * Job Model
 * İş ilanı işlemleri
 */

class Job extends Model {
    protected $table = 'jobs';
    
    /**
     * İşveren'e ait iş ilanlarını getirir
     */
    public function getEmployerJobs($employerId, $status = null) {
        $where = ['employer_id' => $employerId];
        
        if ($status) {
            $where['status'] = $status;
        }
        
        return $this->where($where, 'created_at DESC');
    }
    
    /**
     * Yayındaki iş ilanlarını getirir
     */
    public function getPublishedJobs($page = 1, $filters = [], $limit = null) {
        $sql = "SELECT j.*, u.full_name, u.company_name, u.company_description,
                COUNT(DISTINCT a.id) as application_count
                FROM {$this->table} j
                LEFT JOIN users u ON j.employer_id = u.id
                LEFT JOIN applications a ON j.id = a.job_id
                WHERE j.status = 'published'";
        
        // Filtreleme
        if (!empty($filters['search'])) {
            $sql .= " AND (j.title LIKE :search OR j.description LIKE :search)";
        }
        
        if (!empty($filters['location'])) {
            $sql .= " AND j.location LIKE :location";
        }
        
        if (!empty($filters['employment_type'])) {
            $sql .= " AND j.employment_type = :employment_type";
        }
        
        if (!empty($filters['experience_level'])) {
            $sql .= " AND j.experience_level = :experience_level";
        }
        
        $sql .= " GROUP BY j.id ORDER BY j.published_at DESC";
        
        // Pagination
        $perPage = $limit ?? JOBS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$offset}, {$perPage}";
        
        $query = $this->db->query($sql);
        
        if (!empty($filters['search'])) {
            $query->bind(':search', '%' . $filters['search'] . '%');
        }
        
        if (!empty($filters['location'])) {
            $query->bind(':location', '%' . $filters['location'] . '%');
        }
        
        if (!empty($filters['employment_type'])) {
            $query->bind(':employment_type', $filters['employment_type']);
        }
        
        if (!empty($filters['experience_level'])) {
            $query->bind(':experience_level', $filters['experience_level']);
        }
        
        $jobs = $query->fetchAll();
        
        // Return in format expected by views
        return [
            'jobs' => $jobs,
            'page' => $page,
            'total' => count($jobs)
        ];
    }
    
    /**
     * İş ilanı detayı (ilişkili verilerle)
     */
    public function getJobDetail($jobId) {
        $sql = "SELECT j.*, u.full_name, u.company_name, u.company_description, 
                u.email as company_email, u.phone as company_phone,
                COUNT(DISTINCT a.id) as application_count
                FROM {$this->table} j
                LEFT JOIN users u ON j.employer_id = u.id
                LEFT JOIN applications a ON j.id = a.job_id
                WHERE j.id = :id
                GROUP BY j.id
                LIMIT 1";
        
        return $this->db->query($sql)->bind(':id', $jobId)->fetch();
    }
    
    /**
     * İş ilanı oluşturur
     */
    public function createJob($data) {
        return $this->create($data);
    }
    
    /**
     * İş ilanı günceller
     */
    public function updateJob($jobId, $data) {
        return $this->update($jobId, $data);
    }
    
    /**
     * İş ilanını yayınlar
     */
    public function publishJob($jobId) {
        return $this->update($jobId, [
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * İş ilanını kapatır
     */
    public function closeJob($jobId) {
        return $this->update($jobId, ['status' => 'closed']);
    }
    
    /**
     * Görüntülenme sayısını artırır
     */
    public function incrementViewCount($jobId) {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $jobId)->execute();
    }
    
    /**
     * Başvuru sayısını artırır
     */
    public function incrementApplicationCount($jobId) {
        $sql = "UPDATE {$this->table} SET application_count = application_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $jobId)->execute();
    }
    
    /**
     * Form alanlarını getirir
     */
    public function getFormFields($jobId) {
        $sql = "SELECT * FROM job_form_fields WHERE job_id = :job_id ORDER BY field_order ASC";
        return $this->db->query($sql)->bind(':job_id', $jobId)->fetchAll();
    }
    
    /**
     * Form alanlarını sil
     */
    public function deleteFormFields($jobId) {
        $sql = "DELETE FROM job_form_fields WHERE job_id = :job_id";
        return $this->db->query($sql)->bind(':job_id', $jobId)->execute();
    }
    
    /**
     * Form alanı ekler
     */
    public function addFormField($data) {
        $sql = "INSERT INTO job_form_fields 
                (job_id, field_type, field_label, field_name, field_placeholder, field_options, 
                 is_required, validation_rules, field_category, field_order, ai_generated, ai_scoring_weight)
                VALUES 
                (:job_id, :field_type, :field_label, :field_name, :field_placeholder, :field_options,
                 :is_required, :validation_rules, :field_category, :field_order, :ai_generated, :ai_scoring_weight)";
        
        $this->db->query($sql)
            ->bind(':job_id', $data['job_id'])
            ->bind(':field_type', $data['field_type'])
            ->bind(':field_label', $data['field_label'])
            ->bind(':field_name', $data['field_name'])
            ->bind(':field_placeholder', $data['field_placeholder'] ?? '')
            ->bind(':field_options', $data['field_options'] ?? null)
            ->bind(':is_required', $data['is_required'] ?? 0)
            ->bind(':validation_rules', $data['validation_rules'] ?? '')
            ->bind(':field_category', $data['field_category'] ?? 'custom')
            ->bind(':field_order', $data['field_order'] ?? 0)
            ->bind(':ai_generated', $data['ai_generated'] ?? 0)
            ->bind(':ai_scoring_weight', $data['ai_scoring_weight'] ?? 1.00)
            ->execute();
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Form alanını günceller
     */
    public function updateFormField($fieldId, $data) {
        $sql = "UPDATE job_form_fields SET ";
        $updates = [];
        
        foreach ($data as $key => $value) {
            $updates[] = "{$key} = :{$key}";
        }
        
        $sql .= implode(', ', $updates) . " WHERE id = :id";
        
        $query = $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $query->bind(":{$key}", $value);
        }
        
        $query->bind(':id', $fieldId);
        
        return $query->execute();
    }
    
    /**
     * Form alanını siler
     */
    public function deleteFormField($fieldId) {
        $sql = "DELETE FROM job_form_fields WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $fieldId)->execute();
    }
    
    /**
     * İstatistikler
     */
    public function getEmployerStats($employerId) {
        $sql = "SELECT 
                COUNT(CASE WHEN status = 'published' THEN 1 END) as active_jobs,
                COUNT(CASE WHEN status = 'draft' THEN 1 END) as draft_jobs,
                COUNT(CASE WHEN status = 'closed' THEN 1 END) as closed_jobs,
                SUM(view_count) as total_views,
                SUM(application_count) as total_applications
                FROM {$this->table}
                WHERE employer_id = :employer_id";
        
        return $this->db->query($sql)->bind(':employer_id', $employerId)->fetch();
    }
    
    /**
     * Arama yap
     */
    public function searchJobs($keyword, $page = 1, $limit = 12) {
        $sql = "SELECT j.*, u.full_name, u.company_name, u.company_description,
                COUNT(DISTINCT a.id) as application_count
                FROM {$this->table} j
                LEFT JOIN users u ON j.employer_id = u.id
                LEFT JOIN applications a ON j.id = a.job_id
                WHERE j.status = 'published'
                AND (j.title LIKE :keyword OR j.description LIKE :keyword OR j.requirements LIKE :keyword)
                GROUP BY j.id 
                ORDER BY j.published_at DESC
                LIMIT :offset, :limit";
        
        $offset = ($page - 1) * $limit;
        
        $query = $this->db->query($sql);
        $query->bind(':keyword', '%' . $keyword . '%');
        $query->bind(':offset', $offset);
        $query->bind(':limit', $limit);
        
        $jobs = $query->fetchAll();
        
        // Return in same format as getPublishedJobs
        return [
            'jobs' => $jobs,
            'page' => $page,
            'total' => count($jobs)
        ];
    }
}
