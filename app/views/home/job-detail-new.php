<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    body {
        margin: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .job-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .job-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .job-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    }

    .job-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem;
        color: white;
    }

    .job-hero-top {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .company-icon {
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 900;
        color: #667eea;
        flex-shrink: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .job-title-area h1 {
        font-size: 2.8rem;
        font-weight: 900;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .company-title {
        font-size: 1.4rem;
        opacity: 0.95;
        font-weight: 600;
    }

    .job-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .badge {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 600;
    }

    .job-body {
        padding: 3.5rem;
    }

    .section {
        margin-bottom: 3.5rem;
    }

    .section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title .emoji {
        font-size: 2.2rem;
    }

    .section-text {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #4a5568;
    }

    .list {
        list-style: none;
        padding: 0;
    }

    .list li {
        padding: 1rem 0;
        padding-left: 2.5rem;
        position: relative;
        font-size: 1.1rem;
        line-height: 1.7;
        color: #2d3748;
        border-bottom: 1px solid #e2e8f0;
    }

    .list li:last-child {
        border-bottom: none;
    }

    .list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #667eea;
        font-weight: 900;
        font-size: 1.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .stat-box {
        background: #f7fafc;
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        border: 2px solid #e2e8f0;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #718096;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .apply-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem;
        border-radius: 20px;
        text-align: center;
        color: white;
        margin-top: 3rem;
    }

    .apply-section h2 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }

    .apply-section p {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }

    .apply-btn {
        display: inline-block;
        padding: 1.2rem 3rem;
        background: white;
        color: #667eea;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1.2rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .apply-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }

    @media (max-width: 768px) {
        .job-hero {
            padding: 2rem;
        }

        .job-hero-top {
            flex-direction: column;
            text-align: center;
        }

        .company-icon {
            margin: 0 auto;
        }

        .job-title-area h1 {
            font-size: 2rem;
        }

        .job-body {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="job-page">
    <div class="job-container">
        <a href="<?= url('jobs') ?>" class="back-link">
            ← Tüm İlanlara Dön
        </a>

        <div class="job-card">
            <!-- Hero Section -->
            <div class="job-hero">
                <div class="job-hero-top">
                    <div class="company-icon">
                        <?= strtoupper(substr($job['company_name'] ?? 'İ', 0, 1)) ?>
                    </div>
                    <div class="job-title-area">
                        <h1><?= htmlspecialchars($job['title'] ?? 'İş İlanı') ?></h1>
                        <div class="company-title"><?= htmlspecialchars($job['company_name'] ?? 'Şirket Adı') ?></div>
                    </div>
                </div>

                <div class="job-badges">
                    <div class="badge">
                        📍 <?= htmlspecialchars($job['location'] ?? 'Lokasyon') ?>
                    </div>
                    <div class="badge">
                        💼 <?= isset($job['employment_type']) && isset(EMPLOYMENT_TYPES[$job['employment_type']]) ? EMPLOYMENT_TYPES[$job['employment_type']] : 'Tam Zamanlı' ?>
                    </div>
                    <div class="badge">
                        ⏱️ <?= isset($job['experience_level']) && isset(EXPERIENCE_LEVELS[$job['experience_level']]) ? EXPERIENCE_LEVELS[$job['experience_level']] : 'Orta Seviye' ?>
                    </div>
                    <?php if (!empty($job['salary_min']) && !empty($job['salary_max'])): ?>
                    <div class="badge">
                        💰 ₺<?= number_format($job['salary_min']) ?> - ₺<?= number_format($job['salary_max']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Body Section -->
            <div class="job-body">
                <!-- İstatistikler -->
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-value"><?= $job['view_count'] ?? 0 ?></div>
                        <div class="stat-label">Görüntülenme</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value"><?= $job['application_count'] ?? 0 ?></div>
                        <div class="stat-label">Başvuru</div>
                    </div>
                    <?php if (!empty($job['application_deadline'])): ?>
                    <div class="stat-box">
                        <div class="stat-value"><?= date('d/m', strtotime($job['application_deadline'])) ?></div>
                        <div class="stat-label">Son Başvuru</div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- İş Tanımı -->
                <?php if (!empty($job['description'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">📋</span> İş Tanımı</h2>
                    <div class="section-text"><?= nl2br(htmlspecialchars($job['description'])) ?></div>
                </div>
                <?php endif; ?>

                <!-- Aranan Nitelikler -->
                <?php if (!empty($job['requirements'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">✅</span> Aranan Nitelikler</h2>
                    <ul class="list">
                        <?php 
                        $requirements = is_array($job['requirements']) ? $job['requirements'] : explode(',', $job['requirements']);
                        foreach ($requirements as $req): 
                            $req = trim($req);
                            if (!empty($req)):
                        ?>
                        <li><?= htmlspecialchars($req) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Sorumluluklar -->
                <?php if (!empty($job['responsibilities'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">🎯</span> Sorumluluklar</h2>
                    <ul class="list">
                        <?php 
                        $responsibilities = is_array($job['responsibilities']) ? $job['responsibilities'] : explode(',', $job['responsibilities']);
                        foreach ($responsibilities as $resp): 
                            $resp = trim($resp);
                            if (!empty($resp)):
                        ?>
                        <li><?= htmlspecialchars($resp) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Yan Haklar -->
                <?php if (!empty($job['benefits'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">🎁</span> Yan Haklar ve İmkanlar</h2>
                    <ul class="list">
                        <?php 
                        $benefits = is_array($job['benefits']) ? $job['benefits'] : explode(',', $job['benefits']);
                        foreach ($benefits as $benefit): 
                            $benefit = trim($benefit);
                            if (!empty($benefit)):
                        ?>
                        <li><?= htmlspecialchars($benefit) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Başvuru Bölümü -->
                <div class="apply-section">
                    <h2>🚀 Bu Pozisyona Başvurun</h2>
                    <p>Kariyerinizi bir sonraki seviyeye taşımak için hemen başvurun!</p>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= url('applicant/apply/' . ($job['id'] ?? '')) ?>" class="apply-btn">
                            Hemen Başvur
                        </a>
                    <?php else: ?>
                        <a href="<?= url('auth/login?redirect=job/' . ($job['id'] ?? '')) ?>" class="apply-btn">
                            Giriş Yapın ve Başvurun
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
