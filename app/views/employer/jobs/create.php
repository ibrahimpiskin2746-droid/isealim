<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-brain"></i>
                <span>AI İşe Alım</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= url('employer/dashboard') ?>" class="nav-link">
                <i class="fas fa-chart-line"></i> 
                <span>Dashboard</span>
            </a>
            <a href="<?= url('employer/jobs') ?>" class="nav-link">
                <i class="fas fa-briefcase"></i> 
                <span>İlanlarım</span>
            </a>
            <a href="<?= url('employer/create-job') ?>" class="nav-link active nav-highlight">
                <i class="fas fa-magic"></i> 
                <span>AI İlan Oluştur</span>
            </a>
            <a href="<?= url('employer/applications') ?>" class="nav-link">
                <i class="fas fa-file-alt"></i> 
                <span>Başvurular</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-magic"></i>
                    Yeni İş İlanı Oluştur
                </h1>
                <p class="page-subtitle">Pozisyonu tanımlayın, AI otomatik form oluştursun</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="fillFormWithAI()" class="btn-ai-fill">
                    <i class="fas fa-robot"></i>
                    AI ile Örnek İlan Oluştur
                </button>
                <a href="<?= url('employer/jobs') ?>" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Geri Dön
                </a>
            </div>
        </div>

        <div class="create-job-form">
            <form method="POST" action="<?= url('employer/create-job') ?>" id="createJobForm">
                <?= csrfField() ?>
                
                <!-- Temel Bilgiler -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="fas fa-info-circle"></i> Temel Bilgiler</h3>
                        <p>İş pozisyonu hakkında temel bilgileri girin</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="title">İş Pozisyonu Başlığı <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                class="form-control" 
                                placeholder="Örn: Senior PHP Developer"
                                value="<?= isset($data['title']) ? htmlspecialchars($data['title']) : '' ?>"
                                required
                            >
                            <?php if (isset($errors['title'])): ?>
                                <span class="error-message"><?= htmlspecialchars($errors['title']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="location">Lokasyon <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="location" 
                                name="location" 
                                class="form-control" 
                                placeholder="Örn: İstanbul, Türkiye"
                                value="<?= isset($data['location']) ? htmlspecialchars($data['location']) : '' ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="employment_type">Çalışma Tipi <span class="required">*</span></label>
                            <select id="employment_type" name="employment_type" class="form-control" required>
                                <option value="">Seçiniz</option>
                                <option value="full-time" <?= (isset($data['employment_type']) && $data['employment_type'] == 'full-time') ? 'selected' : '' ?>>Tam Zamanlı</option>
                                <option value="part-time" <?= (isset($data['employment_type']) && $data['employment_type'] == 'part-time') ? 'selected' : '' ?>>Yarı Zamanlı</option>
                                <option value="contract" <?= (isset($data['employment_type']) && $data['employment_type'] == 'contract') ? 'selected' : '' ?>>Sözleşmeli</option>
                                <option value="internship" <?= (isset($data['employment_type']) && $data['employment_type'] == 'internship') ? 'selected' : '' ?>>Stajyer</option>
                                <option value="remote" <?= (isset($data['employment_type']) && $data['employment_type'] == 'remote') ? 'selected' : '' ?>>Uzaktan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="experience_level">Deneyim Seviyesi</label>
                            <select id="experience_level" name="experience_level" class="form-control">
                                <option value="">Seçiniz</option>
                                <option value="entry" <?= (isset($data['experience_level']) && $data['experience_level'] == 'entry') ? 'selected' : '' ?>>Başlangıç</option>
                                <option value="mid" <?= (isset($data['experience_level']) && $data['experience_level'] == 'mid') ? 'selected' : '' ?>>Orta</option>
                                <option value="senior" <?= (isset($data['experience_level']) && $data['experience_level'] == 'senior') ? 'selected' : '' ?>>Kıdemli</option>
                                <option value="lead" <?= (isset($data['experience_level']) && $data['experience_level'] == 'lead') ? 'selected' : '' ?>>Yönetici/Lider</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="salary_min">Maaş (Min)</label>
                            <input 
                                type="number" 
                                id="salary_min" 
                                name="salary_min" 
                                class="form-control" 
                                placeholder="Örn: 15000"
                                value="<?= isset($data['salary_min']) ? htmlspecialchars($data['salary_min']) : '' ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="salary_max">Maaş (Max)</label>
                            <input 
                                type="number" 
                                id="salary_max" 
                                name="salary_max" 
                                class="form-control" 
                                placeholder="Örn: 25000"
                                value="<?= isset($data['salary_max']) ? htmlspecialchars($data['salary_max']) : '' ?>"
                            >
                        </div>
                    </div>
                </div>

                <!-- İş Tanımı -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="fas fa-file-alt"></i> İş Tanımı</h3>
                        <p>Pozisyon hakkında detaylı açıklama yapın. AI bu bilgileri kullanarak form oluşturacak.</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">İş Açıklaması <span class="required">*</span></label>
                        <textarea 
                            id="description" 
                            name="description" 
                            class="form-control" 
                            rows="8"
                            placeholder="Pozisyon hakkında detaylı bilgi verin. Görevler, sorumluluklar, çalışma ortamı vb."
                            required
                        ><?= isset($data['description']) ? htmlspecialchars($data['description']) : '' ?></textarea>
                        <small class="form-hint">Minimum 50 karakter. Ne kadar detaylı olursa, AI o kadar iyi form oluşturur.</small>
                        <?php if (isset($errors['description'])): ?>
                            <span class="error-message"><?= htmlspecialchars($errors['description']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Gereksinimler -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="fas fa-check-circle"></i> Gereksinimler</h3>
                        <p>Adaylarda aranan teknik ve kişisel özellikler</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="requirements">Gereksinimler</label>
                        <textarea 
                            id="requirements" 
                            name="requirements" 
                            class="form-control" 
                            rows="6"
                            placeholder="Örn: PHP, Laravel, MySQL deneyimi. 3+ yıl backend geliştirme. Takım çalışmasına yatkın."
                        ><?= isset($data['requirements']) ? htmlspecialchars($data['requirements']) : '' ?></textarea>
                        <small class="form-hint">Teknik beceriler, deneyim süresi, eğitim, soft skills vb.</small>
                    </div>
                </div>

                <!-- Yan Haklar -->
                <div class="form-section">
                    <div class="section-header">
                        <h3><i class="fas fa-gift"></i> Yan Haklar</h3>
                        <p>Pozisyon için sunulan ekstra avantajlar</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="benefits">Yan Haklar</label>
                        <textarea 
                            id="benefits" 
                            name="benefits" 
                            class="form-control" 
                            rows="4"
                            placeholder="Örn: Sağlık sigortası, yemek kartı, esnek çalışma saatleri, uzaktan çalışma imkanı"
                        ><?= isset($data['benefits']) ? htmlspecialchars($data['benefits']) : '' ?></textarea>
                    </div>
                </div>

                <!-- AI Bilgi Kutusu -->
                <div class="ai-info-box">
                    <div class="ai-info-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="ai-info-content">
                        <h4>AI Form Oluşturma</h4>
                        <p>İlanınızı kaydettikten sonra, AI otomatik olarak başvuru formu oluşturacak. Form alanlarını düzenleyebilir, ekleme/çıkarma yapabilirsiniz.</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Otomatik soru üretimi</li>
                            <li><i class="fas fa-check"></i> Teknik beceri değerlendirmesi</li>
                            <li><i class="fas fa-check"></i> Deneyim uyum analizi</li>
                            <li><i class="fas fa-check"></i> Özelleştirilebilir form alanları</li>
                        </ul>
                    </div>
                </div>

                <!-- Form Butonları -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary-large">
                        <i class="fas fa-save"></i>
                        İlanı Kaydet ve Devam Et
                    </button>
                    <a href="<?= url('employer/jobs') ?>" class="btn-secondary-large">
                        <i class="fas fa-times"></i>
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
.create-job-form {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f3f4f6;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 1.05rem;
}

.form-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-header p {
    color: #6b7280;
    margin: 0;
    font-size: 0.95rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.required {
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.2s;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-hint {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #9ca3af;
}

.error-message {
    display: block;
    margin-top: 0.5rem;
    color: #ef4444;
    font-size: 0.875rem;
    font-weight: 500;
}

.ai-info-box {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 16px;
    padding: 2rem;
    margin: 2.5rem 0;
    display: flex;
    gap: 1.5rem;
}

.ai-info-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    flex-shrink: 0;
}

.ai-info-content h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.75rem;
}

.ai-info-content p {
    color: #6b7280;
    margin: 0 0 1rem;
    line-height: 1.6;
}

.ai-info-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.ai-info-content li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
    font-size: 0.9rem;
}

.ai-info-content li i {
    color: #10b981;
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.625rem;
    transition: all 0.3s;
    text-decoration: none;
}
.btn-secondary:hover {
    border-color: #667eea;
    color: #667eea;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 2px solid #f3f4f6;
}

.btn-primary-large,
.btn-secondary-large {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary-large {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary-large:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary-large {
    background: white;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-secondary-large:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .ai-info-content ul {
        grid-template-columns: 1fr;
    }
}

/* AI Fill Button */
.btn-ai-fill {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.625rem;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
.btn-ai-fill:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}
.btn-ai-fill i {
    font-size: 1.125rem;
}

/* AI Fill Animation */
@keyframes aiTyping {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}
.ai-filling {
    animation: aiTyping 1.5s ease-in-out infinite;
}
</style>

<script>
// AI Form Fill Function
function fillFormWithAI() {
    // Show loading state
    const btn = event.target.closest('.btn-ai-fill');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> AI Dolduruyor...';
    btn.disabled = true;
    
    // Sample job data with different positions
    const sampleJobs = [
        {
            title: 'Senior Full Stack Developer',
            location: 'İstanbul, Türkiye (Hybrid)',
            employment_type: 'full-time',
            experience_level: 'senior',
            salary_min: '25000',
            salary_max: '40000',
            description: `Hızla büyüyen teknoloji şirketimizde deneyimli bir Full Stack Developer arıyoruz.

Sorumluluklar:
• Modern web uygulamaları geliştirmek ve bakımını yapmak
• Frontend ve backend teknolojilerinde end-to-end çözümler üretmek
• Mikroservis mimarisi ile ölçeklenebilir sistemler tasarlamak
• Code review süreçlerine katılmak ve ekip üyelerine mentorluk yapmak
• CI/CD pipeline'larını optimize etmek
• Performans iyileştirmeleri ve güvenlik standartlarını uygulamak

Çalışma Ortamı:
Agile metodolojisi ile çalışan, yenilikçi ve dinamik bir ekipte yer alacaksınız. Haftanın 3 günü ofiste, 2 günü remote çalışma imkanı sunuyoruz.`,
            requirements: `Zorunlu Gereksinimler:
• Bilgisayar Mühendisliği veya ilgili alanda lisans derecesi
• 5+ yıl profesyonel web geliştirme deneyimi
• JavaScript (React, Vue.js veya Angular) ile ileri seviye deneyim
• Node.js ve Express.js ile backend geliştirme deneyimi
• RESTful API tasarımı ve implementasyonu
• PostgreSQL veya MongoDB deneyimi
• Git ve version control sistemleri bilgisi
• Docker ve container teknolojileri deneyimi

İyi Olur:
• TypeScript kullanım deneyimi
• AWS veya Azure cloud platformları bilgisi
• GraphQL deneyimi
• CI/CD (Jenkins, GitLab CI) deneyimi
• Mikroservis mimarisi deneyimi
• Agile/Scrum metodolojisi deneyimi

Kişisel Özellikler:
• Problem çözme becerileri gelişmiş
• Takım çalışmasına yatkın
• İyi iletişim becerileri
• Kendini sürekli geliştirme isteği`,
            benefits: `• Rekabetçi maaş paketi + performans primi
• Özel sağlık sigortası (aile üyeleri dahil)
• Yemek kartı (1000 TL/ay)
• Esnek çalışma saatleri
• 3 gün ofis, 2 gün remote çalışma modeli
• Yıllık 20 gün izin + doğum günü izni
• Eğitim ve sertifikasyon desteği (yılda 10.000 TL'ye kadar)
• Teknoloji cihaz desteği (MacBook, ekstra monitör vb.)
• Spor salonu üyeliği
• Kahve, atıştırmalık ve meyve sınırsız
• Sosyal aktiviteler ve ekip etkinlikleri
• Hızlı kariyer gelişimi fırsatları
• Modern ve ergonomik ofis ortamı`
        },
        {
            title: 'Product Manager (SaaS)',
            location: 'İstanbul, Türkiye',
            employment_type: 'full-time',
            experience_level: 'mid',
            salary_min: '30000',
            salary_max: '45000',
            description: `SaaS platformumuzun gelişiminde stratejik rol oynayacak bir Product Manager arıyoruz.

Sorumluluklar:
• Ürün vizyonunu ve roadmap'ini oluşturmak ve yönetmek
• Müşteri ihtiyaçlarını analiz etmek ve ürün gereksinimlerine dönüştürmek
• Cross-functional ekiplerle (engineering, design, marketing) koordineli çalışmak
• Data-driven kararlar almak ve A/B testleri yönetmek
• Sprint planlamalarına katılmak ve user story'leri yazmak
• Ürün metriklerini takip etmek ve raporlamak
• Rakip analizi yapmak ve market trendlerini takip etmek

Çalışma Stili:
Modern product management araçları kullanarak, agile metodoloji ile çalışıyoruz. Ürün geliştirme sürecinde hem stratejik hem operasyonel rol alacaksınız.`,
            requirements: `Zorunlu:
• 3-5 yıl product management deneyimi (tercihen SaaS ürünlerinde)
• Üniversite veya Yüksek Lisans mezunu
• Güçlü analitik düşünme ve problem çözme becerileri
• SQL bilgisi ve veri analizi deneyimi
• Agile/Scrum metodolojisi deneyimi
• Mükemmel iletişim ve sunum becerileri
• İngilizce ileri seviye (yazılı ve sözlü)

Artı:
• B2B SaaS deneyimi
• API ürünleri deneyimi
• UX/UI prensipleri bilgisi
• Google Analytics, Mixpanel, Amplitude gibi araçları kullanma deneyimi
• Technical background (developer olarak çalışmış olmak)
• Startup deneyimi

Araçlar:
• Jira, Confluence, Miro
• Figma veya Sketch
• Analytics platformları`,
            benefits: `• Üst seviye maaş + hisse senedi opsiyonu
• Özel sağlık sigortası (premium paket)
• Esnek çalışma + tam remote opsiyon
• Konferans ve eğitim bütçesi (15.000 TL/yıl)
• Son model MacBook Pro + aksesuar desteği
• Kitap ve abonelik bütçesi
• Profesyonel gelişim mentorluğu
• Strateji toplantıları için şehir dışı retreat'ler
• Şirket içi product talks ve workshop'lar
• Ürün kararlarında yüksek özerklik
• Hızlı kariyer gelişimi ve C-level'a yükselme fırsatı`
        },
        {
            title: 'UI/UX Designer',
            location: 'Ankara, Türkiye',
            employment_type: 'full-time',
            experience_level: 'mid',
            salary_min: '18000',
            salary_max: '28000',
            description: `Kullanıcı deneyimini merkeze alan, yenilikçi tasarımlar yapacak bir UI/UX Designer arıyoruz.

Sorumluluklar:
• Kullanıcı araştırmaları yapmak ve persona'lar oluşturmak
• Wireframe, mockup ve high-fidelity prototipler hazırlamak
• User journey mapping ve information architecture oluşturmak
• Usability testleri planlamak ve yürütmek
• Design system oluşturmak ve sürdürmek
• Geliştiricilerle yakın çalışarak design handoff sürecini yönetmek
• A/B test tasarımları hazırlamak
• Mobil ve web tasarımları için responsive çözümler üretmek

Çalışma Ortamı:
Design-first yaklaşımla çalışan, kullanıcı odaklı bir ekipte yer alacaksınız. Tasarımlarınız milyonlarca kullanıcıya ulaşacak.`,
            requirements: `Zorunlu:
• 3+ yıl UI/UX design deneyimi
• Güzel Sanatlar, Grafik Tasarım veya ilgili alanda eğitim
• Figma konusunda ileri seviye bilgi
• Adobe Creative Suite (XD, Photoshop, Illustrator) deneyimi
• User research metodolojileri bilgisi
• Responsive ve mobile-first design anlayışı
• Portfolio (mutlaka eklenmelidir)
• Design thinking ve human-centered design bilgisi

İyi Olur:
• Animation ve micro-interaction tasarımı
• HTML/CSS temel bilgisi
• Design system yönetimi deneyimi
• Prototyping araçları (Principle, ProtoPie) deneyimi
• İllustrasyon becerileri
• Video editing bilgisi
• Frontend development anlayışı

Soft Skills:
• Empati ve kullanıcı odaklı düşünme
• Detail-oriented yaklaşım
• İyi iletişim ve sunum becerileri
• Feedback almaya ve vermeye açık
• Problem solving odaklı`,
            benefits: `• Rekabetçi maaş + yıllık zam garantisi
• Özel sağlık sigortası
• Yemek + ulaşım kartı
• Esnek mesai (09:00-18:00 veya 10:00-19:00)
• Cuma günleri yarım gün çalışma
• Adobe Creative Cloud tam lisans
• Design konferanslarına katılım desteği
• Online eğitim platformları (Udemy, Skillshare) erişimi
• Son model MacBook Pro + ekstra ekran
• Ergonomik çalışma ortamı ve design studio
• Müze ve sanat etkinlikleri için bilet bütçesi
• Design mentorship programı
• Ekip içi design sprint'leri ve workshop'lar
• Rahat ve yaratıcı ofis ortamı`
        }
    ];
    
    // Select random job
    const job = sampleJobs[Math.floor(Math.random() * sampleJobs.length)];
    
    // Simulate AI typing effect
    setTimeout(() => {
        // Fill form fields with animation
        const fields = [
            { id: 'title', value: job.title, delay: 100 },
            { id: 'location', value: job.location, delay: 300 },
            { id: 'employment_type', value: job.employment_type, delay: 500 },
            { id: 'experience_level', value: job.experience_level, delay: 700 },
            { id: 'salary_min', value: job.salary_min, delay: 900 },
            { id: 'salary_max', value: job.salary_max, delay: 1100 },
            { id: 'description', value: job.description, delay: 1300 },
            { id: 'requirements', value: job.requirements, delay: 1500 },
            { id: 'benefits', value: job.benefits, delay: 1700 }
        ];
        
        fields.forEach(field => {
            setTimeout(() => {
                const element = document.getElementById(field.id);
                if (element) {
                    element.value = field.value;
                    element.classList.add('ai-filling');
                    setTimeout(() => element.classList.remove('ai-filling'), 500);
                    
                    // Trigger change event for any listeners
                    element.dispatchEvent(new Event('change'));
                }
            }, field.delay);
        });
        
        // Restore button after all fields filled
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                animation: slideInRight 0.3s ease-out;
            `;
            successMsg.innerHTML = `
                <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                <div>
                    <strong>Form AI tarafından dolduruldu!</strong>
                    <p style="margin: 0; font-size: 0.875rem; opacity: 0.9;">Bilgileri kontrol edip yayınlayabilirsiniz</p>
                </div>
            `;
            document.body.appendChild(successMsg);
            
            setTimeout(() => {
                successMsg.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => successMsg.remove(), 300);
            }, 4000);
        }, 2000);
    }, 500);
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>


