<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'İş Başvurusu' ?> - İşe Alım Platformu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('assets/css/modern-dashboard.css') ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
        }

        /* Navigation */
        .apply-nav {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .apply-nav .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .apply-nav .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
        }

        .apply-nav .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .apply-nav .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .apply-nav .nav-links a:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .apply-hero {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
            text-align: center;
        }

        .apply-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .apply-hero p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Quick Search */
        .quick-search {
            max-width: 800px;
            margin: 0 auto 3rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 1rem;
        }

        .search-form input,
        .search-form select {
            padding: 1rem;
            border: 2px solid rgba(103, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .search-form input:focus,
        .search-form select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(103, 126, 234, 0.1);
        }

        .search-btn {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(103, 126, 234, 0.3);
        }

        /* Stats Section */
        .stats-section {
            max-width: 1400px;
            margin: 0 auto 4rem;
            padding: 0 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            font-weight: 500;
        }

        /* Jobs Section */
        .jobs-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 1rem;
        }

        .section-header p {
            font-size: 1.1rem;
            color: #718096;
        }

        /* Job Cards Grid */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .job-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .job-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1.5rem;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .job-badge {
            padding: 0.4rem 1rem;
            background: #f0fdf4;
            color: #16a34a;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .job-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .company-name {
            color: #718096;
            font-size: 1rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .job-details {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .job-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .job-description {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .job-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .tag {
            padding: 0.4rem 0.8rem;
            background: #f7fafc;
            color: #4a5568;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .apply-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(103, 126, 234, 0.3);
        }

        /* Category Sections */
        .category-section {
            margin-bottom: 4rem;
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .category-header h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a202c;
        }

        .view-all {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .view-all:hover {
            opacity: 0.7;
        }

        /* AI Features Section */
        .ai-features {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
        }

        .feature-card h4 {
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .apply-hero h1 {
                font-size: 2rem;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .jobs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="apply-nav">
        <div class="container">
            <a href="<?= url('/') ?>" class="logo">İşe Alım</a>
            <div class="nav-links">
                <a href="<?= url('jobs') ?>">Tüm İlanlar</a>
                <a href="<?= url('auth/login') ?>">Giriş Yap</a>
                <a href="<?= url('auth/register') ?>" style="background: white; color: #667eea; padding: 0.5rem 1.5rem; border-radius: 8px;">Kayıt Ol</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="apply-hero">
        <h1>Hayalinizdeki İşe<br>Adım Atın</h1>
        <p>AI destekli platformumuzla size en uygun iş ilanlarını keşfedin ve başvurunuzu hızlıca tamamlayın</p>

        <!-- Quick Search -->
        <div class="quick-search">
            <form class="search-form" action="<?= url('jobs') ?>" method="GET">
                <input type="text" name="search" placeholder="Pozisyon, teknoloji, şirket..." />
                <input type="text" name="location" placeholder="Şehir, lokasyon..." />
                <button type="submit" class="search-btn">🔍 İş Ara</button>
            </form>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Aktif İlan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">250+</div>
                <div class="stat-label">Şirket</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1000+</div>
                <div class="stat-label">Başarılı Başvuru</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98%</div>
                <div class="stat-label">Memnuniyet</div>
            </div>
        </div>
    </div>

    <!-- Jobs Section -->
    <div class="jobs-section">
        <div class="section-header">
            <h2>🔥 Popüler İş İlanları</h2>
            <p>En çok başvuru alan ve size özel önerilen pozisyonlar</p>
        </div>

        <?php if (!empty($popular_jobs) && !empty($popular_jobs['jobs'])): ?>
        <div class="jobs-grid">
            <?php foreach (array_slice($popular_jobs['jobs'], 0, 6) as $job): ?>
            <a href="<?= url('job/' . $job['id']) ?>" style="text-decoration: none; color: inherit;">
            <div class="job-card">

                <div class="job-card-header">
                    <div class="company-logo"><?= strtoupper(substr($job['company_name'] ?? 'Ş', 0, 1)) ?></div>
                    <div class="job-badge">✨ Önerilen</div>
                </div>
                
                <h3><?= htmlspecialchars($job['title']) ?></h3>
                <div class="company-name"><?= htmlspecialchars($job['company_name'] ?? 'Şirket') ?></div>
                
                <div class="job-details">
                    <div class="job-detail">
                        📍 <?= htmlspecialchars($job['location'] ?? 'Uzaktan') ?>
                    </div>
                    <div class="job-detail">
                        💼 <?= htmlspecialchars($job['employment_type'] ?? 'Tam Zamanlı') ?>
                    </div>
                    <div class="job-detail">
                        ⏱️ <?= htmlspecialchars($job['experience_level'] ?? 'Mid-Level') ?>
                    </div>
                </div>

                <?php if (!empty($job['description'])): ?>
                <div class="job-description">
                    <?= htmlspecialchars(substr($job['description'], 0, 120)) ?>...
                </div>
                <?php endif; ?>

                <?php if (!empty($job['requirements'])): ?>
                <div class="job-tags">
                    <?php 
                    $requirements = explode(',', $job['requirements']);
                    foreach (array_slice($requirements, 0, 3) as $req): 
                    ?>
                    <span class="tag"><?= htmlspecialchars(trim($req)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <button class="apply-btn">
                    Hemen Başvur →
                </button>
            </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 3rem; color: #718096;">
            <p>Henüz iş ilanı bulunmamaktadır.</p>
        </div>
        <?php endif; ?>

        <!-- Category Sections -->
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category => $categoryJobs): ?>
                <?php if (!empty($categoryJobs['jobs'])): ?>
                <div class="category-section">
                    <div class="category-header">
                        <h3><?= htmlspecialchars($category) ?></h3>
                        <a href="<?= url('jobs?search=' . urlencode(strtolower($category))) ?>" class="view-all">Tümünü Gör →</a>
                    </div>
                    
                    <div class="jobs-grid">
                        <?php foreach (array_slice($categoryJobs['jobs'], 0, 4) as $job): ?>
                        <a href="<?= url('job/' . $job['id']) ?>" style="text-decoration: none; color: inherit;">
                        <div class="job-card">

                            <div class="job-card-header">
                                <div class="company-logo"><?= strtoupper(substr($job['company_name'] ?? 'Ş', 0, 1)) ?></div>
                                <div class="job-badge" style="background: #eff6ff; color: #2563eb;">Yeni</div>
                            </div>
                            
                            <h3><?= htmlspecialchars($job['title']) ?></h3>
                            <div class="company-name"><?= htmlspecialchars($job['company_name'] ?? 'Şirket') ?></div>
                            
                            <div class="job-details">
                                <div class="job-detail">📍 <?= htmlspecialchars($job['location'] ?? 'Uzaktan') ?></div>
                                <div class="job-detail">💼 <?= htmlspecialchars($job['employment_type'] ?? 'Tam Zamanlı') ?></div>
                            </div>

                            <button class="apply-btn">
                                Başvur →
                            </button>
                        </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- AI Features -->
    <div class="ai-features">
        <div class="section-header" style="margin-bottom: 3rem;">
            <h2 style="color: white;">🤖 AI ile Akıllı Başvuru</h2>
            <p style="color: rgba(255, 255, 255, 0.9);">Yapay zeka destekli özelliklerimizle başvuru sürecinizi kolaylaştırın</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h4>Akıllı Eşleşme</h4>
                <p>AI, yeteneklerinizi analiz ederek size en uygun iş ilanlarını önerir</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h4>Otomatik Form</h4>
                <p>Başvuru formlarınız otomatik olarak doldurulur, zamandan tasarruf edin</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h4>Başarı Analizi</h4>
                <p>Başvurularınızın başarı oranını artırmak için öneriler alın</p>
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
