<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
/* AI-POWERED CV UPLOAD & APPLICATION PAGE - PROFESSIONAL SaaS DESIGN */
* { box-sizing: border-box; margin: 0; padding: 0; }

.application-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Job Context Header */
.job-context-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}
.job-context-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}
.job-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
.job-context-title {
    font-size: 2.25rem;
    font-weight: 800;
    margin: 0 0 0.75rem;
    line-height: 1.2;
}
.job-context-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    font-size: 1rem;
    opacity: 0.95;
}
.job-context-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.job-summary {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 1.05rem;
    line-height: 1.6;
    opacity: 0.95;
}

/* Progress Steps */
.progress-wrapper {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
}
.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.progress-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
}
.progress-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-weight: 600;
}
.progress-bar-container {
    height: 8px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 2rem;
}
.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.steps-indicator {
    display: flex;
    justify-content: space-between;
    position: relative;
}
.steps-indicator::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e5e7eb;
    z-index: 1;
}
.step-item {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    text-align: center;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #9ca3af;
    margin-bottom: 0.75rem;
    transition: all 0.3s;
}
.step-item.completed .step-circle {
    background: #10b981;
    border-color: #10b981;
    color: white;
}
.step-item.active .step-circle {
    background: #667eea;
    border-color: #667eea;
    color: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2); }
    50% { box-shadow: 0 0 0 8px rgba(102, 126, 234, 0.1); }
}
.step-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #9ca3af;
    transition: color 0.3s;
}
.step-item.completed .step-label,
.step-item.active .step-label {
    color: #111827;
}

/* Application Form Card */
.application-form-card {
    background: white;
    border-radius: 16px;
    padding: 3rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}
.form-step {
    display: none;
}
.form-step.active {
    display: block;
    animation: fadeIn 0.5s;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.step-header {
    margin-bottom: 2.5rem;
}
.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    border-radius: 8px;
    font-weight: 700;
    margin-bottom: 1rem;
}
.step-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 0.75rem;
}
.step-description {
    font-size: 1.05rem;
    color: #6b7280;
    margin: 0;
}

/* CV Upload Area */
.cv-upload-zone {
    border: 3px dashed #d1d5db;
    border-radius: 16px;
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
    background: #f9fafb;
    position: relative;
}
.cv-upload-zone.drag-over {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    transform: scale(1.02);
}
.cv-upload-zone.has-file {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.05);
}
.upload-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    border-radius: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}
.cv-upload-zone.has-file .upload-icon {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    animation: checkmark 0.5s ease;
}
@keyframes checkmark {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.upload-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.75rem;
}
.upload-subtitle {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
}
.upload-formats {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 600;
}
.file-input {
    display: none;
}
.uploaded-file-info {
    display: none;
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    text-align: left;
}
.uploaded-file-info.show {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.file-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #667eea;
    flex-shrink: 0;
}
.file-details {
    flex: 1;
}
.file-name {
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
}
.file-size {
    font-size: 0.875rem;
    color: #6b7280;
}
.file-remove {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #fee2e2;
    color: #ef4444;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.file-remove:hover {
    background: #ef4444;
    color: white;
}

/* AI Processing Indicator */
.ai-processing {
    display: none;
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: rgba(102, 126, 234, 0.05);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    text-align: left;
}
.ai-processing.show {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.ai-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e5e7eb;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.ai-processing-text {
    font-weight: 600;
    color: #667eea;
}

/* Form Fields */
.form-group {
    margin-bottom: 2rem;
}
.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}
.label-required {
    color: #ef4444;
}
.label-help {
    color: #6b7280;
    font-size: 0.85rem;
    font-weight: 400;
    margin-top: 0.25rem;
    display: block;
}
.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.3s;
    background: white;
}
.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}
.form-textarea {
    min-height: 120px;
    resize: vertical;
}
.form-input.ai-filled {
    background: rgba(16, 185, 129, 0.05);
    border-color: #10b981;
}
.ai-filled-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-top: 0.75rem;
}

/* AI Questions */
.ai-questions-intro {
    background: rgba(102, 126, 234, 0.05);
    border-left: 4px solid #667eea;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}
.ai-questions-intro h4 {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.125rem;
    font-weight: 700;
    color: #667eea;
    margin: 0 0 0.5rem;
}
.ai-questions-intro p {
    color: #374151;
    margin: 0;
}

/* Review Summary */
.review-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2.5rem;
    border-bottom: 1px solid #e5e7eb;
}
.review-section:last-child {
    border-bottom: none;
}
.review-section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}
.review-section-title h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}
.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.2s;
}
.btn-edit:hover {
    background: rgba(102, 126, 234, 0.1);
}
.review-item {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}
.review-label {
    font-weight: 600;
    color: #6b7280;
}
.review-value {
    color: #111827;
}
.cv-preview {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
}
.cv-preview-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: rgba(239, 68, 68, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #ef4444;
}

/* Trust Section */
.trust-section {
    background: #f6f8fb;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}
.trust-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #667eea;
}
.trust-section h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.75rem;
}
.trust-features {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0 0;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}
.trust-features li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
    font-size: 0.9rem;
    font-weight: 600;
}
.trust-features i {
    color: #10b981;
}

/* Navigation Buttons */
.form-navigation {
    display: flex;
    gap: 1rem;
    margin-top: 3rem;
}
.btn-prev,
.btn-next,
.btn-submit {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1.25rem 2rem;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}
.btn-prev {
    background: #f3f4f6;
    color: #374151;
}
.btn-prev:hover {
    background: #e5e7eb;
}
.btn-next,
.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
}
.btn-next:hover,
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5);
}
.btn-submit {
    position: relative;
}
.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Success Screen */
.success-screen {
    display: none;
    text-align: center;
    padding: 4rem 2rem;
}
.success-screen.show {
    display: block;
    animation: fadeIn 0.5s;
}
.success-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
    box-shadow: 0 20px 60px rgba(16, 185, 129, 0.4);
    animation: successPop 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes successPop {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}
.success-screen h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 1rem;
}
.success-screen p {
    font-size: 1.125rem;
    color: #6b7280;
    margin: 0 0 2rem;
}
.success-details {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    margin: 2rem auto;
    max-width: 500px;
    text-align: left;
}
.success-detail-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}
.success-detail-item:last-child {
    border-bottom: none;
}
.success-detail-label {
    font-weight: 600;
    color: #6b7280;
}
.success-detail-value {
    font-weight: 700;
    color: #111827;
}
.btn-dashboard {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem 2.5rem;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    transition: all 0.3s;
}
.btn-dashboard:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
    .application-container {
        padding: 1rem 0.5rem;
    }
    .job-context-header {
        padding: 2rem 1.5rem;
    }
    .job-context-title {
        font-size: 1.75rem;
    }
    .progress-wrapper,
    .application-form-card {
        padding: 1.5rem;
    }
    .steps-indicator {
        flex-wrap: wrap;
        gap: 1rem;
    }
    .step-item {
        flex-basis: 33%;
    }
    .step-label {
        font-size: 0.75rem;
    }
    .review-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    .form-navigation {
        flex-direction: column;
    }
}
</style>

<div class="application-container">
    <!-- Job Context Header -->
    <div class="job-context-header">
        <div class="job-badge">
            <i class="fas fa-robot"></i>
            <span>AI-assisted hiring process</span>
        </div>
        <h1 class="job-context-title"><?= $job['title'] ?? 'İş Pozisyonu' ?></h1>
        <div class="job-context-meta">
            <div class="job-context-meta-item">
                <i class="fas fa-building"></i>
                <span><?= $job['company_name'] ?? 'Şirket Adı' ?></span>
            </div>
            <?php if (!empty($job['location'])): ?>
            <div class="job-context-meta-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><?= $job['location'] ?></span>
            </div>
            <?php endif; ?>
            <div class="job-context-meta-item">
                <i class="fas fa-clock"></i>
                <span><?= $job['employment_type'] ?? 'Tam Zamanlı' ?></span>
            </div>
        </div>
        <?php if (!empty($job['short_description'])): ?>
        <div class="job-summary">
            <?= $job['short_description'] ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Progress Indicator -->
    <div class="progress-wrapper">
        <div class="progress-info">
            <h3 class="progress-title">Başvuru Süreci</h3>
            <div class="progress-time">
                <i class="far fa-clock"></i>
                <span>~5 dakika</span>
            </div>
        </div>
        
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBar" style="width: 20%;"></div>
        </div>
        
        <div class="steps-indicator">
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <span class="step-label">CV Yükle</span>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <span class="step-label">Kişisel Bilgiler</span>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <span class="step-label">Beceriler</span>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <span class="step-label">AI Soruları</span>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-circle">5</div>
                <span class="step-label">Gözden Geçir</span>
            </div>
        </div>
    </div>

    <!-- Application Form -->
    <form id="applicationForm" class="application-form-card" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?= $job['id'] ?? '' ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf() ?>">
        
        <!-- Step 1: CV Upload -->
        <div class="form-step active" data-step="1">
            <div class="step-header">
                <div class="step-number">1</div>
                <h2 class="step-title">CV'nizi Yükleyin</h2>
                <p class="step-description">CV'niz otomatik olarak AI tarafından analiz edilecek ve bilgileriniz form alanlarına otomatik doldurulacaktır.</p>
            </div>

            <div class="cv-upload-zone" id="cvUploadZone">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h3 class="upload-title">Dosyayı sürükleyin veya seçin</h3>
                <p class="upload-subtitle">CV'nizi yüklemek için tıklayın veya buraya sürükleyin</p>
                <div class="upload-formats">
                    <i class="fas fa-file-pdf"></i>
                    <span>PDF, DOC, DOCX (Max 5MB)</span>
                </div>
                <input type="file" id="cvFileInput" name="cv_file" class="file-input" accept=".pdf,.doc,.docx">
            </div>

            <div class="uploaded-file-info" id="uploadedFileInfo">
                <div class="file-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="file-details">
                    <div class="file-name" id="fileName">CV_John_Doe.pdf</div>
                    <div class="file-size" id="fileSize">2.5 MB</div>
                </div>
                <button type="button" class="file-remove" id="removeFile">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="ai-processing" id="aiProcessing">
                <div class="ai-spinner"></div>
                <div>
                    <div class="ai-processing-text">AI CV'nizi analiz ediyor...</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">Bilgileriniz otomatik olarak doldurulacak</div>
                </div>
            </div>
        </div>

        <!-- Step 2: Personal Information -->
        <div class="form-step" data-step="2">
            <div class="step-header">
                <div class="step-number">2</div>
                <h2 class="step-title">Kişisel Bilgiler</h2>
                <p class="step-description">Lütfen bilgilerinizi kontrol edin ve gerekirse güncelleyin.</p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Ad Soyad <span class="label-required">*</span>
                </label>
                <input type="text" name="full_name" class="form-input" id="fullName" required placeholder="Adınız ve soyadınız">
            </div>

            <div class="form-group">
                <label class="form-label">
                    E-posta <span class="label-required">*</span>
                </label>
                <input type="email" name="email" class="form-input" id="email" required placeholder="ornek@email.com">
            </div>

            <div class="form-group">
                <label class="form-label">
                    Telefon <span class="label-required">*</span>
                </label>
                <input type="tel" name="phone" class="form-input" id="phone" required placeholder="+90 555 123 45 67">
            </div>

            <div class="form-group">
                <label class="form-label">
                    Şehir
                </label>
                <input type="text" name="city" class="form-input" id="city" placeholder="İstanbul">
            </div>

            <div class="form-group">
                <label class="form-label">
                    LinkedIn Profili
                    <span class="label-help">Portfolio veya profesyonel profilinizi paylaşın</span>
                </label>
                <input type="url" name="linkedin" class="form-input" id="linkedin" placeholder="https://linkedin.com/in/kullanici-adi">
            </div>
        </div>

        <!-- Step 3: Skills & Experience -->
        <div class="form-step" data-step="3">
            <div class="step-header">
                <div class="step-number">3</div>
                <h2 class="step-title">Beceriler & Deneyim</h2>
                <p class="step-description">Yetenekleriniz ve iş deneyiminiz hakkında bilgi verin.</p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Toplam Deneyim Süresi <span class="label-required">*</span>
                    <span class="label-help">İlgili alandaki toplam çalışma süreniz</span>
                </label>
                <select name="experience_years" class="form-select" id="experienceYears" required>
                    <option value="">Seçiniz...</option>
                    <option value="0-1">0-1 yıl</option>
                    <option value="1-3">1-3 yıl</option>
                    <option value="3-5">3-5 yıl</option>
                    <option value="5-10">5-10 yıl</option>
                    <option value="10+">10+ yıl</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Temel Yetenekler <span class="label-required">*</span>
                    <span class="label-help">Bu pozisyon için sahip olduğunuz yetenekleri yazın (virgülle ayırın)</span>
                </label>
                <input type="text" name="skills" class="form-input" id="skills" required placeholder="PHP, Laravel, MySQL, JavaScript">
            </div>

            <div class="form-group">
                <label class="form-label">
                    Eğitim Durumu <span class="label-required">*</span>
                </label>
                <select name="education" class="form-select" id="education" required>
                    <option value="">Seçiniz...</option>
                    <option value="Lise">Lise</option>
                    <option value="Önlisans">Önlisans</option>
                    <option value="Lisans">Lisans</option>
                    <option value="Yüksek Lisans">Yüksek Lisans</option>
                    <option value="Doktora">Doktora</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Mevcut/Son Pozisyon
                </label>
                <input type="text" name="current_position" class="form-input" id="currentPosition" placeholder="Senior PHP Developer">
            </div>
        </div>

        <!-- Step 4: AI Questions -->
        <div class="form-step" data-step="4">
            <div class="step-header">
                <div class="step-number">4</div>
                <h2 class="step-title">AI Soruları</h2>
                <p class="step-description">Bu sorular, pozisyona uygunluğunuzu değerlendirmek için AI tarafından oluşturulmuştur.</p>
            </div>

            <div class="ai-questions-intro">
                <h4>
                    <i class="fas fa-robot"></i>
                    Neden bu sorular?
                </h4>
                <p>Bu sorular, gerçek dünya deneyiminizi anlamamıza ve pozisyon için uygunluğunuzu değerlendirmemize yardımcı olur. Açık ve dürüst yanıtlar vermeniz başarınızı artırır.</p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Bu pozisyona başvurmak için sizi motive eden nedir? <span class="label-required">*</span>
                    <span class="label-help">Motivasyonunuzu ve kariyer hedeflerinizi anlamamıza yardımcı olur</span>
                </label>
                <textarea name="motivation" class="form-textarea" id="motivation" required placeholder="Bu pozisyonda neden çalışmak istediğinizi açıklayın..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">
                    En gurur duyduğunuz teknik projenizi kısaca anlatın <span class="label-required">*</span>
                    <span class="label-help">Teknik yeteneklerinizi ve problem çözme yaklaşımınızı değerlendirmemize yardımcı olur</span>
                </label>
                <textarea name="project_example" class="form-textarea" id="projectExample" required placeholder="Projenin amacı, kullandığınız teknolojiler ve sonuçlar..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Uzaktan çalışma konusunda deneyiminiz var mı? <span class="label-required">*</span>
                </label>
                <select name="remote_experience" class="form-select" id="remoteExperience" required>
                    <option value="">Seçiniz...</option>
                    <option value="Evet, tamamen uzaktan">Evet, tamamen uzaktan çalıştım</option>
                    <option value="Evet, hibrit">Evet, hibrit olarak</option>
                    <option value="Hayır, ama istekliyim">Hayır, ama uzaktan çalışmaya hazırım</option>
                    <option value="Hayır">Hayır, tercih etmiyorum</option>
                </select>
            </div>
        </div>

        <!-- Step 5: Review & Submit -->
        <div class="form-step" data-step="5">
            <div class="step-header">
                <div class="step-number">5</div>
                <h2 class="step-title">Gözden Geçir & Gönder</h2>
                <p class="step-description">Başvurunuzu göndermeden önce bilgilerinizi kontrol edin.</p>
            </div>

            <div class="review-section">
                <div class="review-section-title">
                    <h4>CV Dosyanız</h4>
                    <a href="#" class="btn-edit" onclick="goToStep(1); return false;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
                <div class="cv-preview" id="cvPreviewReview">
                    <div class="cv-preview-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name" id="reviewFileName">CV dosyası yüklenmedi</div>
                        <div class="file-size" id="reviewFileSize">-</div>
                    </div>
                </div>
            </div>

            <div class="review-section">
                <div class="review-section-title">
                    <h4>Kişisel Bilgiler</h4>
                    <a href="#" class="btn-edit" onclick="goToStep(2); return false;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
                <div class="review-item">
                    <div class="review-label">Ad Soyad:</div>
                    <div class="review-value" id="reviewFullName">-</div>
                </div>
                <div class="review-item">
                    <div class="review-label">E-posta:</div>
                    <div class="review-value" id="reviewEmail">-</div>
                </div>
                <div class="review-item">
                    <div class="review-label">Telefon:</div>
                    <div class="review-value" id="reviewPhone">-</div>
                </div>
                <div class="review-item">
                    <div class="review-label">Şehir:</div>
                    <div class="review-value" id="reviewCity">-</div>
                </div>
            </div>

            <div class="review-section">
                <div class="review-section-title">
                    <h4>Beceriler & Deneyim</h4>
                    <a href="#" class="btn-edit" onclick="goToStep(3); return false;">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                </div>
                <div class="review-item">
                    <div class="review-label">Deneyim:</div>
                    <div class="review-value" id="reviewExperience">-</div>
                </div>
                <div class="review-item">
                    <div class="review-label">Yetenekler:</div>
                    <div class="review-value" id="reviewSkills">-</div>
                </div>
                <div class="review-item">
                    <div class="review-label">Eğitim:</div>
                    <div class="review-value" id="reviewEducation">-</div>
                </div>
            </div>
        </div>

        <!-- Form Navigation -->
        <div class="form-navigation">
            <button type="button" class="btn-prev" id="btnPrev" style="display: none;">
                <i class="fas fa-arrow-left"></i>
                <span>Önceki</span>
            </button>
            <button type="button" class="btn-next" id="btnNext">
                <span>Sonraki</span>
                <i class="fas fa-arrow-right"></i>
            </button>
            <button type="submit" class="btn-submit" id="btnSubmit" style="display: none;">
                <span>Başvuruyu Gönder</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>

    <!-- Success Screen -->
    <div class="success-screen" id="successScreen">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2>Başvurunuz Alındı! 🎉</h2>
        <p>Başvurunuz başarıyla kaydedildi ve AI tarafından değerlendiriliyor.</p>
        
        <div class="success-details">
            <div class="success-detail-item">
                <span class="success-detail-label">Pozisyon:</span>
                <span class="success-detail-value"><?= $job['title'] ?? 'İş Pozisyonu' ?></span>
            </div>
            <div class="success-detail-item">
                <span class="success-detail-label">Başvuru Tarihi:</span>
                <span class="success-detail-value" id="successDate">-</span>
            </div>
            <div class="success-detail-item">
                <span class="success-detail-label">Durum:</span>
                <span class="success-detail-value">AI Değerlendirmesi</span>
            </div>
        </div>

        <p style="margin-top: 2rem; color: #6b7280;">
            Başvurunuzun durumunu panelinizden takip edebilirsiniz. 
            <br>Değerlendirme tamamlandığında size bildirim göndereceğiz.
        </p>

        <a href="<?= url('home') ?>" class="btn-dashboard">
            <i class="fas fa-home"></i>
            <span>Ana Sayfaya Dön</span>
        </a>
    </div>

    <!-- Trust Section -->
    <div class="trust-section">
        <div class="trust-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h4>Verileriniz Güvende</h4>
        <p style="color: #6b7280; margin: 0.5rem 0 0;">AI, İK kararlarına yardımcı olmak için kullanılır, insanların yerini almaz.</p>
        <ul class="trust-features">
            <li>
                <i class="fas fa-check-circle"></i>
                <span>GDPR Uyumlu</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>Şifreli Veri</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>Gizlilik Garantisi</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>İnsan Onaylı</span>
            </li>
        </ul>
        <a href="#" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 0.9rem; margin-top: 1rem; display: inline-block;">
            Gizlilik Politikası <i class="fas fa-external-link-alt" style="font-size: 0.8rem;"></i>
        </a>
    </div>
</div>

<script>
// Application Form Multi-Step Logic
let currentStep = 1;
const totalSteps = 5;
let uploadedFile = null;
let cvParsedData = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateStepDisplay();
    initCVUpload();
    initFormNavigation();
});

// CV Upload Functionality
function initCVUpload() {
    const uploadZone = document.getElementById('cvUploadZone');
    const fileInput = document.getElementById('cvFileInput');
    const uploadedFileInfo = document.getElementById('uploadedFileInfo');
    const aiProcessing = document.getElementById('aiProcessing');
    const removeFileBtn = document.getElementById('removeFile');

    // Click to upload
    uploadZone.addEventListener('click', () => fileInput.click());

    // Drag & Drop
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('drag-over');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('drag-over');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileUpload(e.target.files[0]);
        }
    });

    // Remove file
    removeFileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        removeFile();
    });
}

function handleFileUpload(file) {
    // Validate file type
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!allowedTypes.includes(file.type)) {
        alert('Geçersiz dosya formatı. Lütfen PDF, DOC veya DOCX formatında yükleyin.');
        return;
    }

    // Validate file size (5MB)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        alert('Dosya boyutu çok büyük. Maksimum 5MB olmalıdır.');
        return;
    }

    uploadedFile = file;

    // Update UI
    const uploadZone = document.getElementById('cvUploadZone');
    const uploadIcon = uploadZone.querySelector('.upload-icon i');
    uploadZone.classList.add('has-file');
    uploadIcon.className = 'fas fa-check';

    // Show file info
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    document.getElementById('uploadedFileInfo').classList.add('show');

    // Show AI processing
    document.getElementById('aiProcessing').classList.add('show');

    // Simulate AI CV parsing
    setTimeout(() => {
        parseCV(file);
    }, 2000);
}

function removeFile() {
    uploadedFile = null;
    cvParsedData = null;

    const uploadZone = document.getElementById('cvUploadZone');
    const uploadIcon = uploadZone.querySelector('.upload-icon i');
    uploadZone.classList.remove('has-file');
    uploadIcon.className = 'fas fa-cloud-upload-alt';

    document.getElementById('uploadedFileInfo').classList.remove('show');
    document.getElementById('aiProcessing').classList.remove('show');
    document.getElementById('cvFileInput').value = '';

    // Remove AI-filled indicators
    document.querySelectorAll('.ai-filled').forEach(input => {
        input.classList.remove('ai-filled');
    });
    document.querySelectorAll('.ai-filled-badge').forEach(badge => {
        badge.remove();
    });
}

function parseCV(file) {
    // Send CV to backend for AI parsing
    const formData = new FormData();
    formData.append('cv_file', file);
    
    fetch('<?= url("job/parseCV") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            cvParsedData = data.data;
            
            // Auto-fill form fields
            autoFillFields(cvParsedData);
            
            // Hide AI processing
            document.getElementById('aiProcessing').classList.remove('show');
            
            // Show success message
            showNotification('✅ CV başarıyla analiz edildi! Bilgileriniz otomatik dolduruldu.', 'success');
        } else {
            // AI parsing failed, just continue without auto-fill
            document.getElementById('aiProcessing').classList.remove('show');
            showNotification('CV yüklendi. Lütfen bilgilerinizi manuel olarak doldurun.', 'info');
        }
    })
    .catch(error => {
        console.error('CV parsing error:', error);
        document.getElementById('aiProcessing').classList.remove('show');
        showNotification('CV yüklendi. Lütfen bilgilerinizi manuel olarak doldurun.', 'info');
    });
}

function autoFillFields(data) {
    Object.keys(data).forEach(key => {
        const input = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
        if (input) {
            input.value = data[key];
            input.classList.add('ai-filled');
            
            // Add AI-filled badge
            const formGroup = input.closest('.form-group');
            if (formGroup && !formGroup.querySelector('.ai-filled-badge')) {
                const badge = document.createElement('div');
                badge.className = 'ai-filled-badge';
                badge.innerHTML = '<i class="fas fa-robot"></i> <span>AI tarafından dolduruldu</span>';
                formGroup.appendChild(badge);
            }
        }
    });
}

// Form Navigation
function initFormNavigation() {
    document.getElementById('btnNext').addEventListener('click', () => {
        if (validateStep(currentStep)) {
            nextStep();
        }
    });

    document.getElementById('btnPrev').addEventListener('click', () => {
        prevStep();
    });

    document.getElementById('applicationForm').addEventListener('submit', handleSubmit);
}

function validateStep(step) {
    const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
    const requiredInputs = currentStepEl.querySelectorAll('[required]');
    
    for (let input of requiredInputs) {
        if (!input.value.trim()) {
            input.focus();
            showNotification('Lütfen tüm zorunlu alanları doldurun.', 'error');
            return false;
        }
    }

    // Special validation for step 1 (CV upload)
    if (step === 1 && !uploadedFile) {
        showNotification('Lütfen CV dosyanızı yükleyin.', 'error');
        return false;
    }

    return true;
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        updateStepDisplay();
        updateReviewData();
        scrollToTop();
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
        scrollToTop();
    }
}

function goToStep(step) {
    currentStep = step;
    updateStepDisplay();
    scrollToTop();
}

function updateStepDisplay() {
    // Update form steps
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');

    // Update step indicators
    document.querySelectorAll('.step-item').forEach((item, index) => {
        item.classList.remove('active', 'completed');
        if (index + 1 < currentStep) {
            item.classList.add('completed');
            item.querySelector('.step-circle').innerHTML = '<i class="fas fa-check"></i>';
        } else if (index + 1 === currentStep) {
            item.classList.add('active');
            item.querySelector('.step-circle').textContent = index + 1;
        } else {
            item.querySelector('.step-circle').textContent = index + 1;
        }
    });

    // Update progress bar
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('progressBar').style.width = progress + '%';

    // Update navigation buttons
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');

    btnPrev.style.display = currentStep === 1 ? 'none' : 'flex';
    btnNext.style.display = currentStep === totalSteps ? 'none' : 'flex';
    btnSubmit.style.display = currentStep === totalSteps ? 'flex' : 'none';
}

function updateReviewData() {
    if (currentStep === 5) {
        // Update CV preview
        if (uploadedFile) {
            document.getElementById('reviewFileName').textContent = uploadedFile.name;
            document.getElementById('reviewFileSize').textContent = formatFileSize(uploadedFile.size);
        }

        // Update personal info
        document.getElementById('reviewFullName').textContent = document.getElementById('fullName').value || '-';
        document.getElementById('reviewEmail').textContent = document.getElementById('email').value || '-';
        document.getElementById('reviewPhone').textContent = document.getElementById('phone').value || '-';
        document.getElementById('reviewCity').textContent = document.getElementById('city').value || 'Belirtilmedi';

        // Update skills & experience
        document.getElementById('reviewExperience').textContent = document.getElementById('experienceYears').value || '-';
        document.getElementById('reviewSkills').textContent = document.getElementById('skills').value || '-';
        document.getElementById('reviewEducation').textContent = document.getElementById('education').value || '-';
    }
}

function handleSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    
    // Show loading state
    const btnSubmit = document.getElementById('btnSubmit');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Gönderiliyor...</span>';

    // Send form data via AJAX
    fetch('<?= url("job/submitApplication") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide form, show success screen
            document.querySelector('.application-form-card').style.display = 'none';
            document.querySelector('.progress-wrapper').style.display = 'none';
            document.getElementById('successScreen').classList.add('show');
            
            // Update success date
            const now = new Date();
            document.getElementById('successDate').textContent = now.toLocaleDateString('tr-TR');
            
            scrollToTop();
        } else {
            // Show error
            alert(data.message || 'Başvuru gönderilemedi. Lütfen tekrar deneyin.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
    });
}

// Utility Functions
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showNotification(message, type = 'info') {
    // Simple alert for now - in production, use toast notifications
    alert(message);
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
