<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Başvurular' ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title h1 {
            font-size: 28px;
            color: #2d3748;
        }

        .page-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .stat-card {
            background: #f7fafc;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
        }

        .icon-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .icon-orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .icon-purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .stat-content h3 {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .stat-content p {
            font-size: 13px;
            color: #718096;
        }

        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            border-radius: 8px;
            background: #f7fafc;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-tab:hover {
            background: #e2e8f0;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .filter-controls {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            display: block;
            font-size: 12px;
            color: #718096;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .filter-select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #2d3748;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .applications-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .application-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .application-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .applicant-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .application-content {
            flex: 1;
        }

        .applicant-name {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .job-title {
            font-size: 14px;
            color: #667eea;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .application-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #718096;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .application-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 15px;
        }

        .score-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .score-excellent {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .score-good {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .score-medium {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-reviewed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-accepted {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .application-actions {
            display: flex;
            gap: 8px;
        }

        .btn-mini {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view {
            background: #667eea;
            color: white;
        }

        .btn-view:hover {
            background: #5568d3;
        }

        .btn-accept {
            background: #38a169;
            color: white;
        }

        .btn-accept:hover {
            background: #2f855a;
        }

        .btn-reject {
            background: #e53e3e;
            color: white;
        }

        .btn-reject:hover {
            background: #c53030;
        }

        .empty-state {
            background: white;
            padding: 60px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .empty-icon {
            font-size: 80px;
            color: #cbd5e0;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #718096;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .filter-controls {
                flex-direction: column;
            }

            .application-card {
                flex-direction: column;
                text-align: center;
            }

            .application-right {
                align-items: center;
                width: 100%;
            }

            .application-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn-mini {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-top">
                <div class="page-title">
                    <div class="page-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h1>Başvurular</h1>
                        <p style="color: #718096; font-size: 14px; margin-top: 5px;">
                            Tüm iş başvurularınızı yönetin ve değerlendirin
                        </p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="<?= url('employer/dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <button class="btn btn-primary" onclick="exportApplications()">
                        <i class="fas fa-download"></i> Excel İndir
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <?php if (isset($stats)): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= number_format($stats['total'] ?? 0) ?></h3>
                        <p>Toplam Başvuru</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= number_format($stats['pending'] ?? 0) ?></h3>
                        <p>Bekleyen</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= number_format($stats['reviewed'] ?? 0) ?></h3>
                        <p>İncelenen</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= number_format($stats['accepted'] ?? 0) ?></h3>
                        <p>Kabul Edilen</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filter-tabs">
                <a href="<?= url('employer/applications?filter=all') ?>" 
                   class="filter-tab <?= ($current_filter ?? 'all') === 'all' ? 'active' : '' ?>">
                    <i class="fas fa-list"></i> Tümü
                </a>
                <a href="<?= url('employer/applications?filter=top') ?>" 
                   class="filter-tab <?= ($current_filter ?? '') === 'top' ? 'active' : '' ?>">
                    <i class="fas fa-star"></i> En İyi Eşleşmeler
                </a>
                <a href="<?= url('employer/applications?filter=new') ?>" 
                   class="filter-tab <?= ($current_filter ?? '') === 'new' ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i> Yeni Başvurular
                </a>
                <a href="<?= url('employer/applications?filter=pending') ?>" 
                   class="filter-tab <?= ($current_filter ?? '') === 'pending' ? 'active' : '' ?>">
                    <i class="fas fa-hourglass-half"></i> Bekleyenler
                </a>
                <a href="<?= url('employer/applications?filter=reviewed') ?>" 
                   class="filter-tab <?= ($current_filter ?? '') === 'reviewed' ? 'active' : '' ?>">
                    <i class="fas fa-check"></i> İncelenenler
                </a>
            </div>

            <div class="filter-controls">
                <div class="filter-group">
                    <label class="filter-label">İş İlanı</label>
                    <select class="filter-select" onchange="filterByJob(this.value)">
                        <option value="">Tüm İlanlar</option>
                        <?php if (isset($jobs) && !empty($jobs)): ?>
                            <?php foreach ($jobs as $job): ?>
                                <option value="<?= $job['id'] ?>" <?= (isset($filters['job_id']) && $filters['job_id'] == $job['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($job['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Durum</label>
                    <select class="filter-select" onchange="filterByStatus(this.value)">
                        <option value="">Tüm Durumlar</option>
                        <option value="pending" <?= (isset($filters['status']) && $filters['status'] === 'pending') ? 'selected' : '' ?>>Bekliyor</option>
                        <option value="reviewed" <?= (isset($filters['status']) && $filters['status'] === 'reviewed') ? 'selected' : '' ?>>İncelendi</option>
                        <option value="accepted" <?= (isset($filters['status']) && $filters['status'] === 'accepted') ? 'selected' : '' ?>>Kabul Edildi</option>
                        <option value="rejected" <?= (isset($filters['status']) && $filters['status'] === 'rejected') ? 'selected' : '' ?>>Reddedildi</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Minimum AI Skoru</label>
                    <select class="filter-select" onchange="filterByScore(this.value)">
                        <option value="">Tüm Skorlar</option>
                        <option value="80" <?= (isset($filters['min_score']) && $filters['min_score'] == 80) ? 'selected' : '' ?>>80+ Mükemmel</option>
                        <option value="70" <?= (isset($filters['min_score']) && $filters['min_score'] == 70) ? 'selected' : '' ?>>70+ İyi</option>
                        <option value="60" <?= (isset($filters['min_score']) && $filters['min_score'] == 60) ? 'selected' : '' ?>>60+ Orta</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Applications List -->
        <?php if (isset($applications) && !empty($applications)): ?>
            <div class="applications-list">
                <?php foreach ($applications as $app): ?>
                    <div class="application-card">
                        <div class="applicant-avatar">
                            <?= strtoupper(substr($app['applicant_name'] ?? 'A', 0, 1)) ?>
                        </div>

                        <div class="application-content">
                            <div class="applicant-name"><?= htmlspecialchars($app['applicant_name'] ?? 'İsimsiz Başvuran') ?></div>
                            <div class="job-title">
                                <i class="fas fa-briefcase"></i>
                                <?= htmlspecialchars($app['job_title'] ?? 'İlan Başlığı') ?>
                            </div>
                            <div class="application-meta">
                                <div class="meta-item">
                                    <i class="far fa-calendar"></i>
                                    <?= date('d.m.Y', strtotime($app['created_at'] ?? 'now')) ?>
                                </div>
                                <div class="meta-item">
                                    <i class="far fa-envelope"></i>
                                    <?= htmlspecialchars($app['email'] ?? 'E-posta yok') ?>
                                </div>
                                <?php if (isset($app['phone'])): ?>
                                <div class="meta-item">
                                    <i class="fas fa-phone"></i>
                                    <?= htmlspecialchars($app['phone']) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="application-right">
                            <?php 
                            $score = $app['ai_score'] ?? 0;
                            $scoreClass = $score >= 80 ? 'score-excellent' : ($score >= 70 ? 'score-good' : 'score-medium');
                            ?>
                            <div class="score-badge <?= $scoreClass ?>">
                                <i class="fas fa-star"></i>
                                <?= number_format($score) ?>%
                            </div>

                            <?php 
                            $status = $app['status'] ?? 'pending';
                            $statusLabels = [
                                'pending' => 'Bekliyor',
                                'reviewed' => 'İncelendi',
                                'accepted' => 'Kabul Edildi',
                                'rejected' => 'Reddedildi'
                            ];
                            ?>
                            <div class="status-badge status-<?= $status ?>">
                                <?= $statusLabels[$status] ?? 'Bilinmiyor' ?>
                            </div>

                            <div class="application-actions">
                                <a href="<?= url('employer/application/' . ($app['id'] ?? '')) ?>" class="btn-mini btn-view">
                                    <i class="fas fa-eye"></i> Görüntüle
                                </a>
                                <?php if ($status === 'pending' || $status === 'reviewed'): ?>
                                <button class="btn-mini btn-accept" onclick="updateStatus(<?= $app['id'] ?? 0 ?>, 'accepted')">
                                    <i class="fas fa-check"></i> Kabul
                                </button>
                                <button class="btn-mini btn-reject" onclick="updateStatus(<?= $app['id'] ?? 0 ?>, 'rejected')">
                                    <i class="fas fa-times"></i> Reddet
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>Henüz başvuru yok</h3>
                <p>
                    <?php if (($current_filter ?? 'all') === 'top'): ?>
                        80+ AI skoruna sahip başvuru bulunmuyor. Diğer filtreleri deneyin.
                    <?php elseif (($current_filter ?? 'all') === 'new'): ?>
                        Son 7 günde yeni başvuru alınmadı.
                    <?php else: ?>
                        Bu kriterlere uygun başvuru bulunamadı. Filtreleri değiştirmeyi deneyin.
                    <?php endif; ?>
                </p>
                <a href="<?= url('employer/applications') ?>" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Tüm Başvuruları Gör
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function filterByJob(jobId) {
            const currentUrl = new URL(window.location.href);
            if (jobId) {
                currentUrl.searchParams.set('job_id', jobId);
            } else {
                currentUrl.searchParams.delete('job_id');
            }
            window.location.href = currentUrl.toString();
        }

        function filterByStatus(status) {
            const currentUrl = new URL(window.location.href);
            if (status) {
                currentUrl.searchParams.set('status', status);
            } else {
                currentUrl.searchParams.delete('status');
            }
            window.location.href = currentUrl.toString();
        }

        function filterByScore(score) {
            const currentUrl = new URL(window.location.href);
            if (score) {
                currentUrl.searchParams.set('min_score', score);
            } else {
                currentUrl.searchParams.delete('min_score');
            }
            window.location.href = currentUrl.toString();
        }

        function updateStatus(applicationId, status) {
            if (confirm(`Bu başvuruyu ${status === 'accepted' ? 'kabul' : 'reddet'}mek istediğinizden emin misiniz?`)) {
                // AJAX call would go here
                alert(`Başvuru durumu güncelleniyor: ${status}\n\nBu özellik yakında aktif olacak!`);
                // window.location.reload();
            }
        }

        function exportApplications() {
            alert('Başvurular Excel dosyasına aktarılıyor...\n\nBu özellik yakında aktif olacak!');
        }
    </script>
</body>
</html>
