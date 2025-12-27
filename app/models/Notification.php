<?php
/**
 * Notification Model
 * Bildirim işlemleri
 */

class Notification extends Model {
    protected $table = 'notifications';
    
    /**
     * Kullanıcının bildirimlerini getirir
     */
    public function getUserNotifications($userId, $limit = 20, $unreadOnly = false) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        
        if ($unreadOnly) {
            $sql .= " AND is_read = 0";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT {$limit}";
        
        return $this->db->query($sql)->bind(':user_id', $userId)->fetchAll();
    }
    
    /**
     * Okunmamış bildirim sayısı
     */
    public function getUnreadCount($userId) {
        return $this->count(['user_id' => $userId, 'is_read' => 0]);
    }
    
    /**
     * Bildirimi okundu olarak işaretle
     */
    public function markAsRead($notificationId) {
        return $this->update($notificationId, ['is_read' => 1]);
    }
    
    /**
     * Tüm bildirimleri okundu olarak işaretle
     */
    public function markAllAsRead($userId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
        return $this->db->query($sql)->bind(':user_id', $userId)->execute();
    }
    
    /**
     * Bildirimi sil
     */
    public function deleteNotification($notificationId) {
        return $this->delete($notificationId);
    }
    
    /**
     * Toplu bildirim oluştur
     */
    public function createBulkNotifications($userIds, $title, $message, $type, $relatedId = null, $relatedType = null) {
        $sql = "INSERT INTO {$this->table} 
                (user_id, title, message, notification_type, related_id, related_type)
                VALUES (:user_id, :title, :message, :type, :related_id, :related_type)";
        
        $this->db->beginTransaction();
        
        try {
            foreach ($userIds as $userId) {
                $this->db->query($sql)
                    ->bind(':user_id', $userId)
                    ->bind(':title', $title)
                    ->bind(':message', $message)
                    ->bind(':type', $type)
                    ->bind(':related_id', $relatedId)
                    ->bind(':related_type', $relatedType)
                    ->execute();
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logMessage("Bulk notification error: " . $e->getMessage(), 'error');
            return false;
        }
    }
}
