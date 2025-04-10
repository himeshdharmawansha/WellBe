<?php
require_once(__DIR__ . "/../../controllers/ProfileController.php");

// Assume $_SESSION['USER'] has an 'id' property; adjust if it's different
$userId = $_SESSION['USER']->id ?? $_SESSION['userid'] ?? null;
$image = $_SESSION['USER']->image ?? null; // Check if image is in session

// Construct a placeholder image URL; we'll update it via JavaScript
$profileImageUrl = $image 
    ? ROOT . '/assets/images/users/' . $image 
    : ROOT . '/assets/images/users/profile_default.png'; // Using the ash-colored default
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administrative Staff</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/header.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
   <style>
      /* Modal Styling */
      .modal {
         display: none;
         position: fixed;
         top: 0; /* Fixed typo */
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
         z-index: 1000;
         justify-content: center;
         align-items: center;
      }
      .modal-content {
         background-color: #1e2526;
         padding: 30px;
         border-radius: 8px;
         width: 650px; /* Increased width to match screenshot */
         text-align: center;
         position: relative;
         color: white;
      }
      .modal-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 30px; /* Increased spacing below header */
      }
      .modal-header h3 {
         margin: 0;
         font-size: 20px; /* Slightly larger font size */
         color: white;
         text-align: left;
      }
      .modal-header .close-btn {
         background: none;
         border: none;
         font-size: 24px; /* Larger close button */
         color: white;
         cursor: pointer;
      }
      .modal-image {
         width: 200px; /* Increased size to match screenshot */
         height: 200px;
         border-radius: 50%;
         object-fit: cover;
         margin: 0 auto 30px; /* Increased margin below image */
         display: block;
      }
      .modal-footer {
         display: flex;
         justify-content: space-between;
         align-items: center;
      }
      .modal-footer label,
      .modal-footer button {
         display: flex;
         align-items: center;
         gap: 8px; /* Increased gap for better spacing */
         padding: 10px 20px; /* Larger padding for buttons */
         border-radius: 4px;
         cursor: pointer;
         font-size: 16px; /* Larger font size */
         font-weight: 500;
      }
      .modal-footer .add-photo-btn {
         background-color: #0073b1;
         color: white;
         border: none;
      }
      .modal-footer .delete-btn {
         background: none;
         border: 1px solid #ff4d4f;
         color: #ff4d4f;
      }
      .modal-footer input[type="file"] {
         display: none;
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
         <div class="modal-footer">
            <label for="photoUpload" class="add-photo-btn"><i class="fas fa-camera"></i> Add photo</label>
            <input type="file" id="photoUpload" accept="image/*" onchange="uploadPhoto(event)">
            <button class="delete-btn" onclick="deletePhoto()"><i class="fas fa-trash"></i> Delete</button>
         </div>
      </div>
   </div>

   <script>
      // Fetch the user's profile image on page load
      const userId = '<?php echo $userId; ?>';
      const avatar = document.getElementById('userAvatar');
      const modalImage = document.getElementById('modalProfileImage');
      const defaultImageUrl = '<?= ROOT ?>/assets/images/users/profile_default.png';

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

      // Show modal on double-click
      const modal = document.getElementById('profileModal');
      avatar.addEventListener('dblclick', function() {
         modal.style.display = 'flex';
      });

      // Close modal
      function closeModal() {
         modal.style.display = 'none';
      }

      // Handle photo upload
      function uploadPhoto(event) {
         const file = event.target.files[0];
         if (!file) return;

         const formData = new FormData();
         formData.append('photo', file);
         formData.append('userId', userId);

         fetch('<?= ROOT ?>/ProfileController/uploadPhoto', {
            method: 'POST',
            body: formData
         })
         .then(response => {
            if (!response.ok) {
               throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
         })
         .then(data => {
            if (data.status === 'success') {
               const newImageUrl = '<?= ROOT ?>/assets/images/users/' + data.filename;
               avatar.src = newImageUrl;
               modalImage.src = newImageUrl;
               closeModal();
            } else {
               console.error('Upload error:', data);
               alert('Error uploading photo: ' + (data.error || 'Unknown error'));
            }
         })
         .catch(error => {
            console.error('Upload error:', error);
            alert('Failed to upload photo: ' + error.message);
         });
      }

      // Handle photo deletion
      function deletePhoto() {
         if (!confirm('Are you sure you want to delete your profile photo?')) return;

         fetch('<?= ROOT ?>/ProfileController/deletePhoto/' + userId, {
            method: 'POST'
         })
         .then(response => {
            if (!response.ok) {
               throw new Error('Network response was not ok: ' + response.statusText);
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