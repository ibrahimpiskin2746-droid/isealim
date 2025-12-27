<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Profilim' ?> - İş Bulma Platformu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .profile-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            padding: 2.5rem;
        }

        .profile-image-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
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
            color: #e74c3c;
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
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
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
            color: #e74c3c;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
            margin: 2.5rem 0 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .current-cv {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .current-cv a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .info-box {
            background: linear-gradient(135deg, #e0f7fa 0%, #e1bee7 100%);
            padding: 1.25rem;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            margin-bottom: 2rem;
        }

        .info-box h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .info-box p {
            color: #555;
            line-height: 1.6;
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

            .profile-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <a href="<?= url('applicant/dashboard') ?>" class="back-btn">← Panel</a>
            <h1>👤 Profilimi Düzenle</h1>
            <p>Bilgilerinizi güncelleyin ve işverenler için daha çekici olun</p>
        </div>

        <div class="profile-body">
            <?php if (hasFlash('success')): ?>
                <div class="success-message">✓ <?= flash('success') ?></div>
            <?php endif; ?>

            <?php if (hasFlash('error')): ?>
                <div class="error-message">✗ <?= flash('error') ?></div>
            <?php endif; ?>

            <div class="info-box">
                <h3>💡 Profil Tamamlama İpucu</h3>
                <p>Eksiksiz ve detaylı bir profil, iş başvurularınızın başarı şansını %70 artırır. Tüm alanları doldurmaya özen gösterin!</p>
            </div>

            <form method="POST" action="<?= url('applicant/updateProfile') ?>" enctype="multipart/form-data">
                
                <!-- Profil Fotoğrafı -->
                <div class="profile-image-section">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= url($user['profile_image']) ?>" alt="Profil" class="profile-image-preview" id="profilePreview">
                    <?php else: ?>
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='150'%3E%3Ccircle fill='%23667eea' cx='75' cy='75' r='75'/%3E%3Ctext fill='white' font-size='50' font-family='Arial' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3E👤%3C/text%3E%3C/svg%3E" alt="Profil" class="profile-image-preview" id="profilePreview">
                    <?php endif; ?>
                    <div>
                        <label for="profile_image" class="file-upload-label">
                            📸 Fotoğraf Değiştir
                        </label>
                        <input type="file" name="profile_image" id="profile_image" class="file-upload-input" accept="image/*" onchange="previewImage(this)">
                        <span class="file-name" id="imageFileName"></span>
                    </div>
                    <?php if (isset($errors['profile_image'])): ?>
                        <div class="error"><?= $errors['profile_image'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Kişisel Bilgiler -->
                <h2 class="section-title">📋 Kişisel Bilgiler</h2>

                <div class="form-group">
                    <label for="full_name">Ad Soyad <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? $user['full_name'] ?? '') ?>" required>
                    <?php if (isset($errors['full_name'])): ?>
                        <div class="error"><?= $errors['full_name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label for="email">E-posta <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefon <span class="optional">(opsiyonel)</span></label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>" placeholder="+90 555 123 45 67">
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Doğum Tarihi <span class="optional">(opsiyonel)</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($old['date_of_birth'] ?? $user['date_of_birth'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label for="gender">Cinsiyet <span class="optional">(opsiyonel)</span></label>
                        <select id="gender" name="gender">
                            <option value="">Seçiniz</option>
                            <option value="male" <?= (($old['gender'] ?? $user['gender'] ?? '') == 'male') ? 'selected' : '' ?>>Erkek</option>
                            <option value="female" <?= (($old['gender'] ?? $user['gender'] ?? '') == 'female') ? 'selected' : '' ?>>Kadın</option>
                            <option value="other" <?= (($old['gender'] ?? $user['gender'] ?? '') == 'other') ? 'selected' : '' ?>>Diğer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Uyruk <span class="optional">(opsiyonel)</span></label>
                        <input type="text" id="nationality" name="nationality" value="<?= htmlspecialchars($old['nationality'] ?? $user['nationality'] ?? '') ?>" placeholder="Türkiye">
                    </div>

                    <div class="form-group">
                        <label for="location">Konum <span class="optional">(opsiyonel)</span></label>
                        <input type="text" id="location" name="location" value="<?= htmlspecialchars($old['location'] ?? $user['location'] ?? '') ?>" placeholder="İstanbul, Türkiye">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">Hakkımda <span class="optional">(opsiyonel)</span></label>
                    <textarea id="bio" name="bio" placeholder="Kendinizi kısaca tanıtın, kariyer hedeflerinizi paylaşın..."><?= htmlspecialchars($old['bio'] ?? $user['bio'] ?? '') ?></textarea>
                </div>

                <!-- Profesyonel Bilgiler -->
                <h2 class="section-title">💼 Profesyonel Bilgiler</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="current_position">Mevcut Pozisyon <span class="optional">(opsiyonel)</span></label>
                        <input type="text" id="current_position" name="current_position" value="<?= htmlspecialchars($old['current_position'] ?? $user['current_position'] ?? '') ?>" placeholder="Senior Developer">
                    </div>

                    <div class="form-group">
                        <label for="experience_years">Deneyim (Yıl)</label>
                        <input type="number" id="experience_years" name="experience_years" value="<?= htmlspecialchars($old['experience_years'] ?? $user['experience_years'] ?? 0) ?>" min="0" max="50">
                    </div>
                </div>

                <div class="form-group">
                    <label for="skills">Beceriler <span class="optional">(virgülle ayırın)</span></label>
                    <input type="text" id="skills" name="skills" value="<?= htmlspecialchars($old['skills'] ?? $user['skills'] ?? '') ?>" placeholder="PHP, JavaScript, React, SQL, Docker">
                </div>

                <div class="form-group">
                    <label for="languages">Yabancı Diller <span class="optional">(virgülle ayırın)</span></label>
                    <input type="text" id="languages" name="languages" value="<?= htmlspecialchars($old['languages'] ?? $user['languages'] ?? '') ?>" placeholder="İngilizce (Advanced), Almanca (Intermediate)">
                </div>

                <!-- Eğitim ve Sertifikalar -->
                <h2 class="section-title">🎓 Eğitim ve Sertifikalar</h2>

                <div class="form-group">
                    <label for="education">Eğitim <span class="optional">(opsiyonel)</span></label>
                    <textarea id="education" name="education" placeholder="Üniversite, bölüm ve mezuniyet yılı detayları..."><?= htmlspecialchars($old['education'] ?? $user['education'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="certifications">Sertifikalar <span class="optional">(opsiyonel)</span></label>
                    <textarea id="certifications" name="certifications" placeholder="AWS Certified, Google Analytics, vb. Her satıra bir sertifika..."><?= htmlspecialchars($old['certifications'] ?? $user['certifications'] ?? '') ?></textarea>
                </div>

                <!-- İş Deneyimi ve Projeler -->
                <h2 class="section-title">🏆 İş Deneyimi ve Projeler</h2>

                <div class="form-group">
                    <label for="work_experience">İş Deneyimi <span class="optional">(opsiyonel)</span></label>
                    <textarea id="work_experience" name="work_experience" placeholder="Şirket adı, pozisyon, tarih aralığı ve görevleriniz..."><?= htmlspecialchars($old['work_experience'] ?? $user['work_experience'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="projects">Projeler <span class="optional">(opsiyonel)</span></label>
                    <textarea id="projects" name="projects" placeholder="Öne çıkan projeleriniz, açık kaynak katkılarınız..."><?= htmlspecialchars($old['projects'] ?? $user['projects'] ?? '') ?></textarea>
                </div>

                <!-- Tercihlerin -->
                <h2 class="section-title">⚙️ İş Tercihleri</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="expected_salary">Beklenen Maaş <span class="optional">(opsiyonel)</span></label>
                        <input type="text" id="expected_salary" name="expected_salary" value="<?= htmlspecialchars($old['expected_salary'] ?? $user['expected_salary'] ?? '') ?>" placeholder="15.000 - 20.000 TL">
                    </div>

                    <div class="form-group">
                        <label for="work_preference">Çalışma Tercihi <span class="optional">(opsiyonel)</span></label>
                        <select id="work_preference" name="work_preference">
                            <option value="">Seçiniz</option>
                            <option value="office" <?= (($old['work_preference'] ?? $user['work_preference'] ?? '') == 'office') ? 'selected' : '' ?>>Ofis</option>
                            <option value="remote" <?= (($old['work_preference'] ?? $user['work_preference'] ?? '') == 'remote') ? 'selected' : '' ?>>Uzaktan</option>
                            <option value="hybrid" <?= (($old['work_preference'] ?? $user['work_preference'] ?? '') == 'hybrid') ? 'selected' : '' ?>>Hibrit</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="availability">Müsaitlik Durumu <span class="optional">(opsiyonel)</span></label>
                    <select id="availability" name="availability">
                        <option value="">Seçiniz</option>
                        <option value="immediate" <?= (($old['availability'] ?? $user['availability'] ?? '') == 'immediate') ? 'selected' : '' ?>>Hemen Başlayabilirim</option>
                        <option value="2weeks" <?= (($old['availability'] ?? $user['availability'] ?? '') == '2weeks') ? 'selected' : '' ?>>2 Hafta İçinde</option>
                        <option value="1month" <?= (($old['availability'] ?? $user['availability'] ?? '') == '1month') ? 'selected' : '' ?>>1 Ay İçinde</option>
                        <option value="negotiable" <?= (($old['availability'] ?? $user['availability'] ?? '') == 'negotiable') ? 'selected' : '' ?>>Görüşülür</option>
                    </select>
                </div>

                <!-- Sosyal Medya & Web -->
                <h2 class="section-title">🌐 Sosyal Medya & Web</h2>

                <div class="form-group">
                    <label for="linkedin_url">LinkedIn Profili <span class="optional">(opsiyonel)</span></label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($old['linkedin_url'] ?? $user['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/kullanici-adi">
                    <?php if (isset($errors['linkedin_url'])): ?>
                        <div class="error"><?= $errors['linkedin_url'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="github_url">GitHub Profili <span class="optional">(opsiyonel)</span></label>
                        <input type="url" id="github_url" name="github_url" value="<?= htmlspecialchars($old['github_url'] ?? $user['github_url'] ?? '') ?>" placeholder="https://github.com/kullanici-adi">
                        <?php if (isset($errors['github_url'])): ?>
                            <div class="error"><?= $errors['github_url'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="portfolio_url">Portfolio/Website <span class="optional">(opsiyonel)</span></label>
                        <input type="url" id="portfolio_url" name="portfolio_url" value="<?= htmlspecialchars($old['portfolio_url'] ?? $user['portfolio_url'] ?? '') ?>" placeholder="https://siteniz.com">
                        <?php if (isset($errors['portfolio_url'])): ?>
                            <div class="error"><?= $errors['portfolio_url'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CV -->
                <h2 class="section-title">📄 CV (Özgeçmiş)</h2>

                <?php if (!empty($user['cv_path'])): ?>
                    <div class="current-cv">
                        📄 Mevcut CV: <a href="<?= url($user['cv_path']) ?>" target="_blank">Görüntüle</a>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="cv_file" class="file-upload-label">
                        📎 Yeni CV Yükle (PDF, DOC, DOCX - Max 5MB)
                    </label>
                    <input type="file" name="cv_file" id="cv_file" class="file-upload-input" accept=".pdf,.doc,.docx" onchange="showFileName(this, 'cvFileName')">
                    <span class="file-name" id="cvFileName"></span>
                    <?php if (isset($errors['cv_file'])): ?>
                        <div class="error"><?= $errors['cv_file'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">💾 Profili Kaydet</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('profilePreview');
            const fileName = document.getElementById('imageFileName');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                
                reader.readAsDataURL(input.files[0]);
                fileName.textContent = input.files[0].name;
            }
        }

        function showFileName(input, elementId) {
            const fileNameSpan = document.getElementById(elementId);
            if (input.files && input.files[0]) {
                fileNameSpan.textContent = input.files[0].name;
            }
        }
    </script>
</body>
</html>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .profile-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .profile-image-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
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
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .error {
            color: #e74c3c;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            font-size: 1.3rem;
            color: #667eea;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .current-cv {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .current-cv a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .form-row {
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
            <a href="<?= url('applicant/dashboard') ?>" class="back-btn">← Geri</a>
            <h1>Profilimi Düzenle</h1>
            <p>Bilgilerinizi güncelleyin</p>
        </div>

        <div class="profile-body">
            <?php if (hasFlash('success')): ?>
                <div class="success-message"><?= flash('success') ?></div>
            <?php endif; ?>

            <?php if (hasFlash('error')): ?>
                <div class="error-message"><?= flash('error') ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('applicant/updateProfile') ?>" enctype="multipart/form-data">
                
                <!-- Profil Fotoğrafı -->
                <div class="profile-image-section">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= url($user['profile_image']) ?>" alt="Profil" class="profile-image-preview" id="profilePreview">
                    <?php else: ?>
                        <img src="<?= url('public/images/default-avatar.png') ?>" alt="Profil" class="profile-image-preview" id="profilePreview">
                    <?php endif; ?>
                    <div>
                        <label for="profile_image" class="file-upload-label">
                            📸 Fotoğraf Değiştir
                        </label>
                        <input type="file" name="profile_image" id="profile_image" class="file-upload-input" accept="image/*" onchange="previewImage(this)">
                        <span class="file-name" id="imageFileName"></span>
                    </div>
                    <?php if (isset($errors['profile_image'])): ?>
                        <div class="error"><?= $errors['profile_image'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Temel Bilgiler -->
                <h2 class="section-title">Temel Bilgiler</h2>

                <div class="form-group">
                    <label for="full_name">Ad Soyad *</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? $user['full_name'] ?? '') ?>" required>
                    <?php if (isset($errors['full_name'])): ?>
                        <div class="error"><?= $errors['full_name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Telefon <span class="optional">(opsiyonel)</span></label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>" placeholder="+90 555 123 45 67">
                    </div>

                    <div class="form-group">
                        <label for="location">Konum <span class="optional">(opsiyonel)</span></label>
                        <input type="text" id="location" name="location" value="<?= htmlspecialchars($old['location'] ?? $user['location'] ?? '') ?>" placeholder="İstanbul, Türkiye">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">Hakkımda <span class="optional">(opsiyonel)</span></label>
                    <textarea id="bio" name="bio" placeholder="Kendinizi tanıtın..."><?= htmlspecialchars($old['bio'] ?? $user['bio'] ?? '') ?></textarea>
                </div>

                <!-- Profesyonel Bilgiler -->
                <h2 class="section-title">Profesyonel Bilgiler</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="current_position">Mevcut Pozisyon <span class="optional">(opsiyonel)</span></label>
                        <input type="text" id="current_position" name="current_position" value="<?= htmlspecialchars($old['current_position'] ?? $user['current_position'] ?? '') ?>" placeholder="Senior Developer">
                    </div>

                    <div class="form-group">
                        <label for="experience_years">Deneyim (Yıl)</label>
                        <input type="number" id="experience_years" name="experience_years" value="<?= htmlspecialchars($old['experience_years'] ?? $user['experience_years'] ?? 0) ?>" min="0" max="50">
                    </div>
                </div>

                <div class="form-group">
                    <label for="skills">Beceriler <span class="optional">(virgülle ayırın)</span></label>
                    <input type="text" id="skills" name="skills" value="<?= htmlspecialchars($old['skills'] ?? $user['skills'] ?? '') ?>" placeholder="PHP, JavaScript, React, SQL">
                </div>

                <div class="form-group">
                    <label for="education">Eğitim <span class="optional">(opsiyonel)</span></label>
                    <textarea id="education" name="education" placeholder="Üniversite, bölüm ve mezuniyet yılı..."><?= htmlspecialchars($old['education'] ?? $user['education'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="expected_salary">Beklenen Maaş <span class="optional">(opsiyonel)</span></label>
                    <input type="text" id="expected_salary" name="expected_salary" value="<?= htmlspecialchars($old['expected_salary'] ?? $user['expected_salary'] ?? '') ?>" placeholder="15.000 - 20.000 TL">
                </div>

                <!-- Sosyal Medya & Web -->
                <h2 class="section-title">Sosyal Medya & Web</h2>

                <div class="form-group">
                    <label for="linkedin_url">LinkedIn Profili <span class="optional">(opsiyonel)</span></label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($old['linkedin_url'] ?? $user['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/kullanici-adi">
                    <?php if (isset($errors['linkedin_url'])): ?>
                        <div class="error"><?= $errors['linkedin_url'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="github_url">GitHub Profili <span class="optional">(opsiyonel)</span></label>
                    <input type="url" id="github_url" name="github_url" value="<?= htmlspecialchars($old['github_url'] ?? $user['github_url'] ?? '') ?>" placeholder="https://github.com/kullanici-adi">
                    <?php if (isset($errors['github_url'])): ?>
                        <div class="error"><?= $errors['github_url'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="portfolio_url">Portfolio/Website <span class="optional">(opsiyonel)</span></label>
                    <input type="url" id="portfolio_url" name="portfolio_url" value="<?= htmlspecialchars($old['portfolio_url'] ?? $user['portfolio_url'] ?? '') ?>" placeholder="https://siteniz.com">
                    <?php if (isset($errors['portfolio_url'])): ?>
                        <div class="error"><?= $errors['portfolio_url'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- CV -->
                <h2 class="section-title">CV (Özgeçmiş)</h2>

                <?php if (!empty($user['cv_path'])): ?>
                    <div class="current-cv">
                        📄 Mevcut CV: <a href="<?= url($user['cv_path']) ?>" target="_blank">Görüntüle</a>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="cv_file" class="file-upload-label">
                        📎 Yeni CV Yükle (PDF, DOC, DOCX)
                    </label>
                    <input type="file" name="cv_file" id="cv_file" class="file-upload-input" accept=".pdf,.doc,.docx" onchange="showFileName(this, 'cvFileName')">
                    <span class="file-name" id="cvFileName"></span>
                    <?php if (isset($errors['cv_file'])): ?>
                        <div class="error"><?= $errors['cv_file'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">💾 Profili Kaydet</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('profilePreview');
            const fileName = document.getElementById('imageFileName');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                
                reader.readAsDataURL(input.files[0]);
                fileName.textContent = input.files[0].name;
            }
        }

        function showFileName(input, elementId) {
            const fileNameSpan = document.getElementById(elementId);
            if (input.files && input.files[0]) {
                fileNameSpan.textContent = input.files[0].name;
            }
        }
    </script>
</body>
</html>
