<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-brain"></i>
                <span>AI İşe Alım</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= url('employer/dashboard') ?>" class="nav-link active">
                <i class="fas fa-chart-line"></i> 
                <span>Dashboard</span>
                <span class="nav-badge">Live</span>
            </a>
            <a href="<?= url('employer/jobs') ?>" class="nav-link">
                <i class="fas fa-briefcase"></i> 
                <span>İlanlarım</span>
                <span class="nav-count"><?= isset($job_stats['active_jobs']) ? $job_stats['active_jobs'] : 0 ?></span>
            </a>
            <a href="<?= url('employer/create-job') ?>" class="nav-link nav-highlight">
                <i class="fas fa-magic"></i> 
                <span>AI İlan Oluştur</span>
            </a>
            <a href="<?= url('employer/applications') ?>" class="nav-link">
                <i class="fas fa-file-alt"></i> 
                <span>Başvurular</span>
                <span class="nav-count"><?= isset($application_stats['pending']) ? $application_stats['pending'] : 0 ?></span>
            </a>
            <a href="<?= url('employer/candidates') ?>" class="nav-link">
                <i class="fas fa-user-check"></i> 
                <span>AI Adaylar</span>
            </a>
            <a href="<?= url('employer/analytics') ?>" class="nav-link">
                <i class="fas fa-chart-bar"></i> 
                <span>Analytics</span>
            </a>
            <div class="nav-divider"></div>
            <a href="<?= url('employer/messages') ?>" class="nav-link">
                <i class="fas fa-envelope"></i> 
                <span>Mesajlar</span>
            </a>
            <a href="<?= url('employer/profile') ?>" class="nav-link">
                <i class="fas fa-user-circle"></i> 
                <span>Profil</span>
            </a>
        </nav>
        
        <!-- AI Assistant Widget in Sidebar -->
        <div class="sidebar-ai-widget">
            <div class="ai-widget-header">
                <div class="ai-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <h5>AI Asistan</h5>
                    <span class="ai-status">Çevrimiçi</span>
                </div>
            </div>
            <p class="ai-widget-text">Size nasıl yardımcı olabilirim?</p>
            <button class="btn-ai-chat">
                <i class="fas fa-comments"></i>
                Sohbet Başlat
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Welcome & Context Section -->
        <div class="welcome-banner">
            <div class="welcome-content">
                <h1 class="welcome-title">
                    Tekrar hoş geldiniz, <?php 
                    $user = auth();
                    $firstName = 'Kullanıcı';
                    if ($user && isset($user['full_name']) && !empty($user['full_name'])) {
                        $nameParts = explode(' ', trim($user['full_name']));
                        $firstName = $nameParts[0];
                    }
                    echo htmlspecialchars($firstName);
                    ?> 👋
                </h1>
                <p class="welcome-subtitle">
                    İşte iş ilanlarınızla ilgili bugün neler oluyor.
                </p>
            </div>
            <div class="welcome-date">
                <div class="date-box">
                    <i class="fas fa-calendar-day"></i>
                    <span><?php 
                    $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 
                               'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
                    echo date('d') . ' ' . $months[(int)date('n') - 1] . ' ' . date('Y');
                    ?></span>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards with AI Insights -->
        <div class="metrics-grid">
            <div class="metric-card metric-primary">
                <div class="metric-icon-wrapper">
                    <div class="metric-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <div class="metric-content">
                    <div class="metric-value counter" data-target="<?= isset($job_stats['active_jobs']) ? (int)$job_stats['active_jobs'] : 0 ?>">0</div>
                    <div class="metric-label">Aktif İş İlanları</div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+<?= rand(5, 15) ?>% bu hafta</span>
                    </div>
                </div>
            </div>

            <div class="metric-card metric-success">
                <div class="metric-icon-wrapper">
                    <div class="metric-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="metric-content">
                    <div class="metric-value counter" data-target="<?= isset($application_stats['total_applications']) ? (int)$application_stats['total_applications'] : 0 ?>">0</div>
                    <div class="metric-label">Toplam Başvuru</div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+<?= rand(15, 35) ?>% bu hafta</span>
                    </div>
                </div>
            </div>

            <div class="metric-card metric-warning">
                <div class="metric-icon-wrapper">
                    <div class="metric-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                </div>
                <div class="metric-content">
                    <div class="metric-value counter" data-target="<?= rand(50, 95) ?>">0</div>
                    <div class="metric-label">AI Eşleşme Skoru</div>
                    <div class="metric-trend">
                        <i class="fas fa-brain"></i>
                        <span>Ortalama %<?= rand(75, 92) ?> uyum</span>
                    </div>
                </div>
            </div>

            <div class="metric-card metric-info">
                <div class="metric-icon-wrapper">
                    <div class="metric-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="metric-content">
                    <div class="metric-value counter" data-target="<?= isset($application_stats['pending']) ? (int)$application_stats['pending'] : 0 ?>">0</div>
                    <div class="metric-label">İncelenmesi Gereken</div>
                    <div class="metric-trend">
                        <i class="fas fa-hourglass-half"></i>
                        <span>Bekliyor</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Activity Timeline -->
        <div class="ai-activity-section">
            <div class="activity-header">
                <div class="activity-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div>
                    <h3>AI Aktivite Akışı</h3>
                    <p>Son 24 saatteki AI işlemleri</p>
                </div>
                <div class="activity-live">
                    <span class="live-indicator"></span>
                    <span>Canlı</span>
                </div>
            </div>
            
            <div class="activity-timeline">
                <div class="activity-item">
                    <div class="activity-icon-badge success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="activity-content">
                        <h4>3 yeni başvuru AI tarafından değerlendirildi</h4>
                        <p>Senior PHP Developer pozisyonu için otomatik puanlama tamamlandı. En yüksek skor: %92</p>
                        <span class="activity-time"><i class="far fa-clock"></i> 15 dakika önce</span>
                    </div>
                    <div class="activity-action">
                        <button class="btn-mini" onclick="showAIApplications()">Görüntüle</button>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon-badge primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="activity-content">
                        <h4>5 CV otomatik olarak parse edildi</h4>
                        <p>AI, başvuru sahiplerinin becerilerini ve deneyimlerini başarıyla çıkardı</p>
                        <span class="activity-time"><i class="far fa-clock"></i> 1 saat önce</span>
                    </div>
                    <div class="activity-action">
                        <button class="btn-mini" onclick="showParsedCVs()">Detaylar</button>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon-badge warning">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Yeni iş ilanı için form oluşturuldu</h4>
                        <p>Frontend Developer pozisyonu için AI tarafından özelleştirilmiş başvuru formu hazırlandı</p>
                        <span class="activity-time"><i class="far fa-clock"></i> 3 saat önce</span>
                    </div>
                    <div class="activity-action">
                        <button class="btn-mini" onclick="window.location.href='<?= url('employer/demo-form') ?>'">İncele</button>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon-badge info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Haftalık AI raporu hazırlandı</h4>
                        <p>İşe alım trendleri ve öneriler içeren analiz raporu oluşturuldu</p>
                        <span class="activity-time"><i class="far fa-clock"></i> 5 saat önce</span>
                    </div>
                    <div class="activity-action">
                        <a href="<?= url('employer/ai-report') ?>" class="btn-mini">Raporları Gör</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Recommendations Panel -->
        <div class="ai-recommendations-panel">
            <div class="recommendations-header">
                <div class="ai-badge-large">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h3>AI Önerileri</h3>
                    <p>Sizin için kişiselleştirilmiş öneriler</p>
                </div>
            </div>
            
            <div class="recommendations-grid">
                <div class="recommendation-card">
                    <div class="recommendation-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h4>Yüksek Potansiyelli Adaylar</h4>
                    <p>3 aday profil gereksinimlerinizle %85+ uyum sağlıyor</p>
                    <a href="<?= url('employer/applications?filter=top') ?>" class="recommendation-link">
                        Adayları İncele <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="recommendation-card">
                    <div class="recommendation-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h4>İlan Optimizasyonu</h4>
                    <p>İlanınızı %40 daha fazla nitelikli başvuru almak için optimize edin</p>
                    <a href="#" class="recommendation-link">
                        Optimizasyon Önerileri <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="recommendation-card">
                    <div class="recommendation-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>Görüşme Soruları</h4>
                    <p>AI, pozisyonunuz için özelleştirilmiş 12 görüşme sorusu hazırladı</p>
                    <a href="#" class="recommendation-link">
                        Soruları Görüntüle <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="recommendation-card">
                    <div class="recommendation-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h4>Benchmark Analizi</h4>
                    <p>İlanlarınız sektör ortalamasının %15 üzerinde performans gösteriyor</p>
                    <a href="#" class="recommendation-link">
                        Karşılaştır <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Primary Action Panel - AI Job Creation -->
        <div class="cta-panel">
            <div class="cta-icon-bg">
                <i class="fas fa-magic"></i>
            </div>
            <div class="cta-content">
                <h2 class="cta-title">Yeni İş İlanı Oluştur</h2>
                <p class="cta-description">
                    Pozisyonu doğal dilde tanımlayın ve AI'ın sizin için otomatik olarak başvuru formunu oluşturmasına izin verin.
                </p>
                <ul class="cta-features">
                    <li><i class="fas fa-check-circle"></i> AI destekli form oluşturma</li>
                    <li><i class="fas fa-check-circle"></i> Akıllı aday değerlendirme</li>
                    <li><i class="fas fa-check-circle"></i> Otomatik uyum puanlaması</li>
                </ul>
            </div>
            <div class="cta-action">
                <a href="<?= url('employer/create-job') ?>" class="btn-cta">
                    <span class="btn-icon"><i class="fas fa-plus-circle"></i></span>
                    <span class="btn-text">AI ile İş İlanı Oluştur</span>
                    <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                </a>
                <p class="cta-help">
                    <i class="fas fa-info-circle"></i>
                    Sadece 2-3 dakika sürer
                </p>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Left Column: Jobs & Applications -->
            <div class="dashboard-left-column">
                <!-- Recent Jobs Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fas fa-briefcase"></i>
                            <h3>Son İş İlanlarınız</h3>
                        </div>
                        <a href="<?= url('employer/jobs') ?>" class="btn-link">
                            Tümünü Görüntüle <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <?php 
                    $recentJobs = $recent_jobs ?? [];
                    if (!empty($recentJobs)): 
                    ?>
                    <div class="jobs-table-wrapper">
                        <table class="jobs-table">
                            <thead>
                                <tr>
                                    <th>İş Pozisyonu</th>
                                    <th>Durum</th>
                                    <th>Başvurular</th>
                                    <th>AI Skor</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recentJobs, 0, 5) as $job): ?>
                                <tr class="job-row">
                                    <td>
                                        <div class="job-title-cell">
                                            <div class="job-icon">
                                                <i class="fas fa-code"></i>
                                            </div>
                                            <div>
                                                <div class="job-name"><?= htmlspecialchars($job['title'] ?? 'İsimsiz İlan') ?></div>
                                                <div class="job-meta">
                                                    <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location'] ?? 'Belirtilmemiş') ?></span>
                                                    <span><i class="fas fa-clock"></i> <?php 
                                                    if (isset($job['created_at']) && !empty($job['created_at'])) {
                                                        $months = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 
                                                                   'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
                                                        $timestamp = strtotime($job['created_at']);
                                                        echo date('d', $timestamp) . ' ' . $months[(int)date('n', $timestamp) - 1];
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusConfig = [
                                            'published' => ['class' => 'status-active', 'icon' => 'check-circle', 'text' => 'Aktif'],
                                            'draft' => ['class' => 'status-draft', 'icon' => 'edit', 'text' => 'Taslak'],
                                            'closed' => ['class' => 'status-closed', 'icon' => 'times-circle', 'text' => 'Kapalı']
                                        ];
                                        $jobStatus = $job['status'] ?? 'draft';
                                        $status = $statusConfig[$jobStatus] ?? $statusConfig['draft'];
                                        ?>
                                        <span class="status-badge <?= $status['class'] ?>">
                                            <i class="fas fa-<?= $status['icon'] ?>"></i>
                                            <?= $status['text'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="applicant-count">
                                            <i class="fas fa-user-friends"></i>
                                            <strong><?= isset($job['application_count']) ? (int)$job['application_count'] : 0 ?></strong> başvuru
                                        </div>
                                    </td>
                                    <td>
                                        <div class="match-score">
                                            <div class="score-bar">
                                                <div class="score-fill" style="width: <?= rand(75, 95) ?>%"></div>
                                            </div>
                                            <span class="score-text"><?= rand(75, 95) ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if (isset($job['id'])): ?>
                                            <a href="<?= url('employer/job/' . (int)$job['id']) ?>" class="btn-action" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= url('employer/edit-job/' . (int)$job['id']) ?>" class="btn-action" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= url('employer/applications?job_id=' . (int)$job['id']) ?>" class="btn-action" title="Adaylar">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state" style="padding: 3rem 2rem; text-align: center;">
                        <div class="empty-icon" style="width: 100px; height: 100px; margin: 0 auto 1.5rem; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #9ca3af;">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4 style="font-size: 1.5rem; margin-bottom: 0.75rem; color: #111827;">Henüz İş İlanınız Yok</h4>
                        <p style="color: #6b7280; margin-bottom: 1.5rem;">İlk iş ilanınızı oluşturarak başlayın</p>
                        <a href="<?= url('employer/create-job') ?>" class="btn-primary-empty" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #667eea; color: white; padding: 1rem 2rem; border-radius: 10px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                            <i class="fas fa-plus-circle"></i>
                            İlk İlanınızı Oluşturun
                        </a>
                    </div>
                    
                    <!-- Mock Data (Demo için - gerçek veri yoksa gösterilir) -->
                    <div class="jobs-table-wrapper">
                        <table class="jobs-table">
                            <thead>
                                <tr>
                                    <th>İş Pozisyonu</th>
                                    <th>Durum</th>
                                    <th>Başvurular</th>
                                    <th>AI Skor</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Mock Data Row 1 -->
                                <tr class="job-row">
                                    <td>
                                        <div class="job-title-cell">
                                            <div class="job-icon" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.1));">
                                                <i class="fas fa-code" style="color: #667eea;"></i>
                                            </div>
                                            <div>
                                                <div class="job-name">Senior PHP Developer</div>
                                                <div class="job-meta">
                                                    <span><i class="fas fa-map-marker-alt"></i> İstanbul, Türkiye</span>
                                                    <span><i class="fas fa-clock"></i> 5 gün önce</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle"></i>
                                            Aktif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="applicant-count">
                                            <i class="fas fa-user-friends"></i>
                                            <strong>24</strong> başvuru
                                        </div>
                                    </td>
                                    <td>
                                        <div class="match-score">
                                            <div class="score-bar">
                                                <div class="score-fill" style="width: 89%"></div>
                                            </div>
                                            <span class="score-text">89%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= url('employer/jobs/generate-form') ?>" class="btn-action" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= url('employer/jobs/generate-form') ?>" class="btn-action" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= url('employer/applications') ?>" class="btn-action" title="Adaylar">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Mock Data Row 2 -->
                                <tr class="job-row">
                                    <td>
                                        <div class="job-title-cell">
                                            <div class="job-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(52, 211, 153, 0.1));">
                                                <i class="fas fa-laptop-code" style="color: #10b981;"></i>
                                            </div>
                                            <div>
                                                <div class="job-name">Frontend Developer (React)</div>
                                                <div class="job-meta">
                                                    <span><i class="fas fa-map-marker-alt"></i> İstanbul, Türkiye</span>
                                                    <span><i class="fas fa-clock"></i> 3 gün önce</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle"></i>
                                            Aktif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="applicant-count">
                                            <i class="fas fa-user-friends"></i>
                                            <strong>18</strong> başvuru
                                        </div>
                                    </td>
                                    <td>
                                        <div class="match-score">
                                            <div class="score-bar">
                                                <div class="score-fill" style="width: 92%"></div>
                                            </div>
                                            <span class="score-text">92%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= url('employer/jobs/generate-form') ?>" class="btn-action" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= url('employer/jobs/generate-form') ?>" class="btn-action" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= url('employer/applications') ?>" class="btn-action" title="Adaylar">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Mock Data Row 3 -->
                                <tr class="job-row">
                                    <td>
                                        <div class="job-title-cell">
                                            <div class="job-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(251, 191, 36, 0.1));">
                                                <i class="fas fa-mobile-alt" style="color: #f59e0b;"></i>
                                            </div>
                                            <div>
                                                <div class="job-name">Mobile Developer (Flutter)</div>
                                                <div class="job-meta">
                                                    <span><i class="fas fa-map-marker-alt"></i> Ankara, Türkiye</span>
                                                    <span><i class="fas fa-clock"></i> 1 hafta önce</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-draft">
                                            <i class="fas fa-edit"></i>
                                            Taslak
                                        </span>
                                    </td>
                                    <td>
                                        <div class="applicant-count">
                                            <i class="fas fa-user-friends"></i>
                                            <strong>0</strong> başvuru
                                        </div>
                                    </td>
                                    <td>
                                        <div class="match-score">
                                            <div class="score-bar">
                                                <div class="score-fill" style="width: 0%"></div>
                                            </div>
                                            <span class="score-text">-</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= url('employer/jobs/generate-form') ?>" class="btn-action" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= url('employer/jobs/generate-form') ?>" class="btn-action" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= url('employer/applications') ?>" class="btn-action" title="Adaylar">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Applications Section -->
                <div class="dashboard-section" style="margin-top: 2rem;">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fas fa-users"></i>
                            <h3>Son Başvurular</h3>
                        </div>
                        <a href="<?= url('employer/applications') ?>" class="btn-link">
                            Tümünü Görüntüle <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="applications-list">
                        <!-- Mock Application 1 -->
                        <div class="application-item">
                            <div class="applicant-avatar">
                                <img src="https://ui-avatars.com/api/?name=Ahmet+Yilmaz&background=667eea&color=fff&size=80" alt="Avatar">
                                <span class="ai-score-badge">94</span>
                            </div>
                            <div class="applicant-info">
                                <h4>Ahmet Yılmaz</h4>
                                <p class="applicant-position">Senior PHP Developer başvurusu</p>
                                <div class="applicant-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> İstanbul</span>
                                    <span><i class="fas fa-briefcase"></i> 7 yıl deneyim</span>
                                    <span><i class="fas fa-clock"></i> 2 saat önce</span>
                                </div>
                                <div class="applicant-skills">
                                    <span class="skill-tag">PHP</span>
                                    <span class="skill-tag">Laravel</span>
                                    <span class="skill-tag">MySQL</span>
                                    <span class="skill-tag">Vue.js</span>
                                </div>
                            </div>
                            <div class="applicant-actions">
                                <div class="ai-match-indicator excellent">
                                    <i class="fas fa-robot"></i>
                                    <span>Mükemmel Eşleşme</span>
                                </div>
                                <button class="btn-primary-small">CV'yi İncele</button>
                                <button class="btn-secondary-small">Profil</button>
                            </div>
                        </div>

                        <!-- Mock Application 2 -->
                        <div class="application-item">
                            <div class="applicant-avatar">
                                <img src="https://ui-avatars.com/api/?name=Zeynep+Kaya&background=10b981&color=fff&size=80" alt="Avatar">
                                <span class="ai-score-badge">88</span>
                            </div>
                            <div class="applicant-info">
                                <h4>Zeynep Kaya</h4>
                                <p class="applicant-position">Frontend Developer başvurusu</p>
                                <div class="applicant-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> Ankara</span>
                                    <span><i class="fas fa-briefcase"></i> 5 yıl deneyim</span>
                                    <span><i class="fas fa-clock"></i> 4 saat önce</span>
                                </div>
                                <div class="applicant-skills">
                                    <span class="skill-tag">React</span>
                                    <span class="skill-tag">TypeScript</span>
                                    <span class="skill-tag">Node.js</span>
                                    <span class="skill-tag">Tailwind</span>
                                </div>
                            </div>
                            <div class="applicant-actions">
                                <div class="ai-match-indicator good">
                                    <i class="fas fa-robot"></i>
                                    <span>İyi Eşleşme</span>
                                </div>
                                <button class="btn-primary-small">CV'yi İncele</button>
                                <button class="btn-secondary-small">Profil</button>
                            </div>
                        </div>

                        <!-- Mock Application 3 -->
                        <div class="application-item">
                            <div class="applicant-avatar">
                                <img src="https://ui-avatars.com/api/?name=Mehmet+Demir&background=f59e0b&color=fff&size=80" alt="Avatar">
                                <span class="ai-score-badge">76</span>
                            </div>
                            <div class="applicant-info">
                                <h4>Mehmet Demir</h4>
                                <p class="applicant-position">Senior PHP Developer başvurusu</p>
                                <div class="applicant-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> İzmir</span>
                                    <span><i class="fas fa-briefcase"></i> 4 yıl deneyim</span>
                                    <span><i class="fas fa-clock"></i> 1 gün önce</span>
                                </div>
                                <div class="applicant-skills">
                                    <span class="skill-tag">PHP</span>
                                    <span class="skill-tag">Symfony</span>
                                    <span class="skill-tag">PostgreSQL</span>
                                    <span class="skill-tag">Docker</span>
                                </div>
                            </div>
                            <div class="applicant-actions">
                                <div class="ai-match-indicator moderate">
                                    <i class="fas fa-robot"></i>
                                    <span>Orta Eşleşme</span>
                                </div>
                                <button class="btn-primary-small">CV'yi İncele</button>
                                <button class="btn-secondary-small">Profil</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: AI Insights & Quick Actions -->
            <div class="dashboard-right-column">
                <!-- AI Insights Card -->
                <div class="insights-card">
                    <div class="insights-header">
                        <div class="insights-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h3>AI İçgörüler</h3>
                    </div>
                    <div class="insights-content">
                        <div class="insight-item">
                            <div class="insight-icon success">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="insight-text">
                                <strong>5 aday</strong> pozisyonlarınız için <strong>%90+</strong> uyum sağlıyor
                            </div>
                        </div>
                        <div class="insight-item">
                            <div class="insight-icon info">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="insight-text">
                                Son ilanınız <strong>24 saat</strong> içinde <strong>32 başvuru</strong> aldı
                            </div>
                        </div>
                        <div class="insight-item">
                            <div class="insight-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="insight-text">
                                <strong>8 başvuru</strong> incelemenizi bekliyor
                            </div>
                        </div>
                        <div class="insight-item">
                            <div class="insight-icon primary">
                                <i class="fas fa-magic"></i>
                            </div>
                            <div class="insight-text">
                                AI, <strong>3 adayı</strong> ön görüşme için öneriyor
                            </div>
                        </div>
                    </div>
                    <div class="insights-footer">
                        <a href="#" class="btn-insights">
                            <span>Tüm İçgörüler</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="quick-actions-card">
                    <h4><i class="fas fa-bolt"></i> Hızlı İşlemler</h4>
                    <div class="quick-actions-list">
                        <a href="<?= url('employer/create-job') ?>" class="quick-action-item">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-magic"></i>
                            </div>
                            <div>
                                <h5>AI İlan Oluştur</h5>
                                <p>2 dakikada yeni ilan</p>
                            </div>
                        </a>
                        
                        <a href="<?= url('employer/applications') ?>" class="quick-action-item">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h5>Başvuruları İncele</h5>
                                <p>8 yeni başvuru</p>
                            </div>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <h5>Raporları Görüntüle</h5>
                                <p>Haftalık analiz</p>
                            </div>
                        </a>
                        
                        <a href="#" class="quick-action-item">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h5>Görüşme Planla</h5>
                                <p>3 aday bekliyor</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Performance Chart Card -->
                <div class="chart-card">
                    <h4><i class="fas fa-chart-line"></i> Son 7 Gün Performansı</h4>
                    <div class="chart-placeholder">
                        <div class="chart-bars">
                            <div class="chart-bar" style="height: 45%;">
                                <span class="bar-value">12</span>
                            </div>
                            <div class="chart-bar" style="height: 65%;">
                                <span class="bar-value">18</span>
                            </div>
                            <div class="chart-bar" style="height: 35%;">
                                <span class="bar-value">9</span>
                            </div>
                            <div class="chart-bar" style="height: 85%;">
                                <span class="bar-value">24</span>
                            </div>
                            <div class="chart-bar" style="height: 55%;">
                                <span class="bar-value">15</span>
                            </div>
                            <div class="chart-bar" style="height: 75%;">
                                <span class="bar-value">21</span>
                            </div>
                            <div class="chart-bar" style="height: 90%;">
                                <span class="bar-value">32</span>
                            </div>
                        </div>
                        <div class="chart-labels">
                            <span>Pzt</span>
                            <span>Sal</span>
                            <span>Çar</span>
                            <span>Per</span>
                            <span>Cum</span>
                            <span>Cmt</span>
                            <span>Paz</span>
                        </div>
                    </div>
                    <div class="chart-stats">
                        <div class="stat-item">
                            <span class="stat-label">Toplam Başvuru</span>
                            <span class="stat-value">131</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Ortalama/Gün</span>
                            <span class="stat-value">18.7</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Artış</span>
                            <span class="stat-value positive">+24%</span>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-life-ring"></i>
                    </div>
                    <h4>Yardıma mı ihtiyacınız var?</h4>
                    <p>AI asistanımız ve destek ekibimiz size yardımcı olmak için burada.</p>
                    <button class="btn-help">
                        <i class="fas fa-comments"></i>
                        Destek Al
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- AI Chat Modal -->
<div id="aiChatModal" class="ai-modal">
    <div class="ai-modal-content">
        <div class="ai-modal-header">
            <div class="ai-avatar-large">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <h3>AI İşe Alım Asistanı</h3>
                <p>Size nasıl yardımcı olabilirim?</p>
            </div>
            <button class="ai-modal-close" onclick="closeAIChat()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="ai-modal-body">
            <div class="ai-chat-messages" id="aiChatMessages">
                <div class="ai-message ai-message-bot">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        Merhaba! Ben AI işe alım asistanınızım. Size nasıl yardımcı olabilirim?
                    </div>
                </div>
            </div>
            <div class="ai-chat-input-area">
                <input type="text" id="aiChatInput" placeholder="Bir soru sorun veya yardım isteyin..." onkeypress="if(event.key === 'Enter') sendAIMessage()">
                <button onclick="sendAIMessage()" class="btn-send-message">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* PROFESSIONAL EMPLOYER DASHBOARD - ENHANCED STYLES */
* { box-sizing: border-box; }

.dashboard-main {
    flex: 1;
    padding: 2rem;
    background: #f8fafc;
    overflow-x: hidden;
}

@media (max-width: 768px) {
    .dashboard-main {
        padding: 1rem;
    }
}

/* Welcome Banner */
.welcome-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2.5rem 3rem;
    margin-bottom: 2.5rem;
    color: white;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.welcome-banner:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.4);
}
.welcome-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}
.welcome-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem;
}
.welcome-subtitle {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.95;
}
.date-box {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
}

/* Metrics Grid */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.metric-card {
    background: white;
    border-radius: 18px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: currentColor;
    transform: scaleY(0);
    transition: transform 0.3s;
}
.metric-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}
.metric-card:hover::before { transform: scaleY(1); }
.metric-icon {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    transition: transform 0.3s;
}
.metric-card:hover .metric-icon {
    transform: scale(1.1) rotate(-5deg);
}
.metric-primary { color: #667eea; }
.metric-primary .metric-icon {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
}
.metric-success { color: #10b981; }
.metric-success .metric-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(52, 211, 153, 0.1) 100%);
    color: #10b981;
}
.metric-warning { color: #f59e0b; }
.metric-warning .metric-icon {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.1) 100%);
    color: #f59e0b;
}
.metric-info { color: #3b82f6; }
.metric-info .metric-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(96, 165, 250, 0.1) 100%);
    color: #3b82f6;
}
.metric-value {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
    color: #111827;
    margin-bottom: 0.5rem;
}
.metric-label {
    font-size: 0.95rem;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.metric-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
}
.metric-trend.positive { color: #10b981; }

/* CTA Panel */
.cta-panel {
    background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
    border: 2px dashed #667eea;
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 2.5rem;
    align-items: center;
}
.cta-icon-bg {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    animation: float 3s ease-in-out infinite;
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.cta-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 0.75rem;
}
.cta-description {
    font-size: 1.05rem;
    color: #6b7280;
    margin: 0 0 1.25rem;
}
.cta-features {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}
.cta-features li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
    font-weight: 500;
}
.cta-features i { color: #10b981; }
.btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.25rem 2.5rem;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    transition: all 0.3s;
}
.btn-cta:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5);
}
.cta-help {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #9ca3af;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
}
.dashboard-section {
    background: white;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

.dashboard-section:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 2.5rem;
    border-bottom: 1px solid #e5e7eb;
}
.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.section-title h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
}
.btn-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Jobs Table */
.jobs-table-wrapper { overflow-x: auto; }
.jobs-table { width: 100%; border-collapse: collapse; }
.jobs-table thead { background: #f9fafb; }
.jobs-table th {
    text-align: left;
    padding: 1.25rem 2rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #374151;
    text-transform: uppercase;
}
.jobs-table td {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e5e7eb;
}
.job-row {
    transition: background-color 0.2s ease;
}

.job-row:hover { 
    background: #f9fafb; 
    transform: scale(1.01);
}
.job-title-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.job-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
}
.job-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
}
.job-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: #9ca3af;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}
.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}
.status-draft {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}
.applicant-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.match-score {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.score-bar {
    width: 80px;
    height: 8px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}
.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    transition: width 1s ease;
}
.score-text { font-weight: 700; }
.action-buttons { display: flex; gap: 0.5rem; }
.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-action:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}
.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #9ca3af;
}
.empty-state h4 {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
}
.btn-primary-empty {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #667eea;
    color: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Sidebar */
.dashboard-sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.insights-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.insights-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}
.insights-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.insights-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.insights-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
}
.insights-content { padding: 1.5rem; }
.insight-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 0.75rem;
}
.insight-item:hover { background: #f9fafb; }
.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}
.insight-icon.success { background: #10b981; }
.insight-icon.info { background: #3b82f6; }
.insight-icon.warning { background: #f59e0b; }
.insight-icon.primary { background: #667eea; }
.insight-text {
    font-size: 0.9rem;
    color: #374151;
}
.insights-footer { padding: 0 1.5rem 1.5rem; }
.btn-insights {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.875rem;
    background: #f3f4f6;
    color: #667eea;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
}
.btn-insights:hover {
    background: #667eea;
    color: white;
}

/* Quick Stats */
.quick-stats-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
}
.quick-stats-card h4 {
    margin: 0 0 1.5rem;
    font-size: 1.1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.stat-row {
    display: flex;
    justify-content: space-between;
    padding: 0.875rem 0;
    border-bottom: 1px solid #e5e7eb;
}
.stat-row:last-child { border-bottom: none; }
.stat-label {
    font-size: 0.9rem;
    color: #6b7280;
}
.stat-value {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.progress-mini {
    width: 60px;
    height: 6px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

/* Help Card */
.help-card {
    background: #f6f8fb;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
}
.help-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #667eea;
}
.help-card h4 {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
}
.help-card p {
    color: #6b7280;
    font-size: 0.9rem;
    margin: 0 0 1.25rem;
}
.btn-help {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    color: #667eea;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

/* Responsive Design */
@media (max-width: 1400px) {
    .dashboard-grid {
        grid-template-columns: 1fr 350px;
    }
}

@media (max-width: 1200px) {
    .dashboard-grid { 
        grid-template-columns: 1fr; 
    }
    .dashboard-right-column {
        order: -1;
    }
    .cta-panel {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 1.5rem;
    }
    .cta-icon-bg {
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .dashboard-main {
        padding: 1rem;
    }
    .welcome-banner {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
        gap: 1rem;
    }
    .welcome-title {
        font-size: 1.5rem;
    }
    .metrics-grid { 
        grid-template-columns: 1fr; 
        gap: 1rem;
    }
    .metric-card {
        padding: 1.5rem;
    }
    .dashboard-grid { 
        grid-template-columns: 1fr; 
        gap: 1rem;
    }
    .ai-activity-section,
    .ai-recommendations-panel,
    .cta-panel {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .recommendations-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .jobs-table-wrapper {
        overflow-x: auto;
    }
    .jobs-table {
        min-width: 800px;
    }
    .application-item {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .applicant-actions {
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
    }
    .activity-item {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .activity-action {
        justify-content: flex-start;
    }
}

/* Enhanced Sidebar Styles */
.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 800;
    color: #667eea;
}
.sidebar-logo i {
    font-size: 1.5rem;
}
.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
}
.nav-link span:first-of-type {
    flex: 1;
}
.nav-badge {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    animation: pulse 2s infinite;
}
.nav-count {
    background: #667eea;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    min-width: 24px;
    text-align: center;
}
.nav-highlight {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
    border-left: 3px solid #667eea;
}
.nav-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 1rem 0;
}

/* AI Assistant Widget in Sidebar */
.sidebar-ai-widget {
    margin-top: auto;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    color: white;
}
.ai-widget-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}
.ai-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.ai-widget-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
}
.ai-status {
    font-size: 0.75rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.ai-status::before {
    content: '';
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    animation: blink 2s infinite;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
.ai-widget-text {
    font-size: 0.9rem;
    margin: 0 0 1rem;
    opacity: 0.95;
}
.btn-ai-chat {
    width: 100%;
    background: white;
    color: #667eea;
    border: none;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
}
.btn-ai-chat:hover {
    background: #f3f4f6;
    transform: translateY(-2px);
}

/* AI Activity Timeline */
.ai-activity-section {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
}
.activity-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f3f4f6;
}
.activity-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}
.activity-header h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 0.25rem;
}
.activity-header p {
    color: #6b7280;
    margin: 0;
}
.activity-header > div:nth-child(2) {
    flex: 1;
}
.activity-live {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}
.live-indicator {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: blink 2s infinite;
}
.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.activity-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 1.5rem;
    align-items: start;
    padding: 1.5rem;
    background: #f9fafb;
    border-radius: 12px;
    transition: all 0.3s;
}
.activity-item:hover {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateX(5px);
}
.activity-icon-badge {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}
.activity-icon-badge.success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}
.activity-icon-badge.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.activity-icon-badge.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}
.activity-icon-badge.info {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
}
.activity-content h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
}
.activity-content p {
    color: #6b7280;
    margin: 0 0 0.75rem;
    font-size: 0.95rem;
    line-height: 1.5;
}
.activity-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #9ca3af;
    font-weight: 600;
}
.activity-action {
    display: flex;
    align-items: center;
}
.btn-mini {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-mini:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* AI Recommendations Panel */
.ai-recommendations-panel {
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border: 2px solid #e5e7eb;
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2.5rem;
}
.recommendations-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.ai-badge-large {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 8px 24px rgba(251, 191, 36, 0.4);
    animation: float 3s ease-in-out infinite;
}
.recommendations-header h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 0.25rem;
}
.recommendations-header p {
    color: #6b7280;
    margin: 0;
}
.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.recommendation-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}
.recommendation-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transform: scaleX(0);
    transition: transform 0.3s;
}
.recommendation-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}
.recommendation-card:hover::before {
    transform: scaleX(1);
}
.recommendation-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #667eea;
    margin-bottom: 1.5rem;
}
.recommendation-card h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.75rem;
}
.recommendation-card p {
    color: #6b7280;
    margin: 0 0 1.5rem;
    font-size: 0.95rem;
    line-height: 1.6;
}
.recommendation-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.2s;
}
.recommendation-link:hover {
    gap: 0.75rem;
    color: #5568d3;
}
.recommendation-link i {
    transition: transform 0.2s;
}
.recommendation-link:hover i {
    transform: translateX(3px);
}

/* Dashboard Grid Layout */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    margin-bottom: 3rem;
}
.dashboard-left-column {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}
.dashboard-right-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Applications List */
.applications-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding: 1.5rem;
}
.application-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.application-item:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
    border-color: #667eea;
}
.applicant-avatar {
    position: relative;
}
.applicant-avatar img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
}
.ai-score-badge {
    position: absolute;
    bottom: -8px;
    right: -8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    border: 3px solid white;
}
.applicant-info {
    flex: 1;
}
.applicant-info h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
}
.applicant-position {
    color: #6b7280;
    font-size: 0.95rem;
    margin: 0 0 0.75rem;
}
.applicant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}
.applicant-meta span {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}
.applicant-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.skill-tag {
    background: #f3f4f6;
    color: #374151;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 600;
}
.applicant-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    align-items: flex-end;
}
.ai-match-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    white-space: nowrap;
}
.ai-match-indicator.excellent {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}
.ai-match-indicator.good {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}
.ai-match-indicator.moderate {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}
.btn-primary-small,
.btn-secondary-small {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-primary-small {
    background: #667eea;
    color: white;
}
.btn-primary-small:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}
.btn-secondary-small {
    background: #f3f4f6;
    color: #374151;
}
.btn-secondary-small:hover {
    background: #e5e7eb;
}

/* Quick Actions Card */
.quick-actions-card {
    background: white;
    border-radius: 18px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

.quick-actions-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
.quick-actions-card h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 1.5rem;
}
.quick-actions-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.quick-action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s;
}
.quick-action-item:hover {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateX(4px);
}
.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}
.quick-action-item h5 {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.25rem;
}
.quick-action-item p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

/* Performance Chart Card */
.chart-card {
    background: white;
    border-radius: 18px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

.chart-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
.chart-card h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 1.5rem;
}
.chart-placeholder {
    margin-bottom: 1.5rem;
}
.chart-bars {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    height: 150px;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}
.chart-bar {
    flex: 1;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 6px 6px 0 0;
    position: relative;
    transition: all 0.3s;
    cursor: pointer;
}
.chart-bar:hover {
    opacity: 0.8;
    transform: translateY(-4px);
}
.bar-value {
    position: absolute;
    top: -24px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.75rem;
    font-weight: 700;
    color: #374151;
    opacity: 0;
    transition: opacity 0.3s;
}
.chart-bar:hover .bar-value {
    opacity: 1;
}
.chart-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 600;
}
.chart-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}
.stat-item {
    text-align: center;
}
.stat-label {
    display: block;
    font-size: 0.8125rem;
    color: #6b7280;
    margin-bottom: 0.375rem;
}
.stat-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 800;
    color: #111827;
}
.stat-value.positive {
    color: #10b981;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}
.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}
.status-draft {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}
.status-closed {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* AI Chat Modal */
.ai-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}
.ai-modal.active {
    display: flex;
}
.ai-modal-content {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.ai-modal-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px 20px 0 0;
}
.ai-avatar-large {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.ai-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
}
.ai-modal-header p {
    margin: 0;
    font-size: 0.875rem;
    opacity: 0.9;
}
.ai-modal-close {
    margin-left: auto;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s;
}
.ai-modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
}
.ai-modal-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.ai-chat-messages {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.ai-message {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}
.ai-message-bot {
    align-self: flex-start;
}
.ai-message-user {
    align-self: flex-end;
    flex-direction: row-reverse;
}
.message-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}
.ai-message-user .message-avatar {
    background: #10b981;
}
.message-content {
    background: #f3f4f6;
    padding: 0.875rem 1.125rem;
    border-radius: 12px;
    max-width: 70%;
    font-size: 0.9375rem;
    line-height: 1.5;
}
.ai-message-user .message-content {
    background: #667eea;
    color: white;
}
.ai-chat-input-area {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 0.75rem;
}
#aiChatInput {
    flex: 1;
    padding: 0.875rem 1.125rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9375rem;
    transition: all 0.3s;
}
#aiChatInput:focus {
    outline: none;
    border-color: #667eea;
}
.btn-send-message {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1.125rem;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
}
.btn-send-message:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}
</style>

<script>
// Counter Animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        updateCounter();
    });
    
    // AI Chat Button Listeners
    const aiChatButtons = document.querySelectorAll('.btn-ai-chat, .btn-help');
    aiChatButtons.forEach(btn => {
        btn.addEventListener('click', openAIChat);
    });
});

// AI Chat Functions
function openAIChat() {
    const modal = document.getElementById('aiChatModal');
    if (modal) {
        modal.classList.add('active');
    }
}

function closeAIChat() {
    const modal = document.getElementById('aiChatModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('aiChatModal');
    if (modal && e.target === modal) {
        closeAIChat();
    }
});

function sendAIMessage() {
    const input = document.getElementById('aiChatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    const chatMessages = document.getElementById('aiChatMessages');
    
    // Add user message
    const userMessageDiv = document.createElement('div');
    userMessageDiv.className = 'ai-message ai-message-user';
    userMessageDiv.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="message-content">${escapeHtml(message)}</div>
    `;
    chatMessages.appendChild(userMessageDiv);
    
    input.value = '';
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    // Simulate AI response
    setTimeout(() => {
        fetch('<?= url("employer/ai-chat") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'ai-message ai-message-bot';
            aiMessageDiv.innerHTML = `
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">${data.response || 'Üzgünüm, şu anda size yardımcı olamıyorum.'}</div>
            `;
            chatMessages.appendChild(aiMessageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        })
        .catch(error => {
            console.error('AI Chat Error:', error);
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'ai-message ai-message-bot';
            aiMessageDiv.innerHTML = `
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">Anlıyorum. Size nasıl yardımcı olabilirim? İş ilanları, başvurular veya AI analizi hakkında sorularınızı yanıtlayabilirim.</div>
            `;
            chatMessages.appendChild(aiMessageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    }, 500);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show AI Applications Modal
function showAIApplications() {
    // Create modal if not exists
    let modal = document.getElementById('aiApplicationsModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'aiApplicationsModal';
        modal.className = 'ai-modal';
        modal.innerHTML = `
            <div class="ai-modal-content" style="max-width: 900px;">
                <div class="ai-modal-header">
                    <h3><i class="fas fa-robot"></i> AI Değerlendirilen Başvurular</h3>
                    <button class="ai-modal-close" onclick="closeAIApplicationsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="ai-modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="ai-applications-list">
                        <!-- Application 1 -->
                        <div class="ai-application-card">
                            <div class="ai-app-header">
                                <div class="ai-app-candidate">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        AY
                                    </div>
                                    <div>
                                        <h4>Ahmet Yılmaz</h4>
                                        <p>Senior PHP Developer</p>
                                    </div>
                                </div>
                                <div class="ai-score-badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                    <div class="score-number">92%</div>
                                    <div class="score-label">AI Skoru</div>
                                </div>
                            </div>
                            
                            <div class="ai-app-details">
                                <div class="detail-row">
                                    <i class="fas fa-briefcase"></i>
                                    <span><strong>Deneyim:</strong> 8 yıl PHP, Laravel, MySQL</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span><strong>Eğitim:</strong> Bilgisayar Mühendisliği - Boğaziçi Üniversitesi</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-code"></i>
                                    <span><strong>Beceriler:</strong> PHP, Laravel, Vue.js, MySQL, Redis, Docker</span>
                                </div>
                            </div>
                            
                            <div class="ai-analysis">
                                <h5><i class="fas fa-brain"></i> AI Analizi</h5>
                                <div class="analysis-tags">
                                    <span class="tag-success"><i class="fas fa-check"></i> Mükemmel teknik uyum</span>
                                    <span class="tag-success"><i class="fas fa-check"></i> İleri düzey Laravel</span>
                                    <span class="tag-success"><i class="fas fa-check"></i> Güçlü portföy</span>
                                    <span class="tag-warning"><i class="fas fa-info"></i> Uzaktan çalışma tercihi</span>
                                </div>
                                <p class="analysis-text">
                                    <strong>Öne Çıkan Özellikler:</strong> Adayın 8 yıllık deneyimi ve Laravel framework'ünde uzmanlaşması, 
                                    pozisyon gereksinimleriyle mükemmel uyum gösteriyor. GitHub profili aktif, açık kaynak katkıları var.
                                </p>
                            </div>
                            
                            <div class="ai-app-actions">
                                <button class="btn-success"><i class="fas fa-check"></i> Onayla</button>
                                <button class="btn-primary"><i class="fas fa-calendar"></i> Görüşme Ayarla</button>
                                <button class="btn-secondary"><i class="fas fa-eye"></i> Detaylı İncele</button>
                            </div>
                        </div>
                        
                        <!-- Application 2 -->
                        <div class="ai-application-card">
                            <div class="ai-app-header">
                                <div class="ai-app-candidate">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                        ZK
                                    </div>
                                    <div>
                                        <h4>Zeynep Kaya</h4>
                                        <p>PHP Full Stack Developer</p>
                                    </div>
                                </div>
                                <div class="ai-score-badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                    <div class="score-number">88%</div>
                                    <div class="score-label">AI Skoru</div>
                                </div>
                            </div>
                            
                            <div class="ai-app-details">
                                <div class="detail-row">
                                    <i class="fas fa-briefcase"></i>
                                    <span><strong>Deneyim:</strong> 6 yıl PHP, Laravel, React, PostgreSQL</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span><strong>Eğitim:</strong> Yazılım Mühendisliği - İTÜ</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-code"></i>
                                    <span><strong>Beceriler:</strong> PHP, Laravel, React, PostgreSQL, AWS, Git</span>
                                </div>
                            </div>
                            
                            <div class="ai-analysis">
                                <h5><i class="fas fa-brain"></i> AI Analizi</h5>
                                <div class="analysis-tags">
                                    <span class="tag-success"><i class="fas fa-check"></i> Güçlü full-stack deneyim</span>
                                    <span class="tag-success"><i class="fas fa-check"></i> Modern teknolojiler</span>
                                    <span class="tag-info"><i class="fas fa-star"></i> Startup tecrübesi</span>
                                    <span class="tag-success"><i class="fas fa-check"></i> Takım liderliği</span>
                                </div>
                                <p class="analysis-text">
                                    <strong>Öne Çıkan Özellikler:</strong> Full-stack yetenekleri ve startup deneyimi ile hızlı adaptasyon 
                                    gösterebilir. React bilgisi frontend entegrasyonları için artı. 2 yıl takım liderliği yapmış.
                                </p>
                            </div>
                            
                            <div class="ai-app-actions">
                                <button class="btn-success"><i class="fas fa-check"></i> Onayla</button>
                                <button class="btn-primary"><i class="fas fa-calendar"></i> Görüşme Ayarla</button>
                                <button class="btn-secondary"><i class="fas fa-eye"></i> Detaylı İncele</button>
                            </div>
                        </div>
                        
                        <!-- Application 3 -->
                        <div class="ai-application-card">
                            <div class="ai-app-header">
                                <div class="ai-app-candidate">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        MÖ
                                    </div>
                                    <div>
                                        <h4>Mehmet Özkan</h4>
                                        <p>Backend Developer</p>
                                    </div>
                                </div>
                                <div class="ai-score-badge" style="background: linear-gradient(135deg, #ffa500 0%, #ff6b6b 100%);">
                                    <div class="score-number">75%</div>
                                    <div class="score-label">AI Skoru</div>
                                </div>
                            </div>
                            
                            <div class="ai-app-details">
                                <div class="detail-row">
                                    <i class="fas fa-briefcase"></i>
                                    <span><strong>Deneyim:</strong> 4 yıl PHP, Symfony, MySQL</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span><strong>Eğitim:</strong> Bilgisayar Programcılığı - Anadolu Üniversitesi</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-code"></i>
                                    <span><strong>Beceriler:</strong> PHP, Symfony, MySQL, Docker, Linux</span>
                                </div>
                            </div>
                            
                            <div class="ai-analysis">
                                <h5><i class="fas fa-brain"></i> AI Analizi</h5>
                                <div class="analysis-tags">
                                    <span class="tag-success"><i class="fas fa-check"></i> Solid PHP bilgisi</span>
                                    <span class="tag-warning"><i class="fas fa-info"></i> Laravel deneyimi yok</span>
                                    <span class="tag-success"><i class="fas fa-check"></i> Hızlı öğrenen</span>
                                    <span class="tag-info"><i class="fas fa-graduation-cap"></i> Sertifikalar var</span>
                                </div>
                                <p class="analysis-text">
                                    <strong>Öne Çıkan Özellikler:</strong> Symfony deneyimi Laravel'e geçiş için kolaylık sağlar. 
                                    Junior pozisyonlar için uygun, mentorluk ile gelişebilir. AWS ve Docker sertifikaları var.
                                </p>
                            </div>
                            
                            <div class="ai-app-actions">
                                <button class="btn-success"><i class="fas fa-check"></i> Onayla</button>
                                <button class="btn-primary"><i class="fas fa-calendar"></i> Görüşme Ayarla</button>
                                <button class="btn-secondary"><i class="fas fa-eye"></i> Detaylı İncele</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .ai-modal {
                display: none;
                position: fixed;
                z-index: 10000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.6);
                backdrop-filter: blur(5px);
                overflow-y: auto;
            }
            .ai-modal.active {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .ai-modal-content {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 3px;
                border-radius: 20px;
                width: 100%;
                max-width: 950px;
                max-height: 90vh;
                animation: slideDown 0.3s ease;
                display: flex;
                flex-direction: column;
                margin: auto;
            }
            .ai-modal-header {
                background: white;
                padding: 20px 25px;
                border-radius: 17px 17px 0 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #f0f0f0;
                flex-shrink: 0;
            }
            .ai-modal-header h3 {
                margin: 0;
                font-size: 22px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .ai-modal-close {
                background: #f5f5f5;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s;
                flex-shrink: 0;
                font-size: 18px;
            }
            .ai-modal-close:hover {
                background: #ff6b6b;
                color: white;
                transform: rotate(90deg);
            }
            .ai-modal-body {
                background: white;
                padding: 20px;
                border-radius: 0 0 17px 17px;
                overflow-y: auto;
                max-height: calc(90vh - 90px);
            }
            .ai-applications-list {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .ai-application-card {
                background: white;
                border: 2px solid #e8e8e8;
                border-radius: 15px;
                padding: 20px;
                transition: all 0.3s;
            }
            .ai-application-card:hover {
                box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                transform: translateY(-2px);
                border-color: #667eea;
            }
            .ai-application-card:last-child {
                margin-bottom: 0;
            }
            .ai-app-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 2px solid #f5f5f5;
                flex-wrap: wrap;
                gap: 15px;
            }
            .ai-app-candidate {
                display: flex;
                align-items: center;
                gap: 15px;
                flex: 1;
                min-width: 250px;
            }
            .candidate-avatar {
                width: 55px;
                height: 55px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 18px;
                font-weight: bold;
                flex-shrink: 0;
            }
            .ai-app-candidate h4 {
                margin: 0 0 5px 0;
                font-size: 18px;
                font-weight: 600;
                color: #333;
            }
            .ai-app-candidate p {
                margin: 0;
                color: #777;
                font-size: 13px;
            }
            .ai-score-badge {
                padding: 12px 20px;
                border-radius: 10px;
                text-align: center;
                color: white;
                flex-shrink: 0;
            }
            .score-number {
                font-size: 28px;
                font-weight: bold;
                line-height: 1;
            }
            .score-label {
                font-size: 11px;
                margin-top: 4px;
                opacity: 0.95;
            }
            .ai-app-details {
                margin-bottom: 18px;
            }
            .detail-row {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 10px;
                padding: 10px 12px;
                background: #f8f9fa;
                border-radius: 8px;
            }
            .detail-row:last-child {
                margin-bottom: 0;
            }
            .detail-row i {
                color: #667eea;
                font-size: 15px;
                width: 18px;
                flex-shrink: 0;
                margin-top: 2px;
            }
            .detail-row span {
                font-size: 13px;
                color: #444;
                line-height: 1.5;
            }
            .ai-analysis {
                background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%);
                padding: 18px;
                border-radius: 12px;
                margin-bottom: 18px;
            }
            .ai-analysis h5 {
                margin: 0 0 12px 0;
                color: #667eea;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 15px;
                font-weight: 600;
            }
            .analysis-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 12px;
            }
            .analysis-tags span {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 5px;
                white-space: nowrap;
            }
            .tag-success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .tag-warning {
                background: #fff3cd;
                color: #856404;
                border: 1px solid #ffeaa7;
            }
            .tag-info {
                background: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
            }
            .analysis-text {
                margin: 0;
                font-size: 13px;
                line-height: 1.6;
                color: #555;
            }
            .ai-app-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            .ai-app-actions button {
                padding: 10px 18px;
                border: none;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                gap: 6px;
                white-space: nowrap;
            }
            .ai-app-actions .btn-success {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                color: white;
            }
            .ai-app-actions .btn-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
            }
            .ai-app-actions .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            .ai-app-actions .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }
            .ai-app-actions .btn-secondary {
                background: #f0f0f0;
                color: #555;
                border: 1px solid #ddd;
            }
            .ai-app-actions .btn-secondary:hover {
                background: #e0e0e0;
                transform: translateY(-2px);
            }
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @media (max-width: 768px) {
                .ai-modal.active {
                    padding: 10px;
                }
                .ai-modal-content {
                    max-height: 95vh;
                }
                .ai-modal-header h3 {
                    font-size: 18px;
                }
                .ai-app-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .ai-score-badge {
                    align-self: flex-start;
                }
                .ai-app-actions {
                    flex-direction: column;
                }
                .ai-app-actions button {
                    width: 100%;
                    justify-content: center;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    modal.classList.add('active');
}

function closeAIApplicationsModal() {
    const modal = document.getElementById('aiApplicationsModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Close on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('aiApplicationsModal');
    if (modal && e.target === modal) {
        closeAIApplicationsModal();
    }
    
    const cvModal = document.getElementById('parsedCVsModal');
    if (cvModal && e.target === cvModal) {
        closeParsedCVsModal();
    }
});

// Show Parsed CVs Modal
function showParsedCVs() {
    let modal = document.getElementById('parsedCVsModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'parsedCVsModal';
        modal.className = 'ai-modal';
        modal.innerHTML = `
            <div class="ai-modal-content" style="max-width: 1000px;">
                <div class="ai-modal-header">
                    <h3><i class="fas fa-file-alt"></i> AI Parse Edilmiş CV'ler</h3>
                    <button class="ai-modal-close" onclick="closeParsedCVsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="ai-modal-body">
                    <div class="parsed-cvs-list">
                        <!-- CV 1 -->
                        <div class="parsed-cv-card">
                            <div class="cv-card-header">
                                <div class="cv-candidate-info">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        SK
                                    </div>
                                    <div>
                                        <h4>Selin Korkmaz</h4>
                                        <p class="cv-parse-time"><i class="fas fa-clock"></i> 45 dakika önce parse edildi</p>
                                    </div>
                                </div>
                                <div class="parse-status success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Başarılı</span>
                                </div>
                            </div>
                            
                            <div class="cv-parsed-data">
                                <div class="data-section">
                                    <h5><i class="fas fa-user"></i> Kişisel Bilgiler</h5>
                                    <div class="data-grid">
                                        <div class="data-item">
                                            <span class="label">Ad Soyad:</span>
                                            <span class="value">Selin Korkmaz</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">E-posta:</span>
                                            <span class="value">selin.korkmaz@email.com</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Telefon:</span>
                                            <span class="value">+90 532 123 4567</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Lokasyon:</span>
                                            <span class="value">İstanbul, Türkiye</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-code"></i> Beceriler</h5>
                                    <div class="skills-tags">
                                        <span class="skill-tag">JavaScript</span>
                                        <span class="skill-tag">React</span>
                                        <span class="skill-tag">Node.js</span>
                                        <span class="skill-tag">TypeScript</span>
                                        <span class="skill-tag">MongoDB</span>
                                        <span class="skill-tag">Docker</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-briefcase"></i> İş Deneyimi</h5>
                                    <div class="experience-item">
                                        <strong>Senior Frontend Developer</strong> - TechCorp A.Ş.
                                        <span class="date">2021 - Günümüz (3 yıl)</span>
                                    </div>
                                    <div class="experience-item">
                                        <strong>Frontend Developer</strong> - StartupXYZ
                                        <span class="date">2018 - 2021 (3 yıl)</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-graduation-cap"></i> Eğitim</h5>
                                    <div class="education-item">
                                        <strong>Bilgisayar Mühendisliği</strong> - İstanbul Teknik Üniversitesi
                                        <span class="date">2014 - 2018</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cv-card-actions">
                                <button class="btn-view-profile"><i class="fas fa-user"></i> Profil Görüntüle</button>
                                <button class="btn-download-cv"><i class="fas fa-download"></i> CV İndir</button>
                            </div>
                        </div>
                        
                        <!-- CV 2 -->
                        <div class="parsed-cv-card">
                            <div class="cv-card-header">
                                <div class="cv-candidate-info">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                        BT
                                    </div>
                                    <div>
                                        <h4>Burak Tekin</h4>
                                        <p class="cv-parse-time"><i class="fas fa-clock"></i> 52 dakika önce parse edildi</p>
                                    </div>
                                </div>
                                <div class="parse-status success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Başarılı</span>
                                </div>
                            </div>
                            
                            <div class="cv-parsed-data">
                                <div class="data-section">
                                    <h5><i class="fas fa-user"></i> Kişisel Bilgiler</h5>
                                    <div class="data-grid">
                                        <div class="data-item">
                                            <span class="label">Ad Soyad:</span>
                                            <span class="value">Burak Tekin</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">E-posta:</span>
                                            <span class="value">burak.tekin@email.com</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Telefon:</span>
                                            <span class="value">+90 533 987 6543</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Lokasyon:</span>
                                            <span class="value">Ankara, Türkiye</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-code"></i> Beceriler</h5>
                                    <div class="skills-tags">
                                        <span class="skill-tag">Python</span>
                                        <span class="skill-tag">Django</span>
                                        <span class="skill-tag">PostgreSQL</span>
                                        <span class="skill-tag">Redis</span>
                                        <span class="skill-tag">AWS</span>
                                        <span class="skill-tag">Kubernetes</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-briefcase"></i> İş Deneyimi</h5>
                                    <div class="experience-item">
                                        <strong>Backend Developer</strong> - CloudTech Ltd.
                                        <span class="date">2020 - Günümüz (4 yıl)</span>
                                    </div>
                                    <div class="experience-item">
                                        <strong>Junior Developer</strong> - WebSolutions
                                        <span class="date">2018 - 2020 (2 yıl)</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-graduation-cap"></i> Eğitim</h5>
                                    <div class="education-item">
                                        <strong>Yazılım Mühendisliği</strong> - Orta Doğu Teknik Üniversitesi
                                        <span class="date">2014 - 2018</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cv-card-actions">
                                <button class="btn-view-profile"><i class="fas fa-user"></i> Profil Görüntüle</button>
                                <button class="btn-download-cv"><i class="fas fa-download"></i> CV İndir</button>
                            </div>
                        </div>
                        
                        <!-- CV 3 -->
                        <div class="parsed-cv-card">
                            <div class="cv-card-header">
                                <div class="cv-candidate-info">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        EA
                                    </div>
                                    <div>
                                        <h4>Elif Arslan</h4>
                                        <p class="cv-parse-time"><i class="fas fa-clock"></i> 1 saat önce parse edildi</p>
                                    </div>
                                </div>
                                <div class="parse-status success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Başarılı</span>
                                </div>
                            </div>
                            
                            <div class="cv-parsed-data">
                                <div class="data-section">
                                    <h5><i class="fas fa-user"></i> Kişisel Bilgiler</h5>
                                    <div class="data-grid">
                                        <div class="data-item">
                                            <span class="label">Ad Soyad:</span>
                                            <span class="value">Elif Arslan</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">E-posta:</span>
                                            <span class="value">elif.arslan@email.com</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Telefon:</span>
                                            <span class="value">+90 534 456 7890</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Lokasyon:</span>
                                            <span class="value">İzmir, Türkiye</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-code"></i> Beceriler</h5>
                                    <div class="skills-tags">
                                        <span class="skill-tag">Java</span>
                                        <span class="skill-tag">Spring Boot</span>
                                        <span class="skill-tag">MySQL</span>
                                        <span class="skill-tag">Microservices</span>
                                        <span class="skill-tag">Jenkins</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-briefcase"></i> İş Deneyimi</h5>
                                    <div class="experience-item">
                                        <strong>Java Developer</strong> - EnterpriseSoft
                                        <span class="date">2019 - Günümüz (5 yıl)</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-graduation-cap"></i> Eğitim</h5>
                                    <div class="education-item">
                                        <strong>Bilgisayar Mühendisliği</strong> - Ege Üniversitesi
                                        <span class="date">2015 - 2019</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cv-card-actions">
                                <button class="btn-view-profile"><i class="fas fa-user"></i> Profil Görüntüle</button>
                                <button class="btn-download-cv"><i class="fas fa-download"></i> CV İndir</button>
                            </div>
                        </div>
                        
                        <!-- CV 4 -->
                        <div class="parsed-cv-card">
                            <div class="cv-card-header">
                                <div class="cv-candidate-info">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                        CD
                                    </div>
                                    <div>
                                        <h4>Can Demir</h4>
                                        <p class="cv-parse-time"><i class="fas fa-clock"></i> 1 saat önce parse edildi</p>
                                    </div>
                                </div>
                                <div class="parse-status partial">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Kısmi</span>
                                </div>
                            </div>
                            
                            <div class="cv-parsed-data">
                                <div class="data-section">
                                    <h5><i class="fas fa-user"></i> Kişisel Bilgiler</h5>
                                    <div class="data-grid">
                                        <div class="data-item">
                                            <span class="label">Ad Soyad:</span>
                                            <span class="value">Can Demir</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">E-posta:</span>
                                            <span class="value">can.demir@email.com</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Telefon:</span>
                                            <span class="value text-muted">Parse edilemedi</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Lokasyon:</span>
                                            <span class="value">Bursa, Türkiye</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-code"></i> Beceriler</h5>
                                    <div class="skills-tags">
                                        <span class="skill-tag">C#</span>
                                        <span class="skill-tag">.NET Core</span>
                                        <span class="skill-tag">SQL Server</span>
                                        <span class="skill-tag">Azure</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-briefcase"></i> İş Deneyimi</h5>
                                    <div class="experience-item">
                                        <strong>.NET Developer</strong> - SoftwareHouse
                                        <span class="date">2020 - Günümüz (4 yıl)</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-graduation-cap"></i> Eğitim</h5>
                                    <div class="education-item">
                                        <strong>Yazılım Mühendisliği</strong> - Uludağ Üniversitesi
                                        <span class="date">2016 - 2020</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cv-card-actions">
                                <button class="btn-view-profile"><i class="fas fa-user"></i> Profil Görüntüle</button>
                                <button class="btn-download-cv"><i class="fas fa-download"></i> CV İndir</button>
                            </div>
                        </div>
                        
                        <!-- CV 5 -->
                        <div class="parsed-cv-card">
                            <div class="cv-card-header">
                                <div class="cv-candidate-info">
                                    <div class="candidate-avatar" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                                        DO
                                    </div>
                                    <div>
                                        <h4>Deniz Özkan</h4>
                                        <p class="cv-parse-time"><i class="fas fa-clock"></i> 1 saat önce parse edildi</p>
                                    </div>
                                </div>
                                <div class="parse-status success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Başarılı</span>
                                </div>
                            </div>
                            
                            <div class="cv-parsed-data">
                                <div class="data-section">
                                    <h5><i class="fas fa-user"></i> Kişisel Bilgiler</h5>
                                    <div class="data-grid">
                                        <div class="data-item">
                                            <span class="label">Ad Soyad:</span>
                                            <span class="value">Deniz Özkan</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">E-posta:</span>
                                            <span class="value">deniz.ozkan@email.com</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Telefon:</span>
                                            <span class="value">+90 535 111 2233</span>
                                        </div>
                                        <div class="data-item">
                                            <span class="label">Lokasyon:</span>
                                            <span class="value">İstanbul, Türkiye</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-code"></i> Beceriler</h5>
                                    <div class="skills-tags">
                                        <span class="skill-tag">PHP</span>
                                        <span class="skill-tag">Laravel</span>
                                        <span class="skill-tag">Vue.js</span>
                                        <span class="skill-tag">MySQL</span>
                                        <span class="skill-tag">Redis</span>
                                        <span class="skill-tag">Git</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-briefcase"></i> İş Deneyimi</h5>
                                    <div class="experience-item">
                                        <strong>Full Stack Developer</strong> - DigitalAgency
                                        <span class="date">2019 - Günümüz (5 yıl)</span>
                                    </div>
                                    <div class="experience-item">
                                        <strong>PHP Developer</strong> - WebStudio
                                        <span class="date">2017 - 2019 (2 yıl)</span>
                                    </div>
                                </div>
                                
                                <div class="data-section">
                                    <h5><i class="fas fa-graduation-cap"></i> Eğitim</h5>
                                    <div class="education-item">
                                        <strong>Bilgisayar Mühendisliği</strong> - Yıldız Teknik Üniversitesi
                                        <span class="date">2013 - 2017</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cv-card-actions">
                                <button class="btn-view-profile"><i class="fas fa-user"></i> Profil Görüntüle</button>
                                <button class="btn-download-cv"><i class="fas fa-download"></i> CV İndir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add styles for parsed CVs modal
        const style = document.createElement('style');
        style.textContent = `
            #parsedCVsModal .ai-modal-content {
                display: flex;
                flex-direction: column;
                max-height: 90vh;
            }
            #parsedCVsModal .ai-modal-header {
                flex-shrink: 0;
            }
            #parsedCVsModal .ai-modal-body {
                overflow-y: auto;
                max-height: calc(90vh - 90px);
                flex: 1;
            }
            .parsed-cvs-list {
                display: flex;
                flex-direction: column;
                gap: 18px;
                padding-bottom: 10px;
            }
            .parsed-cv-card {
                background: white;
                border: 2px solid #e8e8e8;
                border-radius: 12px;
                padding: 0;
                overflow: hidden;
                transition: all 0.3s;
            }
            .parsed-cv-card:hover {
                box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                transform: translateY(-2px);
                border-color: #667eea;
            }
            .cv-card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 18px;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-bottom: 2px solid #e8e8e8;
                flex-wrap: wrap;
                gap: 12px;
            }
            .cv-candidate-info {
                display: flex;
                align-items: center;
                gap: 12px;
                flex: 1;
                min-width: 200px;
            }
            .cv-candidate-info .candidate-avatar {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                flex-shrink: 0;
            }
            .cv-candidate-info h4 {
                margin: 0 0 4px 0;
                font-size: 16px;
                font-weight: 600;
                color: #333;
            }
            .cv-parse-time {
                margin: 0;
                font-size: 11px;
                color: #777;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .parse-status {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
                flex-shrink: 0;
            }
            .parse-status.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .parse-status.partial {
                background: #fff3cd;
                color: #856404;
                border: 1px solid #ffeaa7;
            }
            .cv-parsed-data {
                padding: 18px;
            }
            .data-section {
                margin-bottom: 16px;
            }
            .data-section:last-child {
                margin-bottom: 0;
            }
            .data-section h5 {
                margin: 0 0 10px 0;
                font-size: 14px;
                font-weight: 600;
                color: #667eea;
                display: flex;
                align-items: center;
                gap: 7px;
            }
            .data-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
                gap: 10px;
            }
            .data-item {
                display: flex;
                flex-direction: column;
                gap: 3px;
                padding: 10px 12px;
                background: #f8f9fa;
                border-radius: 8px;
            }
            .data-item .label {
                font-size: 10px;
                color: #888;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .data-item .value {
                font-size: 13px;
                color: #333;
                font-weight: 500;
                line-height: 1.4;
            }
            .data-item .value.text-muted {
                color: #999;
                font-style: italic;
            }
            .skills-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 7px;
            }
            .skill-tag {
                padding: 7px 14px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);http://localhost/isealim/employer/dashboard
                color: white;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 500;
            }
            .experience-item, .education-item {
                padding: 10px 12px;
                background: #f8f9fa;
                border-radius: 8px;
                margin-bottom: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
            }
            .experience-item:last-child, .education-item:last-child {
                margin-bottom: 0;
            }
            .experience-item strong, .education-item strong {
                color: #333;
                font-size: 13px;
            }
            .experience-item .date, .education-item .date {
                color: #777;
                font-size: 11px;
            }
            .cv-card-actions {
                display: flex;
                gap: 10px;
                padding: 15px 18px;
                background: #f8f9fa;
                border-top: 2px solid #e8e8e8;
            }
            .cv-card-actions button {
                flex: 1;
                padding: 10px 16px;
                border: none;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }
            .cv-card-actions .btn-view-profile {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            .cv-card-actions .btn-view-profile:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }
            .cv-card-actions .btn-download-cv {
                background: #f0f0f0;
                color: #555;
                border: 1px solid #ddd;
            }
            .cv-card-actions .btn-download-cv:hover {
                background: #e0e0e0;
                transform: translateY(-2px);
            }
            @media (max-width: 768px) {
                .cv-card-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .parse-status {
                    align-self: flex-start;
                }
                .data-grid {
                    grid-template-columns: 1fr;
                }
                .cv-card-actions {
                    flex-direction: column;
                }
                .cv-card-actions button {
                    width: 100%;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    modal.classList.add('active');
}

function closeParsedCVsModal() {
    const modal = document.getElementById('parsedCVsModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Show Generated Form Modal
function showGeneratedForm() {
    let modal = document.getElementById('generatedFormModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'generatedFormModal';
        modal.className = 'ai-modal';
        modal.innerHTML = `
            <div class="ai-modal-content" style="max-width: 850px;">
                <div class="ai-modal-header">
                    <h3><i class="fas fa-magic"></i> AI Oluşturulmuş Başvuru Formu</h3>
                    <button class="ai-modal-close" onclick="closeGeneratedFormModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="ai-modal-body" style="overflow-y: auto; max-height: calc(90vh - 90px);">
                    <div class="form-preview-container">
                        <!-- Form Header -->
                        <div class="form-preview-header">
                            <div class="job-badge">
                                <i class="fas fa-code"></i>
                                <span>Frontend Developer</span>
                            </div>
                            <div class="ai-generated-badge">
                                <i class="fas fa-robot"></i>
                                AI Tarafından Oluşturuldu
                            </div>
                        </div>
                        
                        <!-- Form Description -->
                        <div class="form-description">
                            <h4>Form Açıklaması</h4>
                            <p>Bu başvuru formu, Frontend Developer pozisyonuna özgü olarak AI tarafından otomatik oluşturulmuştur. 
                            Form, pozisyon gereksinimlerine göre en uygun soruları içerecek şekilde optimize edilmiştir.</p>
                        </div>
                        
                        <!-- Form Preview -->
                        <div class="form-preview">
                            <h4><i class="fas fa-clipboard-list"></i> Form Soruları Önizlemesi</h4>
                            
                            <!-- Section 1: Personal Info -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-user"></i>
                                    <span>Kişisel Bilgiler</span>
                                    <span class="section-badge">Zorunlu</span>
                                </div>
                                <div class="form-fields">
                                    <div class="field-item">
                                        <label>Ad Soyad</label>
                                        <div class="field-type">Metin girişi</div>
                                    </div>
                                    <div class="field-item">
                                        <label>E-posta Adresi</label>
                                        <div class="field-type">E-posta girişi</div>
                                    </div>
                                    <div class="field-item">
                                        <label>Telefon Numarası</label>
                                        <div class="field-type">Telefon girişi</div>
                                    </div>
                                    <div class="field-item">
                                        <label>LinkedIn Profil URL</label>
                                        <div class="field-type">URL girişi</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 2: Technical Skills -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-code"></i>
                                    <span>Teknik Beceriler</span>
                                    <span class="section-badge">Zorunlu</span>
                                </div>
                                <div class="form-fields">
                                    <div class="field-item">
                                        <label>JavaScript/TypeScript deneyim seviyeniz nedir?</label>
                                        <div class="field-type">
                                            <span class="field-icon">🔘</span>
                                            Çoktan seçmeli: Başlangıç / Orta / İleri / Uzman
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <label>Hangi modern JavaScript framework'lerinde deneyiminiz var?</label>
                                        <div class="field-type">
                                            <span class="field-icon">☑️</span>
                                            Çoklu seçim: React, Vue.js, Angular, Svelte, Next.js
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <label>CSS framework ve preprocessor deneyiminiz</label>
                                        <div class="field-type">
                                            <span class="field-icon">☑️</span>
                                            Çoklu seçim: Tailwind CSS, Bootstrap, SASS, LESS, Styled Components
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <label>State management konusunda hangi araçları kullandınız?</label>
                                        <div class="field-type">Metin alanı (çok satırlı)</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 3: Experience -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-briefcase"></i>
                                    <span>İş Deneyimi</span>
                                    <span class="section-badge">Zorunlu</span>
                                </div>
                                <div class="form-fields">
                                    <div class="field-item">
                                        <label>Frontend geliştirme alanında toplam kaç yıl deneyiminiz var?</label>
                                        <div class="field-type">
                                            <span class="field-icon">🔽</span>
                                            Açılır liste: 0-1 yıl / 1-3 yıl / 3-5 yıl / 5-8 yıl / 8+ yıl
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <label>En son üzerinde çalıştığınız proje hakkında bilgi verin</label>
                                        <div class="field-type">Metin alanı (çok satırlı)</div>
                                    </div>
                                    <div class="field-item">
                                        <label>GitHub veya portfolyo linkiniz</label>
                                        <div class="field-type">URL girişi (opsiyonel)</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 4: Additional Questions -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-question-circle"></i>
                                    <span>Ek Sorular</span>
                                    <span class="section-badge optional">Opsiyonel</span>
                                </div>
                                <div class="form-fields">
                                    <div class="field-item">
                                        <label>Responsive design ve mobile-first yaklaşımı konusundaki yaklaşımınızı açıklayın</label>
                                        <div class="field-type">Metin alanı (çok satırlı)</div>
                                    </div>
                                    <div class="field-item">
                                        <label>Web performans optimizasyonu için hangi teknikleri uygularsınız?</label>
                                        <div class="field-type">Metin alanı (çok satırlı)</div>
                                    </div>
                                    <div class="field-item">
                                        <label>Uzaktan çalışma tercihiniz</label>
                                        <div class="field-type">
                                            <span class="field-icon">🔘</span>
                                            Çoktan seçmeli: Ofiste / Hibrit / Tam uzaktan
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <label>Ne zaman işe başlayabilirsiniz?</label>
                                        <div class="field-type">Tarih seçici</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 5: File Uploads -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-file-upload"></i>
                                    <span>Dosya Yüklemeleri</span>
                                    <span class="section-badge">Zorunlu</span>
                                </div>
                                <div class="form-fields">
                                    <div class="field-item">
                                        <label>CV / Özgeçmiş</label>
                                        <div class="field-type">
                                            <span class="field-icon">📄</span>
                                            Dosya yükleme (PDF, DOC, DOCX - Max 5MB)
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <label>Portfolyo veya proje örnekleri (opsiyonel)</label>
                                        <div class="field-type">
                                            <span class="field-icon">📎</span>
                                            Çoklu dosya yükleme
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- AI Insights -->
                        <div class="ai-insights">
                            <h4><i class="fas fa-lightbulb"></i> AI Önerileri</h4>
                            <div class="insight-list">
                                <div class="insight-item success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Form, pozisyon gereksinimleriyle %95 uyumlu</span>
                                </div>
                                <div class="insight-item success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Tüm kritik teknik beceriler sorgulanıyor</span>
                                </div>
                                <div class="insight-item info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Ortalama tamamlanma süresi: 12-15 dakika</span>
                                </div>
                                <div class="insight-item warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Tavsiye: Portföy sorusunu zorunlu yapabilirsiniz</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Statistics -->
                        <div class="form-stats">
                            <div class="stat-box">
                                <div class="stat-number">24</div>
                                <div class="stat-label">Toplam Soru</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">18</div>
                                <div class="stat-label">Zorunlu Alan</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">5</div>
                                <div class="stat-label">Bölüm</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">~14dk</div>
                                <div class="stat-label">Tahmini Süre</div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="form-actions">
                            <button class="btn-action-primary">
                                <i class="fas fa-check"></i>
                                Formu Onayla ve Yayınla
                            </button>
                            <button class="btn-action-secondary">
                                <i class="fas fa-edit"></i>
                                Düzenle
                            </button>
                            <button class="btn-action-outline">
                                <i class="fas fa-eye"></i>
                                Canlı Önizleme
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add styles for generated form modal
        const style = document.createElement('style');
        style.textContent = `
            #generatedFormModal .ai-modal-content {
                display: flex;
                flex-direction: column;
                max-height: 90vh;
            }
            #generatedFormModal .ai-modal-header {
                flex-shrink: 0;
            }
            .form-preview-container {
                padding: 5px;
            }
            .form-preview-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 12px;
            }
            .job-badge {
                display: flex;
                align-items: center;
                gap: 10px;
                background: rgba(255,255,255,0.2);
                padding: 10px 18px;
                border-radius: 25px;
                color: white;
                font-size: 16px;
                font-weight: 600;
            }
            .ai-generated-badge {
                display: flex;
                align-items: center;
                gap: 8px;
                background: rgba(255,255,255,0.95);
                padding: 8px 16px;
                border-radius: 20px;
                color: #667eea;
                font-size: 13px;
                font-weight: 500;
            }
            .form-description {
                background: #f8f9fa;
                padding: 18px;
                border-radius: 10px;
                margin-bottom: 20px;
                border-left: 4px solid #667eea;
            }
            .form-description h4 {
                margin: 0 0 10px 0;
                color: #333;
                font-size: 16px;
            }
            .form-description p {
                margin: 0;
                color: #666;
                font-size: 14px;
                line-height: 1.6;
            }
            .form-preview {
                margin-bottom: 20px;
            }
            .form-preview > h4 {
                margin: 0 0 20px 0;
                color: #333;
                font-size: 18px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .form-section {
                background: white;
                border: 2px solid #e8e8e8;
                border-radius: 12px;
                margin-bottom: 16px;
                overflow: hidden;
                transition: all 0.3s;
            }
            .form-section:hover {
                border-color: #667eea;
                box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            }
            .section-header {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 14px 18px;
                display: flex;
                align-items: center;
                gap: 10px;
                border-bottom: 2px solid #e8e8e8;
                font-weight: 600;
                color: #333;
                font-size: 15px;
            }
            .section-badge {
                margin-left: auto;
                padding: 4px 12px;
                background: #d4edda;
                color: #155724;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
            }
            .section-badge.optional {
                background: #d1ecf1;
                color: #0c5460;
            }
            .form-fields {
                padding: 18px;
            }
            .field-item {
                padding: 12px;
                background: #f8f9fa;
                border-radius: 8px;
                margin-bottom: 12px;
            }
            .field-item:last-child {
                margin-bottom: 0;
            }
            .field-item label {
                display: block;
                margin-bottom: 6px;
                color: #333;
                font-weight: 500;
                font-size: 13px;
            }
            .field-type {
                color: #666;
                font-size: 12px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .field-icon {
                font-size: 14px;
            }
            .ai-insights {
                background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%);
                padding: 18px;
                border-radius: 12px;
                margin-bottom: 20px;
            }
            .ai-insights h4 {
                margin: 0 0 15px 0;
                color: #667eea;
                font-size: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .insight-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .insight-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                background: white;
                border-radius: 8px;
                font-size: 13px;
            }
            .insight-item.success {
                border-left: 3px solid #28a745;
            }
            .insight-item.info {
                border-left: 3px solid #17a2b8;
            }
            .insight-item.warning {
                border-left: 3px solid #ffc107;
            }
            .insight-item i {
                font-size: 16px;
            }
            .insight-item.success i {
                color: #28a745;
            }
            .insight-item.info i {
                color: #17a2b8;
            }
            .insight-item.warning i {
                color: #ffc107;
            }
            .form-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }
            .stat-box {
                background: white;
                border: 2px solid #e8e8e8;
                border-radius: 12px;
                padding: 18px;
                text-align: center;
                transition: all 0.3s;
            }
            .stat-box:hover {
                border-color: #667eea;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            }
            .stat-number {
                font-size: 32px;
                font-weight: bold;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 8px;
            }
            .stat-label {
                color: #666;
                font-size: 12px;
                font-weight: 500;
            }
            .form-actions {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
            }
            .form-actions button {
                flex: 1;
                min-width: 180px;
                padding: 14px 20px;
                border: none;
                border-radius: 10px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            .btn-action-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            .btn-action-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            }
            .btn-action-secondary {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                color: white;
            }
            .btn-action-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
            }
            .btn-action-outline {
                background: white;
                color: #667eea;
                border: 2px solid #667eea;
            }
            .btn-action-outline:hover {
                background: #667eea;
                color: white;
                transform: translateY(-2px);
            }
            @media (max-width: 768px) {
                .form-preview-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .form-stats {
                    grid-template-columns: repeat(2, 1fr);
                }
                .form-actions {
                    flex-direction: column;
                }
                .form-actions button {
                    width: 100%;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    modal.classList.add('active');
}

function closeGeneratedFormModal() {
    const modal = document.getElementById('generatedFormModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Close on outside click
document.addEventListener('click', function(e) {
    const formModal = document.getElementById('generatedFormModal');
    if (formModal && e.target === formModal) {
        closeGeneratedFormModal();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
