<?php
include 'db.php';

// Ambil kegiatan
$activities = $pdo->query("SELECT * FROM activities ORDER BY activity_date, activity_time")->fetchAll();

// Ambil catatan hari ini
$today = date('Y-m-d');
$reflection = $pdo->prepare("SELECT * FROM reflections WHERE reflection_date=?");
$reflection->execute([$today]);
$today_reflection = $reflection->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ramadhan Planner</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🌙 Ramadhan Planner</h1>
    <div id="countdown">Countdown sahur/buka puasa: --:--:--</div>
</header>

<div class="container">
    <aside>
        <button id="addBtn">+ Add Activity</button>
        <input type="text" id="search" placeholder="Search...">
        <select id="filter">
            <option value="">All Categories</option>
            <option value="ibadah">Ibadah</option>
            <option value="sosial">Sosial</option>
            <option value="belajar">Belajar</option>
        </select>
    </aside>

    <main>
        <div id="calendar">
            <table>
                <thead>
                    <tr>
                        <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    <!-- JS bakal generate calendar -->
                </tbody>
            </table>
        </div>

        <section id="reflection">
            <h2>Today's Reflection</h2>
            <p><?php echo $today_reflection ? $today_reflection['content'] : 'Belum ada catatan hari ini.'; ?></p>
        </section>

        <section id="gallery">
            <h2>Photo Gallery</h2>
            <div class="cards">
                <div class="card">🍽️ Buka Puasa</div>
                <div class="card">📖 Tadarus</div>
                <div class="card">🤝 Santunan</div>
            </div>
        </section>
    </main>
</div>

<!-- Modal Add Activity -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span id="close">&times;</span>
        <h2>Add Activity</h2>
        <form id="activityForm" method="POST" action="add_activity.php">
            <input type="text" name="title" placeholder="Activity title" required>
            <select name="category" required>
                <option value="ibadah">Ibadah</option>
                <option value="sosial">Sosial</option>
                <option value="belajar">Belajar</option>
            </select>
            <input type="date" name="activity_date" required>
            <input type="time" name="activity_time" required>
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
