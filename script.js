// Modal logic
const modal = document.getElementById('modal');
const btn = document.getElementById('addBtn');
const span = document.getElementById('close');

btn.onclick = () => modal.style.display = 'block';
span.onclick = () => modal.style.display = 'none';
window.onclick = e => { if(e.target==modal) modal.style.display='none'; }

// Countdown Sahur/Buka
const countdownEl = document.getElementById('countdown');
function updateCountdown() {
    const now = new Date();
    const target = new Date();
    target.setHours(18, 0, 0); // Contoh buka puasa jam 18:00
    let diff = target - now;
    if(diff<0){diff=0;}
    const h = Math.floor(diff/1000/60/60);
    const m = Math.floor(diff/1000/60)%60;
    const s = Math.floor(diff/1000)%60;
    countdownEl.textContent = `Countdown buka puasa: ${h}:${m}:${s}`;
}
setInterval(updateCountdown, 1000);
updateCountdown();

// Generate simple calendar (current month)
const calendarBody = document.getElementById('calendar-body');
const today = new Date();
const firstDay = new Date(today.getFullYear(), today.getMonth(),1).getDay();
const lastDate = new Date(today.getFullYear(), today.getMonth()+1,0).getDate();
let row = document.createElement('tr');
let dayCount = 0;
for(let i=0;i<firstDay;i++){ row.appendChild(document.createElement('td')); dayCount++;}
for(let d=1;d<=lastDate;d++){
    const td = document.createElement('td'); td.textContent=d; td.classList.add('day');
    row.appendChild(td); dayCount++;
    if(dayCount%7===0){ calendarBody.appendChild(row); row=document.createElement('tr');}
}
calendarBody.appendChild(row);
