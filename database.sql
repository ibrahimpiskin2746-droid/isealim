-- AI Destekli İş Başvuru ve Değerlendirme Platformu
-- Veritabanı Şeması
-- Oluşturulma Tarihi: 26.12.2025

CREATE DATABASE IF NOT EXISTS job_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_platform;

-- Kullanıcılar Tablosu
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    user_type ENUM('employer', 'applicant', 'admin') NOT NULL,
    phone VARCHAR(20),
    company_name VARCHAR(255) NULL,
    company_description TEXT NULL,
    profile_image VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255) NULL,
    reset_token VARCHAR(255) NULL,
    reset_token_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_user_type (user_type),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- İş İlanları Tablosu
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255),
    employment_type ENUM('full-time', 'part-time', 'contract', 'internship') DEFAULT 'full-time',
    experience_level ENUM('entry', 'mid', 'senior', 'lead') DEFAULT 'mid',
    salary_min DECIMAL(10, 2) NULL,
    salary_max DECIMAL(10, 2) NULL,
    salary_currency VARCHAR(10) DEFAULT 'TRY',
    requirements TEXT NULL,
    benefits TEXT NULL,
    ai_generated_form JSON NULL,
    status ENUM('draft', 'published', 'closed', 'archived') DEFAULT 'draft',
    application_deadline DATE NULL,
    view_count INT DEFAULT 0,
    application_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_employer (employer_id),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_deadline (application_deadline)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- İş İlanı Form Alanları Tablosu
CREATE TABLE job_form_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    field_type ENUM('text', 'textarea', 'select', 'radio', 'checkbox', 'file', 'date', 'number', 'email', 'phone') NOT NULL,
    field_label VARCHAR(255) NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    field_placeholder VARCHAR(255) NULL,
    field_options JSON NULL,
    is_required BOOLEAN DEFAULT FALSE,
    validation_rules VARCHAR(255) NULL,
    field_category ENUM('personal', 'technical', 'experience', 'soft-skill', 'open-ended', 'custom') DEFAULT 'custom',
    field_order INT DEFAULT 0,
    ai_generated BOOLEAN DEFAULT FALSE,
    ai_scoring_weight DECIMAL(3, 2) DEFAULT 1.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    INDEX idx_job (job_id),
    INDEX idx_order (field_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Başvurular Tablosu
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    applicant_id INT NOT NULL,
    form_responses JSON NOT NULL,
    cv_file_path VARCHAR(255) NULL,
    cover_letter TEXT NULL,
    status ENUM('pending', 'under-review', 'shortlisted', 'interviewed', 'accepted', 'rejected') DEFAULT 'pending',
    ai_score DECIMAL(5, 2) NULL,
    ai_evaluation JSON NULL,
    ai_strengths TEXT NULL,
    ai_weaknesses TEXT NULL,
    ai_summary TEXT NULL,
    employer_notes TEXT NULL,
    viewed_by_employer BOOLEAN DEFAULT FALSE,
    viewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_job (job_id),
    INDEX idx_applicant (applicant_id),
    INDEX idx_status (status),
    INDEX idx_score (ai_score),
    INDEX idx_created (created_at),
    UNIQUE KEY unique_application (job_id, applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bildirimler Tablosu
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    notification_type ENUM('application', 'status-change', 'new-job', 'message', 'system') NOT NULL,
    related_id INT NULL,
    related_type VARCHAR(50) NULL,
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mesajlar Tablosu
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    application_id INT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    parent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES messages(id) ON DELETE CASCADE,
    INDEX idx_sender (sender_id),
    INDEX idx_receiver (receiver_id),
    INDEX idx_application (application_id),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Oturum Tablosu
CREATE TABLE sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sistem Ayarları Tablosu
CREATE TABLE system_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Aktivite Logları Tablosu
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- AI İşlem Geçmişi Tablosu
CREATE TABLE ai_processing_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NULL,
    application_id INT NULL,
    process_type ENUM('form-generation', 'cv-parsing', 'candidate-evaluation', 'ranking') NOT NULL,
    ai_model VARCHAR(100),
    prompt_text TEXT,
    response_text TEXT,
    tokens_used INT,
    processing_time DECIMAL(10, 3),
    success BOOLEAN DEFAULT TRUE,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE SET NULL,
    INDEX idx_job (job_id),
    INDEX idx_application (application_id),
    INDEX idx_type (process_type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Başlangıç Verileri

-- Admin Kullanıcı (şifre: admin123)
INSERT INTO users (email, password_hash, full_name, user_type, is_verified) VALUES
('admin@jobplatform.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sistem Yöneticisi', 'admin', TRUE);

-- Örnek İşveren (şifre: employer123)
INSERT INTO users (email, password_hash, full_name, user_type, company_name, company_description, is_verified) VALUES
('isveren@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmet Yılmaz', 'employer', 'TechCorp A.Ş.', 'Yazılım ve teknoloji çözümleri sunan öncü şirket.', TRUE);

-- Örnek Başvuran (şifre: applicant123)
INSERT INTO users (email, password_hash, full_name, user_type, phone, is_verified) VALUES
('basvuran@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ayşe Demir', 'applicant', '05551234567', TRUE);

-- Sistem Ayarları
INSERT INTO system_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'İş Platformu', 'string', 'Site adı'),
('site_email', 'info@jobplatform.com', 'string', 'İletişim e-posta adresi'),
('openai_api_key', '', 'string', 'OpenAI API anahtarı'),
('openai_model', 'gpt-4', 'string', 'Kullanılacak AI modeli'),
('max_cv_size_mb', '5', 'number', 'Maksimum CV dosya boyutu (MB)'),
('applications_per_page', '20', 'number', 'Sayfa başına gösterilecek başvuru sayısı'),
('enable_ai_evaluation', 'true', 'boolean', 'AI değerlendirmesini etkinleştir'),
('enable_notifications', 'true', 'boolean', 'Bildirimleri etkinleştir'),
('maintenance_mode', 'false', 'boolean', 'Bakım modu');
