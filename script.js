// Activities data from PHP
let activities = activitiesData || [];
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// Modal functionality
const modal = document.getElementById('modal');
const addBtn = document.getElementById('addBtn');
const closeBtn = document.getElementById('close');

addBtn.addEventListener('click', () => {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Countdown Timer
const countdownEl = document.getElementById('countdown');
let iftarTime = 18; // Default 6 PM
let isNotificationEnabled = true;

function updateCountdown() {
    if (!isNotificationEnabled) {
        countdownEl.textContent = '--:--:--';
        return;
    }

    const now = new Date();
    const target = new Date();
    
    // Set iftar time (default 6 PM, adjust based on location/date)
    target.setHours(iftarTime, 0, 0, 0);
    
    // If iftar time has passed today, set for tomorrow
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

// Toggle notification
const notificationToggle = document.getElementById('notificationToggle');
notificationToggle.addEventListener('change', (e) => {
    isNotificationEnabled = e.target.checked;
    updateCountdown();
});

setInterval(updateCountdown, 1000);
updateCountdown();

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
    
    // Click to add activity
    td.addEventListener('click', () => {
        const dateInput = document.querySelector('input[name="activity_date"]');
        if (dateInput) {
            dateInput.value = dateStr;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
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

// Smooth scroll animations
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
