<?php
/**
 * AI Kurulum ve Test Sayfası
 */
require_once __DIR__ . '/../config/config.php';

// Sadece admin erişebilir
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    die('Bu sayfaya erişim yetkiniz yok.');
}

$pageTitle = 'AI Kurulum & Test';
$aiService = new AIService();

// Test isteği
$testResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_ai'])) {
    $testResult = $aiService->generateJobForm(
        'Senior PHP Developer pozisyonu için deneyimli adaylar arıyoruz. Laravel, MySQL, REST API konularında uzman olmalı.',
        'Senior PHP Developer'
    );
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        .status-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid #667eea;
        }
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .status-item:last-child { border-bottom: none; }
        .status-label {
            font-weight: 600;
            color: #495057;
        }
        .status-value {
            font-family: 'Courier New', monospace;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            background: white;
        }
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.875rem;
        }
        .badge-success {
            background: #10b981;
            color: white;
        }
        .badge-warning {
            background: #f59e0b;
            color: white;
        }
        .badge-danger {
            background: #ef4444;
            color: white;
        }
        .info-box {
            background: #e0e7ff;
            border: 2px solid #667eea;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        .info-box h3 {
            color: #4c1d95;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .info-box ol {
            margin-left: 1.5rem;
            color: #374151;
            line-height: 1.8;
        }
        .info-box code {
            background: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #667eea;
        }
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .test-result {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 2px solid #e9ecef;
        }
        .test-result h3 {
            color: #374151;
            margin-bottom: 1rem;
        }
        .test-result pre {
            background: #1f2937;
            color: #10b981;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 0.875rem;
        }
        .alert {
            padding: 1.25rem;
            border-radius: 10px;
            margin: 1.5rem 0;
            display: flex;
            align-items: start;
            gap: 1rem;
        }
        .alert-info {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            color: #1e40af;
        }
        .alert-success {
            background: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-top: 2rem;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-robot"></i> AI Kurulum & Test</h1>
        <p style="color: #6b7280; margin-bottom: 2rem;">
            OpenAI API entegrasyonunu test edin ve yapılandırın
        </p>

        <!-- Status -->
        <div class="status-card">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">📊 Sistem Durumu</h2>
            
            <div class="status-item">
                <span class="status-label">API Key Durumu:</span>
                <span class="badge <?= AI_DEMO_MODE ? 'badge-warning' : 'badge-success' ?>">
                    <?= AI_DEMO_MODE ? 'DEMO MODE' : 'ACTIVE' ?>
                </span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Model:</span>
                <span class="status-value"><?= OPENAI_MODEL ?></span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Max Tokens:</span>
                <span class="status-value"><?= OPENAI_MAX_TOKENS ?></span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Temperature:</span>
                <span class="status-value"><?= OPENAI_TEMPERATURE ?></span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Timeout:</span>
                <span class="status-value"><?= AI_TIMEOUT ?>s</span>
            </div>
        </div>

        <?php if (AI_DEMO_MODE): ?>
        <!-- Kurulum Talimatları -->
        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> OpenAI API Nasıl Aktif Edilir?</h3>
            <ol>
                <li><strong>API Key Alın:</strong> <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com/api-keys</a> adresinden ücretsiz hesap oluşturun ve API key alın</li>
                <li><strong>API Key'i Ekleyin:</strong> <code>config/config.php</code> dosyasını açın</li>
                <li><strong>Yapıştırın:</strong> <code>define('OPENAI_API_KEY', 'buraya-api-key');</code> satırına API key'inizi yapıştırın</li>
                <li><strong>Kaydedin:</strong> Dosyayı kaydedin ve bu sayfayı yenileyin</li>
            </ol>
            <p style="margin-top: 1rem; font-weight: 600;">
                💡 Demo modda simüle edilmiş AI yanıtları kullanılır. Gerçek AI için API key gereklidir.
            </p>
        </div>
        <?php else: ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <div>
                <strong>API Aktif!</strong><br>
                OpenAI API başarıyla yapılandırılmış. Gerçek AI özellikleri kullanıma hazır.
            </div>
        </div>
        <?php endif; ?>

        <!-- Test Butonu -->
        <form method="POST" style="margin: 2rem 0;">
            <button type="submit" name="test_ai" class="btn btn-primary">
                <i class="fas fa-flask"></i>
                AI Test Et (Form Oluşturma)
            </button>
        </form>

        <!-- Test Sonucu -->
        <?php if ($testResult): ?>
        <div class="test-result">
            <h3>
                <i class="fas fa-<?= $testResult['success'] ? 'check' : 'times' ?>-circle" 
                   style="color: <?= $testResult['success'] ? '#10b981' : '#ef4444' ?>"></i>
                Test Sonucu
            </h3>
            
            <?php if (isset($testResult['demo_mode']) && $testResult['demo_mode']): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>Bu demo mode sonucudur. Gerçek AI için API key ekleyin.</div>
                </div>
            <?php endif; ?>
            
            <pre><?= json_encode($testResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
            
            <?php if ($testResult['success'] && isset($testResult['fields'])): ?>
                <p style="margin-top: 1rem; color: #10b981; font-weight: 600;">
                    ✅ <?= count($testResult['fields']) ?> form alanı başarıyla oluşturuldu!
                </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <a href="<?= SITE_URL ?>" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Ana Sayfaya Dön
        </a>
    </div>
</body>
</html>
