<?php
/**
 * Temel Model Sınıfı
 * Tüm modeller bu sınıftan türetilir
 */

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Tüm kayıtları getirir
     */
    public function all($orderBy = 'id DESC', $limit = null) {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * ID ile kayıt getirir
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->query($sql)->bind(':id', $id)->fetch();
    }
    
    /**
     * Koşula göre kayıtları getirir
     */
    public function where($conditions, $orderBy = '', $limit = '') {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $query = $this->db->query($sql);
        
        foreach ($conditions as $key => $value) {
            $query->bind(":{$key}", $value);
        }
        
        return $query->fetchAll();
    }
    
    /**
     * Tek kayıt getirir
     */
    public function findWhere($conditions) {
        $sql = "SELECT * FROM {$this->table}";
        
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :{$key}";
        }
        $sql .= " WHERE " . implode(' AND ', $where) . " LIMIT 1";
        
        $query = $this->db->query($sql);
        
        foreach ($conditions as $key => $value) {
            $query->bind(":{$key}", $value);
        }
        
        return $query->fetch();
    }
    
    /**
     * Kayıt oluşturur
     */
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * Kayıt günceller
     */
    public function update($id, $data) {
        return $this->db->update($this->table, $data, [$this->primaryKey => $id]);
    }
    
    /**
     * Kayıt siler
     */
    public function delete($id) {
        return $this->db->delete($this->table, [$this->primaryKey => $id]);
    }
    
    /**
     * Toplam kayıt sayısı
     */
    public function count($where = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $query = $this->db->query($sql);
        
        foreach ($where as $key => $value) {
            $query->bind(":{$key}", $value);
        }
        
        $result = $query->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Kayıt var mı kontrol eder
     */
    public function exists($conditions) {
        return $this->count($conditions) > 0;
    }
    
    /**
     * Pagination ile kayıtları getirir
     */
    public function paginate($page = 1, $perPage = ITEMS_PER_PAGE, $where = [], $orderBy = 'id DESC') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY {$orderBy} LIMIT {$offset}, {$perPage}";
        
        $query = $this->db->query($sql);
        
        foreach ($where as $key => $value) {
            $query->bind(":{$key}", $value);
        }
        
        $items = $query->fetchAll();
        $total = $this->count($where);
        
        return [
            'items' => $items,
            'pagination' => paginate($total, $page, $perPage)
        ];
    }
}
