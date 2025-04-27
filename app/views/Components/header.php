<?php
require_once(__DIR__ . "/../../controllers/ProfileController.php");
require_once(__DIR__ . "/../../models/ProfileModel.php");
require_once(__DIR__ . "/../../controllers/ChatController.php");

$profileModel = new ProfileModel();
$chatController = new ChatController();

$userId = $_SESSION['USER']->nic ?? $_SESSION['userid'] ?? null;

$profiles = $profileModel->getAll();
$profileImageUrl = ROOT . '/assets/images/users/Profile_default.png';

if (!empty($profiles) && !isset($profiles['error'])) {
    foreach ($profiles as $profile) {
        if ($profile->id == $userId && isset($profile->image)) {
            $profileImageUrl = ROOT . '/assets/images/users/' . $profile->image;
            break;
        }
    }
}

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
   case 'receptionist':
        $roles = [3, 4];
        break;
}

$unseenCounts = $chatController->UnseenCounts($roles);
$totalUnseen = 0;
if (is_array($unseenCounts)) {
    $totalUnseen = array_reduce($unseenCounts, function($sum, $user) {
        return $sum + ($user['unseen_count'] ?? 0);
    }, 0);
}
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

   <style>
      #notificationModal {
         display: none;
         position: fixed;
         z-index: 999;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.6);
         justify-content: center;
         align-items: center;
         font-family: sans-serif;
      }

      .modal-content-noti {
         background-color: #fff;
         padding: 20px;
         border-radius: 16px;
         text-align: center;
         width: 350px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      }

      .modal-content-noti h3 {
         margin-bottom: 10px;
         font-size: 18px;
      }

      .modal-buttons-noti {
         display: flex;
         flex-direction: column;
         gap: 10px;
         margin-top: 20px;
      }

      .modal-buttons-noti button {
         padding: 10px;
         border: none;
         border-radius: 8px;
         cursor: pointer;
         font-size: 16px;
      }

      .btn-primary {
         background-color: #007bff;
         color: white;
      }

      .btn-danger {
         background-color: #dc3545;
         color: white;
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

   <div id="notificationModal">
      <div class="modal-content-noti" id="step1">
         <h3 id="appointmentMessage">Your appointment has been rescheduled.</h3>
         <h3 id="appointmentPrimaryId" hidden>primary id</h3>
         <div class="modal-buttons-noti">
            <button class="btn-primary" onclick="showManageOptions_noti()" style="background-color: #007bff;">Manage Appointment</button>
         </div>
      </div>
      <div class="modal-content-noti" id="step2" style="display: none;">
         <h3>What would you like to do?</h3>
         <div class="modal-buttons-noti">
            <button class="btn-primary" onclick="handleReschedule_noti()">Reschedule</button>
            <button class="btn-danger" onclick="handleCancel_noti()">Cancel Appointment</button>
         </div>
      </div>
   </div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
   <script>
      const userId = '<?php echo $userId; ?>';
      const avatar = document.getElementById('userAvatar');
      const modalImage = document.getElementById('modalProfileImage');
      const defaultImageUrl = '<?= ROOT ?>/assets/images/users/Profile_default.png';

      let cropper = null;
      let uploadedFile = null;
      let currentFilename = null;

      if (userId) {
         fetch('<?= ROOT ?>/ProfileController/get/' + userId)
            .then(response => response.json())
            .then(data => {
               if (data && !data.error) {
                  const imageUrl = data.profile_image_url || defaultImageUrl;
                  avatar.src = imageUrl;
                  modalImage.src = imageUrl;
                  updateButtonStates(imageUrl);
               } else {
                  avatar.src = defaultImageUrl;
                  modalImage.src = defaultImageUrl;
                  updateButtonStates(defaultImageUrl);
               }
            })
            .catch(error => {
               console.error('Error fetching profile:', error);
               avatar.src = defaultImageUrl;
               modalImage.src = defaultImageUrl;
               updateButtonStates(defaultImageUrl);
            });
      }

      function updateButtonStates(imageUrl) {
         const editButton = document.querySelector('.edit-btn');
         const deleteButton = document.querySelector('.delete-btn');
         const isDefault = imageUrl.includes('Profile_default.png');
         editButton.disabled = isDefault;
         deleteButton.disabled = isDefault;
      }

      const modal = document.getElementById('profileModal');
      avatar.addEventListener('dblclick', function() {
         modal.style.display = 'flex';
         updateButtonStates(modalImage.src);
      });

      function closeModal() {
         modal.style.display = 'none';
         uploadedFile = null;
         currentFilename = null;
      }

      const editModal = document.getElementById('editModal');
      function openEditModal() {
         if (uploadedFile) {
            const editImage = document.getElementById('editImage');
            editImage.src = URL.createObjectURL(uploadedFile);
            initializeCropper();
            editModal.style.display = 'flex';
            modal.style.display = 'none';
         } else if (!modalImage.src.includes('Profile_default.png')) {
            fetch('<?= ROOT ?>/ProfileController/get/' + userId)
               .then(response => response.json())
               .then(data => {
                  if (data && !data.error && data.profile_image_url) {
                     const baseFilename = data.profile_image_url.split('/').pop();
                     const originalFilename = baseFilename.replace(/(\.[^.]+)$/, '_original$1');
                     const originalImageUrl = '<?= ROOT ?>/assets/images/users/' + originalFilename;
                     const editImage = document.getElementById('editImage');
                     editImage.src = originalImageUrl;
                     currentFilename = baseFilename;
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

      function closeEditModal() {
         if (cropper) {
            cropper.destroy();
            cropper = null;
         }
         editModal.style.display = 'none';
         modal.style.display = 'flex';
         document.getElementById('zoomSlider').value = 1;
         document.getElementById('straightenSlider').value = 0;
      }

      function handlePhotoUpload(event) {
         uploadedFile = event.target.files[0];
         if (!uploadedFile) return;

         const randomString = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
         const extension = uploadedFile.name.split('.').pop();
         const filename = `${randomString}.${extension}`;

         const formData = new FormData();
         formData.append('photo', uploadedFile);
         formData.append('userId', userId);
         formData.append('filename', filename);

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
               currentFilename = data.filename;
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

      function initializeCropper() {
         const image = document.getElementById('editImage');
         if (cropper) {
            cropper.destroy();
         }
         cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
            autoCropArea: 0.8,
            ready() {
               const cropBox = this.cropper.cropBox;
               cropBox.style.borderRadius = '50%';
            }
         });
      }

      function rotateLeft() {
         if (cropper) {
            cropper.rotate(-90);
         }
      }

      function rotateRight() {
         if (cropper) {
            cropper.rotate(90);
         }
      }

      function updateZoom() {
         if (cropper) {
            const zoomValue = parseFloat(document.getElementById('zoomSlider').value);
            cropper.zoomTo(zoomValue);
         }
      }

      function updateStraighten() {
         if (cropper) {
            const angle = parseFloat(document.getElementById('straightenSlider').value);
            cropper.rotateTo(angle);
         }
      }

      function saveEditedPhoto() {
         if (!cropper) return;

         const canvas = cropper.getCroppedCanvas({
            width: 280,
            height: 280,
         });

         canvas.toBlob(blob => {
            if (!blob) {
               alert('Failed to process the image.');
               return;
            }

            const editedFile = new File([blob], currentFilename, {
               type: 'image/jpeg',
            });

            const formData = new FormData();
            formData.append('photo', editedFile);
            formData.append('userId', userId);
            formData.append('filename', currentFilename);

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
                  updateButtonStates(newImageUrl);
                  closeEditModal();
                  closeModal();
                  uploadedFile = null;
                  currentFilename = null;
                  location.reload();
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
               updateButtonStates(defaultImageUrl);
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

      window.addEventListener('click', function(event) {
         if (event.target === modal) {
            closeModal();
         }
         if (event.target === editModal) {
            closeEditModal();
         }
      });

      const userType = '<?php echo strtolower($_SESSION['user_type'] ?? ''); ?>';
      let roles = '<?php echo implode(',', $roles); ?>';

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

      setInterval(updateNotificationBadge, 500);
   </script>

   <?php
   //check whether user is a patient
   if (isset($_SESSION['USER']->nic) && strpos($_SESSION['USER']->nic, 'p') !== false) {
   ?>
      <script>
         const socket = new WebSocket('ws://localhost:8080');

         socket.addEventListener('open', () => {
            const userId = <?php echo json_encode($_SESSION['USER']->nic); ?>;
            socket.send(JSON.stringify({ type: 'register', userId }));
         });

         socket.addEventListener('message', (event) => {
            try {
               const data = JSON.parse(event.data);
               document.getElementById('appointmentMessage').innerText = data.text;
               document.getElementById('appointmentPrimaryId').innerText = data.id;
               //console.log("Notification ID:", data.id);

               showModal_noti();
            } catch (err) {
               console.error('Failed to parse WebSocket message:', err);
            }
         });

         function showModal_noti() {
            document.getElementById('notificationModal').style.display = 'flex';
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
         }

         function showManageOptions_noti() {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
         }

         function handleReschedule_noti() {
            const appointmentPrimaryId = document.getElementById('appointmentPrimaryId').innerText;
            document.getElementById('notificationModal').style.display = 'none';
            window.location.href = `http://localhost/wellbe/public/patient/reschedule_doc_appointment/${appointmentPrimaryId}`;
         }

         function handleCancel_noti() {
            const appointmentPrimaryId = document.getElementById('appointmentPrimaryId').innerText;
            console.log(appointmentPrimaryId);
            document.getElementById('notificationModal').style.display = 'none';
            alert('Appointment canceled.');
            const userId = <?php echo json_encode($_SESSION['USER']->id); ?>;
            window.location.href = `http://localhost/wellbe/public/patient/refund/${appointmentPrimaryId}`;
         }
      </script>
   <?php
   }
   ?>
</body>

</html>