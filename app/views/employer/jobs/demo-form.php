<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
.form-preview-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
}

.form-container {
    max-width: 900px;
    margin: 0 auto;
}

.form-header {
    background: white;
    border-radius: 20px 20px 0 0;
    padding: 30px;
    border-bottom: 3px solid #f0f0f0;
}

.ai-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 15px;
}

.form-header h1 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 28px;
    font-weight: 700;
}

.form-header .subtitle {
    color: #666;
    font-size: 15px;
    line-height: 1.6;
}

.form-body {
    background: white;
    padding: 40px;
}

.form-section {
    margin-bottom: 35px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 3px solid #667eea;
}

.section-title i {
    color: #667eea;
    font-size: 22px;
}

.section-badge {
    margin-left: auto;
    padding: 4px 14px;
    background: #d4edda;
    color: #155724;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.section-badge.optional {
    background: #d1ecf1;
    color: #0c5460;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.form-group label .required {
    color: #e74c3c;
    margin-left: 4px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control::placeholder {
    color: #999;
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

.radio-group, .checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.radio-item, .checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.radio-item:hover, .checkbox-item:hover {
    background: #e9ecef;
}

.radio-item input[type="radio"],
.checkbox-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.radio-item label,
.checkbox-item label {
    margin: 0;
    font-weight: 500;
    cursor: pointer;
}

.file-upload-area {
    border: 2px dashed #667eea;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    background: #f5f7ff;
    cursor: pointer;
    transition: all 0.3s;
}

.file-upload-area:hover {
    background: #eef1ff;
    border-color: #5568d3;
}

.file-upload-area i {
    font-size: 48px;
    color: #667eea;
    margin-bottom: 15px;
}

.file-upload-area p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.form-footer {
    background: white;
    border-radius: 0 0 20px 20px;
    padding: 30px 40px;
    border-top: 3px solid #f0f0f0;
}

.ai-insights {
    background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%);
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
}

.ai-insights h3 {
    margin: 0 0 18px 0;
    color: #667eea;
    font-size: 18px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.insight-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 14px;
}

.insight-item:last-child {
    margin-bottom: 0;
}

.insight-item.success {
    border-left: 4px solid #28a745;
}

.insight-item.info {
    border-left: 4px solid #17a2b8;
}

.insight-item.warning {
    border-left: 4px solid #ffc107;
}

.insight-item i {
    font-size: 18px;
}

.insight-item.success i {
    color: #28a745;
}

.insight-item.info i {
    color: #17a2b8;
}

.insight-item.warning i {
    color: #ffc107;
}

.form-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border: 2px solid #e8e8e8;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s;
}

.stat-card:hover {
    border-color: #667eea;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
}

.stat-number {
    font-size: 36px;
    font-weight: bold;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px;
}

.stat-label {
    color: #666;
    font-size: 13px;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-primary-custom {
    flex: 1;
    min-width: 200px;
    padding: 16px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-secondary-custom {
    flex: 1;
    min-width: 200px;
    padding: 16px 30px;
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-secondary-custom:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .form-header, .form-body, .form-footer {
        padding: 25px 20px;
    }
    
    .form-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-primary-custom, .btn-secondary-custom {
        width: 100%;
    }
}
</style>

<div class="form-preview-page">
    <div class="form-container">
        <div class="form-header">
            <div class="ai-badge">
                <i class="fas fa-robot"></i>
                AI Tarafından Oluşturuldu
            </div>
            <h1><?= htmlspecialchars($job['title']) ?> - Başvuru Formu</h1>
            <p class="subtitle">
                Bu başvuru formu, <?= htmlspecialchars($job['title']) ?> pozisyonuna özgü olarak yapay zeka tarafından otomatik oluşturulmuştur. 
                Form, pozisyon gereksinimlerine göre en uygun soruları içerecek şekilde optimize edilmiştir.
            </p>
        </div>

        <div class="form-body">
            <!-- Kişisel Bilgiler -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    <span>Kişisel Bilgiler</span>
                    <span class="section-badge">Zorunlu</span>
                </div>
                
                <?php 
                $personalFields = array_filter($form_fields, fn($f) => $f['field_category'] === 'personal');
                foreach ($personalFields as $field): 
                ?>
                <div class="form-group">
                    <label>
                        <?= htmlspecialchars($field['field_label']) ?>
                        <?php if ($field['is_required']): ?>
                        <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field['field_type'] === 'textarea'): ?>
                        <textarea class="form-control" placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>"></textarea>
                    <?php else: ?>
                        <input type="<?= htmlspecialchars($field['field_type']) ?>" 
                               class="form-control" 
                               placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Teknik Beceriler -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-code"></i>
                    <span>Teknik Beceriler</span>
                    <span class="section-badge">Zorunlu</span>
                </div>
                
                <?php 
                $technicalFields = array_filter($form_fields, fn($f) => $f['field_category'] === 'technical');
                foreach ($technicalFields as $field): 
                ?>
                <div class="form-group">
                    <label>
                        <?= htmlspecialchars($field['field_label']) ?>
                        <?php if ($field['is_required']): ?>
                        <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field['field_type'] === 'radio'): ?>
                        <div class="radio-group">
                            <?php 
                            $options = json_decode($field['field_options'], true);
                            foreach ($options as $option): 
                            ?>
                            <div class="radio-item">
                                <input type="radio" name="<?= htmlspecialchars($field['field_name']) ?>" id="<?= htmlspecialchars($field['field_name'] . '_' . $option) ?>">
                                <label for="<?= htmlspecialchars($field['field_name'] . '_' . $option) ?>"><?= htmlspecialchars($option) ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($field['field_type'] === 'checkbox'): ?>
                        <div class="checkbox-group">
                            <?php 
                            $options = json_decode($field['field_options'], true);
                            foreach ($options as $option): 
                            ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="<?= htmlspecialchars($field['field_name']) ?>[]" id="<?= htmlspecialchars($field['field_name'] . '_' . $option) ?>">
                                <label for="<?= htmlspecialchars($field['field_name'] . '_' . $option) ?>"><?= htmlspecialchars($option) ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($field['field_type'] === 'textarea'): ?>
                        <textarea class="form-control" placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>"></textarea>
                    <?php else: ?>
                        <input type="<?= htmlspecialchars($field['field_type']) ?>" 
                               class="form-control" 
                               placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- İş Deneyimi -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-briefcase"></i>
                    <span>İş Deneyimi</span>
                    <span class="section-badge">Zorunlu</span>
                </div>
                
                <?php 
                $experienceFields = array_filter($form_fields, fn($f) => $f['field_category'] === 'experience');
                foreach ($experienceFields as $field): 
                ?>
                <div class="form-group">
                    <label>
                        <?= htmlspecialchars($field['field_label']) ?>
                        <?php if ($field['is_required']): ?>
                        <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field['field_type'] === 'select'): ?>
                        <select class="form-control">
                            <option value="">Seçiniz...</option>
                            <?php 
                            $options = json_decode($field['field_options'], true);
                            foreach ($options as $option): 
                            ?>
                            <option><?= htmlspecialchars($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($field['field_type'] === 'textarea'): ?>
                        <textarea class="form-control" placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>"></textarea>
                    <?php else: ?>
                        <input type="<?= htmlspecialchars($field['field_type']) ?>" 
                               class="form-control" 
                               placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Ek Sorular -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-question-circle"></i>
                    <span>Ek Sorular</span>
                    <span class="section-badge optional">Opsiyonel</span>
                </div>
                
                <?php 
                $additionalFields = array_filter($form_fields, fn($f) => $f['field_category'] === 'additional');
                foreach ($additionalFields as $field): 
                ?>
                <div class="form-group">
                    <label>
                        <?= htmlspecialchars($field['field_label']) ?>
                        <?php if ($field['is_required']): ?>
                        <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field['field_type'] === 'radio'): ?>
                        <div class="radio-group">
                            <?php 
                            $options = json_decode($field['field_options'], true);
                            foreach ($options as $option): 
                            ?>
                            <div class="radio-item">
                                <input type="radio" name="<?= htmlspecialchars($field['field_name']) ?>" id="<?= htmlspecialchars($field['field_name'] . '_' . $option) ?>">
                                <label for="<?= htmlspecialchars($field['field_name'] . '_' . $option) ?>"><?= htmlspecialchars($option) ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($field['field_type'] === 'textarea'): ?>
                        <textarea class="form-control" placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>"></textarea>
                    <?php elseif ($field['field_type'] === 'date'): ?>
                        <input type="date" class="form-control">
                    <?php else: ?>
                        <input type="<?= htmlspecialchars($field['field_type']) ?>" 
                               class="form-control" 
                               placeholder="<?= htmlspecialchars($field['field_placeholder']) ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Dosya Yüklemeleri -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-file-upload"></i>
                    <span>Dosya Yüklemeleri</span>
                    <span class="section-badge">Zorunlu</span>
                </div>
                
                <?php 
                $fileFields = array_filter($form_fields, fn($f) => $f['field_category'] === 'files');
                foreach ($fileFields as $field): 
                ?>
                <div class="form-group">
                    <label>
                        <?= htmlspecialchars($field['field_label']) ?>
                        <?php if ($field['is_required']): ?>
                        <span class="required">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <div class="file-upload-area">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p><strong>Dosya seçmek için tıklayın</strong> veya sürükleyin</p>
                        <p style="font-size: 12px; color: #999; margin-top: 8px;">
                            <?= htmlspecialchars($field['field_placeholder']) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-footer">
            <!-- AI Insights -->
            <div class="ai-insights">
                <h3><i class="fas fa-lightbulb"></i> AI Önerileri</h3>
                <div class="insight-item success">
                    <i class="fas fa-check-circle"></i>
                    <span>Form, pozisyon gereksinimleriyle %95 uyumlu</span>
                </div>
                <div class="insight-item success">
                    <i class="fas fa-check-circle"></i>
                    <span>Tüm kritik teknik beceriler sorgulanıyor</span>
                </div>
                <div class="insight-item info">
                    <i class="fas fa-info-circle"></i>
                    <span>Ortalama tamamlanma süresi: 12-15 dakika</span>
                </div>
                <div class="insight-item warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Tavsiye: Portföy sorusunu zorunlu yapabilirsiniz</span>
                </div>
            </div>

            <!-- Form Statistics -->
            <div class="form-stats">
                <div class="stat-card">
                    <div class="stat-number"><?= count($form_fields) ?></div>
                    <div class="stat-label">Toplam Soru</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($form_fields, fn($f) => $f['is_required'])) ?></div>
                    <div class="stat-label">Zorunlu Alan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Bölüm</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">~14dk</div>
                    <div class="stat-label">Tahmini Süre</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-primary-custom" onclick="window.location.href='<?= url('employer/dashboard') ?>'">
                    <i class="fas fa-arrow-left"></i>
                    Dashboard'a Dön
                </button>
                <button class="btn-secondary-custom" onclick="window.location.href='<?= url('employer/jobs') ?>'">
                    <i class="fas fa-briefcase"></i>
                    İlanlarım
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
