<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .notifications-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0 4rem;
    }

    .notifications-header {
        max-width: 1400px;
        margin: 0 auto 3rem;
        padding: 0 2rem;
        text-align: center;
    }

    .notifications-header h1 {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
    }

    .notifications-header p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .notifications-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
    }

    /* Profile Sidebar */
    .profile-sidebar {
        position: sticky;
        top: 2rem;
        height: fit-content;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #667eea;
        margin: 0 auto 1rem;
        display: block;
    }

    .profile-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .profile-position {
        color: #718096;
        font-size: 0.9rem;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.75rem;
        background: #f7fafc;
        border-radius: 10px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #718096;
        margin-top: 0.25rem;
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .profile-btn {
        padding: 0.75rem;
        border-radius: 10px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s;
        display: block;
    }

    .profile-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .profile-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(103, 126, 234, 0.3);
    }

    .profile-btn-secondary {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }

    .profile-btn-secondary:hover {
        background: #edf2f7;
    }

    .profile-completion {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f7fafc;
    }

    .completion-text {
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 0.5rem;
    }

    .completion-bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .completion-progress {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s;
    }

    /* Notifications Main */
    .notifications-main {
        min-width: 0;
    }

    .notifications-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .notifications-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .tab-btn {
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        color: #718096;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: #667eea;
        border-bottom-color: #667eea;
    }

    .notification-item {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        transition: all 0.3s;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .notification-item:hover {
        background: #f7fafc;
        border-color: #e2e8f0;
    }

    .notification-item.unread {
        background: #eef2ff;
        border-color: #c7d2fe;
    }

    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .notification-icon.success {
        background: #d1fae5;
    }

    .notification-icon.info {
        background: #dbeafe;
    }

    .notification-icon.warning {
        background: #fed7aa;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .notification-message {
        color: #718096;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .notification-time {
        color: #a0aec0;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #718096;
    }

    @media (max-width: 1024px) {
        .notifications-container {
            grid-template-columns: 1fr;
        }

        .profile-sidebar {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .notifications-header h1 {
            font-size: 2rem;
        }

        .notifications-tabs {
            overflow-x: auto;
        }

        .notification-item {
            flex-direction: column;
        }
    }
</style>

<div class="notifications-page">
    <div class="notifications-header">
        <h1>🔔 Bildirimler</h1>
        <p>Tüm güncellemeler ve bildirimleriniz burada</p>
    </div>

    <div class="notifications-container">
        
        <!-- Profile Sidebar -->
        <?php 
            $user = auth();
            $completionScore = 0;
            $totalFields = 10;
            $filledFields = 0;
            
            if (!empty($user['profile_image'])) $filledFields++;
            if (!empty($user['bio'])) $filledFields++;
            if (!empty($user['location'])) $filledFields++;
            if (!empty($user['skills'])) $filledFields++;
            if (!empty($user['education'])) $filledFields++;
            if (!empty($user['experience_years'])) $filledFields++;
            if (!empty($user['linkedin_url'])) $filledFields++;
            if (!empty($user['github_url'])) $filledFields++;
            if (!empty($user['cv_path'])) $filledFields++;
            if (!empty($user['current_position'])) $filledFields++;
            
            $completionScore = round(($filledFields / $totalFields) * 100);
        ?>
        <aside class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-header">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= url($user['profile_image']) ?>" alt="Profil" class="profile-avatar">
                    <?php else: ?>
                        <div class="profile-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 700;">
                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div class="profile-name"><?= htmlspecialchars($user['full_name']) ?></div>
                    <div class="profile-position"><?= htmlspecialchars($user['current_position'] ?? 'İş Arayan') ?></div>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value"><?= $user['experience_years'] ?? 0 ?></div>
                        <div class="stat-label">Yıl Deneyim</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $unread_count ?? 0 ?></div>
                        <div class="stat-label">Okunmamış</div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="<?= url('applicant/profile') ?>" class="profile-btn profile-btn-primary">
                        ✏️ Profili Düzenle
                    </a>
                    <a href="<?= url('applicant/applications') ?>" class="profile-btn profile-btn-secondary">
                        📋 Başvurularım
                    </a>
                    <a href="<?= url('jobs') ?>" class="profile-btn profile-btn-secondary">
                        💼 İş İlanları
                    </a>
                </div>

                <div class="profile-completion">
                    <div class="completion-text">
                        Profil Tamamlama: <strong><?= $completionScore ?>%</strong>
                    </div>
                    <div class="completion-bar">
                        <div class="completion-progress" style="width: <?= $completionScore ?>%"></div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Notifications Main -->
        <div class="notifications-main">
            <div class="notifications-card">
                <div class="notifications-tabs">
                    <button class="tab-btn active" onclick="filterNotifications('all')">
                        Tümü <?php if (!empty($notifications)): ?>(<?= count($notifications) ?>)<?php endif; ?>
                    </button>
                    <button class="tab-btn" onclick="filterNotifications('unread')">
                        Okunmamış <?php if ($unread_count > 0): ?>(<?= $unread_count ?>)<?php endif; ?>
                    </button>
                </div>

                <?php if (!empty($notifications) && is_array($notifications)): ?>
                    <div class="notifications-list">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" 
                                 onclick="markAsRead(<?= $notification['id'] ?>, '<?= htmlspecialchars($notification['action_url'] ?? '') ?>')">
                                
                                <div class="notification-icon <?= $notification['notification_type'] == 'status-change' ? 'info' : ($notification['notification_type'] == 'new-job' ? 'success' : 'info') ?>">
                                    <?php 
                                    switch($notification['notification_type']) {
                                        case 'application':
                                            echo '📄';
                                            break;
                                        case 'status-change':
                                            echo '🔄';
                                            break;
                                        case 'new-job':
                                            echo '💼';
                                            break;
                                        case 'message':
                                            echo '💬';
                                            break;
                                        default:
                                            echo '🔔';
                                    }
                                    ?>
                                </div>

                                <div class="notification-content">
                                    <div class="notification-title"><?= htmlspecialchars($notification['title']) ?></div>
                                    <div class="notification-message"><?= htmlspecialchars($notification['message']) ?></div>
                                    <div class="notification-time">
                                        <?php
                                        $time = strtotime($notification['created_at']);
                                        $diff = time() - $time;
                                        
                                        if ($diff < 60) {
                                            echo 'Az önce';
                                        } elseif ($diff < 3600) {
                                            echo floor($diff / 60) . ' dakika önce';
                                        } elseif ($diff < 86400) {
                                            echo floor($diff / 3600) . ' saat önce';
                                        } elseif ($diff < 604800) {
                                            echo floor($diff / 86400) . ' gün önce';
                                        } else {
                                            echo date('d.m.Y H:i', $time);
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">🔔</div>
                        <h3>Henüz Bildirim Yok</h3>
                        <p>Yeni bildirimleriniz burada görünecek</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function filterNotifications(type) {
        const items = document.querySelectorAll('.notification-item');
        const tabs = document.querySelectorAll('.tab-btn');
        
        tabs.forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        
        items.forEach(item => {
            if (type === 'all') {
                item.style.display = 'flex';
            } else if (type === 'unread') {
                item.style.display = item.classList.contains('unread') ? 'flex' : 'none';
            }
        });
    }

    function markAsRead(notificationId, actionUrl) {
        fetch('<?= url('applicant/markNotificationRead') ?>/' + notificationId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(() => {
            if (actionUrl) {
                window.location.href = actionUrl;
            } else {
                location.reload();
            }
        });
    }
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
