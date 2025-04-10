<?php
require_once(__DIR__ . "/../../controllers/ProfileController.php");

// Assume $_SESSION['USER'] has an 'id' property; adjust if it's different
$userId = $_SESSION['USER']->nic ?? $_SESSION['userid'] ?? null;
$image = $_SESSION['USER']->image ?? null; // Check if image is in session

// Construct a placeholder image URL; we'll update it via JavaScript
$profileImageUrl = $image
   ? ROOT . '/assets/images/users/' . $image
   : ROOT . '/assets/images/users/Profile_default.png'; // Using the ash-colored default
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administrative Staff</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/header.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
   <!-- Include Cropper.js CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
   <style>
      /* Modal Styling */
      .modal {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
         z-index: 1000;
         justify-content: center;
         align-items: center;
      }

      .modal-content {
         background-color: #1b1f23;
         /* Black background to match screenshot */
         padding: 0 30px;
         /* No top/bottom padding, only side padding */
         border-radius: 8px;
         width: 650px;
         text-align: center;
         position: relative;
         color: white;
      }

      .modal-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 20px 0;
         /* Adjusted padding */
      }

      .modal-header h3 {
         margin: 0;
         font-size: 20px;
         color: white;
         text-align: left;
      }

      .modal-header .close-btn {
         background: none;
         border: none;
         font-size: 24px;
         color: white;
         cursor: pointer;
      }

      .modal-image {
         width: 280px;
         height: 280px;
         border-radius: 50%;
         object-fit: cover;
         margin: 0 auto 20px;
         display: block;
      }

      .visibility-btn {
         display: flex;
         align-items: center;
         gap: 5px;
         background: none;
         border: 1px solid #fff;
         color: white;
         padding: 5px 10px;
         border-radius: 20px;
         font-size: 14px;
         margin-bottom: 20px;
         cursor: default;
         /* Placeholder, non-functional for now */
      }

      .modal-footer hr {
         border: 0;
         border-top: 1px solid #555;
         /* Light gray HR to match screenshot */
         margin: 0 0 20px 0;
      }

      .modal-footer {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 5px 0px 0px 0px;
         /* Adjusted padding */
         background: transparent;
         /* Transparent background to blend with modal */
      }

      .modal-footer .left-buttons {
         display: flex;
         gap: 10px;
      }

      .modal-footer .right-buttons {
         display: flex;
      }

      .modal-footer label,
      .modal-footer button {
         display: flex;
         flex-direction: column;
         align-items: center;
         gap: 5px;
         padding: 10px 15px;
         border: none;
         background: #1b1f23;
         /* Gray background to match screenshot */
         color: white;
         cursor: pointer;
         font-size: 14px;
         font-weight: 500;
         border-radius: 4px;
         transition: background 0.3s ease;
         /* Smooth hover transition */
      }

      .modal-footer label:hover,
      .modal-footer button:hover {
         background: #888;
         /* Lighter gray on hover to match screenshot */
      }

      .modal-footer i {
         font-size: 18px;
         color: white;
      }

      .modal-footer input[type="file"] {
         display: none;
      }

      /* Edit Modal Styling (unchanged) */
      #editModal .modal-content {
         width: 700px;
      }

      .edit-container {
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .edit-image-container {
         width: 390px;
         height: 390px;
         position: relative;
         overflow: hidden;
         border-radius: 50%;
      }

      .edit-image {
         max-width: 100%;
         max-height: 100%;
      }

      .edit-controls {
         width: 200px;
         padding: 10px;
      }

      .edit-controls button,
      .edit-controls input {
         display: block;
         width: 100%;
         margin: 10px 0;
         padding: 8px;
         border-radius: 4px;
         border: none;
         cursor: pointer;
      }

      .edit-controls button {
         background-color: #0073b1;
         color: white;
      }

      .edit-controls input[type="range"] {
         width: 100%;
      }

      .save-btn {
         background-color: #28a745;
         color: white;
         padding: 10px 20px;
         border: none;
         border-radius: 4px;
         cursor: pointer;
         margin: 20px 0px 15px 0px;
      }
   </style>
</head>

<body>
   <header class="main-header">
      <div class="header-left">
         <h1><?php echo isset($pageTitle) ? $pageTitle : ''; ?></h1>
      </div>
      <div class="header-right">
         <a href="chat">
            <div class="notification-icon">
               <i class="fas fa-bell"></i>
               <span class="notification-badge"></span>
            </div>
         </a>
         <div class="user-details">
            <img src="<?= htmlspecialchars($profileImageUrl) ?>" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info">
               <p style="font-weight: bold;"><?= $_SESSION['USER']->first_name ?? 'Unknown' ?></p>
               <p style="color:#989898"><?= $_SESSION['user_type'] ?? 'Unknown' ?></p>
            </div>
         </div>
      </div>
   </header>

   <!-- Modal for Profile Photo Actions -->
   <div class="modal" id="profileModal">
      <div class="modal-content">
         <div class="modal-header">
            <h3>Profile photo</h3>
            <button class="close-btn" onclick="closeModal()">×</button>
         </div>
         <img src="<?= htmlspecialchars($profileImageUrl) ?>" alt="Profile Image" class="modal-image" id="modalProfileImage">
         <hr style="border-color: #555; margin: 10px 0 0 0;">
         <div class="modal-footer">
            <div class="left-buttons">
               <button class="edit-btn" onclick="openEditModal()"><i class="fas fa-edit"></i> Edit</button>
               <label for="photoUpload"><i class="fas fa-camera"></i> Add photo</label>
               <input type="file" id="photoUpload" accept="image/*" onchange="handlePhotoUpload(event)">
            </div>
            <div class="right-buttons">
               <button class="delete-btn" onclick="deletePhoto()"><i class="fas fa-trash"></i> Delete</button>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal for Photo Editing -->
   <div class="modal" id="editModal">
      <div class="modal-content">
         <div class="modal-header">
            <h3>Edit photo</h3>
            <button class="close-btn" onclick="closeEditModal()">×</button>
         </div>
         <div class="edit-container">
            <div class="edit-image-container">
               <img id="editImage" class="edit-image" src="" alt="Image to edit">
            </div>
            <div class="edit-controls">
               <button onclick="rotateLeft()"><i class="fas fa-undo"></i> Rotate Left 90°</button>
               <button onclick="rotateRight()"><i class="fas fa-redo"></i> Rotate Right 90°</button>
               <label>Zoom</label>
               <input type="range" id="zoomSlider" min="1" max="3" step="0.1" value="1" oninput="updateZoom()">
               <label>Straighten</label>
               <input type="range" id="straightenSlider" min="-45" max="45" step="1" value="0" oninput="updateStraighten()">
            </div>
         </div>
         <button class="save-btn" onclick="saveEditedPhoto()">Save photo</button>
      </div>
   </div>

   <!-- Include Cropper.js -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
   <script>
      // Fetch the user's profile image on page load
      const userId = '<?php echo $userId; ?>';
      const avatar = document.getElementById('userAvatar');
      const modalImage = document.getElementById('modalProfileImage');
      const defaultImageUrl = '<?= ROOT ?>/assets/images/users/fault.png';

      let cropper = null;
      let uploadedFile = null;

      if (userId) {
         fetch('<?= ROOT ?>/ProfileController/get/' + userId)
            .then(response => response.json())
            .then(data => {
               if (data && !data.error) {
                  const imageUrl = data.profile_image_url || defaultImageUrl;
                  avatar.src = imageUrl;
                  modalImage.src = imageUrl;
               } else {
                  avatar.src = defaultImageUrl;
                  modalImage.src = defaultImageUrl;
               }
            })
            .catch(error => {
               console.error('Error fetching profile:', error);
               avatar.src = defaultImageUrl;
               modalImage.src = defaultImageUrl;
            });
      }

      // Show profile modal on double-click
      const modal = document.getElementById('profileModal');
      avatar.addEventListener('dblclick', function() {
         modal.style.display = 'flex';
      });

      // Close profile modal
      function closeModal() {
         modal.style.display = 'none';
      }

      // Show edit modal
      const editModal = document.getElementById('editModal');

      function openEditModal() {
         if (!uploadedFile && !modalImage.src.includes('Profile_default.png')) {
            // If no new file is uploaded, use the current profile image for editing
            const imageUrl = modalImage.src;
            const editImage = document.getElementById('editImage');
            editImage.src = imageUrl;
            initializeCropper();
            editModal.style.display = 'flex';
            modal.style.display = 'none';
         } else if (uploadedFile) {
            // If a new file is uploaded, use it for editing
            const editImage = document.getElementById('editImage');
            editImage.src = URL.createObjectURL(uploadedFile);
            initializeCropper();
            editModal.style.display = 'flex';
            modal.style.display = 'none';
         } else {
            alert('Please upload a photo first.');
         }
      }

      // Close edit modal
      function closeEditModal() {
         if (cropper) {
            cropper.destroy();
            cropper = null;
         }
         editModal.style.display = 'none';
         modal.style.display = 'flex';
         // Reset sliders
         document.getElementById('zoomSlider').value = 1;
         document.getElementById('straightenSlider').value = 0;
      }

      // Handle photo upload and redirect to edit modal
      function handlePhotoUpload(event) {
         uploadedFile = event.target.files[0];
         if (!uploadedFile) return;

         const editImage = document.getElementById('editImage');
         editImage.src = URL.createObjectURL(uploadedFile);
         initializeCropper();
         editModal.style.display = 'flex';
         modal.style.display = 'none';
      }

      // Initialize Cropper.js
      function initializeCropper() {
         const image = document.getElementById('editImage');
         if (cropper) {
            cropper.destroy();
         }
         cropper = new Cropper(image, {
            aspectRatio: 1, // Circular crop (1:1 aspect ratio)
            viewMode: 1, // Restrict crop box to image bounds
            dragMode: 'move', // Allow dragging the image
            cropBoxMovable: false, // Prevent moving the crop box
            cropBoxResizable: false, // Prevent resizing the crop box
            toggleDragModeOnDblclick: false,
            autoCropArea: 0.8, // Initial crop area
            ready() {
               // Ensure the crop box is circular
               const cropBox = this.cropper.cropBox;
               cropBox.style.borderRadius = '50%';
            }
         });
      }

      // Rotate left 90°
      function rotateLeft() {
         if (cropper) {
            cropper.rotate(-90);
         }
      }

      // Rotate right 90°
      function rotateRight() {
         if (cropper) {
            cropper.rotate(90);
         }
      }

      // Update zoom
      function updateZoom() {
         if (cropper) {
            const zoomValue = parseFloat(document.getElementById('zoomSlider').value);
            cropper.zoomTo(zoomValue);
         }
      }

      // Update straighten (rotation)
      function updateStraighten() {
         if (cropper) {
            const angle = parseFloat(document.getElementById('straightenSlider').value);
            cropper.rotateTo(angle);
         }
      }

      // Save the edited photo
      function saveEditedPhoto() {
         if (!cropper) return;

         // Get the cropped canvas
         const canvas = cropper.getCroppedCanvas({
            width: 200, // Same as modal image size
            height: 200,
         });

         // Convert canvas to blob
         canvas.toBlob(blob => {
            if (!blob) {
               alert('Failed to process the image.');
               return;
            }

            // Create a new file from the blob
            const editedFile = new File([blob], uploadedFile ? uploadedFile.name : 'edited-image.jpg', {
               type: 'image/jpeg',
            });

            // Upload the edited file
            const formData = new FormData();
            formData.append('photo', editedFile);
            formData.append('userId', userId);

            fetch('<?= ROOT ?>/ProfileController/uploadPhoto', {
                  method: 'POST',
                  body: formData
               })
               .then(response => {
                  if (!response.ok) {
                     return response.text().then(text => {
                        throw new Error('Network response was not ok: ' + response.statusText + ' - Response: ' + text);
                     });
                  }
                  return response.text();
               })
               .then(text => {
                  try {
                     const data = JSON.parse(text);
                     if (data.status === 'success') {
                        const newImageUrl = '<?= ROOT ?>/assets/images/users/' + data.filename;
                        avatar.src = newImageUrl;
                        modalImage.src = newImageUrl;
                        closeEditModal();
                        closeModal();
                        uploadedFile = null; // Reset the uploaded file
                     } else {
                        console.error('Upload error:', data);
                        alert('Error uploading photo: ' + (data.error || 'Unknown error'));
                     }
                  } catch (e) {
                     console.error('Failed to parse JSON response:', text);
                     throw new Error('Invalid JSON response: ' + e.message + ' - Raw response: ' + text);
                  }
               })
               .catch(error => {
                  console.error('Upload error:', error);
                  alert('Failed to upload photo: ' + error.message);
               });
         }, 'image/jpeg');
      }

      // Handle photo deletion
      function deletePhoto() {
         if (!confirm('Are you sure you want to delete your profile photo?')) return;

         fetch('<?= ROOT ?>/ProfileController/deletePhoto/' + userId, {
               method: 'POST'
            })
            .then(response => {
               if (!response.ok) {
                  return response.text().then(text => {
                     throw new Error('Network response was not ok: ' + response.statusText + ' - Response: ' + text);
                  });
               }
               return response.json();
            })
            .then(data => {
               if (data.status === 'success') {
                  avatar.src = defaultImageUrl;
                  modalImage.src = defaultImageUrl;
                  closeModal();
               } else {
                  console.error('Delete error:', data);
                  alert('Error deleting photo: ' + (data.error || 'Unknown error'));
               }
            })
            .catch(error => {
               console.error('Delete error:', error);
               alert('Failed to delete photo: ' + error.message);
            });
      }

      // Close modal when clicking outside
      window.addEventListener('click', function(event) {
         if (event.target === modal) {
            closeModal();
         }
         if (event.target === editModal) {
            closeEditModal();
         }
      });

      // Existing notification badge script
      const userType = '<?php echo strtolower($_SESSION['user_type'] ?? ''); ?>';
      let roles;

      switch (userType) {
         case 'pharmacy':
            roles = '3,5';
            break;
         case 'lab':
            roles = '3,5';
            break;
         case 'admin':
            roles = '1,2,4,5';
            break;
         case 'patient':
            roles = '3,5';
            break;
         case 'doctor':
            roles = '3,4,1,2';
            break;
         default:
            roles = '3';
            console.warn('Unknown user type:', userType);
            break;
      }

      function updateNotificationBadge() {
         fetch('<?= ROOT ?>/ChatController/getUnseenCounts?roles=' + roles)
            .then(response => {
               if (!response.ok) {
                  throw new Error('Network response was not ok');
               }
               return response.json();
            })
            .then(data => {
               let totalUnseen = 0;
               if (Array.isArray(data)) {
                  totalUnseen = data.reduce((sum, user) => sum + (user.unseen_count || 0), 0);
               } else if (data.error) {
                  console.error("Error from server:", data.error);
               }
               const badge = document.querySelector('.notification-badge');
               if (badge) {
                  badge.style.display = totalUnseen > 0 ? 'block' : 'none';
               }
            })
            .catch(error => console.error("Fetch error:", error));
      }

      updateNotificationBadge();
      setInterval(updateNotificationBadge, 500);
   </script>
</body>

</html>