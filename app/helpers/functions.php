<?php
/**
 * Global Yardımcı Fonksiyonlar
 */

/**
 * HTML çıktısını güvenli hale getirir (XSS koruması)
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * URL oluşturur
 */
function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

/**
 * Asset URL oluşturur
 */
function asset($path = '') {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Yönlendirme yapar
 */
function redirect($path = '') {
    header('Location: ' . url($path));
    exit;
}

/**
 * Geri yönlendirme yapar
 */
function back() {
    $referer = $_SERVER['HTTP_REFERER'] ?? url();
    header('Location: ' . $referer);
    exit;
}

/**
 * JSON yanıt gönderir
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Flash mesaj ayarlar
 */
function setFlash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

/**
 * Flash mesaj alır ve temizler
 */
function getFlash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/**
 * Flash mesaj var mı kontrol eder
 */
function hasFlash($type) {
    return isset($_SESSION['flash'][$type]);
}

/**
 * Flash mesajı alır ama silmez
 */
function flash($type) {
    return $_SESSION['flash'][$type] ?? null;
}

/**
 * Oturum kontrolü
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Kullanıcı bilgisi alır
 */
function auth() {
    return $_SESSION['user'] ?? null;
}

/**
 * Kullanıcı ID'si alır
 */
function authId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Kullanıcı tipini kontrol eder
 */
function isEmployer() {
    return auth() && auth()['user_type'] === 'employer';
}

function isApplicant() {
    return auth() && auth()['user_type'] === 'applicant';
}

function isAdmin() {
    return auth() && auth()['user_type'] === 'admin';
}

/**
 * CSRF Token oluşturur
 */
function generateCsrfToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * CSRF Token doğrular
 */
function verifyCsrfToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * CSRF Token HTML input oluşturur
 */
function csrfField() {
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . generateCsrfToken() . '">';
}

/**
 * Tarih formatlar
 */
function formatDate($date, $format = 'd.m.Y') {
    if (!$date) return '';
    return date($format, strtotime($date));
}

/**
 * Tarih-saat formatlar
 */
function formatDateTime($datetime, $format = 'd.m.Y H:i') {
    if (!$datetime) return '';
    return date($format, strtotime($datetime));
}

/**
 * Göreli zaman formatlar (örn: "2 saat önce")
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'Az önce';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' dakika önce';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' saat önce';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' gün önce';
    } elseif ($diff < 2592000) {
        return floor($diff / 604800) . ' hafta önce';
    } elseif ($diff < 31536000) {
        return floor($diff / 2592000) . ' ay önce';
    } else {
        return floor($diff / 31536000) . ' yıl önce';
    }
}

/**
 * Para formatlar
 */
function formatMoney($amount, $currency = 'TRY') {
    $symbols = [
        'TRY' => '₺',
        'USD' => '$',
        'EUR' => '€'
    ];
    
    $symbol = $symbols[$currency] ?? $currency;
    return number_format($amount, 2, ',', '.') . ' ' . $symbol;
}

/**
 * Sayfa başlığı oluşturur
 */
function pageTitle($title = '') {
    return $title ? $title . ' | ' . SITE_NAME : SITE_NAME;
}

/**
 * View dosyasını yükler
 */
function view($viewPath, $data = []) {
    extract($data);
    $viewFile = APP_PATH . '/views/' . $viewPath . '.php';
    
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        die("View bulunamadı: {$viewPath}");
    }
}

/**
 * Rastgele string oluşturur
 */
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * E-posta doğrulama
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Dosya boyutunu okunabilir formata çevirir
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Slug oluşturur (URL dostu string)
 */
function slugify($text) {
    $text = mb_strtolower($text, 'UTF-8');
    
    $turkish = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
    $english = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
    $text = str_replace($turkish, $english, $text);
    
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    
    return $text;
}

/**
 * Metin kısaltma
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text, 'UTF-8') <= $length) {
        return $text;
    }
    
    return mb_substr($text, 0, $length, 'UTF-8') . $suffix;
}

/**
 * Log kaydı oluşturur
 */
function logMessage($message, $level = 'info') {
    if (!LOG_ENABLED) return;
    
    $logFile = LOG_PATH . '/' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    
    if (!file_exists(LOG_PATH)) {
        mkdir(LOG_PATH, 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Debug için var_dump alternatifi
 */
function dd(...$vars) {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Input temizleme
 */
function cleanInput($data) {
    if (is_array($data)) {
        return array_map('cleanInput', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    
    return $data;
}

/**
 * POST verisi al
 */
function post($key = null, $default = null) {
    if ($key === null) {
        return $_POST;
    }
    
    return $_POST[$key] ?? $default;
}

/**
 * GET verisi al
 */
function get($key = null, $default = null) {
    if ($key === null) {
        return $_GET;
    }
    
    return $_GET[$key] ?? $default;
}

/**
 * Dosya yükleme kontrolü
 */
function isFileUploaded($fieldName) {
    return isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK;
}

/**
 * Güvenli dosya yükleme
 */
function uploadFile($fieldName, $destinationPath, $allowedTypes = [], $maxSize = null) {
    if (!isFileUploaded($fieldName)) {
        return ['success' => false, 'error' => 'Dosya yüklenmedi'];
    }
    
    $file = $_FILES[$fieldName];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    
    // Boyut kontrolü
    if ($maxSize && $fileSize > $maxSize) {
        return ['success' => false, 'error' => 'Dosya boyutu çok büyük'];
    }
    
    // Tip kontrolü
    if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Geçersiz dosya tipi'];
    }
    
    // Güvenli dosya adı oluştur
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $safeFileName = uniqid() . '_' . time() . '.' . $extension;
    $destination = $destinationPath . '/' . $safeFileName;
    
    // Dizin yoksa oluştur
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }
    
    // Dosyayı taşı
    if (move_uploaded_file($fileTmpName, $destination)) {
        return [
            'success' => true,
            'fileName' => $safeFileName,
            'filePath' => $destination,
            'fileSize' => $fileSize,
            'fileType' => $fileType
        ];
    }
    
    return ['success' => false, 'error' => 'Dosya yüklenirken hata oluştu'];
}

/**
 * Pagination hesaplama
 */
function paginate($totalItems, $currentPage = 1, $perPage = ITEMS_PER_PAGE) {
    $totalPages = ceil($totalItems / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    
    return [
        'total_items' => $totalItems,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => $currentPage - 1,
        'next_page' => $currentPage + 1
    ];
}

/**
 * HTTP durum kodu ayarla
 */
function setHttpStatus($code) {
    http_response_code($code);
}

/**
 * 404 Sayfa
 */
function show404() {
    setHttpStatus(404);
    view('errors/404');
    exit;
}

/**
 * 403 Sayfa
 */
function show403() {
    setHttpStatus(403);
    view('errors/403');
    exit;
}

/**
 * 500 Sayfa
 */
function show500($message = 'Sunucu hatası') {
    setHttpStatus(500);
    logMessage("500 Error: {$message}", 'error');
    view('errors/500', ['message' => $message]);
    exit;
}
