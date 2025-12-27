<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-header">
            <h4>İşveren Paneli</h4>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/employer/dashboard" class="nav-link">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/employer/jobs" class="nav-link">
                <i class="fas fa-briefcase"></i> İlanlarım
            </a>
            <a href="<?= BASE_URL ?>/employer/create-job" class="nav-link active">
                <i class="fas fa-plus-circle"></i> Yeni İlan
            </a>
            <a href="<?= BASE_URL ?>/employer/applications" class="nav-link">
                <i class="fas fa-file-alt"></i> Başvurular
            </a>
            <a href="<?= BASE_URL ?>/employer/messages" class="nav-link">
                <i class="fas fa-envelope"></i> Mesajlar
            </a>
            <a href="<?= BASE_URL ?>/employer/profile" class="nav-link">
                <i class="fas fa-user"></i> Profil
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-magic text-primary"></i> AI Başvuru Formu</h1>
                    <p class="text-muted">
                        <strong><?= htmlspecialchars($job['title']) ?></strong> pozisyonu için özel oluşturuldu
                    </p>
                </div>
                <a href="<?= BASE_URL ?>/employer/jobs" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> İlanlarıma Dön
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- AI Form Generator Card -->
                <div class="card ai-generator-card mb-4">
                    <div class="card-body">
                        <div class="ai-icon-wrapper mb-4">
                            <div class="ai-icon-bg">
                                <i class="fas fa-robot"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-center mb-3" style="font-size: 1.75rem; font-weight: 700; background: linear-gradient(135deg, #10b981, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            ✨ AI İlanınızı Analiz Etti
                        </h3>
                        <p class="text-center text-muted mb-4" style="font-size: 1.0625rem; line-height: 1.7;">
                            <strong><?= htmlspecialchars($job['title']) ?></strong> pozisyonunuz için yapay zeka, iş tanımınıza ve gereksinimlerinize göre özel başvuru formu oluşturdu. 
                            <br><strong>Doğru adayları seçmek hiç bu kadar kolay olmamıştı!</strong>
                        </p>

                        <?php if (!empty($form_fields)): ?>
                        <div class="alert alert-success" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: white;">
                            <i class="fas fa-robot" style="font-size: 1.25rem;"></i>
                            <strong>AI Form Hazır!</strong> İş ilanınıza özel <?= count($form_fields) ?> soru oluşturuldu.
                        </div>
                        <script>console.log('Form fields count:', <?= count($form_fields) ?>);</script>
                        <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Form oluşturulamadı!</strong> 
                            <p class="mb-0 small">Debug: form_fields = <?= var_export($form_fields, true) ?></p>
                            <p class="mb-0 small">Job Title: <?= htmlspecialchars($job['title'] ?? 'N/A') ?></p>
                            <p class="mb-0 small">Job Description Length: <?= strlen($job['description'] ?? '') ?></p>
                        </div>
                        <form method="POST">
                            <?= csrfField() ?>
                            <input type="hidden" name="action" value="generate">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-redo"></i> Formu Oluştur
                            </button>
                        </form>
                        <?php endif; ?>
                        <div class="text-center mt-3">
                            <a href="<?= BASE_URL ?>/employer/edit-form/<?= $job['id'] ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-edit"></i> Formu Düzenle
                            </a>
                            <form method="POST" style="display: inline-block;" class="ms-2">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="generate">
                                <button type="submit" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-redo"></i> Yeniden Oluştur
                                </button>
                            </form>
                            <a href="<?= BASE_URL ?>/employer/jobs" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-briefcase"></i> İlanlarıma Dön
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Preview -->
                <?php if (!empty($form_fields)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-eye"></i> Form Önizleme - <?= count($form_fields) ?> Soru</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-preview">
                            <?php 
                            $categories = [];
                            foreach ($form_fields as $field) {
                                $cat = $field['field_category'] ?? 'other';
                                $categories[$cat][] = $field;
                            }
                            
                            $categoryLabels = [
                                'personal' => 'Kişisel Bilgiler',
                                'contact' => 'İletişim Bilgileri',
                                'education' => 'Eğitim Bilgileri',
                                'experience' => 'İş Deneyimi',
                                'skills' => 'Yetenekler',
                                'documents' => 'Belgeler',
                                'other' => 'Diğer'
                            ];
                            
                            foreach ($categories as $category => $fields):
                            ?>
                                <div class="form-category mb-4">
                                    <h6 class="category-title">
                                        <i class="fas fa-folder-open"></i>
                                        <?= $categoryLabels[$category] ?? 'Diğer' ?>
                                    </h6>
                                    <div class="category-fields">
                                        <?php foreach ($fields as $field): ?>
                                            <div class="form-field-preview">
                                                <label class="field-label">
                                                    <?= htmlspecialchars($field['field_label']) ?>
                                                    <?php if ($field['is_required']): ?>
                                                        <span class="text-danger">*</span>
                                                    <?php endif; ?>
                                                </label>
                                                
                                                <?php if ($field['field_type'] === 'textarea'): ?>
                                                    <textarea class="form-control" placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>" disabled></textarea>
                                                    
                                                <?php elseif ($field['field_type'] === 'select'): ?>
                                                    <select class="form-control" disabled>
                                                        <option>Seçiniz...</option>
                                                        <?php 
                                                        $options = json_decode($field['field_options'], true) ?? [];
                                                        foreach ($options as $option): 
                                                        ?>
                                                            <option><?= htmlspecialchars($option) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    
                                                <?php elseif ($field['field_type'] === 'radio'): ?>
                                                    <?php 
                                                    $options = json_decode($field['field_options'], true) ?? [];
                                                    foreach ($options as $option): 
                                                    ?>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" disabled>
                                                            <label class="form-check-label"><?= htmlspecialchars($option) ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    
                                                <?php elseif ($field['field_type'] === 'checkbox'): ?>
                                                    <?php 
                                                    $options = json_decode($field['field_options'], true) ?? [];
                                                    foreach ($options as $option): 
                                                    ?>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" disabled>
                                                            <label class="form-check-label"><?= htmlspecialchars($option) ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    
                                                <?php elseif ($field['field_type'] === 'file'): ?>
                                                    <input type="file" class="form-control" disabled>
                                                    
                                                <?php else: ?>
                                                    <input 
                                                        type="<?= htmlspecialchars($field['field_type']) ?>" 
                                                        class="form-control" 
                                                        placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>"
                                                        disabled
                                                    >
                                                <?php endif; ?>
                                                
                                                <?php if ($field['ai_generated']): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-robot"></i> AI Tarafından Oluşturuldu
                                                        <?php if ($field['ai_scoring_weight'] != 1.0): ?>
                                                            | Değerlendirme Ağırlığı: <?= $field['ai_scoring_weight'] ?>
                                                        <?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: #f59e0b;"></i>
                        </div>
                        <h4>Form Alanları Yüklenemedi</h4>
                        <p class="text-muted mb-4">AI form oluşturmada bir sorun oluştu. Lütfen tekrar deneyin.</p>
                        <form method="POST" style="display: inline-block;">
                            <?= csrfField() ?>
                            <input type="hidden" name="action" value="generate">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-redo"></i> Formu Şimdi Oluştur
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="card info-card">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h5><i class="fas fa-sparkles"></i> AI Form Özellikleri</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-steps">
                            <div class="info-step">
                                <div class="step-number" style="background: linear-gradient(135deg, #10b981, #059669);">✓</div>
                                <div class="step-content">
                                    <h6>Pozisyona Özel Sorular</h6>
                                    <p>Developer için teknik sorular, Designer için portfolio, PM için metrik soruları otomatik eklendi.</p>
                                </div>
                            </div>
                            
                            <div class="info-step">
                                <div class="step-number" style="background: linear-gradient(135deg, #10b981, #059669);">✓</div>
                                <div class="step-content">
                                    <h6>Teknoloji Tespiti</h6>
                                    <p>İlan açıklamanızdaki teknolojiler (PHP, React, Python) otomatik tespit edildi.</p>
                                </div>
                            </div>
                            
                            <div class="info-step">
                                <div class="step-number" style="background: linear-gradient(135deg, #10b981, #059669);">✓</div>
                                <div class="step-content">
                                    <h6>Akıllı Ağırlıklandırma</h6>
                                    <p>Teknik sorular 2.5x, deneyim 1.8x, soft skills 1.3x ağırlıkla skorlanacak.</p>
                                </div>
                            </div>
                            
                            <div class="info-step">
                                <div class="step-number" style="background: linear-gradient(135deg, #10b981, #059669);">✓</div>
                                <div class="step-content">
                                    <h6>Tam Özelleştirilebilir</h6>
                                    <p>Formu düzenleyebilir, alan ekleyip çıkarabilir, sıralama yapabilirsiniz.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card tip-card mt-4">
                    <div class="card-body">
                        <h6><i class="fas fa-magic text-primary"></i> Sonraki Adımlar</h6>
                        <ul class="small mb-0" style="padding-left: 1.25rem;">
                            <li class="mb-2"><strong>Formu İnceleyin:</strong> AI'nın oluşturduğu soruları kontrol edin</li>
                            <li class="mb-2"><strong>Düzenleyin:</strong> İstemediğiniz soruları çıkarın, yeni sorular ekleyin</li>
                            <li class="mb-2"><strong>Yayınlayın:</strong> İlanı aktif edin, başvurular gelmeye başlasın</li>
                            <li><strong>AI Skorlama:</strong> Gelen başvurular otomatik skorlanacak</li>
                        </ul>
                    </div>
                </div>

                <div class="card stats-card mt-4">
                    <div class="card-body">
                        <h6><i class="fas fa-chart-bar text-primary"></i> İş İlanı Bilgileri</h6>
                        <div class="stat-item">
                            <span class="stat-label">Pozisyon:</span>
                            <span class="stat-value"><?= htmlspecialchars($job['title']) ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Deneyim:</span>
                            <span class="stat-value"><?= ucfirst($job['experience_level']) ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Çalışma Şekli:</span>
                            <span class="stat-value"><?= ucfirst($job['employment_type']) ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Durum:</span>
                            <span class="badge bg-<?= $job['status'] === 'published' ? 'success' : 'secondary' ?>">
                                <?= $job['status'] === 'published' ? 'Yayında' : 'Taslak' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
:root {
    --primary-color: #4f46e5;
    --primary-light: #6366f1;
    --primary-dark: #4338ca;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-600: #6b7280;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --border-radius: 12px;
    --border-radius-sm: 8px;
    --transition-fast: all 0.2s ease;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
}

/* AI Generator Styles */
.ai-generator-card {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.03) 0%, rgba(255, 255, 255, 1) 100%);
    border: 2px solid #e0e7ff !important;
    box-shadow: 0 8px 24px rgba(79, 70, 229, 0.08) !important;
    transition: var(--transition-fast);
}

.ai-generator-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(79, 70, 229, 0.12) !important;
}

.ai-icon-wrapper {
    display: flex;
    justify-content: center;
}

.ai-icon-bg {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: white;
    box-shadow: 0 20px 40px rgba(79, 70, 229, 0.3), 0 0 0 12px rgba(79, 70, 229, 0.05);
    animation: aiPulse 3s ease-in-out infinite;
    position: relative;
}

.ai-icon-bg::before {
    content: '';
    position: absolute;
    inset: -20px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1));
    animation: aiRotate 20s linear infinite;
    z-index: -1;
}

@keyframes aiPulse {
    0%, 100% { 
        transform: scale(1); 
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.3), 0 0 0 12px rgba(79, 70, 229, 0.05);
    }
    50% { 
        transform: scale(1.05); 
        box-shadow: 0 25px 50px rgba(79, 70, 229, 0.4), 0 0 0 16px rgba(79, 70, 229, 0.08);
    }
}

@keyframes aiRotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.ai-generate-btn {
    padding: 1.25rem 3.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border: none;
    border-radius: 12px;
    color: white;
    box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ai-generate-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(79, 70, 229, 0.4);
    background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
}

.ai-generate-btn:active:not(:disabled) {
    transform: translateY(0);
}

.ai-generate-btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.ai-generate-btn .ai-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Form Preview */
.form-preview {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    padding: 2rem;
}

.form-category {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    transition: var(--transition-fast);
}

.form-category:hover {
    box-shadow: var(--shadow-md);
}

.category-title {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e0e7ff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-field-preview {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius-sm);
    border-left: 3px solid var(--primary-color);
}

.form-field-preview:last-child {
    margin-bottom: 0;
}

.field-label {
    display: block;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    font-size: 0.9375rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-300);
    border-radius: var(--border-radius-sm);
    font-size: 0.9375rem;
    transition: var(--transition-fast);
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.form-control:disabled {
    background: var(--gray-100);
    cursor: not-allowed;
}

.form-check {
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    border-radius: var(--border-radius-sm);
    transition: var(--transition-fast);
}

.form-check:hover {
    background: var(--gray-50);
}

.form-check-input {
    margin-right: 0.75rem;
    width: 18px;
    height: 18px;
}

/* Info Steps */
.info-steps {
    padding: 0;
}

.info-step {
    display: flex;
    gap: 1.25rem;
    margin-bottom: 2rem;
    position: relative;
}

.info-step:last-child {
    margin-bottom: 0;
}

.info-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 50px;
    bottom: -20px;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary-color), transparent);
}

.step-number {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    flex-shrink: 0;
    box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
    position: relative;
    z-index: 1;
}

.step-content h6 {
    margin: 0 0 0.5rem;
    color: var(--gray-900);
    font-weight: 700;
    font-size: 1rem;
}

.step-content p {
    margin: 0;
    font-size: 0.875rem;
    color: var(--gray-600);
    line-height: 1.6;
}

/* Cards */
.info-card,
.tip-card,
.stats-card {
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    transition: var(--transition-fast);
}

.info-card:hover,
.tip-card:hover,
.stats-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.tip-card {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
    border-left: 4px solid var(--warning-color);
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: var(--gray-600);
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-value {
    font-weight: 700;
    color: var(--gray-900);
}

/* Alert Improvements */
.alert-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
    border: 2px solid #86efac;
    border-radius: var(--border-radius);
    padding: 1.25rem;
    color: #065f46;
}

/* Badges */
.badge {
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.75rem;
}

.bg-success {
    background: var(--success-color) !important;
    color: white;
}

.bg-secondary {
    background: var(--gray-600) !important;
    color: white;
}

/* Utilities */
.d-flex { display: flex; }
.justify-content-between { justify-content: space-between; }
.align-items-center { align-items: center; }
.text-primary { color: var(--primary-color) !important; }
.text-warning { color: var(--warning-color) !important; }
.text-muted { color: var(--gray-600) !important; }
.text-danger { color: var(--danger-color) !important; }
.text-center { text-align: center; }
.ms-2 { margin-left: 0.5rem; }
.mt-3 { margin-top: 1rem; }
.mt-4 { margin-top: 1.5rem; }
.mb-3 { margin-bottom: 1rem; }
.mb-4 { margin-bottom: 1.5rem; }
.small { font-size: 0.875rem; }

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    transition: var(--transition-fast);
    cursor: pointer;
    text-decoration: none;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    border: none;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-outline-primary {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
}

.btn-outline-secondary {
    background: transparent;
    color: var(--gray-600);
    border: 2px solid var(--gray-300);
}

.btn-outline-secondary:hover {
    background: var(--gray-600);
    color: white;
}
</style>

<script>
// Form submit animasyonu
document.getElementById('generateFormAI')?.addEventListener('submit', function(e) {
    const btn = this.querySelector('.ai-generate-btn');
    const btnText = btn.querySelector('span');
    const loader = btn.querySelector('.ai-loader');
    
    btn.disabled = true;
    btnText.style.opacity = '0';
    loader.style.display = 'block';
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
