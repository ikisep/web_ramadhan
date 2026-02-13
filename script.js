// Activities data from PHP
let activities = activitiesData || [];
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// Theme Management
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const body = document.body;

// Check if it's day or night (6 AM - 6 PM = day, else = night)
function isDayTime() {
    const hour = new Date().getHours();
    return hour >= 6 && hour < 18;
}

// Auto-detect theme based on time
function autoDetectTheme() {
    const savedTheme = localStorage.getItem('theme');
    const savedAutoMode = localStorage.getItem('autoTheme') === 'true';
    
    if (savedTheme && !savedAutoMode) {
        // User has manually set theme, use that
        body.classList.toggle('light-theme', savedTheme === 'light');
        updateThemeIcon(savedTheme === 'light');
    } else {
        // Auto-detect based on time
        const isDay = isDayTime();
        body.classList.toggle('light-theme', isDay);
        updateThemeIcon(isDay);
        localStorage.setItem('theme', isDay ? 'light' : 'dark');
        localStorage.setItem('autoTheme', 'true');
    }
}

// Update theme icon
function updateThemeIcon(isLight) {
    if (isLight) {
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
    } else {
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
    }
}

// Toggle theme manually
themeToggle.addEventListener('click', () => {
    const isLight = body.classList.toggle('light-theme');
    const theme = isLight ? 'light' : 'dark';
    updateThemeIcon(isLight);
    localStorage.setItem('theme', theme);
    localStorage.setItem('autoTheme', 'false'); // Disable auto mode when manually toggled
});

// Initialize theme
autoDetectTheme();

// Check theme every hour for auto-switch (only if auto mode is enabled)
setInterval(() => {
    const savedAutoMode = localStorage.getItem('autoTheme') === 'true';
    if (savedAutoMode) {
        const isDay = isDayTime();
        const currentIsLight = body.classList.contains('light-theme');
        if (isDay !== currentIsLight) {
            body.classList.toggle('light-theme', isDay);
            updateThemeIcon(isDay);
            localStorage.setItem('theme', isDay ? 'light' : 'dark');
        }
    }
}, 3600000); // Check every hour

// Modal functionality
const modal = document.getElementById('modal');
const addBtn = document.getElementById('addBtn');
const closeBtn = document.getElementById('close');

addBtn.addEventListener('click', () => {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    // Prevent body scroll on mobile
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
});

closeBtn.addEventListener('click', () => {
    closeModal();
});

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.body.style.position = '';
    document.body.style.width = '';
}

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display === 'block') {
        closeModal();
    }
});

// Prayer Times
let prayerTimes = {
    subuh: '04:30',
    dzuhur: '12:00',
    ashar: '15:15',
    maghrib: '18:00',
    isya: '19:15'
};

// Load prayer times from API
async function loadPrayerTimes() {
    try {
        const response = await fetch('get_prayer_times.php');
        const data = await response.json();
        
        if (data.success && data.prayers) {
            prayerTimes = data.prayers;
            
            // Update UI
            document.getElementById('subuh').textContent = prayerTimes.subuh;
            document.getElementById('dzuhur').textContent = prayerTimes.dzuhur;
            document.getElementById('ashar').textContent = prayerTimes.ashar;
            document.getElementById('maghrib').textContent = prayerTimes.maghrib;
            document.getElementById('isya').textContent = prayerTimes.isya;
            
            // Update active prayer
            updateActivePrayer();
        }
    } catch (error) {
        console.error('Error loading prayer times:', error);
        // Use default times
        document.getElementById('subuh').textContent = prayerTimes.subuh;
        document.getElementById('dzuhur').textContent = prayerTimes.dzuhur;
        document.getElementById('ashar').textContent = prayerTimes.ashar;
        document.getElementById('maghrib').textContent = prayerTimes.maghrib;
        document.getElementById('isya').textContent = prayerTimes.isya;
    }
}

// Update active prayer (highlight current prayer time)
function updateActivePrayer() {
    const now = new Date();
    const currentTime = now.getHours() * 60 + now.getMinutes();
    
    const prayers = [
        { name: 'subuh', time: prayerTimes.subuh },
        { name: 'dzuhur', time: prayerTimes.dzuhur },
        { name: 'ashar', time: prayerTimes.ashar },
        { name: 'maghrib', time: prayerTimes.maghrib },
        { name: 'isya', time: prayerTimes.isya }
    ];
    
    // Remove active class from all
    document.querySelectorAll('.prayer-item').forEach(item => {
        item.classList.remove('active-prayer');
    });
    
    // Find next prayer (or current if we're between prayers)
    let nextPrayer = null;
    
    for (let i = 0; i < prayers.length; i++) {
        const [hours, minutes] = prayers[i].time.split(':').map(Number);
        const prayerTime = hours * 60 + minutes;
        
        if (currentTime < prayerTime) {
            nextPrayer = prayers[i];
            break;
        }
    }
    
    // If no next prayer found (after Isya), use Subuh for tomorrow
    if (!nextPrayer) {
        nextPrayer = prayers[0]; // Subuh
    }
    
    // Highlight the next prayer
    const prayerItem = document.getElementById(nextPrayer.name).closest('.prayer-item');
    if (prayerItem) {
        prayerItem.classList.add('active-prayer');
    }
}

// Countdown Timer
const countdownEl = document.getElementById('countdown');
let isNotificationEnabled = true;

function updateCountdown() {
    if (!isNotificationEnabled) {
        countdownEl.textContent = '--:--:--';
        return;
    }

    const now = new Date();
    const target = new Date();
    
    // Get Maghrib time from prayer times
    const [maghribHours, maghribMinutes] = prayerTimes.maghrib.split(':').map(Number);
    target.setHours(maghribHours, maghribMinutes, 0, 0);
    
    // If Maghrib time has passed today, set for tomorrow
    if (target < now) {
        target.setDate(target.getDate() + 1);
    }
    
    const diff = target - now;
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    countdownEl.textContent = 
        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

// Load prayer times on page load
loadPrayerTimes();
setInterval(updateActivePrayer, 60000); // Update every minute

// Toggle notification
const notificationToggle = document.getElementById('notificationToggle');
notificationToggle.addEventListener('change', (e) => {
    isNotificationEnabled = e.target.checked;
    updateCountdown();
});

setInterval(updateCountdown, 1000);
updateCountdown();

// Reload prayer times daily
setInterval(() => {
    const now = new Date();
    if (now.getHours() === 0 && now.getMinutes() === 0) {
        loadPrayerTimes();
    }
}, 60000); // Check every minute

// Calendar functionality
const calendarBody = document.getElementById('calendar-body');
const monthYearEl = document.getElementById('calendar-month-year');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');

const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

function renderCalendar() {
    calendarBody.innerHTML = '';
    
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const today = new Date();
    
    monthYearEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
    
    // Create header row
    const headerRow = document.createElement('tr');
    ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
        const th = document.createElement('th');
        th.textContent = day;
        headerRow.appendChild(th);
    });
    
    let date = 1;
    let row = document.createElement('tr');
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        const td = document.createElement('td');
        td.classList.add('other-month');
        row.appendChild(td);
    }
    
    // Days of the month
    for (let i = firstDay; i < 7; i++) {
        const td = createDayCell(date, currentMonth, currentYear, today);
        row.appendChild(td);
        date++;
    }
    calendarBody.appendChild(row);
    
    // Remaining weeks
    while (date <= daysInMonth) {
        row = document.createElement('tr');
        for (let i = 0; i < 7 && date <= daysInMonth; i++) {
            const td = createDayCell(date, currentMonth, currentYear, today);
            row.appendChild(td);
            date++;
        }
        calendarBody.appendChild(row);
    }
}

function createDayCell(day, month, year, today) {
    const td = document.createElement('td');
    const dayNumber = document.createElement('span');
    dayNumber.className = 'calendar-day-number';
    dayNumber.textContent = day;
    td.appendChild(dayNumber);
    
    // Check if today
    if (day === today.getDate() && 
        month === today.getMonth() && 
        year === today.getFullYear()) {
        td.classList.add('today');
    }
    
    // Add activities for this date
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const dayActivities = activities.filter(act => act.activity_date === dateStr);
    
    dayActivities.forEach(activity => {
        const label = document.createElement('span');
        label.className = `activity-label ${activity.category}`;
        label.textContent = activity.title;
        label.title = `${activity.title} - ${activity.activity_time}`;
        td.appendChild(label);
    });
    
    // Click to add activity (touch and click support)
    const handleDateClick = () => {
        const dateInput = document.querySelector('input[name="activity_date"]');
        if (dateInput) {
            dateInput.value = dateStr;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
        }
    };
    
    td.addEventListener('click', handleDateClick);
    td.addEventListener('touchend', (e) => {
        e.preventDefault();
        handleDateClick();
    });
    
    return td;
}

prevMonthBtn.addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
});

nextMonthBtn.addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
});

// Initialize calendar
renderCalendar();

// Search functionality
const searchInput = document.getElementById('search');
searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    // Filter activities and re-render calendar
    renderCalendar();
});

// Filter functionality
const filterSelect = document.getElementById('filter');
filterSelect.addEventListener('change', (e) => {
    const filterValue = e.target.value;
    if (filterValue === 'all') {
        activities = activitiesData || [];
    } else {
        activities = (activitiesData || []).filter(act => act.category === filterValue);
    }
    renderCalendar();
});

// Save Reflection
const saveReflectionBtn = document.getElementById('saveReflection');
const reflectionTextarea = document.getElementById('reflectionText');

saveReflectionBtn.addEventListener('click', async () => {
    const content = reflectionTextarea.value;
    const today = new Date().toISOString().split('T')[0];
    
    try {
        const response = await fetch('save_reflection.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `content=${encodeURIComponent(content)}&date=${today}`
        });
        
        if (response.ok) {
            saveReflectionBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
            saveReflectionBtn.style.background = '#4CAF50';
            setTimeout(() => {
                saveReflectionBtn.innerHTML = '<i class="fas fa-save"></i> Save Reflection';
                saveReflectionBtn.style.background = '';
            }, 2000);
        }
    } catch (error) {
        console.error('Error saving reflection:', error);
        alert('Failed to save reflection. Please try again.');
    }
});

// Photo Gallery
const addPhotoBtn = document.getElementById('addPhotoBtn');
const photoUpload = document.getElementById('photoUpload');
const galleryGrid = document.getElementById('galleryGrid');

addPhotoBtn.addEventListener('click', () => {
    photoUpload.click();
});

photoUpload.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'gallery-item';
                galleryItem.innerHTML = `
                    <img src="${event.target.result}" alt="Gallery" class="gallery-image">
                    <div class="gallery-overlay">
                        <i class="fas fa-trash delete-photo"></i>
                    </div>
                `;
                
                const deleteBtn = galleryItem.querySelector('.delete-photo');
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    galleryItem.remove();
                });
                
                galleryGrid.appendChild(galleryItem);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Delete photo functionality
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('delete-photo')) {
        e.stopPropagation();
        const galleryItem = e.target.closest('.gallery-item');
        if (galleryItem) {
            galleryItem.style.animation = 'fadeOut 0.3s';
            setTimeout(() => {
                galleryItem.remove();
            }, 300);
        }
    }
});

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.8); }
    }
`;
document.head.appendChild(style);

// Form submission
const activityForm = document.getElementById('activityForm');
activityForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(activityForm);
    
    try {
        const response = await fetch('add_activity.php', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                // Reload activities
                location.reload();
            } else {
                alert('Failed to add activity: ' + result.message);
            }
        } else {
            alert('Failed to add activity. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

// Smooth scroll animations (only on desktop for better mobile performance)
if (window.innerWidth > 768) {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reflection-panel, .gallery-panel, .calendar-section').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
} else {
    // On mobile, show elements immediately
    document.querySelectorAll('.reflection-panel, .gallery-panel, .calendar-section').forEach(el => {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
    });
}

// Prevent zoom on double tap (iOS)
let lastTouchEnd = 0;
document.addEventListener('touchend', (e) => {
    const now = Date.now();
    if (now - lastTouchEnd <= 300) {
        e.preventDefault();
    }
    lastTouchEnd = now;
}, false);

// Optimize touch events for calendar
const calendarTable = document.querySelector('.calendar-table');
if (calendarTable) {
    calendarTable.addEventListener('touchstart', (e) => {
        // Add active state for touch feedback
        if (e.target.tagName === 'TD') {
            e.target.style.backgroundColor = 'rgba(255, 255, 255, 0.15)';
        }
    }, { passive: true });
    
    calendarTable.addEventListener('touchend', (e) => {
        if (e.target.tagName === 'TD') {
            setTimeout(() => {
                e.target.style.backgroundColor = '';
            }, 200);
        }
    }, { passive: true });
}
