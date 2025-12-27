<?php
session_start();

// Already logged in?
if (isset($_SESSION['user_id'])) {
    $redirect = $_SESSION['user_type'] === 'employer' ? 'dashboard-employer.php' : 'dashboard-applicant.php';
    header("Location: $redirect");
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'job_platform';
$username = 'root';
$password = 'mysql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $userType = $_POST['user_type'];
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $passConfirm = $_POST['password_confirm'];
    
    // Validation
    if (empty($email) || empty($pass)) {
        $error = 'Lütfen tüm zorunlu alanları doldurun.';
    } elseif ($pass !== $passConfirm) {
        $error = 'Şifreler eşleşmiyor.';
    } elseif (strlen($pass) < 6) {
        $error = 'Şifre en az 6 karakter olmalıdır.';
    } else {
        // Check if email exists
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        
        if ($checkStmt->fetch()) {
            $error = 'Bu e-posta adresi zaten kullanılıyor.';
        } else {
            // Insert user
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
            
            if ($userType === 'employer') {
                $companyName = trim($_POST['company_name']);
                $insertStmt = $pdo->prepare("INSERT INTO users (email, password, user_type, company_name, created_at) 
                                             VALUES (:email, :password, 'employer', :company_name, NOW())");
                $insertStmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPass,
                    ':company_name' => $companyName
                ]);
            } else {
                $fullName = trim($_POST['full_name']);
                $insertStmt = $pdo->prepare("INSERT INTO users (email, password, user_type, full_name, created_at) 
                                             VALUES (:email, :password, 'applicant', :full_name, NOW())");
                $insertStmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPass,
                    ':full_name' => $fullName
                ]);
            }
            
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Ol - İş Platformu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 500px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .logo h1 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #718096;
            font-size: 14px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .user-type-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .user-type-option {
            position: relative;
        }
        
        .user-type-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .user-type-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 3px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type-option input:checked + .user-type-label {
            border-color: #667eea;
            background: #f7fafc;
        }
        
        .user-type-label i {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .user-type-label strong {
            color: #2d3748;
            font-size: 16px;
        }
        
        .user-type-label span {
            color: #718096;
            font-size: 12px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #718096;
            font-size: 14px;
        }
        
        .login-link {
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .dynamic-fields {
            display: none;
        }
        
        .dynamic-fields.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Üye Ol</h1>
            <p>Hemen hesap oluşturun</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Kayıt başarılı! <a href="login.php">Giriş yapabilirsiniz.</a>
            </div>
        <?php else: ?>
            <form method="POST" id="registerForm">
                <div class="user-type-selector">
                    <div class="user-type-option">
                        <input type="radio" name="user_type" value="applicant" id="applicant" checked>
                        <label for="applicant" class="user-type-label">
                            <i class="fas fa-user"></i>
                            <strong>İş Arayan</strong>
                            <span>İş bulmak istiyorum</span>
                        </label>
                    </div>
                    <div class="user-type-option">
                        <input type="radio" name="user_type" value="employer" id="employer">
                        <label for="employer" class="user-type-label">
                            <i class="fas fa-building"></i>
                            <strong>İşveren</strong>
                            <span>İlan vermek istiyorum</span>
                        </label>
                    </div>
                </div>

                <div id="applicantFields" class="dynamic-fields active">
                    <div class="form-group">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" name="full_name" class="form-input" placeholder="Ad Soyad">
                    </div>
                </div>

                <div id="employerFields" class="dynamic-fields">
                    <div class="form-group">
                        <label class="form-label">Şirket Adı</label>
                        <input type="text" name="company_name" class="form-input" placeholder="Şirket Adı">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">E-posta Adresi</label>
                    <input type="email" name="email" class="form-input" 
                           placeholder="ornek@email.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Şifre</label>
                    <input type="password" name="password" class="form-input" 
                           placeholder="En az 6 karakter" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Şifre Tekrar</label>
                    <input type="password" name="password_confirm" class="form-input" 
                           placeholder="Şifreyi tekrar girin" required>
                </div>

                <button type="submit" name="register" class="btn-register">
                    <i class="fas fa-user-plus"></i> Üye Ol
                </button>
            </form>

            <div class="divider">
                <span>veya</span>
            </div>

            <div class="login-link">
                Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const applicantRadio = document.getElementById('applicant');
        const employerRadio = document.getElementById('employer');
        const applicantFields = document.getElementById('applicantFields');
        const employerFields = document.getElementById('employerFields');

        function toggleFields() {
            if (applicantRadio.checked) {
                applicantFields.classList.add('active');
                employerFields.classList.remove('active');
                employerFields.querySelector('input').removeAttribute('required');
                applicantFields.querySelector('input').setAttribute('required', 'required');
            } else {
                employerFields.classList.add('active');
                applicantFields.classList.remove('active');
                applicantFields.querySelector('input').removeAttribute('required');
                employerFields.querySelector('input').setAttribute('required', 'required');
            }
        }

        applicantRadio.addEventListener('change', toggleFields);
        employerRadio.addEventListener('change', toggleFields);
    </script>
</body>
</html>
