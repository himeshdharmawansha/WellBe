const calendarBody = document.getElementById('calendar-body');
const monthYearDisplay = document.getElementById('monthYear');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');

let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

let nextMonthSchedule = {
   month : null,
   numOfDays : 0,
   year : null
};

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
            if( date === new Date().getDate() &&
            month === new Date().getMonth() &&
            year === new Date().getFullYear()){
               cell.textContent = date;
               cell.classList.add('today');
               date++;
            }else{
               cell.textContent = date;

               //scheduling

               if (nextMonthSchedule.month === month && nextMonthSchedule.numOfDays > 0 && date <= nextMonthSchedule.numOfDays)
               {
                  let nextMonth = nextMonthSchedule.month;
                  let nextYear = nextMonthSchedule.year;
               
                  const formattedNextMonth = String(nextMonth+1).padStart(2, '0');
                  const formattedDate = String(date).padStart(2, '0');
               
                  const index = schedule.findIndex(
                     (entry) => entry.date === `${nextYear}-${formattedNextMonth}-${formattedDate}`
                  );
               
                  if (index === -1) {
                     cell.classList.add('update');
                     cell.onclick = function () {
                        const updatingDate = `${nextYear}-${formattedNextMonth}-${formattedDate}`;
                        const timeSlots = ""; // Replace with dynamic timeslots or other data
                        console.log(updatingDate);
                        openModal(updatingDate, timeSlots);
                     };
                  } else {
                     const timeslotsAvailable = schedule[index].start && schedule[index].end;
               
                     if (!timeslotsAvailable) {
                        cell.classList.add('update');
                        cell.onclick = function () {
                           const updatingDate = `${nextYear}-${formattedNextMonth}-${formattedDate}`;
                           const timeSlots = ""; // Replace with dynamic timeslots or other data
                           openModal(updatingDate, timeSlots);
                        };
                     } else {
                        cell.classList.add('scheduled');
                        
                        cell.onclick = function() {
                           const scheduledDate = `${nextYear}-${formattedNextMonth}-${formattedDate}`;
                           console.log(scheduledDate);
  
                           const exampleAppointments = [
                             { appointmentId: 1, firstName: "John", lastName: "Doe", date: "2024-11-25" },
                             { appointmentId: 2, firstName: "Jane", lastName: "Smith", date: "2024-11-26" },
                             { appointmentId: 3, firstName: "Emily", lastName: "Johnson", date: "2024-11-27" },
                         ];
  
                           showAppointments(exampleAppointments,scheduledDate);
                         };
                     }
                  }
               }
               

               if( new Date().getDate() + 14 >= date && date > new Date().getDate() &&  month === new Date().getMonth() && year === new Date().getFullYear()){


                  let thisMonth = new Date().getMonth()+1;
                  let thisYear = new Date().getFullYear();

                  const index = schedule.findIndex(entry => 
                     entry.date === `${thisYear}-${String(thisMonth).padStart(2, '0')}-${String(date).padStart(2, '0')}`
                 );

                  if(index === -1 ){
                     
                     cell.classList.add('update');

                     cell.onclick = function() {
                        //console.log("Hiiiiiiiiiiiiiiiii");
                        const updatingDate = `${thisYear}-${thisMonth}-${cell.textContent}`; // Replace with dynamic date
                        const timeSlots = ""; // Replace with dynamic timeslots or other data
                        console.log(updatingDate);
                      
                        openModal(updatingDate, timeSlots);
                      }
                  }else {
                     // Matching date found in the schedule
                     const timeslotsAvailable = schedule[index].start && schedule[index].end;
                 
                     if (!timeslotsAvailable) {
                       cell.classList.add('update');
                 
                       cell.onclick = function() {
                         const updatingDate = `${thisYear}-${thisMonth}-${cell.textContent}`;
                         const timeSlots = ""; // Replace with dynamic timeslots or other data
                 
                         openModal(updatingDate, timeSlots);
                       };
                     } else {
                       cell.classList.add('scheduled');
                 
                       cell.onclick = function() {
                         const scheduledDate = `${thisYear}-${thisMonth}-${cell.textContent}`;
                         console.log(scheduledDate);

                         const exampleAppointments = [
                           { appointmentId: 1, firstName: "John", lastName: "Doe", date: "2024-11-25" },
                           { appointmentId: 2, firstName: "Jane", lastName: "Smith", date: "2024-11-26" },
                           { appointmentId: 3, firstName: "Emily", lastName: "Johnson", date: "2024-11-27" },
                       ];

                         showAppointments(exampleAppointments,scheduledDate);
                       };
                     }
                  }
               }

               date++;
            }
         }
         row.appendChild(cell);
      }
      calendarBody.appendChild(row);
   }
   if (daysInMonth < new Date().getDate() + 14 && month === new Date().getMonth()) {
      const numOfDays = new Date().getDate() + 14;
      nextMonthSchedule.numOfDays = numOfDays - daysInMonth;
   
      nextMonthSchedule.month = (currentMonth + 1) % 12;
      if (currentMonth === 11) {
         nextMonthSchedule.year = currentYear + 1;
      } else {
         nextMonthSchedule.year = currentYear;
      }
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
