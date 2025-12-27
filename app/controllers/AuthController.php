<?php
/**
 * Auth Controller
 * Kimlik doğrulama işlemleri
 */

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    /**
     * Giriş sayfası
     */
    public function login() {
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if (isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        if ($this->isPost()) {
            $this->handleLogin();
        } else {
            $this->view('auth/login', [
                'title' => 'Giriş Yap'
            ]);
        }
    }
    
    /**
     * Giriş işlemini yapar
     */
    private function handleLogin() {
        $email = cleanInput(post('email'));
        $password = post('password');
        $remember = post('remember');
        
        // Validasyon
        $errors = $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->view('auth/login', [
                'title' => 'Giriş Yap',
                'errors' => $errors,
                'email' => $email
            ]);
            return;
        }
        
        // Giriş dene
        $result = $this->userModel->login($email, $password);
        
        if ($result['success']) {
            // Remember me
            if ($remember) {
                $this->setRememberMeCookie($result['user']['id']);
            }
            
            setFlash('success', 'Hoş geldiniz, ' . $result['user']['full_name']);
            $this->redirectToDashboard();
        } else {
            $this->view('auth/login', [
                'title' => 'Giriş Yap',
                'error' => $result['message'],
                'email' => $email
            ]);
        }
    }
    
    /**
     * Kayıt sayfası
     */
    public function register() {
        if (isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        if ($this->isPost()) {
            $this->handleRegister();
        } else {
            $this->view('auth/register', [
                'title' => 'Kayıt Ol'
            ]);
        }
    }
    
    /**
     * Kayıt işlemini yapar
     */
    private function handleRegister() {
        $data = [
            'email' => cleanInput(post('email')),
            'password' => post('password'),
            'password_confirm' => post('password_confirm'),
            'full_name' => cleanInput(post('full_name')),
            'phone' => cleanInput(post('phone')),
            'user_type' => post('user_type'),
            'company_name' => cleanInput(post('company_name')),
            'company_description' => cleanInput(post('company_description'))
        ];
        
        // Validasyon
        $errors = [];
        
        if (empty($data['email']) || !isValidEmail($data['email'])) {
            $errors['email'] = 'Geçerli bir e-posta adresi giriniz';
        }
        
        if ($this->userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Bu e-posta adresi zaten kullanılıyor';
        }
        
        if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
            $errors['password'] = 'Şifre en az ' . PASSWORD_MIN_LENGTH . ' karakter olmalıdır';
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = 'Şifreler eşleşmiyor';
        }
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Ad Soyad zorunludur';
        }
        
        if (!in_array($data['user_type'], ['employer', 'applicant'])) {
            $errors['user_type'] = 'Geçersiz kullanıcı tipi';
        }
        
        if ($data['user_type'] === 'employer' && empty($data['company_name'])) {
            $errors['company_name'] = 'Şirket adı zorunludur';
        }
        
        if (!empty($errors)) {
            $this->view('auth/register', [
                'title' => 'Kayıt Ol',
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        // Kayıt oluştur
        unset($data['password_confirm']);
        $userId = $this->userModel->register($data);
        
        if ($userId) {
            setFlash('success', 'Kayıt başarılı! Giriş yapabilirsiniz.');
            redirect('auth/login');
        } else {
            setFlash('error', 'Kayıt sırasında bir hata oluştu');
            $this->view('auth/register', [
                'title' => 'Kayıt Ol',
                'data' => $data
            ]);
        }
    }
    
    /**
     * Çıkış yapar
     */
    public function logout() {
        $this->userModel->logout();
        setFlash('success', 'Başarıyla çıkış yaptınız');
        redirect('auth/login');
    }
    
    /**
     * Şifremi unuttum sayfası
     */
    public function forgotPassword() {
        if ($this->isPost()) {
            $email = cleanInput(post('email'));
            
            $result = $this->userModel->createPasswordResetToken($email);
            
            if ($result['success']) {
                // E-posta gönder (mail fonksiyonu eklenecek)
                setFlash('success', 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi');
            } else {
                setFlash('error', $result['message']);
            }
            
            redirect('auth/forgot-password');
        }
        
        $this->view('auth/forgot-password', [
            'title' => 'Şifremi Unuttum'
        ]);
    }
    
    /**
     * Şifre sıfırlama sayfası
     */
    public function resetPassword($token = '') {
        if (empty($token)) {
            redirect('auth/login');
        }
        
        if ($this->isPost()) {
            $password = post('password');
            $passwordConfirm = post('password_confirm');
            
            if ($password !== $passwordConfirm) {
                setFlash('error', 'Şifreler eşleşmiyor');
            } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
                setFlash('error', 'Şifre en az ' . PASSWORD_MIN_LENGTH . ' karakter olmalıdır');
            } else {
                $result = $this->userModel->resetPassword($token, $password);
                
                if ($result['success']) {
                    setFlash('success', 'Şifreniz başarıyla güncellendi');
                    redirect('auth/login');
                } else {
                    setFlash('error', $result['message']);
                }
            }
        }
        
        $this->view('auth/reset-password', [
            'title' => 'Şifre Sıfırlama',
            'token' => $token
        ]);
    }
    
    /**
     * Dashboard'a yönlendir
     */
    private function redirectToDashboard() {
        if (isEmployer()) {
            redirect('employer/dashboard');
        } elseif (isApplicant()) {
            redirect('applicant/dashboard');
        } elseif (isAdmin()) {
            redirect('admin/dashboard');
        } else {
            redirect('');
        }
    }
    
    /**
     * Remember me cookie ayarlar
     */
    private function setRememberMeCookie($userId) {
        $token = generateRandomString(64);
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 gün
        
        // Token'ı veritabanına kaydet (sessions tablosuna)
        // İmplementasyon eklenebilir
    }
}
