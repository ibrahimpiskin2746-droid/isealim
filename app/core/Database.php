<?php
/**
 * Veritabanı Bağlantı Sınıfı
 * PDO kullanarak güvenli veritabanı işlemleri
 */

class Database {
    private static $instance = null;
    private $connection;
    private $stmt;
    
    private function __construct() {
        try {
            // Önce veritabanı olmadan bağlanmayı dene (veritabanı yoksa oluşturmak için)
            $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            // Önce sunucuya bağlan
            $tempConnection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // Veritabanının var olup olmadığını kontrol et
            $checkDb = $tempConnection->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
            if ($checkDb->rowCount() == 0) {
                // Veritabanı yoksa oluştur
                $tempConnection->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                logMessage("Database '" . DB_NAME . "' created successfully", 'info');
            }
            
            // Şimdi veritabanı ile bağlan
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            $errorMessage = "Veritabanı bağlantısı kurulamadı!\n\n";
            $errorMessage .= "Hata Detayı: " . $e->getMessage() . "\n\n";
            $errorMessage .= "Kontrol Edilecekler:\n";
            $errorMessage .= "1. MySQL servisinin çalıştığından emin olun\n";
            $errorMessage .= "2. config/config.php dosyasındaki ayarları kontrol edin:\n";
            $errorMessage .= "   - DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'TANIMLI DEĞİL') . "\n";
            $errorMessage .= "   - DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'TANIMLI DEĞİL') . "\n";
            $errorMessage .= "   - DB_USER: " . (defined('DB_USER') ? DB_USER : 'TANIMLI DEĞİL') . "\n";
            $errorMessage .= "   - DB_PASS: " . (defined('DB_PASS') ? (DB_PASS ? '***' : 'BOŞ') : 'TANIMLI DEĞİL') . "\n";
            $errorMessage .= "3. phpMyAdmin'den veritabanı bağlantısını test edin\n";
            $errorMessage .= "4. MySQL kullanıcı adı ve şifresinin doğru olduğundan emin olun\n";
            
            logMessage("Database connection error: " . $e->getMessage(), 'error');
            
            // Development modunda detaylı hata göster
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                die("<pre>" . htmlspecialchars($errorMessage) . "</pre>");
            } else {
                die("Veritabanı bağlantısı kurulamadı. Lütfen sistem yöneticinize başvurun.");
            }
        }
    }
    
    /**
     * Singleton pattern
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Bağlantıyı döndürür
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * SQL sorgusu hazırlar
     */
    public function query($sql) {
        try {
            $this->stmt = $this->connection->prepare($sql);
            return $this;
        } catch (PDOException $e) {
            logMessage("Query prepare error: " . $e->getMessage(), 'error');
            throw $e;
        }
    }
    
    /**
     * Parametreleri bağlar
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }
    
    /**
     * Sorguyu çalıştırır
     */
    public function execute($params = []) {
        try {
            // Eğer params boşsa, bindValue() ile bağlanmış parametreler kullanılır
            if (empty($params)) {
                return $this->stmt->execute();
            }
            return $this->stmt->execute($params);
        } catch (PDOException $e) {
            logMessage("Query execution error: " . $e->getMessage(), 'error');
            throw $e;
        }
    }
    
    /**
     * Tüm sonuçları döndürür
     */
    public function fetchAll() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Tek sonuç döndürür
     */
    public function fetch() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Tek kolon değeri döndürür
     */
    public function fetchColumn() {
        $this->execute();
        return $this->stmt->fetchColumn();
    }
    
    /**
     * Satır sayısı döndürür
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Son eklenen ID'yi döndürür
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Transaction başlatır
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Transaction onaylar
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Transaction geri alır
     */
    public function rollback() {
        return $this->connection->rollback();
    }
    
    /**
     * SELECT sorgusu kısa yolu
     */
    public function select($table, $where = [], $columns = '*', $orderBy = '', $limit = '') {
        $sql = "SELECT {$columns} FROM {$table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $this->query($sql);
        
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $this->bind(":{$key}", $value);
            }
        }
        
        return $this->fetchAll();
    }
    
    /**
     * INSERT sorgusu kısa yolu
     */
    public function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);
        
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        
        $this->query($sql);
        
        foreach ($data as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        $this->execute();
        return $this->lastInsertId();
    }
    
    /**
     * UPDATE sorgusu kısa yolu
     */
    public function update($table, $data, $where) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        
        $whereConditions = [];
        foreach ($where as $key => $value) {
            $whereConditions[] = "{$key} = :where_{$key}";
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . 
               " WHERE " . implode(' AND ', $whereConditions);
        
        $this->query($sql);
        
        foreach ($data as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        foreach ($where as $key => $value) {
            $this->bind(":where_{$key}", $value);
        }
        
        return $this->execute();
    }
    
    /**
     * DELETE sorgusu kısa yolu
     */
    public function delete($table, $where) {
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = "{$key} = :{$key}";
        }
        
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $conditions);
        
        $this->query($sql);
        
        foreach ($where as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        return $this->execute();
    }
}
