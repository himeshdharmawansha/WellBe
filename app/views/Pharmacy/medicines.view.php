<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Medicine Inventory</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/medicines.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
      <?php $this->renderComponent('navbar', $active); ?>
      <div class="main-content">
         <?php
         $pageTitle = "Medicine Inventory";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>
         <div class="dashboard-content">
            <div class="search-container">
               <input type="text" class="search-input" placeholder="Search Medicine" id="searchMedicine">
            </div>
            <table class="medicine-table">
               <thead>
                  <tr>
                     <th>Generic Name</th>
                     <th>Brand Name</th>
                     <th>Category</th>
                     <th>Expiry Date</th>
                     <th>Quantity</th>
                     <th>Unit</th>
                  </tr>
               </thead>
               <tbody id="medicine-table-body">
                  <tr>
                     <td colspan="6">Loading...</td>
                  </tr>
               </tbody>
            </table>
            <div class="pagination" id="pagination-controls"></div>
         </div>
      </div>
      <script>
         document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('medicine-table-body');
            const searchInput = document.getElementById('searchMedicine');
            const paginationContainer = document.getElementById('pagination-controls');
            let currentPage = 1;
            let totalPages = 1;
            let isSearching = false;

            function fetchMedicineData(page = 1) {
               if (isSearching) return;

               fetch(`<?= ROOT ?>/Pharmacy/getMedicines?page=${page}`)
                  .then(response => response.json())
                  .then(data => {
                     updateTable(data.medicines);
                     setupPagination(data.totalPages, page);
                  })
                  .catch(error => console.error('Error fetching medicines:', error));
            }

            function searchMedicineData(query, page = 1) {
               fetch(`<?= ROOT ?>/Pharmacy/searchForMedicine?query=${encodeURIComponent(query)}&page=${page}`)
                  .then(response => response.json())
                  .then(data => {
                     updateTable(data.medicines);
                     setupPagination(data.totalPages, page);
                  })
                  .catch(error => console.error('Error fetching searched medicines:', error));
            }

            function updateTable(medicines) {
               tableBody.innerHTML = '';

               if (!Array.isArray(medicines) || medicines.length === 0) {
                  tableBody.innerHTML = `<tr><td colspan="6">No medicines found.</td></tr>`;
                  return;
               }

               medicines.forEach(medicine => {
                  const row = document.createElement('tr');
                  row.classList.toggle('out-of-stock', medicine.quantity_in_stock == 0 || new Date(medicine.expiry_date) < new Date());

                  row.innerHTML = `
                     <td>${medicine.generic_name}</td>
                     <td>${medicine.brand_name}</td>
                     <td>${medicine.category}</td>
                     <td>${medicine.expiry_date}</td>
                     <td>${medicine.quantity_in_stock}</td>
                     <td>${medicine.unit}</td>
                  `;
                  tableBody.appendChild(row);
               });
            }

            function setupPagination(total, page) {
               totalPages = total;
               currentPage = page;
               paginationContainer.innerHTML = '';

               const createButton = (text, className, onClick, disabled = false) => {
                  const button = document.createElement('button');
                  button.textContent = text;
                  button.className = className;
                  button.disabled = disabled;
                  button.addEventListener('click', onClick);
                  return button;
               };

               const prevButton = createButton('Previous', 'pagination-btn', () => {
                  if (currentPage > 1) {
                     currentPage--;
                     isSearching ? searchMedicineData(searchInput.value, currentPage) : fetchMedicineData(currentPage);
                  }
               }, currentPage === 1);
               paginationContainer.appendChild(prevButton);

               for (let i = 1; i <= totalPages; i++) {
                  const pageButton = createButton(i, `pagination-page ${i === currentPage ? 'active' : ''}`, () => {
                     currentPage = i;
                     isSearching ? searchMedicineData(searchInput.value, currentPage) : fetchMedicineData(currentPage);
                  });
                  paginationContainer.appendChild(pageButton);
               }

               const nextButton = createButton('Next', 'pagination-btn', () => {
                  if (currentPage < totalPages) {
                     currentPage++;
                     isSearching ? searchMedicineData(searchInput.value, currentPage) : fetchMedicineData(currentPage);
                  }
               }, currentPage === totalPages);
               paginationContainer.appendChild(nextButton);
            }

            let debounceTimeout;
            searchInput.addEventListener('input', function() {
               clearTimeout(debounceTimeout);
               isSearching = true;

               debounceTimeout = setTimeout(() => {
                  const searchTerm = searchInput.value.trim();
                  if (searchTerm) {
                     searchMedicineData(searchTerm, 1);
                  } else {
                     isSearching = false;
                     fetchMedicineData(1);
                  }
               }, 300);
            });

            fetchMedicineData(1);
         });
      </script>
   </div>
</body>

</html>