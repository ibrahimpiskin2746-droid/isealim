<?php
session_start();

// Check if logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    header('Location: login.php');
    exit;
}

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

$employerId = $_SESSION['user_id'];

// Get user info
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$userStmt->execute([':id' => $employerId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Get statistics
$jobStatsStmt = $pdo->prepare("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
    SUM(views) as total_views
    FROM jobs WHERE employer_id = :id");
$jobStatsStmt->execute([':id' => $employerId]);
$jobStats = $jobStatsStmt->fetch(PDO::FETCH_ASSOC);

$appStatsStmt = $pdo->prepare("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted
    FROM applications a
    INNER JOIN jobs j ON a.job_id = j.id
    WHERE j.employer_id = :id");
$appStatsStmt->execute([':id' => $employerId]);
$appStats = $appStatsStmt->fetch(PDO::FETCH_ASSOC);

// Get recent jobs
$jobsStmt = $pdo->prepare("SELECT * FROM jobs WHERE employer_id = :id ORDER BY created_at DESC LIMIT 5");
$jobsStmt->execute([':id' => $employerId]);
$recentJobs = $jobsStmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent applications
$appsStmt = $pdo->prepare("SELECT a.*, j.title as job_title, u.full_name as applicant_name, u.email
    FROM applications a
    INNER JOIN jobs j ON a.job_id = j.id
    LEFT JOIN users u ON a.applicant_id = u.id
    WHERE j.employer_id = :id
    ORDER BY a.created_at DESC LIMIT 10");
$appsStmt->execute([':id' => $employerId]);
$recentApplications = $appsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İşveren Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo { color: white; font-size: 24px; font-weight: 700; text-decoration: none; }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .nav-links a:hover { background: rgba(255,255,255,0.1); }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .page-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .page-header h1 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 5px;
        }
        .page-header p {
            color: #718096;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .icon-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .icon-orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #718096;
            font-size: 14px;
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        .card-header h2 {
            font-size: 20px;
            color: #2d3748;
        }
        .job-item, .app-item {
            padding: 15px;
            background: #f7fafc;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .job-item:last-child, .app-item:last-child {
            margin-bottom: 0;
        }
        .job-title, .app-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }
        .job-meta, .app-meta {
            font-size: 13px;
            color: #718096;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        @media (max-width: 968px) {
            .content-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="dashboard-employer.php" class="logo">
                <i class="fas fa-briefcase"></i> İşveren Paneli
            </a>
            <div class="nav-links">
                <a href="jobs.php"><i class="fas fa-home"></i> Ana Sayfa</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Hoş Geldiniz, <?= htmlspecialchars($user['company_name']) ?>!</h1>
            <p>İşveren Yönetim Paneli</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($jobStats['total']) ?></div>
                <div class="stat-label">Toplam İlan</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($jobStats['published']) ?></div>
                <div class="stat-label">Yayında</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($appStats['total']) ?></div>
                <div class="stat-label">Toplam Başvuru</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($jobStats['total_views']) ?></div>
                <div class="stat-label">Görüntülenme</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> Son İlanlar</h2>
                </div>
                <?php foreach ($recentJobs as $job): ?>
                    <div class="job-item">
                        <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
                        <div class="job-meta">
                            <span class="badge badge-<?= $job['status'] === 'published' ? 'success' : 'warning' ?>">
                                <?= $job['status'] === 'published' ? 'Yayında' : 'Taslak' ?>
                            </span>
                            • <?= date('d.m.Y', strtotime($job['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-file-alt"></i> Son Başvurular</h2>
                </div>
                <?php foreach ($recentApplications as $app): ?>
                    <div class="app-item">
                        <div class="app-name"><?= htmlspecialchars($app['applicant_name'] ?? 'İsimsiz') ?></div>
                        <div class="app-meta">
                            <?= htmlspecialchars($app['job_title']) ?> • 
                            <span class="badge badge-info">Skor: <?= $app['ai_score'] ?>%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
