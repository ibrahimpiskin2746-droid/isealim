<?php
/**
 * Veritabanı Bağlantı Test Scripti
 * Bu dosyayı tarayıcıda açarak veritabanı bağlantısını test edebilirsiniz
 * 
 * Kullanım: http://localhost/isealim/test-db-connection.php
 */

// Hata gösterimi açık
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Veritabanı Bağlantı Testi</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
    pre { background: #fff; padding: 10px; border-radius: 5px; overflow-x: auto; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    table th, table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    table th { background-color: #4CAF50; color: white; }
    table tr:nth-child(even) { background-color: #f2f2f2; }
</style>";

// Config dosyasını yükle
$configPath = __DIR__ . '/config/config.php';
if (!file_exists($configPath)) {
    die("<div class='error'><strong>❌ HATA:</strong> config/config.php dosyası bulunamadı!</div>");
}

require_once $configPath;

echo "<div class='info'><strong>ℹ️ Config Dosyası Yüklendi</strong></div>";

// Ayarları göster
echo "<h2>📋 Veritabanı Ayarları</h2>";
echo "<table>";
echo "<tr><th>Ayar</th><th>Değer</th></tr>";
echo "<tr><td>DB_HOST</td><td>" . (defined('DB_HOST') ? htmlspecialchars(DB_HOST) : '<span style="color:red;">TANIMLI DEĞİL</span>') . "</td></tr>";
echo "<tr><td>DB_NAME</td><td>" . (defined('DB_NAME') ? htmlspecialchars(DB_NAME) : '<span style="color:red;">TANIMLI DEĞİL</span>') . "</td></tr>";
echo "<tr><td>DB_USER</td><td>" . (defined('DB_USER') ? htmlspecialchars(DB_USER) : '<span style="color:red;">TANIMLI DEĞİL</span>') . "</td></tr>";
echo "<tr><td>DB_PASS</td><td>" . (defined('DB_PASS') ? (DB_PASS ? '*** (şifre var)' : '<span style="color:orange;">BOŞ</span>') : '<span style="color:red;">TANIMLI DEĞİL</span>') . "</td></tr>";
echo "<tr><td>DB_CHARSET</td><td>" . (defined('DB_CHARSET') ? htmlspecialchars(DB_CHARSET) : 'utf8mb4') . "</td></tr>";
echo "</table>";

// PDO extension kontrolü
echo "<h2>🔧 PHP Eklentileri Kontrolü</h2>";
if (extension_loaded('pdo')) {
    echo "<div class='success'>✅ PDO eklentisi yüklü</div>";
} else {
    die("<div class='error'>❌ PDO eklentisi yüklü değil! PHP.ini dosyasından etkinleştirin.</div>");
}

if (extension_loaded('pdo_mysql')) {
    echo "<div class='success'>✅ PDO MySQL eklentisi yüklü</div>";
} else {
    die("<div class='error'>❌ PDO MySQL eklentisi yüklü değil! PHP.ini dosyasından etkinleştirin.</div>");
}

// MySQL sunucusuna bağlantı testi (veritabanı olmadan)
echo "<h2>🔌 MySQL Sunucu Bağlantı Testi</h2>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ];
    
    $testConnection = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "<div class='success'>✅ MySQL sunucusuna başarıyla bağlanıldı!</div>";
    
    // MySQL versiyonu
    $version = $testConnection->query('SELECT VERSION()')->fetchColumn();
    echo "<div class='info'>📌 MySQL Versiyonu: " . htmlspecialchars($version) . "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>❌ MySQL sunucusuna bağlanılamadı!</strong><br>";
    echo "Hata: " . htmlspecialchars($e->getMessage()) . "<br><br>";
    echo "<strong>Çözüm Önerileri:</strong><br>";
    echo "1. AMPPS Control Panel'den MySQL servisinin çalıştığından emin olun<br>";
    echo "2. DB_HOST değerinin doğru olduğundan emin olun (genellikle 'localhost')<br>";
    echo "3. DB_USER ve DB_PASS değerlerinin doğru olduğundan emin olun<br>";
    echo "4. AMPPS'te MySQL şifresi genellikle 'mysql' veya boş olabilir<br>";
    echo "</div>";
    die();
}

// Veritabanı varlık kontrolü
echo "<h2>📊 Veritabanı Kontrolü</h2>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $testConnection = new PDO($dsn, DB_USER, DB_PASS);
    
    $stmt = $testConnection->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    $dbExists = $stmt->rowCount() > 0;
    
    if ($dbExists) {
        echo "<div class='success'>✅ Veritabanı '" . htmlspecialchars(DB_NAME) . "' mevcut</div>";
        
        // Tabloları listele
        $testConnection->exec("USE `" . DB_NAME . "`");
        $tables = $testConnection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<div class='success'>✅ Veritabanında " . count($tables) . " tablo bulundu:</div>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>" . htmlspecialchars($table) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<div class='warning'>⚠️ Veritabanı boş! database.sql dosyasını import etmeniz gerekiyor.</div>";
        }
        
    } else {
        echo "<div class='warning'>⚠️ Veritabanı '" . htmlspecialchars(DB_NAME) . "' bulunamadı!</div>";
        echo "<div class='info'>Veritabanını oluşturmak için aşağıdaki butona tıklayın:</div>";
        echo "<form method='POST' style='margin: 20px 0;'>";
        echo "<button type='submit' name='create_db' style='padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Veritabanını Oluştur</button>";
        echo "</form>";
        
        if (isset($_POST['create_db'])) {
            try {
                $testConnection->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo "<div class='success'>✅ Veritabanı başarıyla oluşturuldu!</div>";
                echo "<div class='info'>Şimdi database.sql dosyasını import etmeniz gerekiyor.</div>";
            } catch (PDOException $e) {
                echo "<div class='error'>❌ Veritabanı oluşturulamadı: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Veritabanı kontrolü sırasında hata: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Son bağlantı testi (tam bağlantı)
echo "<h2>✅ Son Bağlantı Testi</h2>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    
    $finalConnection = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Basit bir sorgu çalıştır
    $result = $finalConnection->query("SELECT 1 as test")->fetch();
    
    echo "<div class='success'>";
    echo "<strong>🎉 BAŞARILI!</strong><br>";
    echo "Veritabanı bağlantısı tamamen çalışıyor!<br>";
    echo "Test sorgusu başarıyla çalıştırıldı.";
    echo "</div>";
    
    echo "<div class='info' style='margin-top: 20px;'>";
    echo "<strong>✅ Tüm testler başarılı!</strong><br>";
    echo "Artık siteyi kullanabilirsiniz: <a href='http://localhost/isealim' target='_blank'>http://localhost/isealim</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>❌ Son bağlantı testi başarısız!</strong><br>";
    echo "Hata: " . htmlspecialchars($e->getMessage()) . "<br><br>";
    
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        echo "<strong>Çözüm:</strong> Veritabanını oluşturmanız gerekiyor. Yukarıdaki 'Veritabanını Oluştur' butonunu kullanın veya phpMyAdmin'den oluşturun.<br>";
    } elseif (strpos($e->getMessage(), "Access denied") !== false) {
        echo "<strong>Çözüm:</strong> Kullanıcı adı veya şifre yanlış. config/config.php dosyasındaki DB_USER ve DB_PASS değerlerini kontrol edin.<br>";
    } elseif (strpos($e->getMessage(), "Connection refused") !== false) {
        echo "<strong>Çözüm:</strong> MySQL servisi çalışmıyor. AMPPS Control Panel'den MySQL'i başlatın.<br>";
    }
    echo "</div>";
}

echo "<hr>";
echo "<div class='info'>";
echo "<strong>💡 İpuçları:</strong><br>";
echo "• AMPPS'te MySQL şifresi genellikle 'mysql' veya boş olabilir<br>";
echo "• Veritabanı yoksa, database.sql dosyasını phpMyAdmin'den import edin<br>";
echo "• Tüm testler başarılıysa, bu dosyayı silebilirsiniz (güvenlik için)<br>";
echo "</div>";


