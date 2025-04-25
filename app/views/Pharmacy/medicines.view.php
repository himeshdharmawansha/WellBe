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
               <div class="right-controls">
                  <button id="addMedicineBtn" class="add-btn"><i class="fas fa-plus"></i></button>
                  <label class="switch">
                     <input type="checkbox" id="toggleView">
                     <span class="slider round"></span>
                  </label>
               </div>
            </div>
            <table class="medicine-table">
               <thead id="table-header">
                  <tr>
                     <th>GENERIC NAME</th>
                     <th>BRAND NAME</th>
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
         document.addEventListener('DOMContentLoaded', function () {
            const tableHeader = document.getElementById('table-header');
            const tableBody = document.getElementById('medicine-table-body');
            const searchInput = document.getElementById('searchMedicine');
            const paginationContainer = document.getElementById('pagination-controls');
            const toggleView = document.getElementById('toggleView');
            const addMedicineBtn = document.getElementById('addMedicineBtn');
            let currentPage = 1;
            let totalPages = 1;
            let isSearching = false;
            let showEditColumn = false;

            function updateTableHeader() {
               if (showEditColumn) {
                  tableHeader.innerHTML = `
                     <tr>
                        <th>GENERIC NAME</th>
                        <th>BRAND NAME</th>
                        <th>Category</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Edit</th>
                     </tr>
                  `;
               } else {
                  tableHeader.innerHTML = `
                     <tr>
                        <th>GENERIC NAME</th>
                        <th>BRAND NAME</th>
                        <th>Category</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                     </tr>
                  `;
               }
            }

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
                  const colspan = showEditColumn ? 7 : 6;
                  tableBody.innerHTML = `<tr><td colspan="${colspan}">No medicines found.</td></tr>`;
                  return;
               }

               medicines.forEach(medicine => {
                  const row = document.createElement('tr');
                  row.dataset.medicineId = medicine.medicine_id;

                  // Highlight rows with quantity_in_stock <= 0
                  const isOutOfStock = parseInt(medicine.quantity_in_stock) <= 0;
                  row.classList.toggle('out-of-stock', isOutOfStock);

                  let rowContent = `
                     <td class="editable" data-field="generic_name">${medicine.generic_name}</td>
                     <td class="editable" data-field="brand_name">${medicine.brand_name}</td>
                     <td class="editable" data-field="category">${medicine.category}</td>
                     <td class="editable" data-field="expiry_date">${medicine.expiry_date}</td>
                     <td class="editable" data-field="quantity_in_stock">${medicine.quantity_in_stock}</td>
                     <td class="editable" data-field="unit">${medicine.unit}</td>
                  `;
                  if (showEditColumn) {
                     rowContent += `
                        <td>
                           <button class="edit-btn"><i class="fas fa-edit"></i></button>
                           <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                     `;
                  }
                  row.innerHTML = rowContent;
                  tableBody.appendChild(row);
               });

               if (showEditColumn) {
                  document.querySelectorAll('.edit-btn').forEach(btn => {
                     btn.addEventListener('click', handleEdit);
                  });
                  document.querySelectorAll('.delete-btn').forEach(btn => {
                     btn.addEventListener('click', handleDelete);
                  });
               }
            }

            function handleEdit(event) {
               const row = event.target.closest('tr');
               const cells = row.querySelectorAll('.editable');
               const editCell = row.querySelector('td:last-child');

               // Convert cells to input fields
               cells.forEach(cell => {
                  const field = cell.dataset.field;
                  const value = cell.textContent;
                  cell.innerHTML = `<input type="text" value="${value}" data-field="${field}" />`;
               });

               // Replace edit/delete buttons with a save button (tick icon)
               editCell.innerHTML = `
                  <button class="sav-btn"><i class="fas fa-check"></i></button>
                  <button class="delete-btn"><i class="fas fa-trash"></i></button>
               `;

               // Add event listener for the save button
               const saveBtn = editCell.querySelector('.sav-btn');
               saveBtn.addEventListener('click', () => saveChanges(row));

               // Reattach delete button listener
               const deleteBtn = editCell.querySelector('.delete-btn');
               deleteBtn.addEventListener('click', handleDelete);
            }

            function saveChanges(row) {
               const medicineId = row.dataset.medicineId;
               const inputs = row.querySelectorAll('input');
               const updatedData = { medicine_id: medicineId };

               // Collect updated values
               inputs.forEach(input => {
                  const field = input.dataset.field;
                  const value = input.value.trim();
                  updatedData[field] = value;
                  input.parentElement.textContent = value;
               });

               // Restore the edit/delete buttons
               const editCell = row.querySelector('td:last-child');
               editCell.innerHTML = `
                  <button class="edit-btn"><i class="fas fa-edit"></i></button>
                  <button class="delete-btn"><i class="fas fa-trash"></i></button>
               `;

               // Reattach event listeners
               editCell.querySelector('.edit-btn').addEventListener('click', handleEdit);
               editCell.querySelector('.delete-btn').addEventListener('click', handleDelete);

               // Send update request to the server
               fetch(`<?= ROOT ?>/Pharmacy/updateMedicine`, {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify(updatedData)
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     fetchMedicineData(currentPage);
                  } else {
                     alert('Failed to update medicine');
                  }
               })
               .catch(error => console.error('Error updating medicine:', error));
            }

            function handleDelete(event) {
               const row = event.target.closest('tr');
               const medicineId = row.dataset.medicineId;

               // Check if this is a new row (not yet saved)
               if (!medicineId) {
                  row.remove(); // Simply remove the row from the view
                  return;
               }

               // For existing rows, confirm and delete from the server
               if (confirm('Are you sure you want to delete this medicine?')) {
                  fetch(`<?= ROOT ?>/Pharmacy/deleteMedicine`, {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json' },
                     body: JSON.stringify({ medicine_id: medicineId })
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        fetchMedicineData(currentPage);
                     } else {
                        alert('Failed to delete medicine');
                     }
                  })
                  .catch(error => console.error('Error deleting medicine:', error));
               }
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

            toggleView.addEventListener('change', () => {
               showEditColumn = toggleView.checked;
               updateTableHeader();
               isSearching ? searchMedicineData(searchInput.value, 1) : fetchMedicineData(1);
               addMedicineBtn.style.display = showEditColumn ? 'inline-block' : 'none';
            });

            addMedicineBtn.addEventListener('click', () => {
               if (!showEditColumn) return;

               const row = document.createElement('tr');
               row.innerHTML = `
                  <td class="editable" data-field="generic_name"><input type="text" data-field="generic_name" placeholder="Generic Name" /></td>
                  <td class="editable" data-field="brand_name"><input type="text" data-field="brand_name" placeholder="Brand Name" /></td>
                  <td class="editable" data-field="category"><input type="text" data-field="category" placeholder="Category" /></td>
                  <td class="editable" data-field="expiry_date"><input type="text" data-field="expiry_date" placeholder="YYYY-MM-DD" /></td>
                  <td class="editable" data-field="quantity_in_stock"><input type="text" data-field="quantity_in_stock" placeholder="Quantity" /></td>
                  <td class="editable" data-field="unit"><input type="text" data-field="unit" placeholder="Unit" /></td>
                  <td>
                     <button class="sav-btn"><i class="fas fa-check"></i></button>
                     <button class="delete-btn"><i class="fas fa-trash"></i></button>
                  </td>
               `;
               tableBody.insertBefore(row, tableBody.firstChild);

               const saveBtn = row.querySelector('.sav-btn');
               saveBtn.addEventListener('click', () => saveNewMedicine(row));

               const deleteBtn = row.querySelector('.delete-btn');
               deleteBtn.addEventListener('click', handleDelete);
            });

            function saveNewMedicine(row) {
               const inputs = row.querySelectorAll('input');
               const newMedicine = {};

               // Collect values and validate
               let allFilled = true;
               inputs.forEach(input => {
                  const value = input.value.trim();
                  newMedicine[input.dataset.field] = value;
                  if (!value) {
                     allFilled = false;
                  }
               });

               if (!allFilled) {
                  alert('Please fill in all fields before saving.');
                  return;
               }

               fetch(`<?= ROOT ?>/Pharmacy/addMedicine`, {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify(newMedicine)
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     fetchMedicineData(currentPage);
                  } else {
                     alert('Failed to add medicine');
                  }
               })
               .catch(error => console.error('Error adding medicine:', error));
            }

            let debounceTimeout;
            searchInput.addEventListener('input', function () {
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

            addMedicineBtn.style.display = 'none';
            fetchMedicineData(1);
         });
      </script>
   </div>
</body>

</html>