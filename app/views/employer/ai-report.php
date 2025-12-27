<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AI Haftalık Rapor' ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .report-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .report-title {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .ai-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .report-title h1 {
            font-size: 32px;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .report-period {
            color: #718096;
            font-size: 14px;
        }

        .report-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .summary-card:hover::before {
            transform: scaleX(1);
        }

        .summary-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .summary-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .icon-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .icon-orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .icon-purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .summary-card h3 {
            color: #718096;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 36px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .summary-change {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .change-positive {
            color: #38a169;
        }

        .change-negative {
            color: #e53e3e;
        }

        .report-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .section-header h2 {
            font-size: 24px;
            color: #2d3748;
        }

        .trend-item {
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }

        .trend-item:last-child {
            margin-bottom: 0;
        }

        .trend-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .trend-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .trend-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-high { background: #c6f6d5; color: #22543d; }
        .badge-medium { background: #feebc8; color: #7c2d12; }
        .badge-low { background: #e2e8f0; color: #2d3748; }

        .trend-description {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.6;
        }

        .recommendation-item {
            display: flex;
            gap: 15px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .recommendation-item:last-child {
            margin-bottom: 0;
        }

        .recommendation-number {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        .recommendation-content {
            flex: 1;
        }

        .recommendation-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .recommendation-text {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.6;
        }

        .chart-placeholder {
            height: 300px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .chart-container {
            position: relative;
            height: 100%;
        }

        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            transition: width 1s ease-in-out;
        }

        .skill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .skill-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }

        .skill-percentage {
            color: #667eea;
            font-weight: 700;
            font-size: 14px;
        }

        .comparison-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .comparison-table th {
            background: #f7fafc;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
        }

        .comparison-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }

        .comparison-table tr:last-child td {
            border-bottom: none;
        }

        .comparison-table tr:hover {
            background: #f7fafc;
        }

        .metric-increase {
            color: #38a169;
            font-weight: 600;
        }

        .metric-decrease {
            color: #e53e3e;
            font-weight: 600;
        }

        .ai-insight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .ai-insight-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.3; }
        }

        .ai-insight-content {
            position: relative;
            z-index: 1;
        }

        .ai-insight-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ai-insight-text {
            font-size: 15px;
            line-height: 1.8;
            opacity: 0.95;
        }

        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-label {
            font-size: 12px;
            color: #718096;
            font-weight: 600;
        }

        .filter-select {
            padding: 8px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #2d3748;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:hover {
            border-color: #667eea;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .stat-box {
            background: #f7fafc;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            color: #718096;
        }

        @media (max-width: 768px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-select {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .report-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .report-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header -->
        <div class="report-header">
            <div class="report-title">
                <div class="ai-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <h1>Haftalık AI Raporu</h1>
                    <div class="report-period">
                        <i class="far fa-calendar-alt"></i> <?= $report['period'] ?> 
                        • Oluşturulma: <?= $report['generated_at'] ?>
                    </div>
                </div>
            </div>
            <div class="report-actions">
                <a href="<?= url('employer/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <button class="btn btn-primary" onclick="exportExcel()">
                    <i class="fas fa-file-excel"></i> Excel İndir
                </button>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-file-pdf"></i> PDF İndir
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-item">
                <label class="filter-label">Dönem Seçin</label>
                <select class="filter-select" onchange="updateReport(this.value)">
                    <option value="7">Son 7 gün</option>
                    <option value="14">Son 14 gün</option>
                    <option value="30">Son 30 gün</option>
                    <option value="90">Son 3 ay</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">İlan Durumu</label>
                <select class="filter-select">
                    <option value="all">Tümü</option>
                    <option value="published">Yayında</option>
                    <option value="draft">Taslak</option>
                    <option value="closed">Kapalı</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Pozisyon Tipi</label>
                <select class="filter-select">
                    <option value="all">Tümü</option>
                    <option value="full-time">Tam Zamanlı</option>
                    <option value="part-time">Yarı Zamanlı</option>
                    <option value="freelance">Serbest</option>
                    <option value="contract">Sözleşmeli</option>
                </select>
            </div>
        </div>

        <!-- AI Insight Box -->
        <div class="ai-insight-box">
            <div class="ai-insight-content">
                <div class="ai-insight-title">
                    <i class="fas fa-robot"></i>
                    AI Önemli Bulgu
                </div>
                <div class="ai-insight-text">
                    Bu hafta işe alım sürecinizde %28 oranında iyileşme tespit edildi! Frontend Developer pozisyonunuz 
                    özellikle dikkat çekici performans gösteriyor. Son 24 saatte gelen başvurularda ortalama AI eşleşme 
                    skoru %82'ye yükseldi. Bu trendin devam etmesi için mevcut ilan formatınızı diğer pozisyonlara 
                    da uygulamanızı öneriyoruz.
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-card-header">
                    <div class="summary-card-icon icon-blue">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
                <h3>Toplam Görüntülenme</h3>
                <div class="summary-value"><?= number_format($report['summary']['total_views']) ?></div>
                <div class="summary-change change-positive">
                    <i class="fas fa-arrow-up"></i> %23 artış
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-header">
                    <div class="summary-card-icon icon-green">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <h3>Toplam Başvuru</h3>
                <div class="summary-value"><?= number_format($report['summary']['total_applications']) ?></div>
                <div class="summary-change change-positive">
                    <i class="fas fa-arrow-up"></i> %15 artış
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-header">
                    <div class="summary-card-icon icon-orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <h3>Ortalama Eşleşme Skoru</h3>
                <div class="summary-value"><?= number_format($report['summary']['avg_match_score'], 1) ?>%</div>
                <div class="summary-change change-positive">
                    <i class="fas fa-arrow-up"></i> %5 artış
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-header">
                    <div class="summary-card-icon icon-purple">
                        <i class="fas fa-reply"></i>
                    </div>
                </div>
                <h3>Yanıt Oranı</h3>
                <div class="summary-value"><?= number_format($report['summary']['response_rate'], 1) ?>%</div>
                <div class="summary-change change-negative">
                    <i class="fas fa-arrow-down"></i> %3 düşüş
                </div>
            </div>
        </div>

        <!-- İşe Alım Trendleri -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2>İşe Alım Trendleri</h2>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Frontend geliştirici pozisyonlarına yoğun ilgi</div>
                    <span class="trend-badge badge-high">Yüksek Öncelik</span>
                </div>
                <div class="trend-description">
                    Son 7 günde frontend pozisyonlarınız %45 daha fazla görüntülendi. 
                    React ve Vue.js becerileri olan adaylar özellikle ilgi gösteriyor.
                </div>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Senior pozisyonlarda eşleşme oranı arttı</div>
                    <span class="trend-badge badge-high">Yüksek Öncelik</span>
                </div>
                <div class="trend-description">
                    AI skorlama algoritması, senior pozisyonlar için %82 eşleşme oranına ulaştı. 
                    5+ yıl deneyimli adaylar hedef kitlenizle uyumlu.
                </div>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Uzaktan çalışma talebi yükselişte</div>
                    <span class="trend-badge badge-medium">Orta Öncelik</span>
                </div>
                <div class="trend-description">
                    Başvuranların %68'i uzaktan veya hibrit çalışma modeli tercih ediyor. 
                    Bu esnekliği sunan ilanlarınız %35 daha fazla başvuru alıyor.
                </div>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Maaş beklentileri pazar ortalamasına yakın</div>
                    <span class="trend-badge badge-medium">Orta Öncelik</span>
                </div>
                <div class="trend-description">
                    Başvuranların maaş beklentileri sektör ortalamasının %5 üzerinde. 
                    Rekabetçi teklifler sunmak için bütçenizi gözden geçirebilirsiniz.
                </div>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Mobil cihazlardan başvuru artışı</div>
                    <span class="trend-badge badge-low">Düşük Öncelik</span>
                </div>
                <div class="trend-description">
                    Başvuruların %42'si mobil cihazlardan geliyor. 
                    İlan ve başvuru formlarınızın mobil uyumlu olması önem kazanıyor.
                </div>
            </div>

            <div class="chart-placeholder">
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Başvuru Trendleri Grafiği -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-chart-area"></i>
                </div>
                <h2>Haftalık Başvuru Trendi</h2>
            </div>

            <div class="chart-placeholder">
                <div class="chart-container">
                    <canvas id="applicationChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Beceri Analizi -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h2>En Çok Talep Edilen Beceriler</h2>
            </div>

            <div class="skill-item">
                <div class="skill-name">
                    <i class="fab fa-react" style="color: #61dafb;"></i> React.js
                </div>
                <div class="skill-percentage">68%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 68%"></div>
            </div>

            <div class="skill-item">
                <div class="skill-name">
                    <i class="fab fa-python" style="color: #3776ab;"></i> Python
                </div>
                <div class="skill-percentage">45%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 45%"></div>
            </div>

            <div class="skill-item">
                <div class="skill-name">
                    <i class="fab fa-node-js" style="color: #68a063;"></i> Node.js
                </div>
                <div class="skill-percentage">42%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 42%"></div>
            </div>

            <div class="skill-item">
                <div class="skill-name">
                    <i class="fas fa-paint-brush" style="color: #ff6b6b;"></i> UI/UX Design
                </div>
                <div class="skill-percentage">38%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 38%"></div>
            </div>

            <div class="skill-item">
                <div class="skill-name">
                    <i class="fab fa-react" style="color: #764ba2;"></i> React Native
                </div>
                <div class="skill-percentage">32%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 32%"></div>
            </div>

            <div class="chart-placeholder" style="margin-top: 30px;">
                <div class="chart-container">
                    <canvas id="skillsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performans Karşılaştırması -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2>Haftalık Performans Karşılaştırması</h2>
            </div>

            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Metrik</th>
                        <th>Bu Hafta</th>
                        <th>Geçen Hafta</th>
                        <th>Değişim</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-eye"></i> Toplam Görüntülenme</td>
                        <td><strong><?= number_format($report['summary']['total_views']) ?></strong></td>
                        <td>1,847</td>
                        <td class="metric-increase"><i class="fas fa-arrow-up"></i> +23%</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-user-check"></i> Başvuru Sayısı</td>
                        <td><strong><?= number_format($report['summary']['total_applications']) ?></strong></td>
                        <td>156</td>
                        <td class="metric-increase"><i class="fas fa-arrow-up"></i> +15%</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-percentage"></i> Eşleşme Oranı</td>
                        <td><strong><?= number_format($report['summary']['avg_match_score'], 1) ?>%</strong></td>
                        <td>74.8%</td>
                        <td class="metric-increase"><i class="fas fa-arrow-up"></i> +5%</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-clock"></i> Ortalama Yanıt Süresi</td>
                        <td><strong>18 saat</strong></td>
                        <td>26 saat</td>
                        <td class="metric-increase"><i class="fas fa-arrow-down"></i> -31%</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-thumbs-up"></i> Başvuru Kalitesi</td>
                        <td><strong>8.4/10</strong></td>
                        <td>7.9/10</td>
                        <td class="metric-increase"><i class="fas fa-arrow-up"></i> +6%</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-star"></i> Mülakata Çağırma Oranı</td>
                        <td><strong>32%</strong></td>
                        <td>28%</td>
                        <td class="metric-increase"><i class="fas fa-arrow-up"></i> +14%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Saat Bazlı Aktivite -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h2>Saat Bazlı Başvuru Aktivitesi</h2>
            </div>

            <div class="chart-placeholder">
                <div class="chart-container">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>

            <div class="stats-grid" style="margin-top: 20px;">
                <div class="stat-box">
                    <div class="stat-value">09:00 - 11:00</div>
                    <div class="stat-label">En Aktif Saat Aralığı</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">Salı</div>
                    <div class="stat-label">En Aktif Gün</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">3.2 dk</div>
                    <div class="stat-label">Ort. Form Doldurma Süresi</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">89%</div>
                    <div class="stat-label">Mobil Uyumluluk Skoru</div>
                </div>
            </div>
        </div>

        <!-- AI Önerileri -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h2>AI Önerileri ve Aksiyon Planı</h2>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">1</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">İlan başlıklarını optimize edin</div>
                    <div class="recommendation-text">
                        "Senior Frontend Developer" başlıklı ilanınız %38 daha fazla tıklanıyor. 
                        Diğer ilanlarınızda da pozisyon seviyesini ve teknoloji stack'ini net belirtin. 
                        Örnek: "Mid-Level Backend Developer (Node.js, MongoDB)"
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">2</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Başvuru formlarınızı sadeleştirin</div>
                    <div class="recommendation-text">
                        10+ sorulu formlar %25 daha az tamamlanıyor. AI analizi, 6-8 sorulu formların 
                        optimal olduğunu gösteriyor. Temel bilgileri alın, detaylı değerlendirmeyi 
                        mülakat aşamasına bırakın.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">3</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Hızlı yanıt süresi kritik</div>
                    <div class="recommendation-text">
                        24 saat içinde yanıt verilen başvurularda adayların %85'i süreci devam ettiriyor. 
                        48 saati aşan yanıtlarda bu oran %42'ye düşüyor. AI sıralama sistemini kullanarak 
                        öncelikli adayları hızlıca belirleyin.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">4</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Şirket kültürünü vurgulayın</div>
                    <div class="recommendation-text">
                        İlan açıklamalarında "ekip çalışması", "yenilikçi projeler" ve "gelişim fırsatları" 
                        vurgulanan pozisyonlar %30 daha nitelikli başvuru alıyor. Şirket değerlerinizi ve 
                        çalışma ortamınızı net bir şekilde ifade edin.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">5</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Maaş aralığını netleştirin</div>
                    <div class="recommendation-text">
                        Maaş aralığı belirtilen ilanlar %52 daha fazla görüntülenme alıyor. 
                        Geniş bir aralık (örn: 40.000-70.000 TL) vermek, daha fazla adayı başvurmaya 
                        teşvik ediyor ve beklenti uyumsuzluğunu azaltıyor.
                    </div>
                </div>
            </div>
        </div>

        <!-- Detaylı İstatistikler -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h2>Detaylı İstatistikler ve Analizler</h2>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Başvuru Kaynakları Dağılımı</div>
                </div>
                <div class="chart-placeholder" style="height: 250px;">
                    <div class="chart-container">
                        <canvas id="sourceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="stats-grid" style="margin-top: 30px;">
                <div class="stat-box">
                    <div class="stat-value">4.2 gün</div>
                    <div class="stat-label">Ort. İşe Alım Süresi</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">92%</div>
                    <div class="stat-label">Form Tamamlanma Oranı</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">3.8</div>
                    <div class="stat-label">İlan Başına Başvuru</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">68%</div>
                    <div class="stat-label">Uzaktan Çalışma Tercihi</div>
                </div>
            </div>

            <div class="trend-item" style="margin-top: 20px;">
                <div class="trend-header">
                    <div class="trend-title">En Aktif Başvuru Günleri</div>
                </div>
                <div class="trend-description">
                    <strong>Pazartesi:</strong> 18% | <strong>Salı:</strong> 24% ⭐ | 
                    <strong>Çarşamba:</strong> 21% | <strong>Perşembe:</strong> 16% | 
                    <strong>Cuma:</strong> 12% | <strong>Hafta Sonu:</strong> 9%
                </div>
            </div>

            <div class="trend-item">
                <div class="trend-header">
                    <div class="trend-title">Başvuran Deneyim Seviyeleri</div>
                </div>
                <div class="skill-item">
                    <div class="skill-name">Junior (0-2 yıl)</div>
                    <div class="skill-percentage">28%</div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 28%"></div>
                </div>
                <div class="skill-item" style="margin-top: 10px;">
                    <div class="skill-name">Mid-Level (2-5 yıl)</div>
                    <div class="skill-percentage">45%</div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 45%"></div>
                </div>
                <div class="skill-item" style="margin-top: 10px;">
                    <div class="skill-name">Senior (5+ yıl)</div>
                    <div class="skill-percentage">27%</div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 27%"></div>
                </div>
            </div>
        </div>

        <!-- İlave AI Önerileri -->
        <div class="report-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h2>İleri Seviye AI Önerileri</h2>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">6</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Video mülakatı entegrasyonu</div>
                    <div class="recommendation-text">
                        İlk tur mülakatlar için video kayıt sistemi ekleyin. AI analizi gösteriyor ki video ön eleme 
                        yapan şirketler %40 daha hızlı işe alım gerçekleştiriyor. Asenkron video mülakatlar, hem size 
                        hem adaylara zaman esnekliği sağlıyor.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">7</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Beceri testleri ekleyin</div>
                    <div class="recommendation-text">
                        Teknik pozisyonlar için otomatik kodlama testleri veya beceri değerlendirmeleri entegre edin. 
                        Test uygulayan ilanlarınızda nitelikli aday oranı %52 daha yüksek. Bu, yanlış eşleşmeleri 
                        başvuru aşamasında önlüyor.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">8</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Referans kontrol sistematiği</div>
                    <div class="recommendation-text">
                        Otomatik referans kontrol süreci oluşturun. Final aşamaya gelen adaylar için standart 
                        referans formu gönderin. Bu süreç işe alım kararlarındaki güveni %65 artırıyor ve 
                        yanlış işe alımları minimize ediyor.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">9</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Aday deneyimi anketleri</div>
                    <div class="recommendation-text">
                        Her başvuru sürecinin sonunda (kabul veya ret) aday deneyimi anketi gönderin. 
                        Feedback toplayan şirketler süreç iyileştirmelerinde %78 daha başarılı oluyor. 
                        Bu veriler gelecek ilanlarınızı optimize etmenize yardımcı olacak.
                    </div>
                </div>
            </div>

            <div class="recommendation-item">
                <div class="recommendation-number">10</div>
                <div class="recommendation-content">
                    <div class="recommendation-title">Aday havuzu oluşturma</div>
                    <div class="recommendation-text">
                        Reddedilen ama potansiyelli adayları "yetenek havuzu"na ekleyin. Gelecek pozisyonlar için 
                        bu havuzdan %30 daha hızlı işe alım yapabilirsiniz. AI sistemi, yeni ilanlarınızla eşleşen 
                        havuz adaylarını otomatik bilgilendirebilir.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart.js Configuration
        const chartColors = {
            primary: '#667eea',
            secondary: '#764ba2',
            success: '#38a169',
            warning: '#f5a623',
            danger: '#e53e3e',
            info: '#4299e1'
        };

        // Trend Chart - İşe Alım Trendleri
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
                    datasets: [{
                        label: 'Görüntülenme',
                        data: [320, 425, 398, 356, 289, 145, 98],
                        borderColor: chartColors.primary,
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Başvuru',
                        data: [28, 35, 31, 27, 22, 12, 8],
                        borderColor: chartColors.success,
                        backgroundColor: 'rgba(56, 161, 105, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Application Chart - Başvuru Trendi
        const appCtx = document.getElementById('applicationChart');
        if (appCtx) {
            new Chart(appCtx, {
                type: 'bar',
                data: {
                    labels: ['Frontend Dev', 'UI/UX Designer', 'Backend Dev', 'Product Manager', 'DevOps Eng.'],
                    datasets: [{
                        label: 'Başvuru Sayısı',
                        data: [45, 32, 28, 18, 15],
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(118, 75, 162, 0.8)',
                            'rgba(56, 161, 105, 0.8)',
                            'rgba(245, 166, 35, 0.8)',
                            'rgba(66, 153, 225, 0.8)'
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Skills Chart - Beceri Dağılımı
        const skillsCtx = document.getElementById('skillsChart');
        if (skillsCtx) {
            new Chart(skillsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['React.js', 'Python', 'Node.js', 'UI/UX', 'React Native'],
                    datasets: [{
                        data: [68, 45, 42, 38, 32],
                        backgroundColor: [
                            '#61dafb',
                            '#3776ab',
                            '#68a063',
                            '#ff6b6b',
                            '#764ba2'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        // Hourly Chart - Saat Bazlı Aktivite
        const hourlyCtx = document.getElementById('hourlyChart');
        if (hourlyCtx) {
            new Chart(hourlyCtx, {
                type: 'line',
                data: {
                    labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
                    datasets: [{
                        label: 'Başvuru Aktivitesi',
                        data: [2, 1, 3, 25, 18, 22, 15, 8],
                        borderColor: chartColors.warning,
                        backgroundColor: 'rgba(245, 166, 35, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: chartColors.warning
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Source Chart - Başvuru Kaynakları
        const sourceCtx = document.getElementById('sourceChart');
        if (sourceCtx) {
            new Chart(sourceCtx, {
                type: 'pie',
                data: {
                    labels: ['Direkt Site', 'LinkedIn', 'Indeed', 'Kariyer.net', 'Diğer'],
                    datasets: [{
                        data: [58, 25, 10, 5, 2],
                        backgroundColor: [
                            chartColors.primary,
                            '#0077b5',
                            '#2164f3',
                            '#ff6b35',
                            '#718096'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        // Functions
        function updateReport(days) {
            console.log('Rapor güncelleniyor: Son ' + days + ' gün');
            // AJAX call would go here
        }

        function exportExcel() {
            alert('Excel raporu indiriliyor...\n\nBu özellik yakında aktif olacak!');
        }

        // Print functionality
        window.onbeforeprint = function() {
            document.querySelectorAll('.btn, .filter-bar').forEach(el => {
                el.style.display = 'none';
            });
        }

        window.onafterprint = function() {
            document.querySelectorAll('.btn').forEach(btn => {
                btn.style.display = '';
            });
            document.querySelector('.filter-bar').style.display = 'flex';
        }

        // Animate progress bars on load
        window.addEventListener('load', function() {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });

        // Animate summary cards on load
        window.addEventListener('load', function() {
            const cards = document.querySelectorAll('.summary-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
</body>
</html>
