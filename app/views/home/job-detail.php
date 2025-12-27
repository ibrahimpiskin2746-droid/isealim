<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<style>
    body {
        margin: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .job-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .job-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .job-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    }

    .job-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem;
        color: white;
    }

    .job-hero-top {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .company-icon {
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 900;
        color: #667eea;
        flex-shrink: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .job-title-area h1 {
        font-size: 2.8rem;
        font-weight: 900;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .company-title {
        font-size: 1.4rem;
        opacity: 0.95;
        font-weight: 600;
    }

    .job-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .badge {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 600;
    }

    .job-body {
        padding: 3.5rem;
    }

    .section {
        margin-bottom: 3.5rem;
    }

    .section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title .emoji {
        font-size: 2.2rem;
    }

    .section-text {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #4a5568;
    }

    .list {
        list-style: none;
        padding: 0;
    }

    .list li {
        padding: 1rem 0;
        padding-left: 2.5rem;
        position: relative;
        font-size: 1.1rem;
        line-height: 1.7;
        color: #2d3748;
        border-bottom: 1px solid #e2e8f0;
    }

    .list li:last-child {
        border-bottom: none;
    }

    .list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #667eea;
        font-weight: 900;
        font-size: 1.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .stat-box {
        background: #f7fafc;
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        border: 2px solid #e2e8f0;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #718096;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .apply-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem;
        border-radius: 20px;
        text-align: center;
        color: white;
        margin-top: 3rem;
    }

    .apply-section h2 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }

    .apply-section p {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }

    .apply-btn {
        display: inline-block;
        padding: 1.2rem 3rem;
        background: white;
        color: #667eea;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1.2rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .apply-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }

    /* AI Section Styles */
    .ai-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem;
        border-radius: 20px;
        margin-top: 3rem;
        color: white;
    }

    .ai-section h2 {
        font-size: 2.2rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ai-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .ai-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .ai-card:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-5px);
    }

    .ai-card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .ai-card h3 {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .ai-card p {
        font-size: 1rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    .ai-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
    }

    .ai-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: white;
        color: #667eea;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        border: none;
        font-size: 1.05rem;
    }

    .ai-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
    }

    .ai-btn.secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid white;
    }

    .ai-btn.secondary:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .match-score {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1.1rem;
        margin-top: 1rem;
    }

    /* AI Modal Styles */
    .ai-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .ai-modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        max-width: 600px;
        width: 100%;
        max-height: 80vh;
        overflow-y: auto;
        padding: 2.5rem;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: #666;
        line-height: 1;
        padding: 0.5rem;
    }

    .modal-content h3 {
        color: #667eea;
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
    }

    .chat-container {
        min-height: 300px;
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .chat-message {
        margin-bottom: 1rem;
        padding: 1rem;
        border-radius: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-message.user {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-left: 20%;
    }

    .chat-message.ai {
        background: white;
        border: 2px solid #e0e0e0;
        margin-right: 20%;
    }

    .chat-input-area {
        display: flex;
        gap: 1rem;
    }

    .chat-input {
        flex: 1;
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s;
    }

    .chat-input:focus {
        border-color: #667eea;
    }

    .send-btn {
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .send-btn:hover {
        transform: scale(1.05);
    }

    .loading {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .loading::after {
        content: '...';
        animation: dots 1.5s infinite;
    }

    @keyframes dots {
        0%, 20% { content: '.'; }
        40% { content: '..'; }
        60%, 100% { content: '...'; }
    }

    @media (max-width: 768px) {
        .job-hero {
            padding: 2rem;
        }

        .job-hero-top {
            flex-direction: column;
            text-align: center;
        }

        .company-icon {
            margin: 0 auto;
        }

        .job-title-area h1 {
            font-size: 2rem;
        }

        .job-body {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="job-page">
    <div class="job-container">
        <a href="<?= url('jobs') ?>" class="back-link">
            ← Tüm İlanlara Dön
        </a>

        <div class="job-card">
            <!-- Hero Section -->
            <div class="job-hero">
                <div class="job-hero-top">
                    <div class="company-icon">
                        <?= strtoupper(substr($job['company_name'] ?? 'İ', 0, 1)) ?>
                    </div>
                    <div class="job-title-area">
                        <h1><?= htmlspecialchars($job['title'] ?? 'İş İlanı') ?></h1>
                        <div class="company-title"><?= htmlspecialchars($job['company_name'] ?? 'Şirket Adı') ?></div>
                    </div>
                </div>

                <div class="job-badges">
                    <div class="badge">
                        📍 <?= htmlspecialchars($job['location'] ?? 'Lokasyon') ?>
                    </div>
                    <div class="badge">
                        💼 <?= isset($job['employment_type']) && isset(EMPLOYMENT_TYPES[$job['employment_type']]) ? EMPLOYMENT_TYPES[$job['employment_type']] : 'Tam Zamanlı' ?>
                    </div>
                    <div class="badge">
                        ⏱️ <?= isset($job['experience_level']) && isset(EXPERIENCE_LEVELS[$job['experience_level']]) ? EXPERIENCE_LEVELS[$job['experience_level']] : 'Orta Seviye' ?>
                    </div>
                    <?php if (!empty($job['salary_min']) && !empty($job['salary_max'])): ?>
                    <div class="badge">
                        💰 ₺<?= number_format($job['salary_min']) ?> - ₺<?= number_format($job['salary_max']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Body Section -->
            <div class="job-body">
                <!-- İstatistikler -->
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-value"><?= $job['view_count'] ?? 0 ?></div>
                        <div class="stat-label">Görüntülenme</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value"><?= $job['application_count'] ?? 0 ?></div>
                        <div class="stat-label">Başvuru</div>
                    </div>
                    <?php if (!empty($job['application_deadline'])): ?>
                    <div class="stat-box">
                        <div class="stat-value"><?= date('d/m', strtotime($job['application_deadline'])) ?></div>
                        <div class="stat-label">Son Başvuru</div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- İş Tanımı -->
                <?php if (!empty($job['description'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">📋</span> İş Tanımı</h2>
                    <div class="section-text"><?= nl2br(htmlspecialchars($job['description'])) ?></div>
                </div>
                <?php endif; ?>

                <!-- Aranan Nitelikler -->
                <?php if (!empty($job['requirements'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">✅</span> Aranan Nitelikler</h2>
                    <ul class="list">
                        <?php 
                        $requirements = is_array($job['requirements']) ? $job['requirements'] : explode(',', $job['requirements']);
                        foreach ($requirements as $req): 
                            $req = trim($req);
                            if (!empty($req)):
                        ?>
                        <li><?= htmlspecialchars($req) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Sorumluluklar -->
                <?php if (!empty($job['responsibilities'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">🎯</span> Sorumluluklar</h2>
                    <ul class="list">
                        <?php 
                        $responsibilities = is_array($job['responsibilities']) ? $job['responsibilities'] : explode(',', $job['responsibilities']);
                        foreach ($responsibilities as $resp): 
                            $resp = trim($resp);
                            if (!empty($resp)):
                        ?>
                        <li><?= htmlspecialchars($resp) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Yan Haklar -->
                <?php if (!empty($job['benefits'])): ?>
                <div class="section">
                    <h2 class="section-title"><span class="emoji">🎁</span> Yan Haklar ve İmkanlar</h2>
                    <ul class="list">
                        <?php 
                        $benefits = is_array($job['benefits']) ? $job['benefits'] : explode(',', $job['benefits']);
                        foreach ($benefits as $benefit): 
                            $benefit = trim($benefit);
                            if (!empty($benefit)):
                        ?>
                        <li><?= htmlspecialchars($benefit) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- AI Powered Features Section -->
                <div class="ai-section">
                    <h2>
                        <span>🤖</span>
                        AI Destekli Kariyer Asistanı
                    </h2>
                    <p style="font-size: 1.1rem; opacity: 0.95; margin-bottom: 1rem;">
                        Yapay zeka destekli sistemimiz size özel kariyer önerileri sunuyor!
                    </p>

                    <div class="ai-cards">
                        <div class="ai-card" onclick="analyzeCV()" style="cursor: pointer;">
                            <div class="ai-card-icon">📊</div>
                            <h3>CV Uyumluluk Analizi</h3>
                            <p>Yapay zeka, CV'nizi bu pozisyonla karşılaştırır ve uyumluluk skorunuzu hesaplar.</p>
                        </div>

                        <div class="ai-card" onclick="getSmartSuggestions()" style="cursor: pointer;">
                            <div class="ai-card-icon">💡</div>
                            <h3>Akıllı Başvuru Önerileri</h3>
                            <p>CV'nizi güçlendirmek için AI tabanlı öneriler ve eksik noktaların analizi.</p>
                        </div>

                        <div class="ai-card" onclick="getInterviewTips()" style="cursor: pointer;">
                            <div class="ai-card-icon">🎯</div>
                            <h3>Mülakat Hazırlığı</h3>
                            <p>Bu pozisyon için sorulabilecek muhtemel sorular ve cevap stratejileri.</p>
                        </div>

                        <div class="ai-card" onclick="openAIChat()" style="cursor: pointer;">
                            <div class="ai-card-icon">💬</div>
                            <h3>AI Kariyer Danışmanı</h3>
                            <p>İş ilanı hakkında sorularınızı sorun, AI asistanımız anında cevaplasın.</p>
                        </div>
                    </div>

                    <div class="ai-actions">
                        <button class="ai-btn" onclick="analyzeCV()">
                            <span>📄</span>
                            CV'mi Analiz Et
                        </button>
                        <button class="ai-btn secondary" onclick="openAIChat()">
                            <span>💬</span>
                            AI ile Sohbet Et
                        </button>
                        <button class="ai-btn secondary" onclick="getInterviewTips()">
                            <span>🎤</span>
                            Mülakat İpuçları
                        </button>
                    </div>

                    <div id="aiResult" style="margin-top: 2rem;"></div>
                </div>

                <!-- Başvuru Bölümü -->
                <div class="apply-section">
                    <h2>🚀 Bu Pozisyona Başvurun</h2>
                    <p>Kariyerinizi bir sonraki seviyeye taşımak için hemen başvurun!</p>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= url('applicant/apply/' . ($job['id'] ?? '')) ?>" class="apply-btn">
                            Hemen Başvur
                        </a>
                    <?php else: ?>
                        <a href="<?= url('auth/login?redirect=job/' . ($job['id'] ?? '')) ?>" class="apply-btn">
                            Giriş Yapın ve Başvurun
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI Chat Modal -->
<div id="aiChatModal" class="ai-modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeAIChat()">&times;</button>
        <h3>🤖 AI Kariyer Danışmanı</h3>
        <div id="chatContainer" class="chat-container"></div>
        <div class="chat-input-area">
            <input 
                type="text" 
                id="chatInput" 
                class="chat-input" 
                placeholder="İş ilanı hakkında soru sorun..."
                onkeypress="if(event.key === 'Enter') sendMessage()"
            >
            <button class="send-btn" onclick="sendMessage()">Gönder</button>
        </div>
    </div>
</div>

<script>
console.log('Job Detail AI Script loaded!');

// AI Chat fonksiyonları
function openAIChat() {
    console.log('openAIChat called');
    document.getElementById('aiChatModal').classList.add('active');
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer.children.length === 0) {
        addAIMessage('Merhaba! 👋 Size bu iş ilanı hakkında nasıl yardımcı olabilirim?');
    }
}

function closeAIChat() {
    console.log('closeAIChat called');
    document.getElementById('aiChatModal').classList.remove('active');
}

function addAIMessage(message) {
    const chatContainer = document.getElementById('chatContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message ai';
    messageDiv.textContent = message;
    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function addUserMessage(message) {
    const chatContainer = document.getElementById('chatContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message user';
    messageDiv.textContent = message;
    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    addUserMessage(message);
    input.value = '';
    
    // Simüle AI yanıtı
    setTimeout(() => {
        const responses = [
            'Bu pozisyon için gereken tecrübe seviyesi ve yeteneklerinizi değerlendirdiğimde, güçlü bir aday olduğunuzu düşünüyorum. Başvuru yapmanızı öneririm! 🎯',
            'Bu iş ilanında öne çıkan beceriler: ' + getJobSkills() + '. Bu becerileri CV\'nizde vurgulamanız önemli.',
            '<?= htmlspecialchars($job['company_name'] ?? 'Bu şirket') ?> harika bir çalışma ortamı sunuyor. Şirket kültürü hakkında daha fazla araştırma yapmanızı öneririm.',
            'Mülakat için hazırlanırken bu pozisyonun gerekliliklerine özel örnekler hazırlayın. Özellikle proje deneyimlerinizi detaylandırın.',
            'Başvuru yaparken CV\'nizde bu pozisyonla alakalı başarılarınızı somut rakamlarla desteklemeyi unutmayın. Örneğin: "% artış sağladım" gibi.'
        ];
        const randomResponse = responses[Math.floor(Math.random() * responses.length)];
        addAIMessage(randomResponse);
    }, 1000);
}

function getJobSkills() {
    const title = '<?= htmlspecialchars($job['title'] ?? '') ?>';
    if (title.includes('Developer') || title.includes('Engineer')) {
        return 'Programlama dilleri, framework bilgisi, problem çözme';
    } else if (title.includes('Designer')) {
        return 'UI/UX, Figma, Adobe XD, yaratıcılık';
    } else if (title.includes('Manager')) {
        return 'Liderlik, proje yönetimi, iletişim';
    }
    return 'İlgili teknik ve soft skill\'ler';
}

// CV Analizi - AI ile gerçek analiz
function analyzeCV() {
    console.log('analyzeCV called');
    const resultDiv = document.getElementById('aiResult');
    resultDiv.innerHTML = '<div class="loading">🤖 AI analiz yapıyor, lütfen bekleyin</div>';
    
    const jobId = '<?= htmlspecialchars($job['id'] ?? '') ?>';
    const apiUrl = '/isealim/job/analyzeJobMatch';
    
    console.log('Fetching:', apiUrl);
    console.log('Job ID:', jobId);
    
    // AJAX isteği
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'job_id=' + encodeURIComponent(jobId),
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        console.log('Response OK:', response.ok);
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        
        // Clone response to read it twice
        return response.clone().text().then(text => {
            console.log('Raw response text:', text);
            return response.json();
        });
    })
    .then(data => {
        console.log('Parsed JSON data:', data);
        if (data.success && data.analysis) {
            const analysis = data.analysis;
            const isDemoMode = data.demo_mode || false;
            
            resultDiv.innerHTML = `
                <div style="background: rgba(255,255,255,0.2); padding: 2rem; border-radius: 16px; margin-top: 1rem;">
                    ${isDemoMode ? '<div style="background: rgba(255,215,0,0.3); padding: 0.5rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;">ℹ️ Demo Mode: Gerçek AI için API key yapılandırın</div>' : ''}
                    <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">📊 AI Uyumluluk Analizi</h3>
                    <div class="match-score">
                        Uyumluluk Skoru: <strong>${analysis.score}%</strong>
                    </div>
                    <div style="margin-top: 1.5rem; line-height: 1.8;">
                        ${analysis.strengths && analysis.strengths.length > 0 ? `
                            <p><strong>✅ Güçlü Yönleriniz:</strong></p>
                            <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                                ${analysis.strengths.map(s => `<li>${s}</li>`).join('')}
                            </ul>
                        ` : ''}
                        
                        ${analysis.improvements && analysis.improvements.length > 0 ? `
                            <p><strong>💡 Geliştirme Önerileri:</strong></p>
                            <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                                ${analysis.improvements.map(i => `<li>${i}</li>`).join('')}
                            </ul>
                        ` : ''}
                        
                        ${analysis.summary ? `
                            <p style="margin-top: 1rem; font-weight: 600;">
                                🎯 ${analysis.summary}
                            </p>
                        ` : ''}
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div style="background: rgba(255,100,100,0.2); padding: 1.5rem; border-radius: 12px; margin-top: 1rem; color: white;">
                    ⚠️ Analiz yapılamadı. Lütfen daha sonra tekrar deneyin.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('AI Analysis Error:', error);
        resultDiv.innerHTML = `
            <div style="background: rgba(255,100,100,0.2); padding: 1.5rem; border-radius: 12px; margin-top: 1rem; color: white;">
                <p style="margin: 0 0 0.5rem 0;">⚠️ Bağlantı hatası</p>
                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Hata: ${error.message}</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.85rem; opacity: 0.8;">
                    URL: /isealim/job/analyzeJobMatch
                </p>
            </div>
        `;
    });
}

// Mülakat İpuçları
function getInterviewTips() {
    console.log('getInterviewTips called');
    const resultDiv = document.getElementById('aiResult');
    resultDiv.innerHTML = '<div class="loading">AI mülakat tavsiyeleri hazırlanıyor</div>';
    
    setTimeout(() => {
        resultDiv.innerHTML = `
            <div style="background: rgba(255,255,255,0.2); padding: 2rem; border-radius: 16px; margin-top: 1rem;">
                <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">🎤 Mülakat Hazırlık Rehberi</h3>
                
                <div style="margin-top: 1.5rem; line-height: 1.8;">
                    <p><strong>📋 Muhtemel Sorular:</strong></p>
                    <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                        <li>Kendinizi tanıtır mısınız ve neden bu pozisyona uygun olduğunuzu düşünüyorsunuz?</li>
                        <li>En büyük teknik başarınızdan bahseder misiniz?</li>
                        <li>Takım çalışması konusunda bir örnek verebilir misiniz?</li>
                        <li>5 yıl sonra kendinizi nerede görüyorsunuz?</li>
                    </ul>
                    
                    <p><strong>💪 Hazırlık Önerileri:</strong></p>
                    <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                        <li>STAR (Durum-Görev-Aksiyon-Sonuç) metodunu kullanın</li>
                        <li>Şirket araştırması yapın ve değerlerini öğrenin</li>
                        <li>Teknik bilginizi güncel tutun</li>
                        <li>Sorularınızı hazırlayın (şirket kültürü, ekip yapısı vb.)</li>
                    </ul>
                    
                    <p><strong>🎯 İpuçları:</strong></p>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        <li>Vücut dilinize dikkat edin</li>
                        <li>Samimi ve özgün olun</li>
                        <li>Örneklerinizi somut rakamlarla destekleyin</li>
                        <li>Heyecanlı ve istekli görünün</li>
                    </ul>
                </div>
            </div>
        `;
    }, 1500);
}

// Akıllı Başvuru Önerileri
function getSmartSuggestions() {
    console.log('getSmartSuggestions called');
    const resultDiv = document.getElementById('aiResult');
    resultDiv.innerHTML = '<div class="loading">🤖 AI önerileri hazırlanıyor</div>';
    
    // Scroll to result
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    const jobTitle = '<?= htmlspecialchars($job['title'] ?? '') ?>';
    const experienceLevel = '<?= htmlspecialchars($job['experience_level'] ?? '') ?>';
    
    setTimeout(() => {
        resultDiv.innerHTML = `
            <div style="background: rgba(255,255,255,0.2); padding: 2rem; border-radius: 16px; margin-top: 1rem;">
                <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">💡 Akıllı Başvuru Önerileri</h3>
                
                <div style="margin-top: 1.5rem; line-height: 1.8;">
                    <p><strong>📝 CV İçerik Önerileri:</strong></p>
                    <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                        <li><strong>Özet Bölümü:</strong> "${jobTitle}" pozisyonu için özelleştirilmiş bir özet yazın. Temel yetkinliklerinizi ve başarılarınızı vurgulayın.</li>
                        <li><strong>Anahtar Kelimeler:</strong> İş ilanındaki teknik terimleri ve yetenekleri CV'nizde kullanın (ATS sistemleri için önemli).</li>
                        <li><strong>Ölçülebilir Başarılar:</strong> "% artış", "X projede", "Y kullanıcı" gibi somut rakamlar ekleyin.</li>
                        <li><strong>İlgili Projeler:</strong> Bu pozisyonla alakalı en iyi 3-5 projenizi öne çıkarın.</li>
                    </ul>
                    
                    <p><strong>🎯 Başvuru Stratejisi:</strong></p>
                    <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                        <li><strong>Ön Yazı:</strong> Generic değil, bu şirket ve pozisyon için özel hazırlanmış bir cover letter yazın.</li>
                        <li><strong>Portfolio/GitHub:</strong> Canlı demo linklerinizi ve kod örneklerinizi ekleyin.</li>
                        <li><strong>LinkedIn Optimizasyonu:</strong> Profilinizi bu pozisyona göre güncelleyin ve "Open to Work" açın.</li>
                        <li><strong>Referanslar:</strong> Bu pozisyonla alakalı referanslarınızı hazırlayın.</li>
                    </ul>
                    
                    <p><strong>✨ Dikkat Çekme Teknikleri:</strong></p>
                    <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                        <li><strong>Video Tanıtım:</strong> 1-2 dakikalık kısa bir tanıtım videosu hazırlayın (Loom veya benzeri).</li>
                        <li><strong>Case Study:</strong> İlgili bir sorunu nasıl çözdüğünüzü gösteren mini case study ekleyin.</li>
                        <li><strong>Sertifikalar:</strong> Bu pozisyonla alakalı güncel sertifikalarınızı vurgulayın.</li>
                        <li><strong>Kişisel Marka:</strong> Blog yazılarınız, Medium makaleleriniz varsa paylaşın.</li>
                    </ul>
                    
                    <p><strong>⚠️ Kaçınılması Gerekenler:</strong></p>
                    <ul style="margin: 0.5rem 0 1rem 1.5rem;">
                        <li>Generic "İş arıyorum" başvuruları</li>
                        <li>Yazım hataları ve formatla sorunlar</li>
                        <li>İlgisiz iş deneyimleri ve yetenekler</li>
                        <li>2 sayfadan uzun CV (özel durumlar hariç)</li>
                        <li>Güncel olmayan teknolojiler ve beceriler</li>
                    </ul>
                    
                    <p style="margin-top: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.15); border-radius: 10px; border-left: 4px solid #4CAF50;">
                        <strong>🎯 Sonraki Adım:</strong> Bu önerileri uygulayarak CV'nizi güncelleyin ve başvuru yapmadan önce bir arkadaşınıza veya mentora gösterin. 
                        İstatistikler gösteriyor ki özelleştirilmiş başvurular %70 daha fazla geri dönüş alıyor!
                    </p>
                </div>
            </div>
        `;
    }, 1200);
}

// Modal dışına tıklanınca kapat - DOM yüklendikten sonra
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, attaching event listeners');
    const modal = document.getElementById('aiChatModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAIChat();
            }
        });
        console.log('Modal event listener attached');
    } else {
        console.error('aiChatModal not found!');
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
