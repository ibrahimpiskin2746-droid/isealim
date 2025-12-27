<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box auth-box-large">
        <div class="auth-header">
            <h1>Kayıt Ol</h1>
            <p>Ücretsiz hesap oluşturun</p>
        </div>
        
        <form method="POST" action="<?= url('auth/register') ?>" class="auth-form">
            <?= csrfField() ?>
            
            <!-- Kullanıcı Tipi Seçimi -->
            <div class="form-group">
                <label>Hesap Tipi</label>
                <div class="user-type-selector">
                    <label class="user-type-option">
                        <input type="radio" name="user_type" value="applicant" 
                               <?= ($data['user_type'] ?? 'applicant') === 'applicant' ? 'checked' : '' ?> required>
                        <div class="user-type-card">
                            <i class="fas fa-user"></i>
                            <h3>İş Arıyorum</h3>
                            <p>İş ilanlarını görüntüle ve başvur</p>
                        </div>
                    </label>
                    
                    <label class="user-type-option">
                        <input type="radio" name="user_type" value="employer"
                               <?= ($data['user_type'] ?? '') === 'employer' ? 'checked' : '' ?>>
                        <div class="user-type-card">
                            <i class="fas fa-briefcase"></i>
                            <h3>Eleman Arıyorum</h3>
                            <p>İş ilanı ver ve başvuruları değerlendir</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Genel Bilgiler -->
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Ad Soyad *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           value="<?= escape($data['full_name'] ?? '') ?>" required>
                    <?php if (isset($errors['full_name'])): ?>
                        <span class="form-error"><?= $errors['full_name'] ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           value="<?= escape($data['phone'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">E-posta Adresi *</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= escape($data['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="form-error"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Şirket Bilgileri (İşveren için) -->
            <div id="employer-fields" style="display: none;">
                <div class="form-group">
                    <label for="company_name">Şirket Adı *</label>
                    <input type="text" id="company_name" name="company_name" class="form-control" 
                           value="<?= escape($data['company_name'] ?? '') ?>">
                    <?php if (isset($errors['company_name'])): ?>
                        <span class="form-error"><?= $errors['company_name'] ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="company_description">Şirket Açıklaması</label>
                    <textarea id="company_description" name="company_description" class="form-control" rows="3"><?= escape($data['company_description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Şifre -->
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Şifre *</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class="form-error"><?= $errors['password'] ?></span>
                    <?php endif; ?>
                    <small>En az <?= PASSWORD_MIN_LENGTH ?> karakter</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Şifre Tekrar *</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                    <?php if (isset($errors['password_confirm'])): ?>
                        <span class="form-error"><?= $errors['password_confirm'] ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-user-plus"></i> Kayıt Ol
            </button>
        </form>
        
        <div class="auth-footer">
            Zaten hesabınız var mı?
            <a href="<?= url('auth/login') ?>">Giriş Yapın</a>
        </div>
    </div>
</div>

<script>
    // Kullanıcı tipi değişikliğinde şirket alanlarını göster/gizle
    document.querySelectorAll('input[name="user_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const employerFields = document.getElementById('employer-fields');
            const companyNameField = document.getElementById('company_name');
            
            if (this.value === 'employer') {
                employerFields.style.display = 'block';
                companyNameField.required = true;
            } else {
                employerFields.style.display = 'none';
                companyNameField.required = false;
            }
        });
    });
    
    // Sayfa yüklendiğinde seçili olan tipte göster
    const selectedType = document.querySelector('input[name="user_type"]:checked');
    if (selectedType && selectedType.value === 'employer') {
        document.getElementById('employer-fields').style.display = 'block';
        document.getElementById('company_name').required = true;
    }
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
