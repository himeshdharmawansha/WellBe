<?php
require_once(__DIR__ . "/../../controllers/ProfileController.php");
require_once(__DIR__ . "/../../models/ProfileModel.php");
require_once(__DIR__ . "/../../controllers/ChatController.php");

// Initialize controllers and models
$profileModel = new ProfileModel();
$chatController = new ChatController();

// Assume $_SESSION['USER'] has an 'id' property; adjust if it's different
$userId = $_SESSION['USER']->nic ?? $_SESSION['userid'] ?? null;

// Fetch all profiles from ProfileModel
$profiles = $profileModel->getAll(); // Array of objects
$profileImageUrl = ROOT . '/assets/images/users/Profile_default.png'; // Default image

if (!empty($profiles) && !isset($profiles['error'])) {
    foreach ($profiles as $profile) {
        if ($profile->id == $userId && isset($profile->image)) {
            $profileImageUrl = ROOT . '/assets/images/users/' . $profile->image;
            break; // Exit loop once the current user's profile is found
        }
    }
}

// Determine roles based on user type for notification counts
$userType = strtolower($_SESSION['user_type'] ?? '');
switch ($userType) {
    case 'pharmacy':
        $roles = [3, 5];
        break;
    case 'lab':
        $roles = [3, 5];
        break;
    case 'admin':
        $roles = [1, 2, 4, 5];
        break;
    case 'patient':
        $roles = [3, 5];
        break;
    case 'doctor':
        $roles = [3, 4, 1, 2];
        break;
    default:
        $roles = [3];
        break;
}

// Fetch unseen counts on page load
$unseenCounts = $chatController->UnseenCounts($roles);
$totalUnseen = 0;
if (is_array($unseenCounts)) {
    $totalUnseen = array_reduce($unseenCounts, function($sum, $user) {
        return $sum + ($user['unseen_count'] ?? 0);
    }, 0);
}
// Set initial badge visibility
$badgeVisibility = $totalUnseen > 0 ? 'block' : 'none';
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
               <span class="notification-badge" style="display: <?= $badgeVisibility ?>;"></span>
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
      const defaultImageUrl = '<?= ROOT ?>/assets/images/users/Profile_default.png';

      let cropper = null;
      let uploadedFile = null;
      let currentFilename = null; // Store the filename of the uploaded file

      if (userId) {
         fetch('<?= ROOT ?>/ProfileController/get/' + userId)
            .then(response => response.json())
            .then(data => {
               if (data && !data.error) {
                  const imageUrl = data.profile_image_url || defaultImageUrl;
                  avatar.src = imageUrl;
                  modalImage.src = imageUrl;
                  updateButtonStates(imageUrl); // Update button states after fetching
               } else {
                  avatar.src = defaultImageUrl;
                  modalImage.src = defaultImageUrl;
                  updateButtonStates(defaultImageUrl); // Update button states for default
               }
            })
            .catch(error => {
               console.error('Error fetching profile:', error);
               avatar.src = defaultImageUrl;
               modalImage.src = defaultImageUrl;
               updateButtonStates(defaultImageUrl); // Update button states on error
            });
      }

      // Function to update edit and delete button states
      function updateButtonStates(imageUrl) {
         const editButton = document.querySelector('.edit-btn');
         const deleteButton = document.querySelector('.delete-btn');
         const isDefault = imageUrl.includes('Profile_default.png');
         editButton.disabled = isDefault;
         deleteButton.disabled = isDefault;
      }

      // Show profile modal on double-click
      const modal = document.getElementById('profileModal');
      avatar.addEventListener('dblclick', function() {
         modal.style.display = 'flex';
         updateButtonStates(modalImage.src); // Ensure buttons are updated when modal opens
      });

      // Close profile modal
      function closeModal() {
         modal.style.display = 'none';
         uploadedFile = null; // Reset uploaded file
         currentFilename = null; // Reset filename
      }

      // Show edit modal
      const editModal = document.getElementById('editModal');
      function openEditModal() {
         if (uploadedFile) {
            // If a new file is uploaded, use it for editing
            const editImage = document.getElementById('editImage');
            editImage.src = URL.createObjectURL(uploadedFile);
            initializeCropper();
            editModal.style.display = 'flex';
            modal.style.display = 'none';
         } else if (!modalImage.src.includes('Profile_default.png')) {
            // Fetch the original image for editing
            fetch('<?= ROOT ?>/ProfileController/get/' + userId)
               .then(response => response.json())
               .then(data => {
                  if (data && !data.error && data.profile_image_url) {
                     const baseFilename = data.profile_image_url.split('/').pop();
                     const originalFilename = baseFilename.replace(/(\.[^.]+)$/, '_original$1');
                     const originalImageUrl = '<?= ROOT ?>/assets/images/users/' + originalFilename;
                     const editImage = document.getElementById('editImage');
                     editImage.src = originalImageUrl;
                     currentFilename = baseFilename; // Store the current filename
                     initializeCropper();
                     editModal.style.display = 'flex';
                     modal.style.display = 'none';
                  } else {
                     alert('Failed to load original image for editing.');
                  }
               })
               .catch(error => {
                  console.error('Error fetching profile:', error);
                  alert('Failed to load image for editing.');
               });
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

      // Handle photo upload and save the original file immediately
      function handlePhotoUpload(event) {
         uploadedFile = event.target.files[0];
         if (!uploadedFile) return;

         // Generate a random filename
         const randomString = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
         const extension = uploadedFile.name.split('.').pop();
         const filename = `${randomString}.${extension}`;

         // Save the original file immediately
         const formData = new FormData();
         formData.append('photo', uploadedFile);
         formData.append('userId', userId);
         formData.append('filename', filename); // Pass the random filename

         fetch('<?= ROOT ?>/ProfileController/saveOriginalPhoto', {
            method: 'POST',
            body: formData
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
               currentFilename = data.filename; // Store the filename for later use
               const editImage = document.getElementById('editImage');
               editImage.src = URL.createObjectURL(uploadedFile);
               initializeCropper();
               editModal.style.display = 'flex';
               modal.style.display = 'none';
            } else {
               console.error('Upload error:', data);
               alert('Error uploading original photo: ' + (data.error || 'Unknown error'));
            }
         })
         .catch(error => {
            console.error('Upload error:', error);
            alert('Failed to upload original photo: ' + error.message);
         });
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
            width: 280, // Match modal image size
            height: 280,
         });

         // Convert canvas to blob
         canvas.toBlob(blob => {
            if (!blob) {
               alert('Failed to process the image.');
               return;
            }

            // Create a new file from the blob
            const editedFile = new File([blob], currentFilename, {
               type: 'image/jpeg',
            });

            // Upload the edited file
            const formData = new FormData();
            formData.append('photo', editedFile);
            formData.append('userId', userId);
            formData.append('filename', currentFilename); // Use the same filename

            fetch('<?= ROOT ?>/ProfileController/saveEditedPhoto', {
               method: 'POST',
               body: formData
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
                  const newImageUrl = '<?= ROOT ?>/assets/images/users/' + data.filename;
                  avatar.src = newImageUrl;
                  modalImage.src = newImageUrl;
                  updateButtonStates(newImageUrl); // Update button states after saving
                  closeEditModal();
                  closeModal();
                  uploadedFile = null; // Reset the uploaded file
                  currentFilename = null; // Reset the filename
                  location.reload(); // Refresh the page to update the profile
               } else {
                  console.error('Upload error:', data);
                  alert('Error uploading edited photo: ' + (data.error || 'Unknown error'));
               }
            })
            .catch(error => {
               console.error('Upload error:', error);
               alert('Failed to upload edited photo: ' + error.message);
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
               updateButtonStates(defaultImageUrl); // Update button states after deletion
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

      // Notification badge script
      const userType = '<?php echo strtolower($_SESSION['user_type'] ?? ''); ?>';
      let roles = '<?php echo implode(',', $roles); ?>'; // Pass PHP roles to JS

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

      // Initial call is already handled by PHP, but we keep the JS update for real-time
      setInterval(updateNotificationBadge, 500);
   </script>
</body>

</html>