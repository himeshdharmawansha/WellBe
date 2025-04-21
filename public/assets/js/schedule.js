(() => {
  console.log("i am schedule");

  // Get modal element and close button
  const modal = document.getElementById("popupForm");
  const closeButton = document.getElementsByClassName("close")[0];
  let scheduledDate = "";

  // Event listener to close the modal when "X" is clicked
  closeButton.onclick = function () {
    modal.style.display = "none";
    resetTimeSlots();
  }

  // Event listener to close the modal when clicking outside the modal
  window.onclick = function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
      resetTimeSlots();
    }
  }

  // Function to open the modal and populate it if necessary
  function openModal(date, timeSlots) {
      modal.style.display = "block";
      //console.log(date);
      const today = date;
    
      // Example: prefill the form based on `date` and `timeSlots`
      //console.log(document.getElementById("timeSlot"));
      document.getElementById("date").value = date;
      document.getElementById("timeSlot").value = timeSlots || '';
      
      document.getElementById("scheduleForm").onsubmit = function (e) {
        e.preventDefault();

        const timeSlots = [];
          const inputs = document.querySelectorAll('#timeSlotContainer input[name="timeSlot[]"]');
          inputs.forEach((input) => {
              timeSlots.push(input.value);
          });

          console.log(timeSlots);
    
        // Get the selected time slot from the form
        //const selectedTimeSlot = document.getElementById("timeSlot").value;
        //console.log(selectedTimeSlot);
    
        // Create an object to send as data
        const data = {
          date: document.getElementById("date").value,
          timeSlot: timeSlots
        };

        console.log(data);

        // Create a form dynamically to send the data
        var form = document.createElement('form');
        form.method = 'POST'; // You can also use 'GET' if you want
        form.action = ''; // The PHP file to handle the data

        // Add data to the form as hidden inputs
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            }
        }

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();  // This sends the form data to calender.php
        
      };
    }
      // Function to add new timeslot input fields
  function addTimeSlot(value = '') {
      const container = document.getElementById('timeSlotContainer');

        // Create a new input field
      const newInput = document.createElement('input');
      newInput.type = 'text';
      newInput.name = 'timeSlot[]';
      newInput.placeholder = 'Enter checkup time';
      newInput.required = true;

      newInput.value = value;

        // Add the new input field to the container
      container.appendChild(newInput);
    }

    function resetTimeSlots() {
      const container = document.getElementById('timeSlotContainer');

      // Clear all inputs except the initial one
      container.innerHTML = `
          <input type="text" name="timeSlot[]" placeholder="Enter checkup time(8-12)" required>
      `;
  }


  // Function to open the Appointment Modal
  function showAppointments(appointments,date) {
      console.log("Type of Appointments:", typeof appointments);
      const header = document.getElementById('showAppointmentHeader');
      const appointmentModal = document.getElementById("appointmentPopup");
      const appointmentTableBody = document.querySelector("#appointmentTable tbody");

      scheduledDate = Date;
      // Clear existing rows
      header.innerHTML = `Appointments On ${date}`;
      appointmentTableBody.innerHTML = "";

      // Populate the table with appointment data
      if (appointments.length === 0) {
          const noDataRow = document.createElement("tr");
          const noDataCell = document.createElement("td");
          noDataCell.colSpan = 4;
          noDataCell.textContent = `No appointments available`;
          noDataCell.style.textAlign = "center";
          noDataCell.style.color = "red";
          noDataCell.style.padding = "10px";
          noDataRow.appendChild(noDataCell);
          appointmentTableBody.appendChild(noDataRow);
      } else {
          appointments.forEach((appointment) => {
              const row = document.createElement("tr");
              row.style.borderBottom = "1px solid #ddd";

              // Create cells for each column
              const appointmentIdCell = document.createElement("td");
              appointmentIdCell.textContent = appointment.appointment_id;
              appointmentIdCell.style.padding = "8px";
              row.appendChild(appointmentIdCell);

              const firstNameCell = document.createElement("td");
              firstNameCell.textContent = appointment.first_name;
              firstNameCell.style.padding = "8px";
              row.appendChild(firstNameCell);

              const lastNameCell = document.createElement("td");
              lastNameCell.textContent = appointment.last_name;
              lastNameCell.style.padding = "8px";
              row.appendChild(lastNameCell);

              const patientType = document.createElement("td");
              patientType.textContent = appointment.patient_type + " Patient";
              patientType.style.padding = "8px";
              row.appendChild(patientType);

              // Append the row to the table bodys
              appointmentTableBody.appendChild(row);
          });
      }

      // Show the modal
      appointmentModal.style.display = "block";

      // Close the modal when the close button is clicked
      document.getElementById("appointmentClose").onclick = function () {
          appointmentModal.style.display = "none";
      };

      // Close the modal when clicking outside the modal content
      window.onclick = function (event) {
          if (event.target == appointmentModal) {
              appointmentModal.style.display = "none";
          }
      };

      document.getElementById("rescheduleButton").onclick = function () {
        const confirmModal = document.getElementById("confirmModal");
        const scheduleDateDisplay = document.getElementById("scheduleDateDisplay");
        const scheduleDateInput = document.getElementById("scheduleDate");
      
        scheduleDateDisplay.textContent = date; // Update the visible span
        scheduleDateInput.value = date;
      
        confirmModal.style.display = "block"; // Show the confirmation modal
      
      };
      
      const confirmNo = document.getElementById("confirmNo");
      
      confirmNo.addEventListener("click", function () {
          const confirmModal = document.getElementById("confirmModal");
          confirmModal.style.display = "none";
      });
  }

  // expose to global scope if needed outside
  window.openModal = openModal;
  window.addTimeSlot = addTimeSlot;
  window.showAppointments = showAppointments;

})();