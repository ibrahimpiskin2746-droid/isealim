<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
.jobs-page {
    min-height: 100vh;
    background: #f5f7fa;
    padding: 40px 20px;
}

.jobs-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 32px;
    font-weight: 700;
}

.page-header .subtitle {
    color: #666;
    font-size: 16px;
}

.filter-tabs {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 12px 24px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    color: #666;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.filter-tab:hover {
    border-color: #667eea;
    color: #667eea;
}

.filter-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.job-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    border: 2px solid #e8e8e8;
    transition: all 0.3s;
    position: relative;
}

.job-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.job-status-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.job-status-badge.active {
    background: #d4edda;
    color: #155724;
}

.job-status-badge.inactive {
    background: #fff3cd;
    color: #856404;
}

.job-status-badge.closed {
    background: #f8d7da;
    color: #721c24;
}

.job-card h3 {
    margin: 0 0 10px 0;
    font-size: 20px;
    font-weight: 700;
    color: #333;
    padding-right: 80px;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 15px 0;
    font-size: 14px;
    color: #666;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.job-meta-item i {
    color: #667eea;
}

.job-description {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin: 15px 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin: 20px 0;
    padding: 15px 0;
    border-top: 2px solid #f0f0f0;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: #666;
}

.job-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-job-action {
    flex: 1;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-edit {
    background: #f0f0f0;
    color: #555;
}

.btn-edit:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
}

.empty-state i {
    font-size: 80px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    font-size: 24px;
    color: #666;
}

.empty-state p {
    color: #999;
    margin-bottom: 30px;
}

.btn-create-job {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-create-job:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

@media (max-width: 768px) {
    .jobs-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-tabs {
        flex-direction: column;
    }
    
    .filter-tab {
        text-align: center;
    }
}
</style>

<div class="jobs-page">
    <div class="jobs-container">
        <div class="page-header">
            <h1>İş İlanlarım</h1>
            <p class="subtitle">Yayınladığınız iş ilanlarını görüntüleyin ve yönetin</p>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="<?= url('employer/jobs') ?>" class="filter-tab <?= empty($current_status) ? 'active' : '' ?>">
                <i class="fas fa-th"></i> Tümü
            </a>
            <a href="<?= url('employer/jobs?status=active') ?>" class="filter-tab <?= $current_status === 'active' ? 'active' : '' ?>">
                <i class="fas fa-check-circle"></i> Aktif
            </a>
            <a href="<?= url('employer/jobs?status=inactive') ?>" class="filter-tab <?= $current_status === 'inactive' ? 'active' : '' ?>">
                <i class="fas fa-pause-circle"></i> Pasif
            </a>
            <a href="<?= url('employer/jobs?status=closed') ?>" class="filter-tab <?= $current_status === 'closed' ? 'active' : '' ?>">
                <i class="fas fa-times-circle"></i> Kapalı
            </a>
            <a href="<?= url('employer/create-job') ?>" class="filter-tab" style="margin-left: auto; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-color: #11998e; color: white;">
                <i class="fas fa-plus"></i> Yeni İlan
            </a>
        </div>

        <!-- Jobs Grid -->
        <?php if (!empty($jobs)): ?>
        <div class="jobs-grid">
            <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <span class="job-status-badge <?= htmlspecialchars($job['status']) ?>">
                    <?php
                    $statusLabels = [
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'closed' => 'Kapalı'
                    ];
                    echo $statusLabels[$job['status']] ?? 'Bilinmiyor';
                    ?>
                </span>
                
                <h3><?= htmlspecialchars($job['title']) ?></h3>
                
                <div class="job-meta">
                    <div class="job-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars($job['location'] ?? 'Belirtilmemiş') ?></span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-briefcase"></i>
                        <span><?= htmlspecialchars($job['type'] ?? 'Tam Zamanlı') ?></span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-calendar"></i>
                        <span><?= date('d.m.Y', strtotime($job['created_at'])) ?></span>
                    </div>
                </div>
                
                <p class="job-description">
                    <?= htmlspecialchars(substr($job['description'] ?? '', 0, 150)) ?>...
                </p>
                
                <div class="job-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= $job['total_applications'] ?? 0 ?></div>
                        <div class="stat-label">Başvuru</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $job['pending_applications'] ?? 0 ?></div>
                        <div class="stat-label">Bekleyen</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $job['views'] ?? 0 ?></div>
                        <div class="stat-label">Görüntülenme</div>
                    </div>
                </div>
                
                <div class="job-actions">
                    <button class="btn-job-action btn-view" onclick="window.location.href='<?= url('job/' . $job['id']) ?>'">
                        <i class="fas fa-eye"></i>
                        Görüntüle
                    </button>
                    <button class="btn-job-action btn-edit" onclick="window.location.href='<?= url('employer/edit-job/' . $job['id']) ?>'">
                        <i class="fas fa-edit"></i>
                        Düzenle
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-briefcase"></i>
            <h3>Henüz İlan Yok</h3>
            <p>Şu anda gösterilecek iş ilanı bulunmuyor. İlk ilanınızı oluşturun!</p>
            <a href="<?= url('employer/create-job') ?>" class="btn-create-job">
                <i class="fas fa-plus"></i>
                İlk İlanımı Oluştur
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
