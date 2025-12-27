<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h1>Giriş Yap</h1>
            <p>Hesabınıza giriş yapın</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= escape($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= url('auth/login') ?>" class="auth-form">
            <?= csrfField() ?>
            
            <div class="form-group">
                <label for="email">E-posta Adresi</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= escape($email ?? '') ?>" required autofocus>
                <?php if (isset($errors['email'])): ?>
                    <span class="form-error"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="form-error"><?= $errors['password'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group form-check">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Beni Hatırla</label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt"></i> Giriş Yap
            </button>
        </form>
        
        <div class="auth-footer">
            <a href="<?= url('auth/forgot-password') ?>">Şifremi Unuttum</a>
            <span>•</span>
            <a href="<?= url('auth/register') ?>">Hesap Oluştur</a>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
