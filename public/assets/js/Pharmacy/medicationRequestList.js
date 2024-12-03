document.addEventListener("DOMContentLoaded", function () {
   const rowsPerPage = 9;
   let currentPage = 1;
   let totalPages = 0;
   let currentTable = null;

   function setupPagination(table) {
      const rows = table.querySelectorAll("tbody tr");
      totalPages = Math.ceil(rows.length / rowsPerPage);

      const paginationContainer = document.querySelector(".pagination");
      paginationContainer.innerHTML = "";

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

   function displayPage(page) {
      if (!currentTable) return; // Guard clause to avoid errors if currentTable is not set

      const rows = currentTable.querySelectorAll("tbody tr");
      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      rows.forEach((row, index) => {
         row.style.display = index >= start && index < end ? "" : "none";
      });

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

   // Make showTab globally accessible
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

      // Initialize pagination for the current table
      currentPage = 1; // Reset to the first page
      displayPage(currentPage);
      setupPagination(currentTable);
   };

   // Initialize the first tab (pending requests)
   window.showTab('pending-requests');
});
