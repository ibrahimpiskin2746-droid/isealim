<?php
/**
 * Router Sınıfı
 * URL yönlendirme ve route işlemleri
 */

class Router {
    private $controller = 'HomeController';
    private $method = 'index';
    private $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // Controller kontrolü
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        // Controller yükle
        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Method kontrolü
        if (isset($url[1])) {
            $methodName = $url[1];
            
            // Tire içeren method adlarını camelCase'e çevir (örn: create-job -> createJob)
            if (strpos($methodName, '-') !== false) {
                $parts = explode('-', $methodName);
                $methodName = $parts[0];
                for ($i = 1; $i < count($parts); $i++) {
                    $methodName .= ucfirst($parts[$i]);
                }
            }
            
            // Method adını kontrol et (hem orijinal hem camelCase)
            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            } elseif (method_exists($this->controller, $url[1])) {
                // Orijinal method adı da denenir
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        // Parametreler
        $this->params = $url ? array_values($url) : [];
        
        // Method'un var olduğunu kontrol et
        if (!method_exists($this->controller, $this->method)) {
            show404();
        }
        
        // Controller metodunu çağır
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    /**
     * URL'i parse eder
     */
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        
        return [];
    }
}
