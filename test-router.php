<?php
/**
 * Router Test Scripti
 * Bu dosyayı tarayıcıda açarak router'ın nasıl çalıştığını test edebilirsiniz
 * 
 * Kullanım: http://localhost/isealim/test-router.php?url=employer/create-job
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Router Test</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
    pre { background: #fff; padding: 10px; border-radius: 5px; overflow-x: auto; }
</style>";

// Test URL'i
$testUrl = $_GET['url'] ?? 'employer/create-job';
$_GET['url'] = $testUrl;

echo "<div class='info'><strong>Test URL:</strong> {$testUrl}</div>";

// URL parse testi
$url = explode('/', filter_var(rtrim($testUrl, '/'), FILTER_SANITIZE_URL));
echo "<div class='info'><strong>Parsed URL:</strong><pre>";
print_r($url);
echo "</pre></div>";

// Controller testi
if (isset($url[0])) {
    $controllerName = ucfirst($url[0]) . 'Controller';
    $controllerFile = __DIR__ . '/app/controllers/' . $controllerName . '.php';
    
    echo "<div class='info'><strong>Controller Name:</strong> {$controllerName}</div>";
    echo "<div class='info'><strong>Controller File:</strong> {$controllerFile}</div>";
    echo "<div class='" . (file_exists($controllerFile) ? 'success' : 'error') . "'>";
    echo "<strong>File Exists:</strong> " . (file_exists($controllerFile) ? 'YES ✅' : 'NO ❌');
    echo "</div>";
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        // Method testi
        if (isset($url[1])) {
            $methodName = $url[1];
            $originalMethod = $methodName;
            
            echo "<div class='info'><strong>Original Method:</strong> {$methodName}</div>";
            
            // Tire içeren method adlarını camelCase'e çevir
            if (strpos($methodName, '-') !== false) {
                $parts = explode('-', $methodName);
                $methodName = $parts[0];
                for ($i = 1; $i < count($parts); $i++) {
                    $methodName .= ucfirst($parts[$i]);
                }
                echo "<div class='info'><strong>Converted Method:</strong> {$methodName}</div>";
            }
            
            // Method kontrolü
            if (class_exists($controllerName)) {
                $reflection = new ReflectionClass($controllerName);
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                $methodNames = array_map(function($m) { return $m->getName(); }, $methods);
                
                echo "<div class='info'><strong>Available Methods:</strong><pre>";
                print_r($methodNames);
                echo "</pre></div>";
                
                echo "<div class='" . (in_array($methodName, $methodNames) ? 'success' : 'error') . "'>";
                echo "<strong>Method Exists:</strong> " . (in_array($methodName, $methodNames) ? "YES ✅ ({$methodName})" : "NO ❌");
                echo "</div>";
            }
        }
    }
}

echo "<hr>";
echo "<div class='info'>";
echo "<strong>💡 Test URL'leri:</strong><br>";
echo "<a href='?url=employer/create-job'>employer/create-job</a><br>";
echo "<a href='?url=employer/dashboard'>employer/dashboard</a><br>";
echo "<a href='?url=employer/generate-form/123'>employer/generate-form/123</a><br>";
echo "</div>";


