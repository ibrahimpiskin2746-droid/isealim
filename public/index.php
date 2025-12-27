<?php
/**
 * Ana Index Dosyası
 * Uygulamayı başlatır
 */

// Konfigürasyon dosyasını yükle
require_once dirname(__DIR__) . '/config/config.php';

// Router'ı başlat
new Router();
