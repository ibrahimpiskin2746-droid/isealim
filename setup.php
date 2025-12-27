<?php
/**
 * Veritabanı Kurulum Scripti
 */

// Config yükle
require_once __DIR__ . '/config/config.php';

echo "🚀 Veritabanı kurulumu başlatılıyor...\n\n";

try {
    // Veritabanı bağlantısı (DB olmadan)
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ MySQL bağlantısı başarılı\n";
    
    // Veritabanını oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Veritabanı oluşturuldu: " . DB_NAME . "\n";
    
    // Veritabanını seç
    $pdo->exec("USE " . DB_NAME);
    
    // SQL dosyasını oku ve çalıştır
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // SQL dosyasını işle (USE komutundan sonrasını al)
    $sqlLines = explode("\n", $sql);
    $currentQuery = '';
    $queriesExecuted = 0;
    
    foreach ($sqlLines as $line) {
        $line = trim($line);
        
        // Boş satır veya yorum satırlarını atla
        if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
            continue;
        }
        
        // USE komutunu atla
        if (stripos($line, 'USE') === 0 || stripos($line, 'CREATE DATABASE') === 0) {
            continue;
        }
        
        $currentQuery .= ' ' . $line;
        
        // Query bitişi kontrolü
        if (substr(trim($line), -1) === ';') {
            try {
                $pdo->exec($currentQuery);
                $queriesExecuted++;
            } catch (PDOException $e) {
                // Tablo zaten varsa devam et
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠️ Query hatası: " . $e->getMessage() . "\n";
                }
            }
            $currentQuery = '';
        }
    }
    
    echo "✅ $queriesExecuted sorgu çalıştırıldı\n";
    
    // Test kullanıcısı oluştur
    $checkUser = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@test.com'")->fetchColumn();
    
    if ($checkUser == 0) {
        $passwordHash = password_hash('12345678', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (email, password_hash, full_name, user_type, is_active, is_verified, created_at) 
                    VALUES ('admin@test.com', '$passwordHash', 'Admin User', 'employer', 1, 1, NOW())");
        echo "✅ Test kullanıcısı oluşturuldu\n";
        echo "   📧 Email: admin@test.com\n";
        echo "   🔑 Şifre: 12345678\n";
        echo "   👤 Tip: İşveren (employer)\n";
    } else {
        echo "ℹ️ Test kullanıcısı zaten mevcut\n";
    }
    
    // Test başvuran oluştur
    $checkApplicant = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'aday@test.com'")->fetchColumn();
    
    if ($checkApplicant == 0) {
        $passwordHash = password_hash('12345678', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (email, password_hash, full_name, user_type, is_active, is_verified, created_at) 
                    VALUES ('aday@test.com', '$passwordHash', 'Test Başvuran', 'applicant', 1, 1, NOW())");
        echo "✅ Test başvuran oluşturuldu\n";
        echo "   📧 Email: aday@test.com\n";
        echo "   🔑 Şifre: 12345678\n";
        echo "   👤 Tip: Başvuran (applicant)\n";
    } else {
        echo "ℹ️ Test başvuran zaten mevcut\n";
    }
    
    echo "\n🎉 Kurulum tamamlandı!\n";
    echo "\n📌 Siteye Erişim:\n";
    echo "🌐 URL: http://localhost/isealim\n";
    echo "🌐 Direkt: http://localhost/isealim/public\n";
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
    exit(1);
}
