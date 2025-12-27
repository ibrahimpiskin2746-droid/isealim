<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'job_platform';
$username = 'root';
$password = 'mysql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Get filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

// Build query
$sql = "SELECT j.*, u.company_name, u.company_logo,
        (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
        FROM jobs j
        LEFT JOIN users u ON j.employer_id = u.id
        WHERE j.status = 'published'";

$params = [];

if ($search) {
    $sql .= " AND (j.title LIKE :search OR j.description LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($location) {
    $sql .= " AND j.location LIKE :location";
    $params[':location'] = "%$location%";
}

if ($type) {
    $sql .= " AND j.employment_type = :type";
    $params[':type'] = $type;
}

$sql .= " ORDER BY j.created_at DESC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsStmt = $pdo->query("SELECT 
    COUNT(*) as total_jobs,
    COUNT(DISTINCT employer_id) as total_companies,
    (SELECT COUNT(*) FROM applications) as total_applications
    FROM jobs WHERE status = 'published'");
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İş İlanları - AI Destekli İş Platformu</title>
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
            line-height: 1.6;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            font-weight: 600;
        }
        
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 20px;
            color: white;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .search-box {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-search {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
        }
        
        .stats {
            max-width: 1200px;
            margin: -30px auto 40px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 14px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .job-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .company-logo {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .job-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .company-name {
            color: #718096;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .job-meta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
            font-size: 13px;
            color: #718096;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .job-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .tag {
            padding: 5px 12px;
            background: #f7fafc;
            border-radius: 20px;
            font-size: 12px;
            color: #4a5568;
        }
        
        .tag.primary {
            background: #e6fffa;
            color: #234e52;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            font-size: 80px;
            color: #cbd5e0;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #718096;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }
            
            .search-box {
                flex-direction: column;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            .jobs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="jobs.php" class="logo">
                <i class="fas fa-briefcase"></i> İş Platformu
            </a>
            <div class="nav-links">
                <a href="jobs.php">İş İlanları</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_type'] === 'employer'): ?>
                        <a href="dashboard-employer.php">Dashboard</a>
                    <?php else: ?>
                        <a href="dashboard-applicant.php">Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php">Çıkış Yap</a>
                <?php else: ?>
                    <a href="login.php">Giriş Yap</a>
                    <a href="register.php" class="btn-primary">Üye Ol</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>AI Destekli İş Bulma Platformu</h1>
        <p>Yapay zeka ile en uygun iş fırsatlarını keşfedin</p>
        
        <form method="GET" action="jobs.php" class="search-box">
            <input type="text" name="search" class="search-input" 
                   placeholder="İş pozisyonu veya anahtar kelime..." 
                   value="<?= htmlspecialchars($search) ?>">
            <input type="text" name="location" class="search-input" 
                   placeholder="Şehir..." 
                   value="<?= htmlspecialchars($location) ?>">
            <select name="type" class="search-input">
                <option value="">Tüm Tipler</option>
                <option value="full-time" <?= $type === 'full-time' ? 'selected' : '' ?>>Tam Zamanlı</option>
                <option value="part-time" <?= $type === 'part-time' ? 'selected' : '' ?>>Yarı Zamanlı</option>
                <option value="contract" <?= $type === 'contract' ? 'selected' : '' ?>>Sözleşmeli</option>
                <option value="freelance" <?= $type === 'freelance' ? 'selected' : '' ?>>Freelance</option>
            </select>
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Ara
            </button>
        </form>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_jobs']) ?></div>
            <div class="stat-label">Aktif İş İlanı</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_companies']) ?></div>
            <div class="stat-label">Şirket</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_applications']) ?></div>
            <div class="stat-label">Başvuru</div>
        </div>
    </div>

    <!-- Jobs List -->
    <div class="container">
        <?php if (count($jobs) > 0): ?>
            <div class="jobs-grid">
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card" onclick="location.href='job-detail.php?id=<?= $job['id'] ?>'">
                        <div class="company-logo">
                            <?php if ($job['company_logo']): ?>
                                <img src="<?= htmlspecialchars($job['company_logo']) ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                            <?php else: ?>
                                <i class="fas fa-building"></i>
                            <?php endif; ?>
                        </div>
                        
                        <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
                        <div class="company-name">
                            <i class="fas fa-building"></i> 
                            <?= htmlspecialchars($job['company_name'] ?? 'Şirket Adı') ?>
                        </div>
                        
                        <div class="job-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($job['location']) ?>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <?= ucfirst(str_replace('-', ' ', $job['employment_type'])) ?>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <?= $job['application_count'] ?> Başvuru
                            </div>
                        </div>
                        
                        <?php if ($job['salary_min'] && $job['salary_max']): ?>
                        <div class="job-meta">
                            <div class="meta-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <?= number_format($job['salary_min']) ?> - <?= number_format($job['salary_max']) ?> TL
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="job-tags">
                            <span class="tag primary"><?= htmlspecialchars($job['experience_level']) ?></span>
                            <span class="tag">
                                <i class="far fa-calendar"></i>
                                <?= date('d.m.Y', strtotime($job['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>İlan Bulunamadı</h3>
                <p>Arama kriterlerinize uygun iş ilanı bulunamadı. Lütfen farklı filtreler deneyin.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
