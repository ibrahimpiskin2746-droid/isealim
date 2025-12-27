<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Şirket Profilim' ?> - İş Bulma Platformu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .profile-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .back-btn {
            position: absolute;
            left: 2rem;
            top: 2rem;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .profile-body {
            padding: 2rem;
        }

        .company-logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .company-logo-preview {
            width: 180px;
            height: 180px;
            border-radius: 20px;
            object-fit: cover;
            border: 5px solid #f093fb;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-group label .required {
            color: #f5576c;
        }

        .form-group label .optional {
            color: #888;
            font-weight: 400;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #f093fb;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
        }

        .error {
            color: #f5576c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .submit-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
        }

        .file-upload-label {
            display: inline-block;
            background: #f0f0f0;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .file-upload-label:hover {
            background: #e0e0e0;
        }

        .file-upload-input {
            display: none;
        }

        .file-name {
            display: inline-block;
            margin-left: 1rem;
            color: #666;
        }

        .section-title {
            font-size: 1.4rem;
            color: #f5576c;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #f093fb;
            margin-bottom: 1.5rem;
        }

        .info-box h3 {
            color: #f5576c;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .back-btn {
                position: static;
                display: inline-block;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <a href="<?= url('employer/dashboard') ?>" class="back-btn">← Panel</a>
            <h1>🏢 Şirket Profilimi Düzenle</h1>
            <p>Şirket bilgilerinizi güncelleyin</p>
        </div>

        <div class="profile-body">
            <?php if (hasFlash('success')): ?>
                <div class="success-message">✓ <?= flash('success') ?></div>
            <?php endif; ?>

            <?php if (hasFlash('error')): ?>
                <div class="error-message">✗ <?= flash('error') ?></div>
            <?php endif; ?>

            <div class="info-box">
                <h3>Profil Tamamlığı</h3>
                <p>Eksiksiz bir profil, iş arayanların şirketinize daha fazla ilgi göstermesini sağlar.</p>
            </div>

            <form method="POST" action="<?= url('employer/updateProfile') ?>" enctype="multipart/form-data">
                
                <!-- Şirket Logosu -->
                <div class="company-logo-section">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= url($user['profile_image']) ?>" alt="Logo" class="company-logo-preview" id="logoPreview">
                    <?php else: ?>
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Crect fill='%23f093fb' width='180' height='180'/%3E%3Ctext fill='white' font-size='60' font-family='Arial' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3E🏢%3C/text%3E%3C/svg%3E" alt="Logo" class="company-logo-preview" id="logoPreview">
                    <?php endif; ?>
                    <div>
                        <label for="company_logo" class="file-upload-label">
                            📸 Şirket Logosu Yükle
                        </label>
                        <input type="file" name="company_logo" id="company_logo" class="file-upload-input" accept="image/*" onchange="previewImage(this)">
                        <span class="file-name" id="logoFileName"></span>
                    </div>
                    <?php if (isset($errors['company_logo'])): ?>
                        <div class="error"><?= $errors['company_logo'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Şirket Bilgileri -->
                <h2 class="section-title">🏢 Şirket Bilgileri</h2>

                <div class="form-group">
                    <label for="company_name">Şirket Adı <span class="required">*</span></label>
                    <input type="text" id="company_name" name="company_name" value="<?= htmlspecialchars($old['company_name'] ?? $user['company_name'] ?? '') ?>" required>
                    <?php if (isset($errors['company_name'])): ?>
                        <div class="error"><?= $errors['company_name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="company_description">Şirket Açıklaması <span class="optional">(opsiyonel)</span></label>
                    <textarea id="company_description" name="company_description" placeholder="Şirketiniz hakkında bilgi verin..."><?= htmlspecialchars($old['company_description'] ?? $user['company_description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="company_industry">Sektör <span class="optional">(opsiyonel)</span></label>
                        <select id="company_industry" name="company_industry">
                            <option value="">Seçiniz</option>
                            <option value="Teknoloji" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Teknoloji') ? 'selected' : '' ?>>Teknoloji</option>
                            <option value="Finans" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Finans') ? 'selected' : '' ?>>Finans</option>
                            <option value="Eğitim" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Eğitim') ? 'selected' : '' ?>>Eğitim</option>
                            <option value="Sağlık" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Sağlık') ? 'selected' : '' ?>>Sağlık</option>
                            <option value="E-Ticaret" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'E-Ticaret') ? 'selected' : '' ?>>E-Ticaret</option>
                            <option value="Üretim" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Üretim') ? 'selected' : '' ?>>Üretim</option>
                            <option value="Perakende" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Perakende') ? 'selected' : '' ?>>Perakende</option>
                            <option value="Medya" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Medya') ? 'selected' : '' ?>>Medya</option>
                            <option value="Turizm" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Turizm') ? 'selected' : '' ?>>Turizm</option>
                            <option value="Diğer" <?= (($old['company_industry'] ?? $user['company_industry'] ?? '') == 'Diğer') ? 'selected' : '' ?>>Diğer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="company_size">Şirket Büyüklüğü <span class="optional">(opsiyonel)</span></label>
                        <select id="company_size" name="company_size">
                            <option value="">Seçiniz</option>
                            <option value="1-10" <?= (($old['company_size'] ?? $user['company_size'] ?? '') == '1-10') ? 'selected' : '' ?>>1-10 çalışan</option>
                            <option value="11-50" <?= (($old['company_size'] ?? $user['company_size'] ?? '') == '11-50') ? 'selected' : '' ?>>11-50 çalışan</option>
                            <option value="51-200" <?= (($old['company_size'] ?? $user['company_size'] ?? '') == '51-200') ? 'selected' : '' ?>>51-200 çalışan</option>
                            <option value="201-500" <?= (($old['company_size'] ?? $user['company_size'] ?? '') == '201-500') ? 'selected' : '' ?>>201-500 çalışan</option>
                            <option value="501-1000" <?= (($old['company_size'] ?? $user['company_size'] ?? '') == '501-1000') ? 'selected' : '' ?>>501-1000 çalışan</option>
                            <option value="1000+" <?= (($old['company_size'] ?? $user['company_size'] ?? '') == '1000+') ? 'selected' : '' ?>>1000+ çalışan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="company_founded_year">Kuruluş Yılı <span class="optional">(opsiyonel)</span></label>
                        <input type="number" id="company_founded_year" name="company_founded_year" value="<?= htmlspecialchars($old['company_founded_year'] ?? $user['company_founded_year'] ?? '') ?>" min="1900" max="<?= date('Y') ?>" placeholder="2020">
                    </div>

                    <div class="form-group">
                        <label for="company_website">Şirket Web Sitesi <span class="optional">(opsiyonel)</span></label>
                        <input type="url" id="company_website" name="company_website" value="<?= htmlspecialchars($old['company_website'] ?? $user['company_website'] ?? '') ?>" placeholder="https://sirketiniz.com">
                        <?php if (isset($errors['company_website'])): ?>
                            <div class="error"><?= $errors['company_website'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="company_address">Şirket Adresi <span class="optional">(opsiyonel)</span></label>
                    <textarea id="company_address" name="company_address" placeholder="Tam adres bilgisi..."><?= htmlspecialchars($old['company_address'] ?? $user['company_address'] ?? '') ?></textarea>
                </div>

                <!-- Yetkili Kişi Bilgileri -->
                <h2 class="section-title">👤 Yetkili Kişi Bilgileri</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Ad Soyad <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? $user['full_name'] ?? '') ?>" required>
                        <?php if (isset($errors['full_name'])): ?>
                            <div class="error"><?= $errors['full_name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefon <span class="optional">(opsiyonel)</span></label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>" placeholder="+90 555 123 45 67">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">E-posta <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Sosyal Medya -->
                <h2 class="section-title">🌐 Sosyal Medya</h2>

                <div class="form-group">
                    <label for="linkedin_url">LinkedIn Sayfası <span class="optional">(opsiyonel)</span></label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($old['linkedin_url'] ?? $user['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/company/sirketiniz">
                    <?php if (isset($errors['linkedin_url'])): ?>
                        <div class="error"><?= $errors['linkedin_url'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="twitter_url">Twitter/X <span class="optional">(opsiyonel)</span></label>
                        <input type="url" id="twitter_url" name="twitter_url" value="<?= htmlspecialchars($old['twitter_url'] ?? $user['twitter_url'] ?? '') ?>" placeholder="https://twitter.com/sirketiniz">
                    </div>

                    <div class="form-group">
                        <label for="facebook_url">Facebook <span class="optional">(opsiyonel)</span></label>
                        <input type="url" id="facebook_url" name="facebook_url" value="<?= htmlspecialchars($old['facebook_url'] ?? $user['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/sirketiniz">
                    </div>
                </div>

                <button type="submit" class="submit-btn">💾 Şirket Profilini Kaydet</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('logoPreview');
            const fileName = document.getElementById('logoFileName');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                
                reader.readAsDataURL(input.files[0]);
                fileName.textContent = input.files[0].name;
            }
        }
    </script>
</body>
</html>
