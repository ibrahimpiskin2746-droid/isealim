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

// Get job ID
$jobId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$jobId) {
    header('Location: jobs.php');
    exit;
}

// Fetch job details
$stmt = $pdo->prepare("SELECT j.*, u.company_name, u.company_logo, u.company_description,
        (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
        FROM jobs j
        LEFT JOIN users u ON j.employer_id = u.id
        WHERE j.id = :id AND j.status = 'published'");
$stmt->execute([':id' => $jobId]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    header('Location: jobs.php');
    exit;
}

// Update view count
$pdo->prepare("UPDATE jobs SET views = views + 1 WHERE id = :id")->execute([':id' => $jobId]);

// Get form fields if any
$formStmt = $pdo->prepare("SELECT * FROM job_form_fields WHERE job_id = :id ORDER BY field_order");
$formStmt->execute([':id' => $jobId]);
$formFields = $formStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle application submission
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'applicant') {
        $error = 'Başvuru yapmak için giriş yapmalısınız.';
    } else {
        // Check if already applied
        $checkStmt = $pdo->prepare("SELECT id FROM applications WHERE job_id = :job_id AND applicant_id = :applicant_id");
        $checkStmt->execute([':job_id' => $jobId, ':applicant_id' => $_SESSION['user_id']]);
        
        if ($checkStmt->fetch()) {
            $error = 'Bu ilana daha önce başvuru yaptınız.';
        } else {
            // Insert application
            $insertStmt = $pdo->prepare("INSERT INTO applications (job_id, applicant_id, status, ai_score, created_at) 
                                         VALUES (:job_id, :applicant_id, 'pending', :score, NOW())");
            $insertStmt->execute([
                ':job_id' => $jobId,
                ':applicant_id' => $_SESSION['user_id'],
                ':score' => rand(70, 95) // Demo AI score
            ]);
            
            $success = true;
        }
    }
}

// Get similar jobs
$similarStmt = $pdo->prepare("SELECT j.*, u.company_name,
        (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
        FROM jobs j
        LEFT JOIN users u ON j.employer_id = u.id
        WHERE j.id != :id AND j.status = 'published'
        ORDER BY RAND()
        LIMIT 3");
$similarStmt->execute([':id' => $jobId]);
$similarJobs = $similarStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($job['title']) ?> - İş İlanı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .job-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .company-info {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #667eea;
        }
        
        .company-details h1 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .company-name {
            color: #667eea;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .job-meta {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            padding: 20px;
            background: #f7fafc;
            border-radius: 10px;
            font-size: 14px;
            color: #4a5568;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .content-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .content-card h2 {
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .content-card p, .content-card ul {
            color: #4a5568;
            line-height: 1.8;
        }
        
        .content-card ul {
            padding-left: 20px;
        }
        
        .content-card ul li {
            margin-bottom: 8px;
        }
        
        .sidebar {
            align-self: flex-start;
        }
        
        .apply-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 20px;
        }
        
        .btn-apply {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-apply:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .similar-jobs {
            margin-top: 20px;
        }
        
        .similar-job-card {
            padding: 15px;
            background: #f7fafc;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .similar-job-card:hover {
            background: #e2e8f0;
        }
        
        .similar-job-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .similar-job-company {
            font-size: 13px;
            color: #718096;
        }
        
        @media (max-width: 968px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .apply-card {
                position: static;
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
                <a href="jobs.php"><i class="fas fa-arrow-left"></i> İlanlara Dön</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_type'] === 'employer'): ?>
                        <a href="dashboard-employer.php">Dashboard</a>
                    <?php else: ?>
                        <a href="dashboard-applicant.php">Dashboard</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php">Giriş Yap</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="main-content">
            <!-- Job Header -->
            <div class="job-header">
                <div class="company-info">
                    <div class="company-logo">
                        <?php if ($job['company_logo']): ?>
                            <img src="<?= htmlspecialchars($job['company_logo']) ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        <?php else: ?>
                            <i class="fas fa-building"></i>
                        <?php endif; ?>
                    </div>
                    <div class="company-details">
                        <h1><?= htmlspecialchars($job['title']) ?></h1>
                        <div class="company-name">
                            <i class="fas fa-building"></i>
                            <?= htmlspecialchars($job['company_name'] ?? 'Şirket Adı') ?>
                        </div>
                    </div>
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
                        <i class="fas fa-chart-line"></i>
                        <?= htmlspecialchars($job['experience_level']) ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <?= $job['application_count'] ?> Başvuru
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <?= number_format($job['views']) ?> Görüntülenme
                    </div>
                    <?php if ($job['salary_min'] && $job['salary_max']): ?>
                    <div class="meta-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <?= number_format($job['salary_min']) ?> - <?= number_format($job['salary_max']) ?> TL
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Job Description -->
            <div class="content-card">
                <h2><i class="fas fa-align-left"></i> İş Tanımı</h2>
                <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
            </div>

            <!-- Requirements -->
            <?php if ($job['requirements']): ?>
            <div class="content-card">
                <h2><i class="fas fa-list-check"></i> Aranan Nitelikler</h2>
                <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Benefits -->
            <?php if ($job['benefits']): ?>
            <div class="content-card">
                <h2><i class="fas fa-gift"></i> Yan Haklar</h2>
                <p><?= nl2br(htmlspecialchars($job['benefits'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Company Info -->
            <?php if ($job['company_description']): ?>
            <div class="content-card">
                <h2><i class="fas fa-building"></i> Şirket Hakkında</h2>
                <p><?= nl2br(htmlspecialchars($job['company_description'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="apply-card">
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Başvurunuz başarıyla alındı!
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'applicant'): ?>
                    <?php
                    // Check if already applied
                    $checkStmt = $pdo->prepare("SELECT id FROM applications WHERE job_id = :job_id AND applicant_id = :applicant_id");
                    $checkStmt->execute([':job_id' => $jobId, ':applicant_id' => $_SESSION['user_id']]);
                    $alreadyApplied = $checkStmt->fetch();
                    ?>
                    
                    <form method="POST">
                        <button type="submit" name="submit_application" class="btn-apply" <?= $alreadyApplied ? 'disabled' : '' ?>>
                            <i class="fas fa-paper-plane"></i>
                            <?= $alreadyApplied ? 'Zaten Başvurdunuz' : 'Hemen Başvur' ?>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="login.php?redirect=job-detail.php?id=<?= $jobId ?>" class="btn-apply" style="text-decoration: none;">
                        <i class="fas fa-sign-in-alt"></i>
                        Başvurmak İçin Giriş Yapın
                    </a>
                <?php endif; ?>

                <div style="margin-top: 20px; text-align: center; color: #718096; font-size: 13px;">
                    <i class="fas fa-info-circle"></i>
                    İlan Tarihi: <?= date('d.m.Y', strtotime($job['created_at'])) ?>
                </div>
            </div>

            <!-- Similar Jobs -->
            <?php if (count($similarJobs) > 0): ?>
            <div class="content-card similar-jobs">
                <h2><i class="fas fa-lightbulb"></i> Benzer İlanlar</h2>
                <?php foreach ($similarJobs as $similar): ?>
                    <div class="similar-job-card" onclick="location.href='job-detail.php?id=<?= $similar['id'] ?>'">
                        <div class="similar-job-title"><?= htmlspecialchars($similar['title']) ?></div>
                        <div class="similar-job-company">
                            <?= htmlspecialchars($similar['company_name']) ?> • 
                            <?= htmlspecialchars($similar['location']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
