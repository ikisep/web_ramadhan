<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
<title>Al-Quran - Ramadhan Planner</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
<style>
.quran-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    position: relative;
    z-index: 10;
}

body .stars,
body .mosque-silhouette,
body .crescent-moon,
body .sun-icon {
    z-index: 1;
    pointer-events: none;
    opacity: 0.3;
}

.quran-header,
.juz-selector,
.surah-list,
.ayah-container {
    background: var(--bg-panel) !important;
    backdrop-filter: blur(10px);
}

.quran-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--bg-panel);
    border-radius: 15px;
    border: 1px solid var(--border-color);
}

.quran-header h1 {
    font-size: 28px;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.juz-selector {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.juz-btn {
    padding: 15px;
    background: var(--bg-panel);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    position: relative;
    z-index: 5;
}

.juz-btn:hover,
.juz-btn.active {
    background: var(--primary-orange);
    border-color: var(--primary-orange);
    color: white;
    transform: translateY(-2px);
}

.surah-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.surah-card {
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    border-radius: 15px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    z-index: 5;
}

.surah-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow);
    border-color: var(--primary-orange);
}

.surah-number {
    display: inline-block;
    width: 35px;
    height: 35px;
    background: var(--primary-orange);
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 35px;
    font-weight: 600;
    margin-right: 15px;
}

.surah-name {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.surah-name-arabic {
    font-size: 18px;
    color: var(--text-muted);
    margin-bottom: 10px;
    font-family: 'Amiri', 'Arial', sans-serif;
}

.surah-info {
    display: flex;
    gap: 15px;
    font-size: 14px;
    color: var(--text-muted);
}

.ayah-container {
    background: var(--bg-panel);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    border: 1px solid var(--border-color);
    position: relative;
    z-index: 5;
}

.ayah-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
}

.ayah-number {
    background: var(--primary-green);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.ayah-arabic {
    font-size: 24px;
    line-height: 2;
    text-align: right;
    color: var(--text-primary);
    margin-bottom: 15px;
    font-family: 'Amiri', 'Arial', sans-serif;
    direction: rtl;
}

.ayah-translation {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-secondary);
    margin-bottom: 15px;
    padding: 15px;
    background: var(--bg-tertiary);
    border-radius: 10px;
}

.ayah-tafsir {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-muted);
    padding: 15px;
    background: var(--bg-tertiary);
    border-radius: 10px;
    border-left: 4px solid var(--primary-blue);
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: var(--primary-orange);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 20px;
    transition: all 0.3s;
    position: relative;
    z-index: 10;
    text-decoration: none;
}

.back-btn:hover {
    background: #ff5722;
    transform: translateX(-5px);
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

@media (max-width: 768px) {
    .juz-selector {
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 10px;
    }
    
    .juz-btn {
        padding: 12px;
        font-size: 14px;
    }
    
    .surah-list {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>
<div class="stars"></div>
<div class="mosque-silhouette"></div>
<div class="crescent-moon"></div>
<div class="sun-icon"></div>

<div class="quran-container">
    <a href="index.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    
    <div class="quran-header">
        <h1><i class="fas fa-book-quran"></i> Al-Quran 30 Juz</h1>
        <p style="color: var(--text-muted);">Bacaan Al-Quran dengan Terjemahan dan Tafsir</p>
    </div>
    
    <div class="juz-selector" id="juzSelector">
        <!-- Juz buttons will be generated by JavaScript -->
    </div>
    
    <div id="surahList" class="surah-list" style="display: none;">
        <!-- Surah list will be loaded here -->
    </div>
    
    <div id="ayahContainer" class="ayah-container" style="display: none;">
        <!-- Ayah details will be shown here -->
    </div>
</div>

<script>
// Theme management (same as main page)
const body = document.body;
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'light') {
    body.classList.add('light-theme');
}

// Load Juz selector
function loadJuzSelector() {
    const container = document.getElementById('juzSelector');
    for (let i = 1; i <= 30; i++) {
        const btn = document.createElement('button');
        btn.className = 'juz-btn';
        btn.textContent = `Juz ${i}`;
        btn.onclick = () => loadSurahsByJuz(i);
        container.appendChild(btn);
    }
}

// Load surahs by Juz
async function loadSurahsByJuz(juz) {
    // Update active button
    document.querySelectorAll('.juz-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Tampilkan daftar surat, sembunyikan ayat
    document.getElementById('surahList').style.display = 'grid';
    document.getElementById('surahList').innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Memuat surah...</div>';
    document.getElementById('ayahContainer').style.display = 'none';
    
    try {
        // Juz to surah mapping (simplified)
        const juzSurahMap = {
            1: {start: 1, end: 2}, 2: {start: 2, end: 2}, 3: {start: 2, end: 3},
            4: {start: 3, end: 4}, 5: {start: 4, end: 5}, 6: {start: 5, end: 6},
            7: {start: 6, end: 7}, 8: {start: 7, end: 8}, 9: {start: 8, end: 9},
            10: {start: 9, end: 11}, 11: {start: 11, end: 12}, 12: {start: 12, end: 13},
            13: {start: 13, end: 15}, 14: {start: 15, end: 16}, 15: {start: 16, end: 18},
            16: {start: 18, end: 20}, 17: {start: 20, end: 22}, 18: {start: 22, end: 25},
            19: {start: 25, end: 27}, 20: {start: 27, end: 29}, 21: {start: 29, end: 33},
            22: {start: 33, end: 36}, 23: {start: 36, end: 39}, 24: {start: 39, end: 41},
            25: {start: 41, end: 45}, 26: {start: 45, end: 51}, 27: {start: 51, end: 57},
            28: {start: 57, end: 66}, 29: {start: 67, end: 78}, 30: {start: 78, end: 114}
        };
        
        const range = juzSurahMap[juz];
        const response = await fetch(`https://api.alquran.cloud/v1/surah`);
        const data = await response.json();
        
        if (data.code === 200 && data.data) {
            const surahs = data.data.filter(s => s.number >= range.start && s.number <= range.end);
            displaySurahs(surahs);
        }
    } catch (error) {
        console.error('Error loading surahs:', error);
        document.getElementById('surahList').innerHTML = '<div class="loading">Error memuat data. Silakan coba lagi.</div>';
    }
}

// Display surahs
function displaySurahs(surahs) {
    const container = document.getElementById('surahList');
    container.innerHTML = surahs.map(surah => `
        <div class="surah-card" onclick="loadSurah(${surah.number})">
            <div>
                <span class="surah-number">${surah.number}</span>
                <span class="surah-name">${surah.englishName}</span>
            </div>
            <div class="surah-name-arabic">${surah.name}</div>
            <div class="surah-info">
                <span><i class="fas fa-list"></i> ${surah.numberOfAyahs} Ayat</span>
                <span><i class="fas fa-bookmark"></i> ${surah.revelationType}</span>
            </div>
        </div>
    `).join('');
}

// Load surah details - langsung masuk ke ayat
async function loadSurah(surahNumber) {
    // Sembunyikan daftar surat, tampilkan loading ayat
    document.getElementById('surahList').style.display = 'none';
    document.getElementById('ayahContainer').style.display = 'block';
    document.getElementById('ayahContainer').innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Memuat ayat...</div>';
    
    // Scroll ke atas
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    try {
        let surahData, translationData;
        
        try {
            const [surahRes, translationRes] = await Promise.all([
                fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}`),
                fetch(`https://api.alquran.cloud/v1/surah/${surahNumber}/id.indonesian`)
            ]);
            
            surahData = await surahRes.json();
            translationData = await translationRes.json();
            
            if (surahData.code === 200 && translationData.code === 200) {
                displayAyahs(surahData.data, translationData.data);
            } else {
                throw new Error('API error');
            }
        } catch (e) {
            // Fallback: use quran-api.id
            try {
                const fallbackRes = await fetch(`https://quran-api-id.vercel.app/surah/${surahNumber}`);
                const fallbackData = await fallbackRes.json();
                if (fallbackData && fallbackData.ayat) {
                    displayAyahsFallback(fallbackData);
                } else {
                    throw e;
                }
            } catch (fallbackError) {
                throw e;
            }
        }
    } catch (error) {
        console.error('Error loading surah:', error);
        document.getElementById('ayahContainer').innerHTML = '<div class="loading">Error memuat data. Silakan coba lagi nanti.</div>';
    }
}

function displayAyahsFallback(data) {
    const container = document.getElementById('ayahContainer');
    container.innerHTML = `
        <div class="ayah-header">
            <h2 style="color: var(--text-primary);">${data.nama_latin || 'Surah'}</h2>
            <button onclick="backToSurahList()" class="back-btn" style="margin: 0;">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
        ${data.ayat ? data.ayat.map(ayah => `
            <div class="ayah-item" style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span class="ayah-number">${ayah.nomor}</span>
                </div>
                <div class="ayah-arabic">${ayah.ar}</div>
                <div class="ayah-translation">
                    <strong>Terjemahan:</strong><br>
                    ${ayah.idn || 'Terjemahan tidak tersedia'}
                </div>
                <div class="ayah-tafsir">
                    <strong><i class="fas fa-info-circle"></i> Tafsir:</strong><br>
                    Ayat ini menjelaskan tentang ${data.nama_latin}. Untuk penjelasan tafsir lebih lengkap, silakan merujuk pada kitab tafsir yang terpercaya seperti Tafsir Ibnu Katsir, Tafsir Al-Muyassar, atau Tafsir Kementerian Agama RI.
                </div>
            </div>
        `).join('') : '<div class="loading">Data tidak tersedia</div>'}
    `;
}

// Display ayahs
function displayAyahs(surah, translation) {
    const container = document.getElementById('ayahContainer');
    container.innerHTML = `
        <div class="ayah-header">
            <h2 style="color: var(--text-primary);">${surah.englishName} - ${surah.name}</h2>
            <button onclick="backToSurahList()" class="back-btn" style="margin: 0;">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
        ${surah.ayahs.map((ayah, index) => `
            <div class="ayah-item" style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span class="ayah-number">${ayah.numberInSurah}</span>
                </div>
                <div class="ayah-arabic">${ayah.text}</div>
                <div class="ayah-translation">
                    <strong>Terjemahan:</strong><br>
                    ${translation.ayahs[index]?.text || 'Terjemahan tidak tersedia'}
                </div>
                <div class="ayah-tafsir">
                    <strong><i class="fas fa-info-circle"></i> Tafsir:</strong><br>
                    Ayat ini menjelaskan tentang ${surah.englishName}. Untuk penjelasan lebih lengkap, silakan merujuk pada kitab tafsir yang terpercaya.
                </div>
            </div>
        `).join('')}
    `;
}

function backToSurahList() {
    // Kembali ke daftar surat dari juz yang dipilih
    document.getElementById('surahList').style.display = 'grid';
    document.getElementById('ayahContainer').style.display = 'none';
    // Scroll ke atas untuk melihat daftar surat
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initialize
loadJuzSelector();
</script>
</body>
</html>

