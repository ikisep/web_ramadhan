<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
<title>Hadits - Ramadhan Planner</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
<style>
.hadits-container {
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

.hadits-header,
.kitab-selector,
.hadits-list,
.hadits-card {
    background: var(--bg-panel) !important;
    backdrop-filter: blur(10px);
}

.hadits-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--bg-panel);
    border-radius: 15px;
    border: 1px solid var(--border-color);
}

.hadits-header h1 {
    font-size: 28px;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.kitab-selector {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.kitab-btn {
    padding: 20px;
    background: var(--bg-panel);
    border: 2px solid var(--border-color);
    border-radius: 15px;
    color: var(--text-primary);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    position: relative;
    z-index: 5;
}

.kitab-btn:hover,
.kitab-btn.active {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: white;
    transform: translateY(-2px);
}

.kitab-btn i {
    display: block;
    font-size: 32px;
    margin-bottom: 10px;
}

.hadits-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.hadits-card {
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    border-radius: 15px;
    padding: 25px;
    transition: all 0.3s;
    position: relative;
    z-index: 5;
}

.hadits-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
    border-color: var(--primary-green);
}

.hadits-number {
    display: inline-block;
    background: var(--primary-green);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
}

.hadits-arabic {
    font-size: 20px;
    line-height: 2;
    text-align: right;
    color: var(--text-primary);
    margin-bottom: 15px;
    font-family: 'Amiri', 'Arial', sans-serif;
    direction: rtl;
    padding: 15px;
    background: var(--bg-tertiary);
    border-radius: 10px;
}

.hadits-translation {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-secondary);
    margin-bottom: 15px;
    padding: 15px;
    background: var(--bg-tertiary);
    border-radius: 10px;
}

.hadits-narrator {
    font-size: 14px;
    color: var(--text-muted);
    padding: 10px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    border-left: 4px solid var(--primary-blue);
}

.search-hadits {
    margin-bottom: 20px;
}

.search-hadits input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 16px;
}

.search-hadits .search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: var(--primary-green);
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
    background: #45a049;
    transform: translateX(-5px);
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

@media (max-width: 768px) {
    .kitab-selector {
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

<div class="hadits-container">
    <a href="index.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    
    <div class="hadits-header">
        <h1><i class="fas fa-book"></i> Kumpulan Hadits</h1>
        <p style="color: var(--text-muted);">Hadits-hadits Shahih dengan Terjemahan</p>
    </div>
    
    <div class="kitab-selector" id="kitabSelector">
        <button class="kitab-btn" onclick="loadHadits('bukhari')">
            <i class="fas fa-book-open"></i>
            Shahih Bukhari
        </button>
        <button class="kitab-btn" onclick="loadHadits('muslim')">
            <i class="fas fa-book-open"></i>
            Shahih Muslim
        </button>
        <button class="kitab-btn" onclick="loadHadits('tirmidzi')">
            <i class="fas fa-book-open"></i>
            Sunan Tirmidzi
        </button>
        <button class="kitab-btn" onclick="loadHadits('ramadan')">
            <i class="fas fa-moon"></i>
            Hadits Ramadhan
        </button>
    </div>
    
    <div class="search-hadits" style="position: relative;">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="searchHadits" placeholder="Cari hadits..." onkeyup="searchHadits()">
    </div>
    
    <div id="haditsList" class="hadits-list">
        <!-- Hadits will be loaded here -->
    </div>
</div>

<script>
// Theme management
const body = document.body;
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'light') {
    body.classList.add('light-theme');
}

// Sample Hadits data
const haditsData = {
    bukhari: [
        {
            number: 1,
            arabic: "إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ",
            translation: "Sesungguhnya setiap amal perbuatan tergantung pada niatnya",
            narrator: "Diriwayatkan dari Umar bin Al-Khattab"
        },
        {
            number: 2,
            arabic: "مِنْ حُسْنِ إِسْلاَمِ الْمَرْءِ تَرْكُهُ مَا لاَ يَعْنِيهِ",
            translation: "Di antara kebaikan Islam seseorang adalah meninggalkan hal yang tidak bermanfaat baginya",
            narrator: "Diriwayatkan dari Abu Hurairah"
        }
    ],
    muslim: [
        {
            number: 1,
            arabic: "الْمُسْلِمُ مَنْ سَلِمَ الْمُسْلِمُونَ مِنْ لِسَانِهِ وَيَدِهِ",
            translation: "Seorang muslim adalah orang yang kaum muslimin selamat dari lisan dan tangannya",
            narrator: "Diriwayatkan dari Abdullah bin Amr"
        }
    ],
    tirmidzi: [
        {
            number: 1,
            arabic: "مَنْ سَنَّ فِي الإِسْلاَمِ سُنَّةً حَسَنَةً",
            translation: "Barangsiapa yang memulai suatu kebiasaan baik dalam Islam",
            narrator: "Diriwayatkan dari Jarir bin Abdullah"
        }
    ],
    ramadan: [
        {
            number: 1,
            arabic: "إِذَا دَخَلَ رَمَضَانُ فُتِّحَتْ أَبْوَابُ الْجَنَّةِ وَغُلِّقَتْ أَبْوَابُ النَّارِ",
            translation: "Apabila bulan Ramadhan tiba, pintu-pintu surga dibuka dan pintu-pintu neraka ditutup",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Bukhari"
        },
        {
            number: 2,
            arabic: "مَنْ صَامَ رَمَضَانَ إِيمَانًا وَاحْتِسَابًا غُفِرَ لَهُ مَا تَقَدَّمَ مِنْ ذَنْبِهِ",
            translation: "Barangsiapa yang berpuasa Ramadhan dengan penuh keimanan dan mengharap pahala, maka akan diampuni dosa-dosanya yang telah lalu",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Bukhari"
        },
        {
            number: 3,
            arabic: "الصَّوْمُ جُنَّةٌ فَلاَ يَرْفُثْ وَلاَ يَجْهَلْ",
            translation: "Puasa adalah perisai, maka janganlah berkata kotor dan janganlah berbuat bodoh",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Bukhari"
        },
        {
            number: 4,
            arabic: "لِلصَّائِمِ فَرْحَتَانِ فَرْحَةٌ عِنْدَ فِطْرِهِ وَفَرْحَةٌ عِنْدَ لِقَاءِ رَبِّهِ",
            translation: "Bagi orang yang berpuasa ada dua kegembiraan: kegembiraan ketika berbuka dan kegembiraan ketika bertemu dengan Tuhannya",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Bukhari"
        },
        {
            number: 5,
            arabic: "مَنْ لَمْ يَدَعْ قَوْلَ الزُّورِ وَالْعَمَلَ بِهِ فَلَيْسَ لِلَّهِ حَاجَةٌ فِي أَنْ يَدَعَ طَعَامَهُ وَشَرَابَهُ",
            translation: "Barangsiapa yang tidak meninggalkan perkataan dusta dan mengamalkannya, maka Allah tidak butuh terhadap puasanya (dari makan dan minum)",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Bukhari"
        },
        {
            number: 6,
            arabic: "مَنْ قَامَ رَمَضَانَ إِيمَانًا وَاحْتِسَابًا غُفِرَ لَهُ مَا تَقَدَّمَ مِنْ ذَنْبِهِ",
            translation: "Barangsiapa yang shalat malam di bulan Ramadhan dengan penuh keimanan dan mengharap pahala, maka akan diampuni dosa-dosanya yang telah lalu",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Bukhari"
        },
        {
            number: 7,
            arabic: "ثَلاَثَةٌ لاَ تُرَدُّ دَعْوَتُهُمُ الصَّائِمُ حَتَّى يُفْطِرَ",
            translation: "Tiga orang yang doanya tidak ditolak: doa orang yang berpuasa sampai ia berbuka",
            narrator: "Diriwayatkan dari Abu Hurairah - Sunan Tirmidzi"
        },
        {
            number: 8,
            arabic: "إِذَا كَانَ أَوَّلُ لَيْلَةٍ مِنْ شَهْرِ رَمَضَانَ صُفِّدَتِ الشَّيَاطِينُ",
            translation: "Apabila malam pertama bulan Ramadhan tiba, setan-setan dibelenggu",
            narrator: "Diriwayatkan dari Abu Hurairah - Shahih Muslim"
        }
    ]
};

let currentHadits = [];
let currentKitab = '';

function loadHadits(kitab) {
    currentKitab = kitab;
    currentHadits = haditsData[kitab] || [];
    
    // Update active button
    document.querySelectorAll('.kitab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    displayHadits(currentHadits);
}

function displayHadits(hadits) {
    const container = document.getElementById('haditsList');
    if (hadits.length === 0) {
        container.innerHTML = '<div class="loading">Tidak ada hadits ditemukan.</div>';
        return;
    }
    
    container.innerHTML = hadits.map(hadits => `
        <div class="hadits-card">
            <span class="hadits-number">Hadits #${hadits.number}</span>
            <div class="hadits-arabic">${hadits.arabic}</div>
            <div class="hadits-translation">
                <strong>Terjemahan:</strong><br>
                ${hadits.translation}
            </div>
            <div class="hadits-narrator">
                <i class="fas fa-user"></i> ${hadits.narrator}
            </div>
        </div>
    `).join('');
}

function searchHadits() {
    const searchTerm = document.getElementById('searchHadits').value.toLowerCase();
    if (!searchTerm) {
        displayHadits(currentHadits);
        return;
    }
    
    const filtered = currentHadits.filter(hadits => 
        hadits.translation.toLowerCase().includes(searchTerm) ||
        hadits.arabic.includes(searchTerm) ||
        hadits.narrator.toLowerCase().includes(searchTerm)
    );
    
    displayHadits(filtered);
}

// Load default (Ramadan hadits)
loadHadits('ramadan');
</script>
</body>
</html>

