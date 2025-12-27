<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .jobs-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0 4rem;
    }

    .jobs-header {
        max-width: 1400px;
        margin: 0 auto 3rem;
        padding: 0 2rem;
        text-align: center;
    }

    .jobs-header h1 {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
    }

    .jobs-header p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
    }

    /* Filters */
    .filters-section {
        max-width: 1400px;
        margin: 0 auto 2rem;
        padding: 0 2rem;
    }

    .filters-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .filters-form {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.5fr 1.5fr auto;
        gap: 1rem;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select {
        padding: 0.875rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(103, 126, 234, 0.1);
    }

    .filter-btn {
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(103, 126, 234, 0.3);
    }

    /* Jobs Container */
    .jobs-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: <?= isLoggedIn() && isApplicant() ? '300px 1fr' : '1fr' ?>;
        gap: 2rem;
    }

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

    .jobs-main {
        min-width: 0;
    }

    .jobs-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        color: white;
    }

    .jobs-count {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .view-toggle {
        display: flex;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem;
        border-radius: 10px;
    }

    .view-toggle button {
        padding: 0.5rem 1rem;
        background: transparent;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .view-toggle button.active {
        background: white;
        color: #667eea;
    }

    /* Jobs Grid */
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
    }

    .job-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }

    .job-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1.5rem;
    }

    .company-logo {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .job-badges {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .job-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-new {
        background: #f0fdf4;
        color: #16a34a;
    }

    .badge-featured {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-urgent {
        background: #fee2e2;
        color: #dc2626;
    }

    .job-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .company-name {
        color: #718096;
        font-size: 1rem;
        margin-bottom: 1.2rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .job-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .job-detail {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #4a5568;
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        background: #f7fafc;
        border-radius: 8px;
    }

    .job-description {
        color: #718096;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .job-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        min-height: 32px;
    }

    .job-tag {
        padding: 0.4rem 0.8rem;
        background: #eef2ff;
        color: #667eea;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .job-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .job-stats {
        display: flex;
        gap: 1.5rem;
        color: #718096;
        font-size: 0.9rem;
    }

    .apply-btn {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .apply-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(103, 126, 234, 0.3);
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 2rem;
    }

    .clear-filters-btn {
        padding: 0.875rem 2rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .pagination a,
    .pagination span {
        padding: 0.75rem 1.25rem;
        background: white;
        color: #667eea;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .pagination span.current {
        background: #667eea;
        color: white;
    }

    @media (max-width: 1024px) {
        .jobs-container {
            grid-template-columns: 1fr !important;
        }

        .profile-sidebar {
            position: static;
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .jobs-header h1 {
            font-size: 2rem;
        }

        .filters-form {
            grid-template-columns: 1fr;
        }

        .jobs-grid {
            grid-template-columns: 1fr;
        }

        .job-details {
            flex-direction: column;
            gap: 0.5rem;
        }

        .job-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .apply-btn {
            width: 100%;
        }

        .profile-stats {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="jobs-page">
    <!-- Header -->
    <div class="jobs-header">
        <h1>💼 İş İlanları</h1>
        <p>Size en uygun pozisyonları keşfedin ve kariyerinize yön verin</p>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-card">
            <form class="filters-form" method="GET" action="<?= url('jobs') ?>">
                <div class="form-group">
                    <label>🔍 Anahtar Kelime</label>
                    <input type="text" name="search" placeholder="Pozisyon, teknoloji, şirket..." 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>📍 Lokasyon</label>
                    <input type="text" name="location" placeholder="Şehir veya uzaktan..." 
                           value="<?= htmlspecialchars($filters['location'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>💼 Çalışma Tipi</label>
                    <select name="employment_type">
                        <option value="">Tümü</option>
                        <option value="Tam Zamanlı" <?= ($filters['employment_type'] ?? '') == 'Tam Zamanlı' ? 'selected' : '' ?>>Tam Zamanlı</option>
                        <option value="Yarı Zamanlı" <?= ($filters['employment_type'] ?? '') == 'Yarı Zamanlı' ? 'selected' : '' ?>>Yarı Zamanlı</option>
                        <option value="Freelance" <?= ($filters['employment_type'] ?? '') == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                        <option value="Staj" <?= ($filters['employment_type'] ?? '') == 'Staj' ? 'selected' : '' ?>>Staj</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>⏱️ Deneyim Seviyesi</label>
                    <select name="experience_level">
                        <option value="">Tümü</option>
                        <option value="Entry Level" <?= ($filters['experience_level'] ?? '') == 'Entry Level' ? 'selected' : '' ?>>Giriş Seviyesi</option>
                        <option value="Mid Level" <?= ($filters['experience_level'] ?? '') == 'Mid Level' ? 'selected' : '' ?>>Orta Seviye</option>
                        <option value="Senior" <?= ($filters['experience_level'] ?? '') == 'Senior' ? 'selected' : '' ?>>Kıdemli</option>
                        <option value="Lead" <?= ($filters['experience_level'] ?? '') == 'Lead' ? 'selected' : '' ?>>Lider</option>
                    </select>
                </div>

                <button type="submit" class="filter-btn">Filtrele</button>
            </form>
        </div>
    </div>

    <!-- Jobs Container -->
    <div class="jobs-container">
        
        <!-- Profile Sidebar (only for logged in applicants) -->
        <?php if (isLoggedIn() && isApplicant()): 
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
                        <div class="stat-value">
                            <?php
                            try {
                                $applicationModel = new Application();
                                $myApplications = $applicationModel->getApplicantApplications(authId(), 1);
                                echo count($myApplications);
                            } catch (Exception $e) {
                                echo '0';
                            }
                            ?>
                        </div>
                        <div class="stat-label">Başvuru</div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="<?= url('applicant/profile') ?>" class="profile-btn profile-btn-primary">
                        ✏️ Profili Düzenle
                    </a>
                    <a href="<?= url('applicant/applications') ?>" class="profile-btn profile-btn-secondary">
                        📋 Başvurularım
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
        <?php endif; ?>

        <!-- Jobs Main Content -->
        <div class="jobs-main">
        <?php if (!empty($jobs) && is_array($jobs) && count($jobs) > 0): ?>
            <div class="jobs-info">
                <div class="jobs-count">
                    ✨ <?= count($jobs) ?> ilan bulundu
                </div>
            </div>

            <div class="jobs-grid">
                <?php foreach ($jobs as $job): ?>
                <a href="<?= url('job/' . $job['id']) ?>" style="text-decoration: none; color: inherit;">
                <div class="job-card">

                    <div class="job-card-header">
                        <div class="company-logo">
                            <?= strtoupper(substr($job['company_name'] ?? $job['full_name'] ?? 'Ş', 0, 1)) ?>
                        </div>
                        <div class="job-badges">
                            <?php 
                            $created = strtotime($job['created_at']);
                            $now = time();
                            $diff = $now - $created;
                            if ($diff < 86400 * 3): // 3 gün
                            ?>
                            <span class="job-badge badge-new">🆕 Yeni</span>
                            <?php endif; ?>
                            
                            <?php if (!empty($job['is_featured'])): ?>
                            <span class="job-badge badge-featured">⭐ Öne Çıkan</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h3 class="job-title"><?= htmlspecialchars($job['title']) ?></h3>
                    
                    <div class="company-name">
                        <span>🏢</span>
                        <?= htmlspecialchars($job['company_name'] ?? $job['full_name'] ?? 'Şirket') ?>
                    </div>

                    <div class="job-details">
                        <div class="job-detail">
                            📍 <?= htmlspecialchars($job['location'] ?? 'Belirtilmemiş') ?>
                        </div>
                        <div class="job-detail">
                            💼 <?= htmlspecialchars($job['employment_type'] ?? 'Tam Zamanlı') ?>
                        </div>
                        <div class="job-detail">
                            ⏱️ <?= htmlspecialchars($job['experience_level'] ?? 'Mid Level') ?>
                        </div>
                        <?php if (!empty($job['salary_range'])): ?>
                        <div class="job-detail">
                            💰 <?= htmlspecialchars($job['salary_range']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($job['description'])): ?>
                    <div class="job-description">
                        <?= htmlspecialchars($job['description']) ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($job['requirements'])): ?>
                    <div class="job-tags">
                        <?php 
                        $requirements = explode(',', $job['requirements']);
                        foreach (array_slice($requirements, 0, 5) as $req): 
                            $req = trim($req);
                            if (!empty($req)):
                        ?>
                        <span class="job-tag"><?= htmlspecialchars($req) ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                    <?php endif; ?>

                    <div class="job-footer">
                        <div class="job-stats">
                            <span>👁️ <?= $job['view_count'] ?? 0 ?> görüntülenme</span>
                            <span>📝 <?= $job['application_count'] ?? 0 ?> başvuru</span>
                        </div>
                        <button class="apply-btn">
                            Başvur →
                        </button>
                    </div>
                </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($total) && $total > 12): ?>
            <div class="pagination">
                <?php 
                $currentPage = $page ?? 1;
                $totalPages = ceil($total / 12);
                
                if ($currentPage > 1): ?>
                    <a href="<?= url('jobs?page=' . ($currentPage - 1)) ?>">← Önceki</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= url('jobs?page=' . $i) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= url('jobs?page=' . ($currentPage + 1)) ?>">Sonraki →</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">🔍</div>
                <h3>İlan Bulunamadı</h3>
                <p>Aradığınız kriterlere uygun iş ilanı bulunamadı. Filtreleri değiştirerek tekrar deneyin.</p>
                <a href="<?= url('jobs') ?>" class="clear-filters-btn">Tüm İlanları Göster</a>
            </div>
        <?php endif; ?>
        </div> <!-- .jobs-main -->
    </div> <!-- .jobs-container -->
</div> <!-- .jobs-page -->

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
