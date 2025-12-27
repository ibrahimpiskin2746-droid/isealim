<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AI Destekli İş Başvuru ve Değerlendirme Platformu">
    <title><?= pageTitle($title ?? '') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= IMG_URL ?>/favicon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/dashboard.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/modern-dashboard.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">
                    <a href="<?= url() ?>">
                        <i class="fas fa-briefcase"></i>
                        <span>İş Platformu</span>
                    </a>
                </div>
                
                <div class="navbar-menu">
                    <?php if (isLoggedIn()): ?>
                        <div class="navbar-user">
                            <a href="<?= url('notifications') ?>" class="navbar-link">
                                <i class="fas fa-bell"></i>
                                <?php 
                                $unreadCount = (new Notification())->getUnreadCount(authId());
                                if ($unreadCount > 0): 
                                ?>
                                    <span class="badge"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </a>
                            
                            <div class="navbar-dropdown">
                                <button class="navbar-toggle">
                                    <i class="fas fa-user-circle"></i>
                                    <span><?= escape(auth()['full_name']) ?></span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                
                                <div class="navbar-dropdown-menu">
                                    <?php if (isEmployer()): ?>
                                        <a href="<?= url('employer/dashboard') ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
                                        <a href="<?= url('employer/jobs') ?>"><i class="fas fa-briefcase"></i> İş İlanlarım</a>
                                        <a href="<?= url('employer/applications') ?>"><i class="fas fa-file-alt"></i> Başvurular</a>
                                    <?php elseif (isApplicant()): ?>
                                        <a href="<?= url('applicant/dashboard') ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
                                        <a href="<?= url('applicant/browse-jobs') ?>"><i class="fas fa-search"></i> İş Ara</a>
                                        <a href="<?= url('applicant/applications') ?>"><i class="fas fa-file-alt"></i> Başvurularım</a>
                                        <a href="<?= url('applicant/profile') ?>"><i class="fas fa-user"></i> Profilim</a>
                                    <?php endif; ?>
                                    <hr>
                                    <a href="<?= url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('jobs') ?>" class="navbar-link">İş İlanları</a>
                        <a href="<?= url('about') ?>" class="navbar-link">Hakkımızda</a>
                        <a href="<?= url('contact') ?>" class="navbar-link">İletişim</a>
                        <a href="<?= url('auth/login') ?>" class="btn btn-outline">Giriş Yap</a>
                        <a href="<?= url('auth/register') ?>" class="btn btn-primary">Kayıt Ol</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if ($success = getFlash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?= escape($success) ?></span>
            <button class="alert-close">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if ($error = getFlash('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= escape($error) ?></span>
            <button class="alert-close">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
