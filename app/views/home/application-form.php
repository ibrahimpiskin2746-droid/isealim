<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .application-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 4rem 0;
    }

    .application-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .application-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .application-header h1 {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .application-header p {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.95);
        max-width: 600px;
        margin: 0 auto;
    }

    .application-content {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .section-title i {
        color: #667eea;
        font-size: 1.5rem;
    }

    .section-description {
        font-size: 1.1rem;
        color: #4a5568;
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .job-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .job-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(103, 126, 234, 0.2);
        border-color: #667eea;
    }

    .job-card:hover::before {
        transform: scaleX(1);
    }

    .job-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .job-logo {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .job-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .job-badge.new {
        background: #d4edda;
        color: #155724;
    }

    .job-badge.urgent {
        background: #f8d7da;
        color: #721c24;
    }

    .job-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .job-company {
        font-size: 1rem;
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .job-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .job-detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4a5568;
        font-size: 0.95rem;
    }

    .job-detail-item i {
        color: #667eea;
    }

    .job-description {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .job-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .job-salary {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
    }

    .btn-apply {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(103, 126, 234, 0.3);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .feature-card {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(103, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(103, 126, 234, 0.2);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .feature-description {
        color: #4a5568;
        line-height: 1.6;
    }

    .cta-section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.1) 100%);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .cta-section h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
    }

    .cta-section p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-large {
        padding: 1rem 3rem;
        font-size: 1.1rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s;
    }

    .btn-primary {
        background: white;
        color: #667eea;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .btn-outline {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .btn-outline:hover {
        background: white;
        color: #667eea;
        transform: translateY(-3px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #4a5568;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .empty-state p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .application-header h1 {
            font-size: 2rem;
        }

        .application-header p {
            font-size: 1rem;
        }

        .application-content {
            padding: 2rem 1.5rem;
        }

        .jobs-grid {
            grid-template-columns: 1fr;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .btn-large {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="application-page">
    <div class="application-container">
        <!-- Header -->
        <div class="application-header">
            <h1>🚀 İş Başvuru Merkezi</h1>
            <p>Hayalinizdeki işe bir adım daha yaklaşın! Binlerce iş fırsatı sizleri bekliyor.</p>
        </div>

        <!-- Main Content -->
        <div class="application-content">
            <h2 class="section-title">
                <i class="fas fa-briefcase"></i>
                Öne Çıkan İş İlanları
            </h2>
            <p class="section-description">
                En güncel ve popüler iş fırsatlarını keşfedin. Her gün yeni pozisyonlar ekleniyor!
            </p>

            <?php if (!empty($featured_jobs)): ?>
                <div class="jobs-grid">
                    <?php foreach ($featured_jobs as $job): ?>
                        <div class="job-card" onclick="window.location.href='<?= url('job/' . $job['id']) ?>'">
                            <div class="job-card-header">
                                <div class="job-logo">
                                    <?= strtoupper(substr($job['company_name'] ?? 'İŞ', 0, 2)) ?>
                                </div>
                                <?php if ($job['is_urgent'] ?? false): ?>
                                    <span class="job-badge urgent">🔥 Acil</span>
                                <?php else: ?>
                                    <span class="job-badge new">✨ Yeni</span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="job-title"><?= htmlspecialchars($job['title']) ?></h3>
                            <div class="job-company"><?= htmlspecialchars($job['company_name'] ?? 'Şirket') ?></div>
                            
                            <div class="job-details">
                                <div class="job-detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($job['location'] ?? 'Belirtilmemiş') ?></span>
                                </div>
                                <div class="job-detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?= ucfirst($job['employment_type'] ?? 'Tam Zamanlı') ?></span>
                                </div>
                                <div class="job-detail-item">
                                    <i class="fas fa-layer-group"></i>
                                    <span><?= ucfirst($job['experience_level'] ?? 'Orta Seviye') ?></span>
                                </div>
                            </div>
                            
                            <p class="job-description">
                                <?= htmlspecialchars(substr($job['description'] ?? '', 0, 150)) . '...' ?>
                            </p>
                            
                            <div class="job-footer">
                                <div class="job-salary">
                                    <?php if (!empty($job['salary_min']) && !empty($job['salary_max'])): ?>
                                        ₺<?= number_format($job['salary_min']) ?> - ₺<?= number_format($job['salary_max']) ?>
                                    <?php else: ?>
                                        <span style="color: #718096;">Maaş Görüşülür</span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= url('job/' . $job['id']) ?>" class="btn-apply" onclick="event.stopPropagation()">
                                    Başvur <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>Henüz İlan Bulunamadı</h3>
                    <p>Yeni iş ilanları yakında eklenecektir. Lütfen daha sonra tekrar kontrol edin.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Features Section -->
        <div class="application-content">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Neden Bizimle Başvurmalısınız?
            </h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="feature-title">AI Destekli Eşleştirme</h3>
                    <p class="feature-description">
                        Yapay zeka algoritmalarımız, yeteneklerinize en uygun işleri bulur.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Hızlı Başvuru</h3>
                    <p class="feature-description">
                        Sadece birkaç tıklama ile iş başvurunuzu tamamlayın.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Kariyer Takibi</h3>
                    <p class="feature-description">
                        Başvuru süreçlerinizi gerçek zamanlı olarak takip edin.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Güvenli Platform</h3>
                    <p class="feature-description">
                        Kişisel bilgileriniz şifrelenerek korunur.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2>Hayalinizdeki Kariyere Başlayın</h2>
            <p>Ücretsiz hesap oluşturun ve binlerce iş fırsatına anında erişin!</p>
            <div class="cta-buttons">
                <?php if (!isLoggedIn()): ?>
                    <a href="<?= url('auth/register') ?>" class="btn-large btn-primary">
                        <i class="fas fa-user-plus"></i> Ücretsiz Kayıt Ol
                    </a>
                    <a href="<?= url('auth/login') ?>" class="btn-large btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Giriş Yap
                    </a>
                <?php else: ?>
                    <a href="<?= url('jobs') ?>" class="btn-large btn-primary">
                        <i class="fas fa-search"></i> Tüm İlanları Görüntüle
                    </a>
                    <a href="<?= url('applicant/dashboard') ?>" class="btn-large btn-outline">
                        <i class="fas fa-tachometer-alt"></i> Panelime Git
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
