document.addEventListener('DOMContentLoaded', function () {
   const remarksButton = document.getElementById('remarksButton');
   const currentDateElement = document.getElementById('currentDate');

   // Get current date and set in remarks
   const currentDate = new Date().toLocaleDateString();
   currentDateElement.textContent = currentDate;

   // Print functionality
   remarksButton.addEventListener('click', function () {
      window.print();
   });
});
