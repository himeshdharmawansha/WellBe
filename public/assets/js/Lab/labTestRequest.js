document.addEventListener("DOMContentLoaded", function () {
   const rowsPerPage = 9;
   let currentPage = 1;
   let totalPages = 0;
   let currentTable = null;

   // Set up pagination for the table
   function setupPagination(table) {
      const rows = table.querySelectorAll("tbody tr");
      totalPages = Math.ceil(rows.length / rowsPerPage);

      const paginationContainer = document.querySelector(".pagination");
      paginationContainer.innerHTML = "";

      // Create Previous button
      const prevButton = document.createElement("button");
      prevButton.className = "pagination-btn";
      prevButton.textContent = "Previous";
      prevButton.disabled = currentPage === 1;
      prevButton.addEventListener("click", () => {
         if (currentPage > 1) {
            currentPage--;
            displayPage(currentPage);
         }
      });
      paginationContainer.appendChild(prevButton);

      // Create page buttons
      for (let i = 1; i <= totalPages; i++) {
         const pageButton = document.createElement("button");
         pageButton.className = `pagination-page ${i === currentPage ? "active" : ""}`;
         pageButton.textContent = i;
         pageButton.addEventListener("click", () => {
            currentPage = i;
            displayPage(currentPage);
         });
         paginationContainer.appendChild(pageButton);
      }

      // Create Next button
      const nextButton = document.createElement("button");
      nextButton.className = "pagination-btn";
      nextButton.textContent = "Next";
      nextButton.disabled = currentPage === totalPages;
      nextButton.addEventListener("click", () => {
         if (currentPage < totalPages) {
            currentPage++;
            displayPage(currentPage);
         }
      });
      paginationContainer.appendChild(nextButton);
   }

   // Display rows for the current page
   function displayPage(page) {
      if (!currentTable) return; // Guard clause to avoid errors if currentTable is not set

      const rows = currentTable.querySelectorAll("tbody tr");
      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      // Hide all rows initially
      rows.forEach((row, index) => {
         row.style.display = index >= start && index < end ? "" : "none";
      });

      // Update pagination buttons
      const pageButtons = document.querySelectorAll(".pagination-page");
      pageButtons.forEach(button => {
         button.classList.toggle("active", parseInt(button.textContent) === page);
      });

      // Update button states
      const prevButton = document.querySelector(".pagination-btn:first-of-type");
      const nextButton = document.querySelector(".pagination-btn:last-of-type");

      prevButton.disabled = page === 1;
      nextButton.disabled = page === totalPages;
   }

   // Show selected tab and initialize pagination for its table
   window.showTab = function (tabId) {
      // Remove 'active' class from all tabs
      document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));

      // Remove 'active' class from all request sections
      document.querySelectorAll('.requests-section').forEach(section => section.classList.remove('active'));

      // Add 'active' class to the clicked tab
      document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');

      // Show the relevant requests section
      const selectedSection = document.getElementById(tabId);
      selectedSection.classList.add('active');

      // Get the table from the selected section
      currentTable = selectedSection.querySelector(".requests-table");

      // Reset page number and initialize pagination for the current table
      currentPage = 1; // Start at the first page
      displayPage(currentPage);
      setupPagination(currentTable);
   };

   // Initialize the first tab (New Requests)
   window.showTab('new-requests');
});
