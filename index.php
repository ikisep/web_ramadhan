<?php
include 'db.php';

// Ambil kegiatan
$activities = $pdo->query("SELECT * FROM activities ORDER BY activity_date, activity_time")->fetchAll();

// Ambil catatan hari ini
$today = date('Y-m-d');
$reflection = $pdo->prepare("SELECT * FROM reflections WHERE reflection_date=?");
$reflection->execute([$today]);
$today_reflection = $reflection->fetch();

// Convert activities to JSON for JavaScript
$activities_json = json_encode($activities);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title>Ramadhan Planner</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="stars"></div>
<div class="mosque-silhouette"></div>
<div class="crescent-moon"></div>

<!-- Top Bar -->
<header class="top-bar">
    <div class="header-left">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <rect width="40" height="40" fill="white" rx="4"/>
                <path d="M20 10 L25 20 L20 25 L15 20 Z" fill="#4A90E2" stroke="#2E5C8A" stroke-width="2"/>
                <circle cx="20" cy="18" r="3" fill="#2E5C8A"/>
            </svg>
        </div>
        <h1>Ramadhan Planner</h1>
    </div>
    <div class="header-right">
        <div class="countdown-container">
            <span id="countdown-label">Time to Iftar:</span>
            <span id="countdown">00:23:45</span>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="notificationToggle" checked>
            <span class="toggle-slider"></span>
        </label>
    </div>
</header>

<div class="container">
    <!-- Left Panel -->
    <aside class="left-panel">
        <button id="addBtn" class="add-activity-btn">
            <i class="fas fa-plus"></i>
            <span>Add Activity</span>
        </button>
        
        <div class="filter-section">
            <label for="filter">Filter:</label>
            <select id="filter" class="filter-dropdown">
                <option value="all">All Activities</option>
                <option value="ibadah">Ibadah</option>
                <option value="sosial">Sosial</option>
                <option value="belajar">Belajar</option>
            </select>
        </div>
        
        <div class="search-section">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search" class="search-input" placeholder="Search...">
        </div>
        
        <!-- Jadwal Sholat -->
        <div class="prayer-times-section">
            <div class="prayer-header">
                <h3><i class="fas fa-mosque"></i> Jadwal Sholat</h3>
                <span class="prayer-city">Bandung</span>
            </div>
            <div class="prayer-times-list" id="prayerTimesList">
                <div class="prayer-item">
                    <span class="prayer-name">Subuh</span>
                    <span class="prayer-time" id="subuh">--:--</span>
                </div>
                <div class="prayer-item">
                    <span class="prayer-name">Dzuhur</span>
                    <span class="prayer-time" id="dzuhur">--:--</span>
                </div>
                <div class="prayer-item">
                    <span class="prayer-name">Ashar</span>
                    <span class="prayer-time" id="ashar">--:--</span>
                </div>
                <div class="prayer-item active-prayer">
                    <span class="prayer-name">Maghrib</span>
                    <span class="prayer-time" id="maghrib">--:--</span>
                </div>
                <div class="prayer-item">
                    <span class="prayer-name">Isya</span>
                    <span class="prayer-time" id="isya">--:--</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Calendar Section -->
        <div class="calendar-section">
            <div class="calendar-header">
                <button class="calendar-nav-btn" id="prevMonth">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h2 id="calendar-month-year">April 2024</h2>
                <button class="calendar-nav-btn" id="nextMonth">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="calendar-wrapper">
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- Calendar will be generated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Panels -->
        <div class="bottom-panels">
            <!-- Today's Reflection -->
            <section class="reflection-panel">
                <div class="panel-header">
                    <h3>Today's Reflection</h3>
                    <i class="fas fa-moon"></i>
                </div>
                <div class="reflection-content">
                    <textarea id="reflectionText" class="reflection-textarea" placeholder="Day 10: Feeling grateful today, enjoyed Iftar with friends. Need to focus more on Quran recitation."><?php echo $today_reflection ? htmlspecialchars($today_reflection['content']) : ''; ?></textarea>
                    <button class="save-reflection-btn" id="saveReflection">
                        <i class="fas fa-save"></i> Save Reflection
                    </button>
                </div>
            </section>

            <!-- Photo Gallery -->
            <section class="gallery-panel">
                <div class="panel-header">
                    <h3>Photo Gallery</h3>
                    <button class="add-photo-btn" id="addPhotoBtn">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="gallery-grid" id="galleryGrid">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=300&h=200&fit=crop" alt="Prayer" class="gallery-image">
                        <div class="gallery-overlay">
                            <i class="fas fa-trash delete-photo"></i>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=300&h=200&fit=crop" alt="Iftar" class="gallery-image">
                        <div class="gallery-overlay">
                            <i class="fas fa-trash delete-photo"></i>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&h=200&fit=crop" alt="Food" class="gallery-image">
                        <div class="gallery-overlay">
                            <i class="fas fa-trash delete-photo"></i>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=300&h=200&fit=crop" alt="Charity" class="gallery-image">
                        <div class="gallery-overlay">
                            <i class="fas fa-trash delete-photo"></i>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- Modal Add Activity -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" id="close">&times;</span>
        <h2>Add Activity</h2>
        <form id="activityForm" method="POST" action="add_activity.php">
            <div class="form-group">
                <label>Activity Title</label>
                <input type="text" name="title" placeholder="e.g., Quran Study" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="ibadah">Ibadah</option>
                    <option value="sosial">Sosial</option>
                    <option value="belajar">Belajar</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="activity_date" required>
            </div>
            <div class="form-group">
                <label>Time</label>
                <input type="time" name="activity_time" required>
            </div>
            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Save Activity
            </button>
        </form>
    </div>
</div>

<!-- Hidden input for photo upload -->
<input type="file" id="photoUpload" accept="image/*" style="display: none;" multiple>

<script>
    const activitiesData = <?php echo $activities_json; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
