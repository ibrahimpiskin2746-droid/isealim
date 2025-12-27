<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h2 class="mb-4">Sayfa Bulunamadı</h2>
                <p class="lead mb-4">Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?= BASE_URL ?>" class="btn btn-primary">Ana Sayfaya Dön</a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">Geri Dön</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 60px 20px;
}
.error-page h1 {
    font-size: 120px;
    line-height: 1;
    margin-bottom: 20px;
}
</style>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
