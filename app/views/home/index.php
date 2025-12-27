<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    .profile-quick-access {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .btn-profile-quick {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 1rem 2rem;
        border-radius: 16px;
        text-decoration: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .btn-profile-quick:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        border-color: #667eea;
    }

    .profile-quick-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        flex-shrink: 0;
    }

    .profile-quick-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-quick-icon span {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .profile-quick-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .profile-quick-name {
        font-weight: 700;
        font-size: 1.2rem;
        color: #2d3748;
    }

    .profile-quick-label {
        color: #667eea;
        font-size: 0.9rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .btn-profile-quick {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .profile-quick-icon {
            width: 50px;
            height: 50px;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>AI Destekli İş Bulma Deneyimi</h1>
            <p class="hero-subtitle">Yapay zeka ile eşleşen iş ilanlarını keşfet, hızlı başvur, anında değerlendiril</p>
            
            <?php if (isLoggedIn()): ?>
                <!-- Profil Butonu - Giriş yapanlar için -->
                <div class="profile-quick-access" style="margin-bottom: 2rem;">
                    <?php if (isApplicant()): ?>
                        <a href="<?= url('applicant/profile') ?>" class="btn-profile-quick">
                            <div class="profile-quick-icon">
                                <?php if (!empty(auth()['profile_image'])): ?>
                                    <img src="<?= url(auth()['profile_image']) ?>" alt="Profil">
                                <?php else: ?>
                                    <span><?= strtoupper(substr(auth()['full_name'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="profile-quick-text">
                                <span class="profile-quick-name"><?= htmlspecialchars(auth()['full_name']) ?></span>
                                <span class="profile-quick-label">Profilimi Görüntüle & Düzenle</span>
                            </div>
                        </a>
                    <?php elseif (isEmployer()): ?>
                        <a href="<?= url('employer/profile') ?>" class="btn-profile-quick">
                            <div class="profile-quick-icon">
                                <?php if (!empty(auth()['profile_image'])): ?>
                                    <img src="<?= url(auth()['profile_image']) ?>" alt="Logo">
                                <?php else: ?>
                                    <span>🏢</span>
                                <?php endif; ?>
                            </div>
                            <div class="profile-quick-text">
                                <span class="profile-quick-name"><?= htmlspecialchars(auth()['company_name'] ?? auth()['full_name']) ?></span>
                                <span class="profile-quick-label">Şirket Profilimi Görüntüle</span>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="hero-search">
                <form method="GET" action="<?= url('jobs') ?>" class="search-form">
                    <div class="search-input-group">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Pozisyon, şirket veya kelime ara..." class="search-input">
                    </div>
                    
                    <div class="search-input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" name="location" placeholder="Şehir" class="search-input">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> İş Ara
                    </button>
                </form>
            </div>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= number_format($stats['total_jobs']) ?></span>
                    <span class="stat-label">Aktif İlan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= number_format($stats['total_employers']) ?></span>
                    <span class="stat-label">İşveren</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= number_format($stats['total_applicants']) ?></span>
                    <span class="stat-label">Aday</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <h2 class="section-title">Neden Bizi Seçmelisiniz?</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h3>AI Destekli Eşleşme</h3>
                <p>Yapay zeka algoritmaları sayesinde size en uygun iş ilanlarını ve adayları bulun</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Hızlı Değerlendirme</h3>
                <p>Başvurular anında AI tarafından değerlendirilir ve skorlanır</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Detaylı Analizler</h3>
                <p>Her aday için güçlü yönler, zayıf yönler ve uyumluluk skoru</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Güvenli Platform</h3>
                <p>Verileriniz en yüksek güvenlik standartlarıyla korunur</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Jobs Section -->
<section class="recent-jobs">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Son Eklenen İş İlanları</h2>
            <a href="<?= url('jobs') ?>" class="btn btn-outline">Tümünü Gör</a>
        </div>
        
        <div class="jobs-grid">
            <?php foreach (array_slice($jobs, 0, 6) as $job): ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <div class="company-logo">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="job-info">
                            <h3><a href="<?= url('job/' . $job['id']) ?>"><?= escape($job['title']) ?></a></h3>
                            <p class="company-name"><?= escape($job['company_name']) ?></p>
                        </div>
                    </div>
                    
                    <div class="job-card-body">
                        <p class="job-description"><?= truncate(strip_tags($job['description']), 120) ?></p>
                        
                        <div class="job-meta">
                            <span class="job-badge"><i class="fas fa-map-marker-alt"></i> <?= escape($job['location']) ?></span>
                            <span class="job-badge"><i class="fas fa-clock"></i> <?= EMPLOYMENT_TYPES[$job['employment_type']] ?></span>
                            <span class="job-badge"><i class="fas fa-layer-group"></i> <?= EXPERIENCE_LEVELS[$job['experience_level']] ?></span>
                        </div>
                    </div>
                    
                    <div class="job-card-footer">
                        <span class="job-date"><?= timeAgo($job['published_at']) ?></span>
                        <a href="<?= url('job/' . $job['id']) ?>" class="btn btn-primary btn-sm">Başvur</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2>Hemen Başlayın</h2>
            <p>Ücretsiz hesap oluşturun ve kariyerinize yön verin</p>
            <div class="cta-buttons">
                <a href="<?= url('jobs') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> İş Başvurusu
                </a>
                <a href="<?= url('employer/dashboard') ?>" class="btn btn-outline btn-lg">
                    <i class="fas fa-briefcase"></i> İşe Alım
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
