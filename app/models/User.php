<?php
/**
 * User Model
 * Kullanıcı işlemleri
 */

class User extends Model {
    protected $table = 'users';
    
    /**
     * E-posta ile kullanıcı bulur
     */
    public function findByEmail($email) {
        return $this->findWhere(['email' => $email]);
    }
    
    /**
     * Kullanıcı kaydı oluşturur
     */
    public function register($data) {
        // Şifreyi hashle
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        
        // Doğrulama token'ı oluştur
        $data['verification_token'] = generateRandomString(64);
        
        return $this->create($data);
    }
    
    /**
     * Giriş kontrolü
     */
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Aktif mi kontrol et
            if (!$user['is_active']) {
                return ['success' => false, 'message' => 'Hesabınız aktif değil'];
            }
            
            // Son giriş zamanını güncelle
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Oturum bilgilerini ayarla
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;
            
            // Activity log
            $this->logActivity($user['id'], 'login', 'users', $user['id'], 'Kullanıcı giriş yaptı');
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'E-posta veya şifre hatalı'];
    }
    
    /**
     * Çıkış yapar
     */
    public function logout() {
        $userId = authId();
        
        if ($userId) {
            $this->logActivity($userId, 'logout', 'users', $userId, 'Kullanıcı çıkış yaptı');
        }
        
        session_destroy();
    }
    
    /**
     * Şifre sıfırlama token'ı oluşturur
     */
    public function createPasswordResetToken($email) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Kullanıcı bulunamadı'];
        }
        
        $token = generateRandomString(64);
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ]);
        
        return ['success' => true, 'token' => $token, 'user' => $user];
    }
    
    /**
     * Şifre sıfırlama
     */
    public function resetPassword($token, $newPassword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE reset_token = :token 
                AND reset_token_expiry > NOW() 
                LIMIT 1";
        
        $user = $this->db->query($sql)->bind(':token', $token)->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Geçersiz veya süresi dolmuş token'];
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $this->update($user['id'], [
            'password_hash' => $hashedPassword,
            'reset_token' => null,
            'reset_token_expiry' => null
        ]);
        
        return ['success' => true];
    }
    
    /**
     * E-posta doğrulama
     */
    public function verifyEmail($token) {
        $user = $this->findWhere(['verification_token' => $token]);
        
        if (!$user) {
            return false;
        }
        
        $this->update($user['id'], [
            'is_verified' => 1,
            'verification_token' => null
        ]);
        
        return true;
    }
    
    /**
     * Şifre değiştirme
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        $user = $this->find($userId);
        
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Mevcut şifre hatalı'];
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->update($userId, ['password_hash' => $hashedPassword]);
        
        return ['success' => true];
    }
    
    /**
     * İşveren listesi
     */
    public function getEmployers($page = 1, $perPage = ITEMS_PER_PAGE) {
        return $this->paginate($page, $perPage, ['user_type' => 'employer', 'is_active' => 1]);
    }
    
    /**
     * Başvuran listesi
     */
    public function getApplicants($page = 1, $perPage = ITEMS_PER_PAGE) {
        return $this->paginate($page, $perPage, ['user_type' => 'applicant', 'is_active' => 1]);
    }
    
    /**
     * Profil güncelleme
     */
    public function updateProfile($userId, $data) {
        // Sadece izin verilen alanları güncelle
        $allowedFields = [
            // Ortak alanlar
            'full_name', 'email', 'phone', 'profile_image',
            // İş başvuran alanları
            'location', 'bio', 'date_of_birth', 'gender', 'nationality',
            'linkedin_url', 'github_url', 'portfolio_url',
            'skills', 'languages', 'experience_years', 'education',
            'certifications', 'work_experience', 'projects',
            'current_position', 'expected_salary', 'work_preference',
            'availability', 'cv_path',
            // İşveren alanları
            'company_name', 'company_description', 'company_website',
            'company_address', 'company_size', 'company_industry',
            'company_founded_year', 'twitter_url', 'facebook_url'
        ];
        
        $updateData = [];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        return $this->update($userId, $updateData);
    }
    
    /**
     * Activity log kaydı
     */
    private function logActivity($userId, $action, $entityType, $entityId, $description) {
        $sql = "INSERT INTO activity_logs 
                (user_id, action, entity_type, entity_id, description, ip_address, user_agent) 
                VALUES (:user_id, :action, :entity_type, :entity_id, :description, :ip, :ua)";
        
        $this->db->query($sql)
            ->bind(':user_id', $userId)
            ->bind(':action', $action)
            ->bind(':entity_type', $entityType)
            ->bind(':entity_id', $entityId)
            ->bind(':description', $description)
            ->bind(':ip', $_SERVER['REMOTE_ADDR'] ?? '')
            ->bind(':ua', $_SERVER['HTTP_USER_AGENT'] ?? '')
            ->execute();
    }
}
