<?php
/**
 * Temel Controller Sınıfı
 * Tüm controller'lar bu sınıftan türetilir
 */

class Controller {
    
    /**
     * View yükler
     */
    protected function view($viewPath, $data = []) {
        extract($data);
        
        $viewFile = APP_PATH . '/views/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            show404();
        }
    }
    
    /**
     * Model yükler
     */
    protected function model($modelName) {
        $modelFile = APP_PATH . '/models/' . $modelName . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        }
        
        throw new Exception("Model bulunamadı: {$modelName}");
    }
    
    /**
     * JSON yanıt döner
     */
    protected function json($data, $statusCode = 200) {
        jsonResponse($data, $statusCode);
    }
    
    /**
     * Yönlendirme yapar
     */
    protected function redirect($path = '') {
        redirect($path);
    }
    
    /**
     * Geri döner
     */
    protected function back() {
        back();
    }
    
    /**
     * Oturum kontrolü - giriş yapmamışsa login'e yönlendir
     */
    protected function requireAuth() {
        if (!isLoggedIn()) {
            setFlash('error', 'Bu sayfayı görüntülemek için giriş yapmalısınız');
            redirect('auth/login');
        }
    }
    
    /**
     * İşveren kontrolü
     */
    protected function requireEmployer() {
        $this->requireAuth();
        if (!isEmployer()) {
            show403();
        }
    }
    
    /**
     * Başvuran kontrolü
     */
    protected function requireApplicant() {
        $this->requireAuth();
        if (!isApplicant()) {
            show403();
        }
    }
    
    /**
     * Admin kontrolü
     */
    protected function requireAdmin() {
        $this->requireAuth();
        if (!isAdmin()) {
            show403();
        }
    }
    
    /**
     * POST isteği kontrolü
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * GET isteği kontrolü
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * AJAX isteği kontrolü
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * CSRF token doğrulama
     */
    protected function verifyCsrf() {
        $token = post(CSRF_TOKEN_NAME);
        
        if (!verifyCsrfToken($token)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Geçersiz istek'], 403);
            } else {
                show403();
            }
        }
    }
    
    /**
     * Form validasyonu
     */
    protected function validate($rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = post($field);
            $ruleList = explode('|', $rule);
            
            foreach ($ruleList as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field] = ucfirst($field) . ' alanı zorunludur';
                    break;
                }
                
                if (str_starts_with($r, 'min:')) {
                    $min = (int)substr($r, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = ucfirst($field) . " en az {$min} karakter olmalıdır";
                        break;
                    }
                }
                
                if (str_starts_with($r, 'max:')) {
                    $max = (int)substr($r, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = ucfirst($field) . " en fazla {$max} karakter olmalıdır";
                        break;
                    }
                }
                
                if ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Geçerli bir e-posta adresi giriniz';
                    break;
                }
                
                if ($r === 'numeric' && !is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . ' sayısal olmalıdır';
                    break;
                }
            }
        }
        
        return $errors;
    }
}
