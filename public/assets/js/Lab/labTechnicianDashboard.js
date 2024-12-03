const calendarBody = document.getElementById('calendar-body');
const monthYearDisplay = document.getElementById('monthYear');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');

let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

const months = [
   'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
];

function generateCalendar(month, year) {
   // Clear previous content
   calendarBody.innerHTML = '';

   // Get first day and total days of the month
   const firstDay = new Date(year, month).getDay();
   const daysInMonth = new Date(year, month + 1, 0).getDate();

   // Get the last day of the previous month
   const prevMonthDays = new Date(year, month, 0).getDate();

   // Fill in the month and year in the header
   monthYearDisplay.textContent = `${months[month]} ${year}`;

   // Fill in the dates in the table
   let date = 1;
   let nextMonthDate = 1;
   for (let i = 0; i < 6; i++) {
      let row = document.createElement('tr');

      for (let j = 0; j < 7; j++) {
         let cell = document.createElement('td');

         if (i === 0 && j < firstDay) {
            cell.textContent = prevMonthDays - (firstDay - j - 1);
            cell.classList.add('inactive'); // empty space for the previous month's days
         } else if (date > daysInMonth) {
            cell.textContent = nextMonthDate;
            nextMonthDate++;
            cell.classList.add('inactive'); // empty space after the last day of the month
         } else {
            cell.textContent = date;
            date++;
         }
         row.appendChild(cell);
      }
      calendarBody.appendChild(row);
   }
}

// Navigate to the previous month
prevMonthBtn.addEventListener('click', () => {
   currentMonth--;
   if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
   }
   generateCalendar(currentMonth, currentYear);
});

// Navigate to the next month
nextMonthBtn.addEventListener('click', () => {
   currentMonth++;
   if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
   }
   generateCalendar(currentMonth, currentYear);
});

// Initial load
generateCalendar(currentMonth, currentYear);
