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
      <!-- Error/Success Popup -->
      <div class="popup" id="error-popup">
         <span class="close-btn" onclick="closePopup()">×</span>
         <span id="popup-message"></span>
         <button class="retry-btn" id="retry-btn" style="display:none;">Retry</button>
      </div>
      <!-- Confirmation Popup -->
      <div class="confirm-popup" id="confirm-popup">
         <span id="confirm-message"></span>
         <div class="confirm-buttons">
            <button id="confirm-yes" class="confirm-btn yes-btn">Yes</button>
            <button id="confirm-no" class="confirm-btn no-btn">No</button>
         </div>
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

         let retryCallback = null;

         function showPopup(message, type = 'error', retry = false, callback = null) {
            console.log('showPopup called with message:', message, 'type:', type);
            const popup = document.getElementById('error-popup');
            const popupMessage = document.getElementById('popup-message');
            const retryBtn = document.getElementById('retry-btn');
            if (!popup || !popupMessage || !retryBtn) {
               console.error('Popup elements not found:', { popup, popupMessage, retryBtn });
               alert(message);
               return;
            }
            popupMessage.textContent = message;
            popup.className = `popup ${type} active`;
            console.log('Popup class set to:', popup.className);

            if (retry) {
               retryBtn.style.display = 'inline-block';
               retryCallback = callback;
            } else {
               retryBtn.style.display = 'none';
               retryCallback = null;
            }

            setTimeout(() => {
               popup.className = 'popup';
               console.log('Popup hidden after timeout');
            }, 5000);
         }

         function closePopup() {
            console.log('closePopup called');
            const popup = document.getElementById('error-popup');
            if (popup) {
               popup.className = 'popup';
               console.log('Popup class reset to:', popup.className);
            }
         }

         function retryAction() {
            if (retryCallback) {
               retryCallback();
            }
         }

         document.getElementById('retry-btn')?.addEventListener('click', retryAction);

         let confirmCallback = null;

         function showConfirmPopup(message, callback) {
            const confirmPopup = document.getElementById('confirm-popup');
            const confirmMessage = document.getElementById('confirm-message');
            if (!confirmPopup || !confirmMessage) {
               console.error('Confirm popup elements not found');
               callback(false);
               return;
            }
            confirmMessage.textContent = message;
            confirmPopup.className = 'confirm-popup active';
            confirmCallback = callback;
         }

         function closeConfirmPopup() {
            const confirmPopup = document.getElementById('confirm-popup');
            if (confirmPopup) {
               confirmPopup.className = 'confirm-popup';
            }
         }

         document.getElementById('confirm-yes')?.addEventListener('click', () => {
            if (confirmCallback) {
               confirmCallback(true);
               confirmCallback = null;
            }
            closeConfirmPopup();
         });

         document.getElementById('confirm-no')?.addEventListener('click', () => {
            if (confirmCallback) {
               confirmCallback(false);
               confirmCallback = null;
            }
            closeConfirmPopup();
         });

         function isFutureDate(dateStr) {
            const currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);
            const inputDate = new Date(dateStr);
            if (isNaN(inputDate.getTime())) {
               return false;
            }
            return inputDate > currentDate;
         }

         function isNumeric(value) {
            return /^\d+$/.test(value);
         }

         function isAlphabetic(value) {
            return /^[A-Za-z]+$/.test(value);
         }

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
                  if (data.error) {
                     showPopup(data.error);
                     tableBody.innerHTML = `<tr><td colspan="${showEditColumn ? 7 : 6}">Error loading medicines.</td></tr>`;
                     return;
                  }
                  updateTable(data.medicines);
                  setupPagination(data.totalPages, page);
               })
               .catch(error => {
                  showPopup('Error fetching medicines. Please try again', 'error', true, () => fetchMedicineData(page));
                  console.error('Error fetching medicines:', error);
                  tableBody.innerHTML = `<tr><td colspan="${showEditColumn ? 7 : 6}">Error loading medicines.</td></tr>`;
               });
         }

         function searchMedicineData(query, page = 1) {
            fetch(`<?= ROOT ?>/Pharmacy/searchForMedicine?query=${encodeURIComponent(query)}&page=${page}`)
               .then(response => response.json())
               .then(data => {
                  if (data.error) {
                     showPopup(data.error);
                     tableBody.innerHTML = `<tr><td colspan="${showEditColumn ? 7 : 6}">Error occurred while searching.</td></tr>`;
                     return;
                  }
                  updateTable(data.medicines);
                  setupPagination(data.totalPages, page);
               })
               .catch(error => {
                  showPopup('Error occurred while searching. Please try again', 'error', true, () => searchMedicineData(query, page));
                  console.error('Error fetching searched medicines:', error);
                  tableBody.innerHTML = `<tr><td colspan="${showEditColumn ? 7 : 6}">Error occurred while searching.</td></tr>`;
               });
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

            cells.forEach(cell => {
               const field = cell.dataset.field;
               const value = cell.textContent;
               cell.innerHTML = `<input type="text" value="${value}" data-field="${field}" />`;
            });

            editCell.innerHTML = `
               <button class="sav-btn"><i class="fas fa-check"></i></button>
               <button class="delete-btn"><i class="fas fa-trash"></i></button>
            `;

            const saveBtn = editCell.querySelector('.sav-btn');
            saveBtn.addEventListener('click', () => saveChanges(row));

            const deleteBtn = editCell.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', handleDelete);
         }

         function saveChanges(row) {
            const medicineId = row.dataset.medicineId;
            const inputs = row.querySelectorAll('input');
            const updatedData = { medicine_id: medicineId };

            let isValid = true;
            inputs.forEach(input => {
               const field = input.dataset.field;
               const value = input.value.trim();
               updatedData[field] = value;

               if (field === 'expiry_date' && value && !isFutureDate(value)) {
                  showPopup('Expiry date must be in the future and in YYYY-MM-DD format');
                  isValid = false;
               }
               if (field === 'quantity_in_stock' && value && !isNumeric(value)) {
                  showPopup('Quantity must be a number');
                  isValid = false;
               }
               if (field === 'unit' && value && !isAlphabetic(value)) {
                  showPopup('Unit must contain only alphabetic characters');
                  isValid = false;
               }
            });

            if (!isValid) return;

            inputs.forEach(input => {
               input.parentElement.textContent = input.value.trim();
            });

            const editCell = row.querySelector('td:last-child');
            editCell.innerHTML = `
               <button class="edit-btn"><i class="fas fa-edit"></i></button>
               <button class="delete-btn"><i class="fas fa-trash"></i></button>
            `;

            editCell.querySelector('.edit-btn').addEventListener('click', handleEdit);
            editCell.querySelector('.delete-btn').addEventListener('click', handleDelete);

            fetch(`<?= ROOT ?>/Pharmacy/updateMedicine`, {
               method: 'POST',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify(updatedData)
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  showPopup('Medicine updated successfully', 'success');
                  fetchMedicineData(currentPage);
               } else {
                  showPopup('Failed to update medicine');
               }
            })
            .catch(error => {
               showPopup('Error updating medicine. Please try again', 'error', true, () => saveChanges(row));
               console.error('Error updating medicine:', error);
            });
         }

         function handleDelete(event) {
            const row = event.target.closest('tr');
            const medicineId = row.dataset.medicineId;

            if (!medicineId) {
               row.remove();
               return;
            }

            showConfirmPopup('Are you sure you want to delete this medicine?', (confirmed) => {
               if (confirmed) {
                  fetch(`<?= ROOT ?>/Pharmacy/deleteMedicine`, {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json' },
                     body: JSON.stringify({ medicine_id: medicineId })
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        showPopup('Medicine deleted successfully', 'success');
                        fetchMedicineData(currentPage);
                     } else {
                        showPopup('Failed to delete medicine');
                     }
                  })
                  .catch(error => {
                     showPopup('Error deleting medicine. Please try again', 'error', true, () => handleDelete(event));
                     console.error('Error deleting medicine:', error);
                  });
               }
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

            let allFilled = true;
            inputs.forEach(input => {
               const value = input.value.trim();
               newMedicine[input.dataset.field] = value;
               if (!value) {
                  allFilled = false;
               }
            });

            if (!allFilled) {
               showPopup('Please fill in all fields before saving.');
               return;
            }

            if (!isFutureDate(newMedicine.expiry_date)) {
               showPopup('Expiry date must be in the future and in YYYY-MM-DD format');
               return;
            }
            if (!isNumeric(newMedicine.quantity_in_stock)) {
               showPopup('Quantity must be a number');
               return;
            }
            if (!isAlphabetic(newMedicine.unit)) {
               showPopup('Unit must contain only alphabetic characters');
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
                  showPopup('Medicine added successfully', 'success');
                  fetchMedicineData(currentPage);
               } else {
                  showPopup('Failed to add medicine');
               }
            })
            .catch(error => {
               showPopup('Error adding medicine. Please try again', 'error', true, () => saveNewMedicine(row));
               console.error('Error adding medicine:', error);
            });
         }

         let debounceTimeout;
         searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimeout);
            isSearching = true;

            debounceTimeout = setTimeout(() => {
               const searchTerm = searchInput.value.trim();
               if (searchTerm.length > 50) {
                  showPopup('Search term too long. Please use up to 50 characters');
                  return;
               }

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
</body>

</html>