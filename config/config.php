<?php
/**
 * Sistem Yapılandırma Dosyası
 * AI Destekli İş Başvuru ve Değerlendirme Platformu
 */

// Hata Raporlama
define('ENVIRONMENT', 'development'); // development, production
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Zaman Dilimi
date_default_timezone_set('Europe/Istanbul');

// Veritabanı Ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_platform');
define('DB_USER', 'root');
define('DB_PASS', 'mysql'); // AMPPS varsayılan şifresi
define('DB_CHARSET', 'utf8mb4');

// Site Ayarları
define('SITE_NAME', 'İş Platformu');
define('SITE_URL', 'http://localhost/isealim');
define('BASE_PATH', dirname(__DIR__));

// Dizin Yapısı
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOAD_PATH', STORAGE_PATH . '/uploads');
define('CV_UPLOAD_PATH', UPLOAD_PATH . '/cvs');
define('PROFILE_UPLOAD_PATH', UPLOAD_PATH . '/profiles');
define('LOG_PATH', STORAGE_PATH . '/logs');
define('CACHE_PATH', STORAGE_PATH . '/cache');

// URL Yapısı
define('PUBLIC_URL', SITE_URL . '/public');
define('ASSETS_URL', PUBLIC_URL . '/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('IMG_URL', ASSETS_URL . '/images');
define('UPLOAD_URL', SITE_URL . '/storage/uploads');

// Güvenlik Ayarları
define('ENCRYPTION_KEY', 'your-32-character-secret-key-here-change-in-production');
define('SESSION_LIFETIME', 7200); // 2 saat
define('CSRF_TOKEN_NAME', '_csrf_token');
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 dakika

// Dosya Yükleme Ayarları
define('MAX_CV_SIZE', 5 * 1024 * 1024); // 5 MB
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024); // 2 MB
define('ALLOWED_CV_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// AI Ayarları
// NOT: Gerçek AI kullanmak için OpenAI API key gereklidir
// API Key almak için: https://platform.openai.com/api-keys
// Ücretsiz demo için boş bırakabilirsiniz (simüle edilmiş yanıtlar kullanılır)
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: ''); // Buraya API key girin
define('OPENAI_MODEL', 'gpt-4o-mini'); // Uygun fiyatlı ve hızlı model
define('OPENAI_MAX_TOKENS', 4000);
define('OPENAI_TEMPERATURE', 0.7);
define('AI_TIMEOUT', 60); // saniye
define('AI_DEMO_MODE', empty(OPENAI_API_KEY)); // API key yoksa demo modu aktif

// E-posta Ayarları
define('MAIL_FROM', 'noreply@jobplatform.com');
define('MAIL_FROM_NAME', SITE_NAME);
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_ENCRYPTION', 'tls');

// Pagination
define('ITEMS_PER_PAGE', 20);
define('JOBS_PER_PAGE', 12);
define('APPLICATIONS_PER_PAGE', 15);

// Cache Ayarları
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 saat

// Log Ayarları
define('LOG_ENABLED', true);
define('LOG_LEVEL', 'debug'); // debug, info, warning, error

// API Rate Limiting
define('API_RATE_LIMIT', 100); // istek/saat
define('API_RATE_PERIOD', 3600); // saniye

// Sistem Sabit Değerleri
define('USER_TYPES', [
    'admin' => 'Yönetici',
    'employer' => 'İşveren',
    'applicant' => 'Başvuran'
]);

define('JOB_STATUS', [
    'draft' => 'Taslak',
    'published' => 'Yayında',
    'closed' => 'Kapalı',
    'archived' => 'Arşivlenmiş'
]);

define('APPLICATION_STATUS', [
    'pending' => 'Beklemede',
    'under-review' => 'İnceleniyor',
    'shortlisted' => 'Kısa Listede',
    'interviewed' => 'Görüşme Yapıldı',
    'accepted' => 'Kabul Edildi',
    'rejected' => 'Reddedildi'
]);

define('EMPLOYMENT_TYPES', [
    'full-time' => 'Tam Zamanlı',
    'part-time' => 'Yarı Zamanlı',
    'contract' => 'Sözleşmeli',
    'internship' => 'Stajyer'
]);

define('EXPERIENCE_LEVELS', [
    'entry' => 'Başlangıç',
    'mid' => 'Orta',
    'senior' => 'Kıdemli',
    'lead' => 'Lider'
]);

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/core/',
        APP_PATH . '/services/',
        APP_PATH . '/helpers/',
        APP_PATH . '/middleware/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Oturum Başlat
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // HTTPS için 1 yapın
    ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
    session_start();
}

// Global Yardımcı Fonksiyonları Yükle
require_once APP_PATH . '/helpers/functions.php';
