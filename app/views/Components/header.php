<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administrative Staff</title>
   <style>
      .main-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 20px;
         background-color: #f0f8ff;
         border-bottom: 2px solid #d1d9f1;
      }
      .header-left h1 {
         font-size: 24px;
         color: #172554;
      }
      .header-right {
         display: flex;
         align-items: center;
      }
      .user-details {
         display: flex;
         align-items: center;
         margin-right: 20px;
         margin-left: 20px;
      }
      .user-avatar {
         width: 40px;
         height: 40px;
         border-radius: 50%;
         background-color: #d1d9f1;
         margin-right: 10px;
      }
      .user-info p {
         margin: 0;
         font-size: 14px;
         color: #172554;
      }
      .notification-icon {
         position: relative;
         padding-top: 1px;
         font-size: 28px;
         color: #a0a0a0;
      }
      .notification-badge {
         display: none; /* Hidden by default */
         position: absolute;
         top: 1.5px;
         right: -4px;
         width: 14px;
         height: 14px;
         background-color: red;
         border-radius: 50%;
         border: 2px solid white;
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
            <div class="user-avatar"></div>
            <div class="user-info">
               <p style="font-weight: bold;"><?=$_SESSION['USER']->first_name?></p>
               <p style="color:#989898"><?=$_SESSION['user_type']?></p>
            </div>
         </div>
      </div>
   </header>

   <script>
      // Get the current user's type
      const userType = '<?php echo strtolower($_SESSION['user_type']); ?>';
      let roles;

      // Use switch to determine roles based on user type
      switch (userType) {
         case 'pharmacy':
            roles = '3,5'; // Pharmacy (1) checks admin (3)
            break;
         case 'lab':
            roles = '3,5'; // Lab (2) checks admin (3)
            break;
         case 'admin':
            roles = '1,2,4,5'; // Admin (3) checks pharmacy, lab, patient, doctor
            break;
         case 'patient':
            roles = '3,5'; // Patient (4) checks admin (3)
            break;
         case 'doctor':
            roles = '3,4,1,2'; // Doctor (5) checks admin (3)
            break;
         default:
            roles = '3'; // Default to checking admin if user type is unrecognized
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

      // Initial call
      updateNotificationBadge();

      // Update every 500 milliseconds
      setInterval(updateNotificationBadge, 500);
   </script>
</body>
</html>